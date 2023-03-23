<?php
require_once '../../php_control/salesReturn.class.php';
require_once '../../php_control/patients.class.php';
require_once '../../php_control/products.class.php';
require_once '../../php_control/stockOut.class.php';
require_once '../../php_control/currentStock.class.php';


// classes initiating 
$SalesReturn    = new SalesReturn();
$Patients       = new Patients();
$products       = new Products();
$stockOut       = new StockOut();
$currentStock   = new CurrentStock();

if (isset($_POST['id'])) {

    
$SalesReturnId = $_POST['id'];
$status = "CANCEL";
echo "$SalesReturnId<br>";
$updateStatus = $SalesReturn->updateStatus($SalesReturnId, $status);

    if($updateStatus == true){
        $attribute = "sales_return_id";
        $data = $salesReturnId;

        //fetch data from sales return details table
        
        $selectReturnDetails = $SalesReturn->selectSalesReturnList($attribute, $data); 
    
        for($i=0; $i<count($selectReturnDetails); $i++){

            $invoice = $selectReturnDetails[$i]['invoice_id'];
            $productId = $selectReturnDetails[$i]['product_id'];;
            $batchNo = $selectReturnDetails[$i]['batch_no'];
            $returnsQty = $selectReturnDetails[$i]['return'];
           
            $checkCurrentSotck = $currentStock->checkStock($productId, $batchNo);

            $currentStockQty = $checkCurrentSotck[0]['qty'];
            $currentStockLooselyCount =  $checkCurrentSotck[0]['loosely_count'];
        
            $selectStockOutDetials = $stockOut->stockOutDetailsSelect($invoice, $productId, $batchNo);

            if($selectStockOutDetials[0]['unit'] == 'tab' || $selectStockOutDetials[0]['unit'] == 'cap' ){
                if($selectStockOutDetials[0]['qty'] == '0'){
                    $looselyCount = $returnsQty;
                    $wholeCount = $looselyCount % $selectStockOutDetials[0]['weightage'];                
                }else{
                    $wholeCount = $returnsQty;
                    $looselyCount = $returnsQty * $selectStockOutDetials[0]['weightage'];                
                }
            }else{
                $wholeCount = $returnsQty;
                $looselyCount = '0';
            }

            echo "$SalesReturnId<br>";
            echo "$returnsQty<br>";
            echo "$productId<br>$batchNo<br>$wholeCount<br>$looselyCount";

            $wholeCount = $currentStockQty - $wholeCount;
            $looselyCount = $currentStockLooselyCount - $looselyCount;

            echo "$productId<br>$batchNo<br>$wholeCount<br>$looselyCount";
            $stockUpdate = $currentStock->updateStock($productId, $batchNo, $wholeCount, $looselyCount);
            echo "<br>"; var_dump($stockUpdate);
        }
    }

//==========================================

if($stockUpdate == true){

    echo 1;
}

}



    
