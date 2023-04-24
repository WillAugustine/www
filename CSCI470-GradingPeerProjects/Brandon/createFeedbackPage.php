<?php

/*
 * Name:    Brandon Mitchell
 * Description: This page presents the user with a feedback form to fill out
 *              and submit to give their idea of the application.
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
        // Verify user hasn't already submitted feedback
        $inFeedback = $conn->prepare("SELECT * FROM feedback WHERE searchID = ?;");
        $inFeedback->bind_param('i', $searchID);
        $inFeedback->execute();
        $inFeedbackResult = $inFeedback->get_result();
        $inFeedbackRow = $inFeedbackResult->fetch_assoc();
        
        $inFeedbackResult->close();
        $inFeedback->close();
        
        // Should be empty if no feedback has been submitted
        if (is_null($inFeedbackRow))
        {
            $unprocessedSearch = $_REQUEST['search'];
            
            echo <<<EOD
                <div class="centerBox">
                    <h2>Thanks for taking your time to fill out this survey</h2>
                </div>
                <form action="insertFeedback.php?search=$unprocessedSearch" method="POST">
                    <h3>Feedback Survey</h3>
                    
                    Did you find the gravesite?<br>
                    <input type="radio" name="question1" value="Yes"> Yes</input><br>
                    <input type="radio" name="question1" value="No"> No</input><br><br>

                    Would you use this site again?<br>
                    <input type="radio" name="question2" value="Yes"> Yes</input><br>
                    <input type="radio" name="question2" value="No"> No</input><br><br>

                    Would you recommend this site to others?<br>
                    <input type="radio" name="question3" value="Yes"> Yes</input><br>
                    <input type="radio" name="question3" value="No"> No</input><br><br>
                    
                    Any additional comments?<br>
                    <textarea name="comments" rows="6" cols="34" maxLength="350"></textarea><br>
                    
                    <div class="centerBox">
                        <input class="submitButton" type="submit" value="Submit" />
                    </div>
                </form>
EOD;
        }
        else
        {
            echo <<<EOD
                <div class="centerBox">
                    <h2>Your feedback has been submitted.  Thanks!</h2>
                    <p>Use the above map button to return to the map.</p>
                </div>
EOD;
        }        
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