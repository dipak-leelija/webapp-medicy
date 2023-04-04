<?php
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
    if (isset($_POST['sales-return'])) {

        //-------Array elements------------------
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
        //---------------------------------------

        $invoice        = $_POST['invoice'];
        $billDate       = $_POST['purchased-date'];
        $billDate       = date('Y-m-d', strtotime($billDate));
        $returnDate     = $_POST['return-date'];
        $items          = $_POST['total-items'];
        $refundMode     = $_POST['refund-mode'];
        $totalQtys      = $_POST['total-qty'];
        $gstAmount      = $_POST['gst-amount'];
        $refundAmount   = $_POST['refund-amount'];
        $added_by = $_SESSION['employee_username'];
        $invoiceId = str_replace("#", '', $invoice);


        // echo "<br>"; print_r($products);
        // echo "<br>"; print_r($batchNo);
        // echo "<br>"; print_r($expdates);
        // echo "<br>"; print_r($weatage);
        // echo "<br>"; print_r($qtys);
        // echo "<br>"; print_r($mrp);
        // echo "<br>"; print_r($discs);
        // echo "<br>"; print_r($gst);
        // echo "<br>"; print_r($totalGSt);
        // echo "<br>"; print_r($returnQty);
        // echo "<br>"; print_r($refunds);
        // echo "<br>"; print_r($billAmount);
        // echo "<br>"; print_r($invoice);
        // echo "<br>"; print_r($billDate);
        // echo "<br>"; print_r($billDate);
        // echo "<br>"; print_r($returnDate);
        // echo "<br>"; print_r($items);
        // echo "<br>"; print_r($refundMode);
        // echo "<br>"; print_r($totalQtys);
        // echo "<br>"; print_r($gstAmount);
        // echo "<br>"; print_r($refundAmount);
        // echo "<br>"; print_r($added_by);
        // echo "<br>"; print_r($invoiceId);
        

       

        $sold     = $StockOut->stockOutDisplayById($invoiceId);
        // echo "<br>"; print_r($sold);
        // echo "Stock Out tabel data:- <br>"; print_r($sold); echo "<br><br>";
        // $patient  = $Patients->patientsDisplayByPId($sold[0]['customer_id']);
        // echo "Patient_details tabel data:- <br>"; print_r($patient); echo "<br><br>";
        // $customerId = $sold[0]['customer_id'];
        // echo $customerId;
        
        if ($sold[0]['customer_id'] == "Cash Sales") {
            $patientName = "Cash Sales";
            $patientPhNo = " ";
        } else {
            $patient  = $Patients->patientsDisplayByPId($sold[0]['customer_id']);
            $patientName = $patient[0]['name'];
            $patientPhNo = $patient[0]['phno'];
        }

        $status = "ACTIVE";

        $returned = $SalesReturn->addSalesReturn($invoiceId, $sold[0]['customer_id'], $billDate, $returnDate, $items, $gstAmount, $refundAmount, $refundMode, $status, $added_by);
        
        $currentTime = date('Y-m-d G:i:s'); //holding current date time for fetchin data of sales return table

        $checkReturned = $SalesReturn->seletSalesReturnByDateTime($invoiceId, $sold[0]['customer_id'], $currentTime);
        // echo "<br><br>"; print_r($checkReturned);
        $SalesReturnId = $checkReturned[0]['id'];
        // echo "<br><br> Sales return id : "; print_r($SalesReturnId);
        
        if ($returned === true) {

            $count = count($_POST['productId']);
            //echo "<br>check2 - > $count<br>";
            $productId = $_POST['productId'];
            //echo "check 3 - > "; print_r($productId); echo "<br>";

            foreach ($_POST['productId'] as $productId) {

                //$i = 0;
                $batch = array_shift($_POST['batchNo']);
                $qty   = array_shift($_POST['qty']);
                $returnQTY  = array_shift($_POST['return']);
                $addedBy = '';
                // echo "<br>Prodcut id : <br>$productId<br>";
                // echo "<br>Batch no :<br>$batch<br>";
                // echo "<br>Purchase Qty : <br>$qty<br>";
                // echo "<br>return Qty :<br>$returnQTY<br>";
                

                $success = $SalesReturn->addReturnDetails($SalesReturnId, $invoiceId, $productId, $batch, array_shift($_POST['setof']), array_shift($_POST['expDate']), $qty, array_shift($_POST['disc']), array_shift($_POST['gst']), array_shift($_POST['billAmount']), $returnQTY, array_shift($_POST['refund']), $added_by);

                // now insert into current stock 
                $stock = $CurrentStock->checkStock($productId, $batch);
                // echo "<br><br>current stock detials ========>"; print_r($stock); echo "<br>";


                $salesDetails = $StockOut->stockOutDetailsSelect($invoiceId, $productId, $batch);
                // echo "<br><br>Sales detials ========>"; print_r($salesDetails); echo "<br>";
                // echo "<br>$productId<br>$batch<br>";
                
                $pDetails = $StockInDetails->showStockInDetailsByTable('product_id', 'batch_no', $productId, $batch);
                //echo "<br><br>Stock in detials ========>"; print_r($pDetails); echo "<br>";
                

                if (count($stock) == 0 || $stock == '') {
                    // (float) filter_var( $uWeightage, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION )
                    if ($pDetails[0]['unit'] == 'cap' || $pDetails[0]['unit'] == 'tab') {
                        if($salesDetails[0]['qty'] == '0'){
                            $looselyCount = $returnQTY;
                            $singlePrice  = $pDetails[0]['base'] + ($pDetails[0]['gst'] / 100 * $pDetails[0]['base']);
                            $looselyPrice = $singlePrice * $looselyCount;
                        }else{
                            $looselyCount = $returnQTY * $pDetails[0]['weightage'];
                            $singlePrice  = $pDetails[0]['base'] + ($pDetails[0]['gst'] / 100 * $pDetails[0]['base']);
                            $looselyPrice = $singlePrice * $looselyCount;
                        }
                    } else {

                        $looselyCount = 0;
                        $looselyPrice = 0;

                    }
                    
                    $CurrentStock->addCurrentStock($productId, $batch, $pDetails[0]['exp_date'], $pDetails[0]['distributor_bill'], $looselyCount, $looselyPrice, $pDetails[0]['weightage'], $pDetails[0]['unit'], $qty, $pDetails[0]['mrp'], $pDetails[0]['ptr'], $pDetails[0]['gst'], $addedBy);

                } else {
                    if(count($pDetails) == 0 || $pDetails == ''){
                        if($pDetails[0]['unit'] == 'cap' || $pDetails[0]['unit'] == 'tab'){
                            $newQuantity = $stock[0]['qty'] + $returnQTY;
                            $newLCount = $stock[0]['loosely_count'] + ($stock[0]['weightage'] * $returnQTY);
                        }else{
                            $newQuantity = $stock[0]['qty'] + $returnQTY;
                            $newLCount = '0';
                        }
                    } else{
                        if($pDetails[0]['unit'] == 'cap' || $pDetails[0]['unit'] == 'tab'){
                            if($salesDetails[0]['qty'] == '0'){
                                $newLCount = $stock[0]['loosely_count'] + $returnQTY;
                                if($returnQTY > $pDetails[0]['weightage']){
                                    $newQuantity = $stock[0]['qty'] + ($returnQTY % $pDetails[0]['weightage']);
                                }else{
                                    $newQuantity = $stock[0]['qty'];
                                }
                            }else{
                                $newQuantity = $stock[0]['qty'] + $returnQTY;
                                $newLCount = $stock[0]['loosely_count'] + ($returnQTY * $pDetails[0]['weightage']);
                            }
                        }else{
                            $newQuantity = $stock[0]['qty'] + $returnQTY;
                            $newLCount = '0';
                        }
                    }
                    // echo "<br>$productId<br>$batch<br>$newQuantity<br>$newLCount";
                    $CurrentStock->updateStock($productId, $batch, $newQuantity, $newLCount);
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
}
?>

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
    <?php if (isset($_POST['sales-return'])) { ?>
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
                            <small><b>Patient: </b> <?php echo $patientName; ?>, Contact:
                                <?php echo $patientPhNo; ?>
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
                //$countProduct = count($products);
                //print_r($products);
                //print_r($batchNo);
                foreach ($products as $product){
                 //for($i = 0; $i<$countProduct; $i++) {
                    $slno++;
                    $i = 0;
                    if ($slno > 1) {
                        echo '<hr style="width: 98%; border-top: 1px dashed #8c8b8b; margin: 0 10px 0; align-items: center;">';
                    }

                    $itemQty = array_shift($qtys);
                    $mrpOnQty = array_shift($mrp);
                    $mrpOnQty = $mrpOnQty * $itemQty;

                    $salesDetails = $StockOut->stockOutDetailsSelect($invoiceId, $product, $batchNo[$i]);
                    //print_r($batchNo[$i]);

                    if($salesDetails != null){
                        if($salesDetails[0]['qty'] == 0){
                            $string = "(L)";
                            $itemQty = $itemQty.$string;
                            $returnQty = $returnQty[$i].$string;
                        }else{
                            $itemQty = $itemQty;
                            $returnQty = $returnQty[$i];
                        }
                    }
                    
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
                                        <small>' . $returnQty. '</small>
                                    </div>
                                    <div class="col-sm-1 text-end">
                                        <small>' . array_shift($refunds) . '</small>
                                    </div>
                                </div>';
                                $i++;
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
            <!-- <h2></h2> -->
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