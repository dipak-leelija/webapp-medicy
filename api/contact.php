<?php
require_once dirname(__DIR__).'/config/constant.php';
require_once 'controllers/ContactController.php';

use Api\Controllers\ApiContactController;

// Set headers for CORS and response content type
require_once "./headers.php";

// echo "origin : ".$origin;

// echo "<br>allowed_origin : ";
// print_r($allowed_origins);

$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
// echo "<br><br>uri : ".$uri;

$uri = explode('/', $uri);

// echo "<br><br>array uri : ";
// print_r($uri);

$method = $_SERVER['REQUEST_METHOD'];

if ($uri[2] === 'api' && str_contains($uri[3], 'contact')) {
    $controller = new ApiContactController();

    switch ($method) {
        case 'POST':
            $data = json_decode(file_get_contents('php://input'), true);
            $controller->createContact($data);
            break;
    }
}else {
    header("HTTP/1.1 404 Not Found");
    echo json_encode(["message" => "Endpoint not found"]);
}

