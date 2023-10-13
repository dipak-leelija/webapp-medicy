<?php


class CurrentStock extends DatabaseConnection
{

    function addCurrentStock($stockInDetailsId, $productId, $batchNo, $expDate, $distributorId, $looselyCount, $looselyPrice, $weightage, $unit, $qty, $mrp, $ptr, $gst, $addedBy, $addedOn, $adminId){
        try {
            $insert = "INSERT INTO `current_stock` (`stock_in_details_id`, `product_id`, `batch_no`, `exp_date`, `distributor_id`, `loosely_count`, `loosely_price`, `weightage`, `unit`, `qty`, `mrp`, `ptr`, `gst`, `added_by`, `added_on`, `admin_id`) 
                   VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
    
            $stmt = $this->conn->prepare($insert);

            if ($stmt) {
                $stmt->bind_param("isssiidisiddisss", $stockInDetailsId, $productId, $batchNo, $expDate, $distributorId, $looselyCount, $looselyPrice, $weightage, $unit, $qty, $mrp, $ptr, $gst, $addedBy, $addedOn, $adminId);
                $res = $stmt->execute();
                $stmt->close();
                return $res;
            } else {
                // Handle the case where the prepared statement couldn't be created
                throw new Exception("Failed to prepare the statement.");
            }
        } catch (Exception $e) {
            // Handle the exception (e.g., log the error, return an error message, etc.)
            // You can customize this part to suit your needs.
            echo "Error: " . $e->getMessage();
            return false;
        }
    }





    function incrCurrentStock($productId, $quantity)
    {

        $incrCurrentStock = " UPDATE `current_stock` SET `qty` = '$quantity' WHERE `current_stock`.`product_id` = '$productId'";

        $incrCurrentStockQuery = $this->conn->query($incrCurrentStock);

        return $incrCurrentStockQuery;
    } //eof incrementCurrentStock function 


    // ========================== CURRENT STOCK UPDATE AFTER sales RETURN =============================

    // function updateStockBStockDetialsId($stockInDetailsId, $newQuantity, $newLCount)
    // {
    //     $sale = " UPDATE `current_stock` SET `qty` = '$newQuantity', `loosely_count` = '$newLCount' WHERE `stock_in_details_id` = '$stockInDetailsId'";
    //     $res = $this->conn->query($sale);
    //     return $res;
    // } //eof updateStock (duplicate function)


    function updateStockByItemId($id, $newQty, $newLqty)
    {
        $sale = " UPDATE `current_stock` SET qty = '$newQty', loosely_count = '$newLqty' WHERE `id` = '$id'";
        $res = $this->conn->query($sale);
        return $res;
    } //eof updateStock


    // ==================== CURRENT STOCK UPDATE UPDATE SLESE RETURN EDIT =====================

    function updateStockByReturnEdit($stokInDetailsId, $newQuantity, $newLCount, $updatedBy, $updatedOn){
        try {
            $editUpdate = "UPDATE `current_stock` SET `qty` = ?, `loosely_count` = ?, `updated_by` = ?, `updated_on` = ? WHERE `stock_in_details_id` = ?";
            $stmt = $this->conn->prepare($editUpdate);

            if ($stmt) {
                $stmt->bind_param("iiiss", $newQuantity, $newLCount, $stokInDetailsId, $updatedBy, $updatedOn,);
                $res = $stmt->execute();
                $stmt->close();
                return $res;
            } else {
                throw new Exception("Failed to prepare the statement.");
            }
        } catch (Exception $e) {
            // Handle the exception (e.g., log the error, return an error message, etc.)
            // You can customize this part to suit your needs.
            echo "Error: " . $e->getMessage();
            return false;
        }
    }


    
    // function updateCurrentStockById($id, $newQuantity, $newLCount)
    // {
    //     $editUpdate = " UPDATE `current_stock` SET `qty` = '$newQuantity', `loosely_count`='$newLCount' WHERE `id` = '$id'";
    //     $res = $this->conn->query($editUpdate);
    //     return $res;
    // } //eof updateStock (duplicate function)


    // ==================== current stock update after stock in edit =========================

