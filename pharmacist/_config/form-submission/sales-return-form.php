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
        $itemID   = $_POST['itemId'];
        $procutId = $_POST['productId'];
        $batchNo    = $_POST['batchNo'];
        // print_r($batchNo);
        $expdates   = $_POST['expDate'];
        $setOf    = $_POST['setof'];
        $qtys       = $_POST['qty'];

        $mrp        = $_POST['mrp'];
        $disc      = $_POST['disc'];
        $gst        = $_POST['gst'];
        $taxableArray   = $_POST['taxable'];
        $returnQty  = $_POST['return'];
        $perItemRefund    = $_POST['refundPerItem'];

        // --------------------------------------
        $itemWeatage = preg_replace('/[a-z]/','',$setOf);
        $unitType = preg_replace('/[0-9]/','',$setOf);
        
        // --------------------------------------

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

        // =============================== PATIENT DETAILS ================================
        $patientData = $StockOut->stockOutDisplayById($invoice);
        if($patientData[0]['customer_id'] == 'Cash Sales'){
            $patientName = 'Cash Sales';
            $contactNumber = "";
        }else{
            $patient = $Patients->patientsDisplayByPId($patientData[0]['customer_id']);
            $patientName = $patient[0]['name'];
            $contactNumber = $patient[0]['phno'];
        }

        //=================================================================================

        // echo "<br> Item id : "; print_r($itemID);
        // echo "<br> Batch no : "; print_r($batchNo);
        // echo "<br> expiry Date : "; print_r($expdates);
        // echo "<br> Stef of : "; print_r($setOf);
        // echo "<br> Unit Type : "; print_r($unitType);
        // echo "<br> Item weatage : "; print_r($itemWeatage);
        // echo "<br> Qantity : "; print_r($qtys);
        // echo "<br> MRP : "; print_r($mrp);
        // echo "<br> Discount : "; print_r($disc);
        // echo "<br> GST : "; print_r($gst);
        // echo "<br> Taxable array : "; print_r($taxableArray);
        // echo "<br> Return QTY : "; print_r($returnQty);
        // echo "<br> Refund Amount : "; print_r($perItemRefund);
        // echo "<br><br> ============ END OF ARRAY ============ <br>";
        // echo "<br> Invoice No : "; print_r($invoice);
        // echo "<br> Bill Date :"; print_r($billDate);
        // echo "<br> Return Date : "; print_r($returnDate);
        // echo "<br> Items : "; print_r($items);
        // echo "<br> Refund Mode : "; print_r($refundMode);
        // echo "<br> Total Qtys : "; print_r($totalQtys);
        // echo "<br> Total Gst Amount : "; print_r($gstAmount);
        // echo "<br> Refund amount : "; print_r($refundAmount);
        // echo "<br> Added By : "; print_r($added_by);
        // echo "<br> Invoice Id : "; print_r($invoiceId);
        // echo "<br><br><br><br>";

        $sold     = $StockOut->stockOutDisplayById($invoiceId);

        $status = "ACTIVE";

        $returned = $SalesReturn->addSalesReturn($invoiceId, $sold[0]['customer_id'], $billDate, $returnDate, $items, $gstAmount, $refundAmount, $refundMode, $status, $added_by);
        // $returned = true;

        if ($returned === true) {

            // foreach ($itemId as $itemId) {
            for($i = 0; $i<count($itemID); $i++){

                // echo "<br><br><br>";
                // echo $itemID[$i], "<br>";
                $unit = $setOf[$i];
                // echo $unit; echo "<br>";
                $itemWeatage = preg_replace('/[a-z]/','',$unit);
                $unitType = preg_replace('/[0-9]/','',$unit);
                // echo $unitType; echo "<br>";
                // echo $itemWeatage; echo "<br><br><br>";
                
                $addedBy = '';

                $attribute = 'invoice_id'; 
                $SalesReturnId = $SalesReturn->selectSalesReturn($attribute, $invoiceId);
                // print_r($SalesReturnId); echo "<br>";

                // echo "$itemID[$i]<br>";
                // echo "$batchNo[$i]<br>";
                // echo "$setOf[$i]<br>";
                // echo "$expdates[$i]<br>";
                // echo "$disc[$i]<br>";
                // echo "$gst[$i]<br>";
                // echo "$taxableArray[$i]<br>";
                // echo "$returnQty[$i]<br>";
                // print_r(array_shift($_POST['refundPerItem'])); echo "<br><br><br>";
               
                // ========================= ADD TO SALES RETURN DETAILS =============================
                
                $addSalesReturndDetails = $SalesReturn->addReturnDetails($SalesReturnId[0]['id'], $itemID[$i], $procutId[$i], $batchNo[$i], $setOf[$i], $expdates[$i], $disc[$i], $gst[$i], $taxableArray[$i], $returnQty[$i], $perItemRefund[$i]) ;

                // ============= CURRENT STOCK UPDATE AREA ===========================
                
                $currentStockDetaisl = $CurrentStock->showCurrentStocById($itemID[$i]);

                foreach($currentStockDetaisl as $currentStockDetaisl){
                    $currentStockItemUnit = $currentStockDetaisl['unit'];
                    if($currentStockItemUnit == 'tab' || $currentStockItemUnit == 'cap'){
                        $curretnStockQty = $currentStockDetaisl['loosely_count'];
                        $UpdatedLooseQty = intval($curretnStockQty) + intval(array_shift($_POST['return']));
                        $UpdatedQty = intdiv(intval($UpdatedLooseQty), intval($itemWeatage));
                    }else{
                        $curretnStockQty = $currentStockDetaisl['qty'];
                        $UpdatedQty = intval($curretnStockQty) + intval(array_shift($_POST['return']));
                        $UpdatedLooseQty = 0;
                    }
                }

                // echo "<br>Current Stock item quantity : $curretnStockQty";
                // echo "<br>CURRENT STOCK UPDATED LOOSE QTY : $UpdatedLooseQty";
                // echo "<br>CURRENT STOCK UPDATED QTY : $UpdatedQty";

                // ========================= CURRENT STOCK UPDATE STRING ============================

                $updateCurrentStock = $CurrentStock->updateStockByItemId($itemID[$i], $UpdatedQty, $UpdatedLooseQty);
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
                                <?php echo $contactNumber; ?>
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
                        <small><b>Disc(%)</b></small>
                    </div>
                    <div class="col-sm-1">
                        <small><b>GST(%)</b></small>
                    </div>
                    <div class="col-sm-1">
                        <small><b>P.QTY</b></small>
                    </div>
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
                //$countProduct = count($products);
                // print_r($products);
                // print_r($batchNo);
                // print_r($invoiceId);

                // foreach ($itemID as $itemID) {
                for ($i = 0; $i<count($itemID); $i++){

                    $slno++;

                    $itemDetails = $CurrentStock->showCurrentStocById($itemID[$i]);
                    $productDetails = $Products->showProductsById($itemDetails[0]['product_id']);
                    $productName = $productDetails[0]['name'];
                    
                    echo '
                                <div class="row">
                                    <div class="col-sm-1 text-center">
                                        <small>' . $slno . '</small>
                                    </div>
                                    <div class="col-sm-2 ">
                                        <small>' . substr($productName, 0, 15) . '</small>
                                    </div>
                    
                                    <div class="col-sm-1">
                                        <small>' . $setOf[$i] . '</small>
                                    </div>
                                    <div class="col-sm-1">
                                        <small>' .  $batchNo[$i] . '</small>
                                    </div>
                                    <div class="col-sm-1">
                                        <small>' . $expdates[$i] . '</small>
                                    </div>
                                    <div class="col-sm-1">
                                        <small>' . $disc[$i] . '</small>
                                    </div>
                                    <div class="col-sm-1">
                                        <small>' . $gst[$i] . '</small>
                                    </div>
                                    <div class="col-sm-1">
                                        <small>' . $qtys[$i] . '</small>
                                    </div>
                                    <div class="col-sm-1">
                                        <small>' . $returnQty[$i] . '</small>
                                    </div>
                                    <div class="col-sm-1" style="text-align: right;">
                                        <small>' . $taxableArray[$i] . '</small>
                                    </div>
                                    <div class="col-sm-1" style="text-align: right;">
                                        <small>' . $perItemRefund[$i]. '</small>
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