<!DOCTYPE html>
<form id="form_validate" method="POST">
    <fieldset>
    <legend>Form Validation Demo</legend>
    
    <p>ID: <input type="text" name="sid"></p>
    
    <p>Name: <input type="text" name="name">
    <input type="checkbox" disabled name="valid_name"></p>
    
    <p>Email: <input type="text" size="32" id="email" name="email">
    <input id="valid_email" type="checkbox" disabled name="valid_email"></p>
    <div id="rsp_email"><!-- --></div>
    
    <p>Age: <input type="text" size="4" id="age" name="age" <small>(between 16 and 100)</small>
    <input id="valid_age" type="checkbox" disabled name="valid_age"></p>
    <div id="rsp_age"><!-- --></div>
    
    <p><input type="submit"></p>
    
    </fieldset>
</form>
    

<?php
/*
* Name: Will Augustine
*
* Desctiption: PHP script to validate form for Assignment2.html
*/

session_start();

if (!isset($_SESSION['user']))
header('location: index.php');
else
{
echo "you are logged in! ";
echo "<a href=logout.php?session=logout>logout</a><br /> <br /> ";
}

$servername = "localhost";
$username = "root";
$password = "LOLzies101";
$dbname = "enrollment";

$conn = new mysqli($servername, $username, $password);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
$sql = "CREATE DATABASE IF NOT EXISTS enrollment";
if ($conn->query($sql) === TRUE) {
    // echo "Database created successfully";
} else {
    echo "Error creating database: " . $conn->error;
}
$conn->close();

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error){
    die("Connection failed: " . $conn->connect_error);
}
$sql = "CREATE TABLE IF NOT EXISTS personalinfo (
    id VARCHAR(4) NOT NULL UNIQUE,
    name VARCHAR(25) NOT NULL,
    email VARCHAR(255),
    age INT(3),
    createdBy VARCHAR(255),
    PRIMARY KEY (id)
)";
if ($conn->query($sql) === TRUE) {
    // echo "Database created successfully";
} else {
    echo "Error creating table: " . $conn->error;
}
$conn->close();

$expected = array('sid'=>'id', 'name'=>'string', 'email'=>'email', 'age'=>'int');
if (isset($_POST['sid'], $_POST['name'], $_POST['email'], $_POST['age'])) {
    $valid_id = $_POST['sid'];
    $valid_name = $_POST['name'];
    $valid_email = $_POST['email'];
    $valid_age = $_POST['age'];
    $session_user = $_SESSION['user'];
    foreach ( $expected AS $key => $type ) {
        if (empty( $_POST[$key])) {
            echo "Key is empty!" . "<br />";
            ${$key} = NULL;
            continue;
        }
        switch( $type ) {
            case 'id':
                if (strlen($_POST[$key]) != 4) {
                    ${$key} = $_POST[$key];
                    echo "Invalid ID - not four characters : " . ${$key} . "<br />";
                    break;
                }
                if (!preg_match("/^\D\d\d\d/", $_POST[$key])) {
                    ${$key} = $_POST[$key];
                    echo "Invalid ID - format should be 'LNNN' where L = letter and N = number!<br />";
                    break;
                }
                else if (strlen($_POST[$key]) == 4) {
                    ${$key} = $_POST[$key];
                    // echo "Valid ID : " . ${$key} . "<br />";
                    $valid_id = ${$key};
                }
                break;

            case 'string':
                if(!preg_match("/^([a-zA-Z' ]+)$/", $_POST[$key])) {
                    ${$key} = $_POST[$key];
                    echo "Invalid name - not alphabetic!<br />";
                    break;
                }
                if (strlen($_POST[$key]) > 25) {
                    ${$key} = $_POST[$key];
                    echo "Invalid name - exceeds 25 characters!<br />";
                    break;
                }
                ${$key} = $_POST[$key];
                // echo "Valid name : " . ${$key} . "<br />";
                $valid_name = ${$key};
                break;

            case 'email':
                if (filter_var($_POST[$key], FILTER_VALIDATE_EMAIL)) {
                    ${$key} = $_POST[$key];
                    // echo "Valid email : " . ${$key} . "<br />";
                    $valid_email = ${$key};
                }
                else {
                    echo "Invalid email - incorrect format!<br />";
                }
                break;

            case 'int':
                if ($_POST[$key] < 16) {
                    ${$key} = $_POST[$key];
                    echo "Invalid age - must be 16 or older!<br />";
                }
                else if ($_POST[$key] > 100) {
                    ${$key} = $_POST[$key];
                    echo "Invalid age - must be 100 or younger!<br />";
                }
                else {
                    ${$key} = $_POST[$key];
                    // echo "Valid age : " . ${$key} . "<br /><br />";
                    $valid_age = ${$key};
                }
                break;

        }
        if (!isset(${$key})) {
            ${$key} = NULL;
        }
    }

// echo "valid_id: '$valid_id'<br />";
// echo "valid_name: '$valid_name'<br />";
// echo "valid_email: '$valid_email'<br />";
// echo "valid_age: '$valid_age'<br />";


    $sql = $conn->prepare("INSERT INTO personalinfo (id, name, email, age, createdBy) VALUES (?, ?, ?, ?, ?)");
    $sql->bind_param('sssis', $valid_id, $valid_name, $valid_email, $valid_age, $session_user);
    $sql->execute();
    $result = $sql->error;
    // echo "Result: '$result'";
    if ($result == '') {
        echo "New record created successfully<br />";
    } 
    else {
        echo "Error: " . $result . "<br>";
    }
}
$conn->close();
?>