<?php
$expected = array('sid'=>'id', 'name'=>'string', 'email'=>'email', 'age'=>'int');

echo "Testing each input..." . "<br />";
foreach ( $expected AS $key => $type ) {
    echo "Beginning of foreach loop" . "<br />";
    if (empty( $_GET[$key])) {
        echo "Key is empty!" . "<br />";
        ${$key} = NULL;
        continue;
    }
    echo "Current type : " . $type;
    switch( $type ) {
        case 'id':
            if (strlen($_GET[$key]) == 4) {
                ${$key} = $_GET[$key];
                echo "Valid ID : " . ${$key} . "<br />";
            }
            break;

        case 'string':
            if(!preg_match("/^[a-zA-Z]*$/", $_GET[$key])) {
                echo "Invalid name - not alphabetic: " . ${$key} . "<br />";
                break;
            }
            if (strlen($_GET[$key]) > 25) {
                echo "Invalid name - exceeds 25 characters: " . ${$key} . "<br />";
                break;
            }
            echo "Valid ID : " . ${$key} . "<br />";
            break;

        case 'email':
            if (filter_var($_GET[$key], FILTER_VALIDATE_EMAIL)) {
                echo "Valid email : " . ${$key} . "<br />";
            }
    }
    if (!isset(${$key})) {
        ${$key} = NULL;
    }

}



// // Retrieving the values of form elements 
// if ($_SERVER["REQUEST_METHOD"] == "POST") {
//     $id = $_POST["sid"];
//     $name = $_POST["name"];
//     $email = $_POST["email"];
//     $age = $_POST["age"];
// }

// $GLOBALS["ID_length_four"] = True;
// $GLOBALS["ID_correct_format"] = True;

// $GLOBALS["name_alphabetic"] = True;
// $GLOBALS["name_exceed_max_length"]= False;

// $GLOBALS["email_correct_format"] = True;

// $GLOBALS["age_between_16_100"] = True;

// function validateID($id) {
//     if (strlen($id) > 4 or strlen($id) < 4) {
//         $ErrMsg = "ID should be 4 characters long";
//     }
// }

// function validateName($name) {
//     if(!preg_match("/^[a-zA-Z]*$/", $name)) {
//         $name_alphabetic = False;
//     } if (strlen($name) > 25) {
//         $name_exceed_max_length = True;
//     } 

//     if (($name_alphabetic) and !($name_exceed_max_length)) {
//         echo "Name is set to ", $name;
//     }
//     else {
//         if (!($name_alphabetic)) {
//             $ErrMsg = "ERROR (name): Only alphabets and whitespace allowed!";
//             echo $ErrMsg;
//             echo "<br>";
//         }
//         if ($name_exceed_max_length) {
//             $ErrMsg = "ERROR (name): Your name must be less than 25 character!\r\n";
//             echo $ErrMsg;
//         }
//     }
// };

// validateID($id);
// validateName($name);
?>