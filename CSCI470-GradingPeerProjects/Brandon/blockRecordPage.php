<?php

/*
 * Name:    Brandon Mitchell
 * Description: The user is redirected here to highlight the block record image
 *              after inserting their information in the Archives Panel.
 */

require_once("functions.php");
createHeader(false);

// User is not logged in, return to landing page
if (!loggedOn())
{
    header("location: .?notLoggedIn");
}

// If user visits this page directly and not through the form
elseif (!validReferrer())
{
    header("location: archivesPanel.php");
}

// Required info is missing from form
elseif (empty($_POST["name"]) || empty($_POST["block"]))
{
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
    
    $name = htmlentities($_POST['name']);
    $block = htmlentities($_POST['block']);
    $lot = htmlentities($_POST['lot']);
    $plot = htmlentities($_POST['plot']);
    
    $title = ": " . $name;
    
    if (empty($plot))
    {
        $plot = null;
    }
    else
    {
        $title = ", Plot " . $plot . $title;
    }
    
    if (empty($lot))
    {
        $lot = null;
    }
    else
    {
        $title = ", Lot " . $lot . $title;
    }
    
    // Verify block is in the database
    $verify = $conn->prepare("SELECT blockID FROM blockRecords WHERE imageFileName = ?");
    $verify->bind_param('s', $block);
    $verify->execute();
    $result = $verify->get_result();
    
    $row = $result->fetch_assoc();
    
    $result->close();
    $verify->close();
    
    // If the row is null, the the block record isn't in the database
    if (!is_null($row))
    {
        $title = "Block " . $row['blockID'] . $title;
        $image = "./blocks/blockRecords/" . $block . ".JPG";
        
        // I have all block records in the database, but not all block record
        // images are available.
        if (file_exists($image))
        {
        // Outputs canvas image and hidden form to submit the data from 
        // previous page
        echo <<<EOD
        <div class="centerBox">
            <h2>$title</h2>
            <div class="blockHighlightBox">
                <img class="blockHighlightImage" src="$image">
                <canvas class="blockHighlightCanvas" id="drawLayer"></canvas>
            </div>
            <br><br>
            <form id="infoForm" style="all: unset;" action="createSearch.php" onSubmit="return saveSearch(this);" method = "POST">
                <input type="hidden" value="$name" name="name" />
                <input type="hidden" value="$block" name="block" />
                <input type="hidden" value="$lot" name="lot" />
                <input type="hidden" value="$plot" name="plot" />
                <input id="infoFormImage" type="hidden" name="image" />
                <input type="submit" class="submitButton" id="saveImageButton" value="Create Search" />
            </form>
        </div>
EOD;
        }
        else
        {
            header("location: archivesPanel.php?blockImageNotPresent");
        }
    }
    else
    {
        header("location: archivesPanel.php?noBlock");
    }
    
    $conn->close();
}

?>

<script>

// Based on code from https://stackoverflow.com/a/30684711
var canvas = document.getElementById("drawLayer");
var ctx = canvas.getContext('2d');

// Position of the mouse
var x = 0, y = 0;

// New position from mouse event
function setPosition(e) 
{
    var rect = canvas.getBoundingClientRect();
    
    // Scale down to canvas area, subtract margins
    x = (e.clientX - rect.left) * canvas.width / canvas.offsetWidth;
    y = (e.clientY - rect.top) * canvas.height / canvas.offsetHeight;
}

// Draws the highlights on the map
function draw(e) 
{
    // Check that the button pressed was the left mouse
    if (e.buttons !== 1) { return; }
    
    ctx.beginPath();
    
    ctx.lineWidth = 25;
    ctx.lineCap = 'butt';
    
    // Transparent yellow
    ctx.strokeStyle = 'rgba(255, 255, 0, 0.25)';
    
    ctx.moveTo(x, y); 
    setPosition(e);
    ctx.lineTo(x, y);
    
    ctx.stroke();
}

ctx.canvas.width = window.innerWidth;
ctx.canvas.height = window.innerHeight;

// Bind event listeners for the canvas's drawing functionality
canvas.addEventListener('mousedown', setPosition);
canvas.addEventListener('mouseenter', setPosition);
canvas.addEventListener('mousemove', draw);

// Converts the image into an encoded string and attaches it to the form
function saveSearch(form)
{
    var image = canvas.toDataURL("image/png");
    form.image.value = image;
    return true;
}

</script>