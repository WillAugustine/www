<!DOCTYPE html>
<html>
<head>
    <title>Cemetery Landing Page</title>
    <link rel="stylesheet" href="style.css"> <!-- Add your CSS file path here -->
    <style>
        /* Add your custom CSS styles here */
    </style>
</head>
<body>
    <?php
    // Start a new session
    session_start();
    $_SESSION['user_token'] = "asfoih";
    $db_server = "localhost";
    $db_username = "CemeteryApplication_User";
    $db_password = 'Pa$$word';
    $db_database = "CemeteryApplication";

    // Check if the user_token session variable is set
    if (isset($_SESSION['user_token'])) {
        // Regular User view

        // Create a mysqli connection
        $mysqli = new mysqli($db_server, $db_username, $db_password, $db_database); // Replace with your database connection details

        // Check connection
        if ($mysqli->connect_error) {
            die("Connection failed: " . $mysqli->connect_error);
        }

        // Retrieve block, lot, plot, and name from the database
        $user_id = $_SESSION['user_token']; // Replace with the actual session variable for user_id
        $query = "SELECT block, lot, plot, name FROM user_data WHERE user_id = ?";
        $stmt = $mysqli->prepare($query);
        $stmt->bind_param("s", $user_id); // Assuming user_id is a string, use "i" for integer, "d" for double
        $stmt->execute();
        $stmt->bind_result($block, $lot, $plot, $name);
        $stmt->fetch();
        $stmt->close();
        $mysqli->close();

        // Display the block, lot, plot, and name information
        echo "<h1>Regular User View</h1>";
        echo "<p>Block: " . $block . "</p>";
        echo "<p>Lot: " . $lot . "</p>";
        echo "<p>Plot: " . $plot . "</p>";
        echo "<p>Name: " . $name . "</p>";

        // Display the End Search button with confirmation prompt
        echo "<form action='survey.php' method='post'>";
        echo "<input type='submit' name='end_search' value='End Search'>";
        echo "</form>";

        // Display the compass image and View Block Image button
        echo "<img src='resources/images/Cemetery_BirdsEyeView.jpg' alt='Cemetery Birds Eye View'>";
        echo "<img src='resources/images/Compass_Image.png' alt='Compass Image'>";
        echo "<button onclick='openBlockImageData()'>View Block Image Data</button>";
    } else {
        // Butte Archives User view
        // Display the logon menu option
        echo "<h1>Butte Archives User View</h1>";
        echo "<a href='login.php'>Logon</a>";
    }
    ?>

    <!-- JavaScript to handle closing of block image data -->
    <script>
        function openBlockImageData() {
            // Add logic to show the block image data overlay
        }

        function closeBlockImageData() {
            // Add logic to close the block image data overlay
        }
    </script>
</body>
</html>
