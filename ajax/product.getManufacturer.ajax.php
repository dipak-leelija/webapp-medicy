<?php

require_once dirname(__DIR__) . '/config/constant.php';
require_once ROOT_DIR . '_config/sessionCheck.php'; //check admin loggedin or not

require_once CLASS_DIR . 'dbconnect.php';
require_once CLASS_DIR . 'manufacturer.class.php';
require_once CLASS_DIR . 'products.class.php';

$Manufacturer = new Manufacturer();
$Products     = new Products();

if (isset($_GET["id"])) {
    $showProducts = json_decode($Products->showProductsByIdOnUser($_GET["id"], $adminId));

    if ($showProducts->status) {
        $prodData =  $showProducts->data;
        
        if(isset($prodData[0]->manufacturer_id)){
            $manufId = $prodData[0]->manufacturer_id;
        }else{
            $manufId = '';
        }
    } else {
        $manufId = '';
    }
    echo $manufId;
}

if (isset($_GET["manufName"])) {
    $showProducts = json_decode($Products->showProductsByIdOnUser($_GET["manufName"], $adminId));

    if ($showProducts->status) {
        $prodData = $showProducts->data;

        if(isset($prodData[0]->manufacturer_id)){
            $manufactureId = $prodData[0]->manufacturer_id;
        }else{
            $manufactureId = null;
        }
    } else {
        $manufactureId = null;
    }


    if ($manufactureId != null) {
        $manufacturerList = json_decode($Manufacturer->showManufacturerById($manufactureId));
        if ($manufacturerList->status) {
            $manufData  = $manufacturerList->data;
            $manufId   =  $manufData->id;
            $manufName =  $manufData->name;
            $manufName = str_replace("&lt", "<", $manufName);
            $manufName = str_replace("&gt", ">", $manufName);
            $manufName = str_replace("&#39", "'", $manufName);
        }
    }else{
        $manufName = '';
    }
    echo $manufName;
}




///////////////////////////////////////////////////////////////////////////////////////////////////////

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
