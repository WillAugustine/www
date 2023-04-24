<!-- After clicking their unique link from 'link/php', the regular user is taken to the map page. 
     Here, the aerial image is drawn, with a dynamically positioned rectangle drawn over it, courtesy of 'map.js'. 
     This age is where this Regular User is able to see where their search's physical location is in relation to the cemetery map.
     The user can also view the highlighted block record that the Archives User curated for them in 'confirm.php'.
     When finished, the "finish and Take Survey" button ushers the Regular User along to 'survey.php', 
     where they can fill out their responses to a short questionnaire -->
<?php 
session_start();
require_once('config.php');
$thisSearchID = $_GET['searchID'];

/* Once it is verified that the searchID for this instance of 'map.php' has a companion entry in the 'Searches' table of 'gravelocwebappdb',
   the GPS info is retrieved from the 'Blocks' table, and each coordinate is given a variable name.*/
if(confirmSearch($thisSearchID)){
    $ourBlock = matchSearchID($thisSearchID);
    $blockInfo = RetrieveSearchInfo($thisSearchID);
    $GPSArray = returnGPS($ourBlock);
    $NWLAT = $GPSArray["NW_LAT"]; 
    $NWLONG = $GPSArray['NW_LONG']; 
    $SELAT = $GPSArray['SE_LAT']; 
    $SELONG = $GPSArray['SE_LONG']; 
    $CENTERLAT = $GPSArray['CENTER_LAT']; 
    $CENTERLONG = $GPSArray['CENTER_LONG']; 
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="style/map.css">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <!--The CSS here is non-fancy, but non-trivial nonetheless. Some explanation is due -->

    <!-- the page wrapper holds the hero wrapper (everything you see) and several hidden inputs for sending data to 'map.js'
         for drawing the block outline onto the canvas. -->
    <div class = "page">  

         <!-- hero wrapper contains all the visible contents of the page, and seperates them using the style methods: 
             'display: flex;' and 'flex-direction: column;'. These functions assert that the elements in the page should 
             place themselves dynamically, vertically. This wrapper sees only the 'head' and 'bpdy' wrappers.-->
        <div class = "hero">

            <!-- at the head level, text is asserted to be centered, so that the label for the map page will be placed appropriately.
                 the head tag only gets 10% of the screen height, since it only needs as much. -->
            <div class = "head">
                <h2>Map</h2>
            </div>

            <!-- most of the function of 'map.php exists within the body, which assertsits children be displayed in a grid, -->
            <div class = "body">

                <!-- big-wrapper is used to proportion 90% of remaining screen space to the a- and b- wrappers which contain 
                     the functional elements of this page, and 10% to 'dummy' divs, which take up space but display nothing; 
                     helpful for formatting the page -->
                <div class = "big-wrapper">
                    <div class = "b-wrapper"> 

                        <!-- image wrapper contains the cemetery image and the canvas overlaying it to enable 'map.js' to draw the block-->
                        <div class = "image-wrapper">
                            <img src="AerialImage\SaintPatricksCemetery2022-11-09.jpg" id = "mapImage" alt="Map">
                            <canvas id = "mapCanvas"></canvas>
                        </div>

                        <!-- dummy divs fill out the bottom tenth of the screen, in both a- and b-wrapper. They prop the map image and the 
                             sidebar contents up to the top of the page and keep any intermediate wrappers from becoming too tall automatically -->
                        <div class = "dummy";>
                        </div>
                    </div>   

                        <!-- a-wrapper surrounds the compass rose, block record link, and the survey page link.
                             they are proportioned thier 90% of the sceen here -->
                    <div class = "a-wrapper">    
                        
                    <!-- at this layer, the contents class specifies a justifiaction of 'space-around, 
                        which means each element tries to fit inside its 1/3 of the bar allotted by 'a-wrapper' -->
                        <div class="contents">
                            <img style = "border-radius: 15px;" src="Images\compassrose.png" alt="Compass Rose">
                            <!-- This link takes the user to 'blockRecordPopup.php', which displays the correct image 
                                 as well as the highlight previously performed by the ArchivesUser -->
                            <a style = "font-size: 20px;"href= "blockRecordPopup.php?HL=<?php echo $GLOBALS['blockInfo']['HLImageFilename'];?>&BIF=<?php echo $GLOBALS['blockInfo']['blockImageFilename'];?>&SID=<?php echo $GLOBALS['thisSearchID'];?>" >Block Image</a>
                            
                            <!-- this form sends the user to 'survey.php' once they click the input button below -->
                            <form method="post" action="survey.php">
                                <input type = "hidden" name = "searchIDCarrier2" value = "<?php echo $GLOBALS['thisSearchID'];?>">
                                <input style = "background: white; font-soze: 20px; border-radius: 15px;" type="submit" name="mapButton" value = "Finish and Take Survey">
                            </form>

                        <!-- another dummy div here -->
                        </div>
                        <div class = "dummy";>
                        </div>
                    </div>

                </div>  
            </div>
        </div>        
        
        <!-- this form was mentioned wayyy back in the comments for the page class. It holds all hidden inputs that 
             'map.php' has to make available to 'map.js' in order to draw the desired block outline onto the aerial view-->
        <form>
            <input type = "hidden" id = "centerLatPt" value = "<?php echo $GLOBALS['CENTERLAT'];?>">
            <input type = "hidden" id = "centerLongPt" value = "<?php echo $GLOBALS['CENTERLONG'];?>">
            <input type = "hidden" id = "SELatPt" value = "<?php echo $GLOBALS['SELAT'];?>">
            <input type = "hidden" id = "SELongPt" value = "<?php echo $GLOBALS['SELONG'];?>">
            <input type = "hidden" id = "NWLatPt" value = "<?php echo $GLOBALS['NWLAT'];?>">
            <input type = "hidden" id = "NWLongPt" value = "<?php echo $GLOBALS['NWLONG'];?>"> 
        </form>
    </div>

    <!-- finally, this script tag tells the computer to actually run 'map.js' now that all the preliminaries are taken care of-->
    <script src="map.js"></script>
</body>
</html>