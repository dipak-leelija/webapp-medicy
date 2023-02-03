<?php
ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);

require_once '../../php_control/products.class.php';


$productTableId = $_POST['id'];

$Products       = new Products();
$deleteProduct  = $Products-> deleteProduct($productTableId);

if ($deleteProduct) {
    echo 1;
}else {
    echo 0;
}


?>