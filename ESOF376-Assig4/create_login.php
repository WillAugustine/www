<?php
$u=$_POST['TU'];
$p=sha1($_POST['TP']);
$db = "test_user";


// Create connection
$conn = mysqli_connect('localhost', 'root', 'LOLzies101', 'test_user');
// Check connection
if (!$conn) {
  die("Connection failed: " . mysqli_connect_error());
}

$sql = "INSERT INTO  users(user,password) VALUES ('$u',  '$p')";

if (mysqli_query($conn, $sql)) {
  echo "New record created successfully<br />";
  echo "<a href=.\>Login</a><br /> <br /> ";
} else {
  echo "Error: " . $sql . "<br>" . mysqli_error($conn);
}

mysqli_close($conn);





?>