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

    $stockReturnId          = $_POST['stock-return-id']; //
    $distributorId          = $_POST['dist-id'];
    $distributorName        = $_POST['dist-name'];
    $returnDate             = $_POST['return-date'];
    $returnDate             = date("Y-m-d", strtotime($returnDate)); //
    $refundMode             = $_POST['refund-mode']; //
    $itemQty                = $_POST['items-qty'];
    $totalRefundItemsQty         = $_POST['total-refund-qty'];
    $returnGst              = $_POST['return-gst']; //
    $refund                 = $_POST['refund'];
    $addedBy                = $_SESSION['employee_username'];

    $refundAmount           = $_POST['refund-amount'];
    $returnQTY              = $_POST['return-qty'];

    //arrays
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
    $CurrentrefundAmount    = $_POST['refund-amount'];


    // echo $stockReturnId, "&nbsp&nbsp", $distributorId, "&nbsp&nbsp", $distributorName, "&nbsp&nbsp", $returnDate, "&nbsp&nbsp", $refundMode, "&nbsp&nbsp", $itemQty, "&nbsp&nbsp", $totalRefundItemsQty, "&nbsp&nbsp", $returnGst, "&nbsp&nbsp", $refund, "&nbsp&nbsp", $addedBy;
    // echo "<br>";

    // print_r($stockReturnDetailsId);
    // echo "<br>";
    // //------------
    // print_r($productId);
    // echo "<br>";
    // print_r($productName);
    // echo "<br>";
    // print_r($ids);
    // echo "<br>";
    // print_r($batchNo);
    // echo "<br>";
    // print_r($expDate);
    // echo "<br>";
    // print_r($setof);
    // echo "<br>";
    // print_r($purchasedQty);
    // echo "<br>";
    // print_r($freeQty);
    // echo "<br>";
    // print_r($mrp);
    // echo "<br>";
    // print_r($ptr);
    // echo "<br>";
    // print_r($purchaseAmount);
    // echo "<br>";
    // print_r($gst);
    // echo "<br>";
    // print_r($CurrentrefundAmount);
    // echo "<br>";
    // print_r($returnQTY);
    // echo "<br><br>";

    //================== Updating Stock Return tabele hear ========================================

    $addedTime = date("h:i:sa");
    $todayDate = date("Y/m/d");

    $stockReturnEditUpdate = $StockReturn->stockReturnEditUpdate($stockReturnId, $distributorId, $returnDate, $itemQty, $totalRefundItemsQty, $returnGst, $refundMode, $refund, $addedBy, $todayDate, $addedTime);

    // echo "<br>";
    // echo $stockReturnId, "&nbsp&nbsp", $distributorId, "&nbsp&nbsp", $distributorName, "&nbsp&nbsp", $returnDate, "&nbsp&nbsp", $refundMode, "&nbsp&nbsp", $itemQty, "&nbsp&nbsp", $totalRefundItemsQty, "&nbsp&nbsp", $returnGst, "&nbsp&nbsp", $refund, "&nbsp&nbsp", $addedBy;
    // echo "<br>";

    //  fetching previous data and update with current data of stock return details tabel and current stock table

    if ($stockReturnEditUpdate === true) {

        for ($i = 0; $i < $ids; $i++) {

            $id_s = $stockReturnDetailsId[$i];

            $StockReturnData = $StockReturn->showStockReturnById($stockReturnId);  //data from stock return table
            $StockReturnDetailsData = $StockReturn->showStockReturnDetailsById($id_s); //data of stockReturnDetails table

            $distributorId = $StockReturnData[0]['distributor_id'];
            $productId     = $StockReturnDetailsData[0]['product_id'];
            $batchNo       = $StockReturnDetailsData[0]['batch_no'];

            $prevReturnQty = $StockReturnDetailsData[0]['return_qty'];
            $currentReturnQty = $returnQty[$i];

            $CurrentStockData = $CurrentStock->showCurrentStocByProductIdandBatchNoDistributorId($productId, $batchNo, $distributorId); //fetching current stock data

            $LooselyCount  = $CurrentStockData[0]['loosely_count'];
            $QTY           = $CurrentStockData[0]['qty'];
            $refundAmount  = $StockReturnDetailsData[0]['refund_amount'];
            $Weatage       = $StockReturnDetailsData[0]['unit'];
            $unit          = $CurrentStockData[0]['unit'];

            // echo "distributor id from stock return table : ", $distributorId;
            // echo "<br><br>";
            // echo "product id from stock return details table : ", $productId;
            // echo "<br><br>";
            // echo "batch number from stock return details table : ", $batchNo;
            // echo "<br><br>";
            // echo "current stock loolsely count : ", $LooselyCount;
            // echo "<br><br>";
            // echo "current stock : ", $QTY;
            // echo "<br><br>";
            // echo "Stock return Details previous refund amount : ", $refundAmount;
            // echo "<br><br>";
            // echo "previous return qty from stock return details : ", $prevReturnQty;
            // echo "<br><br>";
            // echo "current return qty from edit return page : ", $currentReturnQty;
            // echo "<br><br>";
            // echo "unit from current stock : ", $unit;
            // echo "<br><br>";
            // echo "current refund amoun : ", $CurrentrefundAmount[$i];
            // echo "<br><br>";
            // echo "current return qty : ", $returnQTY[$i];
            // echo "<br><br><br><br>";

            $updatedQty = $QTY + (- ($currentReturnQty - $prevReturnQty)); //edit update total quantity of product

            //=========loosely count calculation area====================
            if ($unit == 'tab' || $unit == 'cap') {
                $newLooselyCount = $LooselyCount + (- (($currentReturnQty * $Weatage) - ($prevReturnQty * $Weatage)));
            } else {
                $newLooselyCount = $LooselyCount;
            }
            //============================================================

            $CurrentStockUpdate = $CurrentStock->updateStockByReturnEdit($productId, $batchNo, $distributorId, $updatedQty, $newLooselyCount);  //updating current stock after edit purchase return

            $stockReturnDetailsEdit = $StockReturn->stockReturnDetailsEditUpdate($id_s, $returnQTY[$i], $CurrentrefundAmount[$i], $addedBy, $returnDate, $addedTime);  //updating stock return details table



            // echo "updated qty : ", $updatedQty;
            // echo "<br><br>";
            // echo "updated loosely count : ", $newLooselyCount;
            // echo "<br><br>";
            // print_r($StockReturnData);
            // echo "<br><br>";
            // print_r($StockReturnDetailsData);
            // echo "<br><br>";
            // print_r($CurrentStockData);
            // echo "<br><br>";
            // echo "<br><br>";
        }

        // echo $productId[$i].'<br>';
        // echo $batchNo[$i].'<br>';
        // echo $expDate[$i].'<br>';
        // echo $setof[$i].'<br>';
        // echo $purchasedQty[$i].'<br>';
        // echo $freeQty[$i].'<br>';
        // echo $mrp[$i].'<br>';
        // echo $ptr[$i].'<br>';
        // echo $purchaseAmount[$i].'<br>';
        // echo $gst[$i].'<br>';
        // echo $returnQty[$i].'<br>';
        // echo $refundAmount[$i].'<br><br><br><br><br><br>';

        ################################# data fetching and updating end #######################################
        
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
    function backToMain(){
        window.location.href="../../stock-return.php";
    }
</script>

</html>