    function updateStockByStokinDetailsId($stokinDetailsId, $productId, $batchNo, $expDate, $distributorId, $newQuantity, $newLCount, $mrp, $ptr)
    {
        $editUpdate = " UPDATE `current_stock` SET `product_id` = '$productId', `batch_no` = '$batchNo', `exp_date` = '$expDate', `distributor_id` = '$distributorId', `loosely_count` = '$newLCount', `qty` = '$newQuantity', `mrp` = '$mrp', `ptr` = '$ptr' WHERE `current_stock`.`stock_in_details_id` = '$stokinDetailsId'";
        $res = $this->conn->query($editUpdate);
        return $res;
    } //eof updateStock



    function updateCurrentStockByStockInId($stokinDetailsId, $productId, $batchNo, $expDate, $distributorId, $looseCount, $newQuantity, $ptr, $addedby)
    {
        $editUpdate = " UPDATE `current_stock` SET `product_id` = '$productId', `batch_no` = '$batchNo', `exp_date` = '$expDate', `distributor_id` = '$distributorId', `loosely_count` = '$looseCount', `qty` = '$newQuantity', `ptr` = '$ptr', `added_by` = '$addedby' WHERE `stock_in_details_id` = '$stokinDetailsId'";
        $res = $this->conn->query($editUpdate);
        return $res;
    } //eof updateStock

    //////////////////////////////////////////////////////////////////////////////////////////////////////


    function currentStockGroupbyPid()
    {
        $data = array();
        $select = "SELECT * FROM current_stock GROUP BY `current_stock`.`product_id`";
        $selectQuery = $this->conn->query($select);
        while ($result = $selectQuery->fetch_array()) {
            $data[] = $result;
        }
        return $data;
    } //eof showCurrentStoc function
    //==============================================

    function checkStock($productId, $batchNo)
    {
        $data = array();
        $check = " SELECT * FROM `current_stock` WHERE product_id = '$productId' AND batch_no = '$batchNo'";
        $res = $this->conn->query($check);
        while ($result = $res->fetch_array()) {
            $data[] = $result;
        }
        return $data;
    }


    function showCurrentStock()
    {
        $data = array();
        $select = "SELECT * FROM current_stock WHERE qty > 0 OR loosely_count > 0  ORDER BY added_on ASC";
        $selectQuery = $this->conn->query($select);
        while ($result = $selectQuery->fetch_array()) {
            $data[] = $result;
        }
        return $data;
    } //eof showCurrentStoc function


    // use (showCurrentStocByStokInDetialsId [line number 185 in this page]) this function against (showCurrentStockbyStokInId) function for any convenience....


    function showStockExpiry($newMnth)
    {
        $data = array();
        $select = "SELECT * FROM `current_stock`  WHERE `exp_date` <= '$newMnth' ORDER BY `exp_date` ASC";
        $selectQuery = $this->conn->query($select);
        while ($result = $selectQuery->fetch_array()) {
            $data[] = $result;
        }
        return $data;
    } //eof showCurrentStoc function

    function showCurrentStocByPId($productId)
    {
        //echo $productId;
        $data = array();
        $select = "SELECT * FROM current_stock WHERE `current_stock`.`product_id` = '$productId' AND `current_stock`.`qty` > '0' ORDER BY added_on ASC ";
        // echo $select;
        $selectQuery = $this->conn->query($select);
        while ($result = $selectQuery->fetch_array()) {
            $data[] = $result;
        }
        return $data;
    } //eof showCurrentStocByPId


    function fetchAllBatchnoByPid($productId)
    {
        //echo $productId;
        $data = array();
        $select = "SELECT `current_stock`.`batch_no` FROM current_stock WHERE `current_stock`.`product_id` = '$productId' AND `current_stock`.`qty` > '0' ORDER BY added_on ASC ";
        // echo $select;
        $selectQuery = $this->conn->query($select);
        while ($result = $selectQuery->fetch_array()) {
            $data[] = $result;
        }
        return $data;
    } //eof showCurrentStocByPId



    function showCurrentStocByProductId($productId)
    {
        //echo $productId;
        $data = array();
        $select = "SELECT * FROM current_stock WHERE `product_id` = '$productId'";
        // echo $select;
        $selectQuery = $this->conn->query($select);
        while ($result = $selectQuery->fetch_array()) {
            $data[] = $result;
        }
        return $data;
    } //eof showCurrentStocByProductId


