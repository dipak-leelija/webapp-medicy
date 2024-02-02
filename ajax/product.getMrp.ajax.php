<?php
require_once dirname(__DIR__).'/config/constant.php';
require_once ROOT_DIR.'_config/sessionCheck.php'; //check admin loggedin or not

require_once CLASS_DIR.'dbconnect.php';
require_once CLASS_DIR.'products.class.php';
require_once CLASS_DIR.'currentStock.class.php';
require_once CLASS_DIR.'gst.class.php';


$Products       = new Products();
$CurrentStock = new CurrentStock();
$Gst            = new Gst;

// prodReqStatus

if (isset($_GET["id"])) {

    $prodData = json_decode($Products->showProductsById($_GET["id"]));
    if($prodData->status){
        $editReqFlag = 'not null';
    }else{
        $editReqFlag = '';
    }

    $showProducts = json_decode($Products->showProductsByIdOnUser($_GET["id"], $adminId, $editReqFlag));
    $showProducts = $showProducts->data;

    echo $showProducts[0]->mrp;
}

if (isset($_GET["stockmrp"])) {
    $stock = $CurrentStock->showCurrentStocByPId($_GET["stockmrp"]);
    echo $stock[0]['mrp'];
}


// =============== ptr check =====================

if (isset($_GET["ptrChk"])) {

    $prodData = json_decode($Products->showProductsById($_GET["ptrChk"]));
    if($prodData->status){
        $editReqFlag = 'not null';
    }else{
        $editReqFlag = '';
    }

    $showProducts = json_decode($Products->showProductsByIdOnUser($_GET["ptrChk"], $adminId, $editReqFlag));
    $showProducts = $showProducts->data;

    $mrp = $showProducts[0]->mrp;

    if($showProducts[0]->gst != null || $showProducts[0]->gst != ''){
        $col = 'id';
        $gstData = json_decode($Gst->seletGstByColVal($col, $showProducts[0]->gst));
        $gstData = $gstData->data;
        $gstval = $gstData[0]->percentage;

        $maxptr = ($mrp*100)/($gstval+100);
        $maxptr = floatval($maxptr);
        $maxptr = round($maxptr,2);
        echo $maxptr;
    } else {
        echo $mrp;
    }
   
}
?>