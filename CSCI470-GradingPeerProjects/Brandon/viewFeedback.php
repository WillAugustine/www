<?php

/*
 * Name:    Brandon Mitchell
 * Description: Formats all the saved feedback into a table for easy viewing by
 *              the archives.
 */

require_once("functions.php");
createHeader(false);

// User is not logged in, return to landing page
if (!loggedOn())
{
    header("location: .?notLoggedIn");
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

    $query = "SELECT * FROM feedback;";
    $result = $conn->query($query);
    
    echo <<<EOD
        <div class="centerBox">
            <h2>Feedback from Users</h2>
            <table class="infoTable">
                <tr>
                    <th>Question 1</th>
                    <th>Question 2</th>
                    <th>Question 3</th>
                    <th>Comments</th>
                </tr>
EOD;
    
    // Put each row into the table
    while($row = $result->fetch_assoc())
    {
        $question1 = htmlentities($row['question1']);
        $question2 = htmlentities($row['question2']);
        $question3 = htmlentities($row['question3']);
        $comments = htmlentities($row['comments']);
        
        echo <<<EOD
            <tr>
                <td>$question1</td>
                <td>$question2</td>
                <td>$question3</td>
                <td class="comments">$comments</td>
            </tr>
EOD;
    }
    
    echo "</table></div>";

    $result->close();
    $conn->close();
}

?>