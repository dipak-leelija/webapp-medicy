<?php
require_once dirname(dirname(__DIR__)) . '/config/constant.php';
require_once dirname(dirname(__DIR__)) . '/config/service.const.php';
require_once ROOT_DIR . '_config/sessionCheck.php'; //check admin loggedin or not

require_once CLASS_DIR . 'dbconnect.php';
require_once CLASS_DIR . 'encrypt.inc.php';
require_once CLASS_DIR . 'stockIn.class.php';
require_once CLASS_DIR . 'stockInDetails.class.php';
require_once CLASS_DIR . 'currentStock.class.php';
require_once CLASS_DIR . 'distributor.class.php';
require_once CLASS_DIR . 'products.class.php';
require_once CLASS_DIR . 'manufacturer.class.php';
require_once CLASS_DIR . 'packagingUnit.class.php';
require_once CLASS_DIR . 'stockOut.class.php';
require_once CLASS_DIR . 'stockReturn.class.php';
require_once CLASS_DIR . 'salesReturn.class.php';
require_once CLASS_DIR . 'hospital.class.php';


$StockIn        = new StockIn();
$StockInDetails = new StockInDetails();
$CurrentStock   = new CurrentStock();
$distributor    = new Distributor();
$Session        = new SessionHandler();
$Products       = new Products();
$Manufacturer   = new Manufacturer();
$PackagingUnits = new PackagingUnits();
$StockOut       = new StockOut();
$StcokReturn    = new StockReturn();
$SalesReturn    = new SalesReturn();
$ClinicInfo     = new HealthCare;


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['update'])) {

        $stockIn_Id         = $_POST['stok-in-id'];
        $prevDistId         = $_POST['prev-distributor-id'];
        $distributorId      = $_POST['updated-distributor-id'];

        $distributorDetial = json_decode($distributor->showDistributorById($distributorId));
        $distributorDetial = $distributorDetial->data;
        // print_r($distributorDetial);
        $distributorName      = $distributorDetial->name;
        $distAddress          = $distributorDetial->address;
        $distPIN              = $distributorDetial->area_pin_code;
        $distContact          = $distributorDetial->phno;


        $distPrevBillNo     = $_POST['prev-distributor-bill'];
        $distributorBill    = $_POST['distributor-bill'];

        $Items              = $_POST['items'];
        $items              = count($_POST['productId']);
        $totalQty           = $_POST['total-qty'];
        $billDate           = date_create($_POST['bill-date-val']);
        $billDate           = date_format($billDate, "d-m-Y");
        $dueDate            = date_create($_POST['due-date-val']);
        $dueDate            = date_format($dueDate, "d-m-Y");
        $paymentMode        = $_POST['payment-mode-val'];
        $pMode              = $paymentMode;
        $totalGst           = $_POST['totalGst'];
        $amount             = $_POST['netAmount'];
        $addedBy            = $employeeId;



        $BatchNo            = $_POST['batchNo'];
        $purchaseId         = $_POST['purchaseId'];
        // $mfdDate            = $_POST['mfdDate'];
        $expDate            = $_POST['expDate'];

        $crrntDt = date("d-m-Y");

        // =========== array data ===============
        $product_ids            = $_POST['productId'];
        $batch_no               = $_POST['batchNo'];
        // $mfd_date = $_POST['mfdDate'];
        $exp_date               = $_POST['expDate'];
        $set_of                 = $_POST['setof'];
        $item_weightage         = $_POST['weightage'];
        $item_unit              = $_POST['unit'];
        $item_qty               = $_POST['qty'];
        $item_free_qty          = $_POST['freeQty'];
        $item_mrp               = $_POST['mrp'];
        $item_ptr               = $_POST['ptr'];
        $item_gst               = $_POST['gst'];
        $gstAmount_perItem      = $_POST['gstPerItem'];
        $baseAmount_perItem     = $_POST['base'];
        $discountPercent        = $_POST['discount'];
        $marginAmount_perItem   = $_POST['margin'];
        $billAmount_perItem     = $_POST['billAmount'];

        // print_r($item_qty);
        // echo "<br>"; print_r($item_weightage);

        // ==== check data =====
        $stockInAttrib = 'id';
        $seleteStockinData = $StockIn->stockInByAttributeByTable($stockInAttrib, $stockIn_Id);
        // print_r($seleteStockinData);
        if ($seleteStockinData[0]['distributor_bill'] != $distributorBill) {
            foreach ($seleteStockinData as $seleteStockinData) {
                $table1 = 'distributor_bill';
                $table2 = 'id';
                $updateBillNumber = $StockInDetails->updateStockInDetailsByTableData($table1, $table2, $distributorBill, $seleteStockinData['id']);
            }
        }


        //========== updating stock in table data ===============
        $updateStockIn = $StockIn->updateStockIn($stockIn_Id, $distributorId, $distributorBill, $items, $totalQty, $billDate, $dueDate, $paymentMode, $totalGst, $amount, $employeeId, NOW);


        /* updated iitem id array */
        $updatedItemIdsArray = $_POST['purchaseId'];

        /* previous added items details array */
        $PrevStockInDetailsCheck = $StockInDetails->showStockInDetailsByStokId($stockIn_Id);

        /* storing ids of previous added items in a empty array */
        $prevStokInItemIdArray = [];
        foreach ($PrevStockInDetailsCheck as $StokInids) {
            array_push($prevStokInItemIdArray, $StokInids['id']);
        }

        /* checking difference between two array to point deleted items */
        $ItemArrayIdsDiff = array_diff($prevStokInItemIdArray, $updatedItemIdsArray);


        if ($ItemArrayIdsDiff != '') {
            $ItemNotDeleteCount = 0;
            $WholeNotDeletedQty = 0;
            $WholeNotDeletedGstAmount = 0;
            $WholeNotDeletedPrice = 0;
            foreach ($ItemArrayIdsDiff as $deleteItemId) {

                // === **** $deleteItemId => StockInDetailsItemId **** === 
                $currentStockData = json_decode($CurrentStock->showCurrentStocByStokInDetialsId($deleteItemId));
                // print_r($currentStockData);
                $currentStockItemId = $currentStockData->id;

                // 1. first check stock out data, if stock out != item id, delete data else show alert massage
                $table = 'item_id';
                $stockOutDataCheck = $StockOut->stokOutDetailsDataOnTable($table, $currentStockItemId);

                if ($stockOutDataCheck != null) {
                    $ItemNotDeleteCount = intval($ItemNotDeleteCount) + 1;
                    $stockInDetailsData = $StockInDetails->showStockInDetailsByStokinId($deleteItemId);

                    foreach ($stockInDetailsData as $itemDetails) {
                        $itemTotalQty = intval($itemDetails['qty']) + intval($itemDetails['free_qty']);
                        $itemGstAmount = $itemDetails['gst_amount'];
                        $itemPrice = $itemDetails['amount'];
                    }

                    $WholeNotDeletedQty = intval($WholeNotDeletedQty) + intval($itemTotalQty);
                    $WholeNotDeletedGstAmount = floatval($WholeNotDeletedGstAmount) + floatval($itemGstAmount);
                    $WholeNotDeletedPrice = floatval($WholeNotDeletedPrice) + floatval($itemPrice);

                    echo '<script>';
                    echo 'Swal.fire({
                                    title: "Warning!",
                                    text: "Some item / items cannot be deleted as it was sold.",
                                    icon: "warning",
                                    });';
                    echo '</script>';
                } else {

                    /// deleting from current stock \\\\==============
                    $CurrentStockTable = 'stock_in_details_id';
                    $deleteFromCurrentStock = $CurrentStock->deleteByTabelData($CurrentStockTable, $deleteItemId);

                    // delete from stock in details===================
                    $deleteStockInDetails = $StockInDetails->stockInDeletebyDetailsId($deleteItemId);
                }
            }


            // update stock in data according item details.
            $stockInAttribute = 'id';
            $seleteStockinData = $StockIn->stockInByAttributeByTable($stockInAttribute, $stockIn_Id);
            foreach ($seleteStockinData as $stockInData) {
                $itemsCount     = $stockInData['items'];
                $totalQty       = $stockInData['total_qty'];
                $gstAmount      = $stockInData['gst'];
                $wholeAmount    = $stockInData['amount'];
            }

            $updatedItemsCount  = intval($itemsCount) + intval($ItemNotDeleteCount);
            $updatedTotalQty    = intval($totalQty) + intval($WholeNotDeletedQty);
            $updatedGstAmt      = floatval($gstAmount) + floatval($WholeNotDeletedGstAmount);
            $updatedAmt         = floatval($wholeAmount) + floatval($WholeNotDeletedPrice);


            /* update stock in data */
            $updateStockIn = $StockIn->updateStockIn($stockIn_Id, $distributorId, $distributorBill, $updatedItemsCount, $updatedTotalQty, $billDate, $dueDate, $paymentMode, $updatedGstAmt, $updatedAmt, $employeeId, NOW);
            ///////////////////////// check this area again \\\\\\\\\\\\\\\\\\\\\\\\\\\\
        }



        // =========== add of updated stock in details and current stock data ==============
        $count = count($updatedItemIdsArray);
        for ($i = 0; $i < count($updatedItemIdsArray); $i++) {
            if ($updatedItemIdsArray[$i] == '') {


                $item_total_qty = intval($item_qty[$i]) + intval($item_free_qty[$i]);
                // echo "<br>item total qty check : $item_total_qty";

                if (in_array(strtolower(trim($item_unit[$i])), LOOSEUNITS)) {
                    $item_loose_qty = intval($item_total_qty) * intval($item_weightage[$i]);
                    $item_loose_price = floatval($item_mrp[$i]) / intval($item_weightage[$i]);
                } else {
                    $item_loose_qty = 0;
                    $item_loose_price = 0;
                }

                // echo $item_loose_qty;

                /* add new data to Stock in Details */
                $addToStockInDetails = $StockInDetails->addStockInDetails($stockIn_Id, $product_ids[$i], $distributorBill, $batch_no[$i], $exp_date[$i], $item_weightage[$i], trim($item_unit[$i]), $item_qty[$i], $item_free_qty[$i], $item_loose_qty, $item_mrp[$i], $item_ptr[$i], $discountPercent[$i], $baseAmount_perItem[$i], $item_gst[$i], $gstAmount_perItem[$i], $marginAmount_perItem[$i], $billAmount_perItem[$i], $employeeId, NOW, $adminId);

                $stockInDetailsId = $addToStockInDetails['stockIn_Details_id'];

                /* add new data to current stock */
                $addToCurrentStock = $CurrentStock->addCurrentStock($stockInDetailsId, $product_ids[$i], $batch_no[$i], $exp_date[$i], $distributorId, $item_loose_qty, $item_loose_price, $item_weightage[$i], trim($item_unit[$i]), $item_total_qty, $item_mrp[$i], $item_ptr[$i], $item_gst[$i], $addedBy, NOW, $adminId);
            } else {

                /* update old item data */

                // check data difference by id;
                $stockInDetailsById = $StockInDetails->showStockInDetailsByStokinId($updatedItemIdsArray[$i]);
                foreach ($stockInDetailsById as $stockInDetaislData) {
                    $prevStockInItemQty = intval($stockInDetaislData['qty']) + intval($stockInDetaislData['free_qty']);
                }

                $itemQty = $item_qty[$i];
                $itemFreeQty = $item_free_qty[$i];
                $updatedQty = (intval($itemQty) + intval($itemFreeQty)) - intval($prevStockInItemQty);

                if (in_array(strtolower(trim($item_unit[$i])), LOOSEUNITS)) {
                    $updatedStockInLooseQty = intval($updatedQty) * intval($item_weightage[$i]);
                } else {
                    $updatedStockInLooseQty = 0;
                }

                // update to current stock data
                $currentStockItmeDetails = json_decode($CurrentStock->showCurrentStocByStokInDetialsId($updatedItemIdsArray[$i]));

                if ($currentStockItmeDetails != null) {
                    $itemId = $currentStockItmeDetails->id;
                    $Loose_Qty = intval($currentStockItmeDetails->loosely_count);
                    $item_Qty = intval($currentStockItmeDetails->qty);
                }


                if (in_array(strtolower(trim($item_unit[$i])), LOOSEUNITS)) {
                    $updated_Loose_Qty = intval($Loose_Qty) + intval($updatedStockInLooseQty);
                    $updated_item_qty = intdiv($updated_Loose_Qty, $item_weightage[$i]);
                } else {
                    $updated_Loose_Qty = 0;
                    $updated_item_qty = intval($item_Qty) + intval($updatedQty);
                }


                /* update to current stock */
                $updateCurrentStockItemData = $CurrentStock->updateCurrentStockByStockInId($updatedItemIdsArray[$i], $product_ids[$i], $batch_no[$i], $exp_date[$i], $distributorId, $updated_Loose_Qty, $updated_item_qty, $item_ptr[$i], $addedBy);

                if (in_array(strtolower(trim($item_unit[$i])), LOOSEUNITS)) {
                    $stockInLooseCount = (intval($item_qty[$i]) + intval($item_free_qty[$i])) * intval($item_weightage[$i]);
                } else {
                    $stockInLooseCount = 0;
                }

                // ======= need to check this data ============

                //===========

                $updatedStockInDetails = $StockInDetails->updateStockInDetailsById(intval($updatedItemIdsArray[$i]), $product_ids[$i], $distributorBill, $batch_no[$i], $exp_date[$i], intval($item_weightage[$i]), trim($item_unit[$i]), intval($item_qty[$i]), intval($item_free_qty[$i]), intval($stockInLooseCount), floatval($item_mrp[$i]), floatval($item_ptr[$i]), intval($discountPercent[$i]), floatval($baseAmount_perItem[$i]), intval($item_gst[$i]), floatval($gstAmount_perItem[$i]), floatval($marginAmount_perItem[$i]), floatval($billAmount_perItem[$i]), $addedBy, NOW);


                /* multiple table update area as bellow data are contain multiple row of same item ids. */

                /* UPDATE STOCK_OUT_DETAILS TABLE AREA */
                $stockOutDetaislTable = 'item_id';
                $checkStocOutDetails = $StockOut->stokOutDetailsDataOnTable($stockOutDetaislTable, $itemId);
                // update on stock out details table
                if (!empty($checkStocOutDetails)) {
                    for ($j = 0; $j < count($checkStocOutDetails); $j++) {
                        $updateStockOutDetailslData = $StockOut->updateStockOutDetaisOnStockInEdit($itemId, $batch_no[$i], $exp_date[$i], $employeeId, NOW);
                    }
                } // END OF STOCK OUT DETAILS UPDATE


                /* UPDDATE SALES_RETURN_DETAILS */ // check this qarry
                $salesReturnDetaislTable = 'item_id';
                $salesReturnDetailsData = $SalesReturn->selectSalesReturnList($salesReturnDetaislTable, $itemId);

                // 1st. check sales return details table have current access data or not
                if (!empty($salesReturnDetailsData)) {
                    for ($l = 0; $l < count($salesReturnDetailsData); $l++) {
                        $salesReturnDetailsUpdate = $SalesReturn->updateSalesReturnOnStockInUpdate(intval($itemId), $batch_no[$i], $exp_date[$i], $addedBy, NOW);
                    }
                }


                /* update on stock return details tabel */ //( check this qarry )
                // 1st. check stock return table have current access data or not
                $table1 = 'stockin_id';
                $data1 = $stockIn_Id;
                // updated table where dist id = $prevDistId, and dist bill number =  $distPrevBillNo;
                $updateStockReturn = $StcokReturn->updateStockReturnOnEditStockIn($table1, $data1, $distributorId, $distributorBill, $addedBy);

                $selectStockReturnData = json_decode($StcokReturn->stockReturnFilter($table1, $data1));
                $selectStockReturnData = $selectStockReturnData->data;

                if (!empty($selectStockReturnData)) {
                    $stockReturnId = $selectStockReturnData[0]->id;
                }

                // update stock return details table
                $stockReturnDetailsData = $StcokReturn->showStockReturnDataByStokinIdasArray($updatedItemIdsArray[$i]);

                if (!empty($stockReturnDetailsData)) {
                    for ($m = 0; $m < count($stockReturnDetailsData); $m++) {
                        // update stock return details by $stockReturnTabelData[0]['id'] and $itemId,
                        $updateStockReturn = $StcokReturn->stockReturnDetailsEditByStockInDetailsId($updatedItemIdsArray[$i], $product_ids[$i], $batch_no[$i], $exp_date[$i], $item_weightage[$i] . trim($item_unit[$i]), $item_qty[$i], $item_free_qty[$i], $item_mrp[$i], $item_ptr[$i], $discountPercent[$i], $item_gst[$i], $addedBy);
                    }
                }
            }
        }
    }
}


$preparedData = url_enc(json_encode(['stockIn_Id' => $stockIn_Id]));
header('Location: purchase-invoice.php?data='.$preparedData);
exit;

?>