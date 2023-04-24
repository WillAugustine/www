<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <title>Create User</title>
  <link rel="stylesheet" type="text/css" href="style.css">
</head>
<body>
  <div class="container">
    <header>
      <h1>Create User</h1>
    </header>
    <main>
    <script>
        function validateEmail(email) {
            const regex = /^\S+@\S+\.\S+$/;
            return regex.test(email);
        }

        function validateForm() {
            const emailInput = document.getElementById("email");
            const email = emailInput.value.trim();

            if (validateEmail(email)) {
            
            return true;
            } else {
            
            alert("Please enter a valid email address.");
            return false;
            }
        }
    </script>
      <form action="create.php" method="POST" onsubmit="return validateForm()">
        <label for="email">Email:</label>
        <input type="email" id="email" name="email" value="" >

        <label for="password">Password:</label>
        <input type="password" id="password" name="password" value="">
        <input type="submit" value="Create User">
      </form>
    </main>
    <a href="login.php">Go to Login</a>
    <br>
    <a href="index.php">Main page</a>
    <footer>
      <p>&copy; 2023 Signup Page</p>
    </footer>
  </div>
</body>
</html>



<?php
// Replace these values with your own database credentials---------------------------------------------------------------
$host = 'localhost';
$usernamedb = 'Bo_ButteArchives';
$passworddb = 'password';
$database = 'Bo_CemeteryApplication';

// Connect to the database
$conn = mysqli_connect($host, $usernamedb, $passworddb, $database);

// Check for errors
if (mysqli_connect_errno()) {
  echo "Failed to connect to database: " . mysqli_connect_error();
  exit();
}

// Get the submitted email and password
if (isset($_POST['email'], $_POST['password'])) {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Check if the email already exists in the database
    $sql = "SELECT * FROM users WHERE email='$email'";
    $result = mysqli_query($conn, $sql);

    if (mysqli_num_rows($result) > 0) {
    // If the email already exists, display an error message
    echo "Email already exists.";
    } else {
    // If the email does not exist, hash the password and insert the new user into the database
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);
    $sql = "INSERT INTO users (email, username, password) VALUES ('$email', '', '$hashed_password')";
    mysqli_query($conn, $sql);
    
    // Redirect to the login page
    header("Location: login.php");
    exit();
    }
} else {
    echo 'please enter all fields';
}

mysqli_close($conn);
?>
