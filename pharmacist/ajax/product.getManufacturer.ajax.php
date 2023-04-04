<?php
require_once '../../php_control/manufacturer.class.php';
require_once '../../php_control/products.class.php';
$Manufacturer = new Manufacturer();
$Products     = new Products();

if (isset($_GET["id"])) {
    $showProducts = $Products->showProductsById($_GET["id"]);

    $manufacturerList = $Manufacturer->showManufacturerById($showProducts[0]['manufacturer_id']);
    if ($manufacturerList != NULL) {   
        foreach ($manufacturerList as $row) {
            echo $row["id"];
        }
    }

    //`products`.`product_composition`   product_composition
    //print_r($showProducts);
    //echo $showProducts[0]['product_composition'];
}

if (isset($_GET["name"])) {
    $showProducts = $Products->showProductsById($_GET["name"]);

    $manufacturerList = $Manufacturer->showManufacturerById($showProducts[0]['manufacturer_id']);
    if ($manufacturerList != NULL) {   
        foreach ($manufacturerList as $row) {
            echo $row["name"];
        }
    }
}

?>