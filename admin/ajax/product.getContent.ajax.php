<?php
require_once '../../php_control/manufacturer.class.php';
require_once '../../php_control/products.class.php';

$Manufacturer = new Manufacturer();
$Products     = new Products();

// ===================== product content =====================

if (isset($_GET["pid"])) {
    
    $showProducts = $Products->showProductsById($_GET["pid"]);
    
    echo $showProducts[0]['product_composition'];
}

?>