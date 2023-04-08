
-- Author: Will Augustine
-- Description: Creates database UserTableAssignDB and then creates the table tbl_user
--  in that database. If there is no error creating the table, add keys and insert
--  a default entry into the table

CREATE DATABASE IF NOT EXISTS UserTableAssignDB;

USE UserTableAssignDB;

START TRANSACTION;

    CREATE TABLE `tbl_users` (
        `username` VARCHAR(255) NOT NULL UNIQUE,
        `password` VARCHAR(40) NOT NULL,
        `first_name` VARCHAR(40) NOT NULL,
        `middle_name` CHAR(1),
        `last_name` VARCHAR(40) NOT NULL,
        `last_successful_logon` DATETIME DEFAULT NULL,
        `last_unsuccessful_logon` DATETIME DEFAULT NULL,
        `num_logons` INT(5) DEFAULT 0
    );

    ALTER TABLE `tbl_users`
        ADD PRIMARY KEY (`username`),
        ADD KEY `last_name` (`last_name`(20));

    INSERT INTO `tbl_users` (`username`, `password`, `first_name`, `middle_name`, `last_name`)
        VALUES
        ('waugustine', sha1('MiloMan7'), 'William', 'R', 'Augustine');

COMMIT;

