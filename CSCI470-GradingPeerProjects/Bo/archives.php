<!DOCTYPE html>
<html>
<head>
	<title>Irish Archives Search</title>
	<style>
		body {
			background-color: #f8f8f8;
			font-family: Arial, sans-serif;
		}

		h1 {
			color: #006600;
			text-align: center;
			margin-top: 30px;
		}

		form {
			margin-top: 50px;
			text-align: center;
		}

		input[type=text] {
			padding: 10px;
			font-size: 16px;
			border: 1px solid #ccc;
			border-radius: 5px;
			width: 300px;
		}

		input[type=submit] {
			padding: 10px;
			font-size: 16px;
			background-color: #006600;
			color: #fff;
			border: none;
			border-radius: 5px;
			width: 150px;
			margin-left: 10px;
			cursor: pointer;
		}


		.result {
			margin-top: 50px;
			text-align: center;
		}

		img {
			max-width: 100%;
			height: auto;
			margin-top: 20px;
            position: relative;
		}

		.error {
			color: red;
			font-size: 16px;
			margin-top: 20px;
			text-align: center;
		}

        #canvas {
            
            width: 100%;
			height: 100%;
            
	    }
	</style>
</head>
<body>
	<h1> Archives Search</h1>
    <a href="logout.php">Logout</a>
	<form method="post" action="archives.php">
		<label for="name">Name:</label>
		<input type="text" id="name" name="name" placeholder="Enter name">

		 <label for="block">Block#:</label>
		<input type="text" id="block" name="block" placeholder="Enter block#" pattern="[0-9]+">
        <br>
		 <label for="lot">Lot:</label>
		<input type="text" id="lot" name="lot" placeholder="Enter lot#" pattern="[0-9]+">

		 <label for="plot">Plot:</label>
		<input type="text" id="plot" name="plot" placeholder="Enter plot#" pattern="[0-9]+">

		<input type="submit" value="Search">
	</form>
	


    
</body>
</html>

<?php
require_once 'functions.php';
// Get the search term
if (isset($_POST['block'])) {
    $search_term = $_POST['block'];
    $search_term_encode = urlencode($search_term);
    // Build the search string
    $search_string = $search_term . "%";

    $reg = '^' . $search_term . '[^0-9].*';
    // Create the SQL query
    $sql = "SELECT * FROM image_data WHERE image_name REGEXP '$reg'";

    // // Replace these values with your own database credentials ----------------------------------------------------
    $dbhost = "localhost";
    $dbusername = "root";
    $dbpassword = "password";
    $dbname = "locator";

    $conn = new mysqli($dbhost, $dbusername, $dbpassword, $dbname);
    $result = $conn->query($sql);

    // Check if any rows were returned
    if ($result !== false && $result->num_rows > 0) {
        
    while ($row = $result->fetch_assoc()) {
        // Search for image file
        $block_image = $row["image_name"];
        $image_file = "BlockRecords/Block " . $block_image;

        if (file_exists($image_file)) {

            $image_path = $image_file;
            
            
            echo <<<_END
            <html>
           
            <body>
                Please place a Dot next to the name of the person you're searching for by clicking on the image. A link will be generated for that record after clicking.
                <canvas id="myCanvas" width="800" height="630"></canvas>
                <script>
                    const canvas = document.getElementById('myCanvas');
                    const context = canvas.getContext('2d');
            
                    // Load the image
                    const img = new Image();
                    img.onload = function() {
                        // Draw the image on the canvas
                        var scale = Math.min(canvas.width / img.width, canvas.height / img.height);
                        // Calculate the center of the canvas
                        var x = (canvas.width - img.width * scale) / 2;
                        var y = (canvas.height - img.height * scale) / 2;
                        context.drawImage(img, x, y, img.width * scale, img.height * scale);
                
                        // Add a click event listener to the canvas
                        canvas.addEventListener('click', function(event) {
                            // Get the x and y coordinates of the click event
                            const x = event.pageX - canvas.offsetLeft;
                            const y = event.pageY - canvas.offsetTop;
                
                            // Draw a dot at the clicked position
                            context.beginPath();
                            context.arc(x, y, 5, 0, 2 * Math.PI, false);
                            context.fillStyle = 'red';
                            context.fill();
                            context.closePath();
                        });
                    };
                    img.src = '$image_path';
                    canvas.addEventListener("click", function(event) {
                        // get the X and Y coordinates of the click
                        var x = event.clientX ;
                        var y = event.clientY ;
                      
                        // create a new link with the X and Y parameters
                        var link = "link.php?block=$search_term_encode&x=" + x + "&y=" + y;
                        window.location.href = link;
                      });
                </script>
                    
            
            </body>
            </html>
            _END;
            
            
            break;
        } else {
            // Display error message
            echo "No Image Found";
        }

        echo "</div>";

    }
    } else {
    // Display a message  no results were found
    echo "No record found";
    }
} else {
    echo "Please enter a block number";
}
?>

