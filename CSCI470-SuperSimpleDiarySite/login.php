<!--
    Author: Will Augustine
        Description: PHP file to handle logon, logout, account creation,
            and token validation
-->

<!DOCTYPE html>
<!-- Import styling from styles.css -->
<link rel="stylesheet" href="styles.css">
<?php

    // Include the header at the top of the page
    include("header.php");

    // Function for inserting data into the tbl_logon_attempts table
    //
    //  Inputs:
    //      $conn: The connection to the database
    //      $username: The username you are inserting into the table
    //      $status: The status of the logon attempt ('SUCCESS' or 'FAILURE')
    //
    //  Input types:
    //      $conn: mysqli object
    //      $username: string
    //      $status: string
    //
    function insertIntoLogonAttempsTable($conn, $username, $status) {
        // Get the user's IP address
        $ip = $_SERVER['REMOTE_ADDR'];
        // Get the user's browser data (agent)
        $agent = $_SERVER['HTTP_USER_AGENT'];
                
        // Insert information into tbl_logon_attempts table
        $sql = "INSERT INTO tbl_logon_attempts (username, attempt_datetime, status, ipaddress, user_agent)
            VALUES ('$username', NOW(), '$status', '$ip', '$agent')";
        $conn->query($sql);
    }
    
    // Database connection information
    $servername = "localhost";
    $db_username = "diaryappdbuser";
    $db_password = "DiaryPass$";
    $db_name = "diaryappdb";

    // If user is logging out
    if (isset($_GET['logout'])) {
        // Destroy the session and all variables associated with it
        session_destroy();
        // Redirect user back to landing page (index.php)
        header("Location: ./");
    }

    // If user wants to register for an account
    if (isset($_GET['register'])) {
        // Set default error message to empty
        $error = '<p> <br> </p>';

        // If the token was inputted
        if (isset($_POST['token'])) {
            // Check if the inputted token is correct
            if ($_POST['token'] === "CSCI 470 token") {
                // Get the username from logon attempt to help create account 
                $username = isset($_POST['username']) ? $_POST['username'] : "";
                // Form for creating an account
                echo '
                <center>
                    <form action="login.php?create" method="post">
                        <label for="username">Username:</label>
                        <input type="text" id="username" name="username" value="'.$username.'"><br><br>
                        <label for="password">Password:</label>
                        <input type="password" id="password" name="password"><br><br>
                        <label for="first_name">First Name:</label>
                        <input type="text" id="first_name" name="first_name"><br><br>
                        <label for="middle_initial">Middle Initial:</label>
                        <input type="text" id="middle_initial" name="middle_initial" maxlength="1"><br><br>
                        <label for="last_name">Last Name:</label>
                        <input type="text" id="last_name" name="last_name"><br><br>
                        <input type="submit" formaction="login.php" value="Back">
                        <input type="submit" value="Submit">
                    </form>
                </center>';
                // Exit so nothing else is displayed after account creation form
                exit();
            } else {
                // If token is incorrect, set error message
                $error = '<div class="invalid" ><p>Invalid token!</p></div>';
            }
            
        }
        // Prompt above form to enter token
        echo "<center>Please enter the token:";
        
        // Form for token entry
        echo '
            <form action="login.php?register" method="post">
                <input type="password" name="token">
                '.$error.'
                <input type="submit" value="Submit">
            </form></center>';

        // Exit so nothing is displayed after token prompt
        exit();
        
    }

    // If user creates an account ([Submit] button clicked on
    //      the account creation form)
    if (isset($_GET['create'])) {
        // Create a connection to the database
        $conn = new mysqli($servername, $db_username, $db_password, $db_name);
        if ($conn->connect_error) {
            // If the connection fails, display why
            die("Connection failed: " . $conn->connect_error);
        }

        // Get user input from profile creation form
        $username = $_POST["username"];
        $password = $_POST["password"];
        $first_name = $_POST["first_name"];
        $middle_initial = isset($_POST['middle_initial']) ? $_POST['middle_initial'] : "";
        $last_name = $_POST["last_name"];

        // Insert user information into the tbl_users table
        $sql = "INSERT INTO tbl_users (username, password, first_name, middle_initial, last_name)
            VALUES ('$username', '".sha1($password)."', '$first_name', '$middle_initial', '$last_name')";
        
        // If the query executes successfully
        if ($conn->query($sql) === TRUE) {
            // Set session variables for username and user first name
            $_SESSION['username'] = $username;
            $_SESSION['first_name'] = $first_name;

            // Let user know account was created successfully
            echo "<center>New account created successfully";
            // Display a button to let the user now logon
            echo '
                <form action="login.php" method="post">
                    <input type="submit" value="Login">
                </form></center>';
            // Exit so nothing is displayed after successful account creation
            //      message
            exit();
        } else {
            // If user information insertion query was not successful,
            //      display why it wasn't
            echo "Error: " . $sql . "<br>" . $conn->error;
        }

        // Close the connection to the database
        $conn->close();
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
        $sql = "SELECT first_name FROM tbl_users WHERE username='$username'";
        $username_result = $conn->query($sql);

        // If the username exists
        if ($username_result->num_rows > 0) {
            // Check is the password is correct
            $sql = "SELECT num_logons FROM tbl_users WHERE password='$password'";
            $logon_result = $conn->query($sql);
            // If the password is correct
            if ($logon_result->num_rows > 0) {
                // Start a session
                session_start();
                // Get the number of logons from the tbl_users 'num_logons' column
                $num_logons = $logon_result->fetch_assoc()["num_logons"];
                // Increment the number of successful logons
                $updated_num_logons = $num_logons + 1;
                // Set session variables to logon information
                $_SESSION['first_name'] = $username_result->fetch_assoc()["first_name"];
                $_SESSION['username'] = $username;
                $_SESSION['logged_in'] = true;

                // Update the number of logon and the last successful logon time in the tbl_users table
                $sql = "UPDATE tbl_users SET last_successful_logon=NOW(), num_logons=$updated_num_logons WHERE username='$username'";
                $update_tbl_users_result = $conn->query($sql);

                // Insert successful logon attempt information into tbl_logon_attempts table
                insertIntoLogonAttempsTable($conn, $username, "SUCCESS");

                // Redirect user to landing page (index.php)
                header("Location: ./");
            } else {
                // If the password was incorrect

                // Set error message to display password was incorrect
                $error_msg = "Incorrect password!";

                // Insert unsuccessful logon attempt information into tbl_logon_attempts table
                insertIntoLogonAttempsTable($conn, $username, "FAILURE");

                // Update the last unsuccessful logon time in the tbl_users table
                $sql = "UPDATE tbl_users SET last_unsuccessful_logon=NOW() WHERE username='$username'";
                $update_tbl_users_result = $conn->query($sql);

            }
        } else {
            // If the username did not exist

            // Set error message to display username does not exist
            $error_msg = "Username does not exist!";
            
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
            <input type="submit" formaction="login.php?register" value="Create account">
        </form>
    </center>
</body>
</html>