<?php
require_once 'dbconnect.php';



class StockReturn extends DatabaseConnection{




##################################################################################################################################
#                                                                                                                                #
#                                            Stock Return Functions(stock_return)                                                #
#                                                                                                                                #
##################################################################################################################################
    
    function addStockReturn($stockReturnId, $distributorId, $returnDate, $items, $totalQty, $returnGst, $refundMode, $refundAmount, $addedBy){
        $sql = "INSERT INTO `stock_return` (`id`, `distributor_id`, `return_date`, `items`, `total_qty`, `gst_amount`, `refund_mode`, `refund_amount`, `added_by`) VALUES ('$stockReturnId', '$distributorId', '$returnDate', '$items', '$totalQty', '$returnGst', '$refundMode', '$refundAmount', '$addedBy')";
        $res = $this->conn->query($sql);
        return $res;
    }// eof addStockReturn



    function showStockReturn(){
        $data = array();
        $sql  = "SELECT * FROM stock_return";
        $res  = $this->conn->query($sql);
        while ($result = $res->fetch_array()) {
            $data[] = $result;
        }
        return $data;
    }//eof showStockReturn


    function showStockReturnById($returnId){
        $data = array();
        $sql  = "SELECT * FROM stock_return WHERE `id` = $returnId";
        $res  = $this->conn->query($sql);
        while ($result = $res->fetch_array()) {
            $data[] = $result;
        }
        return $data;
    }//eof showStockReturn



    function stockReturnFilter($table, $value){
        $data = array();
        $sql  = "SELECT * FROM stock_return WHERE `$table` = '$value'";
        $res  = $this->conn->query($sql);
        while ($result = $res->fetch_array()) {
            $data[] = $result;
        }
        return $data;
    }//eof stockReturnFilter

    

    function stockReturnFilterbyDate($table, $value1, $value2){
        $data = array();
        $sql  = $sql = "SELECT * FROM `stock_return` WHERE `$table`  between '$value1' and '$value2'";
        $res  = $this->conn->query($sql);
        while ($result = $res->fetch_array()) {
            $data[] = $result;
        }
        return $data;
    }//eof stockReturnFilter



    function stockReturnStatus($returnId, $statusValue){
        $sql  = "UPDATE stock_return SET `status` = '$statusValue' WHERE `stock_return`.`id` = '$returnId'";
        // echo $sql.$this->conn->error;
        $res  = $this->conn->query($sql);
        $data = $this->conn->affected_rows;
        return $data;

    }//eof stockReturnStatus


    //stockReturn Edit\update function...........
    function stockReturnEdit($id, $distributorId, $returnDate, $items, $totalQty, $gst, $refundMode, $refundAmount, $addedBy){

        $update = "UPDATE `stock_return` SET `distributor_id`='$distributorId',`return_date`='$returnDate',`items`='$items',`total_qty`='$totalQty',`gst_amount`='$gst',`refund_mode`='$refundMode',`refund_amount`='$refundAmount', `added_by`='$addedBy' WHERE `stock_return`.`id`='$id' ";

        $response = $this->conn->query($update);

        return $response;
    }

    // ---------------EDIT STOCK RETURN UPDATE FUNCTION----------------RD----------------

    function stockReturnEditUpdate($id, $distributorId, $returnDate, $items, $totalQty, $gst, $refundMode, $refundAmount, $addedBy, $addedOn, $addedTime){

        $editANDupdate = "UPDATE `stock_return` SET `distributor_id`='$distributorId',`return_date`='$returnDate',`items`='$items',`total_qty`='$totalQty',`gst_amount`='$gst',`refund_mode`='$refundMode',`refund_amount`='$refundAmount',`added_by`='$addedBy',`added_on`='$addedOn',`added_time`='$addedTime' WHERE `stock_return`.`id`='$id'";

        $response = $this->conn->query($editANDupdate);

        return $response;
    }



###################################################################################################################################
#                                                                                                                                 #
#                                           Stock Return Details Functions(stock_return_details)                                  #
#                                                                                                                                 #
###################################################################################################################################

function addStockReturnDetails($stockReturnId, $productId, $batchNo, $expDate, $unit, $purchaseQty, $freeQty, $mrp, $ptr, $purchaseAmount, $gst, $returnQty, $refundAmount, $addedBy){
    $sql = "INSERT INTO stock_return_details (`stock_return_id`, `product_id`, `batch_no`, `exp_date`, `unit`, `purchase_qty`, `free_qty`, `mrp`, `ptr`, `purchase_amount`, `gst`, `return_qty`, `refund_amount`, `added_by`) VALUES ('$stockReturnId', '$productId', '$batchNo', '$expDate', '$unit', '$purchaseQty', '$freeQty', '$mrp', '$ptr', '$purchaseAmount', '$gst', '$returnQty', '$refundAmount', '$addedBy')";
    $res = $this->conn->query($sql);
    return $res;
}// eof addStockReturn


//stock return start-------------------

function showStockReturnDetails($returnId){
    $data = array();
    $sql  = "SELECT * FROM stock_return_details WHERE `stock_return_id` = '$returnId'";
    $res  = $this->conn->query($sql);
    while ($result = $res->fetch_array()) {
        $data[] = $result;
    }
    return $data;
}

function showStockReturnDetailsById($Id){
    
    $data = array();
    $sql  = "SELECT * FROM stock_return_details WHERE `id` = '$Id'";
    $res  = $this->conn->query($sql);
    while ($result = $res->fetch_array()) {
        $data[] = $result;
    }
    return $data;
}
//eof showStockReturn


//stockReturnDetails Edit\update function...........

function stockReturnDetailsEdit($id, $stockReturnId, $productId, $batchNo, $expDate, $unit, $purchaseQty, $freeQty, $mrp, $ptr, $purchaseAmount, $gst, $returnQty, $refundAmount, $addedBy){

    $update = "UPDATE `stock_return_details` SET `product_id`='$productId',`batch_no`='$batchNo',`exp_date`='$expDate',`unit`='$unit',`purchase_qty`='$purchaseQty',`free_qty`='$freeQty',`mrp`='$mrp',`ptr`='$ptr',`purchase_amount`='$purchaseAmount',`gst`='$gst',`return_qty`='$returnQty',`refund_amount`='$refundAmount',`added_by`='$addedBy' WHERE `id`='$id' AND `stock_return_id`='$stockReturnId' ";

    $res = $this->conn->query($update);

    return $res;
}

// ----------------- stock return details edit/update by id ----------------RD-----------

function stockReturnDetailsEditUpdate($id, $returnQTY, $refundAmount, $addedBy, $addedOn, $addedTime){
    $editUpdate = "UPDATE `stock_return_details` SET `return_qty`='$returnQTY',`refund_amount`='$refundAmount',`added_by`='$addedBy',`added_on`='$addedOn',`added_time`='$addedTime' WHERE `stock_return_details`.`id`='$id'";

    $response = $this->conn->query($editUpdate);

    return $response;
}

}


?>