<?php

class SalesReturn extends DatabaseConnection
{

    function addSalesReturn($invoiceId, $patientId, $billdate, $returnDate, $items, $totalQty, $gstAmount, $refundAmount, $refundMode, $status, $added_by, $addedOn, $adminId)
    {

        try {
            $addReturn = "INSERT INTO  sales_return (`invoice_id`, `patient_id`, `bill_date`, `return_date`, `items`, `total_qty`, `gst_amount`, `refund_amount`, `refund_mode`, `status`, `added_by`, `added_on`, `admin_id`) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

            $res = $this->conn->prepare($addReturn);

            $res->bind_param("isssiiddsssss", $invoiceId, $patientId, $billdate, $returnDate, $items, $totalQty, $gstAmount, $refundAmount, $refundMode, $status, $added_by, $addedOn, $adminId);

            if ($res->execute()) {

                $salesReturnId = $this->conn->insert_id;
                return ["result" => true, "sales_return_id" => $salesReturnId];
            } else {

                throw new Exception("Error executing SQL statement: " . $res->error);
            }
        } catch (Exception $e) {
            return $e;
        }
    }







    function salesReturnDisplay($adminId)
    {
        try {
            $res  = array();

            $query = "SELECT * FROM sales_return WHERE `admin_id` = '$adminId' ";

            $queryres  = $this->conn->query($query);
            while ($result = $queryres->fetch_array()) {
                $res[]    = $result;
            }
            return $res;
        } catch (Exception $e) {
            return $e->getMessage();
        }
    } //end employeesDisplay function





