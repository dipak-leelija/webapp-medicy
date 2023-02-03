<?php

//Connecting to Databse
$servername = "localhost";
$username = "root";
$password = "";
$database = "medicy_db";

//Create a Connection
$conn = mysqli_connect($servername, $username, $password, $database);

//Die if connection was not successful
if (!$conn) {
  die("Connection Failed ☹☹: ". mysqli_connect_error());
}

//redirect do dashboard after successfull login
// header("Location: dashboard.html");
// die();

?>