<?php

namespace leggettc18\SimpleORM;

use Exception;
use InvalidArgumentException;
use PDO;
use ReflectionClass;

/**
 * Simple ORM base class.
 * 
 * Copied by Christopher Leggett from 
 * roaatech\php-simple-orm with some edits.
 * 
 * @package leggettc18\SimpleORM
 * @abstract
 * @author      Alex Joyce <im@alex-joyce.com>
 * @author      Muhannad Shelleh <muhannad.shelleh@live.com>
 * @author      Christopher Leggett <chris@leggett.dev>
 */
abstract class DataModel {

    /** @var PDO db connection object */
    protected static $conn;

    /** @var string name of table */
    protected static $tableName;

    /** @var string name of pk column */
    protected static $pkColumn;

    /** @var string name of created at column timestamp */
    protected static $createdAtColumn;

    /** @var string name of updated at column timestamp */
    protected static $updatedAtColumn;

    /** @var boolean true to disable insert/update/delete */
    protected static $readOnly = false;

    /** @var array default values (used on object instantiation) */
    protected static $defaultValues = [];

    /** @var mixed internally used */
    protected $reflectionObject;

    /** @var string method used to load the object */
    protected $loadMethod;

    /** @var mixed initial data loaded on object instatiation */
    protected $loadData;

    /** @var array history of object fields modifications */
    protected $modifiedFields = [];

    /** @var boolean is the object new (not persisted in db) */
    protected $isNew = false;

    /** @var boolean to ignore pk value on update */
    protected $ignoreKeyOnUpdate = true;

    /** @var boolean to ignore pk value on insert */
    protected $ignoreKeyOnInsert = true;

    /** @var array the data loaded/to-load to db */
    protected $data = [];

    /** @var array the loaded from database after filtration */
    protected $filteredData = [];

    /** @var mixed value of the pk (unique id of the object) */
    protected $pkValue;

    /** @var boolean internal flag to identify whether to run the input filters or not */
    protected $inSetTransaction = false;

    /**
     * ER Fine Tuning
     */
    const FILTER_IN_PREFIX = 'filterIn';
    const FILTER_OUT_PREFIX = 'filterOut';

    /**
     * Loading options.
     */
    const LOAD_BY_PK = 1;
    const LOAD_BY_ARRAY = 2;
    const LOAD_NEW = 3;
    const LOAD_EMPTY = 4;

    /**
     * Fetch options:
     * FIELD: only first field of first record
     * ONE: Fetch & return one record only.
     * MANY: Fetch multiple records.
     * NONE: Don't fetch.
     */
    const FETCH_ONE = 1;
    const FETCH_MANY = 2;
    const FETCH_NONE = 3;
    const FETCH_FIELD = 4;

    /**
     * Constructor.
     * 
     * @access public
     * @param mixed $data
     * @param integer $method
     * @return void
     */
    public function __construct($data = null, $method = self::LOAD_EMPTY) {
        // store raw data
        $this->loadData = $data;
        $this->loadMethod = $method;

        // load our data
        switch ($method) {
            case self::LOAD_BY_PK:
                $this->loadByPK();
                break;
            
            case self::LOAD_BY_ARRAY:
                $this->hydrateEmpty();
                $this->loadByArray();
                break;

            case self::LOAD_NEW:
                $this->hydrateEmpty();
                $this->loadByArray();
                $this->insert();
                break;

            case self::LOAD_EMPTY:
                $this->hydrateEmpty();
                break;
        }

        $this->initialise();
    }

    /**
     * Give the class a connection to play with.
     * 
     * @access public
     * @static
     * @param PDO $conn PDO connection instance.
     * @param string $database
     * @return void
     */
    public static function useConnection(PDO $conn) {
        static::$conn = $conn;
    }

    public static function createConnection($host, $username, $password, $database, array $options = [], $port = 3306, $charset = null) {
        $dsn = "mysql:dbname={$database};host={$host};port={$port}";
        static::$conn = new PDO($dsn, $username, $password, $options);
    }

    /**
     * Get our connection instance.
     * 
     * @access public
     * @static
     * @return PDO
     */
    public static function getConnection() {
        return static::$conn;
    }

    /**
     * Get load method.
     * 
     * @access public
     * @return integer
     */
    public function getLoadMethod() {
        return $this->loadMethod;
    }

    /**
     * Get load data (raw).
     * 
     * @access public
     * @return array
     */
    public function getLoadData() {
        return $this->loadData;
    }

    /**
     * Load ER by Primary Key
     * 
     * @access protected
     * @return void
     */
    protected function loadByPK() {
        // populate PK
        $this->pkValue = $this->loadData;

        // load data
        $this->hydrateFromDatabase();
    }

