<?php

$servername = "localhost";
$username = "root";
$password = "LOLzies101";
$database = "test_user";
$conn = new mysqli($servername, $username, $password, $database);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
$sql = "CREATE TABLE IF NOT EXISTS `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user` varchar(10) NOT NULL,
  `password` varchar(40) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=103 ;";
if ($conn->query($sql) === TRUE) {
    // echo "Database created successfully";
} else {
    echo "Error creating user table: " . $conn->error;
}
$conn->close();

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