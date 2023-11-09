<?php

require_once dirname(__DIR__).'/config/constant.php';
require_once ROOT_DIR.'_config/sessionCheck.php'; //check admin loggedin or not

require_once CLASS_DIR.'dbconnect.php';
require_once CLASS_DIR.'products.class.php';
require_once CLASS_DIR.'products.class.php';
require_once CLASS_DIR.'packagingUnit.class.php';

$Products       = new Products();
$PackagingUnits = new PackagingUnits();

// ============= get product name =================
if (isset($_GET["pName"])) {
    $showProducts = $Products->showProductsById($_GET["pName"]);
    foreach ($showProducts as $row) {
       echo $row["name"];
    }
}

// ================ get power ===============
if (isset($_GET["power"])) {
    $showProducts = $Products->showProductsById($_GET["power"]);
    // print_r($showProducts);
    echo $showProducts[0]['power'];
}
// echo "Hi";

// ========================= packege Type ====================
if (isset($_GET["pType"])) {
    $showProductsPType = $Products->showProductsById($_GET["pType"]);
    $showPackType = $PackagingUnits->showPackagingUnitById($showProductsPType[0]['packaging_type']);
    // print_r($showPackType);
    foreach ($showPackType as $row) {
       echo '<option value="'.$row["id"].'">'.$row["unit_name"].'</option>';

    }
}

// ========================== packege In ====================
if (isset($_GET["packegeIn"])) {
    $showProductsPackegeIn = $Products->showProductsById($_GET["packegeIn"]);
    $showPackType = $PackagingUnits->showPackagingUnitById($showProductsPackegeIn[0]['packaging_type']);
    foreach ($showPackType as $row) {
       echo $row["unit_name"];
    }
}

// ======================= weightage ========================
if (isset($_GET["weightage"])) {
    $showProducts = $Products->showProductsById($_GET["weightage"]);
    // print_r($showProducts);
    // $showWeightage = $Products->showProductsById($showProducts[0]['packaging_type']);
    // print_r($showPackType);
    foreach ($showProducts as $row) {
       echo $row["unit_quantity"];
    }
}

// ========================= unit ==============================
if (isset($_GET["unit"])) {
    $showProducts = $Products->showProductsById($_GET["unit"]);
    foreach ($showProducts as $row) {
       echo $row["unit"];
    }
}
?>