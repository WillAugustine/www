-- Start a transaction
START TRANSACTION;

-- Create the CemeteryApplication database
START TRANSACTION;

    CREATE DATABASE CemeteryApplication;

COMMIT;

START TRANSACTION;

    CREATE USER 'CemeteryApplication_User'@'localhost' IDENTIFIED BY 'Pa$$word';
    GRANT ALL PRIVILEGES ON CemeteryApplication.* TO 'CemeteryApplication_User'@'localhost';
    FLUSH PRIVILEGES;

COMMIT;

-- Use the CemeteryApplication database
START TRANSACTION;

    USE CemeteryApplication;

COMMIT;

-- Create the Archives User table
START TRANSACTION;

    CREATE TABLE Archives_User (
        id INT AUTO_INCREMENT PRIMARY KEY,
        username VARCHAR(255) NOT NULL,
        password VARCHAR(255) NOT NULL
    );

COMMIT;

START TRANSACTION;

    INSERT INTO Archives_User (username, password)
        VALUES ('ButteArchives', 'password');

COMMIT;

-- Create the Regular Users table
START TRANSACTION;
    
    CREATE TABLE Regular_Users (
        id INT AUTO_INCREMENT PRIMARY KEY,
        link_identifier VARCHAR(255) NOT NULL,
        block_record_image_id INT,
        FOREIGN KEY (block_record_image_id) REFERENCES Block_Record_Image(id)
    );

COMMIT;

-- Create the Block Record Image table
START TRANSACTION;

    CREATE TABLE Block_Record_Image (
        id INT AUTO_INCREMENT PRIMARY KEY,
        block_number VARCHAR(255) NOT NULL,
        image_data BLOB NOT NULL
    );

COMMIT;

-- Create the Search Record table
START TRANSACTION;

    CREATE TABLE Search_Record (
        id INT AUTO_INCREMENT PRIMARY KEY,
        user_id INT NOT NULL,
        name VARCHAR(255) NOT NULL,
        block VARCHAR(255) NOT NULL,
        lot VARCHAR(255) NOT NULL,
        plot VARCHAR(255) NOT NULL,
        search_timestamp TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY (user_id) REFERENCES Archives_User(id)
    );

COMMIT;

-- Create the Search Feedback table
START TRANSACTION;

    CREATE TABLE Search_Feedback (
        id INT AUTO_INCREMENT PRIMARY KEY,
        search_record_id INT NOT NULL,
        success BOOLEAN NOT NULL,
        feedback TEXT,
        FOREIGN KEY (search_record_id) REFERENCES Search_Record(id)
    );

COMMIT;

-- Commit the transaction
COMMIT;
