<?php

namespace leggettc18\SimpleRouter;

use Exception;

class Request {

    /**
     * Returns the currently requested URI
     * 
     * @static
     * @return string
     */
    public static function uri() {

        return trim(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH), '/');

    }

    /**
     * Returns the currently requested method
     * 
     * @static
     * @return string 
     */
    public static function method() {
        return $_SERVER['REQUEST_METHOD'];
    }
}