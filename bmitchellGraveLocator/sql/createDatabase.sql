/*
 * Name:    Brandon Mitchell
 * Description: Creates the database bmitchellCemeteryProject to use for the 
 *              project, the tables associated with it, and the user.
 */



DROP DATABASE IF EXISTS bmitchellCemeteryProject;

CREATE DATABASE IF NOT EXISTS bmitchellCemeteryProject;

-- Database already exists, select it so the table is made in the right place
USE bmitchellCemeteryProject;

CREATE TABLE IF NOT EXISTS users
(
    userID int NOT NULL AUTO_INCREMENT,
    username varchar(20) NOT NULL UNIQUE, 
    password varchar(255) NOT NULL,
    PRIMARY KEY(userID)
) ENGINE = InnoDB;

CREATE TABLE IF NOT EXISTS blocks
(
    blockID int NOT NULL,
    
    -- Probably don't need double, but might as well to be safe
    SELat double NOT NULL,
    SELong double NOT NULL,
    SWLat double NOT NULL,
    SWLong double NOT NULL,
    NELat double NOT NULL,
    NELong double NOT NULL,
    NWLat double NOT NULL,
    NWLong double NOT NULL,
    centerLat double NOT NULL,
    centerLong double NOT NULL,
    PRIMARY KEY(blockID)
) ENGINE = InnoDB;

CREATE TABLE IF NOT EXISTS blockRecords
(
    blockRecordID int NOT NULL AUTO_INCREMENT,
    imageFileName varchar(50) NOT NULL UNIQUE,
    
    -- In case there is a record with no associated block for some reason
    blockID int NULL,
    PRIMARY KEY(blockRecordID),
    FOREIGN KEY(blockID) REFERENCES blocks(blockID)
) ENGINE = InnoDB;

CREATE TABLE IF NOT EXISTS searches
(
    searchID int NOT NULL AUTO_INCREMENT,
    name varchar(40) NOT NULL,
    blockRecordID int NOT NULL,
    lot int NULL,
    plot int NULL,
    PRIMARY KEY(searchID),
    FOREIGN KEY(blockRecordID) REFERENCES blockRecords(blockRecordID)
) ENGINE = InnoDB;

CREATE TABLE IF NOT EXISTS feedback
(
    feedbackID int NOT NULL AUTO_INCREMENT,
    searchID int NOT NULL UNIQUE,
    question1 varchar(3) NULL,
    question2 varchar(3) NULL,
    question3 varchar(3) NULL,
    comments varchar(400) NULL,
    PRIMARY KEY(feedbackID),
    FOREIGN KEY(searchID) REFERENCES searches(searchID)
) ENGINE = InnoDB;

DROP USER IF EXISTS 'bmitchellCemeteryUser'@'localhost';

CREATE USER IF NOT EXISTS 'bmitchellCemeteryUser'@'localhost' IDENTIFIED BY 'WebScienceCemetery';

-- Ensure user has no privileges before we start adding them
REVOKE ALL PRIVILEGES, GRANT OPTION FROM 'bmitchellCemeteryUser'@'localhost';

-- Limit user's privileges to what is strictly necessary
GRANT SELECT ON bmitchellCemeteryProject.* TO 'bmitchellCemeteryUser'@'localhost';
GRANT INSERT ON bmitchellCemeteryProject.searches TO 'bmitchellCemeteryUser'@'localhost';
GRANT INSERT ON bmitchellCemeteryProject.feedback TO 'bmitchellCemeteryUser'@'localhost';