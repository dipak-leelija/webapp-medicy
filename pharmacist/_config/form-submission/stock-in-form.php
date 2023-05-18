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
            // echo $distributorName;
            $distributorDetial = $distributor->selectDistributorByName($distributorName);
            // echo "<br><br>"; print_r($distributorDetial);

            $distributorId = $distributorDetial[0]['id'];
            // echo $distributorId;
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
            // $purchaseId         = $_POST['purchaseId'];

            $MFDCHECK           = $_POST['mfdDate'];
            $expDate            = $_POST['expDate'];

            $stokinDetilsByBillId = $StockInDetails->stokInDetialsbyBillNo($distributorBill);

        } elseif (isset($_POST['update'])) {
            $distributorId        = $_POST['distributor-id'];
            // echo $distributorid;
            // $distributorDetial = $distributor->showDistributorById($distributorid);

            // $distributorId = $distributorDetial[0]['id'];
            // echo $distributorId;
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

            $stokinDetilsByBillId = $StockInDetails->stokInDetialsbyBillNo($distributorBill);

            //============= checking area=========
            echo "<br>Distributor Id -> $distributorId";
            echo "<br>Bill No -> ";             
            echo $distributorBill;                
            echo "<br>Items -> ";                     
            print_r($items);                        
            echo "<br>Items Array -> ";                  
            print_r($Items);                      
            echo "<br>Total qty -> ";                  
            print_r($totalQty);                            
            echo "<br>Bill daste -> ";                       
            print_r($billDate);                        
            echo "<br>Due date -> ";                        
            print_r($dueDate);                    
            echo "<br>Payment Mode -> ";                   
            print_r($paymentMode);                  
            echo "<br>Total Gst -> ";                                  
            print_r($totalGst);                       
            echo "<br>Amount -> ";                               
            print_r($amount);                        
            echo "<br>Purchase Detail Id -> ";            
            print_r($purchaseId);                                                        
            echo "<br>Posted Batch Numbers -> ";                   
            print_r($BatchNo);                            
            echo "<br>MFD DATE CHECK -> ";                        
            print_r($MFDCHECK);                       
            echo "<br>EXP DATE CHECK -> ";                     
            print_r($expDate); 
            echo "<br>";                                                     
        }
        //============ EOF CHECKING ===========

        $addStockIn  = FALSE;
        if (isset($_POST['stock-in'])) {
            $addStockIn = $StockIn->addStockIn($distributorId, $distributorBill, $items, $totalQty, $billDate, $dueDate, $paymentMode, $totalGst, $amount, $addedBy);
        } // stock-in request end

        $updateStockIn = FALSE;
        if (isset($_POST['update'])) {
            $updateStockIn = $StockIn->updateStockIn($distributorId, $distributorBill, $items, $totalQty, $billDate, $dueDate, $paymentMode, $totalGst, $amount, $addedBy);
        } // stock-in request end

        if ($addStockIn == TRUE || $updateStockIn == TRUE) {

            //=========== STOCK IN DETAILS ===========
            foreach ($_POST['productId'] as $productId) {

                $purchaseId         = array_shift($_POST['purchaseId']);
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

                // $purchaseId  = array_shift($newIdset);

                $looselyPrice = '';

                if ($unit == "tab" || $unit == "cap") {

                    $looselyCount = $weightage * ($qty + $freeQty);
                    $looselyPrice = ($mrp * $qty) / ($weightage * $qty);
                }


                if (isset($_POST['stock-in'])) {

                    $addStockInDetails = $StockInDetails->addStockInDetails($productId, $distributorBill, $batchNo, $mfdDate, $expDate, $weightage, $unit, $qty, $freeQty, $looselyCount, $mrp, $ptr, $discount, $base, $gst, $gstPerItem, $margin, $amount, '');
                    $addStockInDetails = TRUE;

                    if ($addStockInDetails == true) {

                        // =========== FETCHING STOK IN DETAILS DATA FOR STOK IN DETAILS ID ===============
                        $selectStockInDetail = $StockInDetails->stokInDetials($productId, $distributorBill, $batchNo);
                        // print_r($selectStockInDetail);
                        foreach($selectStockInDetail as $stockDetailsData){
                            $stokInDetaislId = $stockDetailsData["id"];
                        }
                        // ============ ADD TO CURRENT STOCK ============ 
                        $addCurrentStock = $CurrentStock->addCurrentStock($stokInDetaislId ,$productId, $batchNo, $expDate, $distributorId, $looselyCount, $looselyPrice, $weightage, $unit, $qty + $freeQty, $mrp, $ptr, $gst, $addedBy);
                    }
                } // end stock-in request

                if (isset($_POST['update'])) {

                    if ($purchaseId != null) {
                        echo "---------------------------";
                        echo "<br>PURCHASE ID FOUND";
                        echo "<br>Purchase detail id : $purchaseId<br>";
                        echo "<br>Batch Number : $batchNo<br>";
                        echo "update product details using purchase id";
                        echo "<br>Product ID : ", $productId;
                        echo "<br>Bath No : ", $batchNo;
                        echo "<br>MFD : $mfdDate";
                        echo "<br>Exp Date : ", $expDate;
                        echo "<br>Weightage : ", $weightage;
                        echo "<br>Unit : ", $unit;
                        echo "<br>QTY : ", $qty;
                        echo "<br>Free Qty : ", $freeQty;
                        echo "<br>Loosely Count : ", $looselyCount;
                        echo "<br>MRP : ", $mrp;
                        echo "<br>PTR : ", $ptr;
                        echo "<br>Discount : ", $discount;
                        echo "<br>Base Price : ", $base;
                        echo "<br>GST : ", $gst;
                        echo "<br>GST PER ITEM : ", $gstPerItem;
                        echo "<br>MARGIN : ", $margin;
                        echo "<br>AMOUNT : ", $amount;
                        echo "<br>LOOSELY PRICE : ", $looselyPrice;
                        echo "<br>PURCHASE DETIALS ID : ", $purchaseId;
                        echo "<br>Added on : $addedOn<br>";

    // ==================== fetching stock in detials and current stock previous data ====================

                        $selectStockInDetail = $StockInDetails->showStockInDetailsByStokinId($purchaseId);
                        print_r($selectStockInDetail);

                        foreach($selectStockInDetail as $stockDetailsData){
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

                        print_r($selectCurrentStockDetaisl);

                        foreach($selectCurrentStockDetaisl as $currentStockData){

                            $newQuantity = $currentStockData["qty"];
                            $newLCount = $currentStockData["loosely_count"];
                        }

                        $newQuantity = $newQuantity + $totalQty;

                        if ($unit == "tab" || $unit == "cap") {
                            $looselyCount = $weightage * $newQuantity;
                        }

                        $newLCount = $newLCount + (-($newLCount - $looselyCount));
                        
    // ========================================== eof data fetch =======================================

                        $updateStokInDetails = $StockInDetails->updateStockInDetailsById($purchaseId, $productId, $distributorBill, $batchNo, $mfdDate, $expDate, $weightage, $unit, $qty, $freeQty, $looselyCount, $mrp, $ptr, $discount, $base, $gst, $gstPerItem, $margin, $amount, $addedBy, $addedOn);

                        //update current stock

                        //$updateCurrentStock = $CurrentStock->updateStockByStokinDetailsId($stokinDetailsId, $productId, $batchNo, $expDate, $distributorId, $newQuantity, $newLCount, $mrp, $ptr)
                        // updateStockByStokinDetailsId($stokinDetailsId, $productId, $batchNo, $expDate, $distributorId, $newQuantity, $newLCount, $mrp, $ptr)
                        exit;
                    }else {
                
                        $addStockInDetails = FALSE;
                        $addStockInDetails = $StockInDetails->addStockInDetails($productId, $distributorBill, $batchNo, $mfdDate, $expDate, $weightage, $unit, $qty, $freeQty, $looselyCount, $mrp, $ptr, $discount, $base, $gst, $gstPerItem, $margin, $amount, '');

                        $selectStockInDetail = $StockInDetails->stokInDetials($productId, $distributorBill, $batchNo);

                        // print_r($selectStockInDetail);

                        foreach($selectStockInDetail as $stockDetaislData){
                            $stokInDetaislId = $stockDetaislData["id"];
                        }

                        if ($addStockInDetails == true) {
                            // ============ CURRENT STOCK ============ 
                            $addCurrentStock = $CurrentStock->addCurrentStock($stokInDetaislId ,$productId, $batchNo, $expDate, $distributorId, $looselyCount, $looselyPrice, $weightage, $unit, $qty + $freeQty, $mrp, $ptr, $gst, $addedBy);
                        } 
                    }
                }
            } //eof foreach
            // $addCurrentStock = TRUE;
            exit;
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