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


    function stockReturnStatus($returnId, $statusValue){
        $sql  = "UPDATE stock_return SET `status` = '$statusValue' WHERE `stock_return`.`id` = '$returnId'";
        // echo $sql.$this->conn->error;
        $res  = $this->conn->query($sql);
        $data = $this->conn->affected_rows;
        return $data;

    }//eof stockReturnStatus



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



function showStockReturnDetails($returnId){
    $data = array();
    $sql  = "SELECT * FROM stock_return_details WHERE `stock_return_id` = '$returnId'";
    $res  = $this->conn->query($sql);
    while ($result = $res->fetch_array()) {
        $data[] = $result;
    }
    return $data;
}//eof showStockReturn



}


?>