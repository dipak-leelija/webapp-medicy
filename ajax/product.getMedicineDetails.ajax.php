<?php

require_once dirname(__DIR__).'/config/constant.php';
require_once ROOT_DIR.'_config/sessionCheck.php'; //check admin loggedin or not

require_once CLASS_DIR.'dbconnect.php';
require_once CLASS_DIR.'products.class.php';
require_once CLASS_DIR.'products.class.php';
require_once CLASS_DIR.'packagingUnit.class.php';
require_once CLASS_DIR."itemUnit.class.php";

$Products       = new Products();
$PackagingUnits = new PackagingUnits();
$ItemUnit       = new ItemUnit;

// ============= get product name =================
if (isset($_GET["pName"])) {
    $showProducts = json_decode($Products->showProductsByIdOnUser($_GET["pName"], $adminId));
    $showProducts = $showProducts->data;
    // print_r($showProducts);
    foreach ($showProducts as $row) {
       echo $row->name;
    }
}

// ================ get power ===============
if (isset($_GET["power"])) {
    $showProducts = $Products->showProductsByIdOnUser($_GET["power"], $adminId);
    print_r($showProducts);
    // echo $showProducts[0]['power'];
}
// echo "Hi";

// ========================= packege Type ====================
if (isset($_GET["pType"])) {
    $showProductsPType = $Products->showProductsByIdOnUser($_GET["pType"], $adminId);
    $showPackType = $PackagingUnits->showPackagingUnitById($showProductsPType[0]['packaging_type']);
    // print_r($showPackType);
    foreach ($showPackType as $row) {
       echo '<option value="'.$row["id"].'">'.$row["unit_name"].'</option>';

    }
}

// ========================== packege In ====================
if (isset($_GET["packegeIn"])) {
    $showProductsPackegeIn = $Products->showProductsByIdOnUser($_GET["packegeIn"], $adminId);
    $showPackType = $PackagingUnits->showPackagingUnitById($showProductsPackegeIn[0]['packaging_type']);
    foreach ($showPackType as $row) {
       echo $row["unit_name"];
    }
}

// ======================= weightage ========================
if (isset($_GET["weightage"])) {
    $showProducts = json_decode($Products->showProductsByIdOnUser($_GET["weightage"], $adminId));
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
    $showProducts = json_decode($Products->showProductsByIdOnUser($_GET["unit"], $adminId));
    $showProducts = $showProducts->data;

    foreach ($showProducts as $row) {
        $unitId =  $row->unit;

        echo $ItemUnit->itemUnitName($unitId);
    }
}
?>