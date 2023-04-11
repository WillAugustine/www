<!--
    Author: Will Augustine
        Description: PHP file for viewing profile/logon information/history
-->

<!DOCTYPE html>

<!-- Import styling from styles.css -->
<link rel="stylesheet" href="styles.css">

<head>
    <title>Logon History Profile</title>
</head>

<!-- Include the header at the top of the webpage -->
<?php include("header.php") ?>

<body>
    <center><h1>Logon History Profile</h1></center>
    <?php
        // Database connection information
        $servername = "localhost";
        $username = "diaryappdbuser";
        $password = "DiaryPass$";
        $dbname = "diaryappdb";

        // Create database connection
        $conn = new mysqli($servername, $username, $password, $dbname);

        // If the connection fails, display why
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        // Get the user's logon history from the database
        $sql = "SELECT attempt_datetime, status FROM tbl_logon_attempts WHERE username='" . $_SESSION['username'] . "' ORDER BY attempt_datetime DESC";
        $result = $conn->query($sql);
    ?>
    <div class="logon-history">

    <!-- If there are entries in tbl_logon_attemps for the user -->
    <?php if ($result->num_rows > 0): ?>
        
        <!-- Create a table to display logon history -->
        <table>
            <!-- Table header row -->
            <tr>
                <th>Attempt Datetime</th>
                <th>Status</th>
            </tr>
            <!-- For each element in tbl_logon_attemps for the user -->
            <?php while($row = $result->fetch_assoc()): ?>
                <!-- Display the attempt datetime and the resulting status -->
                <tr>
                    <td><?php echo $row["attempt_datetime"] ?></td>
                    <td><?php echo $row["status"] ?></td>
                </tr>
            <?php endwhile; ?>
        </table>
    <!-- If there are no entries in tbl_logon_attempts for the user -->
    <?php else: ?>
        <!-- Display there is no logon history -->
        <p style="text-align:center;">No logon history found</p>
    <?php endif; ?>
        </div>

    <!-- Close database connection -->
    <?php $conn->close(); ?>
    
</body>