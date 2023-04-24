<?php
// Replace these values with your own database credentials---------------------------------------------------------------
$dbhost = "localhost";
$dbusername = "root";
$dbpassword = "password";
$dbname = "locator";
$conn = mysqli_connect($dbhost, $dbusername, $dbpassword, $dbname);

// Check connection
if (!$conn) {
  die("Connection failed: " . mysqli_connect_error());
}

// Get the block ID from the URL parameter
$blockpath = urldecode($_GET['block']);
$link = "BlockRecords/Block " . $blockpath;

$search_string = $blockpath . "%";

// Create the SQL query
$sql = "SELECT * FROM image_data WHERE image_name LIKE '$search_string'";
$result = $conn->query($sql);

// Check if any rows were returned
if ($result !== false && $result->num_rows > 0) {

while ($row = $result->fetch_assoc()) {
    // Search for image file
    $block_image = $row["image_name"];
    $image_file = "BlockRecords/Block " . $block_image;
    if (file_exists($image_file)) {
        
        $link = $image_file;
        
        
        
        break;
    } else {

        
    }

}
} else {

echo "No record found";
}

$matches = array();
preg_match('/\d+/', $blockpath, $matches);
$block = $matches[0]; 
$feedback = '';
$result = '';
$x = $_GET['x'];
$y = $_GET['y'];
// Insert a new search record into the database
$sql = "INSERT INTO searches (link, block, feedback, result, x, y) VALUES ('$link', '$block', '$feedback', '$result', '$x', '$y')";
if (mysqli_query($conn, $sql)) {
  echo "New search record created successfully<br>";
} else {
  echo "Error: " . $sql . "<br>" . mysqli_error($conn);
}

$id = mysqli_insert_id($conn);
$userLink = "main.php?id=" . $id . "&x=" . $x . "&y=" . $y;
echo "<a href='$userLink'>$userLink</a>";
echo "<br>";
echo "<a href='archives.php'>Back</a>";

mysqli_close($conn);
?>
