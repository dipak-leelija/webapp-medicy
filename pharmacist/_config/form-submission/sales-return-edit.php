<!-- <?php
ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);

require_once '../sessionCheck.php'; //check admin loggedin or not


require_once '../../../php_control/hospital.class.php';
require_once '../../../php_control/stockOut.class.php';
require_once '../../../php_control/patients.class.php';
require_once '../../../php_control/products.class.php';
require_once '../../../php_control/doctors.class.php';
require_once '../../../php_control/salesReturn.class.php';
require_once '../../../php_control/currentStock.class.php';
require_once '../../../php_control/stockInDetails.class.php';

// require_once '../../../php_control/idsgeneration.class.php';



//  INSTANTIATING CLASS
$HelthCare       = new HelthCare();
$StockOut        = new StockOut();
$Patients        = new Patients();
$Products        = new Products();
$Doctors         = new Doctors();
$SalesReturn     = new SalesReturn();
$CurrentStock    = new CurrentStock();
$StockInDetails  = new StockInDetails();
// $IdGeneration    = new IdGeneration();




if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['sales-return-edit'])) {
        
        $products   = $_POST['productId'];
        $batchNo    = $_POST['batchNo'];
        $expdates   = $_POST['expDate'];
        $weatage    = $_POST['setof'];
        $qtys       = $_POST['qty'];
        $mrp        = $_POST['mrp'];
        $discs      = $_POST['disc'];
        $gst        = $_POST['gst'];
        $totalGSt   = $_POST['taxable'];
        $returnQty  = $_POST['return'];
        $refunds    = $_POST['refund'];
        $billAmount = $_POST['billAmount'];

        $invoice        = $_POST['invoice'];
        $billDate       = $_POST['purchased-date'];
        $billDate       = date('Y-m-d', strtotime($billDate));
        $returnDate     = $_POST['return-date'];
        $items          = $_POST['total-items'];
        $refundMode     = $_POST['refund-mode'];
        $totalQtys      = $_POST['total-qty'];
        $gstAmount      = $_POST['gst-amount'];
        $refundAmount   = $_POST['refund-amount'];
        $invoiceId      = str_replace("#", '', $invoice);
        $sold           = $StockOut->stockOutDisplayById($invoiceId);
        $patient        = $Patients->patientsDisplayByPId($sold[0]['customer_id']);
        $added_by       = $_SESSION['employee_username'];


        $PatientsName = $_POST['patient-name'];
        if($PatientsName == 'Cash Sales'){
            $patientNm = "Cash Sales";
            $patientPNo = " ";
        }
        else{
            $patientNm = $patient[0]['name'];
            $patientPNo = $patient[0]['phno'];
        }

        // Generate Return Bill

        $returned = $SalesReturn->addSalesReturn($invoiceId, $sold[0]['customer_id'], $billDate, $returnDate, $items, $gstAmount, $refundAmount, $refundMode, $added_by);

        if ($returned == true) {

            foreach ($_POST['productId'] as $productId) {

                $batch = array_shift($_POST['batchNo']);
                $qty   = array_shift($_POST['qty']);

                $success = $SalesReturn->addReturnDetails($invoiceId, $productId, $batch, array_shift($_POST['setof']), array_shift($_POST['expDate']), $qty, array_shift($_POST['disc']), array_shift($_POST['gst']), array_shift($_POST['billAmount']), array_shift($_POST['return']), array_shift($_POST['refund']), $added_by);

                // now insert into current stock 
                $stock = $CurrentStock->checkStock($productId, $batch);

                if (count($stock) == 0 || $stock == '') {

                    $pDetails = $StockInDetails->showStockInDetailsByTable('product_id', 'batch_no', $productId, $batch);

                    // (float) filter_var( $uWeightage, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION )

                    if ($pDetails[0]['unit'] == 'cap' || $pDetails[0]['unit'] == 'tab') {
                        $looselyCount = $qty * $pDetails[0]['weightage'];
                        $singlePrice  = $pDetails[0]['base'] + ($pDetails[0]['gst'] / 100 * $pDetails[0]['base']);
                        $looselyPrice = $singlePrice * $looselyCount;
                    } else {
                        $looselyCount = 0;
                        $looselyPrice = 0;
                    }

                    $addedBy = '';
                    // updateStock($productId, $batch, $newQuantity, $newLCount);
                    $CurrentStock->addCurrentStock($productId, $batch, $pDetails[0]['exp_date'], $pDetails[0]['distributor_bill'], $looselyCount, $looselyPrice, $pDetails[0]['weightage'], $pDetails[0]['unit'], $qty, $pDetails[0]['mrp'], $pDetails[0]['ptr'], $pDetails[0]['gst'], $addedBy);
                } else {

                    $newQuantity = $stock[0]['qty'] + $qty;

                    if ($stock[0]['unit'] == 'cap' || $stock[0]['unit'] == 'tab') {
                        $newLCount   = $stock[0]['weatage'] * $newQuantity;
                    } else {
                        $newLCount = 0;
                    }
                    $CurrentStock->updateStock($productId, $batch, $newQuantity, $newLCount);
                }
            }
        }

        // $Doctors->showDoctorById($docId);

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
}
?> -->

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Medicy Health Care Lab Test Bill</title>
    <link rel="stylesheet" href="../../../css/bootstrap 5/bootstrap.css">
    <link rel="stylesheet" href="../../../css/custom/test-bill.css">

    <style>
        body {
            overscroll-behavior-y: contain;
        }
    </style>
