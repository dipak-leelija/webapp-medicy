<?php
require_once '../../php_control/manufacturer.class.php';
require_once '../../php_control/products.class.php';
$Manufacturer = new Manufacturer();
$Products     = new Products();

if (isset($_GET["id"])) {
    $showProducts = $Products->showProductsById($_GET["id"]);
    // $manufacturerList = $Manufacturer->showManufacturer();
    $manufacturerList = $Manufacturer->showManufacturerById($showProducts[0]['manufacturer_id']);
    // print_r($manufacturerList);
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
    // $manufacturerList = $Manufacturer->showManufacturer();
    $manufacturerList = $Manufacturer->showManufacturerById($showProducts[0]['manufacturer_id']);
    // print_r($manufacturerList);
    if ($manufacturerList != NULL) {   
        foreach ($manufacturerList as $row) {
            $manufName =  $row["name"];
            $manufName = str_replace("&lt", "<", $manufName);
            $manufName = str_replace("&gt", ">", $manufName);
            $manufName = str_replace("&#39", "'", $manufName);

            echo $manufName;
        }
    }
}

?>