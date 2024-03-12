<?php

require_once dirname(dirname(__DIR__)).'/config/constant.php';
require_once ROOT_DIR.'_config/sessionCheck.php'; //check admin loggedin or not

require_once CLASS_DIR.'dbconnect.php';
require_once CLASS_DIR .'encrypt.inc.php';
require_once ROOT_DIR.'_config/healthcare.inc.php';
require_once CLASS_DIR.'hospital.class.php';
require_once CLASS_DIR.'stockReturn.class.php';
require_once CLASS_DIR.'idsgeneration.class.php';
require_once CLASS_DIR.'currentStock.class.php';
require_once CLASS_DIR.'distributor.class.php';


//  INSTANTIATING CLASS
$HelthCare       = new HealthCare();
$StockReturn     = new StockReturn();
$IdsGeneration   = new IdsGeneration();
$CurrentStock    = new CurrentStock();
$Distributor     = new Distributor;


if ($_SERVER['REQUEST_METHOD'] == "POST") {
    if (isset($_POST['stock-return'])) {
        
        $stockReturnId      = $IdsGeneration->stockReturnId();

        $stockInId          = $_POST['stockInId'];
        $stockInDetailsId   = $_POST['stok-in-details-id'];
        $distributorId      = $_POST['dist-id'];
        $distributorName    = $_POST['dist-name'];
        $distData = json_decode($Distributor->showDistributorById($distributorId));
        // print_r($distData);
        $distAddress    = $distData->data->address;
        $distPin        = $distData->data->area_pin_code;
        $distContact    = $distData->data->phno;
        
        $returnDate      = $_POST['return-date'];
        $returnDate      = date("Y-m-d", strtotime($returnDate));

        $itemQty         = $_POST['items-qty'];
        $totalReturnQty  = $_POST['total-return-qty'];
        
        $returnGst       = $_POST['return-gst-val'];

        $refundMode      = $_POST['refund-mode'];
        // $billNo          = $_POST['bill-no'];
        $refund          = $_POST['refund'];

        $addedBy         = $employeeId;
        $addedOn         = NOW;
        $Admin           = $adminId;
        $status          = 1;

        $allowedUnits = ["tablets", "tablet", "capsules", "capsule"];

    
        $returned = $StockReturn->addStockReturn($stockReturnId, $stockInId, intval($distributorId), $returnDate, intval($itemQty), intval($totalReturnQty), floatval($returnGst), $refundMode, floatval($refund), $status, $addedBy, $addedOn, $Admin);
        
        $returnResult = $returned['result'];

        // $returnResult = true;
        if($returnResult == 'true'){

            //arrays
            $stokInDetailsId = $_POST['stok-in-details-id'];
            $productId      = $_POST['productId'];
            
            $productName    = $_POST['productName'];
            $ids            = count($productId);
        
            $batchNo        = $_POST['batchNo'];
            $distBillNo     = $_POST['distBillNo'];
            $expDate        = $_POST['expDate'];

            $setof          = $_POST['setof'];
            // print_r($setof);
            $unit           = preg_replace('/[0-9]/','',$setof);
            $weightage      = preg_replace('/[a-z-A-Z]/','',$setof);

            $purchasedQty   = $_POST['purchasedQty'];
            $freeQty        = $_POST['freeQty'];
            $mrp            = $_POST['mrp'];
            $ptr            = $_POST['ptr'];
            
            $gstPercent     = $_POST['gst'];
            $gstPercent     = preg_replace('/[%]/','',$gstPercent);

            $discParcent    = $_POST['disc-percent'];
            $discParcent    = preg_replace('/[%]/','',$discParcent);

            $returnQty      = $_POST['return-qty'];
            $returnFQty     = $_POST['return-free-qty'];
            $refundAmount   = $_POST['refund-amount'];

            


        
            for ($i=0; $i < $ids; $i++) { 
                $currentStockData = json_decode($CurrentStock->showCurrentStocByStokInDetialsId($stokInDetailsId[$i]));
                $wholeQty = $currentStockData->qty;
                $looseQty = $currentStockData->loosely_count;

                if ($wholeQty >= $totalReturnQty) {
                
                    if (in_array(strtolower($unit[$i]), $allowedUnits)){
                        $updatedLooseQty = intval($looseQty) - ($totalReturnQty * $weightage[$i]);
                        $updatedQty = intdiv($updatedLooseQty, $weightage[$i]);
                    }else{
                        $updatedLooseQty = 0;
                        $updatedQty = intval($wholeQty) - $totalReturnQty;
                    }
                
                    if($_SESSION['ADMIN']){
                        $updatedBy = $adminId;
                    }else{
                        $updatedBy = $employeeId;
                    }

                    // ============== update current stock function =================
                    $updateCurrentStock = $CurrentStock->updateStockByReturnEdit(intval($stokInDetailsId[$i]), intval($updatedQty), intval($updatedLooseQty), $updatedBy, NOW);

                    // ====== add stock return function =============
                    $detailesReturned = $StockReturn->addStockReturnDetails($stockReturnId, intval($stokInDetailsId[$i]), $productId[$i], $distBillNo[$i], $batchNo[$i], $expDate[$i], $setof[$i], intval($purchasedQty[$i]), intval($freeQty[$i]), floatval($mrp[$i]), floatval($ptr[$i]), intval($gstPercent[$i]), intval($discParcent[$i]), intval($returnQty[$i]), intval($returnFQty[$i]), floatval($refundAmount[$i]));
                }else {
                    echo 'Return quantity is more then current stock quantity of this item!';
                    exit;
                }
            }
        }
    }
}

$response = url_enc(json_encode(['stock_return_id' => $stockReturnId]));
header("Location: ".URL."stock-return-invoice.php?data=".$response);
exit;

