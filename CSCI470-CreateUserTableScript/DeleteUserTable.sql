
-- Author: Will Augustine
-- Description: Deletes the tbl_user table from the UserTableAssignDB database


START TRANSACTION;
USE UserTableAssignDB;
DROP TABLE tbl_users;
COMMIT;
