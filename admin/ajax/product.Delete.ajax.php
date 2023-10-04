<?php
ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);

require_once '../../php_control/products.class.php';

$Products       = new Products();


$productTableId = $_POST['id'];

$deleteProduct  = $Products->deleteProduct($productTableId);

if ($deleteProduct) {
    echo 1;
}else {
    echo 0;
}


?>