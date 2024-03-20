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




    //     function updateLabBill($invoiceId, $customerId, $reffBy, $itemsNo, $qty, $mrp, $disc, $gst, $amount, $paymentMode, $billDate, $addedBy ){

    //         $updateBill = "UPDATE stock_out SET `customer_id` = '$customerId', `reff_by` = '$reffBy', `items` = '$itemsNo', `qty` = '$qty', `mrp` = '$mrp', `disc` = '$disc', `gst` = '$gst', `amount` = '$amount', `payment_mode` = '$paymentMode', `bill_date` = '$billDate', `added_by` = '$addedBy' WHERE `invoice_id` = '$invoiceId'";

    //         // echo $insertEmp.$this->conn->error;
    //         // exit;
    //         $updateBillQuery = $this->conn->query($updateBill);
    //         return $updateBillQuery;

    //     }//end updateLabBill function


    //     function amountSoldBy($pharmacist){
    //         $sold = array();
    //         $sql = "SELECT items,amount FROM stock_out WHERE `stock_out`.`added_by` = '$pharmacist'";
    //         $sqlQuery = $this->conn->query($sql);
    //         while($result = $sqlQuery->fetch_array()){
    //             $sold[]	= $result;
    //         }
    //         return $sold;
    //     }// eof amountSoldBy



    //     function soldByDate($billDate){
    //         $data = array();
    //         $sql = "SELECT items,amount FROM stock_out WHERE `stock_out`.`added_on` = '$billDate'";
    //         $sqlQuery = $this->conn->query($sql);
    //         while($result = $sqlQuery->fetch_array()){
    //             $data[]	= $result;
    //         }
    //         return $data;
    //     }// eof amountSoldBy



    //     function needsToCollect(){
    //         $data = array();
    //         $sql = "SELECT items,amount FROM stock_out WHERE `stock_out`.`payment_mode` = 'Credit'";
    //         $sqlQuery = $this->conn->query($sql);
    //         while($result = $sqlQuery->fetch_array()){
    //             $data[]	= $result;
    //         }
    //         return $data;
    //     }// eof amountSoldBy




    //     function cancelLabBill($billId, $status){

    //         $cancelBill = "UPDATE `stock_out` SET `status` = '$status' WHERE `stock_out`.`bill_id` = '$billId'";
    //         // echo $cancelBill.$this->conn->error;
    //         // exit;
    //         $cancelBillQuery = $this->conn->query($cancelBill);
    //         // echo $cancelBillQuery.$this->conn->error;
    //         // exit;
    //         return $cancelBillQuery;

    //     }//end cancelLabBill function


    //     ################################################################################################################################
    //     #                                                                                                                              #
    //     #                                    Sales Return Details                                            #
    //     #                                                                                                                              #
    //     ################################################################################################################################


    function addReturnDetails($invoiceId, $SalesReturnId, $itemId, $productId, $batchNo, $weatage, $expDate, $mrp, $ptr, $disc, $gst, $gstAmount, $taxable, $returnQty, $refund, $adminId)
    {
        try {
            $insert = "INSERT INTO sales_return_details (`invoice_id`, `sales_return_id`, `item_id`, `product_id`, `batch_no`, `weatage`, `exp`, `mrp`, `ptr`, `disc`, `gst`, `gst_amount`, `taxable`, `return_qty`, `refund_amount`, `admin_id`) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
            $stmt = $this->conn->prepare($insert);

            if ($stmt) {
                $stmt->bind_param("iiissssddiiddids", $invoiceId, $SalesReturnId, $itemId, $productId, $batchNo, $weatage, $expDate, $mrp, $ptr, $disc, $gst, $gstAmount, $taxable, $returnQty, $refund, $adminId);
                $res = $stmt->execute();
                $stmt->close();
                return $res;
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