$selectClinicInfo = json_decode($HelthCare->showHealthCare($adminId));
// print_r($selectClinicInfo->data);
$pharmacyLogo = $selectClinicInfo->data->logo;
$pharmacyName = $selectClinicInfo->data->hospital_name;

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Stock Return</title>
    <link rel="stylesheet" href="<?= CSS_PATH ?>bootstrap 5/bootstrap.css">
    <link rel="stylesheet" href="<?= CSS_PATH ?>custom/test-bill.css">

</head>


<body>
    <div class="custom-container">
        <div class="custom-body <?php if($refundMode != 'Credit'){ echo "paid-bg";} ?>">
            <div class="card-body ">
                <div class="row">
                    <div class="col-sm-1">
                        <img class="float-end" style="height: 55px; width: 58px;" src="<?= LOCAL_DIR.$pharmacyLogo ?>" alt="Medicy">
                    </div>
                    
                    <div class="col-sm-8">
                        <h4 class="text-start my-0"><?php echo $distributorName; ?></h4>
                        <p class="text-start" style="margin-top: -5px; margin-bottom: 0px;">
                            <small><?php echo $distAddress .', '. $distPin; ?></small>
                        </p>
                        <p class="text-start" style="margin-top: -8px; margin-bottom: 0px;">
                            <small><?php echo 'M: '.$distContact; ?></small>
                        </p>

                    </div>
                    <div class="col-sm-3 border-start border-dark">
                        <p class="my-0"><b>Return Bill</b></p>
                        <p style="margin-top: -5px; margin-bottom: 0px;"><small>Bill id:
                                #<?php echo $stockReturnId; ?></small></p>
                        <p style="margin-top: -5px; margin-bottom: 0px;"><small>Refund Mode:
                                <?php echo $refundMode;?></small>
                        </p>
                        <p style="margin-top: -5px; margin-bottom: 0px;"><small>Return Date: <?php echo $returnDate;?></small>
                        </p>
                    </div>
                </div>
            </div>
            <hr class="my-0" style="height:1px; background: #000000; border: #000000;">
            <div class="row my-0">
                <div class="col-sm-6 my-0">
                    <p ><small><b>Distributor: </b>
                            <?= $distributorName; ?></small></p>
                </div>
                <div class="col-sm-6 my-0 text-end">
                    <p style="margin-top: -3px; margin-bottom: 0px;"><small><b>Bill Date: </b>
                            <?php echo $returnDate; ?></small></p>
                </div>

            </div>
            <hr class="my-0" style="height:1px;">

            <div class="row">
                <!-- table heading -->

                <div class="col-sm-2">
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
                <div class="col-sm-1" style="width: 7%;">
                    <small><b>P.Qty</b></small>
                </div>
                <div class="col-sm-1 text-end" style="width: 5%;">
                    <small><b>Free</b></small>
                </div>
                <div class="col-sm-1 text-end">
                    <small><b>MRP</b></small>
                </div>
                <div class="col-sm-1 text-end">
                    <small><b>PTR</b></small>
                </div>
                <div class="col-sm-1 text-end" style="width: 7%;">
                    <small><b>GST%</b></small>
                </div>
                <div class="col-sm-1" style="width: 7%;">
                    <small><b>DISC%</b></small>
                </div>
                <div class="col-sm-1" style="width: 7%;">
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

                    for ($i=0; $i < $ids; $i++) { 
                $slno = $i+1;

                        if ($slno >1) {
                            echo '<hr style="width: 98%; border-top: 1px dashed #8c8b8b; margin: 0 10px 0; align-items: center;">';
                        }
                        
                echo '
                    <div class="col-sm-2 ">
                        <small>'.substr($productName[$i], 0, 15).'</small>
                    </div>
                    <div class="col-sm-1">
                        <small>'.strtoupper($batchNo[$i]).'</small>
                    </div>
                    <div class="col-sm-1">
                        <small>'.$setof[$i].'</small>
                    </div>
                    <div class="col-sm-1">
                        <small>'.$expDate[$i].'</small>
                    </div>
                    <div class="col-sm-1" style="width: 7%;">
                        <small>'.$purchasedQty[$i].'</small>
                    </div>
                    <div class="col-sm-1 text-end" style="width: 5%;">
                        <small>'.$freeQty[$i].'</small>
                    </div>
                    <div class="col-sm-1 text-end">
                        <small>'.$mrp[$i].'</small>
                    </div>
                    <div class="col-sm-1 text-end">
                        <small>'.$ptr[$i].'</small>
                    </div>
                    <div class="col-sm-1 text-end" style="width: 7%;">
                        <small>'.$gstPercent[$i].'</small>
                    </div>
                    <div class="col-sm-1" style="width: 7%;">
                        <small>'.$discParcent[$i].'</small>
                    </div>
                    <div class="col-sm-1" style="width: 7%;">
                        <small>'.$returnQty[$i].'('.$returnFQty[$i].'F'.')'.'</small>
                    </div>
                    <div class="col-sm-1 text-end">
                        <small>'.$refundAmount[$i].'</small>
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
                            <small class="pt-0 mt-0">Total Items <b><?php echo $itemQty;?></b> & Total Units
                                <b><?php echo $totalReturnQty; ?></b></small>
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
        <button class="btn btn-primary shadow mx-2" onclick="goBack()">Go Back</button>
        <button class="btn btn-primary shadow mx-2" onclick="window.print()">Print Bill</button>
    </div>
    </div>
</body>
<script src="<?= JS_PATH ?>bootstrap-js-5/bootstrap.js"></script>

<script>
    const goBack = () =>{
        window.location.href = '<?= URL ?>stock-return.php';
    }
</script>
</html>