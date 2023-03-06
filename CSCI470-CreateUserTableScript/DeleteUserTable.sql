
-- Author: Will Augustine
-- Description: Deletes the tbl_user table from the UserTableAssignDB database


USE UserTableAssignDB;
START TRANSACTION;
DROP TABLE tbl_users;
-- SHOW TABLES;
-- DESC `tbl_users`;
COMMIT;
