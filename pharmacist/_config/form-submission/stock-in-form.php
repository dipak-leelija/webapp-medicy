<?php

require_once '../../_config/sessionCheck.php';
require_once '../../../php_control/stockIn.class.php';
require_once '../../../php_control/stockInDetails.class.php';
require_once '../../../php_control/currentStock.class.php';
require_once '../../../php_control/distributor.class.php';
require_once '../../../php_control/products.class.php';
require_once '../../../php_control/manufacturer.class.php';
require_once '../../../php_control/packagingUnit.class.php';

$StockIn = new StockIn();
$StockInDetails = new StockInDetails();
$CurrentStock = new CurrentStock();
$distributor = new Distributor();
$Session = new SessionHandler();
$Products = new Products();
$Manufacturer = new Manufacturer();
$PackagingUnits = new PackagingUnits();

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    if (isset($_POST['stock-in'])) {

        $distributorName      = $_POST['distributor-name'];
        $distributorDetial = $distributor->selectDistributorByName($distributorName);
        foreach ($distributorDetial as $distDeta) {
            $distributorId      = $distDeta['id'];
            $distAddress        = $distDeta['address'];
            $distPIN            = $distDeta['area_pin_code'];
            $distContact        = $distDeta['phno'];
        }

        $updtBatchNoArry    = $_POST['batchNo'];
        // print_r($updtBatchNoArry);
        $distributorBill    = $_POST['distributor-bill'];

        $Items              = $_POST['items'];
        $items              = count($_POST['productId']);
        $totalQty           = $_POST['total-qty'];

        $billDate           = date_create($_POST['bill-date-val']);
        $billDate           = date_format($billDate, "d-m-Y");

        $dueDate            = date_create($_POST['due-date-val']);
        $dueDate            = date_format($dueDate, "d-m-Y");

        $paymentMode        = $_POST['payment-mode-val'];
        $pMode              = $paymentMode;
        $totalGst           = $_POST['totalGst'];
        $amount             = $_POST['netAmount'];
        $addedBy            = $_SESSION['employee_username'];
        $BatchNo            = $_POST['batchNo'];
        $MFDCHECK           = $_POST['mfdDate'];
        $expDate            = $_POST['expDate'];

        $crrntDt = date("d-m-Y");
    } elseif (isset($_POST['update'])) {

        $stockIn_Id         = $_POST['stok-in-id'];
        $distributorId      = $_POST['distributor-id'];

        $distributorDetial = $distributor->showDistributorById($distributorId);
        foreach ($distributorDetial as $distDeta) {
            $distributorName      = $distDeta['name'];
            $distAddress          = $distDeta['address'];
            $distPIN              = $distDeta['area_pin_code'];
            $distContact          = $distDeta['phno'];
        }

        $updtBatchNoArry    = $_POST['batchNo'];
        $distributorBill    = $_POST['distributor-bill'];
        $Items              = $_POST['items'];
        $items              = count($_POST['productId']);
        $totalQty           = $_POST['total-qty'];

        $billDate           = date_create($_POST['bill-date-val']);
        $billDate           = date_format($billDate, "d-m-Y");

        $dueDate            = date_create($_POST['due-date-val']);
        $dueDate            = date_format($dueDate, "d-m-Y");

        $paymentMode        = $_POST['payment-mode-val'];
        $pMode              = $paymentMode;
        $totalGst           = $_POST['totalGst'];
        $amount             = $_POST['netAmount'];
        $addedBy            = $_SESSION['employee_username'];
        $BatchNo            = $_POST['batchNo'];
        $purchaseId         = $_POST['purchaseId'];

        $MFDCHECK           = $_POST['mfdDate'];
        $expDate            = $_POST['expDate'];

        $crrntDt = date("d-m-Y");

        // ===================================== CHECKING ===========================================    
        echo "<br>Stok in id Array =>"; print_r($stockIn_Id); echo "<br>";

    }
