<?php
require_once dirname(__DIR__).'/config/constant.php';
require_once 'controllers/InfoController.php';

use Api\Controllers\ApiInfoController;

require_once "./headers.php";

echo $origin;

$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
// echo $uri;

$uri = explode('/', $uri);



// for($i = 0; $i<count(($uri)); $i++){
//     if($uri[1] === 'api' || $uri[2] === 'api'){
//         $api = $i;
//     }

//     if( str_contains($uri[$i], 'infos')){
//         $infos = $i;
//     }
// }



$method = $_SERVER['REQUEST_METHOD'];

if ($uri[2] === 'api' && str_contains($uri[3], 'infos')) {
    $controller = new ApiInfoController();

    switch ($method) {
        case 'GET':
            if (isset($uri[3]) && is_numeric($uri[3])) {
                $controller->getInfoById($uri[3]);
            } else {
                $controller->getAllInfos();
            }
            break;

        case 'POST':
            $data = json_decode(file_get_contents('php://input'), true);
            $controller->createInfo($data);
            break;

        case 'PUT':
            if (isset($uri[3]) && is_numeric($uri[3])) {
                $data = json_decode(file_get_contents('php://input'), true);
                $controller->updateInfo($uri[3], $data);
            }
            break;

        case 'DELETE':
            if (isset($uri[3]) && is_numeric($uri[3])) {
                $controller->deleteInfo($uri[3]);
            }
            break;

        default:
            header("HTTP/1.1 405 Method Not Allowed");
            break;
    }
}else {
    header("HTTP/1.1 404 Not Found");
    echo json_encode(["message" => "Endpoint not found"]);
}
