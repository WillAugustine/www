<link href="style.css" rel="stylesheet" />
<?php

    include_once('header.php');
    if (isset($_GET['link'])) {
        $user_link = $_GET['link'];
        echo "Where does the user with code " . $user_link . " want to go?";
    };

    define("DB_SERVER", "localhost");
    define("DB_USER", "ButteArchives");
    define("DB_PASSWORD", 'password');
    define("DB_DATABASE", "CemeteryLocatorApplication");

    if (isset($_REQUEST['attempt']))
    {
        $user_link = $_POST['link'];
        $block = $_POST['block'];
        $lot = $_POST['lot'];
        $plot = $_POST['plot'];
        $name = $_POST['name'];
        $index = (int)$_POST['headstoneIndex'];
        $index += 1;
        $DoD = "";
        $age = "";
        $undertaker = "";

        

        
        $sql_insert_variables =  "INSERT INTO ButteArchivesRecords (block, lot, plot, name";
        $sql_insert_values = "VALUES (" . $block . ", " . $lot . ", " . $plot . ", '" . $name . "'";

        if (!empty($_POST['dateOfDeath'])) {
            $DoD = $_POST['dateOfDeath'];
            $sql_insert_variables .= ", dateOfDeath";
            $sql_insert_values .= ", " . $DoD;
        }
        if (!empty($_POST['age'])) {
            $age = $_POST['age'];
            $sql_insert_variables .= ", age";
            $sql_insert_values .= ", " . $age;
        }
        if (!empty($_POST['undertaker'])) {
            $undertaker = $_POST['undertaker'];
            $sql_insert_variables .= ", undertaker";
            $sql_insert_values .= ", " . $undertaker;
        }
        
        $sql_insert_variables .= ") ";
        $sql_insert_values .= ")";
        $sql = $sql_insert_variables . $sql_insert_values;

        if (array_key_exists('newInformation', $_POST)) {
            if ($delete_sql = $conn->prepare("DELETE FROM `ButteArchivesRecords` WHERE 
            `block`=? AND 
            `lot`=? AND 
            `plot`=?")) {
                $stmt->bind_param("iii", $block, $lot, $plot);
            } else {
                die("Error: ". $conn->error);
            }
            $stmt->execute();
            $stmt->close();
            $conn->query($sql);
            header("Location: add_headstones.php?link=' . $user_link . '&headstoneIndex=' . $index");
        }
        
        // echo "user link: " . $user_link . "<br>";
        // echo "block: " . $block . "<br>";
        // echo "lot: " . $lot . "<br>";
        // echo "plot: " . $plot . "<br>";
        // echo "name: " . $name . "<br>";
        // echo "index: " . $index . "<br>";

        // connect to the database
        $conn = new mysqli( DB_SERVER, DB_USER, DB_PASSWORD, DB_DATABASE );
        if ( $conn->connect_error ) exit( 'connection failed: ' . $conn->connect_error );

        // Select data with block, lot, polt, name from ButteArchivesRecords
        if ($stmt = $conn->prepare("SELECT * FROM `ButteArchivesRecords` WHERE 
            `block`=? AND 
            `lot`=? AND 
            `plot`=?")) {
            $stmt->bind_param("iii", $block, $lot, $plot);

        } else {
            die("Error: ". $conn->error);
        }
        $stmt->execute();
        $result = $stmt->get_result();
        $currentData = $result->fetch_assoc();
        

        if (isset($currentData)) {
            echo "There is already someone buried at
            ".$block."-".$lot."-".$plot."!<br><br>";
            $currentName = $currentData['name'];
            $currentDoD = $currentData['dateOfDeath'];
            $currentAge = $currentData['age'];
            $currentUndertaker = $currentData['undertaker'];
            echo "Please select which is correct:<br><br>";
            echo '
            <table style="width:100%">
                <tr>
                    <th>Details</th>
                    <th>Information you inputted</th>
                    <th>Information currently in records</th>
                </tr>
                <tr>
                    <td>Name</td>
                    <td>'.$name.'</td>
                    <td>'.$currentName.'</td>
                </tr>
                <tr>
                    <td>Date of Death</td>
                    <td>'.$DoD.'</td>
                    <td>'.$currentDoD.'</td>
                </tr>
                <tr>
                    <td>Age</td>
                    <td>'.$age.'</td>
                    <td>'.$currentAge.'</td>
                </tr>
                <tr>
                    <td>Undertaker</td>
                    <td>'.$undertaker.'</td>
                    <td>'.$currentUndertaker.'</td>
                </tr>
            </table><br><br>';
            

        } else {
            // echo "currentData is NOT set<br>";
            // echo "sql: '" . $sql . "<br>";
            if ( $conn->query($sql) ) {
                echo "Added data!<br>";
            } else {
                echo "Error: " . $conn->error . "<br>";
            }
        }
        $stmt->close();

        echo '
            <form method="post">
                <input type="submit" name="newInformation" class="button" value="The information I inputted is correct" />
            </form>';
        echo '<a class="button" href="add_headstones.php?link=' . $user_link . '&headstoneIndex=' . $index . '">They are both buried at '.$block.'-'.$lot.'-'.$plot.'</a>';


        // If an entry exists, prompt user to select which one is correct
        //      If new info if correct, update ButteArchivesRecords with new information
        //      If current info is correct, set values equal to current info


        // $stmt = $conn->prepare("INSERT INTO Users (firstName, lastName, email, dateOfVisit, uniqueLink)
        //     VALUES (?, ?, ?, ?, ?)");
        // $stmt->bind_param("sssss", $user_firstName, $user_lastName, $user_email, $user_DoV, $newUserCode);
        // if ( $stmt->execute() ) {

        //     header("Location: add_headstones.php?id=".$newUserCode);
        // }
        // $stmt->close();
        // $conn->close();
    }
?>