    function salesReturnSearch($search, $adminId)
    {
        try {
            $resData  = array();

            $selectReturnData = "SELECT * FROM `sales_return` WHERE (`invoice_id` LIKE '%$search%' OR `bill_date` LIKE '%$search%' OR `return_date` LIKE '%$search%' OR `refund_amount` LIKE '%$search%') AND `admin_id` = '$adminId'";
            
            $stmt = $this->conn->prepare($selectReturnData);

            if ($stmt) {
                $stmt->execute();
                $result = $stmt->get_result();

                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_array()) {
                        $resData[] = $row;
                    }
                    return $resData;
                } else {
                    return null;
                }
                $stmt->close();
            } else {
                echo "Statement preparation failed: " . $this->conn->error;
            }
        } catch (Exception $e) {
            return $e->getMessage();
        }
    } //end employeesDisplay function





    function selectSalesReturn($table, $data)
    {
        try {
            $res = array();

            $sql = "SELECT * FROM sales_return WHERE $table = ?";
            $stmt = $this->conn->prepare($sql);
            $stmt->bind_param("s", $data);

            if ($stmt) {

                $stmt->execute();

                $result = $stmt->get_result();

                if ($result) {
                    while ($row = $result->fetch_array()) {
                        $res[] = $row;
                    }
                } else {
                    echo "Query failed: " . $this->conn->error;
                }

                $stmt->close();
            } else {
                echo "Statement preparation failed: " . $this->conn->error;
            }

            return $res;
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }







    function selectSalesReturnByAttribs($table1, $table2, $data1, $data2)
    {
        try {
            $res = array();

            $sql = "SELECT * FROM `sales_return` WHERE $table1 = ? AND $table2 = ?";

            $stmt = $this->conn->prepare($sql);

            if ($stmt) {

                $stmt->bind_param("ss", $data1, $data2);

                $stmt->execute();

                $result = $stmt->get_result();

                if ($result) {
                    while ($row = $result->fetch_array()) {
                        $res[] = $row;
                    }
                } else {

                    echo "Query failed: " . $this->conn->error;
                }

                $stmt->close();
            } else {

                echo "Statement preparation failed: " . $this->conn->error;
            }

            return $res;
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }







    // sales retun edit update function
    function updateSalesReturn($id, $returnDate, $items, $totalQty, $gstAmount, $refundAmount, $refundMode, $updatedBy, $updatedOn)
    {
        try {
            $updateQuery = "UPDATE `sales_return` SET `return_date`=?, `items`=?, `total_qty`=?, `gst_amount`=?, `refund_amount`=?, `refund_mode`=?, `updated_by`=?, `updated_on`=? WHERE `id`=?";
            $stmt = $this->conn->prepare($updateQuery);

            if ($stmt) {
                $stmt->bind_param("siiddsssi", $returnDate, $items, $totalQty, $gstAmount, $refundAmount, $refundMode, $updatedBy, $updatedOn, $id);
                $update = $stmt->execute();
                $stmt->close();
                return $update;
            } else {
                throw new Exception("Failed to prepare the statement.");
            }
        } catch (Exception $e) {
            // Handle any exceptions that occur
            // Customize this part to suit your needs
            echo "Error: " . $e->getMessage();
            return false;
        }
    }








    //--------------select sales return table by invoice id and patient id-------------- RD -------

    function selectSalesReturnByInvoiceIdandPatientId($invoiceId, $patientId)
    {
        $response = array();
        $selectSalesReturn = "SELECT * FROM `sales_return` WHERE `invoice_id` = '$invoiceId' AND `patient_id` = '$patientId'";
        $query = $this->conn->query($selectSalesReturn);
        while ($result = $query->fetch_array()) {
            $response[] = $result;
        }
        return $response;
    }

    function seletSalesReturnByDateTime($invoiceId, $patientId, $timeStamp)
    {
        $response = array();
        $selectSalesReturn = "SELECT * FROM `sales_return` WHERE `invoice_id` = '$invoiceId' AND `patient_id` = '$patientId' AND `added_on` = '$timeStamp'";
        $query = $this->conn->query($selectSalesReturn);
        while ($result = $query->fetch_array()) {
            $response[] = $result;
        }
        return $response;
    }

    function salesReturnByID($Id)
    {
        $response = array();
        $selectSalesReturn = "SELECT * FROM `sales_return` WHERE `id` = '$Id'";
        $query = $this->conn->query($selectSalesReturn);
        while ($result = $query->fetch_array()) {
            $response[] = $result;
        }
        return $response;
    }


    function activesalesReturnByID($Id, $status)
    {
        $response = array();
        $selectSalesReturn = "SELECT * FROM `sales_return` WHERE `id` = '$Id' AND `status` = '$status'";
        $query = $this->conn->query($selectSalesReturn);
        while ($result = $query->fetch_array()) {
            $response[] = $result;
        }
        return $response;
    }


    //------------------------------updating sales return table-------------- RD ----------------



    function updateStatus($id, $status, $addedBy, $updateTime)
    {
        try {
            $updateSalesReturn = "UPDATE `sales_return` SET `status` = ?, `updated_by` = ?, `updated_on` = ? WHERE `id` = ?";
            $stmt = $this->conn->prepare($updateSalesReturn);

            $stmt->bind_param("issi", $status, $addedBy, $updateTime, $id);

            $stmt->execute();

            // $success = $stmt->affected_rows > 0;
            if ($stmt->affected_rows > 0) {
                return array('status' => '1', 'message' => 'success');
            } else {
                return array('status' => '0', 'message' => throw new Exception());
            }
            $stmt->close();
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }

    //end of sales return table update-----------------------------------------------------------

    // ============ sales retun search and filter =======================
    

    function salesReturnSearchFilter($searchVal = '', $salestartDate = '', $saleEndDate = '', $returnStartDt = '', $returnEndDt = '', $returnInitiatedBy = '',  $adminId = '') {
        try {
            // Base query
            $searchSQL = "SELECT * FROM sales_return WHERE 1=1";
            $params = array();
            $types = '';
    
            // Adding search conditions
            if (!empty($searchVal)) {
                $searchSQL .= " AND (invoice_id  LIKE '$searchVal' OR patient_id LIKE '$searchVal' OR patient_id IN (SELECT patient_id FROM patient_details WHERE name LIKE '$searchVal') OR refund_amount LIKE '$searchVal')";
            }
            
            // Adding date range condition1
            if (!empty($salestartDate) && !empty($saleEndDate)) {
                $searchSQL .= " AND DATE(bill_date) BETWEEN STR_TO_DATE('$salestartDate', '%d-%m-%Y') AND STR_TO_DATE('$saleEndDate', '%d-%m-%Y')";
            }

            // Adding date range condition2
            if (!empty($returnStartDt) && !empty($returnEndDt)) {
                $searchSQL .= " AND DATE(added_on) BETWEEN STR_TO_DATE('$returnStartDt', '%d-%m-%Y') AND STR_TO_DATE('$returnEndDt', '%d-%m-%Y')";
            }
    
            // Adding return initieted by condition
            if (!empty($returnInitiatedBy)) {
                $searchSQL .= " AND added_by = '$returnInitiatedBy'";
            }

            // Adding payment mode condition
            if (!empty($paymentMode)) {
                $searchSQL .= " AND refund_mode = '$paymentMode'";
            }
    
            // Adding admin ID condition
            if (!empty($adminId)) {
                $searchSQL .= " AND admin_id = '$adminId'";
            }
    
            // print_r($searchSQL);
            // Prepare statement
            $stmt = $this->conn->prepare($searchSQL);
            if (!$stmt) {
                throw new Exception('Statement preparation exception: ' . $this->conn->error);
            }
            
            // Bind parameters dynamically
            if (!empty($params)) {
                $stmt->bind_param($types, ...$params);
            }
            
            // Execute statement
            $stmt->execute();
            $result = $stmt->get_result();
            
            // Fetch data
            if ($result->num_rows > 0) {
                $purchaseData = array();
                while ($row = $result->fetch_object()) {
                    $purchaseData[] = $row;
                }
                return json_encode(['status' => '1', 'message' => 'success', 'data' => $purchaseData]);
            } else {
                return json_encode(['status' => '0', 'message' => 'No data found', 'data' => '']);
            }
        } catch (Exception $e) {
            return json_encode(['status' => '0', 'message' => 'Error: ' . $e->getMessage(), 'data' => '']);
        }
    }


    //     ################################################################################################################################
    //     #                                                                                                                              #
    //     #                               Sales Return etails                                            #
    //     #                                                                                                                              #
    //     ################################################################################################################################


    function addReturnDetails($invoiceId, $SalesReturnId, $itemId, $productId, $batchNo, $weatage, $expDate, $mrp, $ptr, $disc, $gst, $gstAmount, $taxable, $returnQty, $refund, $addedBy, $addedOn, $adminId)
    {
        try {
            $insert = "INSERT INTO sales_return_details (`invoice_id`, `sales_return_id`, `item_id`, `product_id`, `batch_no`, `weatage`, `exp`, `mrp`, `ptr`, `disc`, `gst`, `gst_amount`, `taxable`, `return_qty`, `refund_amount`, `updated_by`, `updated_on`, `admin_id`) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
            $stmt = $this->conn->prepare($insert);

            if ($stmt) {
                $stmt->bind_param("iiissssddiiddidsss", $invoiceId, $SalesReturnId, $itemId, $productId, $batchNo, $weatage, $expDate, $mrp, $ptr, $disc, $gst, $gstAmount, $taxable, $returnQty, $refund, $addedBy, $addedOn, $adminId);
                $res = $stmt->execute();
                $stmt->close();
                return $res;
            } else {
                throw new Exception("Failed to prepare the statement.");
            }
        } catch (Exception $e) {
            echo "Error: " . $e->getMessage();
            return false;
        }
    }







    function selectSalesReturnList($table, $data)
    {
        try {
            $res = array();

            // Define the SQL query using a prepared statement
            $sql = "SELECT * FROM sales_return_details WHERE $table = ?";

            // Prepare the SQL statement
            $stmt = $this->conn->prepare($sql);

            if ($stmt) {
                // Bind the parameter
                $stmt->bind_param("s", $data);

                // Execute the query
                $stmt->execute();

                // Get the result
                $result = $stmt->get_result();

                // Check if the query was successful
                if ($result) {
                    while ($row = $result->fetch_array()) {
                        $res[] = $row;
                    }
                } else {
                    echo "Query failed: " . $this->conn->error;
                }

                $stmt->close();
            } else {
                echo "Statement preparation failed: " . $this->conn->error;
            }

            return $res;
        } catch (Exception $e) {
            echo "Error: " . $e->getMessage();
            return array();
        }
    }







    function updateSalesReturnDetails($salesReturnId, $gstAmount, $taxable, $returnQty, $refundAmount, $updatedBy, $updatedOn)
    {
        try {
            $updateQuery = "UPDATE `sales_return_details` SET `gst_amount`=?, `taxable`=?, `return_qty`=?, `refund_amount`=?, `updated_by`=?, `updated_on`=? WHERE `id`=?";
            $stmt = $this->conn->prepare($updateQuery);

            if ($stmt) {
                $stmt->bind_param("ddidssi", $gstAmount, $taxable, $returnQty, $refundAmount, $updatedBy, $updatedOn, $salesReturnId);
                $updateDetails = $stmt->execute();
                $stmt->close();
                return $updateDetails;
            } else {
                throw new Exception("Failed to prepare the statement.");
            }
        } catch (Exception $e) {
            echo "Error: " . $e->getMessage();
            return false;
        }
    }
    //--------------------fetch sales return details table data----------------RD--------------




    function salesReturnDetialSelect($invoiceId, $productId, $batchNo)
    {
        $response = array();
        $salesReturnDetailsData = "SELECT * FROM `sales_return_details` WHERE `invoice_id` = '$invoiceId' AND `product_id` = '$productId' AND `batch_no` = '$batchNo'";
        $query = $this->conn->query($salesReturnDetailsData);
        while ($result = $query->fetch_array()) {
            $response[] = $result;
        }
        return $response;
    }

    function salesReturnbyInvoiceIdsalesReturnId($invoiceId, $salesRetundid)
    {
        $response = array();
        $salesReturnDetailsData = "SELECT * FROM `sales_return_details` WHERE `invoice_id` = '$invoiceId' AND `sales_return_id`='$salesRetundid'";
        $query = $this->conn->query($salesReturnDetailsData);
        while ($result = $query->fetch_array()) {
            $response[] = $result;
        }
        return $response;
    }




    function seletReturnDetailsBy($table1, $data1, $table2, $data2)
    {
        try {
            $response = array();
            $salesReturnDetailsData = "SELECT * FROM `sales_return_details` WHERE `$table1` = '$data1' AND `$table2`='$data2'";
            $query = $this->conn->query($salesReturnDetailsData);
            while ($result = $query->fetch_array()) {
                $response[] = $result;
            }
            return $response;
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }





    function selectReturnDetailsByColsAndTime($col1, $data1, $col2, $data2, $dateTime) {
        try {
            $salesReturnDetailsData = "SELECT * FROM `sales_return_details` 
                                        WHERE `$col1` = '$data1' 
                                        AND `$col2` = '$data2' 
                                        AND `updated_on` < '$dateTime'";

                $stmt = $this->conn->prepare($salesReturnDetailsData);
                $stmt->execute();
                $result = $stmt->get_result();

                $response = array();
                if($result->num_rows  > 0){
                    while ($row = $result->fetch_array()) {
                        $response[] = $row;
                    }
                    return json_encode(['status'=>'1', 'data'=>$response]);
                }else{
                    return json_encode(['status'=>'0', 'data'=>'']);
                }
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }
    






    function updateSalesReturnOnStockInUpdate($itemid, $batchNo, $expDate, $addedBy, $addedOn)
    {
        try {
            $updateSalesReturnDetails = "UPDATE `sales_return_details` SET `batch_no`=?, `exp`=?, `updated_by`=?, `updated_on`=? WHERE `item_id`=?";

            $stmt = $this->conn->prepare($updateSalesReturnDetails);

            if (!$stmt) {
                throw new Exception("Error preparing update statement: " . $this->conn->error);
            }

            $stmt->bind_param("ssssi", $batchNo, $expDate, $addedBy, $addedOn, $itemid);

            if ($stmt->execute()) {
                
                $affectedRows = $stmt->affected_rows;

                return ($affectedRows > 0);
            } else {
                throw new Exception("Error executing update statement: " . $stmt->error);
            }
        } catch (Exception $e) {
            return false;
        }
    }




    function updateSalesReturnOnReturnCancel($id, $returnQty, $refundAmount)
    {
        $cancelReturnDetails = "UPDATE `sales_return_details` SET `return_qty` = '$returnQty', `refund_amount`='$refundAmount' WHERE `id`='$id'";
        $cancelReturnData = $this->conn->query($cancelReturnDetails);
        return $cancelReturnData;
    }





    //================= DELETE FROM SALES RETURN DETAILS ================

    function deleteSalesReturnDetaislById($id)
    {
        $deleteReturnDetails = "DELETE FROM `sales_return_details` WHERE `id`='$id'";
        $deleteData = $this->conn->query($deleteReturnDetails);
        return $deleteData;
    }
}
