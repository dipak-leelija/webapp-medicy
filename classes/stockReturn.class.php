<?php
require_once 'dbconnect.php';



class StockReturn extends DatabaseConnection
{




    ##################################################################################################################################
    #                                                                                                                                #
    #                                            Stock Return Functions(stock_return)                                                #
    #                                                                                                                                #
    ##################################################################################################################################

    function addStockReturn($stockReturnId, $stockInId, $distributorId, $distBillNo, $returnDate, $itemQty, $totalReturnQty, $returnGst, $refundMode, $refund, $status, $addedBy, $addedOn, $Admin)
    {
        try{
            // Construct the SQL query with placeholders
            $sql = "INSERT INTO `stock_return` (`id`, `stockin_id`, `distributor_id`, `bill_no`, `return_date`, `items`, `total_qty`, `gst_amount`, `refund_mode`, `refund_amount`, `status`, `added_by`, `added_on`, `admin_id`) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

            // Prepare the SQL statement
            $stmt = $this->conn->prepare($sql);
            if (!$stmt) {
                throw new Exception("Error preparing insert statement: " . $this->conn->error);
            }

            // Bind the parameters
            $stmt->bind_param("iiissiidsdssss", $stockReturnId, $stockInId, $distributorId, $distBillNo, $returnDate, $itemQty, $totalReturnQty, $returnGst, $refundMode, $refund, $status, $addedBy, $addedOn, $Admin);

            // Execute the prepared statement
            if ($stmt->execute()) {
            // Return the ID of the newly inserted record
                $insertedId = $stmt->insert_id;
                return ["result" => true];
            } else {
                throw new Exception("Error executing insert statement: " . $stmt->error);
            }
        } catch (Exception $e) {
            return ["result" => false, "error" => $e->getMessage()];
        } 
    } // eof addStockReturn

    

    function showStockReturn()
    {
        $data = array();
        $sql  = "SELECT * FROM stock_return";
        $res  = $this->conn->query($sql);
        while ($result = $res->fetch_array()) {
            $data[] = $result;
        }
        return $data;
    } //eof showStockReturn


    function showStockReturnById($returnId)
    {
        $data = array();
        $sql  = "SELECT * FROM stock_return WHERE `id` = $returnId";
        $res  = $this->conn->query($sql);
        while ($result = $res->fetch_array()) {
            $data[] = $result;
        }
        return $data;
    } //eof showStockReturn



    function stockReturnFilter($table, $value)
    {
        $data = array();
        $sql  = "SELECT * FROM stock_return WHERE `$table` = '$value'";
        $res  = $this->conn->query($sql);
        while ($result = $res->fetch_array()) {
            $data[] = $result;
        }
        return $data;
    } //eof stockReturnFilter





    function stockReturnFilterbyDate($table, $value1, $value2){
        try{
            $data = array();
            $sql  = $sql = "SELECT * FROM `stock_return`
            WHERE DATE(`$table`) BETWEEN '$value1' AND '$value2'";
            $res  = $this->conn->query($sql);
            
            if($res->num_rows > 0){
                while ($result = $res->fetch_array()) {
                    $data[] = $result;
                }
                return $data;
            }else{
                return null;
            }
        }catch(Exception $e){
            if($e){
                echo "Exception occur : ".$e;
            }
        }
    } //eof stockReturnFilter





    function stockReturnByTables($table1, $data1, $table2, $data2)
    {
        $response = array();
        $selectSalesReturn = "SELECT * FROM `stock_return` WHERE `$table1` = '$data1' AND `$table2` = '$data2'";
        $query = $this->conn->query($selectSalesReturn);
        while ($result = $query->fetch_array()) {
            $response[] = $result;
        }
        return $response;
    }


    function stockReturnStatus($returnId, $statusValue)
    {
        $sql  = "UPDATE stock_return SET `status` = '$statusValue' WHERE `stock_return`.`id` = '$returnId'";
        // echo $sql.$this->conn->error;
        $res  = $this->conn->query($sql);
        $data = $this->conn->affected_rows;
        return $data;
    } //eof stockReturnStatus


    //stockReturn Edit\update function...........
    function stockReturnEdit($id, $distributorId, $returnDate, $items, $totalQty, $gst, $refundMode, $refundAmount, $addedBy)
    {

        $update = "UPDATE `stock_return` SET `distributor_id`='$distributorId',`return_date`='$returnDate',`items`='$items',`total_qty`='$totalQty',`gst_amount`='$gst',`refund_mode`='$refundMode',`refund_amount`='$refundAmount', `added_by`='$addedBy' WHERE `stock_return`.`id`='$id' ";

        $response = $this->conn->query($update);

        return $response;
    }

