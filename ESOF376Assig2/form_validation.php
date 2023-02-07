<?php
/*
* Name: Will Augustine
*
* Desctiption: PHP script to validate form for Assignment2.html
*/
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
            }
            break;

        case 'string':
            if(!preg_match("/^[a-zA-Z]*$/", $_POST[$key])) {
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
            break;

        case 'email':
            if (filter_var($_POST[$key], FILTER_VALIDATE_EMAIL)) {
                ${$key} = $_POST[$key];
                echo "Valid email : " . ${$key} . "<br />";
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
                echo "Valid age : " . ${$key} . "<br />";
            }
            break;

    }
    if (!isset(${$key})) {
        ${$key} = NULL;
    }
}
?>