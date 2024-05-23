<?php
require_once dirname(__DIR__).'/config/constant.php';
require_once dirname(__DIR__) . '/classes/dbconnection.php';
require_once 'Models/PlanModel.php';
require_once 'Controllers/PlansController.php';

use Controllers\PlansController;

// Get the origin of the request
$origin = $_SERVER['HTTP_ORIGIN'] ?? '';

$allowed_origin = 'http://localhost:5173';

// Set headers for CORS and response content type
if ($origin === $allowed_origin) {
    header("Access-Control-Allow-Origin: $allowed_origin");
    header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
    header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
    header("Content-Type: application/json; charset=UTF-8");
}

// Handle preflight OPTIONS request
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    header("HTTP/1.1 200 OK");
    exit();
}

$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$uri = explode('/', $uri);

$method = $_SERVER['REQUEST_METHOD'];

if ($uri[2] === 'api' && str_contains($uri[3], 'plans')) {

    $controller = new PlansController();

    switch ($method) {
        case 'GET':
            $plans = $controller->getAllPlans();
            echo json_encode($plans);
            break;

        default:
            header("HTTP/1.1 405 Method Not Allowed");
            break;
    }
} else {
    header("HTTP/1.1 404 Not Found");
    echo json_encode(["message" => "Endpoint not found"]);
}
