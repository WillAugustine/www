START TRANSACTION;

    USE CemeteryLocatorApplication;

    DROP TABLE BlockData;
    DROP TABLE ButteArchivesRecords;
    DROP TABLE Feedback;
    DROP TABLE Users;
    DROP TABLE AuthorizedUsers;
    DROP TABLE HeadstonesForLinks;
    DROP TABLE Highlights;
    DROP USER ButteArchives@localhost;

COMMIT;