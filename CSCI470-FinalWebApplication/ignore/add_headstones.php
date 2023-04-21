<!DOCTYPE html>
<?php
    include_once('header.php');
    if (isset($_GET['link'])) {
        $user_link = $_GET['link'];
        echo "Where does the user with code " . $user_link . " want to go?<br>";
    };
    $index = $_GET['headstoneIndex'];
    echo "The user has " . $index . " headstones added!";

    $db_server = "localhost";
    $db_username = "ButteArchives";
    $db_password= "password";
    $db_database= "CemeteryLocatorApplication";

    if (isset($_REQUEST['attempt']))
    {
        $user_ID = $_POST['link'];
        $block = $_POST['block'];
        $lot = $_POST['lot'];
        $plot = $_POST['plot'];
        $name = $_POST['name'];
        if (isset($_POST['dateOfDeath'])) $DoD = $_POST['dateOfDeath'];
        if (isset($_POST['dateOfDeath'])) $DoD = $_POST['dateOfDeath'];
        if (isset($_POST['dateOfDeath'])) $DoD = $_POST['dateOfDeath'];

        echo "userID: " . $user_ID . "<br>";
        echo "block: " . $block . "<br>";
        echo "lot: " . $lot . "<br>";
        echo "plot: " . $plot . "<br>";
        echo "name: " . $name . "<br>";

        // connect to the database
        // $conn = new mysqli( $db_server, $db_username, $db_password, $db_database );
        // if ( $conn->connect_error ) exit( 'connection failed: ' . $conn->connect_error );
        // $stmt = $conn->prepare("INSERT INTO Users (firstName, lastName, email, dateOfVisit, uniqueLink)
        //     VALUES (?, ?, ?, ?, ?)");
        // $stmt->bind_param("sssss", $user_firstName, $user_lastName, $user_email, $user_DoV, $newUserCode);
        // if ( $stmt->execute() ) {

        //     header("Location: add_headstones.php?id=".$newUserCode);
        // }
        // $stmt->close();
        // $conn->close();
    }
if ($index === 0) {
    echo '
    <form action="populate_records.php?attempt" method="post">
        <pre>
            Block: <input type="text" name="block" /><br />
            Lot: <input type="text" name="lot" /><br />
            Plot: <input type="text" name="plot" /><br />
            Name: <input type="text" name="name" /><br />
            Date of Death: <input type="date" name="dateOfDeath" /><br />
            Age: <input type="text" name="age" /><br />
            Undertaker: <input type="text" name="undertaker" /><br />
            <input type="hidden" name="link" value="'.$user_link.'"/> 
            <input type="hidden" name="headstoneIndex" value="'.$index.'"/> 
            <input type="submit" value="Add Headstone" />
        </pre>
    </form>';
}
else if ($index <= 5) {
    echo '
    <form action="populate_records.php?attempt" method="post">
        <pre>
            Block: <input type="text" name="block" /><br />
            Lot: <input type="text" name="lot" /><br />
            Plot: <input type="text" name="plot" /><br />
            Name: <input type="text" name="name" /><br />
            Date of Death: <input type="date" name="dateOfDeath" /><br />
            Age: <input type="text" name="age" /><br />
            Undertaker: <input type="text" name="undertaker" /><br />
            <input type="hidden" name="link" value="'.$user_link.'"/> 
            <input type="hidden" name="headstoneIndex" value="'.$index.'"/> 
            <input type="submit" value="Add Headstone" />
            <input type="submit" formaction="email_user.php?id='.$user_link.'" value="Send link to user"/>
        </pre>
    </form>';
}
?>