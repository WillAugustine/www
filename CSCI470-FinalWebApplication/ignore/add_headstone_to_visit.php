<!DOCTYPE html>
<?php

    $user_link = "";
    $headstoneID = "";
    $index = "";

    if (isset($_GET['link'])) {
        $user_link = $_GET['link'];
        echo "Where does the user with code " . $user_link . " want to go?<br>";
    };
    if (isset($_GET['headstoneID'])) {
        $headstoneID = $_GET['headstoneID'];
    }
    if (isset($_GET['index'])) {
        $index = $_GET['index'];
    }

    $db_server = "localhost";
    $db_username = "ButteArchives";
    $db_password= "password";
    $db_database= "CemeteryLocatorApplication";
    
    $conn = new mysqli( $db_server, $db_username, $db_password, $db_database );
    if ( $conn->connect_error ) exit( 'connection failed: ' . $conn->connect_error );

    if ($stmt = $conn->prepare("SELECT ID FROM `Users` WHERE `uniqueLink`=?")) {
        $stmt->bind_param("s", $user_link);
    } else {
        die("Error selecting ID from Users: ". $conn->error);
    }
    $stmt->execute();
    $result = $stmt->get_result();
    $currentData = $result->fetch_assoc();
    if (isset($currentData)) {
        $user_id = $currentData['ID'];
    }
    $stmt->close();

    $column = "headstoneID_".$index;
    echo "column: $column<br>";

    if ($index == 1) {
        if ($stmt = $conn->prepare("INSERT INTO `HeadstonesForLinks`
            (`userLink`, `".$column."`) VALUES (?, ?)")) {
            $stmt->bind_param("si", $user_link, $headstoneID);
        } else {
            die("Error creating entry for HeadstonesForLinks: ". $conn->error);
        }
    } else {
        if ($stmt = $conn->prepare("UPDATE `HeadstonesForLinks` SET `".$column."` = ? WHERE `userLink` = ?")) {
            $stmt->bind_param("is", $headstoneID, $user_link);
        } else {
            die("Error appending headstone to HeadstonesForLinks: ". $conn->error);
        }
    }
    $stmt->execute();
    $stmt->close();

    echo '<a class="button" href="add_headstones.php?link=' . $user_link . '&headstoneIndex='.$index.'">Continue</a>';
    // header("Location: add_headstones.php?link=$user_link&headstoneIndex=$index");

?>