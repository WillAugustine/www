<!-- Archives users authenticate here. They must providea working username
     and password. In this project, there will only be one such combination,
     to be listed in the referral document submitted alongside this project.

     This screen should be the first anyone sees, as 'index.php' will 
     redirect the user here if their signin has not been verified. -->
<?php
    session_start();
    require_once('config.php');
    $username = "";
    if (isset($_POST['login']))
    {
        //Verify that proper credentials have been entered
        if (!empty($_POST['username']) && !empty($_POST['password']))
        {
            $username = $_POST['username'];
            $password = ($_POST['password']);
            $hashedPassword = sha1($password);            
            //check if user exists
            $result = VerifyUser($username, $hashedPassword);
            $rows = mysqli_num_rows($result);
            if($rows == 1){
                $_SESSION['username'] = $username;
                $_SESSION['auth'] = sha1($username);
                header('location:index.php');
                exit;
            }
        }
        else
        {
            header('location: signin.php');
        }
    }
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="style/signin.css">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <div class = "signin-wrapper">            
    <h2>St. Patrick's Grave Locator </h2>
    <h3>Sign-in</h3>
        <form style = " display: flex; flex-direction: column; align-items: center; justify-content: space-between;" method="post" action="<?php echo $_SERVER['PHP_SELF']?>">
            <input type="text" name="username" placeholder="Username" required>
            <input type="password" name="password" placeholder="Password" required>
            <input type="submit" name="login" value = "login">
        </form>
    </div>
</body>
</html>