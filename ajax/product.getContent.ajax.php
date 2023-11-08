<?php
require_once dirname(dirname(__DIR__)).'/config/constant.php';
require_once ADM_DIR.'_config/sessionCheck.php'; //check admin loggedin or not

require_once CLASS_DIR.'dbconnect.php';
require_once CLASS_DIR.'manufacturer.class.php';
require_once CLASS_DIR.'products.class.php';

$Manufacturer = new Manufacturer();
$Products     = new Products();

// ===================== product content =====================

if (isset($_GET["pid"])) {
    
    $showProducts = $Products->showProductsById($_GET["pid"]);
    
    echo $showProducts[0]['product_composition'];
}

?>