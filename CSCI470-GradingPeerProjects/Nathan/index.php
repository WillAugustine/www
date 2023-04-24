<!-- This project's homepage, 'index.php' lets you decide to create a new 
     search, or to sign out of your Archives User account.  
    -->
<?php
session_start();
require_once('config.php');
if(!isset($_SESSION['username'], $_SESSION['auth']) ){
    header('location: signin.php');
    exit();
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="style/index.css">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
        <div class = "index-page-wrapper">     
            <div class = "a-wrapper">          
                <form style = " display: flex; flex-direction: row; align-items: right; justify-content: space-between;" method="post" action="<?php echo $_SERVER['PHP_SELF']?>">
                    <a style = " padding-left: 50px; padding-right: 50px; padding-bottom: 50px;" href="signin.php">Sign Out</a>
                    <a  style = " padding-left: 50px; padding-right: 50px;  padding-bottom: 50px;" href="createSearch.php">Create Search</a>
                </form>
            </div>
        </div>
        <div class = "b-wrapper">   
            <h2>St. Patrick's Grave Locator </h2>
            <p> Signed in as:  <?php echo $_SESSION['username']; ?>  </p> 

        </div>
</body>
</html>