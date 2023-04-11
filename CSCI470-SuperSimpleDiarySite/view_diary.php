<!--
    Author: Will Augustine
        Description: PHP file for viewing all diary entries
-->

<!DOCTYPE html>
<center>
<?php
    // Database connection information
    $servername = "localhost";
    $username = "diaryappdbuser";
    $password = "DiaryPass$";
    $dbname = "diaryappdb";

    // Create database connection
    $conn = new mysqli($servername, $username, $password, $dbname);

    // If the connection failed, display why
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Gets the total number of entries from the tbl_diary_entries table for current user
    $sql = "SELECT COUNT(*) as total_entries FROM tbl_diary_entries WHERE username='" . $_SESSION['username'] . "'";
    $result = $conn->query($sql);
    $row = $result->fetch_assoc();
    $total_entries = $row['total_entries'];

    // Get the current entry index from the session variable or set it to 0 if it doesn't exist
    $entry_index = isset($_SESSION['entry_index']) ? $_SESSION['entry_index'] : 0;

    // If the entry index is greater than the total number of entries
    //      Used if the last element in the diary is deleted
    if ($entry_index >= $total_entries) {
        // Decrement the current entry index
        $entry_index = $total_entries - 1;
    }


    // If the previous arrow was clicked (left arrow)
    if (isset($_POST['prev'])) {
        // Decrement the current entry index (go back one entry)
        $entry_index--;
    } elseif (isset($_POST['next'])) {
        // If the next arrow was clicked (right arrow)
        // Increment the current entry index (go forward one entry)
        $entry_index++;
    }
    
    // If the current entry index is less than 0
    if ($entry_index < 0) {
        // Set the current entry index to 0 (the first entry)
        $entry_index = 0;
    }
    
    // If the delete button was clicked
    if (isset($_POST['delete'])) {
        // Get the entry_datetime value of the current entry
        $sql = "SELECT entry_datetime FROM tbl_diary_entries WHERE username='" . $_SESSION['username'] . "' ORDER BY entry_datetime DESC LIMIT 1 OFFSET " . $entry_index;
        $result = $conn->query($sql);
        $row = $result->fetch_assoc();
        $entry_datetime = $row['entry_datetime'];
        
        // Delete the current entry from the database using the entry_datetime
        $sql = "DELETE FROM tbl_diary_entries WHERE username='" . $_SESSION['username'] . "' AND entry_datetime='" . $entry_datetime . "'";
        $conn->query($sql);

        // Refresh the diary view by redirecting user to landing page (index.php)
        header("Location: ./");

    }

    // Get the diary entry for the current user and entry index
    $sql = "SELECT entry, entry_datetime FROM tbl_diary_entries WHERE username='" . $_SESSION['username'] . "' ORDER BY entry_datetime DESC LIMIT 1 OFFSET " . $entry_index;
    $result = $conn->query($sql);

    // If the diary is not empty
    if ($result->num_rows > 0) {
        // Output data of entry at entry index
        while($row = $result->fetch_assoc()) {
            // Format the date and time of the diary entry in 12-hour format with AM/PM
            $entry_datetime = new DateTime($row["entry_datetime"]);
            $formatted_datetime = $entry_datetime->format('m/d/Y \a\t g:i A');
    
            // Display the diary header
            echo "<h2>Diary entry on " . $formatted_datetime . "</h2>";

            // Set the entry to equal the diary entry at the entry index
            $entry = $row['entry'];
        }
    } else {
        // If there are no entries in the diary or the entry index is out of range
        
        // Set the entry to equal the message that there are no more entries
        $entry = "No more entries";
    }

    // Update the entry index in the session variable
    $_SESSION['entry_index'] = $entry_index;

    // Close the connection to the database
    $conn->close();
?>

<!-- div class for displaying the entry information with applicable arrows -->
<div class="entry-container">
    <div class="arrow-container">
        <!-- If not viewing the first diary entry, display the left/prev arrow -->
        <?php if ($entry_index > 0): ?>
            <form method="post">
                <input type="submit" class="arrow prev" name="prev" value="&#8592;">
            </form>
        <?php endif; ?>
    </div>
    <div class="entry">
        <?php echo $entry ?>
    </div>
    <div class="arrow-container">
        <!-- If not viewing the last diary entry, display the right/next arrow -->
        <?php if ($entry_index < $total_entries - 1): ?>
            <form method="post">
                <input type="submit" class="arrow next" name="next" value="&#8594;">
            </form>
        <?php endif; ?>
    </div>
</div>
<!-- If viewing a valid diary entry, add a 'Delete' button -->
<?php if ($result->num_rows > 0): ?>
    <!-- If the user clicks the 'Delete' button, ask a confirmation message -->
    <form method="post" onsubmit="return confirm('Are you sure you want to delete this entry?');">
        <input type="submit" name="delete" value="Delete">
    </form>
<?php endif; ?>
</center>