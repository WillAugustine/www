-- Author: Will Augustine
--      Description: MySQL script used to delete all used tables in the diaryappdb database
--      Usage: After logging into mysql command line, run the following command:
--          'source <path where script is located>\diary_app_db_reset.sql'

START TRANSACTION;
    USE `diaryappdb`;
COMMIT;

START TRANSACTION;
    DROP TABLE `tbl_diary_entries`;
COMMIT;

START TRANSACTION;
    DROP TABLE `tbl_logon_attempts`;
COMMIT;

START TRANSACTION;
    DROP TABLE `tbl_users`;
COMMIT;
