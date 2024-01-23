<?php

require_once dirname(__DIR__) . '/config/constant.php';
require_once ROOT_DIR . '_config/sessionCheck.php'; //check admin loggedin or not

require_once CLASS_DIR . 'dbconnect.php';
require_once CLASS_DIR . 'products.class.php';
require_once CLASS_DIR . 'products.class.php';
require_once CLASS_DIR . 'packagingUnit.class.php';
require_once CLASS_DIR . "itemUnit.class.php";

$Products       = new Products();
$PackagingUnits = new PackagingUnits();
$ItemUnit       = new ItemUnit;

// prodReqStatus
// oldProdReqStatus
// ============= get product name =================
if (isset($_GET["pName"])) {
    // $prodReqStatus = $_GET["prodReqStatus"];

    $showProducts = json_decode($Products->showProductsByIdOnUser($_GET["pName"], $adminId, $_GET["edtiRequestFlag"], $_GET["prodReqStatus"], $_GET["oldProdReqStatus"]));
    $showProducts = $showProducts->data;
    // print_r($showProducts);
    foreach ($showProducts as $row) {
        echo $row->name;
    }
}

// ================ get power ===============
if (isset($_GET["power"])) {
    $showProducts = json_decode($Products->showProductsByIdOnUser($_GET["power"], $adminId, $_GET["edtiRequestFlag"], $_GET["prodReqStatus"], $_GET["oldProdReqStatus"]));
    // print_r($showProducts);
    if($showProducts->status){
        $showProducts = $showProducts->data;

        echo $showProducts[0]->power;
    }
    
}
// echo "Hi";

// ========================= packege Type ====================
if (isset($_GET["pType"])) {
    $showProductsPType = $Products->showProductsByIdOnUser($_GET["pType"], $adminId, $_GET["edtiRequestFlag"], $_GET["prodReqStatus"], $_GET["oldProdReqStatus"]);
    $showPackType = $PackagingUnits->showPackagingUnitById($showProductsPType[0]['packaging_type']);
    // print_r($showPackType);
    foreach ($showPackType as $row) {
        echo '<option value="' . $row["id"] . '">' . $row["unit_name"] . '</option>';
    }
}

// ========================== packege In ====================
if (isset($_GET["packegeIn"])) {
    $showProductsPackegeIn = json_decode($Products->showProductsByIdOnUser($_GET["packegeIn"], $adminId,$_GET["edtiRequestFlag"], $_GET["prodReqStatus"], $_GET["oldProdReqStatus"]));
    
    if ($showProductsPackegeIn->status) {
        $showProductsPackegeIn = $showProductsPackegeIn->data;
        // print_r($showProductsPackegeIn);

        $showPackType = $PackagingUnits->showPackagingUnitById($showProductsPackegeIn[0]->packaging_type);
        foreach ($showPackType as $row) {
            echo $row["unit_name"];
        }
    }
}

// ======================= weightage ========================
if (isset($_GET["weightage"])) {
    $showProducts = json_decode($Products->showProductsByIdOnUser($_GET["weightage"], $adminId, $_GET["edtiRequestFlag"], $_GET["prodReqStatus"], $_GET["oldProdReqStatus"]));
    $showProducts = $showProducts->data;
    // print_r($showProducts);
    // $showWeightage = $Products->showProductsById($showProducts[0]['packaging_type']);
    // print_r($showPackType);
    foreach ($showProducts as $row) {
        echo $row->unit_quantity;
    }
}

// ========================= unit ==============================
if (isset($_GET["unit"])) {
    $showProducts = json_decode($Products->showProductsByIdOnUser($_GET["unit"], $adminId, $_GET["edtiRequestFlag"], $_GET["prodReqStatus"], $_GET["oldProdReqStatus"]));
    $showProducts = $showProducts->data;

    foreach ($showProducts as $row) {
        $unitId =  $row->unit;

        echo $ItemUnit->itemUnitName($unitId);
    }
}
