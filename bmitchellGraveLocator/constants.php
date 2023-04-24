<?php 

/*
 * Name:    Brandon Mitchell
 * Description: These are constants related to the database and to the 
 *              encryption of the search ID.
 */

// Constants related to the database user
define("DB_HOST", "localhost");
define("DB_USER", "bmitchellCemeteryUser");
define("DB_PASS", "WebScienceCemetery");
define("DB_NAME", "bmitchellCemeteryProject");

// Try to compilate the base64 encoded search ID to make it harder to modify
define("ENCRYPT_KEY", "nZ7\\Q7tzwar/W,5L");

?>