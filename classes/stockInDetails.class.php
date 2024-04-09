<?php

class StockInDetails extends DatabaseConnection
{

    function addStockInDetails($stokInid, $productId, $distBill, $batchNo, $expDate, $weightage, $unit, $qty, $freeQty, $looselyCount, $mrp, $ptr, $discount, $d_price, $gst, $gstPerItem, $base, $amount, $ADDEDBY, $ADDEDON)
    {
        try {
            $insertStockInDetails = "INSERT INTO `stock_in_details` (`stokIn_id`, `product_id`, `distributor_bill`, `batch_no`, `exp_date`, `weightage`, `unit`, `qty`, `free_qty`, `loosely_count`, `mrp`, `ptr`, `discount`, `d_price`, `gst`, `gst_amount`, `base`, `amount`, `update_emp_id`, `updated_on`) VALUES ( ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

            // Prepare the SQL statement
            $responce = $this->conn->prepare($insertStockInDetails);

            if (!$responce) {
                throw new Exception("Error preparing SQL statement: " . $this->conn->error);
            }

            // Binding parameters
            $responce->bind_param("issssisiiddddidddss", $stokInid, $productId, $distBill, $batchNo, $expDate, $weightage, $unit, $qty, $freeQty, $looselyCount, $mrp, $ptr, $discount, $d_price, $base, $gst, $gstPerItem, $amount, $ADDEDBY, $ADDEDON);

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





    // ==================== select query section ===============================

    // ====== select by stockInid =========
    function showStockInDetailsByStokId($stockId)
    {
        try {
            $select = "SELECT * FROM `stock_in_details` WHERE `stokIn_id` = ?";
            $stmt = $this->conn->prepare($select);

            if (!$stmt) {
                throw new Exception("Error preparing statement: " . $this->conn->error);
            }

            $stmt->bind_param("i", $stockId);

            if (!$stmt->execute()) {
                throw new Exception("Error executing statement: " . $stmt->error);
            }

            $result = $stmt->get_result();

            if ($result === false) {
                throw new Exception("Error getting result: " . $stmt->error);
            }

            $data = array();

            if ($result->num_rows > 0) {
                while ($row = $result->fetch_array()) {
                    $data[] = $row;
                }
                return $data;
                $stmt->close();
            } else {
                return null;
            }
        } catch (Exception $e) {
            if ($e) {
                echo $e;
            }
        }
        return 0;
    }


    //======================================== UPDATE TABEL ==============================================

    function updateStockInDetailsById($id, $productId, $distBillNo, $BatchNo, $exp, $weightage, $unit, $qty, $freeQTY, $looselyCount, $mrp, $ptr, $discount, $d_price, $gst, $gstAmount, $base, $amount, $updatedBy, $updatedOn)
    {

        try {
            $update = "UPDATE `stock_in_details` SET `product_id`=?, `distributor_bill`=?, `batch_no`=?, `exp_date`=?, `weightage`=?, `unit`=?, `qty`=?, `free_qty`=?, `loosely_count`=?, `mrp`=?, `ptr`=?, `discount`=?, `d_price`=?, `gst`=?, `gst_amount`=?, `base`=?, `amount`=?, `update_emp_id`=?, `updated_on`=? WHERE `id`=?";


            $stmt = $this->conn->prepare($update);

            $stmt->bind_param("ssssisiiiddididddssi", $productId, $distBillNo, $BatchNo, $exp, $weightage, $unit, $qty, $freeQTY, $looselyCount, $mrp, $ptr, $discount, $d_price, $gst, $gstAmount, $base, $amount, $updatedBy, $updatedOn, $id);

            // return var_dump($stmt);

            $stmt->execute();

            $result = $stmt->affected_rows;

            $stmt->close();

            return $result;
        } catch (Exception $e) {
            if ($e) {
                return $e;
            } else {
                return null;
            }
        }
    }



    function updateStockInDetailsByTableData($table1, $table2, $data1, $data2)
    {
        $update = "UPDATE `stock_in_details` SET `$table1`='$data1' WHERE `$table2`='$data2'";
        $result = $this->conn->query($update);
        return $result;
    }

    // ============================== eof update table ==============================================

    // ========================= show table data start ==================================


    function showStockInDetails()
    {
        try {
            $select = "SELECT * FROM stock_in_details";
            $selectQuery = $this->conn->query($select);

            if ($selectQuery === false) {
                throw new Exception("Error executing the query: " . $this->conn->error);
            }

            $data = array();
            while ($result = $selectQuery->fetch_array()) {
                $data[] = $result;
            }

            return $data;
        } catch (Exception $e) {
            if ($e) {
                echo "Error: " . $e->getMessage();
            } else {
                return null;
            }
        }
    }



    function showStockInDetailsByStokinId($id)
    {
        try {
            $select = "SELECT * FROM `stock_in_details` WHERE `id` = ?";
            $stmt = $this->conn->prepare($select);

            $stmt->bind_param("i", $id);

            $stmt->execute();

            $result = $stmt->get_result();

            $data = array();
            while ($row = $result->fetch_assoc()) {
                $data[] = $row;
            }

            $stmt->close();

            return $data;
        } catch (Exception $e) {
            if ($e) {
                echo "Error: " . $e->getMessage();
            } else {
                return null;
            }
        }
    }



    function showStockInDetailsByPId($productId)
    {
        try {
            $select = "SELECT * FROM `stock_in_details` WHERE `product_id` = ?";
            $stmt = $this->conn->prepare($select);

            $stmt->bind_param("s", $productId);

            $stmt->execute();

            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                $data = array();
                while ($row = $result->fetch_assoc()) {
                    $data[] = $row;
                }
                return $data;

                $stmt->close();
            } else {
                return null;
                $stmt->close();
            }
        } catch (Exception $e) {
            if ($e) {
                echo "Error: " . $e->getMessage();
            } else {
                return null;
            }
        }
    }

    // =================================================================================
    // =========== function to fetch stock in details data on distributor ==============

    function stockInDetailsDataForReturnFilter($distId)
    {
        try {
            // Assuming $this->conn is your database connection
            $stmt = $this->conn->prepare("
                SELECT stock_in_details.*
                FROM stock_in
                JOIN stock_in_details ON stock_in.id = stock_in_details.stokIn_id
                WHERE stock_in.distributor_id = ?
                AND stock_in_details.qty > '0'");
    
            $stmt->bind_param('i', $distId);
    
            $stmt->execute();
    
            $result = $stmt->get_result();
            
            if($result->num_rows > 0){
                $data = array();
                while ($row = $result->fetch_object()) {
                    $data[] = $row;
                }
                
                return json_encode(['status'=>'1', 'message'=>'success', 'data'=>$data]);
            } else {
                return json_encode(['status'=>'0', 'message'=>'fails', 'data'=>'']);
            }
            $stmt->close();
        } catch (Exception $e) {
            return json_encode(['status'=>' ', 'message'=>$e->getMessage(), 'data'=>'']);
            // echo "Error: " . $e->getMessage();
        }
        return 0;
    }
    





    function showStockInDetailsByTable($table1, $table2, $data1, $data2){
        try {
            $data   = array();
            $select = "SELECT * FROM `stock_in_details` WHERE `$table1`= '$data1' AND `$table2`= '$data2'";
            $selectQuery = $this->conn->query($select);

            if ($selectQuery === false) {
                throw new Exception("Error executing the query: " . $this->conn->error);
            }

            while ($result = $selectQuery->fetch_array()) {
                $data[] = $result;
            }

            return $data;
        } catch (Exception $e) {
            return "Error: " . $e->getMessage();

        }
    }

    // ==================================================================================


    function showStockInDetailsById($DistBill)
    {
        $data = [];
        $select = " SELECT * FROM `stock_in_details` WHERE  `distributor_bill`= '$DistBill'";
        $selectQuery = $this->conn->query($select);
        while ($result = $selectQuery->fetch_array()) {
            $data[] = $result;
        }
        return $data;
    } //eof showStockInDetails function







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

    /**
     * stockDistributorBillNo name replaced
     */
    function stocInByPidAndBatch($batchNo, $productId)
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

    //====================================== DELETE QUARRY ==================================

    function stockInDeletebyDetailsId($Id)
    {
        try {
            $delQry = "DELETE FROM `stock_in_details` WHERE `id` = ?";

            $stmt = $this->conn->prepare($delQry);

            $stmt->bind_param("i", $Id);

            $stmt->execute();

            $result = $stmt->affected_rows;

            $stmt->close();

            if ($result > 0) {
                return true;
            } else {
                return false;
            }
        } catch (Exception $e) {
            if ($e) {
                return $e;
            } else {
                return 0;
            }
        }
    }




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


}//eof Products class
