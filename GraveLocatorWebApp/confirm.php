<!-- On this page, Archives User will be able to make a highlight 
     on the block image corresponding to their search information. -->
<?php
session_start();
require_once('config.php');
$invalidBlockError = false;
$blockChosen = $_POST['Block'];
$nameInfo = $_POST['Name'];
$blockImageFilename = "";

// verifies that a new search has begun
if(isset($_POST['blockInfo'])){
    if(isset($_POST['Block']) && isset($_POST['Name'])){

        // these variables store the information entered by ArchivesUser on 'createSearch.php'
        $name = $_POST['Name']; 
        $block = $_POST['Block'];
        $lot = $_POST['Lot']; 
        $plot = $_POST['Plot'];

        /* these if statements shore up the possibility that no lot or plot information
           is entered, in which case they set the associated variables = 0 */
        if(isset($_POST['lot']) && $_POST['lot'] != 0){
            $lot = $_POST['lot'];
         }
         else{
            $lot = "0";
         }
         if(isset($_POST['plot']) && $_POST['plot'] != 0){
            $plot = $_POST['plot'];
         }
         else{
            $plot = "0";
         }
        
        /*if the block image exists, we grab it from the directory, 
          create the identifier for this unique search, and 
          grab the filenames for the block record img and its canvas */
        $numRows = checkValidBlock($block); 
            if($numRows == 1){
                $blockNum = RetrieveBlockImage($block);
                $rows = mysqli_num_rows($blockNum);
                $searchID = md5(date('Y-m-d h:i:sa')." ".strval($block));
                $blockImageObject = mysqli_fetch_all($blockNum);
                $blockImageFilename = $blockImageObject[0][0];
            }
            else{
                $invalidBlockError = true;
            }
    }
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="style/confirm.css">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <div name = "1" class = "confirm-page-wrapper">
        <div class = "content-wrapper" name = "2">
            <?php               
               $blockFilename = "BlockRecords/Block ".$blockImageFilename. ".JPG";
               $blockRecordFilename = "Block ".$blockImageFilename;            ?>

            <!--transparent layer for drawing highlights -->
            <div name = "4" class = "block-image-wrapper">
                <img style="" src= "<?php echo $GLOBALS['blockFilename']; ?>"alt="Your Block does not have a Block Record Image"></img>
                <canvas id = "blockRecordImageHighlight"></canvas>
            </div>
            <div class="block-button-wrapper" name = "3">

            <!-- several hidden inputs: one transfers the link (usable by regular user) to 'link.php' 
                 and the others transfer the values of $searchID, $name, $block, $lot, $plot, $blockRecordFilename
                 and the highlighter information for 'highligh.js'. -->
                <form name="save-image" method="post" action="link.php">
                    <input type = "hidden" name="generatedLink" value = "<?php echo "http://localhost/GraveLocatorWebApp/map.php?searchID=".$searchID;?>">
                    <input type = "hidden" name="searchIDCarrier" value = "<?php echo $GLOBALS['searchID'];?>">
                    <input type = "hidden" name="nameCarrier" value = "<?php echo $GLOBALS['name'];?>">
                    <input type = "hidden" name="blockChosenCarrier" value = "<?php echo $GLOBALS['blockChosen'];?>">
                    <input type = "hidden" name="lotCarrier" value = "<?php echo $GLOBALS['lot'];?>">
                    <input type = "hidden" name="plotCarrier" value = "<?php echo $GLOBALS['plot'];?>">
                    <input type = "hidden" name="BIFCarrier" value = "<?php echo $GLOBALS['blockRecordFilename'];?>">
                    <input type="hidden" name="highlighterForRecord" id="highlightRec"></input>
                    <input type="submit" name="buttonConf" value = "confirm Search" onclick="save()">
                </form>
            </div>
        </div>
    </div>
    <script src="highlight.js"></script>
</body>
</html>