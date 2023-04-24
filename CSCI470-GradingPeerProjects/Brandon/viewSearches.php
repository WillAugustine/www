<?php

/*
 * Name:    Brandon Mitchell
 * Description: Formats all the search info in a table, allows the Archives
 *              to view what has been searched or retrieve an old search link.
 */

require_once("functions.php");
createHeader(false);

// User is not logged in, return to landing page
if (!loggedOn())
{
    header('location: .?notLoggedIn');
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

    $query = "SELECT * FROM searches NATURAL JOIN blockRecords;";
    $result = $conn->query($query);
    
    echo <<<EOD
        <div class="centerBox">
            <h2>Previous Searches</h2>
            <table class="infoTable">
                <tr>
                    <th>Name</th>
                    <th>Block</th>
                    <th>Lot</th>
                    <th>Plot</th>
                    <th>Search Link<th>
                    <th><th>
                </tr>
EOD;
    
    // Put each row into the table
    while($row = $result->fetch_assoc())
    {
        $name = htmlentities($row['name']);
        $block = htmlentities($row['blockID']);
        $lot = htmlentities($row['lot']);
        $plot = htmlentities($row['plot']);
        $searchID = $row['searchID'];
        
        $searchURL = "mapPage.php?search=" . encodeSearchID($searchID);
        
        echo <<<EOD
            <tr>
                <td>$name</td>
                <td>$block</td>
                <td>$lot</td>
                <td>$plot</td>
                <td class="searchLinks"><a href="./$searchURL">$searchURL</a></td>
                <td>
                    <button class="submitButton" onClick="copy('$searchURL');">
                        Copy
                    </button>
                </td>
            </tr>
EOD;
    }
    
    echo "</table></div>";

    $result->close();
    $conn->close();
}

?>

<script>

// Copies the text that is passed in to the clipboard
function copy(text)
{
    navigator.clipboard.writeText(text);
}

</script>