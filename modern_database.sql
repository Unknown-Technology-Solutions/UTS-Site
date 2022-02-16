CREATE DATABASE IF NOT EXISTS uts_modern_v1;

/*GRANT ALL privileges ON uts_modern_v1.* TO 'modern'@localhost;
GRANT ALL privileges ON uts_modern_v1.* TO 'modern'@'127.0.0.1';
FLUSH PRIVILEGES;*/

use uts_modern_v1;

/*DROP TABLE IF EXISTS customer_requests;
DROP TABLE IF EXISTS users;*/

/*CREATE TABLE customer_requests (
	id          INT(11) UNSIGNED AUTO_INCREMENT NOT NULL PRIMARY KEY,
	first_name  VARCHAR(50) NOT NULL,
	last_name   VARCHAR(50) NOT NULL,
	company     VARCHAR(50) DEFAULT NULL,
	request     VARCHAR(2048) NOT NULL,
	email       VARCHAR(50) NOT NULL,
	submit_time DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
	CONSTRAINT unique_id UNIQUE (id)
);*/

/*CREATE TABLE users (
	uid       INT(11) UNSIGNED AUTO_INCREMENT NOT NULL PRIMARY KEY,
	username  VARCHAR(255) NOT NULL UNIQUE,
	password  VARCHAR(255) NOT NULL,
	firstName VARCHAR(255) NOT NULL,
	lastName  VARCHAR(255) NOT NULL,
	company   VARCHAR(255) DEFAULT NULL,
	lastLogin DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
	CONSTRAINT unique_uid UNIQUE (uid)
);*/

CREATE TABLE IF NOT EXISTS `customer_requests` (
	`id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
	`first_name` VARCHAR(50) NOT NULL COLLATE 'utf8_general_ci',
	`last_name` VARCHAR(50) NOT NULL COLLATE 'utf8_general_ci',
	`company` VARCHAR(50) NULL DEFAULT NULL COLLATE 'utf8_general_ci',
	`request` VARCHAR(2048) NOT NULL COLLATE 'utf8_general_ci',
	`email` VARCHAR(50) NOT NULL COLLATE 'utf8_general_ci',
	`submit_time` DATETIME NOT NULL DEFAULT current_timestamp(),
	`completed` VARCHAR(5) NOT NULL DEFAULT 'false' COLLATE 'utf8_general_ci',
	PRIMARY KEY (`id`) USING BTREE,
	UNIQUE INDEX `unique_id` (`id`) USING BTREE
)
COLLATE='utf8_general_ci'
ENGINE=INNODB
;


CREATE TABLE IF NOT EXISTS `acct_types` (
	`id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
	`name` VARCHAR(50) NOT NULL DEFAULT 'Name' COLLATE 'utf8_general_ci',
	`description` VARCHAR(512) NOT NULL DEFAULT 'Description' COLLATE 'utf8_general_ci',
	PRIMARY KEY (`id`) USING BTREE,
	UNIQUE INDEX `id` (`id`) USING BTREE,
	INDEX `name` (`name`) USING BTREE
)
COLLATE='utf8_general_ci'
ENGINE=InnoDB
;

CREATE TABLE IF NOT EXISTS `charge_types` (
	`id` INT(11) NOT NULL AUTO_INCREMENT,
	`name` VARCHAR(50) NOT NULL DEFAULT 'Name' COLLATE 'utf8_general_ci',
	`description` VARCHAR(512) NOT NULL DEFAULT 'Description' COLLATE 'utf8_general_ci',
	`standard` VARCHAR(5) NOT NULL DEFAULT 'false' COLLATE 'utf8_general_ci',
	`price_monthly` FLOAT NOT NULL DEFAULT '0',
	PRIMARY KEY (`id`) USING BTREE,
	UNIQUE INDEX `id` (`id`) USING BTREE,
	INDEX `name` (`name`) USING BTREE
)
COLLATE='utf8_general_ci'
ENGINE=InnoDB
;

CREATE TABLE IF NOT EXISTS `customer_records` (
	`id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
	`first_name` VARCHAR(50) NOT NULL DEFAULT 'First' COLLATE 'utf8_general_ci',
	`last_name` VARCHAR(50) NOT NULL DEFAULT 'Last' COLLATE 'utf8_general_ci',
	`company` VARCHAR(50) NOT NULL DEFAULT 'Company' COLLATE 'utf8_general_ci',
	`acct_type` VARCHAR(50) NOT NULL DEFAULT '0' COLLATE 'utf8_general_ci',
	`notes` VARCHAR(4096) NOT NULL DEFAULT 'No notes on file.' COLLATE 'utf8_general_ci',
	`charges` VARCHAR(50) NOT NULL DEFAULT '0' COLLATE 'utf8_general_ci',
	PRIMARY KEY (`id`) USING BTREE,
	UNIQUE INDEX `id` (`id`) USING BTREE,
	INDEX `acct_type` (`acct_type`) USING BTREE,
	INDEX `charges` (`charges`) USING BTREE,
	CONSTRAINT `acct_type_FK1` FOREIGN KEY (`acct_type`) REFERENCES `uts_modern_v1`.`acct_types` (`name`) ON UPDATE CASCADE ON DELETE NO ACTION,
	CONSTRAINT `charges_FK2` FOREIGN KEY (`charges`) REFERENCES `uts_modern_v1`.`charge_types` (`name`) ON UPDATE CASCADE ON DELETE NO ACTION
)
COLLATE='utf8_general_ci'
ENGINE=InnoDB
;

CREATE TABLE IF NOT EXISTS `sec_reports` (
	`id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
	`name` VARCHAR(50) NOT NULL DEFAULT 'Name' COLLATE 'utf8_general_ci',
	`service` VARCHAR(50) NOT NULL DEFAULT 'Service' COLLATE 'utf8_general_ci',
	`description` VARCHAR(2048) NOT NULL DEFAULT 'Service' COLLATE 'utf8_general_ci',
	`date_disclosed` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP(),
	PRIMARY KEY (`id`) USING BTREE,
	UNIQUE INDEX `id` (`id`) USING BTREE
)
COLLATE='utf8_general_ci'
ENGINE=InnoDB
;
