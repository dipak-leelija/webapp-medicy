<?php

class StockInDetails extends DatabaseConnection
{

    function addStockInDetails($stokInid, $productId, $distBill, $batchNo, $mfdDate, $expDate, $weightage, $unit, $qty, $freeQty, $looselyCount, $mrp, $ptr, $discount, $base, $gst, $gstPerItem, $margin, $amount) {
        try {
            $insertStockInDetails = "INSERT INTO `stock_in_details` (`stokIn_id`, `product_id`, `distributor_bill`, `batch_no`, `mfd_date`, `exp_date`, `weightage`, `unit`, `qty`, `free_qty`, `loosely_count`, `mrp`, `ptr`, `discount`, `base`, `gst`, `gst_amount`, `margin`, `amount`) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
    
            // Prepare the SQL statement
            $responce = $this->conn->prepare($insertStockInDetails);
    
            if (!$responce) {
                throw new Exception("Error preparing SQL statement: " . $this->conn->error);
            }
    
            // Binding parameters
            $responce->bind_param("isssssisiiiddididdd", $stokInid, $productId, $distBill, $batchNo, $mfdDate, $expDate, $weightage, $unit, $qty, $freeQty, $looselyCount, $mrp, $ptr, $discount, $base, $gst, $gstPerItem, $margin, $amount);
    
            // Execute the prepared statement
            if ($responce->execute()) {
                // Get the ID of the newly inserted record
                $addStockInDetailsId = $this->conn->insert_id;
                // Return id and result
                return ["result" => true, "stockIn_Details_id" => $addStockInDetailsId];
            } else {
                // Handle the error (e.g., log or return an error message)
                throw new Exception("Error executing SQL statement: " . $responce->error);
            }
        } catch (Exception $e) {
            // Handle exceptions (e.g., log the error or return an error message)
            return ["result" => false, "error" => $e->getMessage()];
        }
    }
    



    function showStockInDetails()
    {
        $select = " SELECT * FROM stock_in_details ";
        $selectQuery = $this->conn->query($select);
        while ($result = $selectQuery->fetch_array()) {
            $data[] = $result;
        }
        return $data;
    } //eof showStockInDetails function



    function showStockInDetailsByTable($table1, $table2, $data1, $data2)
    {
        $data   = array();
        $select = "SELECT * FROM `stock_in_details` WHERE `$table1`= '$data1' AND `$table2`= '$data2'";
        $selectQuery = $this->conn->query($select);
        while ($result = $selectQuery->fetch_array()) {
            $data[] = $result;
        }
        return $data;
    } //eof showStockInByTable function


    function showStockInDetailsByStokinId($id)
    {
        $select = " SELECT * FROM `stock_in_details` WHERE `id`= '$id'";
        $selectQuery = $this->conn->query($select);
        while ($result = $selectQuery->fetch_array()) {
            $data[] = $result;
        }
        return $data;
    } //eof showStockInDetails function

    function showStockInDetailsByStokId($StockId)
    {
        $select = " SELECT * FROM `stock_in_details` WHERE `stokIn_id`= '$StockId'";
        $selectQuery = $this->conn->query($select);
        while ($result = $selectQuery->fetch_array()) {
            $data[] = $result;
        }
        return $data;
    } //eof showStockInDetails function


    function showStockInDetailsById($DistBill)
    {
        $select = " SELECT * FROM `stock_in_details` WHERE  `distributor_bill`= '$DistBill'";
        $selectQuery = $this->conn->query($select);
        while ($result = $selectQuery->fetch_array()) {
            $data[] = $result;
        }
        return $data;
    } //eof showStockInDetails function



    function showStockInDetailsByPId($productId)
    {
        $select = "SELECT * FROM `stock_in_details` WHERE `product_id` = '$productId'";
        $selectQuery = $this->conn->query($select);
        while ($result = $selectQuery->fetch_array()) {
            $data[] = $result;
        }
        return $data;
    } // eof showStockInDetailsByPId



    function showStockInMargin($productId)
    {
        $select = "SELECT margin FROM stock_in_details WHERE `product_id` = '$productId'";
        $selectQuery = $this->conn->query($select);
        while ($result = $selectQuery->fetch_array()) {
            $data[] = $result;
        }
        return $data;
    } // eof showStockInMargin

    function seletStokInItemsIds($stockInId)
    {
        $select = "SELECT id FROM stock_in_details WHERE `stokIn_id` = '$stockInId'";
        $selectQuery = $this->conn->query($select);
        while ($result = $selectQuery->fetch_array()) {
            $data[] = $result;
        }
        return $data;
    } // eof showStockInMargin


    function showStockInByBatch($batchNo)
    {
        $data = array();
        $sql = "SELECT * FROM stock_in_details WHERE `batch_no` = '$batchNo'";
        $sqlRes = $this->conn->query($sql);
        while ($result = $sqlRes->fetch_array()) {
            $data[] = $result;
        }
        return $data;
    } // eof stockInDelete

