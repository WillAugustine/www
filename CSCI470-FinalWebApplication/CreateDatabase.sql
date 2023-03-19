CREATE DATABASE IF NOT EXISTS CemeteryLocatorApplication;

USE CemeteryLocatorApplication;

START TRANSACTION;

    CREATE TABLE `authorized_users` (
        `username` VARCHAR(255) NOT NULL UNIQUE,
        `password` VARCHAR(40) NOT NULL
    );

    ALTER TABLE `authorized_users`
        ADD PRIMARY KEY (`username`);

    INSERT INTO `authorized_users` (`username`, `password`)
        VALUES
        ('ButteArchives', sha1('SaintPatrick'));

COMMIT;

START TRANSACTION;

    CREATE TABLE `block_data` (
        `number` INT(3) NOT NULL UNIQUE,
        `SE_lat` DECIMAL(11, 8),
        `SE_long` DECIMAL(11, 8),
        `SW_lat` DECIMAL(11, 8),
        `SW_long` DECIMAL(11, 8),
        `NE_lat` DECIMAL(11, 8),
        `NE_long` DECIMAL(11, 8),
        `NW_lat` DECIMAL(11, 8),
        `NW_long` DECIMAL(11, 8)
    );

    LOAD DATA LOCAL INFILE 'data\BlockCorners_Modified.csv'
        INTO TABLE `block_data`
        FIELDS TERMINATED BY ','
        ENCLOSED BY ""
        LINES TERMINATED BY '\n'
        IGNORE 1 ROWS;

COMMIT;

START TRANSACTION;

    CREATE TABLE `ButteArchivesRecords` (
        `ID` INT(4) NOT NULL UNIQUE,
        `block` INT(4) NOT NULL,
        `lot` INT(4) NOT NULL,
        `plot` INT(4) NOT NULL,
        `name` VARCHAR(255) NOT NULL,
        `dateOfDeath` DATE,
        `age` INT(3),
        `undertaker` VARCHAR(255)
    );

COMMIT;

START TRANSACTION;

    CREATE TABLE `Users` (
        `ID` INT(4) NOT NULL UNIQUE,
        `firstName` VARCHAR(45) NOT NULL,
        `lastName` VARCHAR(45) NOT NULL,
        `email` VARCHAR(255),
        `dateOfVisit` DATE,
        `uniqueLink` VARCHAR(65) NOT NULL
    );

COMMIT;

START TRANSACTION;

    CREATE TABLE `Feedback` (
        `userID` INT(4) NOT NULL,
        `headstoneFound` BOOLEAN,
        `Comments` LONGTEXT
    );

COMMIT;