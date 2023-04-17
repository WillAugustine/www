<!DOCTYPE html>
<link rel="stylesheet" href="styles.css">
<?php
    // Include header on top of page
    include("header.php");

    // Determine if the user is logged in
    $logged_in = isset($_SESSION['logged_in']) ? $_SESSION['logged_in'] : false;

    // If the user is logged in
    if ($logged_in) {
        // View the diary
        include("create_new_user.php");
    } else {
        // If the user is not logged in, redirect them to the login page
        header("Location: login.php");
    }
?>