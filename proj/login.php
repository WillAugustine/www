<?php
session_start();

if(isset($_SESSION['login_user'])) {
    header("location: archives.php");
}

if($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['email'], $_POST['password'])) {
        $email = $_POST['email'];
        $password = $_POST['password'];
        
        // Replace these values with your own database credentials---------------------------------------------------------------
        $dbhost = "localhost";
        $dbusername = "Bo_ButteArchives";
        $dbpassword = "password";
        $dbname = "Bo_CemeteryApplication";

        $conn = new mysqli($dbhost, $dbusername, $dbpassword, $dbname);

        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        $sql = "SELECT * FROM users WHERE email='$email'";
        
        $result = $conn->query($sql);
        $row = $result->fetch_assoc();
        $stored_hash = $row['password'];
        
        if ($result && password_verify($password, $stored_hash)) {
            
            $status = "success";
            
            
            $_SESSION['login_user'] = $username;
            echo "Session variable set: " . $_SESSION['login_user'];


            $conn->close();
            header("location: archives.php");

        } else {

            $status = "failure";
        

            $error = "Invalid username or password";
            $conn->close();
        }
    } else {
        echo "Please Login";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Login</title>
    <link rel="stylesheet" type="text/css" href="style.css">
</head>
<body>
    <h1>Login</h1>
    <form method="POST">
        <label>Email:</label>
        <input type="text" name="email" required>
        <br>
        <label>Password:</label>
        <input type="password" name="password" required>
        <br>
        <input type="submit" value="Login">
    </form>
    <?php if(isset($error)) { echo "<p>$error</p>"; } ?>
    <a href="create.php">Create an account</a>
</body>
</html>