</head>


<body>
    <?php if (isset($_POST['sales-return-edit'])) { ?>
        <div class="custom-container">
            <div class="custom-body">
                <div class="card-body border-bottom border-dark">
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
                            <small><b>Patient: </b> <?php echo $patientNm; ?>, Contact:
                                <?php echo $patientPNo; ?>
                            </small>
                        </p>

                    </div>
                    <div class="col-sm-6 my-0">
                        <p class="text-end" style="margin-top: -3px; margin-bottom: 0px;"><small><b>Refered By:</b>
                                <?php echo $sold[0]['reff_by']; ?></small></p>
                        <p class="text-end" style="margin-top: -5px; margin-bottom: 0px;">
                            <small><?php //if($doctorReg != NULL){echo 'Reg: '.$doctorReg; } 
                                    ?></small>
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
                        <small><b>Qty</b></small>
                    </div>
                    <div class="col-sm-1">
                        <small><b>Disc(%)</b></small>
                    </div>
                    <div class="col-sm-1">
                        <small><b>GST(%)</b></small>
                    </div>
                    <div class="col-sm-1">
                        <small><b>Amount</b></small>
                    </div>
                    <div class="col-sm-1">
                        <small><b>Return</b></small>
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
                foreach ($products as $product) {
                    $slno++;

                    if ($slno > 1) {
                        echo '<hr style="width: 98%; border-top: 1px dashed #8c8b8b; margin: 0 10px 0; align-items: center;">';
                    }

                    $itemQty = array_shift($qtys);
                    $mrpOnQty = array_shift($mrp);
                    $mrpOnQty = $mrpOnQty * $itemQty;

                    $showProducts = $Products->showProductsById($product);
                    echo '
                                <div class="row">
                                    <div class="col-sm-1 text-center">
                                        <small>' . $slno . '</small>
                                    </div>
                                    <div class="col-sm-2 ">
                                        <small>' . substr($showProducts[0]['name'], 0, 15) . '</small>
                                    </div>
                    
                                    <div class="col-sm-1">
                                        <small>' . array_shift($weatage) . '</small>
                                    </div>
                                    <div class="col-sm-1">
                                        <small>' . array_shift($batchNo) . '</small>
                                    </div>
                                    <div class="col-sm-1">
                                        <small>' . array_shift($expdates) . '</small>
                                    </div>
                                    <div class="col-sm-1">
                                        <small>' . $itemQty . '</small>
                                    </div>
                                    <div class="col-sm-1">
                                        <small>' . array_shift($discs) . '</small>
                                    </div>
                                    <div class="col-sm-1">
                                        <small>' . array_shift($gst) . '</small>
                                    </div>
                                    <div class="col-sm-1">
                                        <small>' . array_shift($billAmount) . '</small>
                                    </div>
                                    <div class="col-sm-1">
                                        <small>' . array_shift($returnQty) . '</small>
                                    </div>
                                    <div class="col-sm-1 text-end">
                                        <small>' . array_shift($refunds) . '</small>
                                    </div>
                                </div>';
                }
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
            <button class="btn btn-primary shadow mx-2" onclick="history.back()">Go Back</button>
            <button class="btn btn-primary shadow mx-2" onclick="window.print()">Print Bill</button>
        </div>
        </div>
    <?php
    } else {
    ?>

        <div class="container mt-3">
            <h2></h2>
            <div class="mt-4 p-5 bg-primary text-white rounded text-center">
                <h1>Refresh Not Allowed</h1>
                <p> You Can Find Your Generated Return Bills on <a href="/medicy.in/pharmacist/sales-returns.php" class="text-light">Returns</a> Page. Refresh is Not Allowed on This
                    Page.
                </p>
            </div>
        </div>



    <?php
    }
    ?>
    <script>
        // if (window.history.replaceState) {
        //     window.history.replaceState(null, null, window.location.href);
        // }
    </script>
    <script src="../../../js/bootstrap-js-5/bootstrap.js"></script>
</body>

</html>