    function showCurrentStocById($stockId)
    {
        //echo $productId;
        $data = array();
        $select = "SELECT * FROM current_stock WHERE `id` = '$stockId'";
        // echo $select;
        $selectQuery = $this->conn->query($select);
        while ($result = $selectQuery->fetch_array()) {
            $data[] = $result;
        }
        return $data;
    } //eof showCurrentStocByProductId

    function showCurrentStocByStokInDetialsId($stockInDetialsId)
    {
        $data = array();
        $select = "SELECT * FROM current_stock WHERE `stock_in_details_id` = '$stockInDetialsId'";
        // echo $select;
        $selectQuery = $this->conn->query($select);
        while ($result = $selectQuery->fetch_array()) {
            $data[] = $result;
        }
        return $data;
    } //eof showCurrentStocByProductId


    function showCurrentStocByProductIdandBatchNo($productId, $BatchNo)
    {
        $data = array();
        $select = "SELECT * FROM `current_stock` WHERE `current_stock`.`product_id` = '$productId' AND `current_stock`.`batch_no`='$BatchNo'";
        $selectQuery = $this->conn->query($select);
        while ($result = $selectQuery->fetch_array()) {
            $data[] = $result;
        }
        return $data;
    } //eof showCurrentStocByPIdAndProductBatchNo

    //showCurrentStocByProductIdandBatchNoDistributorId
    function showCurrentStocByPIdBNoDId($productId, $BatchNo, $distributorId)
    {
        $data = array();
        $select = "SELECT * FROM current_stock WHERE `current_stock`.`product_id` = '$productId' AND `current_stock`.`batch_no`='$BatchNo' AND `current_stock`.`distributor_id`='$distributorId'";
        // echo $select;
        $selectQuery = $this->conn->query($select);
        while ($result = $selectQuery->fetch_array()) {
            $data[] = $result;
        }
        return $data;
    } //eof showCurrentStocByProductIdandBatchNoDistributorId


    function showCurrentStocByUnit($productId, $unitType)
    {
        // echo $productId;
        $data = array();
        $select = "SELECT * FROM current_stock WHERE `current_stock`.`product_id` = '$productId' AND `current_stock`.`$unitType` > 0  ORDER BY added_on ASC";
        // echo $select;
        $selectQuery = $this->conn->query($select);
        while ($result = $selectQuery->fetch_array()) {
            $data[] = $result;
        }
        return $data;
    } //eof showCurrentStocByPId


    function checkStockExist($productId)
    {
        $data = array();
        $select = "SELECT product_id FROM current_stock WHERE `current_stock`.`product_id` = '$productId'";
        $selectQuery = $this->conn->query($select);
        while ($result = $selectQuery->fetch_array()) {
            $data[] = $result;
        }
        return $data;
    } //eof checkStockExist




    ///////////////////////////////////////////////////////////////////////
    //=============================delete section =========================
    ///////////////////////////////////////////////////////////////////////

    function deleteCurrentStock($productId, $batchNo)
    {

        $delQry = "DELETE FROM `current_stock` WHERE `current_stock`.`product_id` = '$productId' and `batch_no` = '$batchNo' ";
        // echo $delQry.$this->conn->error;exit;
        $delSql = $this->conn->query($delQry);
        return $delSql;
    } // eof stockInDelete

    function deleteCurrentStockbyId($productId)
    {
        //echo $batchNo;
        // echo $productId;

        $delQry = "DELETE FROM `current_stock` WHERE `product_id` = '$productId'";
        // echo $delQry.$this->conn->error;exit;
        $delSql = $this->conn->query($delQry);
        // var_dump($delSql);
        return $delSql;
    } // eof stockInDelete


    function deleteCurrentStockbyStockIndetailsId($stockIndetailsID)
    {
        //echo $batchNo;
        // echo $productId;

        $delQry = "DELETE FROM `current_stock` WHERE `stock_in_details_id` = '$stockIndetailsID'";
        // echo $delQry.$this->conn->error;exit;
        $delSql = $this->conn->query($delQry);
        // var_dump($delSql);
        return $delSql;
    } // eof stockInDelete


    function deleteByTabelData($table, $data)
    {
        $delQry = "DELETE FROM `current_stock` WHERE `$table` = '$data'";
        $delSql = $this->conn->query($delQry);
        return $delSql;
    } // eof stockInDelete

}//eof Products class
