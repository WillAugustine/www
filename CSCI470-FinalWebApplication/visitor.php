<?php
    if (isset($_GET['id'])) {
        session_start();
        $_SESSION['visitor'] = true;
        $_SESSION['user_link'] = $_GET['id'];
        include('header.php');

        $user_link = $_GET['id'];

        if (isset($_REQUEST['feedback'])) {
            echo "Please provide feedback!<br>";
            exit();
        }        


        define("DB_SERVER", "localhost");
        define("DB_USER", "ButteArchives");
        define("DB_PASSWORD", 'password');
        define("DB_DATABASE", "CemeteryLocatorApplication");
        // connect to the database
        $conn = new mysqli( DB_SERVER, DB_USER, DB_PASSWORD, DB_DATABASE );
        if ( $conn->connect_error ) exit( 'connection failed: ' . $conn->connect_error );

        // Select data with block, lot, polt, name from ButteArchivesRecords
        if ($stmt = $conn->prepare("SELECT * FROM `Users` WHERE `uniqueLink`=?")) {
            $stmt->bind_param("s", $user_link);

        } else {
            die("Error: ". $conn->error);
        }
        $stmt->execute();
        $result = $stmt->get_result();
        $currentData = $result->fetch_assoc();
        $user_firstName = $currentData['firstName'];
        $user_lastName = $currentData['lastName'];
        printf("Welcome %s %s!", $user_firstName, $user_lastName);
    };
?>