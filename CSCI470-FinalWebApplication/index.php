<!DOCTYPE html>
<?php
    $servername = "localhost";
    $username = "root";
    $password = "";
    $database = "CemeteryLocatorApplication";

    //echo sha1('aslam');
    if (isset($_REQUEST['attempt']))
    {

        $user = $_POST['user'];
        $password = sha1($_POST['password']);


        // connect to the database
        $connect = mysqli_connect( $servername, $username, null, $database );
        if ( !$connect ) exit( 'connection failed: ' . mysqli_connect_error() );

        // create a query statement resource
        $stmt = mysqli_prepare( $connect,
        "select username from authorized_users where username=? and password=?" );

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
                    header('location: butte_archives.php');
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