    // ---------------EDIT STOCK RETURN UPDATE FUNCTION----------------------------

    function stockReturnEditUpdate($id, $items, $totalQty, $gst, $refundMode, $refundAmount, $updatedBy, $updatedOn){
        try {
            $editANDupdate = "UPDATE `stock_return` SET `items`=?, `total_qty`=?, `gst_amount`=?, `refund_mode`=?, `refund_amount`=?, `updated_by`=?, `updated_on`=? WHERE `id`=?";
            $stmt = $this->conn->prepare($editANDupdate);

            if ($stmt) {
                $stmt->bind_param("sssssssi", $items, $totalQty, $gst, $refundMode, $refundAmount, $updatedBy,  $updatedOn, $id);
                $response = $stmt->execute();
                $stmt->close();
                return ["result" => true];
            } else {
                throw new Exception("Failed to prepare the statement.");
            }
        } catch (Exception $e) {
            // Handle the exception (e.g., log the error, return an error message, etc.)
            // Customize this part to suit your needs.
            echo "Error: " . $e->getMessage();
            return false;
        }
    }



    function updateStockReturnOnEditStockIn($table1, $data1, $updateData1, $updateData2, $addedBy)
    {

        try {
            // Construct the SQL query with placeholders
            $updateOnEditStockIn = "UPDATE `stock_return` SET `distributor_id`=?, `bill_no`=?, `added_by`=? WHERE `$table1`=? ";

            // Prepare the SQL statement
            $statement = $this->conn->prepare($updateOnEditStockIn);
            if (!$statement) {
                throw new Exception("Error preparing update statement: " . $this->conn->error);
            }

            // Bind the parameters
            $statement->bind_param("ssss", $updateData1, $updateData2, $addedBy, $data1);

            // Execute the prepared statement
            if ($statement->execute()) {
                // Check if any rows were affected by the update
                $affectedRows = $statement->affected_rows;

                // If rows were affected, return the ID of the updated data
                if ($affectedRows > 0) {
                    $updatedId = $this->conn->insert_id;
                    return ["result" => true, "id" => $updatedId]; // Modify this to return the actual ID
                } else {
                    return ["result" => false, "message" => "No rows were updated."];
                }
            } else {
                throw new Exception("Error executing update statement: " . $statement->error);
            }
        } catch (Exception $e) {
            return ["result" => false, "error" => $e->getMessage()];
        }
    }


    // ------------------------- DELETE STOCK RETURN DATA -----------------------------

    function delteStockReturnData($stockReturnId)
    {
        $delQuary = "DELETE FROM `stock_return` WHERE `id`='$stockReturnId'";
        $delFromStockReturn = $this->conn->query($delQuary);
        return $delFromStockReturn;
    }

    ###################################################################################################################################
    #                                                                                                                                 #
    #                                           Stock Return Details Functions(stock_return_details)                                  #
    #                                                                                                                                 #
    ###################################################################################################################################

