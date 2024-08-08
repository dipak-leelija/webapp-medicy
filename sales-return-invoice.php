<?php
require_once __DIR__. '/config/constant.php';
require_once ROOT_DIR . '_config/sessionCheck.php'; //check admin loggedin or not
require_once CLASS_DIR . 'dbconnect.php';
require_once ROOT_DIR  . '_config/healthcare.inc.php';
require_once CLASS_DIR . 'patients.class.php';
require_once CLASS_DIR . 'products.class.php';
require_once CLASS_DIR . 'doctors.class.php';
require_once CLASS_DIR . 'salesReturn.class.php';

//  INSTANTIATING CLASS
$Patients        = new Patients();
$Products        = new Products();
$Doctors         = new Doctors();
$SalesReturn     = new SalesReturn;


// ---------- NON ARRAY ELEMENTS -----------
$invoice        = $_GET['data'];
$invoiceId = str_replace("#", '', $invoice);
        
$patientData = $SalesReturn->selectSalesReturn("invoice_id", $invoice);

$patientId = $patientData[0]['patient_id'];
$purchasedDate = $patientData[0]['bill_date'];

$salesReturnTableId = $patientData[0]['id'];
$patientData[0]['invoice_id'];
$patientData[0]['status'];
$patientData[0]['added_by'];
$patientData[0]['added_on'];
$patientData[0]['updated_by'];
$patientData[0]['updated_on'];
$patientData[0]['admin_id'];


if ($patientId == 'Cash Sales') {
    $patientId      = 'Cash Sales';
    $patientName    = 'Cash Sales';
    $contactNumber  = "";
} else {
    $patient = $Patients->patientsDisplayByPId($patientId);
    $patient        = json_decode($patient);
    $patientName    = $patient->name;
    $contactNumber  = $patient->phno;
}

$billDate       = $purchasedDate;
$billDate       = date('Y-m-d', strtotime($billDate));
$returnDate     = $patientData[0]['return_date'];
$items          = $patientData[0]['items'];
$totalQtys      = $patientData[0]['total_qty']; 
$gstAmount      = $patientData[0]['gst_amount'];
$refundAmount   = $patientData[0]['refund_amount'];
$refundMode     = $patientData[0]['refund_mode'];

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="icon" type="image/x-icon" href="<?= FAVCON_PATH ?>">
    <title>Medicy Health Care Lab Test Bill</title>
    <link rel="stylesheet" href="<?php echo PLUGIN_PATH ?>/bootstrap/5.3.3/dist/css/bootstrap.css">
    <link rel="stylesheet" href="<?= CSS_PATH ?>custom/test-bill.css">

    <style>
    body {
        overscroll-behavior-y: contain;
    }
    </style>
</head>