    /**
     * Load ER by array hydration.
     * 
     * @access protected
     * @return void
     */
    protected function loadByArray() {
        // set our data
        foreach ($this->loadData AS $key => $value) {
            $this->data[$key] = $value;
        }
        // extract columns
        $this->executeOutputFilters();
    }

    /**
     * Hydrate the object with null or default values.
     * Fetches column names use DESCRIBE.
     * 
     * @access protected
     * @return void
     */
    protected function hydrateEmpty() {

        $defaults = static::$defaultValues ? static::$defaultValues : [];

        foreach ($this->getColumnNames() AS $field) {
            $this->data[$field] = array_key_exists($field, $defaults) ? $defaults[$field] : null;
        }

        // mark object as new
        $this->isNew = true;
    }

    /**
     * Fetch the data from the database.
     * 
     * @access protected
     * @throws Exception If the record is not found
     * @return void
     */
    protected function hydrateFromDatabase() {
        $sql = sprintf("SELECT * FROM `%s` WHERE `%s` = '%s';", static::getTableName(), static::getTablePk(), $this->id());
        $result = static::getConnection()->query($sql);

        if (!$result || !$result->rowCount()) {
            throw new Exception(sprintf("%s record not found in database. (PK: %s)", get_called_class(), $this->id()), 2);
        }

        foreach ($result->fetch(PDO::FETCH_ASSOC) AS $key => $value) {
            $this->data[$key] = $value;
        }

        unset($result);

        // extract columns
        $this->executeOutputFilters();
    }

    /**
     * Get the table name for this ER class.
     * 
     * @access public
     * @static
     * @return string
     */
    public static function getTableName() {
        return @static::$tableName ? static::$tableName : strtolower(basename(str_replace("\\", DIRECTORY_SEPARATOR, get_called_class())));
    }

    /**
     * Get the PK field name for this ER class.
     * 
     * @access public
     * @static
     * @return string
     */
    public static function getTablePk() {
        return @static::$pkColumn? static::$pkColumn : 'id';
    }

    /**
     * Return the PK for this record.
     * 
     * @access public
     * @return integer
     */
    public function id() {
        return $this->pkValue ? $this->pkValue : (
            array_key_exists(static::getTablePk(), $this-> data) ? $this->data[static::getTablePk()] : null
        );
    }

    /**
     * Check if the current record has just been created in this instance.
     * 
     * @access public
     * @return boolean
     */
    public function isNew() {
        return $this->isNew;
    }

    /**
     * Marks an instance as not new
     * 
     * @access protected
     */
    protected function notNew() {
        $this->isNew = false;
    }

    /**
     * Executed just before any new records are created.
     * Place holder for sub-classes.
     * 
     * @access public
     * @return void
     */
    public function preInsert(array &$data = []) {
        if (static::$createdAtColumn) {
            $data[static::$createdAtColumn] = static::setCurrentTimestampValue();
        }
        if (static::$updatedAtColumn) {
            $data [static::$updatedAtColumn] = static::setCurrentTimestampValue();
        }
    }

    /**
     * Executed just after any new records are created.
     * Place holder for sub-classes.
     * 
     * @access public
     * @return void
     */
    public function postInsert() {

    }

    /**
     * Executed just before any records are deleted.
     * Place holder for sub-classes.
     * 
     * @access public
     * @return void
     */
    public function preDelete() {

    }

    /**
     * Executed just after any records are deleted.
     * Place holder for sub-classes.
     * 
     * @access public
     * @return void
     */
    public function postDelete() {

    }

    /**
     * Executed just before any records are updated.
     * Place holder for sub-classes.
     * 
     * @access public
     * @return void
     */
    public function preUpdate(array &$data = []) {
        if (static::$updatedAtColumn) {
            $data[static::$updatedAtColumn] = static::setCurrentTimestampValue();
        }
    }

    /**
     * Executed just after any records are udpated.
     * Place holder for sub-classes.
     * 
     * @access public
     * @return void
     */
    public function postUpdate() {

    }

    /**
     * Executed just after the record has loaded.
     * Place holder for sub-classes.
     * 
     * @access public
     * @return void
     */
    public function initialise() {

    }

    /**
     * Execute these filters when loading data from the database.
     * 
     * Receives array of data, returns an array of data OR directly change it by reference.
     * 
     * @access protected
     * @return void
     */
    protected function executeOutputFilters() {
        $r = new ReflectionClass(get_class($this));

        $data = $this->data;

        foreach ($r->getMethods() AS $method) {
            if (substr($method->name, 0, strlen(self::FILTER_OUT_PREFIX)) == self::FILTER_OUT_PREFIX) {
                $returnedData = $this->{$method->name}($data);
                $data = (is_array($returnedData) ? $returnedData : []) + $data;
            }
        }

        $this->filteredData = (is_array($data) ? $data : []) + $this->data;
    }

