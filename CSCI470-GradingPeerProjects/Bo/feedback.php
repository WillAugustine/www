<!DOCTYPE html>
<html>
<head>
	<title>Feedback Page</title>
	<link rel="stylesheet" href="style.css">
</head>
<body>
	<div class="container">
		<h1>Feedback Page</h1>
		<?php
		// Check if the ID parameter is set in the URL
		if (isset($_GET['id'])) {

			$id = $_GET['id'];

		} else {

			echo '<p>Error: ID parameter is missing.</p>';
			exit();
		}

		// Check if the form has been submitted
		if (isset($_POST['submit'])) {
            // Replace these values with your own database credentials---------------------------------------------------------------
            $host = 'localhost';
            $usernamedb = 'root';
            $passworddb = 'password';
            $database = 'locator';

            
            $conn = mysqli_connect($host, $usernamedb, $passworddb, $database);
			// Get the feedback and result values from the form
			$feedback = $_POST['feedback'];
			$result = $_POST['result'];

			// Sanitize the feedback and result values to prevent SQL injection attacks
			$feedback = mysqli_real_escape_string($conn, $feedback);
			$result = mysqli_real_escape_string($conn, $result);

			// Update the searches table with the new feedback and result values for the specified ID
			$sql = "UPDATE searches SET feedback='$feedback', result='$result' WHERE id=$id";
			if (mysqli_query($conn, $sql)) {
				echo '<p>Feedback and result values updated successfully.</p>';
                header("location: main.php?id='$id'");
			} else {
				echo '<p>Error updating feedback and result values: ' . mysqli_error($conn) . '</p>';
			}
		}
		?>
		<form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]) . '?id=' . $id; ?>">
            <div style="display: flex; flex-direction: column; align-items: center;">
                <div class="form-group">
                    <label for="result">Result:</label>
                    <select class="form-control" name="result" id="result" required>
                        <option value="">Select a result</option>
                        <option value="Success">Success</option>
                        <option value="Failure">Failure</option>
                    </select>
                </div>
            </div>
            <br>
            <br>
			<div style="display: flex; flex-direction: column; align-items: center;">
                <div style="margin-bottom: 10px;">Feedback:</div>
                <textarea name="feedback" rows="5" cols="50"></textarea>
            </div>
			<div style="display: flex; flex-direction: column; align-items: center;">
                <button type="submit" name="submit" class="btn btn-primary">Submit</button>
                <br>
                <a href="javascript:history.back()" class="btn btn-secondary">Cancel</a>
            </div>
		</form>
	</div>
</body>
</html>
