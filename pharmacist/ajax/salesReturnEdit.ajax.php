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
    echo $bill[0]['reff_by'];
}


// get products list
if (isset($_GET["products"])) {
    $invoiceId = $_GET["products"];
    $items = $salesReturn->salesReturnDetailsbyInvoiceId($invoiceId);
    echo '<option value="" selected disabled>Select item</option>';
    print_r($items);
    foreach ($items as $item) {
        $product = $Products->showProductsById($item['product_id']);
        //print_r($product); echo "<br><br>";
        echo '<option data-invoice="'.$invoiceId.'" data-batch="'.$item['batch_no'].'" value="'.$item['product_id'].'">'.$product[0]['name'].'</option>';
    }
}


// ===========================  Item Details   =========================== 

// get product exp date
if (isset($_GET["exp-date"])) {
    $invoice = $_GET["exp-date"];
    $item = $salesReturn->selectSalesReturnDetailsbyInvoiceIdProductIdBatchNo($invoice, $_GET["p-id"], $_GET["batch"]);
    //print_r($item);
    echo $item[0]['exp'];
}

// get product full unit
if (isset($_GET["unit"])) {
    $invoice = $_GET["unit"];
    $item = $salesReturn->selectSalesReturnDetailsbyInvoiceIdProductIdBatchNo($invoice, $_GET["p-id"], $_GET["batch"]);
    echo $item[0]['weatage'];
}


// get product mrp
if (isset($_GET["mrp"])) {
    $invoice = $_GET["mrp"];
    $item = $StockOut->stockOutSelect($invoice, $_GET["p-id"], $_GET["batch"]);
    echo $item[0]['mrp'];
}



// get product qty
if (isset($_GET["qty"])) {
    $invoice = $_GET["qty"];
    //$item = $StockOut->stockOutSelect($invoice, $_GET["p-id"], $_GET["batch"]);
    $item = $StockOut->salesReturnDetails($invoice, $_GET["p-id"], $_GET["batch"]);
    //print_r($item);
    
    echo $item[0]['return'];
    
}



// get product discount
if (isset($_GET["disc"])) {
    $invoice = $_GET["disc"];

    $item = $salesReturn->selectSalesReturnDetailsbyInvoiceIdProductIdBatchNo($invoice, $_GET["p-id"], $_GET["batch"]);
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

    $item = $salesReturn->selectSalesReturnDetailsbyInvoiceIdProductIdBatchNo($invoice, $_GET["p-id"], $_GET["batch"]);
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

    $item = $salesReturn->selectSalesReturnDetailsbyInvoiceIdProductIdBatchNo($invoice, $_GET["p-id"], $_GET["batch"]);
    echo $item[0]['amount'];
}


// // get product full unit
// if (isset($_GET["unit"])) {
//     $invoice = $_GET["unit"];

//     $item = $StockOut->stockOutSelect($invoice, $_GET["p-id"], $_GET["batch"]);
//     echo $item[0]['weatage'];
// }



// // get product full unit
// if (isset($_GET["unit"])) {
//     $invoice = $_GET["unit"];

//     $item = $StockOut->stockOutSelect($invoice, $_GET["p-id"], $_GET["batch"]);
//     echo $item[0]['weatage'];
// }
?>