    /**
     * Execute these filters when saving data to the database.
     * 
     * @access protected
     * @return void
     */
    protected function executeInputFilters($array) {
        $r = new ReflectionClass(get_class($this));

        foreach ($r->getMethods() AS $method) {
            if (substr($method->name, 0, strlen(self::FILTER_IN_PREFIX)) == self::FILTER_IN_PREFIX) {
                $array = $this->{$method->name}($array);
            }
        }

        return $array;
    }

    /**
     * Save (insert/update) to the database.
     * 
     * @access public
     * @return $this
     */
    public function save() {
        if ($this->isNew()) {
            $this->insert();
        } else {
            $this->update();
        }
        return $this;
    }

    /**
     * Insert the record
     * 
     * @access protected
     * @throws Exception
     * @return void
     */
    protected function insert() {

        if(static::$readOnly) {
            throw new Exception("Cannot write to READ ONLY tables.");
        }

        $array = $this->getRaw();

        // run pre inserts
        if ($this->preInsert($array) === false) {
            return;
        }

        // input filters
        $array = $this->executeInputFilters($array);

        // remove data not relevant
        $array = array_intersect_key($array, array_flip($this->getColumnNames()));

        // to PK or not to PK
        if ($this->ignoreKeyOnInsert === true) {
            unset($array[static::getTablePk()]);
        }

        // compile statement
        $fieldNames = $fieldMarkers = $types = $values = [];

        foreach ($array AS $key => $value) {
            $fieldNames[] = sprintf('`%s`', $key);
            if (is_object($value) && $value instanceof RawSQL) {
                $fieldMarkers[] = (string) $value;
            } else {
                $fieldMarkers[] = '?';
                $types[] = $this->parseValueType($value);
                $values[] = &$array[$key];
            }
        }

        // build sql statement
        $sql = sprintf("INSERT INTO `%s` (%s) VALUES (%s)", static::getTableName(), implode(', ', $fieldNames), implode(', ', $fieldMarkers));
        
        //prepare, bind & execute
        static::sql($sql, self::FETCH_NONE, array_values($values));

        $lastId = static::getConnection()->listInsertId();

        // set our PK (if exists)
        if ($lastId) {
            $this->pkValue = $lastId;
            $this->data[static::getTablePk()] = $lastId;
        }

        // mark as old
        $this->isNew = false;

        //hydrate
        $this->hydrateFromDatabase($lastId);

        // run post inserts
        $this->postInsert();
    }

    /** 
     * Update the record
     * 
     * @access public
     * @throws Exception
     * @return void
    */
    public function update() {
        if (static::$readOnly) {
            throw new Exception("Cannot write to READ ONLY tables.");
        }

        if ($this->isNew()) {
            return $this->insert();
        }

        $pk = static::getTablePk();
        $id = $this->id();

        $array = $this->getRaw();

        //preupdate
        if ($this->preUpdate($array) === false) {
            return;
        }

        // input filters
        $array = $this->executeInputFilters($array);

        // remove data not relevant
        $array = array_intersect_key($array, array_flip($this->getColumnNames()));

        // to PK or not to PK
        if ($this->ignoreKeyOnUpdate === true) {
            unset($array[$pk]);
        }

        // compile statement
        $fields = $types = $values = [];

        foreach ($array AS $key => $value) {
            if (is_object($value) && $value instanceof RawSQL) {
                $fields[] = sprintf('`%s` = %s', $key, (string) $value);
            } else {
                $fields[] = sprintf('`%s` = ?', $key);
                $types[] = $this->parseValueType($value);
                $values[] = &$array[$key];
            }
        }

        // where
        $types[] = 'i';
        $values[] = &$id;

        // build sql statement
        $sql = sprintf("UPDATE `%s` SET %s WHERE `%s` = ?", static::getTableName(), implode(', ', $fields), $pk);

        // prepare, bind & execute
        static::sql($sql, self::FETCH_NONE, $values);

        // reset modified list
        $this->modifiedFields = [];

        $this->hydrateFromDatabase();

        $this->postUpdate();
    }

