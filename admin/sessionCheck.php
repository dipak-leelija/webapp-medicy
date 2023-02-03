<?php
session_start();
$_SESSION['last_page'] = $_SERVER['REQUEST_URI'];
if($_SESSION['admin'] == false){
    header("Location: login.php");
    exit();
  }
?>
				
