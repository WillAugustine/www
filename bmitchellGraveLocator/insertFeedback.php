<?php

/*
 * Name:    Brandon Mitchell
 * Description: Takes the feedback from hte createFeedbackPage and inserts it
 *              into the database.
 */

require_once("functions.php");

if (!isset($_REQUEST['search']))
{
    header("location: .?missingSearch");
}

elseif (!validReferrer())
{
    header("location: createFeedbackPage.php?search=" . $_REQUEST['search']);
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
            // If user didn't answer a quesiton, insert null instead of empty string
            // Also ensure that the selection is valid
            $question1 = !empty($_POST['question1']) && validRadioChoice($_POST['question1']) ? $_POST['question1'] : null;
            $question2 = !empty($_POST['question2']) && validRadioChoice($_POST['question2']) ? $_POST['question2'] : null;
            $question3 = !empty($_POST['question3']) && validRadioChoice($_POST['question3']) ? $_POST['question3'] : null;
            $comments = !empty($_POST['comments']) ? $_POST['comments'] : null;
            
            $insertQuery = $conn->prepare("INSERT INTO feedback (searchID, question1, question2, question3, comments) VALUES (?, ?, ?, ?, ?);");
            $insertQuery->bind_param("issss", $searchID, $question1, $question2, $question3, $comments);
            $insertQuery->execute();
            $insertQuery->close();
        }
        
        header("location: createFeedbackPage.php?search=" . rawurlencode($_REQUEST['search']));
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