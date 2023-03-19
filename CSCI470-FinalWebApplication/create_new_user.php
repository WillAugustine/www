<!DOCTYPE html>
<?php
    include_once('header.php');
    echo "Time to create a new user<br><br><br>";

    function getRandomString($n)
    {
        $possibleChars = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $randomString = '';
        for ($i = 0; $i < $n; $i++) {
            $index = rand(0, strlen($possibleChars) - 1);
            $randomString  .= $possibleChars[$index];
        }
        return $randomString;
    }

    define("DB_SERVER", "localhost");
    define("DB_USER", "ButteArchives");
    define("DB_PASSWORD", 'password');
    define("DB_DATABASE", "CemeteryLocatorApplication");

    //echo sha1('aslam');
    if (isset($_REQUEST['attempt']))
    {

        
        $newUserCode = getRandomString(25);
        echo "User's code = " . $newUserCode . "<br><br><br>";

        $user_firstName = $_POST['firstName'];
        $user_lastName = $_POST['lastName'];
        $user_email = $_POST['email'];
        $user_DoV = $_POST['dateOfVisit'];


        // connect to the database
        $connect = mysqli_connect( DB_SERVER, DB_USER, DB_PASSWORD, DB_DATABASE );
        if ( !$connect ) exit( 'connection failed: ' . mysqli_connect_error() );

        // create a query statement resource
        $stmt = mysqli_execute_query( $connect,
        "select ID from Users where max(ID)" );

        if ( $stmt ) {
            // execute the statement
            $currMaxID = mysqli_stmt_execute( $stmt );
            echo "Curr max ID: " . $currMaxID . "<br>";
            $user_ID = $currMaxID + 1;
            // bind the substitution to the statement
            mysqli_stmt_bind_param( $stmt, "ss", $user, $password );

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


<form action="create_new_user.php?attempt" method="post">
    First Name: <input type="text" name="firstName" /><br />
    Last Name: <input type="text" name="lastName" /><br />
    Email: <input type="text" name="email" /><br />
    Date of Visit: <input type="date" name="dateOfVisit" /><br />
    <input type="submit" value="Create User" />
</form>