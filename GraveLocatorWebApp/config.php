<!-- Setup file that contains the functions used throughout this project.
     Each function uses an input to perform an SQL query on the database 
     --'gravelocwebappdb'
     and either returns the needed element(s) within the database or 
     inserts new ones according to the function calls in the other '.php' files -->
<?php
$dbname = "gravelocwebappdb";
$hostname = "localhost";
$dbusername = "webappdbuser";
$dbpassword = "webAppPass$";

// Create connection
$conn = new mysqli($hostname, $dbusername, $dbpassword,  $dbname);

// Check connection
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}
/* given username and password info, VerifyUser will 
   query the database to ensure that a user exists with the entered credentials*/
function VerifyUser($username, $password){
    global $conn;
    $query ="SELECT * from users WHERE username ='".$username."' and password ='".$password."'"; 
    $return = mysqli_query($conn, $query);
    return $return;
}
/* given a searchID, matchSearchID will return a block number, to be used in conjunction with returnGPS*/
function matchSearchID($searchID){
    global $conn;
    $query = " SELECT blockChosen from searches WHERE searchID = '".$searchID."'"; 
    $result = mysqli_query($conn, $query);
    $rs = mysqli_fetch_array($result);
    return $rs[0];
}
/* given a block number, returnGPS will return the GPS information (latitude and longitude of block corners and center) from the Blocks table of 'gravelocwebappdb' */
function returnGPS($blockChosen){
    global $conn;
 
    $query = " SELECT * from blocks WHERE blockID = '".$blockChosen."'"; 
    $result = mysqli_query($conn, $query);
    $rs = mysqli_fetch_assoc($result);
    return $rs; 
}
/* given a searchID, name, block, lot, plot, blockImage, and blockImageHighlight, will save a unique search to the Searches table of 'gravelocwebappdb'*/
function InsertSearch ($searchID, $name, $blockChosen,  $lot, $plot, $blockImgFilename, $HLImgFilename){
    global $conn;
    $query = "INSERT INTO searches(`searchID`, `name`, `blockChosen`, `lot`, `plot`, `blockImageFilename`, `HLImageFilename`) VALUES ('$searchID', '$name', '$blockChosen', '$lot', '$plot', '$blockImgFilename', '$HLImgFilename')";
    $task = mysqli_query($conn, $query);
}
/* given a surveyID, searchID, and 3 survey responses, InsertSurvey will save a unique survey to the Surveys table of 'gravelocwebappdb'*/
function InsertSurvey ($surveyID, $searchID, $surveyResponseOne, $surveyResponseTwo, $surveyResponseThree){
    global $conn;
    $query = "INSERT INTO Surveys(`surveyID`, `searchID`, `surveyResponseOne`, `surveyResponseTwo`, `surveyResponseThree`) VALUES ( '$surveyID', '$searchID', '$surveyResponseOne', '$surveyResponseTwo', '$surveyResponseThree')";
    $task = mysqli_query($conn, $query);
}
/* given a searchID, confirmSearch will return either True if that ID si valid in the Searches table of'gravelocwebappdb' and false otherwise*/
function confirmSearch($searchID){
    global $conn;
    $query = " SELECT * from searches WHERE searchID = ".'"'.$searchID.'"'; 
    $task = mysqli_query($conn, $query);
    $result = mysqli_num_rows($task);
    if($result == 1){
        return true;
    }
    else{
        return false;
    }
}
/* given a blockID, RetrieveBlockImage will return the associated imagefrom the BlockImages table of 'gravelocwebappdb */
function RetrieveBlockImage ($blockInfo){
    global $conn;
    $query = "SELECT * from blockimages WHERE blockID ='".$blockInfo."'"; 
    $blockNum = mysqli_query($conn, $query);
    return $blockNum;
}
/* given a searchID, RetrieveSearchInfo will return the associative array represening the entire entry within Searches */
function RetrieveSearchInfo ($searchID){
    global $conn;
    $query = "SELECT * from searches WHERE searchID ='".$searchID."'"; 
    $result = mysqli_query($conn, $query);
    $rs = mysqli_fetch_assoc($result);
    return $rs;
}/* given a blockID, checkValidBlock will return the number of rows which match to this blockID */
function checkValidBlock($blockID){
    global $conn;
    $query = "SELECT * FROM blocks WHERE blockID='".$blockID."'";
    $response = mysqli_query($conn, $query);
    $rows = mysqli_num_rows($response);
    return $rows;
}
?>