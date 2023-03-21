<?php
##########################################################################################################
#                                                                                                        #
#                                      Sales Return Edit Page                              (RD)          #
#                                                                                                        #
##########################################################################################################
require_once "../../php_control/stockOut.class.php";
require_once '../../php_control/products.class.php';
require_once '../../php_control/patients.class.php';
require_once "../../php_control/salesReturn.class.php";

$StockOut   = new StockOut();
$Products   = new Products();
$Patients   = new Patients();
$salesReturn = new SalesReturn();

// get patient name
if (isset($_GET["patient"])) {
    $invoiceId = $_GET["patient"];
    $bill = $StockOut->stockOutDisplayById($invoiceId);
    //print_r($bill);
    if($bill[0]['customer_id'] == "Cash Sales"){
        $patient = "Cash Sales";
        echo $patient;
    }
    else{
    $patient = $Patients->patientsDisplayByPId($bill[0]['customer_id']);
    echo $patient[0]['name'];
    }
}

// get Bill Date
if (isset($_GET["bill-date"])) {
    $invoiceId = $_GET["bill-date"];
    $bill = $StockOut->stockOutDisplayById($invoiceId);
    echo date("d-m-Y", strtotime($bill[0]['bill_date']));
}


// get Reffered Doctor
if (isset($_GET["reff-by"])) {
    $invoiceId = $_GET["reff-by"];
    $bill = $StockOut->stockOutDisplayById($invoiceId);
    echo $bill[0]["reff_by"];
}

// get products list
if (isset($_GET["products"])) {

    $invoiceId = $_GET["products"];
    $salesRetundid = $_GET["salesreturnID"];

    //echo "$invoiceId<br>$salesRetundid";
    
    $items = $salesReturn->salesReturnbyInvoiceIdsalesReturnId($invoiceId, $salesRetundid);
    echo '<option value="" selected disabled>Select item</option>';
    //print_r($items);
    foreach ($items as $item) {
        $product = $Products->showProductsById($item['product_id']);
        //print_r($product); echo "<br><br>";
        echo '<option data-invoice="'.$invoiceId.'" data-batch="'.$item['batch_no'].'" value="'.$item['product_id'].'">'.$product[0]['name'].'</option>';
    }
}

// CHECK DATA
// if (isset($_GET["products"])) {
//     $invoiceId = $_GET["products"];
//     $salesRtnId = $_GET["salesreturnID"];
//     $bill = $StockOut->stockOutDisplayById($invoiceId);
//     echo "$invoiceId<br>$salesRtnId";
// }
// ===========================  Item Details   =========================== 

// get product exp date
if (isset($_GET["exp-date"])) {
    $invoice = $_GET["exp-date"];
    $item = $salesReturn->salesReturnDetialSelect($invoice, $_GET["p-id"], $_GET["batch"]);
    //print_r($item);
    echo $item[0]['exp'];
}

// get product full unit
if (isset($_GET["unit"])) {
    $invoice = $_GET["unit"];
    $item = $salesReturn->salesReturnDetialSelect($invoice, $_GET["p-id"], $_GET["batch"]);
    echo $item[0]['weatage'];
}

// get product mrp
if (isset($_GET["mrp"])) {
    $invoice = $_GET["mrp"];
    $item = $StockOut->stockOutSelect($invoice, $_GET["p-id"], $_GET["batch"]);
    echo $item[0]['mrp'];
}


// get product purchase qty
if (isset($_GET["pqty"])) {
    $invoice = $_GET["pqty"];
    //$item = $StockOut->stockOutSelect($invoice, $_GET["p-id"], $_GET["batch"]);
    $item = $salesReturn->salesReturnDetialSelect($invoice, $_GET["p-id"], $_GET["batch"]);
    //print_r($item);
    echo $item[0]['qty'];
}

// get product curretn qty
if (isset($_GET["qty"])) {
    $invoice = $_GET["qty"];
    $totalReturnqty = 0;
    //$item = $StockOut->stockOutSelect($invoice, $_GET["p-id"], $_GET["batch"]);
    $item = $salesReturn->salesReturnDetialSelect($invoice, $_GET["p-id"], $_GET["batch"]);
    //print_r($item);
    $sizeOfitem = sizeof($item);
    for($i = 0; $i<$sizeOfitem; $i++){
        $totalReturnqty = $totalReturnqty + $item[$i]['return'];
    }
    $qty = $item[0]['qty'] - $totalReturnqty;
    echo $qty;
    
}

// get product return qty
if (isset($_GET["rtnqty"])) {
    $invoice = $_GET["rtnqty"];
    //$item = $StockOut->stockOutSelect($invoice, $_GET["p-id"], $_GET["batch"]);
    $item = $salesReturn->salesReturnDetialSelect($invoice, $_GET["p-id"], $_GET["batch"]);
    //print_r($item);
    
    echo $item[0]['return'];
}

// get product discount
if (isset($_GET["disc"])) {
    $invoice = $_GET["disc"];

    $item = $salesReturn->salesReturnDetialSelect($invoice, $_GET["p-id"], $_GET["batch"]);
    echo $item[0]['disc'];
}


// get product discount price
if (isset($_GET["disc-price"])) {
    $invoice = $_GET["disc-price"];

    $item = $StockOut->stockOutSelect($invoice, $_GET["p-id"], $_GET["batch"]);
    echo $item[0]['d_price'];
}


// get product gst percentage
if (isset($_GET["gst"])) {
    $invoice = $_GET["gst"];

    $item = $salesReturn->salesReturnDetialSelect($invoice, $_GET["p-id"], $_GET["batch"]);
    echo $item[0]['gst'];
}


// get product taxable amount
if (isset($_GET["taxable"])) {
    $invoice = $_GET["taxable"];

    $item = $StockOut->stockOutSelect($invoice, $_GET["p-id"], $_GET["batch"]);
    echo $item[0]['gst_amount'];
}


// get product amount
if (isset($_GET["amount"])) {
    $invoice = $_GET["amount"];

    $item = $salesReturn->salesReturnDetialSelect($invoice, $_GET["p-id"], $_GET["batch"]);
    echo $item[0]['amount'];
}

?>


