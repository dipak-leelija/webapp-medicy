<?php

require_once dirname(dirname(__DIR__)).'/config/constant.php';
require_once ROOT_DIR.'_config/sessionCheck.php'; //check admin loggedin or not
require_once dirname(dirname(__DIR__)) . '/config/service.const.php';

require_once CLASS_DIR.'dbconnect.php';
require_once CLASS_DIR .'encrypt.inc.php';
require_once ROOT_DIR.'_config/healthcare.inc.php';
require_once CLASS_DIR.'hospital.class.php';
require_once CLASS_DIR.'stockReturn.class.php';
require_once CLASS_DIR.'idsgeneration.class.php';
require_once CLASS_DIR.'currentStock.class.php';
require_once CLASS_DIR.'distributor.class.php';


//  INSTANTIATING CLASS
$HelthCare       = new HealthCare();
$StockReturn     = new StockReturn();
$IdsGeneration   = new IdsGeneration();
$CurrentStock    = new CurrentStock();
$Distributor     = new Distributor;


if ($_SERVER['REQUEST_METHOD'] == "POST") {
    if (isset($_POST['stock-return'])) {
        
        $stockReturnId      = $IdsGeneration->stockReturnId();

        $stockInId          = $_POST['stockInId'];
        $stockInDetailsId   = $_POST['stok-in-details-id'];
        $distributorId      = $_POST['dist-id'];
        $distributorName    = $_POST['dist-name'];
        
        $distData = json_decode($Distributor->showDistributorById($distributorId));
        $distAddress    = $distData->data->address;
        $distPin        = $distData->data->area_pin_code;
        $distContact    = $distData->data->phno;

        $returnDate      = date("Y-m-d", strtotime($_POST['return-date']));
        $itemQty         = $_POST['items-qty'];
        $totalReturnQty  = $_POST['total-return-qty'];
        $returnGst       = $_POST['return-gst-val'];
        $refundMode      = $_POST['refund-mode'];
        $refund          = $_POST['refund'];
        $status          = 1;
    
        $returned = $StockReturn->addStockReturn($stockReturnId, $stockInId, intval($distributorId), $returnDate, intval($itemQty), intval($totalReturnQty), floatval($returnGst), $refundMode, floatval($refund), $status, $employeeId, NOW, $adminId);
        
        $returnResult = $returned['result'];

        // $returnResult = true;
        if($returnResult == true){

            //arrays
            $stokInDetailsId = $_POST['stok-in-details-id'];
            $productId      = $_POST['productId'];
            
            $productName    = $_POST['productName'];
            $ids            = count($productId);
        
            $batchNo        = $_POST['batchNo'];
            $distBillNo     = $_POST['distBillNo'];
            $expDate        = $_POST['expDate'];

            $setof          = $_POST['setof'];
            
            $unit           = preg_replace('/[0-9]/','',$setof);
            // print_r($unit);
            $weightage      = preg_replace('/[a-z-A-Z]/','',$setof);

            $purchasedQty   = $_POST['purchasedQty'];
            $freeQty        = $_POST['freeQty'];
            $mrp            = $_POST['mrp'];
            $ptr            = $_POST['ptr'];
            
            $gstPercent     = preg_replace('/[%]/','',$_POST['gst']);
            $discParcent    = preg_replace('/[%]/','',$_POST['disc-percent']);

            $returnQty      = $_POST['return-qty'];
            // echo "<br>Return qty : ";
            // print_r($returnQty);
            $returnFQty     = $_POST['return-free-qty'];
            // echo "<br>Return free qty : ";
            // print_r($returnFQty);
            $refundAmount   = $_POST['refund-amount'];

            
            for ($i=0; $i < $ids; $i++) { 
                $currentStockData = json_decode($CurrentStock->showCurrentStocByStokInDetialsId($stokInDetailsId[$i]));

                $wholeQty = $currentStockData->qty;
                $looseQty = $currentStockData->loosely_count;

                // peritem toral return qty (return qantity + free return qantity)
                $perItemTotalReturnQty = intval($returnQty) + intval($returnFQty);

                if ($wholeQty >= $perItemTotalReturnQty) {
                
                    if (in_array(strtolower(trim($unit[$i])), LOOSEUNITS)){
                        $updatedLooseQty = intval($looseQty) - ($perItemTotalReturnQty * $weightage[$i]);
                        $updatedQty = intdiv($updatedLooseQty, $weightage[$i]);
                    }else{
                        $updatedLooseQty = 0;
                        $updatedQty = intval($wholeQty) - $perItemTotalReturnQty;
                    }
                
                    // echo "<br><br>";
                    // echo $updatedQty;

                    $updatedBy = ($_SESSION['ADMIN']) ? $adminId : $employeeId;

                    // ============== update current stock function =================
                    $updateCurrentStock = $CurrentStock->updateStockByReturnEdit(intval($stokInDetailsId[$i]), intval($updatedQty), intval($updatedLooseQty), $updatedBy, NOW);

                    // ====== add stock return function =============
                    $detailesReturned = $StockReturn->addStockReturnDetails($stockReturnId, intval($stokInDetailsId[$i]), $productId[$i], $distBillNo[$i], $batchNo[$i], $expDate[$i], $setof[$i], intval($purchasedQty[$i]), intval($freeQty[$i]), floatval($mrp[$i]), floatval($ptr[$i]), intval($gstPercent[$i]), intval($discParcent[$i]), intval($returnQty[$i]), intval($returnFQty[$i]), floatval($refundAmount[$i]));
                }else {
                    echo 'Return quantity is more then current stock quantity of this item!';
                    exit;
                }
            }
        }
    }
}

exit;
$response = url_enc(json_encode(['stock_return_id' => $stockReturnId]));
header("Location: ".URL."stock-return-invoice.php?data=".$response);
exit;

?>
