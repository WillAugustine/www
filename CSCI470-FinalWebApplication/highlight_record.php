<link href="styles.css" rel="stylesheet" />
<?php
    include("header.php");

    // Connect to the database
    $conn = new mysqli('localhost', 'ButteArchives', 'password', 'CemeteryLocatorApplication');
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Check if the "done highlighting" button was clicked
    if (isset($_POST['done_highlighting'])) {
        // Get the highlighting data from the form
        $maxX = $_POST['maxX'];
        $minX = $_POST['minX'];
        $maxY = $_POST['maxY'];
        $minY = $_POST['minY'];

        echo "($minX, $minY) -> ($maxX, $maxY)<br>";
        // Insert the highlighting data into the Highlights table
        $stmt = $conn->prepare("INSERT INTO Highlights (maxX, minX, maxY, minY) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("dddd", $maxX, $minX, $maxY, $minY);
        if($stmt->execute()){
            header("Location: create_new_user.php?add_headstones");
        } else {
            echo "ERROR: " . $stmt->error . "<br>";
        }

        $stmt->close();
    }

    // Check if the "clear" button was clicked
    if (isset($_POST['clear'])) {
        // Delete all rows from the Highlights table
        $conn->query("TRUNCATE TABLE Highlights");
    }

    // Close the database connection
    $conn->close();

    $block = isset($_GET['block']) ? $_GET['block'] : null;
    $plot = isset($_GET['plot']) ? $_GET['plot'] : null;
    $lot = isset($_GET['lot']) ? $_GET['lot'] : null;
    $name = isset($_GET['name']) ? $_GET['name'] : null;
    
    if ($block !== null) {
        $imagePath = 'blocks/Block ' . $block . '.JPG';
        if (file_exists($imagePath)) {
            // echo '<img src="' . $imagePath . '" alt="Block Image" class="block-image">';
        } else {
            echo 'Image not found.';
        }
    } else {
        echo 'Block data not provided.';
    }
?>

<!-- Add a container for the image and highlights -->
<div id="image-container">
    <!-- Add the image here -->
    <img src="<?php echo $imagePath?>" alt="Block Image" class="block-image">
</div>

<!-- Add a form to submit the highlighting data -->
<form method="post" id="highlight-form">
    <input type="hidden" id="maxX" name="maxX">
    <input type="hidden" id="minX" name="minX">
    <input type="hidden" id="maxY" name="maxY">
    <input type="hidden" id="minY" name="minY">
    <input type="submit" name="done_highlighting" value="Done Highlighting">
</form>

<!-- Add a button to clear highlights -->
<form method="post">
    <input type="submit" name="clear" value="Clear Highlights">
</form>

<!-- Add JavaScript to handle highlighting -->
<script>
    // Get the image container element
    const imageContainer = document.getElementById('image-container');

    // Get the highlight form elements
    const highlightForm = document.getElementById('highlight-form');
    const maxXInput = document.getElementById('maxX');
    const minXInput = document.getElementById('minX');
    const maxYInput = document.getElementById('maxY');
    const minYInput = document.getElementById('minY');

    // Initialize variables for tracking the highlight area
    let isHighlighting = false;
    let highlightStartX = 0;
    let highlightStartY = 0;
    let highlightEndX = 0;
    let highlightEndY = 0;
    let clickCount = 0;

    // Handle mousedown events on the image container
    imageContainer.addEventListener('mousedown', (event) => {
        // Increment the click count
        clickCount++;

        // Check if this is the first or third click
        if (clickCount === 1 || clickCount === 3) {
            // Start highlighting
            isHighlighting = true;

            // Reset the click count if this is the third click
            if (clickCount === 3) {
                clickCount = 1;
            }

            // Get the starting coordinates of the highlight area
            const rect = imageContainer.getBoundingClientRect();
            highlightStartX = event.clientX - rect.left;
            highlightStartY = event.clientY - rect.top;
        } else if (clickCount === 2) {
            // Stop highlighting
            isHighlighting = false;

            // Get the ending coordinates of the highlight area
            const rect = imageContainer.getBoundingClientRect();
            highlightEndX = event.clientX - rect.left;
            highlightEndY = event.clientY - rect.top;

            // Set the values of the hidden form inputs
            maxXInput.value = Math.max(highlightStartX, highlightEndX);
            minXInput.value = Math.min(highlightStartX, highlightEndX);
            maxYInput.value = Math.max(highlightStartY, highlightEndY);
            minYInput.value = Math.min(highlightStartY, highlightEndY);
        }
    });

    // Handle mousemove events on the image container
    imageContainer.addEventListener('mousemove', (event) => {
        // Check if we're currently highlighting
        if (isHighlighting) {
            // Get the current coordinates of the mouse
            const rect = imageContainer.getBoundingClientRect();
            const currentX = event.clientX - rect.left;
            const currentY = event.clientY - rect.top;

            // Calculate the width and height of the highlight area
            const width = currentX - highlightStartX;
            const height = currentY - highlightStartY;

            // Create a new highlight element
            const highlight = document.createElement('div');
            highlight.classList.add('highlight');
            highlight.style.left = `${highlightStartX}px`;
            highlight.style.top = `${highlightStartY}px`;
            highlight.style.width = `${width}px`;
            highlight.style.height = `${height}px`;

            // Remove any existing highlights
            document.querySelectorAll('.highlight').forEach((el) => el.remove());

            // Add the new highlight to the image container
            imageContainer.appendChild(highlight);
        }
    });

</script>

