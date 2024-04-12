<?php

require_once './config/constant.php';
require_once ROOT_DIR.'_config/sessionCheck.php'; //check admin loggedin or not

require_once CLASS_DIR.'dbconnect.php';
require_once ROOT_DIR.'_config/healthcare.inc.php';
require_once CLASS_DIR .'encrypt.inc.php';
require_once CLASS_DIR.'hospital.class.php';
require_once CLASS_DIR.'stockReturn.class.php';
require_once CLASS_DIR.'distributor.class.php';
require_once CLASS_DIR.'products.class.php';


//  INSTANTIATING CLASS
$HelthCare       = new HealthCare();
$StockReturn     = new StockReturn();
$Distributor     = new Distributor;
$StockReturn     = new StockReturn;
$Products        = new Products;

if (isset($_GET['data'])) {

   $reponse = json_decode(url_dec($_GET['data']));
    
   $stockReturnId   = $reponse->stock_return_id;
    if($stockReturnId) {
        $returnResponse = json_decode($StockReturn->showStockReturnById($stockReturnId));
        // print_r($returnResponse);
        if ($returnResponse->status == 1) {

            $returnData     = $returnResponse->data[0];
            $returnDate     = $returnData->return_date;
            $totalReturnQty = $returnData->total_qty;
            $returnGst      = $returnData->gst_amount;
            $refundMode     = $returnData->refund_mode;
            $refund         = $returnData->refund_amount;
            $itemQty        = $returnData->items;
            $distributorId  = $returnData->distributor_id;
            // $billNo         = $returnData->bill_no;

            $distributorResponse = json_decode($Distributor->showDistributorById($distributorId));

            if ($distributorResponse->status) {
                $distributorData = $distributorResponse->data;

                $distributorName = $distributorData->name;
                $distContact = $distributorData->phno;
                $distAddress = $distributorData->address;
                $distPIN = $distributorData->area_pin_code;

            }

            $returnDetails = $StockReturn->showStockReturnDetails($stockReturnId);

        }


    }
}


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
                            <small><?php echo $distAddress .', '. $distPIN; ?></small>
                        </p>
                        <p class="text-start" style="margin-top: -8px; margin-bottom: 0px;">
                            <small><?php echo 'M: '.$distContact; ?></small>
                        </p>

                    </div>
                    <div class="col-sm-3 border-start border-dark">
                        <p class="my-0"><b>Return Bill</b></p>
                        <p style="margin-top: -5px; margin-bottom: 0px;"><small>Return ID:
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
                foreach ($returnDetails as $eachDetail) {

                    $productNameResponse = json_decode($Products->showProductNameById($eachDetail['product_id']));
                    if($productNameResponse->status){
                        $productName = $productNameResponse->data->name;
                    }



                    $batchNo        = $eachDetail['batch_no'];
                    $expDate        = $eachDetail['exp_date'];
                    $setof          = $eachDetail['unit'];
                    $purchasedQty   = $eachDetail['purchase_qty'];
                    $freeQty        = $eachDetail['free_qty'];
                    $mrp            = $eachDetail['mrp'];
                    $ptr            = $eachDetail['ptr'];
                    $gstPercent     = $eachDetail['gst'];
                    $discParcent    = $eachDetail['disc'];
                    $returnQty      = $eachDetail['return_qty'];
                    $returnFQty     = $eachDetail['return_free_qty'];
                    $refundAmount   = $eachDetail['refund_amount'];
                

                        if (count($returnDetails) >1) {
                            echo '<hr style="width: 98%; border-top: 1px dashed #8c8b8b; margin: 0 10px 0; align-items: center;">';
                        }
                        
                echo '
                    <div class="col-sm-2 ">
                        <small>'.substr($productName, 0, 15).'</small>
                    </div>
                    <div class="col-sm-1">
                        <small>'.strtoupper($batchNo).'</small>
                    </div>
                    <div class="col-sm-1">
                        <small>'.$setof.'</small>
                    </div>
                    <div class="col-sm-1">
                        <small>'.$expDate.'</small>
                    </div>
                    <div class="col-sm-1" style="width: 7%;">
                        <small>'.$purchasedQty.'</small>
                    </div>
                    <div class="col-sm-1 text-end" style="width: 5%;">
                        <small>'.$freeQty.'</small>
                    </div>
                    <div class="col-sm-1 text-end">
                        <small>'.$mrp.'</small>
                    </div>
                    <div class="col-sm-1 text-end">
                        <small>'.$ptr.'</small>
                    </div>
                    <div class="col-sm-1 text-end" style="width: 7%;">
                        <small>'.$gstPercent.'</small>
                    </div>
                    <div class="col-sm-1" style="width: 7%;">
                        <small>'.$discParcent.'</small>
                    </div>
                    <div class="col-sm-1" style="width: 7%;">
                        <small>'.$returnQty.'('.$returnFQty.'F'.')'.'</small>
                    </div>
                    <div class="col-sm-1 text-end">
                        <small>'.$refundAmount.'</small>
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