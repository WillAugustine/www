<?php

/*
 * Name:    Brandon Mitchell
 * Description: Takes the data from the blockRecordPage and inserts it into the 
 *              database.  Has to decode and save the image.
 */

session_start();

require_once("functions.php");

if (!loggedOn())
{
    // User is not logged in, return to landing page
    header("location: .?notLoggedIn");
}

// If user visits this page directly and not through the form
elseif (!validReferrer())
{
    header("location: archivesPanel.php");
}

elseif (empty($_POST['name']) || empty($_POST['block']) || empty($_POST['image']))
{
    // Required fields are missing, shouldn't happen with normal use
    header("location: archivesPanel.php?missingData");
}

// Not required, but verify is valid if included
elseif (!empty($_POST["lot"]) && !validLot($_POST["lot"]))
{
    header("location: archivesPanel.php?invalidLot");
}

elseif (!empty($_POST["plot"]) && !validPlot($_POST["plot"]))
{
    header("location: archivesPanel.php?invalidPlot");
}

else
{
    require_once("constants.php");
    
    // Forces it to throw errors instead of failing silently
    mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
    
    $conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
    if ($conn->connect_error) 
    {
        die('Connection failed: ' . mysqli_connect_error());
    } 
    
    $name = $_POST['name'];
    $block = $_POST['block'];   
    $lot = $_POST['lot'];
    $plot = $_POST['plot'];
    $image = $_POST['image'];
    
    if (empty($lot))
    {
        $lot = null;
    }
    
    if (empty($plot))
    {
        $plot = null;
    }    
    
    // Verify block is in the database
    $verify = $conn->prepare("SELECT blockRecordID FROM blockRecords WHERE imageFileName = ?");
    $verify->bind_param('s', $block);
    $verify->execute();
    $result = $verify->get_result();
    
    $row = $result->fetch_assoc();
    
    $result->close();
    $verify->close();
    
    // If the row is null, the the block record isn't in the database
    if (!is_null($row))
    {
        $blockRecordID = $row['blockRecordID'];
        
        $insert = $conn->prepare("INSERT INTO searches (name, blockRecordID, lot, plot) VALUES (?, ?, ?, ?);");
        
        $insert->bind_param('siii', $name, $blockRecordID, $lot, $plot);
        $insert->execute();
        
        $searchID = $insert->insert_id;
        
        $insert->close();
        
        // Image was converted and encoded into a string, need to decode
        $image = str_replace("data:image/png;base64,", "", $image);
        $image = str_replace(' ', '+', $image);
        $fileData = base64_decode($image);

        // Save highlight to server, what if the image was invalid? Don't know.
        file_put_contents("./blocks/blockHighlights/$searchID.png", $fileData);

        $encodedSearchID = encodeSearchID($searchID);

        header("location: archivesPanel.php?search=$encodedSearchID");
    }
    else
    {
        header("location: archivesPanel.php?noBlock");
    }
    
    $conn->close();
}

?>