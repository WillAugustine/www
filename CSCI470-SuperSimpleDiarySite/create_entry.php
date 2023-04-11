<!--
    Author: Will Augustine
        Description: PHP file for creating an entry to add to the dictionary
-->

<!DOCTYPE html>

<!-- Pulls styling from styles.css -->
<link rel="stylesheet" href="styles.css">

<?php
    // Add the header to the top of the page
    include("header.php");

    // Server variables
    $servername = "localhost";
    $username = "diaryappdbuser";
    $password = "DiaryPass$";
    $dbname = "diaryappdb";

    // Default error message is empty with correct spacing so format does not change
    //      when there is an error message
    $error = '<p> <br> </p>';

    // Create connection
    $conn = new mysqli($servername, $username, $password, $dbname);
    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Handle form submission
    // If submit button is clicked
    if (isset($_POST['entry'])) {
        // Sanitize user input to avoid SQL and HTML injections
        $entry = htmlspecialchars($conn->real_escape_string($_POST['entry']));

        // Validate entry length
        if (strlen($entry) > 255) {
            $error = '<div class="invalid" ><p>Must be less than 250 characters!</p></div>';
        } else if (strlen($entry) === 0) {
            $error = '<div class="invalid" ><p>Entry cannot be blank!</p></div>';
        } else {
            // Insert the new entry into the database using a prepared statement
            $stmt = $conn->prepare("INSERT INTO tbl_diary_entries (username, entry_datetime, entry) VALUES (?, NOW(), ?)");
            $stmt->bind_param("ss", $_SESSION['username'], $entry);
            $stmt->execute();

            // Take user back to index.php page to view diary entries
            header("Location: ./");
        }
    }

    // Close mysqli connnection
    $conn->close();
?>

<!-- Form for the user to input their new entry -->
<center>
    <form method="post">
        <label for="entry">Diary Entry:</label><br>
        <textarea id="entry" name="entry" oninput="updateCounter()"></textarea><br>
        <span id="counter">0/255</span><br>
        <?php echo $error ?>
        <input type="submit" value="Submit">
    </form>
</center>

<!-- Script to constantly update character counter -->
<script>
function updateCounter() {
    var entry = document.getElementById("entry");
    var counter = document.getElementById("counter");
    counter.innerHTML = entry.value.length + "/255";
}
</script>
