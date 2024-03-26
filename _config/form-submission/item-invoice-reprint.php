<?php
require_once dirname(dirname(__DIR__)) . '/config/constant.php';
require_once ROOT_DIR . '_config/sessionCheck.php'; //check admin loggedin or not

require_once CLASS_DIR . 'dbconnect.php';
require_once ROOT_DIR . '_config/user-details.inc.php';
require_once ROOT_DIR . '_config/healthcare.inc.php';
require_once CLASS_DIR . 'hospital.class.php';
require_once CLASS_DIR . 'stockOut.class.php';
require_once CLASS_DIR . 'products.class.php';
require_once CLASS_DIR . 'itemUnit.class.php';
require_once CLASS_DIR . 'packagingUnit.class.php';
require_once CLASS_DIR . 'manufacturer.class.php';
require_once CLASS_DIR . 'patients.class.php';

require_once CLASS_DIR . 'encrypt.inc.php';


$invoiceId = $_GET['id'];

//  INSTANTIATING CLASS
$StockOut        = new StockOut();
$Products        = new Products();
$ItemUnit        = new ItemUnit();
$PackagingUnits  = new PackagingUnits();
$Manufacturer    = new Manufacturer();
$Patients        = new Patients;
$ClinicInfo  = new HealthCare;
// echo $healthCareLogo;

if (isset($_GET['id']) && !empty($_GET['id'])) {

    $invoiceId = url_dec($_GET['id']);
    // echo $invoiceId;
    $stockOut  = $StockOut->stockOutDisplayById($invoiceId);
    // print_r($stockOut);
    foreach ($stockOut as $stockOut) {
        $invoiceId      = $stockOut['invoice_id'];
        $customerId     = $stockOut['customer_id'];
        $reffby         = $stockOut['reff_by'];
        $totalMrp       = $stockOut['mrp'];
        $totalGSt       = $stockOut['gst'];
        $billAmout      = $stockOut['amount'];
        $pMode          = $stockOut['payment_mode'];
        $billdate       = $stockOut['bill_date'];

        $details = $StockOut->stockOutDetailsBY1invoiveID($invoiceId);
        $details = json_decode($details, true);
        // print_r($details);
    }

}else {
    echo 'Invalid Request!'; 
    die("404");
}

if ($customerId != 'Cash Sales') {
    $patient = json_decode($Patients->patientsDisplayByPId($customerId));

    $patientName = $patient->name;
    $patientPhno = $patient->phno;
    $patientAge  = $patient->age;

    // $patientElement = "<p style='margin-top: -3px; margin-bottom: 0px;'><small><b>Patient: </b>  $patientName, <b>Age:</b> $patientAge </small></p><p style='margin-top: -5px; margin-bottom: 0px;'><small><b>M:</b> $patientPhno </small></p>"";
} else {
    // $patientElement = "<p style='margin-top: -3px; margin-bottom: 0px;'><small><b>Patient: </b>  $customerId</small></p>"";
    $patientName = 'Cash Sales';
    $patientPhno = '';
    $patientAge = '';
}



$selectClinicInfo = json_decode($ClinicInfo->showHealthCare($adminId));
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
    <title>Medicy Health Care Lab Test Bill</title>
    <link rel="stylesheet" href="<?php echo CSS_PATH ?>bootstrap 5/bootstrap.css">
    <link rel="stylesheet" href="<?php echo CSS_PATH ?>custom/test-bill.css">

</head>


