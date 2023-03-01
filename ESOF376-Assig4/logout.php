<?php
if (isset($_GET['session']))
{
session_start();
if(isset($_SESSION['user']))
  unset($_SESSION['user']);
header('location:./');
}
?>