exit;
    $addStockIn  = FALSE;
    if (isset($_POST['stock-in'])) {
        $addStockIn = $StockIn->addStockIn($distributorId, $distributorBill, $items, $totalQty, $billDate, $dueDate, $paymentMode, $totalGst, $amount, $addedBy);

        $table1 = "distributor_id";
        $data1 = $distributorId;
        $table2 = "distributor_bill";
        $data2 = $distributorBill;

        $selectStockInData = $StockIn->showStockInByTable($table1, $table2, $data1, $data2);
        // print_r($selectStockInData); echo "<br><br>";
        $stokInid = $selectStockInData[0]["id"];
    } // stock-in request end

    $updateStockIn = FALSE;
    // echo "hello 1";
    if (isset($_POST['update'])) {
        // echo "hello 2";
        // echo "<br>$distributorBill<br>";
        $updateStockIn = $StockIn->updateStockIn($stockIn_Id, $distributorId, $distributorBill, $items, $totalQty, $billDate, $dueDate, $paymentMode, $totalGst, $amount, $addedBy);
    } // stock-in request end

    if ($addStockIn == TRUE || $updateStockIn == TRUE) {

        //=========== STOCK IN DETAILS ===========
        foreach ($_POST['productId'] as $productId) {

            $batchNo            = array_shift($_POST['batchNo']);
            $mfdDate            = array_shift($_POST['mfdDate']);
            $expDate            = array_shift($_POST['expDate']);

            $weightage          = array_shift($_POST['weightage']);
            $unit               = array_shift($_POST['unit']);
            $qty                = array_shift($_POST['qty']);
            $freeQty            = array_shift($_POST['freeQty']);
            $looselyCount       = '';
            $mrp                = array_shift($_POST['mrp']);
            $ptr                = array_shift($_POST['ptr']);
            $discount           = array_shift($_POST['discount']);
            $base               = array_shift($_POST['base']);
            $gst                = array_shift($_POST['gst']);
            $gstPerItem         = array_shift($_POST['gstPerItem']);
            $margin             = array_shift($_POST['margin']);
            $amount             = array_shift($_POST['billAmount']);
            $looselyPrice       = '';
            $addedOn            = date("Y-m-d h:m:s");


            $looselyPrice = '';

            if ($unit == "tab" || $unit == "cap") {

                $looselyCount = $weightage * ($qty + $freeQty);
                $looselyPrice = ($mrp * $qty) / ($weightage * $qty);
            }

            if (isset($_POST['stock-in'])) {

                $addStockInDetails = $StockInDetails->addStockInDetails($stokInid, $productId, $distributorBill, $batchNo, $mfdDate, $expDate, $weightage, $unit, $qty, $freeQty, $looselyCount, $mrp, $ptr, $discount, $base, $gst, $gstPerItem, $margin, $amount, $addedBy);

                $addStockInDetails = TRUE;

                if ($addStockInDetails == true) {

                    // =========== FETCHING STOK IN DETAILS DATA FOR STOK IN DETAILS ID ===============

                    $selectStockInDetail = $StockInDetails->stokInDetials($productId, $distributorBill, $batchNo);
                    // print_r($selectStockInDetail); echo "<br><br>";

                    foreach ($selectStockInDetail as $stockDetailsData) {
                        $stokInDetailsId = $stockDetailsData["id"];
                        // echo "<br>stock in detials id => $stokInDetailsId<br>";
                    }

                    // ============ ADD TO CURRENT STOCK ============ 
                    $addCurrentStock = $CurrentStock->addCurrentStock($stokInDetailsId, $productId, $batchNo, $expDate, $distributorId, $looselyCount, $looselyPrice, $weightage, $unit, $qty + $freeQty, $mrp, $ptr, $gst, $addedBy);
                }
            } // end stock-in request

            if (isset($_POST['update'])) {

                $purchaseId         = array_shift($_POST['purchaseId']);
                
                if ($purchaseId != null) {

                    $selectStockInDetails = $StockInDetails->showStockInDetailsByStokinId($purchaseId);
                    
                    foreach($selectStockInDetails as $prevStockInDetails){
                        $prevStockInQty = $prevStockInDetails['qty'];
                        $prevStockInfreeQty = $prevStockInDetails['free_qty'];
                        $prevStockInLQty = $prevStockInDetails['loosely_count'];
                    }

                    $newQuantity = intval($freeQty) + intval($qty);

                    if ($unit == "tab" || $unit == "cap") {
                        $newLooselyCount = $weightage * $newQuantity;
                    }

                    $updateStokInDetails = $StockInDetails->updateStockInDetailsById($purchaseId, $productId, $distributorBill, $batchNo, $mfdDate, $expDate, $weightage, $unit, $qty, $freeQty, $looselyCount, $mrp, $ptr, $discount, $base, $gst, $gstPerItem, $margin, $amount, $addedBy, $addedOn);
                
        // ==================================== current stock update area ===============================

                    $stokInDetaislId = $purchaseId;
                    $selectCurrentStockDetaisl = $CurrentStock->showCurrentStockbyStokInId($stokInDetaislId);

                    foreach ($selectCurrentStockDetaisl as $currentStockData) {
                        $Quantity   = $currentStockData["qty"];
                        $LCount     = $currentStockData["loosely_count"];
                    }

                    $UpdatedQuantity = intval($newQuantity) - ((intval($prevStockInQty) + intval($prevStockInfreeQty)) - intval($Quantity));
                    $UpdatedLQantity = intval($newLooselyCount)-(intval($prevStockInLQty)-intval($LCount));

                    //update current stock

                    $updateCurrentStock = $CurrentStock->updateStockByStokinDetailsId($stokInDetaislId, $productId, $batchNo, $expDate, $distributorId, $UpdatedQuantity, $UpdatedLQantity, $mrp, $ptr);

                } else {

                    //select star form stok in by $distributorBill
                    $stockInData = $StockIn->showStockInById($distributorBill);

                    $stokInid = $stockInData[0]["id"];

                    $addStockInDetails = FALSE;
                    $addStockInDetails = $StockInDetails->addStockInDetails($stokInid, $productId, $distributorBill, $batchNo, $mfdDate, $expDate, $weightage, $unit, $qty, $freeQty, $looselyCount, $mrp, $ptr, $discount, $base, $gst, $gstPerItem, $margin, $amount, '');

                    $selectStockInDetail = $StockInDetails->stokInDetials($productId, $distributorBill, $batchNo);

                    // print_r($selectStockInDetail);

                    foreach ($selectStockInDetail as $stockDetaislData) {
                        $stokInDetaislId = $stockDetaislData["id"];
                    }

                    if ($addStockInDetails == true) {
                        // ============ CURRENT STOCK ============ 
                        $addCurrentStock = $CurrentStock->addCurrentStock($stokInDetaislId, $productId, $batchNo, $expDate, $distributorId, $looselyCount, $looselyPrice, $weightage, $unit, $qty + $freeQty, $mrp, $ptr, $gst, $addedBy);
                    }
                }
            }
        } //eof foreach
        // // $addCurrentStock = TRUE;
        // if ($addCurrentStock = TRUE) {
        //     echo '
        //     <script>
        //     swal("Success", "Stock Updated!", "success")
        //     .then((value) => {
        //             window.location="../../stock-in.php";
        //         });
        //     </script>';
        // } else {
        //     echo '<script>
        //     swal("Error", "Inventry Updation Faield!", "error")
        //     .then((value) => {
        //             window.location="../../stock-in.php";
        //         });
        //         </script>';
        // }
    } //eof if $addStockIn


} // post request method entered

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Medicy Health Care Medicine Purchase Bill</title>
    <link rel="stylesheet" href="../../../css/bootstrap 5/bootstrap-purchaseItem.css">
    <link rel="stylesheet" href="../../../css/custom/purchase-bill.css">

