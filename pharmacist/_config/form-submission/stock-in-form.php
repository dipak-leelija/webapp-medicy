<!doctype html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <script src="../../../js/sweetAlert.min.js"></script>

</head>

<body>
    <?php
    require_once '../../_config/sessionCheck.php';
    require_once '../../../php_control/stockIn.class.php';
    require_once '../../../php_control/stockInDetails.class.php';
    require_once '../../../php_control/currentStock.class.php';
    require_once '../../../php_control/distributor.class.php';

    $StockIn = new StockIn();
    $StockInDetails = new StockInDetails();
    $CurrentStock = new CurrentStock();
    $distributor = new Distributor();
    $Session = new SessionHandler();

    if ($_SERVER["REQUEST_METHOD"] == "POST") {

        if (isset($_POST['stock-in'])) {

            $distributorName      = $_POST['distributor-name'];
            $distributorDetial = $distributor->selectDistributorByName($distributorName);
            $distributorId = $distributorDetial[0]['id'];
            
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
            $totalGst           = $_POST['totalGst'];
            $amount             = $_POST['netAmount'];
            $addedBy            = '';
            $BatchNo            = $_POST['batchNo'];
            $MFDCHECK           = $_POST['mfdDate'];
            $expDate            = $_POST['expDate'];

        } elseif (isset($_POST['update'])) {

            $stockIn_Id         = $_POST['stok-in-id'];
            $distributorId      = $_POST['distributor-id'];
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
            $totalGst           = $_POST['totalGst'];
            $amount             = $_POST['netAmount'];
            $addedBy            = '';
            $BatchNo            = $_POST['batchNo'];
            $purchaseId         = $_POST['purchaseId'];

            $MFDCHECK           = $_POST['mfdDate'];
            $expDate            = $_POST['expDate'];
        }

        $addStockIn  = FALSE;
        if (isset($_POST['stock-in'])) {
            $addStockIn = $StockIn->addStockIn($distributorId, $distributorBill, $items, $totalQty, $billDate, $dueDate, $paymentMode, $totalGst, $amount, $addedBy);

            $table1 = "distributor_id";
            $data1 = $distributorId;
            $table2 = "distributor_bill";
            $data2 = $distributorBill;
            $selectCurrentStockInData = $StockIn->showStockInByTable($table1, $table2, $data1, $data2);
            // print_r($selectCurrentStockInData);
            $stokInid = $selectCurrentStockInData[0]["id"];
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

                    $addStockInDetails = $StockInDetails->addStockInDetails($stokInid, $productId, $distributorBill, $batchNo, $mfdDate, $expDate, $weightage, $unit, $qty, $freeQty, $looselyCount, $mrp, $ptr, $discount, $base, $gst, $gstPerItem, $margin, $amount, '');

                    $addStockInDetails = TRUE;

                    if ($addStockInDetails == true) {

                        // =========== FETCHING STOK IN DETAILS DATA FOR STOK IN DETAILS ID ===============
                        $selectStockInDetail = $StockInDetails->stokInDetials($productId, $distributorBill, $batchNo);
                        // print_r($selectStockInDetail);

                        foreach ($selectStockInDetail as $stockDetailsData) {
                            $stokInDetaislId = $stockDetailsData["id"];
                        }

                        // ============ ADD TO CURRENT STOCK ============ 
                        $addCurrentStock = $CurrentStock->addCurrentStock($stokInDetaislId, $productId, $batchNo, $expDate, $distributorId, $looselyCount, $looselyPrice, $weightage, $unit, $qty + $freeQty, $mrp, $ptr, $gst, $addedBy);
                    }
                } // end stock-in request

                if (isset($_POST['update'])) {

                    $purchaseId         = array_shift($_POST['purchaseId']);

                    if ($purchaseId != null) {

                        $selectStockInDetail = $StockInDetails->showStockInDetailsByStokinId($purchaseId);
                        // print_r($selectStockInDetail);
                        foreach ($selectStockInDetail as $stockDetailsData) {
                            $stokInDetaislId = $stockDetailsData["id"];
                            $QTY = $stockDetailsData["qty"];
                            $FreeQTY = $stockDetailsData["free_qty"];
                            $LooseCount = $stockDetailsData["loosely_count"];
                            $Weightage = $stockDetailsData["weightage"];
                            $Unit = $stockDetailsData["unit"];
                        }

                        $QTY = - ($qty - $QTY);
                        $FreeQTY = - ($freeQty - $FreeQTY);

                        $totalQty = $QTY + $FreeQTY;

                        $selectCurrentStockDetaisl = $CurrentStock->showCurrentStockbyStokInId($stokInDetaislId);

                        // print_r($selectCurrentStockDetaisl);

                        foreach ($selectCurrentStockDetaisl as $currentStockData) {

                            $newQuantity = $currentStockData["qty"];
                            $newLCount = $currentStockData["loosely_count"];
                        }

                        $newQuantity = $newQuantity + $totalQty;

                        if ($unit == "tab" || $unit == "cap") {
                            $looselyCount = $weightage * $newQuantity;
                        }

                        $newLCount = $newLCount + (- ($newLCount - $looselyCount));

                        // ================== eof data fetch ======================

                        $updateStokInDetails = $StockInDetails->updateStockInDetailsById($purchaseId, $productId, $distributorBill, $batchNo, $mfdDate, $expDate, $weightage, $unit, $qty, $freeQty, $looselyCount, $mrp, $ptr, $discount, $base, $gst, $gstPerItem, $margin, $amount, $addedBy, $addedOn);

                        //update current stock

                        $updateCurrentStock = $CurrentStock->updateStockByStokinDetailsId($stokInDetaislId, $productId, $batchNo, $expDate, $distributorId, $newQuantity, $newLCount, $mrp, $ptr);

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
            // $addCurrentStock = TRUE;
            if ($addCurrentStock = TRUE) {
                echo '
                <script>
                swal("Success", "Stock Updated!", "success")
                .then((value) => {
                        window.location="../../stock-in.php";
                    });
                </script>';
            } else {
                echo '<script>
                swal("Error", "Inventry Updation Faield!", "error")
                .then((value) => {
                        window.location="../../stock-in.php";
                    });
                    </script>';
            }
        } //eof if $addStockIn


    } // post request method entered

    ?>



</body>

</html>