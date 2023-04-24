<!-- This page shows the Regular User their block record image, along with the highlight made by ArchivesUser previously
     to return to 'map/php', the uer can click the a-link which sends them back to their unique URL of 'map.php'. -->
<?php
session_start();
require_once('config.php');
$highlightFile = "";
$imageFile = "";
$SID = "";

/* Provided that the highligher and image filenames have been set, this function grabs them along with the unique
   searchID to reference the correct URL upon the Regular User's return to 'map.php'*/
if(isset($_GET['HL'], $_GET['BIF']) ){
    $highlightFile = $_GET['HL'];
    $imageFile = "BlockRecords/";
    $imageFile.= $_GET['BIF'];
    $imageFile.= ".JPG";
    $SID = $_GET['SID'];
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="style/blockRecordPopup.css">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>

</head>
<body>
        <div class = "parent"">    
            <img class = "image1" src = "<?php echo $GLOBALS['imageFile']?>"></img>
            <img class = "image2" src = "<?php echo $GLOBALS['highlightFile']?>"></img>
            <div class = "bottom">
                <a href= "http://localhost/GraveLocatorWebApp/map.php?searchID=<?php echo $GLOBALS['SID'];?>">Return to Map Page</a>
            </div>
        </div>

</body>
</html>
