<link href="styles.css" rel="stylesheet" />
<?php

    $db_server = "localhost";
    $db_username = "ButteArchives";
    $db_password= "password";
    $db_database= "CemeteryLocatorApplication";

    $user_link = isset($_SESSION['user_link']) ? $_SESSION['user_link'] : "";

    // Connect to the database
    $conn = new mysqli( $db_server, $db_username, $db_password, $db_database );
    if ( $conn->connect_error ) exit( 'connection failed: ' . $conn->connect_error );

    // Query the database for block record images, their associated headstone names, and highlight data
    $query = "SELECT ButteArchivesRecords.blockImagePath, ButteArchivesRecords.name, Highlights.maxX, Highlights.minX, Highlights.maxY, Highlights.minY, Highlights.imageWidth FROM ButteArchivesRecords INNER JOIN Highlights ON ButteArchivesRecords.highlightID = Highlights.ID";
    $result = $conn->query($query);

    // Check for errors
    if (!$result) {
        die("Query failed: " . $conn->error);
    }

    // Create an array to store the block record images, their associated headstone names, and highlight data
    $images = array();
    while ($row = $result->fetch_assoc()) {
        $images[] = array(
            'blockImagePath' => $row['blockImagePath'],
            'name' => $row['name'],
            'maxX' => $row['maxX'],
            'minX' => $row['minX'],
            'maxY' => $row['maxY'],
            'minY' => $row['minY'],
            'imageWidth' => $row['imageWidth']
        );
    }

    // Close the database connection
    $conn->close();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Block Record Images</title>
</head>
<body>
    <!-- Create a button to open the overlay
    <button onclick="openOverlay(0)">View Block Record Images</button> -->

    <!-- Create the overlay -->
    <div class="selected-blocks-overlay" onclick="closeOverlay()">
        <div class="selected-blocks-overlay-content">
            <!-- Display the headstone name -->
            <h1 id="headstone-name"></h1>

            <!-- Display the image with the highlight overtop -->
            <div style="position:relative;">
                <img id="block-record-image" src="" alt="Block Record Image">
                <div class="highlight"></div>
            </div>

            <!-- Add arrows to traverse the images -->
            <div>
                <span id="arrow" onclick="prevImage(event)">&#10094;</span>
                <span id="arrow" onclick="nextImage(event)">&#10095;</span>
            </div>
        </div>
    </div>

    <script>
        // Store the block record images and their associated headstone names in a JavaScript array
        var images = <?php echo json_encode($images); ?>;
        
        // Store the current image index
        var currentImageIndex = 0;

        // Function to open the overlay and display the first image
        function openOverlay(index) {
            currentImageIndex = index;
            document.getElementById('headstone-name').innerHTML = images[index].name;
            document.getElementById('block-record-image').src = images[index].blockImagePath;

            // Calculate the position and size of the highlight
            var imageWidth = document.getElementById('block-record-image').clientWidth;
            var scale = imageWidth / images[index].imageWidth;
            var highlight = document.querySelector('.highlight');
            highlight.style.left = (images[index].minX * scale) + 'px';
            highlight.style.top = (images[index].minY * scale) + 'px';
            highlight.style.width = ((images[index].maxX - images[index].minX) * scale) + 'px';
            highlight.style.height = ((images[index].maxY - images[index].minY) * scale) + 'px';

            document.querySelector('.selected-blocks-overlay').style.display = 'block';
        }

        // Function to close the overlay
        function closeOverlay() {
            document.querySelector('.selected-blocks-overlay').style.display = 'none';
        }

        // Function to display the previous image
        function prevImage(event) {
            event.stopPropagation();
            
            if (currentImageIndex > 0) {
                currentImageIndex--;
                openOverlay(currentImageIndex);
            }
        }

        // Function to display the next image
        function nextImage(event) {
            event.stopPropagation();
            
            if (currentImageIndex < images.length - 1) {
                currentImageIndex++;
                openOverlay(currentImageIndex);
            }
        }
        
        // Open the overlay when the file is loaded
        window.onload = function() {
            openOverlay(0);
        };

        window.addEventListener('resize', openOverlay(currentImageIndex));
    </script>
</body>
</html>