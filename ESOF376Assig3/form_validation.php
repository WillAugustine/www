<?php
/*
* Name: Will Augustine
*
* Desctiption: PHP script to validate form for Assignment2.html
*/
$servername = "localhost";
$username = "ESOF376";
$password = "password";
$dbname = "enrollment";
$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error){
    die("Connection failed: " . $conn->connect_error);
}

$valid_id = NULL;
$valid_name = NULL;
$valid_email = NULL;
$valid_age = NULL;

$expected = array('sid'=>'id', 'name'=>'string', 'email'=>'email', 'age'=>'int');

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
            if (!preg_match("/^\d\D\D\D/", $_POST[$key])) {
                ${$key} = $_POST[$key];
                echo "Invalid ID - format should be 'NLLL' where N = number and L = letter!<br />";
                break;
            }
            else if (strlen($_POST[$key]) == 4) {
                ${$key} = $_POST[$key];
                echo "Valid ID : " . ${$key} . "<br />";
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
            echo "Valid name : " . ${$key} . "<br />";
            $valid_name = ${$key};
            break;

        case 'email':
            if (filter_var($_POST[$key], FILTER_VALIDATE_EMAIL)) {
                ${$key} = $_POST[$key];
                echo "Valid email : " . ${$key} . "<br />";
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
                echo "Valid age : " . ${$key} . "<br /><br />";
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

if (isset($valid_id, $valid_name, $valid_email, $valid_age)) {
    $sql = $conn->prepare("INSERT INTO personalinfo (ID, name, email, age) VALUES (?, ?, ?, ?)");
    $sql->bind_param('sssi', $valid_id, $valid_name, $valid_email, $valid_age);
    $sql->execute();
    $result = $sql->error;
    // echo "Result: '$result'";
    if ($result == '') {
        echo "New record created successfully<br />";
    } else {
        echo "Error: " . $result . "<br>";
    }
}
$conn->close();
?>