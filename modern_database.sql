CREATE DATABASE uts_modern;
GRANT ALL privileges ON `uts_modern`.* TO 'modern'@localhost;
FLUSH PRIVILEGES;
use uts_modern;
CREATE TABLE `customer_requests` (
	`id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
	`first_name` VARCHAR(50) NULL DEFAULT NULL,
	`last_name` VARCHAR(50) NULL DEFAULT NULL,
	`company` VARCHAR(50) NULL DEFAULT NULL,
	`request` VARCHAR(50) NULL DEFAULT NULL,
	`email` VARCHAR(50) NULL DEFAULT NULL,
	`submit_time` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
	PRIMARY KEY (`id`)
)
COLLATE='utf8_general_ci'
ENGINE=InnoDB
;
