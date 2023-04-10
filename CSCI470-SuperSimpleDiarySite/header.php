<!DOCTYPE html>
<link rel="stylesheet" href="styles.css">

<head>
    <title>Your Diary</title>
</head>

<?php
    session_start();
    $logged_in = isset($_SESSION['logged_in']) ? $_SESSION['logged_in'] : false;
    $user_name = isset($_SESSION['first_name']) ? $_SESSION['first_name'] : '';
    $title = $logged_in ? "$user_name's Diary" : "Diary";
    if (isset($_SESSION['logged_in']) && $_SESSION['logged_in']) {
        $button_message = "<a href='profile.php' id='login-button'>View Profile</a>&nbsp;<a href='login.php?logout' id='login-button'>Logout</a>";
    } else {
        $button_message = "<a href='login.php' id='login-button'>Login</a>";
    }
?>

<div class="header">
    <div class="item"><span><?php echo "" ?></span></div>
    <div class="item"><span><h1><?php echo $title ?></h1></span></div>
    <div class="item"><span><?php echo $button_message; ?></span></div>
</div>
<hr>
<br>

