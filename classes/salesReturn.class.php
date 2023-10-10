<?php

require_once 'dbconnect.php';

class SalesReturn extends DatabaseConnection
{

    function addSalesReturn($invoiceId, $patientId, $billdate, $returnDate, $items, $gstAmount, $refundAmount, $refundMode, $status, $added_by)
    {

        try {
            $addReturn = "INSERT INTO  sales_return (`invoice_id`, `patient_id`, `bill_date`, `return_date`, `items`, `gst_amount`, `refund_amount`, `refund_mode`, `status`, `added_by`) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

            $res = $this->conn->prepare($addReturn);
            // binding parameters --------
            $res->bind_param("ssssssssss", $invoiceId, $patientId, $billdate, $returnDate, $items, $gstAmount, $refundAmount, $refundMode, $status, $added_by);
            // Execute the prepared statement
            if ($res->execute()) {
                // Return the ID of the newly inserted record
                return $res->insert_id;
            } else {
                // Handle the error (e.g., log or return an error message)
                throw new Exception("Error executing SQL statement: " . $res->error);
            }
        } catch (Exception $e) {
            // Handle exceptions (e.g., log the error or return an error message)
            return $e; // You can customize the error handling as needed
        }
    } //end addLabBill function



    function salesReturnDisplay()
    {
        $res  = array();
        $query = "SELECT * FROM sales_return";
        $queryres  = $this->conn->query($query);
        while ($result = $queryres->fetch_array()) {
            $res[]    = $result;
        }
        return $res;
    } //end employeesDisplay function

    function selectSalesReturn($table, $data)
    {
        $res = array();
        $sql = "SELECT * FROM sales_return WHERE `$table` = '$data'";
        $sqlQuery = $this->conn->query($sql);
        while ($result = $sqlQuery->fetch_array()) {
            $res[]    = $result;
        }
        return $res;
    } // eof stockOutDisplayById 



    function selectSalesReturnByAttribs($table1, $table2, $data1, $data2)
    {
        $res = array();
        $sql = "SELECT * FROM sales_return WHERE `$table1` = '$data1' AND `$table2` = '$data2'";
        $sqlQuery = $this->conn->query($sql);
        while ($result = $sqlQuery->fetch_array()) {
            $res[]    = $result;
        }
        return $result;
    } // eof stockOutDisplayById 



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

    function updateSalesReturn($id, $returnDate, $items, $gstAmount, $refundAmount, $refundMode, $status, $added_by)
    {
        $updateSalesReturn = "UPDATE `sales_return` SET `return_date`='$returnDate',`items` = '$items', `gst_amount`='$gstAmount',`refund_amount`='$refundAmount',`refund_mode`='$refundMode', `status` = '$status', `added_by`='$added_by' WHERE `id`='$id'";

        $update = $this->conn->query($updateSalesReturn);

        return $update;
    }

    function updateStatus($id, $status, $addedBy)
    {
        $updateSalesReturn = "UPDATE `sales_return` SET `status` = '$status', `added_by`='$addedBy' WHERE `id`='$id'";
        $update = $this->conn->query($updateSalesReturn);
        return $update;
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


    function addReturnDetails($invoiceId, $SalesReturnId, $itemId, $productId, $batchNo, $weatage, $exp_date, $mrp, $disc, $gst, $taxable, $returnQty, $refund)
    {

        $insert = "INSERT INTO  sales_return_details (`invoice_id`, `sales_return_id`, `item_id`, `product_id`, `batch_no`, `weatage`, `exp`, `mrp`, `disc`, `gst`, `taxable`, `return_qty`, `refund_amount`) VALUES  ('$invoiceId', '$SalesReturnId', '$itemId', '$productId', '$batchNo', '$weatage', '$exp_date', '$mrp', '$disc', '$gst', '$taxable', '$returnQty', '$refund')";
        // echo $insertEmp.$this->conn->error;
        // exit;
        $res = $this->conn->query($insert);
        return $res;
    } //end addPharmacyBillDetails function


    //--------------------fetch sales return details table data----------------RD--------------

    function selectSalesReturnList($table, $data)
    {
        $res = array();
        $sql = "SELECT * FROM sales_return_details WHERE `$table` = '$data'";
        $sqlQuery = $this->conn->query($sql);
        while ($result = $sqlQuery->fetch_array()) {
            $res[]    = $result;
        }
        return $res;
    } //end stockOutDetailsById function

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
        $response = array();
        $salesReturnDetailsData = "SELECT * FROM `sales_return_details` WHERE `$table1` = '$data1' AND `$table2`='$data2'";
        $query = $this->conn->query($salesReturnDetailsData);
        while ($result = $query->fetch_array()) {
            $response[] = $result;
        }
        return $response;
    }


    //--------------------update sales return details table----------------RD--------------

    function updateSalesReturnDetails($salesRetunId, $taxable, $returnQTY, $refundAmount, $addedBy)
    {

        $updateSalseReturnDetails = "UPDATE `sales_return_details` SET `taxable` = '$taxable', `return_qty`='$returnQTY',`refund_amount`='$refundAmount',`added_by`='$addedBy' WHERE `id`='$salesRetunId'";

        $updateDetails = $this->conn->query($updateSalseReturnDetails);

        return $updateDetails;
    }


    function updateSalesReturnOnStockInUpdate($itemid, $batchNo, $expDate, $addedBy)
    {
        try {
            // Construct the SQL query
            $updateSalesReturnDetails = "UPDATE `sales_return_details` SET `batch_no`=?, `exp`=?, `added_by`=? WHERE `item_id`=?";

            // Prepare the SQL statement
            $stmt = $this->conn->prepare($updateSalesReturnDetails);

            if (!$stmt) {
                throw new Exception("Error preparing update statement: " . $this->conn->error);
            }

            // Bind the parameters
            $stmt->bind_param("ssss", $batchNo, $expDate, $addedBy, $itemid);

            // Execute the prepared statement
            if ($stmt->execute()) {
                // Check if any rows were affected by the update
                $affectedRows = $stmt->affected_rows;

                // Return the result based on the affected rows
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
