START TRANSACTION;

    USE CemeteryLocatorApplication;

    DROP TABLE block_data;
    DROP TABLE ButteArchivesRecords;
    DROP TABLE Feedback;
    DROP TABLE Users;
    DROP TABLE authorized_users;
    DROP TABLE HeadstonesForLinks;
    DROP USER ButteArchives@localhost;

COMMIT;