CREATE DATABASE IF NOT EXISTS CemeteryLocatorApplication;

CREATE USER ButteArchives@localhost IDENTIFIED BY 'password';
GRANT ALL PRIVILEGES ON CemeteryLocatorApplication.* TO ButteArchives@localhost;
FLUSH PRIVILEGES;


USE CemeteryLocatorApplication;

START TRANSACTION;

    CREATE TABLE `AuthorizedUsers` (
        `username` VARCHAR(255) NOT NULL UNIQUE,
        `password` VARCHAR(40) NOT NULL
    );

    ALTER TABLE `AuthorizedUsers`
        ADD PRIMARY KEY (`username`);

    INSERT INTO `AuthorizedUsers` (`username`, `password`)
        VALUES
        ('ButteArchives', sha1('SaintPatrick'));

COMMIT;

START TRANSACTION;

    CREATE TABLE `BlockCoordinates` (
        `block` VARCHAR(255) NOT NULL UNIQUE,
        `SE_lat` DECIMAL(11, 8),
        `SE_long` DECIMAL(11, 8),
        `NW_lat` DECIMAL(11, 8),
        `NW_long` DECIMAL(11, 8)
    );

COMMIT;

START TRANSACTION;

    CREATE TABLE `Highlights` (
        `ID` INT NOT NULL UNIQUE AUTO_INCREMENT,
        `maxX` DECIMAL(8, 4),
        `minX` DECIMAL(8, 4),
        `maxY` DECIMAL(8, 4),
        `minY` DECIMAL(8, 4),
        `imageWidth` DECIMAL(8, 4)
    );

COMMIT;

START TRANSACTION;

    CREATE TABLE `ButteArchivesRecords` (
        `ID` INT(4) NOT NULL UNIQUE AUTO_INCREMENT,
        `block` VARCHAR(255) NOT NULL,
        `lot` INT(4) NOT NULL,
        `plot` INT(4) NOT NULL,
        `name` VARCHAR(255) NOT NULL,
        `dateOfDeath` DATE,
        `age` VARCHAR(8),
        `undertaker` VARCHAR(255),
        `blockImagePath` VARCHAR(255),
        `highlightID` INT,
        `coordinateID` INT,
        FOREIGN KEY (`highlightID`) REFERENCES `Highlights`(`ID`),
        FOREIGN KEY (`block`) REFERENCES `BlockCoordinates`(`block`)
    );

COMMIT;

START TRANSACTION;

    CREATE TABLE `HeadstonesForLinks` (
        `userLink` VARCHAR(65) NOT NULL UNIQUE,
        `headstoneID_1` INT(4),
        `headstoneID_2` INT(4),
        `headstoneID_3` INT(4),
        `headstoneID_4` INT(4),
        `headstoneID_5` INT(4),
        FOREIGN KEY (`headstoneID_1`) REFERENCES `ButteArchivesRecords`(`ID`),
        FOREIGN KEY (`headstoneID_2`) REFERENCES `ButteArchivesRecords`(`ID`),
        FOREIGN KEY (`headstoneID_3`) REFERENCES `ButteArchivesRecords`(`ID`),
        FOREIGN KEY (`headstoneID_4`) REFERENCES `ButteArchivesRecords`(`ID`),
        FOREIGN KEY (`headstoneID_5`) REFERENCES `ButteArchivesRecords`(`ID`)
    );

COMMIT;

START TRANSACTION;

    CREATE TABLE `Users` (
        `ID` INT(4) NOT NULL UNIQUE AUTO_INCREMENT,
        `firstName` VARCHAR(45) NOT NULL,
        `lastName` VARCHAR(45) NOT NULL,
        `email` VARCHAR(255),
        `dateOfVisit` DATE,
        `uniqueLink` VARCHAR(65) NOT NULL,
        PRIMARY KEY (`ID`),
        FOREIGN KEY (`uniqueLink`) REFERENCES `HeadstonesForLinks`(`userLink`)
    );

COMMIT;

START TRANSACTION;

    CREATE TABLE `Feedback` (
        `userID` INT(4) NOT NULL,
        `headstoneFound` BOOLEAN,
        `recommend` BOOLEAN,
        `useAgain` BOOLEAN,
        `comments` LONGTEXT,
        FOREIGN KEY (`userID`) REFERENCES `Users`(`ID`)
    );

COMMIT;