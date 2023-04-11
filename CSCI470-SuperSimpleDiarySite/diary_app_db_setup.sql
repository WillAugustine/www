-- Author: Will Augustine
--      Description: MySQL script used to create all necessary tables in the diaryappdb database
--      Usage: After logging into mysql command line, run the following command:
--          'source <path where script is located>\diary_app_db_setup.sql'


-- CREATE DATABASE IF NOT EXISTS `diaryappdb`;

-- START TRANSACTION;

--     CREATE USER diaryappdbuser@localhost IDENTIFIED BY 'DiaryPass$';

-- COMMIT;

GRANT ALL PRIVILEGES ON diaryappdb.* TO diaryappdbuser@localhost;
FLUSH PRIVILEGES;

USE `diaryappdb`;

START TRANSACTION;

    CREATE TABLE IF NOT EXISTS `tbl_users` (
        `username` VARCHAR(255) UNIQUE NOT NULL,
        `password` VARCHAR(40) NOT NULL,
        `first_name` VARCHAR(40) NOT NULL,
        `middle_initial` CHAR(1),
        `last_name` VARCHAR(40) NOT NULL,
        `last_successful_logon` DATETIME DEFAULT NULL,
        `last_unsuccessful_logon` DATETIME DEFAULT NULL,
        `num_logons` INT(5) DEFAULT 0,
        PRIMARY KEY (`username`)     
    );

COMMIT;

START TRANSACTION;

    CREATE TABLE IF NOT EXISTS `tbl_logon_attempts` (
        `username` VARCHAR(255) NOT NULL,
        `attempt_datetime` DATETIME NOT NULL,
        `status` ENUM('SUCCESS', 'FAILURE'),
        `ipaddress` VARCHAR(39),
        `user_agent` VARCHAR(255),
        FOREIGN KEY (`username`) REFERENCES `tbl_users`(`username`)
    );

COMMIT;

START TRANSACTION;

    CREATE TABLE IF NOT EXISTS `tbl_diary_entries` (
        `username` VARCHAR(255) NOT NULL,
        `entry_datetime` DATETIME NOT NULL,
        `entry` VARCHAR(255) NOT NULL,
        FOREIGN KEY (`username`) REFERENCES `tbl_users`(`username`)
    );

COMMIT;