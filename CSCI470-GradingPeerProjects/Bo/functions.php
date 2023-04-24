<?php // Replace these values with your own database credentials---------------------------------------------------------------
  $dbhost  = 'localhost';    // Unlikely to require changing
  $dbname  = 'locator';   // Modify these...
  $dbuser  = 'root';   // ...variables according
  $dbpass  = 'password';   // ...to your installation

  $con = new mysqli($dbhost, $dbuser, $dbpass, $dbname);
  session_start();

  if (isset($_SESSION['username'])) {
      $loggedin = TRUE;
      $user = $_SERVER['username'];
  }
  else {
      $loggedin = FALSE;
  }

  function queryDB($query) {
      global $con;
      $fetched = $con->query($query);
      if (!$fetched) {
          die("error");
      }
      return $fetched;
    }

    function cleanString($data) {
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        return $data;
    }

?>