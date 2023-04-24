<?php

/*
 * Name:    Brandon Mitchell
 * Description: This is the page the Archives first see after logging in.  It 
 *              has some basic instructions and a form to create a new search.
 */

require_once("functions.php");
createHeader(false);

$searchURL = "";
$blockRecords = "";

// User is not logged in, return to landing page
if (!loggedOn())
{
    header('location: .?notLoggedIn');
}
else
{
    require_once("constants.php");

    $conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
    if ($conn->connect_error)
    {
        die('Connection failed: ' . mysqli_connect_error());
    }

    $searchHTMLBox = "";
    
    if (isset($_REQUEST['search']))
    {
        $encodedSearchID = rawurlencode($_REQUEST['search']);
        $searchURL = "mapPage.php?search=$encodedSearchID";
        
        $searchHTMLBox = <<<EOD
        <div class="searchBox">
            <a href="./$searchURL">$searchURL</a><br>
            <button class="submitButton" onClick="copy('$searchURL');">
                Copy
            </button>
        </div>
EOD;
    }

    $query = "SELECT imageFileName FROM blockRecords;";
    $result = $conn->query($query);

    $blockRecords .= "<datalist id='blockRecords'>";

    // Append each block record to the datalist, used to suggest blocks to user
    while ($row = $result->fetch_assoc())
    {
        $imageName = $row['imageFileName'];
        $blockRecords .= "<option>$imageName</option>";
    }

    $blockRecords .= "</datalist>";

    $result->close();
    $conn->close();
}

?>
    
    <div class="centerBox">
        <h2>Archives Panel</h2>
    </div>
    <div class="infoBox">
        The following form can be used to created a search.  The name and block 
        fields are required, but the lot and plot are not.  Begin by typing the 
        name of the block and then select the specific record desired from the 
        dropdown menu.
    </div><br>
    <form action="blockRecordPage.php" method="POST" autocomplete="off">
        <p class="errorText">
            <?php
                if (isset($_REQUEST['missingData']))
                {
                    echo "Name and block are required fields.";
                }
                elseif (isset($_REQUEST['invalidLot']))
                {
                    echo "Lot must be between 1 and 4.";
                }    
                elseif (isset($_REQUEST['invalidPlot']))
                {
                    echo "Plot must be between 1 and 6.";
                }                
                elseif (isset($_REQUEST['blockImageNotPresent']))
                {
                    echo "The block image associated with that block isn't availble.";
                }
            ?>
        </p>
        <h3>URL Creation Form</h3>
        *Name: <input type="text" name="name" maxLength="40" required /><br /><br />
        *Block: <input type="text" name="block" list="blockRecords" maxLength="40" required /><br /><br />
        <?php echo $blockRecords ?>
        Lot: <input type="text" name="lot" maxLength="1" /><br /><br />
        Plot: <input type="text" name="plot" maxLength="1" /><br /><br />
        <div class="centerBox">
            <input class="submitButton" type="submit" value="Create" />
        </div>
    </form>
    <br><br>
    <?php echo $searchHTMLBox ?>
    
    <script>
    
    // Copys the input to the clipboard, used to copy URLs
    function copy(text)
    {
        navigator.clipboard.writeText(text);
    }
    
    </script>

</body>
</html>