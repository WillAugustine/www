<link href="style.css" rel="stylesheet" />
<?php

    include_once('header.php');

    function sendEmail($to, $subject, $body) {
        $email_link = "mailto:$to?subject=$subject&body=$body";
        $email_link = str_replace( PHP_EOL, '', $email_link);
        echo '
            <script type="text/javascript">
                window.location.href = "'.$email_link.'";
                window.location.href = "./";
            </script>';
    }

    if (isset($_SESSION['user_link'])) {
        $user_link = $_SESSION['user_link'];

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
        $user_email = empty($currentData['email']) ? "not provided" : $currentData['email'];
        $user_DoV = $currentData['dateOfVisit'];
        if (array_key_exists('email_sent', $_POST)) {
            $subject = $user_firstName . " " . $user_lastName . "'s Butte Archives Link for " . $user_DoV;

            $folder = explode("email_user", $_SERVER['REQUEST_URI'])[0];

            $link = $_SERVER['HTTP_HOST'] . $folder . "visitor.php?id=" . $user_link;

            $body = 'Thanks for visiting the Butte Archives! 
            %0D%0A%0D%0AHere is the link for your visit to the Saint Patrick Cemetery: 
            %0D%0Ahttp://'.$link;

            $subject = str_replace(' ', '%20', $subject);
            $body = str_replace(' ', '%20', $body);
            $user_email = ($user_email === "not provided") ? "" : $user_email;
            sendEmail($user_email, $subject, $body);
        }
        // Retrieve headstone IDs from HeadstonesForLinks table
        if ($stmt = $conn->prepare("SELECT * FROM `HeadstonesForLinks` WHERE `userLink`=?")) {
            $stmt->bind_param("s", $user_link);
            $stmt->execute();
            $result = $stmt->get_result();
            $headstoneData = $result->fetch_assoc();
        } else {
            die("Error: ". $conn->error);
        }

        // Retrieve headstone information from ButteArchivesRecords table
        $headstoneIDs = array();
        for ($i = 1; $i <= 5; $i++) {
            if (isset($headstoneData["headstoneID_$i"])) {
                array_push($headstoneIDs, $headstoneData["headstoneID_$i"]);
            }
        }
        $in = str_repeat('?,', count($headstoneIDs) - 1) . '?';
        if ($stmt = $conn->prepare("SELECT * FROM `ButteArchivesRecords` WHERE `ID` IN ($in)")) {
            $stmt->bind_param(str_repeat('i', count($headstoneIDs)), ...$headstoneIDs);
            $stmt->execute();
            $result = $stmt->get_result();
        } else {
            die("Error: ". $conn->error);
        }
    };
?>
<div class='confirmation_info'>
    <h2>Does the following information look correct for <?php echo $user_firstName." ".$user_lastName ?>?</h2>

    <h3>Visitor Information:</h3>

    <table>
        <tr>
            <th>Email</th>
            <th>Date of visit</th>
        </tr>
            <td><?php echo $user_email ?></td>
            <td><?php echo $user_DoV ?></td>
    </table>

    <h3>Headstones they're visiting:</h3>

    <table>
        <tr>
            <th>#</th>
            <th>Name</th>
            <th>Block</th>
            <th>Lot</th>
            <th>Plot</th>
            <th>Date of Death</th>
            <th>Age</th>
        </tr>

        <?php
        $counter = 1;
        while ($row = $result->fetch_assoc()) { ?>
            <tr>
                <td id="counter"><?php echo $counter ?></td>
                <td id="name"><?php echo $row['name'] ?></td>
                <td id="block"><?php echo $row['block'] ?></td>
                <td id="lot"><?php echo $row['lot'] ?></td>
                <td id="plot"><?php echo $row['plot'] ?></td>
                <td id="dateOfDeath"><?php echo date('m-d-Y', strtotime($row['dateOfDeath'])) ?></td>
                <td id="age"><?php echo $row['age'] ?></td>
            </tr>
            <?php
            $counter++; 
        } ?>
    </table>

    <form method ="post">
        <input type="submit" name="email_sent" class="button" value="Yes" />
        <input type="submit" name="incorrect_info" class="button" value="No" />
    </form>
</div>