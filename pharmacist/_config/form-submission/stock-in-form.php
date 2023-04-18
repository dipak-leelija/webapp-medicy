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

    require_once '../../../php_control/stockIn.class.php';
    require_once '../../../php_control/stockInDetails.class.php';
    require_once '../../../php_control/currentStock.class.php';

    $StockIn = new StockIn();
    $StockInDetails = new StockInDetails();
    $CurrentStock = new CurrentStock();

if($_SERVER["REQUEST_METHOD"] == "POST"){
        $distributorId      = $_POST['distributor-id'];
        $distributorBill    = $_POST['distributor-bill'];
        // $items              = $_POST['items'];
        $items              = count($_POST['productId']);
        $totalQty           = $_POST['total-qty'];

        $billDate           = date_create($_POST['bill-date-val']);
        $billDate           = date_format($billDate,"d-m-Y");

        $dueDate            = date_create($_POST['due-date-val']);
        $dueDate            = date_format($dueDate,"d-m-Y");
        
        $paymentMode        = $_POST['payment-mode-val'];
        $totalGst           = $_POST['totalGst'];
        $amount             = $_POST['netAmount'];
        $addedBy            = ''; 
        
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

                $batchNo        = array_shift($_POST['batchNo']);
                $expDate        = array_shift($_POST['expDate']);

                $weightage      = array_shift($_POST['weightage']);
                $unit           = array_shift($_POST['unit']); 
                $qty            = array_shift($_POST['qty']);
                $freeQty        = array_shift($_POST['freeQty']);
                $looselyCount   = '';
                $mrp            = array_shift($_POST['mrp']);
                $ptr            = array_shift($_POST['ptr']);
                $discount       = array_shift($_POST['discount']);
                $base           = array_shift($_POST['base']);
                $gst            = array_shift($_POST['gst']);
                $gstPerItem     = array_shift($_POST['gstPerItem']);
                $margin         = array_shift($_POST['margin']);
                $amount         = array_shift($_POST['billAmount']);
                $looselyPrice   = '';

                
                //$looselyPrice = '';
                    
                if ($unit == "tab" || $unit == "cap") {

                    $looselyCount = $weightage * ($qty + $freeQty);
                    // $looselyPriceOnMRP = $mrp / $weightage;
                    $looselyPrice = ($mrp * $qty) / ($weightage * $qty);;     
                }
                

                if (isset($_POST['stock-in'])) {

                    $addStockInDetails = $StockInDetails->addStockInDetails($productId, $distributorBill, $batchNo, $expDate, $weightage, $unit, $qty, $freeQty, $looselyCount, $mrp, $ptr, $discount, $base, $gst, $gstPerItem, $margin, $amount, '');
                    // $addStockInDetails = TRUE;
                    if ($addStockInDetails) {

                        // ============ CURRENT STOCK ============ 
                        $addCurrentStock = $CurrentStock->addCurrentStock($productId, $batchNo, $expDate, $distributorId, $looselyCount, $looselyPrice, $weightage, $unit, $qty+$freeQty, $mrp, $ptr, $gst, $addedBy);
                        
                    }
                } // end stock-in request

                if (isset($_POST['update'])) {

                    $deleteExists    = $StockInDetails->stockInDelete($distributorBill, $batchNo);
                    $delCurrentStock = $CurrentStock->deleteCurrentStock($productId, $batchNo);
                    // echo var_dump($delCurrentStock);
                    if ($deleteExists == TRUE && $delCurrentStock == TRUE) {
                        
                        $addStockInDetails = $StockInDetails->addStockInDetails($productId, $distributorBill, $batchNo, $expDate, $weightage, $unit, $qty, $freeQty, $looselyCount, $mrp, $ptr, $discount, $base, $gst, $gstPerItem, $margin, $amount, '');

                        // $addStockInDetails = TRUE;
                        if ($addStockInDetails) {
                            // ============ CURRENT STOCK ============ 
                            $addCurrentStock = $CurrentStock->addCurrentStock($productId, $batchNo, $expDate, $distributorId, $looselyCount, $looselyPrice, $weightage, $unit, $qty+$freeQty, $mrp, $ptr, $gst, $addedBy);
                        }
                    }
                } // end update request
                
            }//eof foreach
            // $addCurrentStock = TRUE;

            if ($addCurrentStock) {
                echo '
                <script>
                swal("Success", "Stock Updated!", "success")
                .then((value) => {
                        window.location="../../stock-in.php";
                    });
                </script>';
            }else{
                echo '<script>
                swal("Error", "Inventry Updation Faield!", "error")
                .then((value) => {
                        window.location="../../stock-in.php";
                    });
                    </script>';
            }

        }//eof if $addStockIn


}// post request method entered

?>



</body>

</html>
