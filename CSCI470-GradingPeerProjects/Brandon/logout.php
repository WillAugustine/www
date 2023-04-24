<?php

/*
 * Name:    Brandon Mitchell
 * Description: Clears session variables and destroys the user's session to log
 *              then out.  Then redirects them to the landing page, index.php.
 */

session_start();
session_unset();
session_destroy();

header('location: .');
?>