<!--
    Author: Will Augustine
        Description: PHP file for the header on each webpage
-->

<!DOCTYPE html>
<!-- Pulls styling from styles.css -->
<link rel="stylesheet" href="styles.css">

<head>
    <title>Saint Patrick Cemetery</title>
</head>

<?php

    if (isset($_GET['visitor'])) {
        $button_message = "
        <div class='dropdown'>
            <button class='dropbtn'>Options</button>
            <div class='dropdown-content' style='left:0;'>
                <a href='create_new_user.php'>Create Visitor</a>
                <a href='view_feedback.php'>View Feedback</a>
                <a href='login.php?logout'>Logout</a>
            </div>
        </div>";

        exit();
    }
    else {
        // Starts session
        session_start();

        // Determine if user is logged in
        $logged_in = isset($_SESSION['logged_in']) ? $_SESSION['logged_in'] : false;

        // Determine user's first name
        $user_name = isset($_SESSION['first_name']) ? $_SESSION['first_name'] : '';

        // Determine user's username
        $user_username = isset($_SESSION['username']) ? $_SESSION['username'] : '';

        // Determine page title
        $title = $logged_in ? "Welcome, $user_username!" : "Welcome";

        $creating_account = isset($_SESSION['visitor_name']);

        $visitor_name = $creating_account ? $_SESSION['visitor_name'] : "ANON";

        $title = $creating_account ? "$visitor_name's Visit" : $title;

        // Drop down menu
        $profile_menu = "
        <div class='dropdown'>
            <button class='dropbtn'>$user_username</button>
            <div class='dropdown-content' style='left:0;'>
                <a href='create_new_user.php'>Create Visitor</a>
                <a href='view_feedback.php'>View Feedback</a>
                <a href='login.php?logout'>Logout</a>
            </div>
        </div>";

        // If the user is logged in
        if (isset($_SESSION['logged_in']) && $_SESSION['logged_in']) {
            // Set the button in the top right to profile drop-down menu
            $button_message = $profile_menu;
        } else {
            // If user is not logged in, set button in top right of every page to "login"
            $button_message = "<a href='login.php' id='login-button'>Login</a>";
        }
    }
    
?>

<!-- Creates the header elements in line seperated in thirds (hence first element being blank) -->
<div class="header">
    <div class="item"><span><?php echo $left_message ?></span></div>
    <div class="item"><span><h1><?php echo $title ?></h1></span></div>
    <div class="item"><span><?php echo $button_message; ?></span></div>
</div>
<hr>
<br>

