<?php
require_once dirname(__DIR__).'/config/constant.php';
require_once 'controllers/ContactController.php';

use Api\Controllers\ApiContactController;

// Set headers for CORS and response content type
require_once "./headers.php";



$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

$uri = explode('/', $uri);


for($i=0; $i<count($uri); $i++){
    if($i == 1 || $i == 2){
        if($uri[$i] == 'api'){
            $uriPos = $i;
        }
    }

    if($i == 2 || $i == 3){
        if(str_contains($uri[$i], 'contact')){
            $contactPos = $i;
        }
    }
}


$method = $_SERVER['REQUEST_METHOD'];

if ($uri[$uriPos] === 'api' && str_contains($uri[$contactPos], 'contact')) {
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

