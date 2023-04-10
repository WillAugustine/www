<!DOCTYPE html>
<link rel="stylesheet" href="styles.css">
<?php

    include("header.php");

    function insertIntoLogonAttempsTable($conn, $username, $status) {
        $ip = $_SERVER['REMOTE_ADDR'];
        $agent = $_SERVER['HTTP_USER_AGENT'];
        // $logged_in = isset($_SESSION['logged_in']) ? $_SESSION['logged_in'] : false;
        // $status = $logged_in ? "SUCCESS" : "FAILURE";
                
        $sql = "INSERT INTO tbl_logon_attempts (username, attempt_datetime, status, ipaddress, user_agent)
            VALUES ('$username', NOW(), '$status', '$ip', '$agent')";
        $conn->query($sql);
    }
    
    $servername = "localhost";
    $db_username = "diaryappdbuser";
    $db_password = "DiaryPass$";
    $db_name = "diaryappdb";

    if (isset($_GET['logout'])) {
        session_start();
        $_SESSION['logged_in'] = false;
        session_destroy();
        header("Location: ./");
    }

    if (isset($_GET['register'])) {
        $error = '<p> <br> </p>';

        if (isset($_POST['token'])) {
            if ($_POST['token'] === "CSCI 470 token") {
                $username = isset($_POST['username']) ? $_POST['username'] : "";
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
                exit();
            } else {
                $error = '<div class="invalid" ><p>Invalid token!</p></div>';
            }
            
        }
                        
        echo "<center>Please enter the token:";
        
        
        echo '
            <form action="login.php?register" method="post">
                <input type="password" name="token">
                '.$error.'
                <input type="submit" value="Submit">
            </form></center>';
        exit();
        
    }

    if (isset($_GET['create'])) {
        $conn = new mysqli($servername, $db_username, $db_password, $db_name);
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        // Get user input
        $username = $_POST["username"];
        $password = $_POST["password"];
        $first_name = $_POST["first_name"];
        $middle_initial = isset($_POST['middle_initial']) ? $_POST['middle_initial'] : "";
        $last_name = $_POST["last_name"];

        // Insert customer information into the Customers table
        $sql = "INSERT INTO tbl_users (username, password, first_name, middle_initial, last_name)
            VALUES ('$username', '".sha1($password)."', '$first_name', '$middle_initial', '$last_name')";
        if ($conn->query($sql) === TRUE) {
            $_SESSION['username'] = $username;
            $_SESSION['first_name'] = $first_name;
            echo "<center>New account created successfully";
            echo '
                <form action="login.php" method="post">
                    <input type="submit" value="Login">
                </form></center>';
                exit();
        } else {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }

        $conn->close();
    }

    $error_msg = null;

    if (isset($_GET['request'])) {
        $conn = new mysqli($servername, $db_username, $db_password, $db_name);
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        // Get user input
        $username = $_POST["username"];
        $password = sha1($_POST["password"]);
        

        // Check if the username and password are correct
        $sql = "SELECT first_name FROM tbl_users WHERE username='$username'";
        $username_result = $conn->query($sql);
        if ($username_result->num_rows > 0) {
            $sql = "SELECT num_logons FROM tbl_users WHERE password='$password'";
            $logon_result = $conn->query($sql);
            if ($logon_result->num_rows > 0) {
                // Login successful
                session_start();
                $num_logons = $logon_result->fetch_assoc()["num_logons"];
                $updated_num_logons = $num_logons + 1;
                $_SESSION['first_name'] = $username_result->fetch_assoc()["first_name"];
                $_SESSION['username'] = $username;
                $_SESSION['logged_in'] = true;

                $sql = "UPDATE tbl_users SET last_successful_logon=NOW(), num_logons=$updated_num_logons WHERE username='$username'";
                $update_tbl_users_result = $conn->query($sql);

                insertIntoLogonAttempsTable($conn, $username, "SUCCESS");

                header("Location: ./");
            } else {
                $error_msg = "Incorrect password!";

                insertIntoLogonAttempsTable($conn, $username, "FAILURE");

                $sql = "UPDATE tbl_users SET last_unsuccessful_logon=NOW() WHERE username='$username'";
                $update_tbl_users_result = $conn->query($sql);

            }
        } else {
            $error_msg = "Username does not exist!";
            // $_SESSION['username_attempt'] = $username;
        }

        
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
<!-- <?php include("footer.php");?> -->