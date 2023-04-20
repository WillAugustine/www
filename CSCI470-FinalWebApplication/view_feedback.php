<link href="styles.css" rel="stylesheet" />
<?php

    include_once('header.php');

    define("DB_SERVER", "localhost");
    define("DB_USER", "ButteArchives");
    define("DB_PASSWORD", 'password');
    define("DB_DATABASE", "CemeteryLocatorApplication");
    
    unset($_SESSION['visitor_name']);

    // connect to the database
    $conn = new mysqli( DB_SERVER, DB_USER, DB_PASSWORD, DB_DATABASE );
    if ( $conn->connect_error ) exit( 'connection failed: ' . $conn->connect_error );

?>
<div class='feedback'>
    <h2>Feedback:</h2>

    <table>
        <tr>
            <th>Visit Date</th>
            <th>Visitor</th>
            <th>Headstones Found?</th>
            <th>Recommend?</th>
            <th>Use Again?</th>
            <th>Comments</th>
        </tr>

        <?php
            $sql = "SELECT dateOfVisit, CONCAT(firstName, ' ', lastName) AS visitor, headstoneFound, recommend, useAgain, comments FROM Feedback INNER JOIN Users ON Feedback.userID = Users.ID ORDER BY dateOfVisit DESC LIMIT 10";
            $result = $conn->query($sql);
            if ($result->num_rows > 0) {
                while($row = $result->fetch_assoc()) { ?>
                    <tr>
                        <td id="date"> <?php echo date('m-d-Y', strtotime($row["dateOfVisit"])) ?> </td>
                        <td id="name"> <?php echo $row["visitor"] ?> </td>
                        <td id="yesno"> <?php echo ($row["headstoneFound"] ? "Yes" : "No") ?> </td>
                        <td id="yesno"> <?php echo ($row["recommend"] ? "Yes" : "No") ?> </td>
                        <td id="yesno"> <?php echo ($row["useAgain"] ? "Yes" : "No") ?> </td>
                        <td id="comments"> <?php echo $row["comments"] ?> </td>
                    </tr>
                <?php }
            }
        ?>
    </table>

</div>