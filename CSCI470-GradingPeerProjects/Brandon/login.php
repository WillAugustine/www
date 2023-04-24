<?php

/*
 * Name:    Brandon Mitchell
 * Description: Verify that the user's information is valid and logs them in if
 *              it is.
 */

require_once("functions.php");

if (!validReferrer())
{
    header("location: .");
}
elseif (empty($_POST['username']) || empty($_POST['password']))
{
    header('location: .?missingData');
}
else
{
    require_once("constants.php");
    
    $username = $_POST['username'];
    $password = $_POST['password'];
    
    $conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
    if ($conn->connect_error) 
    {
        die('Connection failed: ' . mysqli_connect_error());
    }
    
    // Check database to verify user exists, password is correct
    $stmt = $conn->prepare("SELECT userID, password FROM users WHERE username = ?;");
    $stmt->bind_param('s', $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0)
    {
        $row = $result->fetch_assoc();
        
        if (password_verify($password, $row['password']))
        {
            session_start();
            $_SESSION['userID'] = $row['userID'];
            $_SESSION['username'] = $username;
            
            header('location: archivesPanel.php');
        }
        else
        {
            // Incorrect password
            header('location: .?loginFailed');
        }
    }
    else
    {
        // Log in failed, return to main page
        header('location: .?loginFailed');
    }

    $result->close();
    $stmt->close();
    $conn->close();
}

?>