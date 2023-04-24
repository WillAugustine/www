<?php

/*
 * Name:    Brandon Mitchell
 * Description: Creates the block highlight and combines the highlights and 
 *              record together to display to the user.
 */

if (!isset($_REQUEST['search']))
{
    header("location: .?missingSearch");
}
else
{
    require_once("constants.php");
    require_once("functions.php");
    createHeader(true, $_REQUEST['search']);
    
    // Forces it to throw errors instead of failing silently
    mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
    
    $conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
    if ($conn->connect_error) 
    {
        die('Connection failed: ' . mysqli_connect_error());
    } 
    
    $searchID = decodeSearchID($_REQUEST['search']);
    
    $searchesQuery = $conn->prepare("SELECT * FROM searches WHERE searchID = ?;");
    $searchesQuery->bind_param('i', $searchID);
    $searchesQuery->execute();
    $searchesResult = $searchesQuery->get_result();
    $searchesRow = $searchesResult->fetch_assoc();
    
    if (!is_null($searchesRow))
    {
        $name = htmlentities($searchesRow["name"]);
        $blockRecordID = htmlentities($searchesRow["blockRecordID"]);
        $lot = htmlentities($searchesRow["lot"]);
        $plot = htmlentities($searchesRow["plot"]);
        
        // Stored procs?  I know this is a bit messy, probably better to do in two queries
        $blockQuery = $conn->prepare("SELECT * FROM blockRecords NATURAL JOIN blocks WHERE blockID = (SELECT blockID FROM blockRecords WHERE blockRecordID = ?) AND blockRecordID = ?;");
        $blockQuery->bind_param('ii', $blockRecordID, $blockRecordID);
        $blockQuery->execute();
        $blockResult = $blockQuery->get_result();
        $blockRow = $blockResult->fetch_assoc();

        $title = "Block " . $blockRow['blockID'];
        
        if (!empty($lot))
        {
            $title .= ", Lot " . $lot;
        }
        
        if (!empty($plot))
        {
            $title .= ", Plot " . $plot;
        }
        
        $title .= ": " . $name;

        echo "<div class='centerBox'><h2>$title</h2>";
        
        $blockResult->close();
        $blockQuery->close();

        $map = imagecreatefromjpeg("./img/cemetery.jpg");
        $width = imagesx($map);
        $height = imagesy($map);
        
        // RGB, yellow border
        $color = imagecolorallocate($map, 255, 255, 0);
        imagesetthickness($map, 5);
        
        // Found through trial and error
        $imageWestLong = -112.545450;
        $imageEastLong = -112.539850;
        
        $imageNorthLat = 45.98596700;
        $imageSouthLat = 45.97970000;
        
        $pixelsPerLat = $width / ($imageNorthLat - $imageSouthLat);
        $pixelsPerLong = $height / ($imageEastLong - $imageWestLong);
        
        $SWLong = ($blockRow["SWLong"] - $imageWestLong) * $pixelsPerLong;
        $SWLat = abs($blockRow["SWLat"] - $imageNorthLat) * $pixelsPerLat;
        
        $NELong = ($blockRow["NELong"] - $imageWestLong) * $pixelsPerLong;
        $NELat = abs($blockRow["NELat"] - $imageNorthLat) * $pixelsPerLat;
        
        imagerectangle($map, intval($SWLong), intval($SWLat), intval($NELong), intval($NELat), $color);
        
        // Should have processed the image before I did all the above math
        // Remove emtpy fields, houses, rotate so image is horizontal
        $map = imagecrop($map, ['x' => 750, 'y' => 0, 'width' => 1800, 'height' => $height]);
        $map = imagerotate($map, -90, 0);
        
        // Start the output buffer
        ob_start();
        
        // Convert image object to image in buffer and get data
        imagejpeg($map);
        $imageData = ob_get_clean();
        
        // Encode image and output with image tag
        $encodedImage = "data:image/jpeg;base64," . base64_encode($imageData);
        echo "<img class='mapPageImages' src=$encodedImage><br>";
        
        $blockRecordFile = $blockRow["imageFileName"];
        $blockRecord = imagecreatefromjpeg("./blocks/blockRecords/$blockRecordFile.JPG");
        $blockHighlights = imagecreatefrompng("./blocks/blockHighlights/$searchID.png");
        
        $width = imagesx($blockRecord);
        $height = imagesy($blockRecord);
        $newWidth  = imagesx($blockHighlights);
        $newHeight = imagesy($blockHighlights);
        
        // Overlays the block hightlights over the original block record
        $combined = imagecreatetruecolor($newWidth, $newHeight);
        imagecopyresampled($combined, $blockRecord, 0, 0, 0, 0, $newWidth, $newHeight, $width, $height);
        imagecopyresampled($combined, $blockHighlights, 0, 0, 0, 0, $newWidth, $newHeight, $newWidth, $newHeight);
                
        ob_start();
        
        // Convert image object to image in buffer and get data
        imagejpeg($combined);
        $imageData = ob_get_clean();
        
        // Encode image and output with image tag
        $encodedImage = "data:image/jpeg;base64," . base64_encode($imageData);
        echo "<img class='mapPageImages' src='$encodedImage'><br></div>";
    }
    else
    {
        header("location: .?invalidSearch");
    }
    
    $searchesResult->close();
    $searchesQuery->close();
    $conn->close();
}

?>