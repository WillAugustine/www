<?php

/*
 * Name:    Brandon Mitchell
 * Description: These are constants related to the database and to the 
 *              encryption of the search ID.
 */

require_once("constants.php");

// Outputs the nav bar, changes based on which page user is on, if they are 
// logged in or not
function createHeader($mapPage, $search = null)
{
    echo <<<EOD
        <!DOCTYPE html>
        <html>
        <head>
            <title>St. Patrick's Cemetery Grave Locator</title>
            <link rel="stylesheet" href="style.css">
        </head>
        <body>
            <ul>
                <li><a class="siteTitle">St. Patrick's Cemetery Locator</a></li>
                <li><a href=".">Home</a></li>
EOD;
    
    session_start();
    
    $signedIn = loggedOn();
    
    if ($signedIn)
    {
        echo <<<EOD
            <li><a href="archivesPanel.php">Create A Search</a></li>
            <li><a href="viewSearches.php">View Searches</a></li>
            <li><a href="viewFeedback.php">View Feedback</a></li>
            <li class="logout"><a href="logout.php">Logout</a></li>
EOD;
    }
    
    if ($mapPage)
    {
        $search = rawurlencode($search);
        echo <<<EOD
            <li><a href="mapPage.php?search=$search">Map</a></li>
            <li><a href="createFeedbackPage.php?search=$search">Give Feedback</a></li>
EOD;
    }
    
    echo "</ul><div class='mainContainer'>";
}

// Encrypts the searchID to obscure it
function encodeSearchID($searchID)
{
    // Encode the string as there could be spaces
    return rawurlencode(openssl_encrypt($searchID, "AES-128-ECB", ENCRYPT_KEY));
}

// Decrypts the searchID so it can be used to search the database
function decodeSearchID($searchID)
{
    return rawurldecode(openssl_decrypt($searchID, "AES-128-ECB", ENCRYPT_KEY));
}

// Checks the referrer to see if it is valid.  Used on pages where data is sent
// to the back end and pages that require you to visit a previous page
function validReferrer()
{
    return !(!isset($_SERVER['HTTP_REFERER']) || empty($_SERVER['HTTP_REFERER']) ||
        parse_url($_SERVER['HTTP_REFERER'])['host'] !== $_SERVER['HTTP_HOST']);
}

// Returns a bool indicating whether or not the user is logged on
function loggedOn()
{
    return isset($_SESSION['userID'], $_SESSION['username']);
}

// Returns a bool indicating if the lot valud is valid
function validLot($lot)
{
    return is_numeric($lot) && $lot >= 1 && $lot <= 4;
}

// Returns a bool indicating if the plot valud is valid
function validPlot($plot)
{
    return is_numeric($plot) && $plot >= 1 && $plot <= 6;
}

// Returns a bool indicating if the radio button choice is a valid one
function validRadioChoice($choice)
{
    return $choice === "Yes" || $choice === "No";
}

?>