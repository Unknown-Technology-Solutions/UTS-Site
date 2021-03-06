CREATE DATABASE IF NOT EXISTS uts_modern_v1;

GRANT ALL privileges ON uts_modern_v1.* TO 'modern'@localhost;
GRANT ALL privileges ON uts_modern_v1.* TO 'modern'@'127.0.0.1';
FLUSH PRIVILEGES;

use uts_modern_v1;

DROP TABLE IF EXISTS customer_requests;
DROP TABLE IF EXISTS users;

CREATE TABLE customer_requests (
	id          INT(11) UNSIGNED AUTO_INCREMENT NOT NULL PRIMARY KEY,
	first_name  VARCHAR(50) NOT NULL,
	last_name   VARCHAR(50) NOT NULL,
	company     VARCHAR(50) DEFAULT NULL,
	request     VARCHAR(2048) NOT NULL,
	email       VARCHAR(50) NOT NULL,
	submit_time DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
	CONSTRAINT unique_id UNIQUE (id)
);

CREATE TABLE users (
	uid       INT(11) UNSIGNED AUTO_INCREMENT NOT NULL PRIMARY KEY,
	username  VARCHAR(255) NOT NULL UNIQUE,
	password  VARCHAR(255) NOT NULL,
	firstName VARCHAR(255) NOT NULL,
	lastName  VARCHAR(255) NOT NULL,
	company   VARCHAR(255) DEFAULT NULL,
	lastLogin DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
	CONSTRAINT unique_uid UNIQUE (uid)
);

INSERT INTO users (username, password, firstName, lastName, company) VALUES ('test', 'pass', 'John', 'Doe', 'Unknown Technology Solutions');