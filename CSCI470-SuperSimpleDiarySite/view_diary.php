<!DOCTYPE html>
<center>
<?php
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

    $sql = "SELECT COUNT(*) as total_entries FROM tbl_diary_entries WHERE username='" . $_SESSION['username'] . "'";
    $result = $conn->query($sql);
    $row = $result->fetch_assoc();
    $total_entries = $row['total_entries'];

    // Get the current entry index from the session variable or set it to 0 if it doesn't exist
    $entry_index = isset($_SESSION['entry_index']) ? $_SESSION['entry_index'] : 0;

    if ($entry_index >= $total_entries) {
        $entry_index = $total_entries - 1;
    }

    // Handle button clicks
    if (isset($_POST['prev'])) {
        $entry_index--;
    } elseif (isset($_POST['next'])) {
        $entry_index++;
    }
    if (isset($_POST['delete'])) {
        // Get the entry_datetime value of the current entry
        $sql = "SELECT entry_datetime FROM tbl_diary_entries WHERE username='" . $_SESSION['username'] . "' ORDER BY entry_datetime DESC LIMIT 1 OFFSET " . $entry_index;
        $result = $conn->query($sql);
        $row = $result->fetch_assoc();
        $entry_datetime = $row['entry_datetime'];
        
        // Delete the current entry from the database
        $sql = "DELETE FROM tbl_diary_entries WHERE username='" . $_SESSION['username'] . "' AND entry_datetime='" . $entry_datetime . "'";
        $conn->query($sql);
        header("Location: ./");

    }

    // Get the diary entry for the current user and entry index
    $sql = "SELECT entry, entry_datetime FROM tbl_diary_entries WHERE username='" . $_SESSION['username'] . "' ORDER BY entry_datetime DESC LIMIT 1 OFFSET " . $entry_index;
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        // output data of each row
        while($row = $result->fetch_assoc()) {
            // Format the date and time of the diary entry in 12-hour format with AM/PM
            $entry_datetime = new DateTime($row["entry_datetime"]);
            $formatted_datetime = $entry_datetime->format('m/d/Y \a\t g:i A');
    
            echo "<h2>Diary entry on " . $formatted_datetime . "</h2>";
            $entry = $row['entry'];
        }
    } else {
        $entry = "No more entries";
    }

    // Update the entry index in the session variable
    $_SESSION['entry_index'] = $entry_index;

    $conn->close();
?>

<div class="entry-container">
    <div class="arrow-container">
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
        <?php if ($entry_index < $total_entries - 1): ?>
            <form method="post">
                <input type="submit" class="arrow next" name="next" value="&#8594;">
            </form>
        <?php endif; ?>
    </div>
</div>
<?php if ($result->num_rows > 0): ?>
<form method="post" onsubmit="return confirm('Are you sure you want to delete this entry?');">
<input type="submit" name="delete" value="Delete">
</form>
<?php endif; ?>
</center>