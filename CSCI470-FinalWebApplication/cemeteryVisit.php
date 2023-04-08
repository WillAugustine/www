<?php

    include_once('header.php');
    if (isset($_GET['id'])) {
        $user_link = $_GET['id'];

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

    

    // if (isset($_REQUEST['attempt']))
    // {
    //     $user_link = $_POST['link'];
    //     $block = $_POST['block'];
    //     $lot = $_POST['lot'];
    //     $plot = $_POST['plot'];
    //     $name = $_POST['name'];
    //     $index = (int)$_POST['headstoneIndex'];
    //     $index += 1;

        
    //     $sql_insert_variables =  "INSERT INTO ButteArchivesRecords (block, lot, plot, name";
    //     $sql_insert_values = "VALUES (" . $block . ", " . $lot . ", " . $plot . ", '" . $name . "'";

    //     if (!empty($_POST['dateOfDeath'])) {
    //         $DoD = $_POST['dateOfDeath'];
    //         $sql_insert_variables .= ", dateOfDeath";
    //         $sql_insert_values .= ", " . $DoD;
    //     }
    //     if (!empty($_POST['age'])) {
    //         $age = $_POST['age'];
    //         $sql_insert_variables .= ", age";
    //         $sql_insert_values .= ", " . $age;
    //     }
    //     if (!empty($_POST['undertaker'])) {
    //         $undertaker = $_POST['undertaker'];
    //         $sql_insert_variables .= ", undertaker";
    //         $sql_insert_values .= ", " . $undertaker;
    //     }
        
    //     $sql_insert_variables .= ") ";
    //     $sql_insert_values .= ")";
    //     $sql = $sql_insert_variables . $sql_insert_values;

    //     echo "userID: " . $user_ID . "<br>";
    //     echo "block: " . $block . "<br>";
    //     echo "lot: " . $lot . "<br>";
    //     echo "plot: " . $plot . "<br>";
    //     echo "name: " . $name . "<br>";
    //     echo "index: " . $index . "<br>";

        
        

    //     if (isset($currentData)) {
    //         echo "currentData is set<br>";
    //         echo "Current Data:<br><pre>";
    //         print_r($currentData);
    //         echo "</pre><br>";
    //     } else {
    //         echo "currentData is NOT set<br>";
    //         // echo "sql: '" . $sql . "<br>";
    //         if ( $conn->query($sql) ) {
    //             echo "Added data!<br>";
    //         } else {
    //             echo "Error: " . $conn->error . "<br>";
    //         }
    //     }
    //     $stmt->close();

    //     echo '<b>Does everything look correct?</b>';
    //     echo '<a class="button" href="add_headstones.php?link=' . $user_link . '&headstoneIndex=' . $index . '">Yes</a>';


    //     // If an entry exists, prompt user to select which one is correct
    //     //      If new info if correct, update ButteArchivesRecords with new information
    //     //      If current info is correct, set values equal to current info


    //     // $stmt = $conn->prepare("INSERT INTO Users (firstName, lastName, email, dateOfVisit, uniqueLink)
    //     //     VALUES (?, ?, ?, ?, ?)");
    //     // $stmt->bind_param("sssss", $user_firstName, $user_lastName, $user_email, $user_DoV, $newUserCode);
    //     // if ( $stmt->execute() ) {

    //     //     header("Location: add_headstones.php?id=".$newUserCode);
    //     // }
    //     // $stmt->close();
    //     // $conn->close();
    // }
?>