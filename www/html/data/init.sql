/**
 * Database creation script
 */

DROP TABLE IF EXISTS comment;
DROP TABLE IF EXISTS post;
DROP TABLE IF EXISTS user;

CREATE TABLE user (
    id INTEGER NOT NULL PRIMARY KEY AUTO_INCREMENT,
    username VARCHAR(30) NOT NULL,
    password VARCHAR(255) NOT NULL,
    created_at DATETIME NOT NULL,
    is_enabled BOOLEAN NOT NULL DEFAULT true
);

/* This will become user = 1. I'm creating this just to satisfy constraints here.
   This password will be properly hashed in the installer */

INSERT INTO
    user
    (
        username, password, created_at, is_enabled
    )
    VALUES
    (
        "admin", "unhashed-password", CURDATE() - INTERVAL 3 MONTH, 0
    )
;

DROP TABLE IF EXISTS post;

CREATE TABLE post (
    id INTEGER NOT NULL PRIMARY KEY AUTO_INCREMENT,
    title VARCHAR(30) NOT NULL,
    body VARCHAR(10000) NOT NULL,
    user_id INTEGER NOT NULL,
    created_at DATETIME NOT NULL,
    updated_at DATETIME,
    FOREIGN KEY (user_id) REFERENCES user(id)
);

INSERT INTO
    post
    (
        title, body, user_id, created_at
    )
    VALUES(
        "Here's our first post",
        "This is the body of the first post.

It is split into paragraphs.",
        1,
        CURDATE() - INTERVAL 2 MONTH - INTERVAL 45 MINUTE + INTERVAL 10 SECOND
    )
;

INSERT INTO
    post
    (
        title, body, user_id, created_at
    )
    VALUES(
        "Now for a second article",
        "this is the body of the second post.
This is another paragraph.",
        1,
        CURDATE() - INTERVAL 40 DAY + INTERVAL 815 MINUTE + INTERVAL 37 SECOND
    )
;

INSERT INTO
    post
    (
        title, body, user_id, created_at
    )
    VALUES(
        "Here's a third post",
        "This is the body of the third post.
This is split into paragraphs.",
        1, 
        CURDATE() - INTERVAL 13 DAY + INTERVAL 198 MINUTE + INTERVAL 51 SECOND
    )
;

DROP TABLE IF EXISTS comment;

CREATE TABLE comment (
    id INTEGER NOT NULL PRIMARY KEY AUTO_INCREMENT,
    post_id INTEGER NOT NULL,
    created_at DATETIME NOT NULL,
    name VARCHAR(30) NOT NULL,
    website VARCHAR(100) NOT NULL,
    text VARCHAR(10000) NOT NULL,
    FOREIGN KEY (post_id) REFERENCES post(id)
);

INSERT INTO
    comment
    (
        post_id, created_at, name, website, text
    )
    VALUES(
        1,
        CURDATE() - INTERVAL 10 DAY + INTERVAL 231 MINUTE + INTERVAL 7 SECOND,
        'Jimmy',
        'http://example.com/',
        "This is Jimmy's contribution"
    )
;

INSERT INTO
    comment
    (
        post_id, created_at, name, website, text
    )
    VALUES(
        1, 
        CURDATE() - INTERVAL 8 DAY + INTERVAL 549 MINUTE + INTERVAL 32 SECOND,
        'Jonny',
        'http://anotherexample.com/',
        "This is a comment from Jonny"
    )
;