</head>


<body>
    <div class="custom-container">
        <div class="custom-body <?php if ($pMode != 'Credit') {
                                    if ($dueDate == $crrntDt) {
                                        echo "paid-bg";
                                    }
                                } ?>">
            <div class="card-body ">
                <div class="row">
                    <div class="col-sm-1">
                        <img class="float-end" style="height: 55px; width: 58px;" src="../../../images/logo-p.jpg" alt="Medicy">
                    </div>
                    <div class="col-sm-8">
                        <h4 class="text-start my-0"><?php echo $distributorName; ?></h4>
                        <p class="text-start" style="margin-top: -5px; margin-bottom: 0px;">
                            <small><?php echo $distAddress . ', PIN - ' . $distPIN; ?></small>
                        </p>
                        <p class="text-start" style="margin-top: -8px; margin-bottom: 0px;">
                            <small><?php echo 'Contact No : ' . $distContact  ?></small>
                        </p>

                    </div>
                    <div class="col-sm-3 border-start border-dark">
                        <p class="my-0"><b>Stock In Invoice</b></p>
                        <p style="margin-top: -5px; margin-bottom: 0px;"><small>Bill id:
                                <?php echo $distributorBill; ?></small></p>
                        <p style="margin-top: -5px; margin-bottom: 0px;"><small>Payment Mode: <?php echo $pMode; ?></small>
                        </p>
                        <p style="margin-top: -5px; margin-bottom: 0px;"><small>Bill Date: <?php echo $billDate; ?></small>
                        </p>
                        <p style="margin-top: -5px; margin-bottom: 0px;"><small>Due Date: <?php echo $dueDate; ?></small>
                        </p>
                    </div>
                </div>
            </div>
            <hr class="my-0" style="height:1px; background: #000000; border: #000000;">

            <hr class="my-0" style="height:1px;">

            <div class="row">
                <!-- table heading -->
                <div class="col-sm-1 text-center" style="width: 3%;">
                    <small><b>SL.</b></small>
                </div>
                <div class="col-sm-1" hidden>
                    <small><b>P Id</b></small>
                </div>
                <div class="col-sm-1" style="width: 12%;">
                    <small><b>P Name</b></small>
                </div>
                <div class="col-sm-1" style="width: 12%;">
                    <small><b>Manuf.</b></small>
                </div>
                <div class="col-sm-1" style="width: 8%;">
                    <small><b>Packing</b></small>
                </div>
                <div class="col-sm-1" style="width: 10%;">
                    <small><b>Batch</b></small>
                </div>
                <div class="col-sm-1" style="width: 5%;">
                    <small><b>MFD.</b></small>
                </div>
                <div class="col-sm-1" style="width: 5%">
                    <small><b>Exp.</b></small>
                </div>
                <div class="col-sm-1 text-end" style="width: 5%;">
                    <small><b>QTY</b></small>
                </div>
                <div class="col-sm-1 text-end" style="width: 5%;">
                    <small><b>F.QTY</b></small>
                </div>
                <div class="col-sm-1 text-end" style="width: 7%;">
                    <small><b>MRP</b></small>
                </div>
                <div class="col-sm-1 text-end" style="width: 7%;">
                    <small><b>PTR</b></small>
                </div>
                <div class="col-sm-1 text-end" style="width: 5%;">
                    <small><b>Disc(%)</b></small>
                </div>
                <div class="col-sm-1 text-end" style="width: 5%;">
                    <small><b>GST(%)</b></small>
                </div>
                <div class="col-sm-1b text-end" style="width: 10%;">
                    <small><b>Amount</b></small>
                </div>
                <!--/end table heading -->
            </div>

            <hr class="my-0" style="height:1px;">

            <div class="row">
                <?php
                $slno = 0;
                $subTotal = floatval(00.00);
                $itemIds    = $_POST['productId'];
                $itemBillNo    = $_POST['distributor-bill'];
                $distributorId = $distributorId;
                $itemBatchNo    = $updtBatchNoArry;
                // $stokInId = $stokInid;
                
                $count = count($itemIds);
                $totalGst = 0;
                $totalMrp = 0;
                $billAmnt = 0;

                for ($i = 0; $i < $count; $i++) {
                    $slno++;

                    $itemDetials = $StockInDetails->stokInDetials($itemIds[$i], $itemBillNo, $itemBatchNo[$i]);
                    // print_r($itemDetials);
                    // echo "<br>";

                    
                    foreach ($itemDetials as $itemsData) {

                        $prodId = $itemsData['product_id'];
                        
                        $productDetails = $Products->showProductsById($prodId);
                        foreach ($productDetails as $pData) {
                            $pname = $pData['name'];
                            $pManfId = $pData['manufacturer_id'];
                            $pType  = $pData['packaging_type']; 
                            $pQTY = $pData['unit_quantity'];
                            $pUnit = $pData['unit'];
                        }

                        $packagingData = $PackagingUnits->showPackagingUnitById($pType);
                        foreach ($packagingData as $packData) {
                            $unitNm = $packData['unit_name'];
                        }


                        $manufDetails = $Manufacturer->showManufacturerById($pManfId);
                        foreach ($manufDetails as $manufData) {
                            $manufName = $manufData['name'];
                        }


                        
                        $batchNo = $itemsData['batch_no'];
                        $MfdDate = $itemsData['mfd_date'];
                        $ExpDate = $itemsData['exp_date'];
                        $qty = $itemsData['qty'];
                        $FreeQty = $itemsData['free_qty'];
                        $Mrp = $itemsData['mrp'];
                        $Ptr = $itemsData['ptr'];
                        $discPercent = $itemsData['discount'];
                        $gstPercent = $itemsData['gst'];
                        $Amount = $itemsData['amount'];

                        $gstAmnt =  $itemsData['gst_amount'];
                        $totalGst = $totalGst + $gstAmnt;

                        $totalMrp = $totalMrp + ($Mrp*$qty);
                        $billAmnt = $billAmnt + $Amount;
                        
                    }

                    $cGst = $sGst = number_format($totalGst/2,2);
                    
                    // $sGst = ;
                    // $itemQty  = $qty[$i];
                    // $mrpOnQty = $mrp[$i];
                    // $mrpOnQty = $mrpOnQty * $itemQty;

                    if ($slno > 1) {
                        echo '<hr style="width: 98%; border-top: 1px dashed #8c8b8b; margin: 0 10px 0; align-items: center;">';
                    }
                ?>
                    <div class="col-sm-1 text-center" style="width: 3%;">
                        <small> <?php echo "$slno" ?></small>
                    </div>
                    <div class="col-sm-1b " hidden>
                        <small><?php echo "$prodId" ?></small>
                    </div>
                    <div class="col-sm-1b" style="width: 12%;">
                        <small><?php echo "$pname" ?></small>
                    </div>
                    <div class="col-sm-1b" style="width: 12%;">
                        <small><?php echo "$manufName" ?></small>
                    </div>
                    <div class="col-sm-1b" style="width: 8%;">
                        <small><?php echo $pQTY.$pUnit,"/",$unitNm ?></small>
                    </div>
                    <div class="col-sm-1b" style="width: 10%;">
                        <small><?php echo "$batchNo" ?></small>
                    </div>
                    <div class="col-sm-1 text-end" style="width: 5%;">
                        <small><?php echo "$MfdDate" ?></small>
                    </div>
                    <div class="col-sm-1 text-center" style="width: 5%;">
                        <small><?php echo "$ExpDate" ?></small>
                    </div>
                    <div class="col-sm-1 text-end" style="width: 5%;">
                        <small><?php echo "$qty" ?></small>
                    </div>
                    <div class="col-sm-1 text-end" style="width: 5%;">
                        <small><?php echo "$FreeQty" ?></small>
                    </div>
                    <div class="col-sm-1 text-end" style="width: 7%;">
                        <small><?php echo "$Mrp" ?></small>
                    </div>
                    <div class="col-sm-1 text-end" style="width: 7%;">
                        <small><?php echo "$Ptr" ?></small>
                    </div>
                    <div class="col-sm-1 text-end" style="width: 5%;">
                        <small><?php echo "$discPercent%" ?></small>
                    </div>
                    <div class="col-sm-1 text-end" style="width: 5%;">
                        <small><?php echo "$gstPercent%" ?></small>
                    </div>
                    <div class="col-sm-1b text-end" style="width: 10%;">
                        <small><?php echo "$Amount" ?></small>
                    </div>

                <?php
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
                                    <!-- <small>₹<?php echo $totalGSt / 2; ?></small> -->
                                    <small>₹<?php echo "$cGst" ?></small>
                                </p>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-8 text-end">
                                <p style="margin-top: -5px; margin-bottom: 0px;"><small>SGST:</small></p>
                            </div>
                            <div class="col-4 text-end">
                                <p style="margin-top: -5px; margin-bottom: 0px;">
                                    <!-- <small>₹<?php echo $totalGst / 2; ?></small> -->
                                    <small>₹<?php echo "$cGst" ?></small>
                                </p>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-8 text-end">
                                <p style="margin-top: -5px; margin-bottom: 0px;"><small>Total GST:</small></p>
                            </div>
                            <div class="col-4 text-end">
                                <p style="margin-top: -5px; margin-bottom: 0px;">
                                    <!-- <small>₹<?php echo floatval($totalGSt); ?></small> -->
                                    <small>₹<?php echo "$totalGst" ?></small>
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
                                    <!-- <small><b>₹<?php echo floatval($totalMrp); ?></b></small> -->
                                    <small><b>₹<?php echo "$totalMrp" ?></b></small>
                                </p>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-8 text-end">
                                <p style="margin-top: -5px; margin-bottom: 0px;"><small>Net:</small></p>
                            </div>
                            <div class="col-4 text-end">
                                <p style="margin-top: -5px; margin-bottom: 0px;">
                                    <!-- <small><b>₹<?php echo floatval($billAmout); ?></b></small> -->
                                    <small><b>₹<?php echo "$billAmnt" ?></b></small>
                                </p>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-8 text-end">
                                <p style="margin-top: -5px; margin-bottom: 0px;"><small>You Saved:</small></p>
                            </div>
                            <div class="col-4 text-end">
                                <p style="margin-top: -5px; margin-bottom: 0px;">
                                    <!-- <small>₹<?php echo $totalMrp - $billAmnt; ?></small> -->
                                    <small>₹<?php echo $totalMrp - $billAmnt ?></small>
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
        <!-- <button class="btn btn-primary shadow mx-2" onclick="history.back()">Go Back</button> -->
        <button class="btn btn-primary shadow mx-2" onclick="back()">Add New</button>
        <button class="btn btn-primary shadow mx-2" id="<?php echo $distributorId ?>" value="<?php echo $itemBillNo ?>" onclick="goBack('<?php echo $distributorId ?>','<?php echo $itemBillNo ?>', this.id, this.value)">Go Back</button>
        <button class="btn btn-primary shadow mx-2" onclick="window.print()">Print Bill</button>
    </div>
    </div>

</body>
<script src="../../../js/bootstrap-js-5/bootstrap.js"></script>
<script>
const back = () => {
    window.location.replace("../../../pharmacist/stock-in.php")
}

const goBack = (id, value) =>{
    console.log(id);
    console.log(value);
    location.href=`../../stock-in-edit.php?edit=${value}&editId=${id}`;
}
</script>

</html>