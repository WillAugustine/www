<!-- This page is used by the Reguar User. They only have access to a single hyperlink 
     that will take them to their unique 'map.php' url, but lots of other functionality
     is also happening on this page. The data for the highlight is saved to the file structure here, 
     and a call to InsertSearch() is made, which updates the database that this Regular User's new Search exists.-->
<?php
session_start();
require_once('config.php');
$blockImageFilename = "";
if(isset($_POST['buttonConf'])){
    $blockImageFilename = $_POST['BIFCarrier'];
    $datetime = date('Y-m-d h-i-sa');
    $directory = "highlights/";
    $img = $_POST['highlighterForRecord'];
    $img = str_replace('data:image/png;base64,', '', $img);
    $img = str_replace(' ', '+', $img);
    $data = base64_decode($img); 
    $filename = $blockImageFilename.$datetime.".png";
    $file = $directory.$filename;
    $success = file_put_contents($file, $data);
    InsertSearch($_POST['searchIDCarrier'], $_POST['nameCarrier'], $_POST['blockChosenCarrier'],  $_POST['lotCarrier'], $_POST['plotCarrier'], $_POST['BIFCarrier'], $file);
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="style/link.css">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <div class = "lead">
        <div>
            <p> Accessing as: Regular User</p>            
        </div>  
        <div>
            <h2>Your Search has been Generated</h2>
        </div>    
        <div>
            <!--link to the map page that is uniquely identified by the searchID used in InsertSearch (the PK of the 'Searches' table)-->
            <p>Access your Search Here</p>
            <input style = "text-align: center; width: 45%;"value = "<?php echo "http://localhost/GraveLocatorWebApp/map.php?searchID=".$_POST['searchIDCarrier'];?>" readonly></input>
        </div>
    </div>
</body>
</html>