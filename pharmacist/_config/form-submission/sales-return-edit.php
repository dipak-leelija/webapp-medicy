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
    if (isset($_POST['sales-return-edit'])) {

        $returnId   = $_POST['salesreturnid'];
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
        print_r($refunds);
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


        //$returnId == sales_return_id, (id of stock return tabel).....
        //echo $returnId, "<br><br>", $invoiceId, "<br><br>";

        // fetching sales return data from sales return tabel------------------
        $checkStockOut = $SalesReturn->salesReturnByID($returnId, $invoiceId);
        //print_r($checkStockOut);
        //echo "<br><br>";

        $PatientsName = $checkStockOut[0]['patient_id'];
        if($PatientsName == 'Cash Sales'){
            $patientNm = "Cash Sales";
            $patientPNo = " ";
        }
        else{
            $patientNm = $patient[0]['name'];
            $patientPNo = $patient[0]['phno'];
        }

        foreach ($checkStockOut as $valueCheck) {
            $checkitems = $valueCheck['items'];
            $checkgstamount = $valueCheck['gst_amount'];
            $checkrefundamount = $valueCheck['refund_amount'];
            $checkrefundmode = $valueCheck['refund_mode'];
            $checkReturnDate = $valueCheck['return_date'];
        }

        // check edited data with stock return table and update edit data in it -------------------RD 

        if ($gstAmount != $checkgstamount || $refundAmount != $checkrefundamount || $refundMode != $checkrefundmode || $returnDate != $checkReturnDate) {
            if ($gstAmount != $checkgstamount && $refundAmount != $checkrefundamount) {
                $gstAmount = $checkgstamount + $gstAmount;
                $refundAmount = $checkrefundamount + $refundAmount;
                $returned = $SalesReturn->updateSalesReturn($returnId, $returnDate, $gstAmount, $refundAmount, $refundMode, $added_by);
            } else {
                // $gstAmount = $checkgstamount + $gstAmount;
                // $refundAmount = $checkrefundamount + $refundAmount;
                $returned = $SalesReturn->updateSalesReturn($returnId, $returnDate, $gstAmount, $refundAmount, $refundMode, $added_by);
            }
        } elseif ($gstAmount == $checkgstamount && $refundAmount == $checkrefundamount && $refundMode == $checkrefundmode && $returnDate == $checkReturnDate) {
?>
            <script src="../../../js/sweetAlert.min.js"></script>
            <script>
                swal("Error", "Can't edit update with same data!", "error");
            </script>
<?php

            $returned = true;
        }
        // ------------------------------------------------------------------------------------------

        //print_r($_POST['productId']);
        //echo "<br><br>";


        // now check and update stock return details table with edit data ---------------------- RD
        if ($returned == true) {

            foreach ($_POST['productId'] as $productId) {

                $ProductId = $productId;
                //fetching sales return details from sales retur details table---------
                //echo "salse-return-details table========";
                $checkStockOutDetails = $SalesReturn->salesReturnIdandProductId($returnId, $ProductId);
                //print_r($checkStockOutDetails);
                //echo "<br><br>";

                $salesReturnId = $returnId;
                $invoiceId = $invoiceId;
                $productID = $productId;
                $batchNo = array_shift($_POST['batchNo']);

                //echo "<br><br>";

                $discountPercent = array_shift($_POST['disc']);
                $gstPercent = array_shift($_POST['gst']);
                $gstAmount = array_shift($_POST['billAmount']);
                $returnQty = array_shift($_POST['return']);
                $refundAmount = array_shift($_POST['refund']);
                $addedBy = $added_by;
                $addedOn = $billDate;

                // echo "Sales return Id : $salesReturnId";
                // echo "<br><br>";
                // echo "Invoice Id : $invoiceId";
                // echo "<br><br>";
                // echo "Product Id : $productID";
                // echo "<br><br>";
                // echo "Batch no : $batchNo";
                // echo "<br><br>";
                // echo "Discount percent : $discountPercent";
                // echo "<br><br>";
                // echo "GST percent : $gstPercent";
                // echo "<br><br>";
                // echo "GST amount : $gstAmount";
                // echo "<br><br>";
                // echo "Return QTY : $returnQty";
                // echo "<br><br>";
                // echo "Refund Amount : $refundAmount";
                // echo "<br><br>";

                $success = $SalesReturn->updateSalesReturnDetails($salesReturnId, $invoiceId, $productID, $batchNo,  $discountPercent, $gstPercent, $gstAmount, $returnQty, $refundAmount, $addedBy, $addedOn);

                // now insert into current stock 
                $stock = $CurrentStock->checkStock($productId, $batchNo);
                //echo "current stock details : <br>";
                //print_r($stock);

                // if($returnQty > $checkStockOutDetails[0]['return']){
                //     $returnQuantity = $returnQty - $checkStockOutDetails[0]['return']; //current stock -count
                // }
                // else if($returnQty < $checkStockOutDetails[0]['return']){
                //     $returnQuantity = $checkStockOutDetails[0]['return'] - $returnQty; // current stock +count
                // }

                if (count($stock) != 0 || $stock != '' && $success == true) {
                    
                    //fetching pharmacy invoice details
                    $invoiceDetail = $StockOut->stockOutSelect($invoiceId, $productID, $batchNo);
                    
                    if ($invoiceDetail[0]['qty'] == 0) {
                        if ($stock[0]['unit'] == 'cap' || $stock[0]['unit'] == 'tab') {
                            //only loose sales count area
                            $QTY = $returnQty % $stock[0]['weightage'];
                            $prevQTY = $checkStockOutDetails[0]['return'] % $stock[0]['weightage'];
                            $looselyCount = $returnQty - ($QTY * $stock[0]['weightage']);
                            $prevLooselyCount = $checkStockOutDetails[0]['return'] - ($prevQTY * $stock[0])['weightage'];
                            $updatedQTY = $stock[0]['qty'] + (- ($QTY - $prevQTY));
                            $updateLooselyCount = $stock[0]['loosely_count'] + ( - ($looselyCount - $prevLooselyCount));
                        } 
                    } else {
                        if ($stock[0]['unit'] == 'cap' || $stock[0]['unit'] == 'tab') {
                            $looselyCount = $returnQty * $stock[0]['weightage'];
                            $prevQTY = $checkStockOutDetails[0]['return'];
                            $prevLooselyCount = $checkStockOutDetails[0]['return'] * $stock[0]['weightage'];
                            $updatedQTY = $stock[0]['qty'] + (- ($returnQty - $prevQTY));
                            $updateLooselyCount = $stock[0]['loosely_count'] + (- ($looselyCount - $prevLooselyCount));
                        } else {
                            $updatedQTY = $stock[0]['qty'] + (- ($returnQty - $checkStockOutDetails[0]['qty']));
                            $updateLooselyCount = 0;

                        }
                    }
                }else {
                    $updatedQTY = $stock[0]['qty'] + $qty;

                    if ($stock[0]['unit'] == 'cap' || $stock[0]['unit'] == 'tab') {
                        $updateLooselyCount   = $stock[0]['weatage'] * $updatedQTY;
                    } else {
                        $updateLooselyCount = 0;
                    }
                    
                }
                
                $CurrentStock->updateStock($productId, $batchNo, $updatedQTY, $updateLooselyCount);
            }
           
        }

        // $Doctors->showDoctorById($docId);

    }

    $totalRefundAmount = 0;
    for($i = 0; $i<count($refunds); $i++){
        $totalRefundAmount = $totalRefundAmount + $refunds[$i]; 
    }

    //print_r($batchNo);
    // print_r();
    // print_r();
    // print_r();
    // print_r();
    // print_r();
    // print_r();
    // print_r();



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
                                        <small>' . $batchNo . '</small>
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
                                        <small>' . $returnQty . '</small>
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
                                        <small><b>₹<?php echo floatval($totalRefundAmount); ?></b></small>
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
    <script src="../../../js/sweetAlert.min.js"></script>
</body>

</html>