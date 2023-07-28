<?php

ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);
require_once '../sessionCheck.php'; //check admin loggedin or not


require_once '../../../php_control/hospital.class.php';
require_once '../../../php_control/stockReturn.class.php';
require_once '../../../php_control/idsgeneration.class.php';
require_once '../../../php_control/currentStock.class.php';


//  INSTANTIATING CLASS
$HelthCare       = new HelthCare();
$StockReturn     = new StockReturn();
$IdGeneration    = new IdGeneration();
$CurrentStock    =  new CurrentStock();


if (isset($_POST['stock-return-edit'])) {

    // $stockReturnIdArray     = $_POST['stock-return-id']; //
    $stockReturnId          = $_POST['stock-returned-id'];
    $distributorId          = $_POST['dist-id'];
    $distributorName        = $_POST['dist-name'];
    $returnDate             = $_POST['return-date'];
    $returnDate             = date("Y-m-d", strtotime($returnDate)); //
    $refundMode             = $_POST['refund-mode']; //
    $itemQty                = $_POST['items-qty'];
    $totalRefundItemsQty         = $_POST['total-refund-qty'];
    $returnGst              = $_POST['return-gst']; //
    $refund                 = $_POST['NetRefund'];
    $addedBy                = $_SESSION['employee_username'];

    $refundAmount           = $_POST['refund-amount'];
    $returnQTY              = $_POST['return-qty'];

    // arrays
    $stockReturnDetailsId   = $_POST['stock-return-details-id'];
    $productId              = $_POST['productId'];
    $productName            = $_POST['productName'];
    $ids                    = count($productId);
    $batchNo                = $_POST['batchNo'];
    $expDate                = $_POST['expDate'];
    $setof                  = $_POST['setof'];
    $purchasedQty           = $_POST['purchasedQty'];
    $freeQty                = $_POST['freeQty'];
    $mrp                    = $_POST['mrp'];
    $ptr                    = $_POST['ptr'];
    $purchaseAmount         = $_POST['purchase-amount'];
    $gst                    = $_POST['gst'];
    $returnQty              = $_POST['return-qty'];
    $returnFQty              = $_POST['return-free-qty'];
    $CurrentrefundAmount    = $_POST['refund-amount'];

    echo "<br>Distributor Id : ";print_r($distributorId);
    echo "<br>Distributor Name : ";print_r($distributorName);
    echo "<br>Return Date : "; print_r($returnDate);
    echo "<br>Refund Mode : "; print_r($refundMode);
    echo "<br>Item qty : "; print_r($itemQty);
    echo "<br>Id s : "; print_r($ids);
    echo "<br>Total return Item Qty : "; print_r($totalRefundItemsQty);
    echo "<br>Return Gst : "; print_r($returnGst);
    echo "<br>Refund Amount: "; print_r($refund);
    echo "<br>Added By : "; print_r($addedBy);
    echo "<br>Stock return Id : $stockReturnId";
    
    // echo "<br><br><br>ARRAYS ----------- <br>";
    echo "<br>STOK RETURN DETAILS ID : "; print_r($stockReturnDetailsId);
    echo "<br>Product Id : ";print_r($productId);
    echo "<br>Product Name : ";print_r($productName);
    echo "<br>STOK RETURN ID : "; print_r($stockReturnIdArray);
    echo "<br>Batch No : "; print_r($batchNo);
    echo "<br>Exp Date : "; print_r($expDate);
    echo "<br>Set of : "; print_r($setof);
// exit;
    echo "<br>Purchase QTY : "; print_r($purchasedQty);
    echo "<br>Free qty : "; print_r($freeQty);
    echo "<br>MRP : "; print_r($mrp);
    echo "<br>PTR : "; print_r($ptr);
    echo "<br>Purchase Amount : "; print_r($purchaseAmount);
    echo "<br>GST : "; print_r($gst);
    echo "<br>Current Refund Amount : "; print_r($CurrentrefundAmount);
    echo "<br> Return QTY : "; print_r($returnQty);
    echo "<br> Return Free QTY : "; print_r($returnFQty);
    echo "<br>Refund Amount : "; print_r($refundAmount);
    echo "<br>Return QTY : "; print_r($returnQTY);
    
exit;
    //================== Updating Stock Return tabele hear ========================================

    $addedTime = date("h:i:sa");
    $todayDate = date("Y/m/d");

    $stockReturnEditUpdate = $StockReturn->stockReturnEditUpdate($stockReturnId, $distributorId, $returnDate, $itemQty, $totalRefundItemsQty, $returnGst, $refundMode, $refund, $addedBy, $todayDate, $addedTime);
    // $stockReturnEditUpdate = true;

    if ($stockReturnEditUpdate === true) {

        for ($i = 0; $i < $ids; $i++) {
            // echo "<br>hello check";
            $StockReturnDetailsid = $stockReturnDetailsId[$i];
            // echo "<br>Data Value : $StockReturnDetailsid";
            $prevStokReturnDetailsData = $StockReturn->showStockReturnDetailsById($StockReturnDetailsid);
            // echo "<br>Previous Stock return details : "; print_r($prevStokReturnDetailsData);
            foreach($prevStokReturnDetailsData as $prevStockReturnData){
                $prevReturnQty = $prevStockReturnData['return_qty'];
                // echo "<br><br>Prev return qty : $prevReturnQty";
                $prevReturnFQty = $prevStockReturnData['return_free_qty'];
                // echo "<br>Prev return F qty : $prevReturnFQty"; 
                $stockInDetailsId = $prevStockReturnData['stokIn_details_id'];
                // echo "<br>Prev stokIN details Id : $stockInDetailsId";
                $totalPrevReturn = intval($prevReturnQty) + intval($prevReturnFQty);
                // echo "<br>Total Prev Return qty : $totalPrevReturn";
                $prevReturnWeatage = $prevStockReturnData['unit'];
                // echo "<br>Prev return weatage : $prevReturnWeatage";
            }

            $unit = preg_replace("/[^a-z]+/", "", $setof[$i]);
            // echo "<br>Unit : $unit";
            $weatage = preg_replace("/[^0-9]+/", "", $setof[$i]);
            // echo "<br>Weatage : $weatage";

           if ($unit == 'tab' || $unit == 'cap') {
                $prevReturnLooselyCount = intval($totalPrevReturn) * intval($weatage);
                $currentReturnLooselyCount = ((intval($returnQty[$i])+intval($returnFQty[$i])) * intval($weatage));
            } else {
                $prevReturnLooselyCount = 0;
                $currentReturnLooselyCount = 0;
            }

            // echo "<br>Prev Return loose qty : $prevReturnLooselyCount";
            // echo "<br>Current Return  qty : ",$currentReturnLooselyCount / intval($weatage);
            // echo "<br>Current Return loose qty : $currentReturnLooselyCount";
            // echo "<br>",$returnQty[$i] + $returnFQty[$i];

 //==================================== need to check from this area ========================================

            $CurrentStockData = $CurrentStock->showCurrentStocByStokInDetialsId($stockInDetailsId); //
            // echo "<br>Current stok data on stock in detials id : "; print_r($CurrentStockData);
            
            if ($CurrentStockData != null) {

                foreach($CurrentStockData as $currentStock){
                    $CurrentStockLooselyCount  = $currentStock['loosely_count'];
                    // echo "<br>Current Stock loosely count : $CurrentStockLooselyCount";
                    $CurrentStockQTY           = $currentStock['qty'];
                    // echo "<br>Current qty : $CurrentStockQTY";
                }


                // echo "<br><br>TOTAL RETURN QTY : ";
                $totalCrrntReturnQty =  intval($returnQty[$i]) + intval($returnFQty[$i]);
                $totalPrevReturnQty = (intval($prevReturnQty)) + (intval($prevReturnFQty));
                $updatedReturnQty = $totalCrrntReturnQty - $totalPrevReturnQty;
                // echo $updatedReturnQty;

                $updatedQty = intval($CurrentStockQTY) +  (-($updatedReturnQty)); //edit update total quantity of product   
                // echo "<br>Updated qty : $updatedQty";
                $newLooselyCount = intval($CurrentStockLooselyCount) + (-($currentReturnLooselyCount - $prevReturnLooselyCount));
                // echo "<br>Updated loose qty : $newLooselyCount";

            } else {

                $updatedQty = '';
                $newLooselyCount = '';

            }
            
            $CurrentStockUpdate = $CurrentStock->updateStockByReturnEdit($stockInDetailsId, $updatedQty, $newLooselyCount);  //updating current stock after edit purchase return

            $stockReturnDetailsEdit = $StockReturn->stockReturnDetailsEditUpdate($StockReturnDetailsid, $returnQty[$i], $returnFQty[$i], $refundAmount[$i], $addedBy, $returnDate, $addedTime);  //updating stock return details table
        }

        ############################## data fetching and updating end ###################################

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