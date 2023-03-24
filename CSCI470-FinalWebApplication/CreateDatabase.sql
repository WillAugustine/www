CREATE DATABASE IF NOT EXISTS CemeteryLocatorApplication;

CREATE USER ButteArchives@localhost IDENTIFIED BY 'password';
GRANT ALL PRIVILEGES ON CemeteryLocatorApplication.* TO ButteArchives@localhost;
FLUSH PRIVILEGES;


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
        `blockID` INT(3) NOT NULL UNIQUE,
        `maxLat` DECIMAL(11, 8),
        `minLat` DECIMAL(11, 8),
        `maxLong` DECIMAL(11, 8),
        `minLong` DECIMAL(11, 8)
    );

    -- LOAD DATA LOCAL INFILE 'data\BlockCorners_Modified.csv'
    --     INTO TABLE `block_data`
    --     FIELDS TERMINATED BY ','
    --     ENCLOSED BY ""
    --     LINES TERMINATED BY '\n'
    --     IGNORE 1 ROWS;

COMMIT;

START TRANSACTION;

    CREATE TABLE `ButteArchivesRecords` (
        `ID` INT(4) NOT NULL UNIQUE AUTO_INCREMENT,
        `block` INT(4) NOT NULL,
        `lot` INT(4) NOT NULL,
        `plot` INT(4) NOT NULL,
        `name` VARCHAR(255) NOT NULL,
        `dateOfDeath` DATE,
        `age` VARCHAR(8),
        `undertaker` VARCHAR(255)
    );

COMMIT;

START TRANSACTION;

    CREATE TABLE `Users` (
        `ID` INT(4) NOT NULL UNIQUE AUTO_INCREMENT,
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

START TRANSACTION;

    CREATE TABLE `HeadstonesForLinks` (
        `userLink` VARCHAR(65) NOT NULL UNIQUE,
        `headstoneID_1` INT(4) NOT NULL,
        `headstoneID_2` INT(4),
        `headstoneID_3` INT(4),
        `headstoneID_4` INT(4),
        `headstoneID_5` INT(4)
    );

COMMIT;