<body>
    <div class="custom-container">
        <div class="custom-body">
            <div class="card-body border-bottom border-dark">
                <div class="row">
                    <div class="col-sm-1">
                        <img class="float-end" style="height: 55px; width: 58px;" src="<?= $healthCareLogo?>"
                            alt="Medicy">
                    </div>
                    <div class="col-sm-8">
                        <h4 class="text-start my-0"><?php echo $healthCareName; ?></h4>
                        <p class="text-start" style="margin-top: -5px; margin-bottom: 0px;">
                            <small><?php echo $healthCareAddress1 . ', ' . $healthCareAddress2 . ', ' . $healthCareCity . ', ' . $healthCarePin; ?></small>
                        </p>
                        <p class="text-start" style="margin-top: -8px; margin-bottom: 0px;">
                            <small><?php echo 'M: ' . $healthCarePhno . ', ' . $healthCareApntbkNo; ?></small>
                        </p>

                    </div>
                    <div class="col-sm-3 border-start border-dark">
                        <p class="my-0"><b>Return Bill</b></p>
                        <p style="margin-top: -5px; margin-bottom: 0px;"><small>Bill id:
                                <?php echo $invoice; ?></small></p>
                        <p style="margin-top: -5px; margin-bottom: 0px;"><small>Return Mode:
                                <?php echo $refundMode; ?></small>
                        </p>
                        <p style="margin-top: -5px; margin-bottom: 0px;"><small>Return Date:
                                <?php echo $returnDate; ?></small>
                        </p>
                    </div>
                </div>
            </div>
            <!-- <hr class="my-0" style="height:1px; background: #000000; border: #000000;"> -->
            <div class="row my-0">
                <div class="col-sm-6 my-0">
                    <p style="margin-top: -3px; margin-bottom: 0px;">
                        <small><b>Patient: </b> <?php echo $patientName; ?>, Contact:
                            <?php echo $contactNumber; ?>
                        </small>
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
                    <small><b>Packing</b></small>
                </div>
                <div class="col-sm-1">
                    <small><b>Batch</b></small>
                </div>
                <div class="col-sm-1">
                    <small><b>Exp.</b></small>
                </div>
                <div class="col-sm-1">
                    <small><b>Disc(%)</b></small>
                </div>
                <div class="col-sm-1">
                    <small><b>GST(%)</b></small>
                </div>
                <!-- <div class="col-sm-1">
                    <small><b>P.QTY</b></small>
                </div>
                <div class="col-sm-1">
                    <small>' . $qtys . '</small>
                </div> -->
                <div class="col-sm-1">
                    <small><b>Return</b></small>
                </div>
                <div class="col-sm-1">
                    <small><b>Taxable</b></small>
                </div>
                <div class="col-sm-1 text-end">
                    <small><b>Refunds</b></small>
                </div>
                <!--/end table heading -->
            </div>

            <hr class="my-0" style="height:1px;">

            <!-- <div class="row"> -->

            <?php
                $slno = 0;
                $subTotal = floatval(00.00);

                $allItems = $SalesReturn->selectSalesReturnList("sales_return_id", $salesReturnTableId);
                
                foreach ($allItems as $eachItem) {

                    $itemID         = $eachItem['id'];
                    // $ = $eachItem['invoice_id'];
                    // $ = $eachItem['sales_return_id'];
                    // $ = $eachItem['item_id'];
                    $productId      = $eachItem['product_id'];
                    $batchNo        = $eachItem['batch_no'];
                    $setOf          = $eachItem['weatage'];
                    $expdates       = $eachItem['exp'];
                    $mrp            = $eachItem['mrp'];
                    $ptr            = $eachItem['ptr'];
                    $disc           = $eachItem['disc'];
                    $gst            = $eachItem['gst'];
                    $gstAmount      = $eachItem['gst_amount'];
                    $taxableArray   = $eachItem['taxable'];
                    $returnQty      = $eachItem['return_qty'];
                    $perItemRefund  = $eachItem['refund_amount'];

                    $productResponse = json_decode($Products->showProductsById($productId));
                    if($productResponse->status == 1){
                        $product = $productResponse->data;
                        $productName = $product->name;
                    }

                // --------------------------------------
                $itemWeatage = preg_replace('/[a-z]/', '', $setOf);
                $unitType = preg_replace('/[0-9]/', '', $setOf);
                    echo '
                                <div class="row">
                                    <div class="col-sm-1 text-center">
                                        <small>' . $slno . '</small>
                                    </div>
                                    <div class="col-sm-2 ">
                                        <small>' . substr($productName, 0, 15) . '</small>
                                    </div>
                    
                                    <div class="col-sm-1">
                                        <small>' . $setOf . '</small>
                                    </div>
                                    <div class="col-sm-1">
                                        <small>' .  $batchNo . '</small>
                                    </div>
                                    <div class="col-sm-1">
                                        <small>' . $expdates . '</small>
                                    </div>
                                    <div class="col-sm-1">
                                        <small>' . $disc . '</small>
                                    </div>
                                    <div class="col-sm-1">
                                        <small>' . $gst . '</small>
                                    </div>
                                    <div class="col-sm-1">
                                        <small>' . $returnQty . '</small>
                                    </div>
                                    <div class="col-sm-1">
                                        <small>' . $taxableArray . '</small>
                                    </div>
                                    <div class="col-sm-1" style="text-align: right;">
                                        <small>' . $perItemRefund . '</small>
                                    </div>
                                </div>';
                }

                // }
                ?>

            <!-- </div> -->
            <!-- </div> -->

            <!-- </div> -->
            <div class="footer border-top border-bottom border-dark mt-4">
                <!-- <hr calss="my-0" style="height: 1px;"> -->

                <!-- table total calculation -->
                <div class="row my-2 ">
                    <div class="col-4"></div>
                    <div class="col-4 border-end border-dark">
                        <div class="row">
                            <div class="col-8 text-end">
                                <p style="margin-top: -5px; margin-bottom: 0px;"><small>CGST:</small></p>
                            </div>
                            <div class="col-4 text-end">
                                <p style="margin-top: -5px; margin-bottom: 0px;">
                                    <small>₹<?php echo (float)$gstAmount / 2; ?></small>
                                </p>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-8 text-end">
                                <p style="margin-top: -5px; margin-bottom: 0px;"><small>SGST:</small></p>
                            </div>
                            <div class="col-4 text-end">
                                <p style="margin-top: -5px; margin-bottom: 0px;">
                                    <small>₹<?php echo (float)$gstAmount / 2; ?></small>
                                </p>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-8 text-end">
                                <p style="margin-top: -5px; margin-bottom: 0px;"><small>Total GST:</small></p>
                            </div>
                            <div class="col-4 text-end">
                                <p style="margin-top: -5px; margin-bottom: 0px;">
                                    <small>₹<?php echo floatval($gstAmount); ?></small>
                                </p>
                            </div>
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="row">
                            <div class="col-8 text-end">
                                <p style="margin-top: -5px; margin-bottom: 0px;"><small>Items: </small></p>
                            </div>
                            <div class="col-4 text-end">
                                <p style="margin-top: -5px; margin-bottom: 0px;">
                                    <small><?php echo $items; ?></small>
                                </p>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-8 text-end">
                                <p style="margin-top: -5px; margin-bottom: 0px;"><small>Refund:</small></p>
                            </div>
                            <div class="col-4 text-end">
                                <p style="margin-top: -5px; margin-bottom: 0px;">
                                    <small><b>₹<?php echo floatval($refundAmount); ?></b></small>
                                </p>
                            </div>
                        </div>
                    </div>

                </div>



            </div>
            <!-- <hr style="height: 1px; margin-top: 2px;"> -->
        </div>
    </div>
    <div class="justify-content-center print-sec d-flex my-5">
        <!-- <button class="btn btn-primary shadow mx-2" onclick="history.back()">Go Back</button> -->
        <button class="btn btn-primary shadow mx-2" onclick="goBack()">Go Back</button>
        <button class="btn btn-primary shadow mx-2" onclick="window.print()">Print Bill</button>
    </div>
    </div>
    <script>
    const goBack = () => {
        window.location.href = 'sales-returns.php';
    }
    </script>
    <script src="<?= JS_PATH ?>bootstrap-js-5/bootstrap.js"></script>
</body>

</html>