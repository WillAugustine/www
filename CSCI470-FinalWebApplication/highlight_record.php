<link href="styles.css" rel="stylesheet" />
<?php
    include("header.php");

    $block = $_SESSION['block'];
    
    $db_server = "localhost";
    $db_username = "ButteArchives";
    $db_password= "password";
    $db_database= "CemeteryLocatorApplication";

    // Connect to the database
    $conn = new mysqli( $db_server, $db_username, $db_password, $db_database );
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    
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
    <img src="<?php echo $imagePath?>" alt="Block Image" class="block-image" id="block-image">
    <!-- Add a canvas element to draw the highlights on -->
    <canvas id="highlight-canvas"></canvas>
</div>

<form action="populate_records.php" method="post" class="done-highlighting">
    <input type="hidden" name="maxX" value="" id="maxX">
    <input type="hidden" name="minX" value="" id="minX">
    <input type="hidden" name="maxY" value="" id="maxY">
    <input type="hidden" name="minY" value="" id="minY">
    <input type="hidden" name="imageWidth" value="" id="imageWidth">
    <input type="hidden" name="imagePath" value="<?php echo $imagePath; ?>" id="imagePath">
    <input type="submit" value="Done Highlighting">
</form>
<form action="create_new_user.php?add_headstones" method="post" class="done-highlighting">
    <input type="submit" value="Back">
</form>

<div class="highlight-overlay">
  <div class="overlay-content">
    <p>Instructions for highlighting:</p>
    <ul>
      <li>Click once to start highlighting</li>
      <li>Click again to finish highlighting</li>
    </ul>
    <button id="close-overlay">OK</button>
  </div>
</div>

<script>
    // Get references to the image and canvas elements
    const blockImage = document.querySelector('#block-image');
    const highlightCanvas = document.querySelector('#highlight-canvas');

    // Set the canvas size to match the image size
    highlightCanvas.width = blockImage.width;
    highlightCanvas.height = blockImage.height;

    // Get the canvas 2D context to draw on
    const ctx = highlightCanvas.getContext('2d');

    // Initialize variables to store the highlight start and end coordinates
    let highlightStartX = null;
    let highlightStartY = null;
    let highlightEndX = null;
    let highlightEndY = null;

    // Add an event listener for clicks on the canvas
    highlightCanvas.addEventListener('click', (event) => {
        // Check if this is the first or second click for the current highlight
        if (highlightStartX === null && highlightStartY === null) {
            // This is the first click, so store the start coordinates
            highlightStartX = event.offsetX;
            highlightStartY = event.offsetY;
        } else {
            // This is the second click, so store the end coordinates
            highlightEndX = event.offsetX;
            highlightEndY = event.offsetY;

            // Update the values of the hidden input fields
            document.querySelector('#maxX').value = Math.max(highlightStartX, highlightEndX);
            document.querySelector('#minX').value = Math.min(highlightStartX, highlightEndX);
            document.querySelector('#maxY').value = Math.max(highlightStartY, highlightEndY);
            document.querySelector('#minY').value = Math.min(highlightStartY, highlightEndY);
            document.querySelector('#imageWidth').value = blockImage.width;

            ctx.clearRect(0, 0, highlightCanvas.width, highlightCanvas.height);

            // Set the fill style to a semi-transparent yellow color
            ctx.fillStyle = 'rgba(255, 255, 0, 0.3)';

            // Draw a filled rectangle on the canvas using the start and end coordinates
            ctx.fillRect(highlightStartX, highlightStartY, highlightEndX - highlightStartX, highlightEndY - highlightStartY);

            // Reset the start and end coordinates for the next highlight
            highlightStartX = null;
            highlightStartY = null;
            highlightEndX = null;
            highlightEndY = null;
        }
    });

    // Add an event listener for mousemove events on the canvas
    highlightCanvas.addEventListener('mousemove', (event) => {
        // Check if a highlight is currently being drawn
        if (highlightStartX !== null && highlightStartY !== null) {
            // Clear the canvas
            ctx.clearRect(0, 0, highlightCanvas.width, highlightCanvas.height);

            // Set the fill style to a semi-transparent yellow color
            ctx.fillStyle = 'rgba(255, 255, 0, 0.3)';

            // Draw a filled rectangle on the canvas using the start coordinates and current cursor position
            ctx.fillRect(highlightStartX, highlightStartY, event.offsetX - highlightStartX, event.offsetY - highlightStartY);
        }
    });
    // Add an event listener for the load event on the image element
    blockImage.addEventListener('load', () => {
        // Set the canvas size to match the image size
        highlightCanvas.width = blockImage.width;
        highlightCanvas.height = blockImage.height;

        // Set the canvas position to match the image position
        highlightCanvas.style.top = `${blockImage.offsetTop}px`;
        highlightCanvas.style.left = `${blockImage.offsetLeft}px`;
    });

    // Add an event listener for the resize event on the window object
    window.addEventListener('resize', () => {
        // Set the canvas size to match the image size
        highlightCanvas.width = blockImage.width;
        highlightCanvas.height = blockImage.height;

        // Set the canvas position to match the image position
        highlightCanvas.style.top = `${blockImage.offsetTop}px`;
        highlightCanvas.style.left = `${blockImage.offsetLeft}px`;
    });

    // Get references to the overlay and close button elements
    const overlay = document.querySelector('.highlight-overlay');
    const closeOverlayButton = document.querySelector('#close-overlay');

    // Show the overlay when the page loads
    window.addEventListener('load', () => {
        overlay.style.display = 'block';
    });

    // Hide the overlay when the user clicks anywhere outside the overlay content
    window.addEventListener('click', (event) => {
        if (event.target === overlay) {
            overlay.style.display = 'none';
        }
    });

    // Hide the overlay when the user clicks the "OK" button
    closeOverlayButton.addEventListener('click', () => {
        overlay.style.display = 'none';
    });
    
</script>
