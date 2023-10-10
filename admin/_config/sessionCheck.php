<?php 
session_start();
date_default_timezone_set("Asia/Kolkata");

// Check if a specific session variable exists to determine if the user is logged in
if (!isset($_SESSION['LOGGEDIN'])) {
    header("Location: login.php");
    exit;
}
