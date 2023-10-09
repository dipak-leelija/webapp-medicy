<?php 
date_default_timezone_set("Asia/Kolkata");
session_start();

// Check if a specific session variable exists to determine if the user is logged in
if (isset($_SESSION['loggedin']) == false) {
  header("Location: login.php");
  exit();
}
