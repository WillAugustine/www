START TRANSACTION;

    USE CemeteryLocatorApplication;

    SET FOREIGN_KEY_CHECKS=0;

    DROP TABLE BlockData;
    DROP TABLE ButteArchivesRecords;
    DROP TABLE Feedback;
    DROP TABLE Users;
    DROP TABLE AuthorizedUsers;
    DROP TABLE HeadstonesForLinks;
    DROP TABLE Highlights;
    DROP USER ButteArchives@localhost;

    SET FOREIGN_KEY_CHECKS=1;

COMMIT;