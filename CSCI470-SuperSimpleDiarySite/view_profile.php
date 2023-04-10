<!DOCTYPE html>
<link rel="stylesheet" href="styles.css">

<head>
    <title>Logon History Profile</title>
</head>

<?php include("header.php") ?>

<body>
    <center><h1>Logon History Profile</h1></center>
    <?php
        // session_start();
        $servername = "localhost";
        $username = "diaryappdbuser";
        $password = "DiaryPass$";
        $dbname = "diaryappdb";

        // Create connection
        $conn = new mysqli($servername, $username, $password, $dbname);
        // Check connection
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        // Get the user's logon history from the database
        $sql = "SELECT attempt_datetime, status FROM tbl_logon_attempts WHERE username='" . $_SESSION['username'] . "' ORDER BY attempt_datetime DESC";
        $result = $conn->query($sql);
    ?>
    <div class="logon-history">
    <?php if ($result->num_rows > 0): ?>
        
        <table>
            <tr>
                <th>Attempt Datetime</th>
                <th>Status</th>
            </tr>
            <?php while($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?php echo $row["attempt_datetime"] ?></td>
                    <td><?php echo $row["status"] ?></td>
                </tr>
            <?php endwhile; ?>
        </table>
    <?php else: ?>
        <p style="text-align:center;">No logon history found</p>
    <?php endif; ?>
        </div>

    <?php $conn->close(); ?>
    
</body>