<body>
    <div class="custom-container">
        <div class="custom-body <?php if ($pMode != 'Credit') {
                                    echo "paid-bg";
                                } ?>">
            <div class="card-body ">
                <div class="row">
                    <div class="col-sm-1">
                        <img class="float-end" style="height: 55px; width: 58px;" src="<?= LOCAL_DIR . $pharmacyLogo ?>" alt="Medicy">
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
                        <p class="my-0"><b>Invoice</b></p>
                        <p style="margin-top: -5px; margin-bottom: 0px;"><small>Bill id:
                                #<?php echo $invoiceId; ?></small></p>
                        <p style="margin-top: -5px; margin-bottom: 0px;"><small>Payment: <?php echo $pMode; ?></small>
                        </p>
                        <p style="margin-top: -5px; margin-bottom: 0px;"><small>Date: <?php echo $billdate; ?></small>
                        </p>
                    </div>
                </div>
            </div>
            <hr class="my-0" style="height:0px; background: #000000; border: #000000;">
            <div class="row my-0">
                <div class="col-sm-6 ms-4 my-0">
                    <p class="text-start" style="margin-top: -3px; margin-bottom: 0px;"><small><b>Refered By:</b>
                            <?php echo $reffby; ?></small></p>
                </div>
            </div>
            <hr class="my-0" style="height:2px;">

            <div class="row">
                <!-- table heading -->

                <div class="col-sm-1 text-center" style="width: 5rem;">
                    <small><b>SL.</b></small>
                </div>
                <div class="col-sm-2" style="width: 12rem;">
                    <small><b>Name</b></small>
                </div>
                <div class="col-sm-1">
                    <small><b>Manuf.</b></small>
                </div>
                <div class="col-sm-1" style="width: 7rem;">
                    <small><b>Batch</b></small>
                </div>
                <div class="col-sm-1">
                    <small><b>Exp.</b></small>
                </div>
                <div class="col-sm-1 text-end" style="width: 7rem;">
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

                    //=========================
                    $checkTable = json_decode($Products->productExistanceCheck($detail['product_id']));

                    if ($checkTable->status) {
                        $table = 'products';
                    } else {
                        $table = 'product_request';
                    }
                    //=========================

                    $productResponse = json_decode($Products->showProductsByIdOnTableName($detail['product_id'], $table));

                    $product = $productResponse->data;
                    // print_r($product);

                    $packQty = $product->unit_quantity;

                    if (isset($product->manufacturer_id)) {
                        $manuf = json_decode($Manufacturer->manufacturerShortName($product->manufacturer_id));

                        $manufacturerName = $manuf->status == 1 ? $manuf->data : '';
                    } else {
                        $manufacturerName = '';
                    }


                    $itemunit = $ItemUnit->itemUnitName($product->unit);
                    $packUnit = $PackagingUnits->packagingTypeName($product->packaging_type);

                    $weatage = "$itemunit of $packUnit";

                    $slno++;
                    if ($slno > 1) {
                        echo '<hr style="width: 98%; border-top: 1px dashed #8c8b8b; margin: 0 10px 0; align-items: center;">';
                    }

                    // $itemQty = $detail['loosely_count']/$packQty;

                    $itemQty = intdiv($detail['loosely_count'], $packQty);

                    // ===================================================

                    if ($detail['loosely_count'] != 0) {
                        $itemSellQty = $detail['loosely_count'] / $detail['weightage'];
                        
                        if(!is_int($itemSellQty)){
                            $itemSellQty = $detail['loosely_count'] . ' ' . $detail['unit'];
                        }

                    } else {
                        $itemSellQty = $detail['qty'];
                    }

                    // ===================================================


                    echo '<div class="col-sm-1 text-center" style="width: 5rem;">
                                <small>' . $slno . '</small>
                            </div>
                            <div class="col-sm-2" style="width: 12rem;">
                                <small>' . substr($detail['item_name'], 0, 15) . '</small>
                            </div>
                            <div class="col-sm-1">
                                <small>' . $manufacturerName . '</small>
                            </div>
                            <div class="col-sm-1" style="width: 7rem;">
                                <small>' . $detail['batch_no'] . '</small>
                            </div>
                            <div class="col-sm-1">
                                <small>' . $detail['exp_date'] . '</small>
                            </div>
                            <div class="col-sm-1 text-end" style="width: 7rem;">
                                <small>' . $itemSellQty . '</small>
                            </div>
                            <div class="col-sm-1 text-end">
                                <small>' . $detail['mrp'] . '</small>
                            </div>
                            <div class="col-sm-1 text-end">
                            <small>' . (isset($detail['discount']) ? $detail['discount'] : '') . '</small>
                            </div>
                            <div class="col-sm-1 text-end">
                                <small>' . $detail['gst'] . '</small>
                            </div>
                            <div class="col-sm-1 text-end">
                                <small>' . $detail['amount'] . '</small>
                            </div>';
                }
                ?>

            </div>

            <div class="footer">
                <hr calss="my-0" style="height: 2px;">
                <!-- table total calculation -->

                <div class="row my-0">
                    <div class="col-5">
                        <div class="row">
                            <div class="col-2 ms-4">
                                <b><small>Patient </small></b><br>
                                <b><small>Age</small></b><br>
                                <b><small>Contact</small></b>
                            </div>
                            <div class="col-9">
                                <p class="text-start mb-0"><small><?= ' :   ' . $patientName; ?></small></p>
                                <p class="text-start mb-0"><small><?= ' :   ' . $patientAge; ?></small></p>
                                <p class="text-start"><small><?= ' :   ' . $patientPhno; ?></small></p>
                            </div>
                        </div>
                    </div>
                    <div class="col-7 border-start border-dark">
                        <div class="col-12">
                            <div class="row">
                                <div class="col-sm-2">
                                    <p class="m-0"><small>CGST</small></p>
                                    <p class="m-0"><small>SGST</small></p>
                                    <p><small>Total GST:</small></p>
                                </div>
                                <div class="col-sm-4">
                                    <p class="m-0">
                                        <small>: ₹<?php echo $totalGSt / 2; ?></small>
                                    </p>
                                    <p class="m-0">
                                        <small>: ₹<?php echo $totalGSt / 2; ?></small>
                                    </p>
                                    <p class="m-0">
                                        <small>: ₹<?php echo floatval($totalGSt); ?></small>
                                    </p>
                                </div>
                                <div class="col-sm-2">
                                    <p class="m-0"><small>MRP</small></p>
                                    <b><p class="m-0"><small>Payble</small></p></b>
                                    <p><small>You Saved</small></p>
                                </div>
                                <div class="col-sm-4">
                                    <p class="m-0">
                                        <small>: ₹<?php echo floatval($totalMrp); ?></small>
                                    </p>
                                    <p class="m-0">
                                        <b><small>: ₹<?php echo floatval($billAmout); ?></small></b>
                                    </p>
                                    <p class="m-0">
                                        <small>: ₹<?php echo $totalMrp - $billAmout; ?></small>
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!--<div class="row my-0">
                    <div class="col-6 border-start border-dark">
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
                                        <small>₹<?php echo $totalMrp - $billAmout; ?></small>
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div> -->

            </div>
            <hr style="height: 1px; margin-top: 0px;">
        </div>
    </div>
    <div class="justify-content-center print-sec d-flex my-5">
        <button class="btn btn-primary shadow mx-2" onclick="history.back()">Go Back</button>
        <button class="btn btn-primary shadow mx-2" onclick="window.print()">Print Bill</button>
    </div>
    </div>
</body>
<script src="<?php echo JS_PATH ?>bootstrap-js-5/bootstrap.js"></script>

</html>