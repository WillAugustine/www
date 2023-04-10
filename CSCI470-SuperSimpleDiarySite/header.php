<!DOCTYPE html>
<link rel="stylesheet" href="styles.css">

<head>
    <title>Your Diary</title>
</head>

<?php
    session_start();
    $logged_in = isset($_SESSION['logged_in']) ? $_SESSION['logged_in'] : false;
    $user_name = isset($_SESSION['first_name']) ? $_SESSION['first_name'] : '';
    $user_username = isset($_SESSION['username']) ? $_SESSION['username'] : '';
    $title = $logged_in ? "$user_name's Diary" : "Diary";
    $profile_menu = "
    <div class='dropdown'>
        <button class='dropbtn'>$user_username</button>
        <div class='dropdown-content' style='left:0;'>
            <a href='view_profile.php'>View Proifile</a>
            <a href='./'>View Diary</a>
            <a href='create_entry.php'>Create Entry</a>
            <a href='login.php?logout'>Logout</a>
        </div>
    </div>
    ";
    if (isset($_SESSION['logged_in']) && $_SESSION['logged_in']) {
        $button_message = "$profile_menu";
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

