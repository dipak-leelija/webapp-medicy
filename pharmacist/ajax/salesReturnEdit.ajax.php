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

$tabel = 'id';
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

    // echo "$invoiceId<br>$salesRetundid";
    $table = 'sales_return_id';
    $items = $salesReturn->selectSalesReturnList($table, $salesRetundid);
    echo '<option value="" selected disabled>Select item</option>';
    foreach ($items as $item) {
        // print_r($items);
        $product = $Products->showProductsById($item['product_id']);
        // print_r($product); echo "<br><br>";
        echo '<option data-invoice="'.$invoiceId.'" sales-return-id="'.$item['sales_return_id'].'" value="'.$item['product_id'].'" returned-item-id="'.$item['id'].'" current-stock-item-id="'.$item['item_id'].'">'.$product[0]['name'].'</option>';
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
    $exp = $_GET["exp-date"];
    $item = $salesReturn->selectSalesReturnList($tabel, $exp);
    // print_r($item);
    echo $item[0]['exp'];
}


///////////////////////////// get product full unit////////////////////////
if (isset($_GET["unit"])) {
    $item = $salesReturn->selectSalesReturnList($tabel, $_GET["unit"]);
    echo $item[0]['weatage'];
}

// get product full unit
if (isset($_GET["unitType"])) {
    $item = $salesReturn->selectSalesReturnList($tabel, $_GET["unitType"]);
    $unit = $item[0]['weatage'];
    $unitType = preg_replace('/[0-9]/','', $unit);
    echo $unitType;   
}

// get product full unit
if (isset($_GET["itemWeatage"])) {
    $item = $salesReturn->selectSalesReturnList($tabel, $_GET["itemWeatage"]);
    $unit = $item[0]['weatage'];
    $itemWeatage = preg_replace('/[a-z]/','', $unit);
    echo $itemWeatage;
}//=======================================================================

//batch number
if (isset($_GET["batchNo"])) {
    $item = $salesReturn->selectSalesReturnList($tabel, $_GET["batchNo"]);
    $batch = $item[0]['batch_no'];
    echo $batch;
}

// get product mrp
if (isset($_GET["mrp"])) {
    $invoice = $_GET["mrp"];
    $item = $StockOut->stockOutSelect($invoice, $_GET["p-id"]);
    echo $item[0]['mrp'];
}


// get product purchase qty
if (isset($_GET["pqty"])) {
    $invoice = $_GET["pqty"];
    //$item = $StockOut->stockOutSelect($invoice, $_GET["p-id"], $_GET["batch"]);
    $item = $StockOut->stockOutSelect($invoice, $_GET["p-id"]);
    foreach($item as $item){
        if($item['loosely_count'] > 0){
            $purchaseQty = $item['loosely_count'];
        }else{
            $purchaseQty = $item['qty'];
        }
    }
    echo $purchaseQty;
}

// get product return qty
if (isset($_GET["rtnqty"])) {
    $invoice = $_GET["rtnqty"];
    $item = $salesReturn->selectSalesReturnList($tabel, $_GET["rtnqty"]);
    //print_r($item);
    echo $item[0]['return_qty'];
    // echo $invoice;
}

// get product discount
if (isset($_GET["disc"])) {
    $invoice = $_GET["disc"];
    $item = $salesReturn->selectSalesReturnList($tabel, $_GET["disc"]);
    echo $item[0]['disc'];
}

// get product gst percentage
if (isset($_GET["gst"])) {
    $invoice = $_GET["gst"];

    $item = $salesReturn->selectSalesReturnList($tabel, $_GET["gst"]);
    echo $item[0]['gst'];
}

// get product taxable
if (isset($_GET["taxable"])) {
    $invoice = $_GET["taxable"];
    $item = $salesReturn->selectSalesReturnList($tabel, $_GET["taxable"]);
    echo $item[0]['taxable'];
}

// get product amount
if (isset($_GET["amount"])) {
    $invoice = $_GET["amount"];
    $item = $salesReturn->selectSalesReturnList($tabel, $_GET["amount"]);
    echo $item[0]['refund_amount'];
}

?>


