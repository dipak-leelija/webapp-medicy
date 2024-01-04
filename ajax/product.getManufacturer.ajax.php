<?php

require_once dirname(__DIR__).'/config/constant.php';
require_once ROOT_DIR.'_config/sessionCheck.php'; //check admin loggedin or not

require_once CLASS_DIR.'dbconnect.php';
require_once CLASS_DIR.'manufacturer.class.php';
require_once CLASS_DIR.'products.class.php';

$Manufacturer = new Manufacturer();
$Products     = new Products();

if (isset($_GET["id"])) {
    $showProducts = $Products->showProductsById($_GET["id"]);
    // $manufacturerList = $Manufacturer->showManufacturer();
    $manufacturerList = $Manufacturer->showManufacturerById($showProducts[0]['manufacturer_id']);
    $manufacturerList = json_decode($manufacturerList, true);
    // print_r($manufacturerList);
    if ($manufacturerList != NULL) {   
        foreach ($manufacturerList as $row) {
            $manufId =  $row["id"];
            $manufName =  $row["name"];
            $manufName = str_replace("&lt", "<", $manufName);
            $manufName = str_replace("&gt", ">", $manufName);
            $manufName = str_replace("&#39", "'", $manufName);
        }
        echo $row["id"];
    }

    //`products`.`product_composition`   product_composition
    //print_r($showProducts);
    //echo $showProducts[0]['product_composition'];
}

if (isset($_GET["manufName"])) {
    $showProducts = $Products->showProductsById($_GET["manufName"]);
    $showProducts = json_decode($showProducts,true);
    // print_r($showProducts);
    if(isset($showProducts['status']) && $showProducts['status'] == '1'){
        $data = $showProducts['data'][0];
        $manufactureId = $data['manufacturer_id'];
        // print_r($manufactureId);
    }
    // $manufacturerList = $Manufacturer->showManufacturer();
    $manufacturerList = $Manufacturer->showManufacturerById($manufactureId);
    $manufacturerList = json_decode($manufacturerList, true);
    // print_r($manufacturerList);
    if(isset($manufacturerList['status']) && $manufacturerList['status'] == '1'){
        $listData  = $manufacturerList['data'];
        $manufId   =  $listData["id"];
        $manufName =  $listData["name"];
        $manufName = str_replace("&lt", "<", $manufName);
        $manufName = str_replace("&gt", ">", $manufName);
        $manufName = str_replace("&#39", "'", $manufName);
    }
    echo $manufName;
    // if ($manufacturerList != NULL) {   
    //     foreach ($manufacturerList as $row) {
    //         $manufId =  $row["id"];
    //         $manufName =  $row["name"];
    //         $manufName = str_replace("&lt", "<", $manufName);
    //         $manufName = str_replace("&gt", ">", $manufName);
    //         $manufName = str_replace("&#39", "'", $manufName);
    //     }
    //     echo $manufName;
    // }

    //`products`.`product_composition`   product_composition
    //print_r($showProducts);
    //echo $showProducts[0]['product_composition'];
}

if (isset($_GET["name"])) {
    $showProducts = $Products->showProductsById($_GET["name"]);
    // $manufacturerList = $Manufacturer->showManufacturer();
    $manufacturerList = $Manufacturer->showManufacturerById($showProducts[0]['manufacturer_id']);
    $manufacturerList = json_decode($manufacturerList, true);
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