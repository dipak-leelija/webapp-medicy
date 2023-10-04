<?php
require_once '../_config/sessionCheck.php';

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

    $added_by = $_SESSION['employee_username'];
    $SalesReturnId = $_POST['id'];
    $status = "0";

    $salesRetunTableData = $SalesReturn->salesReturnByID($SalesReturnId);
    $invoiceId = $salesRetunTableData[0]['invoice_id'];

    $updateStatus = $SalesReturn->updateStatus($SalesReturnId, $status, $added_by);
    $updateStatus = true;

    if ($updateStatus == true) {

        $attribute = "sales_return_id";
        $data = $SalesReturnId;

        //fetch data from sales return details table
        $selectReturnDetails = $SalesReturn->selectSalesReturnList($attribute, $data);

        foreach ($selectReturnDetails as $selectReturnDetails) {

            $salesReturnDetailsId = $selectReturnDetails['id'];
            $curretnStockItemId = $selectReturnDetails['item_id'];
            $productId = $selectReturnDetails['product_id'];;
            $batchNo = $selectReturnDetails['batch_no'];
            $returnsQty = $selectReturnDetails['return_qty'];

            $checkCurrentSotck = $currentStock->showCurrentStocById($curretnStockItemId);
            foreach ($checkCurrentSotck as $checkCurrentSotck) {
                $currentStockQty = $checkCurrentSotck['qty'];
                $currentStockLooselyCount =  $checkCurrentSotck['loosely_count'];
            }

            $table1 = 'sales_return_id';
            $table2 = 'item_id';
            $salesReturnDetailsData = $SalesReturn->seletReturnDetailsBy($table1, $SalesReturnId, $table2, $curretnStockItemId);
            foreach ($salesReturnDetailsData as $salesReturnDetailsData) {
                $itemSetOf = $salesReturnDetailsData['weatage'];
                $itemUnit = preg_replace("/[0-9]/", "", $itemSetOf);
                $itemWeatage = preg_replace("/[a-z]/", "", $itemSetOf);
                $returnsQty = $salesReturnDetailsData['return_qty'];

                if ($itemUnit == 'tab' || $itemUnit == 'cap') {
                    $looselyCount = $returnsQty;
                } else {
                    $wholeCount = $returnsQty;
                }

                if ($itemUnit == 'tab' || $itemUnit == 'cap') {
                    $updatedLooselyCount = intval($currentStockLooselyCount) - intval($looselyCount);
                    $updatedQty = intdiv($updatedLooselyCount, $itemWeatage);
                }else{
                    $updatedQty = intval($currentStockQty) - intval($wholeCount);
                    $updatedLooselyCount = 0;
                }
                

                $stockUpdate = $currentStock->updateCurrentStockById($curretnStockItemId, $updatedQty, $updatedLooselyCount);

                // sales return update. set return qty  0 and refund amount 0;
                $setReturnQty = 0;
                $setRefundAmount = 0;
                $updateSalesRetunDetails = $SalesReturn->updateSalesReturnOnReturnCancel($salesReturnDetailsId, $setReturnQty, $setRefundAmount);

                $deleteReturnDetails = $SalesReturn->deleteSalesReturnDetaislById($salesReturnDetailsId);
            }
        }
    }

    //==========================================
    if ($stockUpdate == true) {
        echo 1;
    }else{
        echo 0;
    }
}