    function stockDistributorBillNo($batchNo, $productId)
    {

        $data = array();
        $sql = "SELECT * FROM stock_in_details WHERE `batch_no` = '$batchNo' AND `product_id` = '$productId'";
        $sqlRes = $this->conn->query($sql);
        while ($result = $sqlRes->fetch_array()) {
            $data[] = $result;
        }
        return $data;
    } // fetching stock Distributor Bill no

    function selectProduct($productId, $batchNo)
    {
        $data = array();
        $check = " SELECT * FROM `current_stock` WHERE `product_id` = '$productId' AND `batch_no` = '$batchNo' AND (`qty` > '0' OR `loosely_count` > '0') ";
        $res = $this->conn->query($check);
        while ($result = $res->fetch_array()) {
            $data[] = $result;
        }
        return $data;
    }


    function stokInDetials($productId, $billNo, $batchNo)
    {
        $data = array();
        $check = " SELECT * FROM `stock_in_details` WHERE `product_id` = '$productId' AND `batch_no` = '$batchNo' AND `distributor_bill` ='$billNo' ";
        $res = $this->conn->query($check);
        while ($result = $res->fetch_array()) {
            $data[] = $result;
        }
        return $data;
    }


    function stokInDetialsByTimeStamp($productId, $billNo, $batchNo, $timeStamp)
    {
        $data = array();
        $check = " SELECT * FROM `stock_in_details` WHERE `product_id` = '$productId' AND `batch_no` = '$batchNo' AND `distributor_bill` ='$billNo' AND `added_on` = '$timeStamp' ";
        $res = $this->conn->query($check);
        while ($result = $res->fetch_array()) {
            $data[] = $result;
        }
        return $data;
    }


    function stokInDetialsbyBillNo($billNo)
    {
        $data = array();
        $check = " SELECT * FROM `stock_in_details` WHERE `stock_in_details`.`distributor_bill` ='$billNo' ";
        $res = $this->conn->query($check);
        while ($result = $res->fetch_array()) {
            $data[] = $result;
        }
        return $data;
    }


    // function stockDetailsbyStokInBillNo($productId, $batchNo, $distBillNo){
    //     $data = array();
    //     $check = " SELECT * FROM `current_stock` WHERE `product_id` = '$productId' AND `batch_no` = '$batchNo' AND `stock_in_details`.`distributor_bill` = '$distBillNo' ";
    //     $res = $this->conn->query($check);
    //     while ($result = $res->fetch_array()) {
    //         $data[] = $result;
    //     }
    //     return $data;
    // }

    //======================================================================================== UPDATE TABEL

    function updateStockInDetailsById($id, $productId, $distBillNo, $BatchNo, $mfd, $exp, $weightage, $unit, $qty, $freeQTY, $looselyCount, $mrp, $ptr, $discount, $base, $gst, $gstAmount, $margin, $amount, $addedBy)
    {

        $update = "UPDATE `stock_in_details` SET `product_id`='$productId',`distributor_bill`='$distBillNo',`batch_no`='$BatchNo',`mfd_date`='$mfd',`exp_date`='$exp',`weightage`='$weightage',`unit`='$unit',`qty`='$qty',`free_qty`='$freeQTY',`loosely_count`='$looselyCount',`mrp`='$mrp',`ptr`='$ptr',`discount`='$discount',`base`='$base',`gst`='$gst',`gst_amount`='$gstAmount',`margin`='$margin',`amount`='$amount',`added_by`='$addedBy' WHERE `id`='$id'";

        $result = $this->conn->query($update);

        return $result;
    }


    function updateStockInDetailsByTableData($table1, $table2, $data1, $data2){
        $update = "UPDATE `stock_in_details` SET `$table1`='$data1' WHERE `$table2`='$data2'";
        $result = $this->conn->query($update);
        return $result;
    }
    //====================================== DELETE QUARRY ==================================


    function stockInDelete($distBill, $batchNo)
    {
        $delQry = "DELETE FROM stock_in_details WHERE distributor_bill = '$distBill' AND batch_no = '$batchNo'";
        $delSql = $this->conn->query($delQry);
        return $delSql;
    } // eof stockInDelete

    function stockInDeletebyId($stockInId)
    {
        $delQry = "DELETE FROM `stock_in_details` WHERE `stokIn_id` = '$stockInId'";
        $delSql = $this->conn->query($delQry);
        return $delSql;
    } // eof stockInDelete

    function stockInDeletebyDetailsId($Id)
    {

        $delQry = "DELETE FROM `stock_in_details` WHERE `id` = '$Id'";
        $delSql = $this->conn->query($delQry);
        return $delSql;
    } // eof stockInDelete

}//eof Products class
