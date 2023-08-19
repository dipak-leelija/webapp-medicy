<?php
##########################################################################################################
#                                                                                                        #
#                                           Sales Return Page                                            #
#                                                                                                        #
##########################################################################################################
require_once "../../php_control/stockOut.class.php";
require_once '../../php_control/products.class.php';
require_once '../../php_control/patients.class.php';
require_once '../../php_control/salesReturn.class.php';

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

    $items = $StockOut->stockOutDetailsById($invoiceId); // bill invoice details
    // print_r($items);

    echo '<option value="" selected enable>Select item</option>';
    foreach ($items as $item) {
        echo '<option data-invoice="'.$invoiceId.'" data-batch="'.$item['batch_no'].'" value="'.$item['item_id'].'">'.$item['item_name'].'</option>';
    }
}

// ===========================  Item Details   =========================== 


//product id
if (isset($_GET["prod-id"])) {
    $invoice = $_GET["prod-id"];
    $attribute1 = 'invoice_id';
    $attribute2 = 'item_id';
    $item = $StockOut->stokOutDetailsData($attribute1, $invoice, $attribute2, $_GET["p-id"]);
    // print_r( $item);
    echo $item[0]['product_id'];
}


// get product exp date
if (isset($_GET["exp-date"])) {
    $invoice = $_GET["exp-date"];
    $item = $StockOut->stockOutSelect($invoice, $_GET["p-id"]);
    echo $item[0]['exp_date'];
}

// get product full unit
if (isset($_GET["unit"])) {
    $invoice = $_GET["unit"];
    $item = $StockOut->stockOutSelect($invoice, $_GET["p-id"]);
    echo $item[0]['weatage'];
}

// get product mrp
if (isset($_GET["mrp"])) {
    $invoice = $_GET["mrp"];
    $item = $StockOut->stockOutSelect($invoice, $_GET["p-id"]);
    echo $item[0]['mrp'];
}

//get product purchase quantity
if (isset($_GET["p_qty"])) {
    $invoice = $_GET["p_qty"];
    $itemId = $_GET["p-id"];

    $item = $StockOut->stockOutSelect($invoice, $itemId);
    foreach($item as $item){
        $itemWeatage = $item['weatage'];
        $itemType = preg_replace('/[0-9]/', '', $itemWeatage);
        if($itemType == 'tab' || $itemType == 'cap'){
            echo $item['loosely_count'];
        }else{
            echo $item['qty'];
        }
    }
}

// get product current qty
if (isset($_GET["qty"])) {
    $invoice = $_GET["qty"];
    $itemId = $_GET["p-id"];
    $batchNo = $_GET["batch"];
    $totalReturnQTY = 0;

    $item = $StockOut->stockOutSelect($invoice, $itemId); // details from phermacy invoice
    $itemWeatage = $item[0]['weatage'];
    $itemType = preg_replace('/[0-9]/', '', $itemWeatage);

    $table = 'invoice_id';
    $salesReturnData = $salesReturn->selectSalesReturn($table, $invoice);
    if($salesReturnData != null){
        $id = $salesReturnData[0]['id'];
    }else{
        $id = null;
    }

    $tabel1 = 'sales_return_id';
    $tabel2 = 'item_id';
    $itemChek = $salesReturn->seletReturnDetailsBy($tabel1, $id, $tabel2, $itemId);
    foreach($itemChek as $itemChek){
        $totalReturnQTY = $itemChek['return_qty'];
    }

    if($salesReturnData != null){
        if($itemChek != null){
            $totalReturnQTY = $totalReturnQTY;
        }else{
            $totalReturnQTY = 0;
        }

    }else{
        $totalReturnQTY = 0;
    }
    
    if($itemType == 'tab' || $itemType == 'cap'){
        $currentQty = ($item[0]['loosely_count'] - intval($totalReturnQTY)); 
    }
    else{ 
        $currentQty =  $item[0]['qty'] - intval($totalReturnQTY);  
    }

    echo $currentQty;
}

// get product discount
if (isset($_GET["disc"])) {
    $invoice = $_GET["disc"];
    $item = $StockOut->stockOutSelect($invoice, $_GET["p-id"]);
    echo $item[0]['disc'];
}

// get product gst percentage
if (isset($_GET["gst"])) {
    $invoice = $_GET["gst"];
    $item = $StockOut->stockOutSelect($invoice, $_GET["p-id"]);
    echo $item[0]['gst'];
}

// get product taxable amount
if (isset($_GET["taxable"])) {
    $invoice = $_GET["taxable"];
    $item = $StockOut->stockOutSelect($invoice, $_GET["p-id"]);
    echo $item[0]['taxable'];
}



// get product amount
if (isset($_GET["amount"])) {
    $invoice = $_GET["amount"];
    $item = $StockOut->stockOutSelect($invoice, $_GET["p-id"]);
    echo $item[0]['amount'];
}

