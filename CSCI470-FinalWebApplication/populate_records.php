<?php

    session_start();
    define("DB_SERVER", "localhost");
    define("DB_USER", "ButteArchives");
    define("DB_PASSWORD", 'password');
    define("DB_DATABASE", "CemeteryLocatorApplication");

    // Connect to the database
    $conn = new mysqli( DB_SERVER, DB_USER, DB_PASSWORD, DB_DATABASE );
    if ( $conn->connect_error ) exit( 'connection failed: ' . $conn->connect_error );
    $maxX = $_POST['maxX'];
    $minX = $_POST['minX'];
    $maxY = $_POST['maxY'];
    $minY = $_POST['minY'];
    $imageWidth = $_POST['imageWidth'];

    echo "($minX, $minY) -> ($maxX, $maxY)<br>";
    // Insert the highlighting data into the Highlights table
    $stmt = $conn->prepare("INSERT INTO Highlights (maxX, minX, maxY, minY, imageWidth) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("ddddd", $maxX, $minX, $maxY, $minY, $imageWidth);
    if($stmt->execute()){
        $sql = "SELECT LAST_INSERT_ID()";
        $result = $conn->query($sql);
        $highlightID = $result->fetch_assoc()['LAST_INSERT_ID()'];
        echo $highlightID . "<br>";
        $name = empty($_SESSION["name"]) ? null : $_SESSION["name"];
        $block = empty($_SESSION["block"]) ? null : $_SESSION["block"];
        echo "block: '" . $block . "'<br>";
        $lot = empty($_SESSION["lot"]) ? null : $_SESSION["lot"];
        $plot = empty($_SESSION["plot"]) ? null : $_SESSION["plot"];
        $DoD = empty($_SESSION["dateOfDeath"]) ? null : $_SESSION["dateOfDeath"];
        $age = empty($_SESSION["age"]) ? null : $_SESSION["age"];
        $undertaker = empty($_SESSION["undertaker"]) ? null : $_SESSION["undertaker"];
        $stmt = $conn->prepare("INSERT INTO ButteArchivesRecords
            (block, lot, plot, name, dateOfDeath, age, undertaker, highlightID)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("siissssi", $block, $lot, $plot, $name, $DoD, $age, $undertaker, $highlightID);
        if($stmt->execute()){
            $sql = "SELECT LAST_INSERT_ID()";
            $result = $conn->query($sql);
            $blockID = $result->fetch_assoc()['LAST_INSERT_ID()'];
            $index = $_SESSION['headstone_index'];
            $colToPopulate = "headstoneID_".$index;
            $visitor_link = $_SESSION['user_link'];
            $stmt = $conn->prepare("UPDATE HeadstonesForLinks SET $colToPopulate = ? WHERE userLink = ?");
            $stmt->bind_param("is", $blockID, $visitor_link);
            if ($stmt->execute()) {
                header("Location: create_new_user.php?add_headstones");

            } else {
                echo "ERROR (HeadstonesForLinks): " . $stmt->error . "<br>";
            }
        } else {
            echo "ERROR (ButteArchivesRecords): " . $stmt->error . "<br>";
        }

        // header("Location: create_new_user.php?add_headstones");
    } else {
        echo "ERROR: " . $stmt->error . "<br>";
    }

    $stmt->close();
    exit();

?>