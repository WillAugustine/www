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

        $user_firstName = $_POST['firstName'];
        $user_lastName = $_POST['lastName'];
        $user_email = $_POST['email'];
        $user_DoV = $_POST['dateOfVisit'];


        // connect to the database
        $conn = new mysqli( DB_SERVER, DB_USER, DB_PASSWORD, DB_DATABASE );
        if ( $conn->connect_error ) exit( 'connection failed: ' . $conn->connect_error );
        // echo "firstName: " . $user_firstName . "<br>";
        // echo "lastName: " . $user_lastName . "<br>";
        // echo "email: " . $user_email . "<br>";
        // echo "date of visit: " . $user_DoV . "<br>";
        // echo "unique code: " . $newUserCode . "<br>";
        $stmt = $conn->prepare("INSERT INTO Users (firstName, lastName, email, dateOfVisit, uniqueLink)
            VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("sssss", $user_firstName, $user_lastName, $user_email, $user_DoV, $newUserCode);
        if ( $stmt->execute() ) {

            header("Location: add_headstones_to_user.php?id=".$newUserCode);
        }
        $stmt->close();
        $conn->close();
    }


?>


<form action="create_new_user.php?attempt" method="post">
    First Name: <input type="text" name="firstName" /><br />
    Last Name: <input type="text" name="lastName" /><br />
    Email: <input type="text" name="email" /><br />
    Date of Visit: <input type="date" name="dateOfVisit" /><br />
    <input type="submit" value="Create User" />
</form>