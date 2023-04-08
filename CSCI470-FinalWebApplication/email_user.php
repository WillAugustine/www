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
        $user_email = $currentData['email'];
        $user_DoV = $currentData['dateOfVisit'];
        if (array_key_exists('email_sent', $_POST)) {
            $subject = $user_firstName . " " . $user_lastName . "'s Butte Archives Link for " . $user_DoV;

            $folder = explode("email_user", $_SERVER['REQUEST_URI'])[0];

            $link = $_SERVER['HTTP_HOST'] . $folder . "cemeteryVisit.php?id=" . $user_link;

            $body = 'Thanks for visiting the Butte Archives! 
            %0D%0A%0D%0AHere is the link for your visit to the Saint Patrick Cemetery: 
            %0D%0Ahttp://'.$link;

            $subject = str_replace(' ', '%20', $subject);
            $body = str_replace(' ', '%20', $body);
            sendEmail($user_email, $subject, $body);
        }
        printf("Does the following infomormation look correct for %s %s?\n", $user_firstName, $user_lastName);
        echo "<br><br>";
        echo '
            <table style="width:250px">
                <tr>
                    <th>Email</th>
                    <th>Date of visit</th>
                </tr>
                    <td text-align="center">'.$user_email.'</td>
                    <td>'.$user_DoV.'</td>
            </table>
        ';
        echo "<br><h3>Headstones:</h3><br>";
        echo '
            <table style="width:750px">
                <tr>
                    <th style="width:10%">Block</th>
                    <th style="width:10%">Lot</th>
                    <th style="width:10%">Plot</th>
                    <th>Name</th>
                </tr>
            </table><br><br>
        ';
    };
    $subject = "Butte Archives Link";

    $folder = explode("email_user", $_SERVER['REQUEST_URI'])[0];

    $link = $_SERVER['HTTP_HOST'] . $folder . "cemeteryVisit.php?id=" . $user_link;

    $body = 'Thanks for visiting the Butte Archives! 
    %0D%0A%0D%0AHere is the link for your visit to the Saint Patrick Cemetery: 
    %0D%0Ahttp://'.$link;

    $subject = str_replace(' ', '%20', $subject);
    $body = str_replace(' ', '%20', $body);

    echo '<form method ="post">
            <input type="submit" name="email_sent" class="button" value="Yes" />
        </form>';
?>