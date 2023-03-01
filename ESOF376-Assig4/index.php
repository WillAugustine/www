<?php
    $servername = "localhost";
    $username = "root";
    $password = "LOLzies101";

    $conn = new mysqli($servername, $username, $password);
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    $sql = "CREATE DATABASE IF NOT EXISTS test_user";
    if ($conn->query($sql) === TRUE) {
        // echo "Database created successfully";
    } else {
        echo "Error creating database: " . $conn->error;
    }
    $conn->close();

    //echo sha1('aslam');
    if (isset($_REQUEST['attempt']))
    {

        $user = $_POST['user'];
        $password = sha1($_POST['password']);


        // connect to the database
        $connect = mysqli_connect( 'localhost', 'root', 'LOLzies101', 'test_user' );
        if ( !$connect ) exit( 'connection failed: ' . mysqli_connect_error() );

        // create a query statement resource
        $stmt = mysqli_prepare( $connect,
        "select user from users where user=? and password=?" );

        if ( $stmt ) {
            // bind the substitution to the statement
            mysqli_stmt_bind_param( $stmt, "ss", $user, $password );
            // execute the statement
            mysqli_stmt_execute( $stmt );

            // retrieve the result...
            mysqli_stmt_bind_result( $stmt, $major );

            // ...and display it
            if ( mysqli_stmt_fetch( $stmt ) ) {
                // Regenerates session ID
                if (!empty($_POST['password']) && sha1($_POST['password']) === $password) {
                    session_regenerate_id();
                    $_SESSION['auth'] = TRUE;
                    session_start();
                    $_SESSION['user']= $user;
                    header('location: insert.php');
                }
                

            } else {
                echo "User does not exists";
            } 

            // clean up statement resource
            mysqli_stmt_close( $stmt );
        }
        mysqli_close( $connect );

    }

?>


<form action="index.php?attempt" method="post">
    Username: <input type="text" name="user" /><br />
    Password: <input type="password" name="password" /><br />
    <input type="submit" value="Login" />
</form>
<body>
    <p>
        Need to create an account? <br>
        <a href=create_login.html>Create Account</a>
    </p>
</body>
