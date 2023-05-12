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
    require_once '../../../php_control/distributor.class.php';

    $StockIn = new StockIn();
    $StockInDetails = new StockInDetails();
    $CurrentStock = new CurrentStock();
    $distributor = new Distributor();

if($_SERVER["REQUEST_METHOD"] == "POST"){

    if (isset($_POST['stock-in'])) { 
        $distributorName      = $_POST['distributor-name'];
        // echo $distributorName;
        $distributorDetial = $distributor->selectDistributorByName($distributorName);
        echo "<br><br>"; print_r($distributorDetial);
    }elseif(isset($_POST['update'])){
        $distributorid        = $_POST['distributor-id'];
        // echo $distributorid;
        $distributorDetial = $distributor->showDistributorById($distributorid);
    }
        
        

        $distributorId = $distributorDetial[0]['id'];
        // echo $distributorId;

        $distributorBill    = $_POST['distributor-bill'];
        $Items              = $_POST['items'];
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

        $BatchNo            = $_POST['batchNo'];

        $stokinDetilsByBillId = $StockInDetails->stokInDetialsbyBillNo($distributorBill);
        
        // $purchaseId         = $_POST['purchaseId'];

        // if (isset($_POST['stock-in'])){
        //     $tempConditionCheck1 = "true";
        // }else{
        //     $tempConditionCheck1 = "false";
        // }

        // if (isset($_POST['update'])){
        //     $tempConditionCheck2 = "true";
        // }else{
        //     $tempConditionCheck2 = "false";
        // }
        
        //============= checking area=========
        echo "<br><br>";
        // print_r($distributorid); echo "-> Distributor Id <br><br>";
        print_r($distributorBill); echo "-> Bill No <br><br>";
        print_r($items); echo "-> Items <br><br>";
        print_r($Items); echo "-> Items Array <br><br>";
        print_r($totalQty); echo "-> Total qty <br><br>";
        print_r($billDate); echo "-> Bill daste <br><br>";
        print_r($dueDate); echo "-> Due date <br><br>";
        print_r($paymentMode); echo "-> Payment Mode <br><br>";
        print_r($totalGst); echo "-> Total Gst <br><br>";
        print_r($amount); echo "-> Amount <br><br>";
        // print_r($purchaseId); echo "-> Purchase Detail Id <br><br>";
        print_r($BatchNo); echo "-> Batch Numbers <br><br>";
        // print_r($tempConditionCheck1); echo "-> Condition Check 1 stock-in<br><br>";
        // print_r($tempConditionCheck2); echo "-> Condition Check 2 UPDATE<br><br>";

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
                // $purchaseDetaislId  = array_shift($_POST['purchaseId']);

                echo "<br>Product ID : ",$productId;
                echo "<br>Bath No : ",$batchNo;
                echo "<br>Exp Date : ",$expDate;
                echo "<br>Weightage : ",$weightage;
                echo "<br>Unit : ",$unit;
                echo "<br>QTY : ",$qty;
                echo "<br>Free Qty : ",$freeQty;
                echo "<br>Loosely Count : ",$looselyCount;
                echo "<br>MRP : ",$mrp;
                echo "<br>PTR : ",$ptr;
                echo "<br>Discount : ",$discount;
                echo "<br>Base Price : ",$base;
                echo "<br>GST : ",$gst;
                echo "<br>GST PER ITEM : ",$gstPerItem;
                echo "<br>MARGIN : ",$margin;
                echo "<br>AMOUNT : ",$amount;
                echo "<br>LOOSELY PRICE : ",$looselyPrice;
                // echo "<br>PURCHASE DETIALS ID : ",$purchaseDetaislId;
                echo "<br><br><br>";

             
                $looselyPrice = '';
                    
                if ($unit == "tab" || $unit == "cap") {

                    $looselyCount = $weightage * ($qty + $freeQty);
                    // $looselyPriceOnMRP = $mrp / $weightage;
                    $looselyPrice = ($mrp * $qty) / ($weightage * $qty);;     
                }
                

                if (isset($_POST['stock-in'])) {

                    $addStockInDetails = $StockInDetails->addStockInDetails($productId, $distributorBill, $batchNo, $mfdDate, $expDate, $weightage, $unit, $qty, $freeQty, $looselyCount, $mrp, $ptr, $discount, $base, $gst, $gstPerItem, $margin, $amount, '');
                    $addStockInDetails = TRUE;
                    if ($addStockInDetails == true) {

                        // ============ CURRENT STOCK ============ 
                        $addCurrentStock = $CurrentStock->addCurrentStock($productId, $batchNo, $expDate, $distributorId, $looselyCount, $looselyPrice, $weightage, $unit, $qty+$freeQty, $mrp, $ptr, $gst, $addedBy);
                        
                    }
                } // end stock-in request

                if (isset($_POST['update'])) {

                    //retrive all product data using bill id (done)
                    // create an deta array with product id and batch number from stok in details
                    // check product id and batch number is exisist or not
                    // if not exisist add data
                    // if exisist update data

                //=============== NEED TO CHEK THIS AREA ==================================

                    $deleteExists    = $StockInDetails->stockInDelete($distributorBill, $batchNo);
                    $delCurrentStock = $CurrentStock->deleteCurrentStock($productId, $batchNo);

                
                    if ($deleteExists == TRUE && $delCurrentStock == TRUE) {
                        
                        $addStockInDetails = $StockInDetails->addStockInDetails($productId, $distributorBill, $batchNo, $mfdDate, $expDate, $weightage, $unit, $qty, $freeQty, $looselyCount, $mrp, $ptr, $discount, $base, $gst, $gstPerItem, $margin, $amount, '');

                        // $addStockInDetails = TRUE;
                        if ($addStockInDetails) {
                            // ============ CURRENT STOCK ============ 
                            $addCurrentStock = $CurrentStock->addCurrentStock($productId, $batchNo, $expDate, $distributorId, $looselyCount, $looselyPrice, $weightage, $unit, $qty+$freeQty, $mrp, $ptr, $gst, $addedBy);
                        }
                    }
                    
                // =============== NEED TO CHEK THIS AREA ==================================
                } // end update request
                
            }//eof foreach
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
