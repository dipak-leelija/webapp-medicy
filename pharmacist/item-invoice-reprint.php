<?php

ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);

require_once '_config/sessionCheck.php';//check admin loggedin or not


require_once '../php_control/hospital.class.php';
require_once '../php_control/stockOut.class.php';



//  INSTANTIATING CLASS
$HelthCare       = new HelthCare();
$StockOut        = new StockOut();

if (isset($_GET['id'])) {
    // echo $_GET['id'];
    $stockOut = $StockOut->stockOutDisplayById($_GET['id']);
    // print_r($stockOut);

    $invoiceId      = $stockOut[0]['invoice_id'];
    $customerName   = $stockOut[0]['customer_id'];
    $reffby         = $stockOut[0]['reff_by'];	
    $totalMrp       = $stockOut[0]['mrp'];	
    $totalGSt       = $stockOut[0]['gst'];	
    $billAmout      = $stockOut[0]['amount'];	
    $pMode          = $stockOut[0]['payment_mode'];	
    $billdate       = $stockOut[0]['bill_date'];	

    $details = $StockOut->stockOutDetailsById($_GET['id']);

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
    <title>Medicy Health Care Lab Test Bill</title>
    <link rel="stylesheet" href="../css/bootstrap 5/bootstrap.css">
    <link rel="stylesheet" href="../css/custom/test-bill.css">

</head>


<body>
    <div class="custom-container">
        <div class="custom-body <?php if($pMode != 'Credit'){ echo "paid-bg";} ?>">
            <div class="card-body ">
                <div class="row">
                    <div class="col-sm-1">
                        <img class="float-end" style="height: 55px; width: 58px;" src="../images/logo-p.jpg"
                            alt="Medicy">
                    </div>
                    <div class="col-sm-8">
                        <h4 class="text-start my-0"><?php echo $healthCareName; ?></h4>
                        <p class="text-start" style="margin-top: -5px; margin-bottom: 0px;">
                            <small><?php echo $healthCareAddress1.', '.$healthCareAddress2.', '.$healthCareCity.', '.$healthCarePIN; ?></small>
                        </p>
                        <p class="text-start" style="margin-top: -8px; margin-bottom: 0px;">
                            <small><?php echo 'M: '.$healthCarePhno.', '.$healthCareApntbkNo; ?></small>
                        </p>

                    </div>
                    <div class="col-sm-3 border-start border-dark">
                        <p class="my-0"><b>Invoice</b></p>
                        <p style="margin-top: -5px; margin-bottom: 0px;"><small>Bill id:
                                #<?php echo $invoiceId; ?></small></p>
                        <p style="margin-top: -5px; margin-bottom: 0px;"><small>Payment: <?php echo $pMode;?></small>
                        </p>
                        <p style="margin-top: -5px; margin-bottom: 0px;"><small>Date: <?php echo $billdate;?></small>
                        </p>
                    </div>
                </div>
            </div>
            <hr class="my-0" style="height:1px; background: #000000; border: #000000;">
            <div class="row my-0">
                <div class="col-sm-6 my-0">
                    <p style="margin-top: -3px; margin-bottom: 0px;"><small><b>Patient: </b>
                            <?php echo $customerName.', Age: 25'; ?></small></p>
                    <p style="margin-top: -5px; margin-bottom: 0px;"><small>M:
                            <?php echo 7699753019; echo ', Test date: 241544';?></small></p>
                </div>
                <div class="col-sm-6 my-0">
                    <p class="text-end" style="margin-top: -3px; margin-bottom: 0px;"><small><b>Refered By:</b>
                            <?php echo $reffby; ?></small></p>
                    <p class="text-end" style="margin-top: -5px; margin-bottom: 0px;">
                        <small><?php //if($doctorReg != NULL){echo 'Reg: '.$doctorReg; } ?></small>
                    </p>
                </div>

            </div>
            <hr class="my-0" style="height:1px;">

            <div class="row">
                <!-- table heading -->
                <div class="col-sm-1 text-center">
                    <small><b>SL.</b></small>
                </div>
                <div class="col-sm-2 ">
                    <small><b>Name</b></small>
                </div>
                <div class="col-sm-1">
                    <small><b>Manuf.</b></small>
                </div>
                <div class="col-sm-1">
                    <small><b>Packing</b></small>
                </div>
                <div class="col-sm-1">
                    <small><b>Batch</b></small>
                </div>
                <div class="col-sm-1">
                    <small><b>Exp.</b></small>
                </div>
                <div class="col-sm-1 text-end">
                    <small><b>QTY</b></small>
                </div>
                <div class="col-sm-1 text-end">
                    <small><b>MRP</b></small>
                </div>
                <div class="col-sm-1 text-end">
                    <small><b>Disc(%)</b></small>
                </div>
                <div class="col-sm-1 text-end">
                    <small><b>GST(%)</b></small>
                </div>
                <div class="col-sm-1 text-end">
                    <small><b>Amount</b></small>
                </div>
                <!--/end table heading -->
            </div>

            <hr class="my-0" style="height:1px;">

            <div class="row">
                <?php
                $slno = 0;
                $subTotal = floatval(00.00);
                    foreach ($details as $detail) {
                        $slno++;
                 
                                if ($slno >1) {
                                    echo '<hr style="width: 98%; border-top: 1px dashed #8c8b8b; margin: 0 10px 0; align-items: center;">';
                                }
                                
                           echo '<div class="col-sm-1 text-center">
                                    <small>'.$slno.'</small>
                                </div>
                                <div class="col-sm-2 ">
                                    <small>'.substr($detail['item_name'], 0, 15).'</small>
                                </div>
                                <div class="col-sm-1">
                                    <small>'.strtoupper(substr("Dr. Reddys Pvt Ltd", 0, 7)).'</small>
                                </div>
                                <div class="col-sm-1">
                                    <small>'.$detail['weatage'].'</small>
                                </div>
                                <div class="col-sm-1">
                                    <small>'.$detail['batch_no'].'</small>
                                </div>
                                <div class="col-sm-1">
                                    <small>'.$detail['exp_date'].'</small>
                                </div>
                                <div class="col-sm-1 text-end">
                                    <small>'.$detail['qty'].'</small>
                                </div>
                                <div class="col-sm-1 text-end">
                                    <small>'.$detail['mrp'].'</small>
                                </div>
                                <div class="col-sm-1 text-end">
                                    <small>'.$detail['disc'].'</small>
                                </div>
                                <div class="col-sm-1 text-end">
                                    <small>'.$detail['gst'].'</small>
                                </div>
                                <div class="col-sm-1 text-end">
                                    <small>'.$detail['amount'].'</small>
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
                                    <small>₹<?php echo $totalGSt / 2; ?></small>
                                </p>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-8 text-end">
                                <p style="margin-top: -5px; margin-bottom: 0px;"><small>SGST:</small></p>
                            </div>
                            <div class="col-4 text-end">
                                <p style="margin-top: -5px; margin-bottom: 0px;">
                                    <small>₹<?php echo $totalGSt / 2; ?></small>
                                </p>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-8 text-end">
                                <p style="margin-top: -5px; margin-bottom: 0px;"><small>Total GST:</small></p>
                            </div>
                            <div class="col-4 text-end">
                                <p style="margin-top: -5px; margin-bottom: 0px;">
                                    <small>₹<?php echo floatval($totalGSt); ?></small>
                                </p>
                            </div>
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="row">
                            <div class="col-8 text-end">
                                <p style="margin-top: -5px; margin-bottom: 0px;"><small>Total MRP:</small></p>
                            </div>
                            <div class="col-4 text-end">
                                <p style="margin-top: -5px; margin-bottom: 0px;">
                                    <small><b>₹<?php echo floatval($totalMrp); ?></b></small>
                                </p>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-8 text-end">
                                <p style="margin-top: -5px; margin-bottom: 0px;"><small>Net:</small></p>
                            </div>
                            <div class="col-4 text-end">
                                <p style="margin-top: -5px; margin-bottom: 0px;">
                                    <small><b>₹<?php echo floatval($billAmout); ?></b></small>
                                </p>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-8 text-end">
                                <p style="margin-top: -5px; margin-bottom: 0px;"><small>You Saved:</small></p>
                            </div>
                            <div class="col-4 text-end">
                                <p style="margin-top: -5px; margin-bottom: 0px;">
                                    <small>₹<?php  echo $totalMrp - $billAmout; ?></small>
                                </p>
                            </div>
                        </div>
                    </div>

                </div>



            </div>
            <hr style="height: 1px; margin-top: 2px;">
        </div>
    </div>
    <div class="justify-content-center print-sec d-flex my-5">
        <button class="btn btn-primary shadow mx-2" onclick="history.back()">Go Back</button>
        <button class="btn btn-primary shadow mx-2" onclick="window.print()">Print Bill</button>
    </div>
    </div>
</body>
<script src="../js/bootstrap-js-5/bootstrap.js"></script>

</html>