    /**
     * Delete the record from the database.
     * 
     * @access public
     * @return void
     */
    public function delete() {
        if (static::$readOnly) {
            throw new Exception("Cannot write to READ ONLY tables.");
        }

        if ($this->isNew()) {
            throw new Exception('Unable to delete object, record is new (and therefore doesn\'t exist in the database).');
        }

        if ($this->preDelete() === false) {
            return;
        }

        // build sql statement
        $sql = sprintf("DELETE FROM `%s` = ?", static::getTableName(), static::getTablePk());
        $id = $this->id();

        // prepare, bind & execute
        static::sql($sql, self::FETCH_NONE, [$id]);

        $this->postDelete();
    }

    /**
     * Fetch column names directly from MySQL.
     * 
     * @access public
     * @return array
     */
    public static function getColumnNames() {
        $conn = static::getConnection();
        $result = $conn->query(sprintf("DESCRIBE %s;", static::getTableName()));

        if ($result === false) {
            throw new Exception(sprintf('Unable to fetch the column names. %s.', $conn->errorCode()));
        }

        $ret = [];

        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            $ret[] = $row['Field'];
        }

        $result->closeCursor();

        return $ret;
    }

    /**
     * Revert the object by reloading our data.
     * 
     * @access public
     * @param boolean $return if true teh current object won't be reverted, it will return a new object via cloning.
     * @return void | clone
     */
    public function revert($return = false) {
        if ($return) {
            $ret = clone $this;
            $ret->revert();

            return $ret;
        }

        $this->hydrateFromDatabase();
    }

    /**
     * Get a value for a particular field or all values.
     * 
     * @access public
     * @param string $fieldName If false (default), the entire record will be returned as an array.
     * @return array | string
     */
    public function get($fieldName = false) {
        //return all data
        if ($fieldName === false) {
            return $this->filteredData;
        }

        return array_key_exists($fieldName, $this->filteredData) ? $this->filteredData[$fieldName] : (
                array_key_exists($fieldName, $this->data) ? $this->data[$fieldName] : $this->{fieldName}
            );
    }

    public function getRaw($fieldName = false) {
        // return all data
        if ($fieldName === false) {
            return $this->data;
        }

        return array_key_exists($fieldName, $this->data) ? $this->data[$fieldName] : $this->{$fieldName};
    }

    /**
     * Set a new value for a particular field.
     * 
     * @access public
     * @param string|array $fieldName list of key=>values OR a key name
     * @param string $newValue if $dataMapOrFieldName is a key name, this will be the value
     * @return void
     */
    public function set($fieldName, $newValue = null) {
        if (is_array($fieldName)) {
            $this->inSetTransaction = true;
            foreach ($fieldname as $key => $value) {
                $this->set($key, $value);
            }
            $this->data = $this->executeInputFilters($this->data);
            $this->executeOutputFilters();
            $this->inSetTransaction = false;
        } elseif (is_scalar($fieldName)) {
            // if changed, mark object as modified
            if ($this->get($fieldName) != $newValue) {
                $this->modifiedFields($fieldName, $newValue);
            }
            $this->data[$fieldName] = $newValue;
            if (!$this->inSetTransaction) {
                $this->data = $this->executeInputFilters($this->data);
                $this->executeOutputFilters();
            }
        }
        return $this;
    }

    /**
     * Check if our record has been modified since boot up.
     * This is only available if you use set() to change the object.
     * 
     * @access public
     * @return array | false
     */
    public function isModified() {
        return count($this->modifiedFields) > 0;
    }

    /**
     * Modification history of all fields, or null if nothing is changed since load
     * 
     * @access public
     * @return null|array
     */
    public function modified() {
        return $this->isModified() ? $this->modifiedFields : null;
    }

    /**
     * Mark a field as modified & add the change to our history.
     * 
     * @access protected
     * @param string $fieldName
     * @param string $newValue
     * @return void
     */
    protected function modifiedFields($fieldName, $newValue) {
        // add modified field to a list
        if (!isset($this->modifiedFields[$fieldName])) {
            $this->modifiedFields[$fieldName] = $newValue;
            
            return;
        }

        // already modified, initiate a numerical array
        if (!is_array($this->modifiedFields[$fieldName])) {
            $this->modifiedFields[$fieldName] = [$this->modifiedFields[$fieldName]];
        }

        // add new change to array
        $this->modifiedFields[$fieldName][] = $newValue;
    }

    /**
     * Execute an SQL statement & get all records as hydrated objects.
     * 
     * @access public
     * @param string $sql
     * @param integer $return
     * @param array|null $params parameters to bind
     * @return mixed
     */
    public static function sql($sql, $return = self::FETCH_MANY, array $params = null) {
        // shortcuts
        $sql = str_replace([':table', ':pk'], [static::getTableName(), static::getTablePk()], $sql);

        // prepare
        $stmt = static::getConnection()->prepare($sql);
        if(!$stmt || !$stmt->execute($params)) {
            throw new Exception(sprintf('Unable to execute SQL statement. %s', static::getConnection()->errorCode()));
        }

        if ($return === self::FETCH_NONE) {
            return;
        }

        $ret = [];

        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $obj = $return == self::FETCH_FIELD ? $row : call_user_func_array([get_called_class(), 'hydrate'], [$row, false]);
            $ret[] = $obj;
        }

        $stmt->closeCursor();

        // return one if requested
        if ($return === self::FETCH_ONE || $return === self::FETCH_FIELD) {
            $ret = isset($ret[0]) ? $ret[0] : null;
        }

        if ($return === self::FETCH_FIELD && $ret) {
            $data = $ret instanceof DataModel ? $ret->get() : $ret;
            $ret = array_values($ret)[0];
        }

        return $ret;
    }

    /**
     * Execute a Count SQL statement & return the number.
     * 
     * @access public
     * @param string $sql
     * @param integer $return
     * @return mixed
     */
    public static function count($sql = "SELECT count(*) FROM :table") {
        $count = (int) (static::sql($sql, self::FETCH_FIELD));

        return $count > 0 ? $count : 0;
    }

    /**
     * Truncate the table
     * All data will be removed permanently.
     * 
     * @access public
     * @static
     * @return void
     */
    public static function truncate() {
        if (static::$readOnly) {
            throw new Exception("Cannot write to READ ONLY tables.");
        }

        static::sql('TRUNCATE :table', self::FETCH_NONE);
    }

    /**
     * Get all records.
     * 
     * @access public
     * @return array
     */
    public static function all() {
        return static::sql("SELECT * FROM :table");
    }

    /**
     * Retrieve a record by its primary key (PK).
     * 
     * @access public
     * @param integer|string $pk
     * @return mixed|static|$this|DataModel|static|object
     */
    public static function retrieveByPK($pk) {
        if (!is_numeric($pk) && !is_string($pk)) {
            throw new InvalidArgumentException('The PK must be an integer or string.');
        }

        return new static($pk, self::LOAD_BY_PK);
    }

    /**
     * Load an ER object by array.
     * This skips reloading the data from the database.
     * 
     * @access public
     * @param array $data
     * @param boolean $asNew
     * @return object
     */
    public static function hydrate(array $data, $asNew = true) {
        $reflectionObj = new ReflectionClass(get_called_class());

        $instance = $reflectionObj->newInstanceArgs([$data, self::LOAD_BY_ARRAY]);

        if (!$asNew) {
            $instance->notNew();
        }

        return $instance;
    }

    /**
     * Retrieve a record by a particular column name using the retrieveBy prefix.
     * e.g.
     * 1) Foo::retrieveByTitle('Hello World') is equal to Foo::retrieveByField('title', 'HelloWorld');
     * 2) Foo::retrieveByIsPublic(true) is equal to Foo::retrieveByField('is_public', true);
     * 
     * @access public
     * @static
     * @param string $name
     * @param array $args
     * @return mixed
     */
    public static function __callStatic($name, $args) {
        $class = get_called_class();

        if (substr($name, 0, 10) == 'retrieveBy') {
            // prepend field name to args
            $field = strtolower(preg_replace('/\B([A-Z])/', '_${1}', substr($name, 10)));
            array_unshift($args, $field);

            return call_user_func_array([$class, 'retrieveByField'], $args);
        }

        throw new Exception(sprintf('There is no static method named "%s" in the class "%s".', $name, $class));
    }

    /**
     * Retrieve a record by a particular column name.
     * 
     * @access public
     * @static
     * @param string $field
     * @param mixed $value
     * @param integer $return
     * @return mixed|static|$this|DataModel|static
     */
    public static function retrieveByField($field, $value, $return = self::FETCH_MANY) {
        if (!is_string($field))
            throw new InvalidArgumentException('The field name must be a string.');
        
        // build our query
        $operator = (strpos($value, '%') === false) ? '=' : 'LIKE';
        
        $sql = sprintf("SELECT * FROM :table WHERE %s %s '%s'", $field, $operator, $value);

        if ($return === self::FETCH_ONE) {
            $sql .= ' LIMIT 0,1';
        }

        // fetch our records
        return static::sql($sql, $return);
    }

    protected static function setCurrentTimestampValue() {
        return RawSQL::make('CURRENT_TIMESTAMP');
    }

    public function __get($name) {
        return $this->get($name);
    }

    public function __set($name, $value) {
        if (array_key_exists($name, $this->data)) {
            $this->set($name, $value);
            return;
        }
        throw new Exception(sprintf("Can not set property %s", $name));
    }

}