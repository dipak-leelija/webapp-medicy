<?php

require_once 'dbconnect.php';




class CurrentStock extends DatabaseConnection{



    function addCurrentStock($productId, $batchNo, $expDate, $distributorId, $looselyCount, $looselyPrice, $weightage, $unit, $qty, $mrp, $ptr, $gst, $addedBy){

        // echo $looselyCount;
        // echo $looselyPrice;

        $insert = "INSERT INTO `current_stock` (`product_id`, `batch_no`, `exp_date`, `distributor_id`, `loosely_count`, `loosely_price`, `weightage`, `unit`, `qty`, `mrp`, `ptr`, `gst`, `added_by`) VALUES ('$productId', '$batchNo', '$expDate', '$distributorId', '$looselyCount', '$looselyPrice', '$weightage', '$unit', '$qty', '$mrp', '$ptr', '$gst', '$addedBy')";

        $res = $this->conn->query($insert);

        return $res;
    }//eof addProduct function 




    function incrCurrentStock($productId, $quantity){

        $incrCurrentStock = " UPDATE `current_stock` SET `qty` = '$quantity' WHERE `current_stock`.`product_id` = '$productId'";

        $incrCurrentStockQuery = $this->conn->query($incrCurrentStock);

        return $incrCurrentStockQuery;
    }//eof incrementCurrentStock function 



    function updateStock($productId, $batchNo, $newQuantity, $newLCount){
        $sale = " UPDATE `current_stock` SET qty = '$newQuantity', loosely_count = '$newLCount' WHERE product_id = '$productId' AND batch_no = '$batchNo'";
        $res = $this->conn->query($sale);
        return $res;
    }//eof updateStock

    //============= current stock update after edting purchase return ================== RD===========
    function updateStockByReturnEdit($productId, $batchNo, $distributor, $newQuantity, $newLCount){
        $editUpdate = " UPDATE `current_stock` SET `qty` = '$newQuantity', `loosely_count`='$newLCount' WHERE `current_stock`.`product_id` = '$productId' AND `current_stock`.`batch_no` = '$batchNo' AND `current_stock`.`distributor_id`='$distributor' ";
        $res = $this->conn->query($editUpdate);
        return $res;
    }//eof updateStock


    function currentStockGroupbyPid(){
        $data = array();
        $select = "SELECT * FROM current_stock GROUP BY `current_stock`.`product_id`";
        $selectQuery = $this->conn->query($select);
        while ($result = $selectQuery->fetch_array()) {
            $data[] = $result;
        }
        return $data;
    }//eof showCurrentStoc function
    //==============================================

    function checkStock($productId, $batchNo){
        $data = array();
        $check = " SELECT * FROM `current_stock` WHERE product_id = '$productId' AND batch_no = '$batchNo'";
        $res = $this->conn->query($check);
        while ($result = $res->fetch_array()) {
            $data[] = $result;
        }
        return $data;
    }

    // function currentStock($productId, $batchNo){
    //     $data = array();
    //     $check = " SELECT * FROM `current_stock` WHERE product_id = '$productId' AND batch_no = '$batchNo' AND (`qty` > '0' || `loosely_count` > '0') ";
    //     $res = $this->conn->query($check);
    //     while ($result = $res->fetch_array()) {
    //         $data[] = $result;
    //     }
    //     return $data;
    // }

    function showCurrentStock(){
        $data = array();
        $select = "SELECT * FROM current_stock WHERE qty > 0 OR loosely_count > 0  ORDER BY added_on ASC";
        $selectQuery = $this->conn->query($select);
        while ($result = $selectQuery->fetch_array()) {
            $data[] = $result;
        }
        return $data;
    }//eof showCurrentStoc function


    function showStockExpiry($newMnth){
        $data = array();
        $select = "SELECT * FROM current_stock  WHERE exp_date <=  '$newMnth' ORDER BY exp_date ASC";
        $selectQuery = $this->conn->query($select);
        while ($result = $selectQuery->fetch_array()) {
            $data[] = $result;
        }
        return $data;
    }//eof showCurrentStoc function

    function showCurrentStocByPId($productId){
        //echo $productId;
        $data = array();
        $select = "SELECT * FROM current_stock WHERE `current_stock`.`product_id` = '$productId' AND `current_stock`.`qty` > '0' ORDER BY added_on ASC " ;
        // echo $select;
        $selectQuery = $this->conn->query($select);
        while ($result = $selectQuery->fetch_array()) {
            $data[] = $result;
        }
        return $data;
    }//eof showCurrentStocByPId

    
    function showCurrentStocByProductId($productId){
        //echo $productId;
        $data = array();
        $select = "SELECT * FROM current_stock WHERE `current_stock`.`product_id` = '$productId'";
        // echo $select;
        $selectQuery = $this->conn->query($select);
        while ($result = $selectQuery->fetch_array()) {
            $data[] = $result;
        }
        return $data;
    }//eof showCurrentStocByProductId


    function showCurrentStocById($stockId){
        //echo $productId;
        $data = array();
        $select = "SELECT * FROM current_stock WHERE `current_stock`.`id` = '$stockId'";
        // echo $select;
        $selectQuery = $this->conn->query($select);
        while ($result = $selectQuery->fetch_array()) {
            $data[] = $result;
        }
        return $data;
    }//eof showCurrentStocByProductId


    function showCurrentStocByProductIdandBatchNo($productId, $BatchNo){
        $data = array();
        $select = "SELECT * FROM `current_stock` WHERE `current_stock`.`product_id` = '$productId' AND `current_stock`.`batch_no`='$BatchNo'";
        $selectQuery = $this->conn->query($select);
        while ($result = $selectQuery->fetch_array()) {
            $data[] = $result;
        }
        return $data;
    }//eof showCurrentStocByPIdAndProductBatchNo

    //showCurrentStocByProductIdandBatchNoDistributorId
    function showCurrentStocByPIdBNoDId($productId, $BatchNo, $distributorId){
        $data = array();
        $select = "SELECT * FROM current_stock WHERE `current_stock`.`product_id` = '$productId' AND `current_stock`.`batch_no`='$BatchNo' AND `current_stock`.`distributor_id`='$distributorId'";
        // echo $select;
        $selectQuery = $this->conn->query($select);
        while ($result = $selectQuery->fetch_array()) {
            $data[] = $result;
        }
        return $data;
    }//eof showCurrentStocByProductIdandBatchNoDistributorId


    function showCurrentStocByUnit($productId, $unitType){
        // echo $productId;
        $data = array();
        $select = "SELECT * FROM current_stock WHERE `current_stock`.`product_id` = '$productId' AND `current_stock`.`$unitType` > 0  ORDER BY added_on ASC";
        // echo $select;
        $selectQuery = $this->conn->query($select);
        while ($result = $selectQuery->fetch_array()) {
            $data[] = $result;
        }
        return $data;
    }//eof showCurrentStocByPId


    function checkStockExist($productId){
        $data = array();
        $select = "SELECT product_id FROM current_stock WHERE `current_stock`.`product_id` = '$productId'";
        $selectQuery = $this->conn->query($select);
        while ($result = $selectQuery->fetch_array()) {
            $data[] = $result;
        }
        return $data;
    }//eof checkStockExist


    function deleteCurrentStock($productId, $batchNo){

        //echo $batchNo;
        //echo $productId;

        $delQry = "DELETE FROM `current_stock` WHERE `id` = '$productId' and `batch_no` = '$batchNo' ";
        // echo $delQry.$this->conn->error;exit;
        $delSql = $this->conn->query($delQry);
        return $delSql;
    }// eof stockInDelete



}//eof Products class

