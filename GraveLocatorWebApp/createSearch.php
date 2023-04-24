<!-- Archives User will use this page to fill out the name, 
     block, lot and plot information for a particular search.
     This information is used in 'link.php' to create a new 
     unique search and save it to the PHPMyAdmin SQL database-->
<?php
session_start();
require_once('config.php');
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="style/createsearch.css">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<style> 
    h2{

        font-size: 45px;
        text-align: center;
    }
    h3{
        color: black;
        text-align:center;
        font-size:24px;
    }
    p{
        padding: 0px;
        margin: 0px;
        color: gray;
        text-align:center;
        font-size:14px;
    }
</style>
<body>
    <div class = title-wrapper>   
        <a href= "index.php">Sign out </a>
    </div>
    <h2>Create a Search </h2>   
    <h3>Enter Search Information</h3>
    <div class = "info-wrapper" style = "display: flex; flex-direction: column; align-items: center; font-size: 20px; text-align: center;">       
        <form style = "display: flex; flex-direction: column; align-items: center; justify-content: space-between;" method="post" action="confirm.php">
            <input type="text" name="Name" placeholder="Name" required>
            <input type="text" name="Block" placeholder="Block" required>
            <input type="text" name="Lot" placeholder="Lot">
            <input type="text" name="Plot" placeholder="Plot">
            <input type="submit" name="blockInfo" value = "confirm">
        </form>
    </div>
</body>
</html>