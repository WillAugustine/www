<?php
    // Include the header at the top of the page
    include("header.php");
    // Database connection information
    $servername = "localhost";
    $db_username = "ButteArchives";
    $db_password = "password";
    $db_name = "CemeteryLocatorApplication";

    // If user is logging out
    if (isset($_GET['logout'])) {
        // Destroy the session and all variables associated with it
        session_destroy();
        // Redirect user back to landing page (index.php)
        header("Location: ./");
    }

    // Set the default error message to empty
    $error_msg = null;

    // If the user attempts to logon
    if (isset($_GET['request'])) {
        // Create a connection to the database
        $conn = new mysqli($servername, $db_username, $db_password, $db_name);
        if ($conn->connect_error) {
            // If the connection failed, display why
            die("Connection failed: " . $conn->connect_error);
        }

        // Get user input from logon form
        $username = $_POST["username"];
        // Use sha1 to hash inputted password
        $password = sha1($_POST["password"]);
        

        // Check if the username exists in the tbl_users table
        $sql = "SELECT * FROM AuthorizedUsers WHERE username='$username' AND password='$password'";
        $result = $conn->query($sql);

        // If the username exists
        if ($result->num_rows > 0) {
            // Start a session
            session_start();
            // Set session variables to logon information
            $_SESSION['username'] = $username;
            $_SESSION['logged_in'] = true;
            header("Location: ./");
        } else {
            // If the password was incorrect

            // Set error message to display password was incorrect
            $error_msg = "Incorrect username or password!";
        }

        // Close connection to database
        $conn->close();
    }
?>

<!DOCTYPE html>
<html>
<head>
    <title>Login</title>
</head>
<body>
    <center>
        <!-- Form for logon -->
        <h1>Login</h1>
        <form action="login.php?request" method="post">
            <label for="username">Username:</label>
            <input type="text" id="username" name="username"><br><br>
            <label for="password">Password:</label>
            <input type="password" id="password" name="password"><br>
            <?php if (isset($error_msg)) echo '<div class="invalid" ><p>'.$error_msg.'</p></div>';
            else echo '<p> <br> </p>';?>
            <input type="submit" value="Submit">
        </form>
    </center>
</body>
</html>