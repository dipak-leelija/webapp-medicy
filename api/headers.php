<?php

$origin = $_SERVER['HTTP_ORIGIN'] ?? '';
$allowed_origins = ['https://medicy.in', 'http://localhost:5173'];

$server = LOCAL_DIR;

// Set headers for CORS and response content type
if (in_array($origin, $allowed_origins)) {
    // echo $origin;
    header("Access-Control-Allow-Origin: $origin");
    header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
    header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
    header("Content-Type: application/json; charset=UTF-8");
}

?>