    function addStockReturnDetails($stockReturnId, $stockInDetailsId, $productId, $batchNo, $expDate, $unit, $purchaseQty, $freeQty, $mrp, $ptr, $gst, $disc, $returnQty, $returnFQty, $refundAmount)
{
    try {
        $sql = "INSERT INTO stock_return_details (`stock_return_id`, `stokIn_details_id`, `product_id`, `batch_no`, `exp_date`, `unit`, `purchase_qty`, `free_qty`, `mrp`, `ptr`, `gst`, `disc`, `return_qty`, `return_free_qty`, `refund_amount`) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $this->conn->prepare($sql);

        if ($stmt) {
            $stmt->bind_param("iissssiiddiiiid", $stockReturnId, $stockInDetailsId, $productId, $batchNo, $expDate, $unit, $purchaseQty, $freeQty, $mrp, $ptr, $gst, $disc, $returnQty, $returnFQty, $refundAmount);
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



    //stock return start-------------------

    function showStockReturnDetails($returnId)
    {
        $data = array();
        $sql  = "SELECT * FROM stock_return_details WHERE `stock_return_id` = '$returnId'";
        $res  = $this->conn->query($sql);
        while ($result = $res->fetch_array()) {
            $data[] = $result;
        }
        return $data;
    }

    function showStockReturnDataByStokinId($stockInDetailsId)
    {
        $data = array();
        $sql  = "SELECT * FROM stock_return_details WHERE `stokIn_details_id` = '$stockInDetailsId'";
        $res  = $this->conn->query($sql);
        while ($result = $res->fetch_array()) {
            $data[] = $result;
        }
        return $data;
    }

    function showStockReturnDetailsById($Id)
    {

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
    function stockReturnDetailsEdit($id, $stockReturnId, $productId, $batchNo, $expDate, $unit, $purchaseQty, $freeQty, $mrp, $ptr, $purchaseAmount, $gst, $returnQty, $refundAmount, $addedBy)
    {

        $update = "UPDATE `stock_return_details` SET `product_id`='$productId',`batch_no`='$batchNo',`exp_date`='$expDate',`unit`='$unit',`purchase_qty`='$purchaseQty',`free_qty`='$freeQty',`mrp`='$mrp',`ptr`='$ptr',`purchase_amount`='$purchaseAmount',`gst`='$gst',`return_qty`='$returnQty',`refund_amount`='$refundAmount',`added_by`='$addedBy' WHERE `id`='$id' AND `stock_return_id`='$stockReturnId' ";

        $res = $this->conn->query($update);

        return $res;
    }


    // ---------------- stock return detaisl edit -----------------
    function stockReturnDetailsEditByStockInDetailsId($stockInDetailsId, $productId, $batchNo, $expDate, $unit, $purchaseQty, $freeQty, $mrp, $ptr, $discParcent, $gst, $addedBy)
    {

        try {
            // Construct the SQL query with placeholders
            $update = "UPDATE `stock_return_details` SET `product_id`=?, `batch_no`=?, `exp_date`=?, `unit`=?, `purchase_qty`=?, `free_qty`=?, `mrp`=?, `ptr`=?, `disc`=?, `gst`=?, `added_by`=? WHERE `stokIn_details_id`=?";

            // Prepare the SQL statement
            $statement = $this->conn->prepare($update);
            if (!$statement) {
                throw new Exception("Error preparing update statement: " . $this->conn->error);
            }

            // Bind the parameters
            $statement->bind_param("ssssssssssss", $productId, $batchNo, $expDate, $unit, $purchaseQty, $freeQty, $mrp, $ptr, $discParcent, $gst, $addedBy, $stockInDetailsId);

            // Execute the prepared statement
            if ($statement->execute()) {
                // Check if any rows were affected by the update
                $affectedRows = $statement->affected_rows;

                // Return the result based on the affected rows
                return ($affectedRows > 0);
            } else {
                throw new Exception("Error executing update statement: " . $statement->error);
            }
        } catch (Exception $e) {
            return false;
        }
    } // edit on stock in details id ===========


    // ----------------- stock return details edit/update by id ----------------RD-----------
    function stockReturnDetailsEditUpdate($id, $returnQTY, $returnFQTY, $refundAmount, $updatedBy, $updatedOn){

        try{
            $editUpdate = "UPDATE `stock_return_details` SET  `return_qty`= ?, `return_free_qty` =  ?,`refund_amount`= ?,`updated_by`= ?, `updated_on` =  ? WHERE `id`= ?";

            // Prepare the SQL statement
            $statement = $this->conn->prepare($editUpdate);
            if (!$statement) {
                throw new Exception("Error preparing update statement: " . $this->conn->error);
            }

            // Bind the parameters
            $statement->bind_param("iiidss", $returnQTY, $returnFQTY, $refundAmount, $updatedBy, $updatedOn, $id);

            // Execute the prepared statement
            if ($statement->execute()) {
                // Check if any rows were affected by the update
                $affectedRows = $statement->affected_rows;
                // Return the result based on the affected rows
                return ($affectedRows > 0);
            } else {
                throw new Exception("Error executing update statement: " . $statement->error);
            }
        }catch (Exception $e) {
            return false;
        }
    }

    // ---------------------- STOCK RETURN DETAILS ITEMS DELETE -----------------------------

    function delteStockReturnDetailsbyReturnId($stockReturnedId)
    {
        $delQuary = "DELETE FROM `stock_return_details` WHERE `stock_return_id`='$stockReturnedId'";
        $delByReturnId = $this->conn->query($delQuary);
        return $delByReturnId;
    }

    function delteStockReturnDetailsbyItemId($stockReturnDetailsId)
    {
        $delQuary = "DELETE FROM `stock_return_details` WHERE `id`='$stockReturnDetailsId'";
        $delbyId = $this->conn->query($delQuary);
        return $delbyId;
    }

    function deleteStockByTableData($table, $data)
    {
        $delQuary = "DELETE FROM `stock_return_details` WHERE `$table`='$data'";
        $delbyId = $this->conn->query($delQuary);
        return $delbyId;
    }
}
