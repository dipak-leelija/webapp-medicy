<?php

ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);
require_once '../sessionCheck.php'; //check admin loggedin or not


require_once '../../../php_control/hospital.class.php';
require_once '../../../php_control/stockReturn.class.php';
require_once '../../../php_control/idsgeneration.class.php';
require_once '../../../php_control/currentStock.class.php';
require_once '../../../php_control/stockInDetails.class.php';

//  INSTANTIATING CLASS
$HelthCare       = new HelthCare();
$StockReturn     = new StockReturn();
$IdGeneration    = new IdGeneration();
$CurrentStock    = new CurrentStock();
$StokInDetails   = new StockInDetails();

if (isset($_POST['stock-return-edit'])) {


    //+++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
    $distributorId          = $_POST['dist-id'];
    $distributorName        = $_POST['dist-name'];
    $returnDate             = $_POST['return-date'];
    $returnDate             = date("Y-m-d", strtotime($returnDate)); //
    $refundMode             = $_POST['refund-mode']; //
    $itemQty                = $_POST['items-qty'];
    $totalRefundItemsQty         = $_POST['total-refund-qty']; // FREE QUANTITY + RETURN QANTITY
    $returnGst              = $_POST['return-gst']; // TOTAL RETURN GST
    $refund                 = $_POST['NetRefund'];
    $addedBy                = $_SESSION['employee_username'];
    $stockReturnId          = $_POST['stock-returned-id'];

    // ========================== array data =====================================

    if (isset($_POST['stock-return-details-item-id']) != null) {
        $UpdatedStokReturnDetailsItemId = $_POST['stock-return-details-item-id'];
        $stockReturnDetailsItemsId   = $_POST['stock-return-details-item-id'];
        $productId              = $_POST['productId'];
        $ids                    = count($productId); // NEEDED to count of total returned product
        // stock return id
        echo "<br>ITEM COUNT FROM STOCK RETURN ARRAY : ";
        print_r($ids);
        echo "<br><br>";

    } else{
        $UpdatedStokReturnDetailsItemId = null;
        $ids = 0;
    }

    print_r($UpdatedStokReturnDetailsItemId);
    echo "=> Updated Stock return detial item id array <br><br>";

    // ========================== end of array data ==============================

    echo "<br>Stock return Id : $stockReturnId<br>";
    echo "<br>Distributor Id : $distributorId";
    echo "<br>Distributor Name : $distributorName";
    echo "<br>Return Date : $returnDate";
    echo "<br>Refund Mode : $refundMode";
    echo "<br>Item qty : $itemQty";
    echo "<br>Total return Item Qty (FREE + PAID) : $totalRefundItemsQty";
    echo "<br>Return Gst : $returnGst";
    echo "<br>NET Refund Amount: $refund";
    echo "<br>Added By : $addedBy";

    $addedTime = date("h:i:sa");
    $todayDate = date("Y/m/d");

    // ================ STOCK RETURN DATA UPDATE  BLOCK ==================
    $stockReturnEditUpdate = $StockReturn->stockReturnEditUpdate($stockReturnId, $distributorId, $returnDate, $itemQty, $totalRefundItemsQty, $returnGst, $refundMode, $refund, $addedBy, $todayDate, $addedTime);
    // ================ END OF STOCK RETURN DATA UPDATE  BLOCK ==================

    $PrvRtrnItemIdArray = [];
    $PrvRtrnItemStockInIdArray = [];
    $stockReturnDataFetch = $StockReturn->showStockReturnDetails($stockReturnId);
    // print_r($stockReturnDataFetch); echo "<br><br>";
    foreach ($stockReturnDataFetch as $returnData) {
        $ItemId = $returnData['id'];
        $StokInDetialsId = $returnData['stokIn_details_id'];
        array_push($PrvRtrnItemIdArray, $ItemId);
        // array_push($PrvRtrnItemStockInIdArray, $StokInDetialsId);
    }

    print_r($PrvRtrnItemIdArray);
    echo "=> previous return item id array <br><br>";
    // ==================== arrays =======================
    
    
    if ($UpdatedStokReturnDetailsItemId != null) {
        $updatedDetailIdDiff = array_diff($PrvRtrnItemIdArray, $UpdatedStokReturnDetailsItemId);
    } else {
        $updatedDetailIdDiff = $PrvRtrnItemIdArray;
    }
    print_r($updatedDetailIdDiff); // use this data to delete from return details and update current stock
    echo "=> difference between updated array and previous array<br><br>";
    echo "<br>============= CHECK DIFF END  ============<br>";

    //================== deleted data add to current stock block =====================
    echo "<br> ===================== DELETE DATA ACTION AREA ======================<br>";
    foreach($updatedDetailIdDiff as $deleteItemId){

        $prevStokReturnDetailsData = $StockReturn->showStockReturnDetailsById($deleteItemId);
        echo "<br> Stock Return Item Details : ";
        print_r($prevStokReturnDetailsData);
        echo "<br>";

        foreach($prevStokReturnDetailsData as $returnData){
            $StokInDetailsId = $returnData['stokIn_details_id'];
            $returnQty = $returnData['return_qty'];
            $returnFQty = $returnData['return_free_qty'];
            $totalReturnQTY = intval($returnQty) + intval($returnFQty);

            $stockInDetailsData = $StokInDetails->showStockInDetailsByStokinId($StokInDetailsId);
            echo "<br> Stock In Item Details : ";
            print_r($stockInDetailsData);
            echo "<br>";
            foreach($stockInDetailsData as $stokInItemDetails){
                $itemUnit = $stokInItemDetails['unit'];
                echo "Unit : $itemUnit<br>";
                $itemWeightage = $stokInItemDetails['weightage'];

                if($itemUnit == 'tab' || $itemUnit == 'cap'){
                    $LooseQty = intval($totalReturnQTY) * intval($itemWeightage);
                }else{
                    $LooseQty = 0;
                }
                echo "Loose Quantity : $LooseQty<br>";
            }

            $currenStockData = $CurrentStock->showCurrentStocByStokInDetialsId($StokInDetailsId);
            echo "<br> Current Stock data : ";
            print_r($currenStockData);
            echo "<br>";
            foreach($currenStockData as $currenStockData){
                $CurrentItemQTY = $currenStockData['qty'];
                $CurrentLooselyCount = $currenStockData['loosely_count'];

                $UpdatedQty = intval($CurrentItemQTY) + intval($totalReturnQTY);
                $UpdatedLooseQty = intval($CurrentLooselyCount) + intval($LooseQty);

                echo "Updated Loose Quantity : $UpdatedLooseQty<br>";
                echo "Updated Quantity : $UpdatedQty<br>";

                $updateCurrentStock = $CurrentStock->updateStockBStockDetialsId($StokInDetailsId, $UpdatedQty, $UpdatedLooseQty);

                $deleteStockReturnDetails = $StockReturn->delteStockReturnDetailsbyItemId($deleteItemId);
            }
        }
    }
    echo "<br> ===================== DELETE DATA ACTION AREA END ======================<br>";
    // =============================== eof diff data block ===================================


    // ============================== update data block start ============================
    if ($UpdatedStokReturnDetailsItemId != null) {
        foreach($UpdatedStokReturnDetailsItemId as $itemId){
            $stockReturnDetailsItemsId = $itemId;
            $productId = array_shift($_POST['productId']);
            $productName = array_shift($_POST['productName']);
            $batchNo = array_shift($_POST['batchNo']);
            $expDate = array_shift($_POST['expDate']);
            $setof = array_shift($_POST['setof']);
            $purchasedQty = array_shift($_POST['purchasedQty']);
            $freeQty = array_shift($_POST['freeQty']);
            $mrp = array_shift($_POST['mrp']);
            $ptr = array_shift($_POST['ptr']);
            $purchaseAmount = array_shift($_POST['purchase-amount']);
            $perItemGstAmount = array_shift($_POST['gstAmount']);
            $PerItemsGstPercentage = array_shift($_POST['gst']);
            $returnQTY = array_shift($_POST['return-qty']);
            $returnFQty = array_shift($_POST['return-free-qty']);
            $PerItemsRefundAmount = array_shift($_POST['refund-amount']);  // Per items REFUND AMOUNT

            echo "<br>STOK RETURN DETAILS ID : $stockReturnDetailsItemsId";
            echo "<br>Product Id : $productId";
            echo "<br>Product Name : $productName";
            // echo "<br>STOK RETURN ID : "; print_r($stockReturnId);
            echo "<br>Batch No : $batchNo";
            echo "<br>Exp Date : $expDate";
            echo "<br>Pack of : $setof";
            echo "<br>Purchase QTY : $purchasedQty";
            echo "<br>Free qty (purchase time) : $freeQty";
            echo "<br>MRP : $mrp";
            echo "<br>PTR : $ptr";
            echo "<br>Purchase Amount : $purchaseAmount";
            echo "<br>GST Amount per Items : $perItemGstAmount";
            echo "<br>GST Percent per Items : $PerItemsGstPercentage";
            echo "<br>RETURN QUANTITY per Items : $returnQTY";
            echo "<br>RETURN FREE QUANTITY per Items : $returnFQty";
            echo "<br>Per item Refund Amount : $PerItemsRefundAmount<br>";

            $stockReturnEditUpdate = true;

            if ($stockReturnEditUpdate === true) {

                $prevStokReturnDetailsData = $StockReturn->showStockReturnDetailsById($stockReturnDetailsItemsId);
                echo "<br>Previous Stock return details : ";
                print_r($prevStokReturnDetailsData);
                foreach ($prevStokReturnDetailsData as $prevStockReturnData) {
                    $prevReturnQty = $prevStockReturnData['return_qty'];
                    echo "<br><br>Prev return qty : $prevReturnQty";
                    $prevReturnFQty = $prevStockReturnData['return_free_qty'];
                    echo "<br>Prev return F qty : $prevReturnFQty";
                    $stockInDetailsId = $prevStockReturnData['stokIn_details_id'];
                    echo "<br>Prev stokIN details Id : $stockInDetailsId";
                    $totalPrevReturn = intval($prevReturnQty) + intval($prevReturnFQty);
                    echo "<br>Total Prev Return qty : $totalPrevReturn";
                }

                echo "<br>$setof<br>";
                $unit = preg_replace("/[^a-z]+/", "", $setof);
                echo "<br>unit : $unit<br>";
                $weatage = preg_replace("/[^0-9]+/", "", $setof);
                echo "<br>weatage : $weatage<br>";

                if ($unit == 'tab' || $unit == 'cap') {
                    $prevReturnLooselyCount = intval($totalPrevReturn) * intval($weatage);
                    $currentReturnLooselyCount = ((intval($returnQTY) + intval($returnFQty)) * intval($weatage));
                } else {
                    $prevReturnLooselyCount = 0;
                    $currentReturnLooselyCount = 0;
                }

                echo "<br> Previous return loosely count = $prevReturnLooselyCount<br>";
                echo "<br> Current return loosely count = $currentReturnLooselyCount<br>";
                $LooseReturnDiff = ($prevReturnLooselyCount - $currentReturnLooselyCount);
                $qtyReturnDiff = (intval($prevReturnQty) + intval($prevReturnFQty)) - (intval($returnQTY)+intval($returnFQty));
                echo "<br> loosely count return diff = $LooseReturnDiff<br>";
                echo "<br> whole count return diff = $qtyReturnDiff<br>";

                //===================== need to check from this area ===============================

                $CurrentStockData = $CurrentStock->showCurrentStocByStokInDetialsId($stockInDetailsId); //
                // echo "<br>Current stok data on stock in detials id : "; print_r($CurrentStockData);
                if ($CurrentStockData != null) {
                    foreach ($CurrentStockData as $currentStock) {
                        $CurrentStockLooselyCount  = $currentStock['loosely_count'];
                        echo "<br>Current Stock loosely count : $CurrentStockLooselyCount";
                        $CurrentStockQTY           = $currentStock['qty'];
                        echo "<br>Current qty : $CurrentStockQTY";
                    }

                    $updatedQty = intval($CurrentStockQTY) + intval($qtyReturnDiff); //edit update total quantity of product   
                    $newLooselyCount = intval($CurrentStockLooselyCount) + intval($LooseReturnDiff);  
                } else {
                    $updatedQty = '';
                    $newLooselyCount = '';
                }
                echo "<br><br>Updated qty : $updatedQty";
                echo "<br>Updated loose qty : $newLooselyCount";
                echo "<br>+++++++++++++++===============+++++++++++++=<br>";

                $CurrentStockUpdate = $CurrentStock->updateStockByReturnEdit($stockInDetailsId, $updatedQty, $newLooselyCount);  //updating current stock after edit purchase return

                $stockReturnDetailsEdit = $StockReturn->stockReturnDetailsEditUpdate($stockReturnDetailsItemsId, $returnQTY, $returnFQty, $PerItemsRefundAmount, $addedBy, $returnDate, $addedTime);  //updating stock return details table

                #################### data fetching and updating end ########################

            }
        }
    }
}

$showhelthCare = $HelthCare->showhelthCare();
foreach ($showhelthCare as $rowhelthCare) {
    $healthCareName     = $rowhelthCare['hospital_name'];
    $healthCareAddress1 = $rowhelthCare['address_1'];
    $healthCareAddress2 = $rowhelthCare['address_2'];
    $healthCareCity     = $rowhelthCare['city'];
    $healthCarePIN      = $rowhelthCare['pin'];
    $healthCarePhno     = $rowhelthCare['hospital_phno'];
    $healthCareApntbkNo = $rowhelthCare['appointment_help_line'];
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Stock Return</title>
    <link rel="stylesheet" href="../../../css/bootstrap 5/bootstrap.css">
    <link rel="stylesheet" href="../../../css/custom/test-bill.css">

</head>


<body>
    <div class="custom-container">
        <div class="custom-body <?php if ($refundMode != 'Credit') {
                                    echo "paid-bg";
                                } ?>">
            <div class="card-body ">
                <div class="row">
                    <div class="col-sm-1">
                        <img class="float-end" style="height: 55px; width: 58px;" src="../../../images/logo-p.jpg" alt="Medicy">
                    </div>
                    <div class="col-sm-8">
                        <h4 class="text-start my-0"><?php echo $healthCareName; ?></h4>
                        <p class="text-start" style="margin-top: -5px; margin-bottom: 0px;">
                            <small><?php echo $healthCareAddress1 . ', ' . $healthCareAddress2 . ', ' . $healthCareCity . ', ' . $healthCarePIN; ?></small>
                        </p>
                        <p class="text-start" style="margin-top: -8px; margin-bottom: 0px;">
                            <small><?php echo 'M: ' . $healthCarePhno . ', ' . $healthCareApntbkNo; ?></small>
                        </p>

                    </div>
                    <div class="col-sm-3 border-start border-dark">
                        <p class="my-0"><b>Return Bill</b></p>
                        <p style="margin-top: -5px; margin-bottom: 0px;"><small>Bill id:
                                #<?php echo $stockReturnId; ?></small></p>
                        <p style="margin-top: -5px; margin-bottom: 0px;"><small>Refund Mode:
                                <?php echo $refundMode; ?></small>
                        </p>
                        <p style="margin-top: -5px; margin-bottom: 0px;"><small>Return Date: <?php echo $returnDate; ?></small>
                        </p>
                    </div>
                </div>
            </div>
            <hr class="my-0" style="height:1px; background: #000000; border: #000000;">
            <div class="row my-0">
                <div class="col-sm-6 my-0">
                    <p><small><b>Patient: </b>
                            <?php echo $distributorName; ?></small></p>
                    <!-- <p style="margin-top: -5px; margin-bottom: 0px;"><small>M:
                            <?php //echo 7699753019; echo ', Test date: 241544';
                            ?></small></p> -->
                </div>
                <div class="col-sm-6 my-0 text-end">
                    <p style="margin-top: -3px; margin-bottom: 0px;"><small><b>Bill Date: </b>
                            <?php echo $returnDate; ?></small></p>
                </div>

            </div>
            <hr class="my-0" style="height:1px;">

            <div class="row">
                <!-- table heading -->

                <div class="col-sm-2 ">
                    <small><b>Name</b></small>
                </div>
                <div class="col-sm-1">
                    <small><b>Batch</b></small>
                </div>
                <div class="col-sm-1">
                    <small><b>Packing</b></small>
                </div>
                <div class="col-sm-1">
                    <small><b>Exp.</b></small>
                </div>
                <div class="col-sm-1">
                    <small><b>P.Qty</b></small>
                </div>
                <div class="col-sm-1 text-end">
                    <small><b>Free</b></small>
                </div>
                <div class="col-sm-1 text-end">
                    <small><b>PTR</b></small>
                </div>
                <div class="col-sm-1 text-end">
                    <small><b>MRP</b></small>
                </div>
                <div class="col-sm-1 text-end">
                    <small><b>P.Amount</b></small>
                </div>
                <div class="col-sm-1">
                    <small><b>Return</b></small>
                </div>
                <div class="col-sm-1 text-end">
                    <small><b>Refund</b></small>
                </div>
                <!--/end table heading -->
            </div>

            <hr class="my-0" style="height:1px;">

            <div class="row">
                <?php

                for ($i = 0; $i < $ids; $i++) {
                    $slno = $i + 1;

                    if ($slno > 1) {
                        echo '<hr style="width: 98%; border-top: 1px dashed #8c8b8b; margin: 0 10px 0; align-items: center;">';
                    }


                    // 
                    // 
                    // $returnQty[$i]
                    // $gst[$i]

                    echo '
                    <div class="col-sm-2 ">
                        <small>' . substr($productName[$i], 0, 15) . '</small>
                    </div>
                    <div class="col-sm-1">
                        <small>' . strtoupper($batchNo[$i]) . '</small>
                    </div>
                    <div class="col-sm-1">
                        <small>' . $setof[$i] . '</small>
                    </div>
                    <div class="col-sm-1">
                        <small>' . $expDate[$i] . '</small>
                    </div>
                    <div class="col-sm-1">
                        <small>' . $purchasedQty[$i] . '</small>
                    </div>
                    <div class="col-sm-1 text-end">
                        <small>' . $freeQty[$i] . '</small>
                    </div>
                    <div class="col-sm-1 text-end">
                        <small>' . $ptr[$i] . '</small>
                    </div>
                    <div class="col-sm-1 text-end">
                        <small>' . $mrp[$i] . '</small>
                    </div>
                    <div class="col-sm-1 text-end">
                        <small>' . $purchaseAmount[$i] . '</small>
                    </div>
                    <div class="col-sm-1">
                        <small>' . $returnQty[$i] . '</small>
                    </div>
                    <div class="col-sm-1 text-end">
                        <small>' . $CurrentrefundAmount[$i] . '</small>
                    </div>';
                }

                ?>

            </div>
            <!-- </div> -->

            <!-- </div> -->
            <div class="footer">
                <hr calss="my-0" style="height: 1px;">

                <!-- table total calculation -->
                <div class="row my-0">
                    <div class="col-4"></div>
                    <div class="col-4">
                        <div class="row">
                            <div class="col-8 text-end">
                                <p style="margin-top: -5px; margin-bottom: 0px;"><small>CGST:</small></p>
                            </div>
                            <div class="col-4 text-end">
                                <p style="margin-top: -5px; margin-bottom: 0px;">
                                    <small>₹<?php echo $returnGst / 2; ?></small>
                                </p>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-8 text-end">
                                <p style="margin-top: -5px; margin-bottom: 0px;"><small>SGST:</small></p>
                            </div>
                            <div class="col-4 text-end">
                                <p style="margin-top: -5px; margin-bottom: 0px;">
                                    <small>₹<?php echo $returnGst / 2; ?></small>
                                </p>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-8 text-end">
                                <p style="margin-top: -5px; margin-bottom: 0px;"><small>Total GST:</small></p>
                            </div>
                            <div class="col-4 text-end">
                                <p style="margin-top: -5px; margin-bottom: 0px;">
                                    <small>₹<?php echo floatval($returnGst); ?></small>
                                </p>
                            </div>
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="row text-end">
                            <small class="pt-0 mt-0">Total Items <b><?php echo $itemQty; ?></b> & Total Units
                                <b><?php echo $totalRefundItemsQty; ?></b></small>
                        </div>
                        <div class="row text-end mt-1">
                            <h5 class="mb-0 pb-0">Total Refund: <b>₹<?php echo floatval($refund); ?></b></h5>

                        </div>

                    </div>

                </div>



            </div>
            <hr style="height: 1px; margin-top: 2px;">
        </div>
    </div>
    <div class="justify-content-center print-sec d-flex my-5">
        <button class="btn btn-primary shadow mx-2" onclick="backToMain()">Go Back</button>
        <button class="btn btn-primary shadow mx-2" onclick="window.print()">Print Bill</button>
    </div>
    </div>
</body>
<script src="../../../js/bootstrap-js-5/bootstrap.js"></script>

<script>
    function backToMain() {
        window.location.href = "../../stock-return.php";
    }
</script>

</html>