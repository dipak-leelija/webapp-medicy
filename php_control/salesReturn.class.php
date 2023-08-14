<?php

require_once 'dbconnect.php';





class SalesReturn extends DatabaseConnection{

    function addSalesReturn($invoiceId, $patientId, $billdate, $returnDate, $items, $gstAmount, $refundAmount, $refundMode, $status, $added_by){	
        $addReturn = "INSERT INTO  sales_return (`invoice_id`, `patient_id`, `bill_date`, `return_date`, `items`, `gst_amount`, `refund_amount`, `refund_mode`, `status`, `added_by`) VALUES ('$invoiceId', '$patientId', '$billdate', '$returnDate', '$items', '$gstAmount', '$refundAmount', '$refundMode', '$status', '$added_by')";
        // echo $insertEmp.$this->conn->error;
        // exit;
        $res = $this->conn->query($addReturn);
        return $res;

    }//end addLabBill function


    function salesReturnDisplay(){
        $res  = array();
        $query = "SELECT * FROM sales_return";
        $queryres  = $this->conn->query($query);
        while($result = $queryres->fetch_array()){
            $res[]	= $result;
        }
        return $res;

    }//end employeesDisplay function

    function selectSalesReturn($table, $data){
        $res = array();
        $sql = "SELECT * FROM sales_return WHERE `$table` = '$data'";
        $sqlQuery = $this->conn->query($sql);
        while($result = $sqlQuery->fetch_array()){
            $res[]	= $result;
        }
        return $res;
    }// eof stockOutDisplayById 

    //--------------select sales return table by invoice id and patient id-------------- RD -------

    function selectSalesReturnByInvoiceIdandPatientId($invoiceId, $patientId){
        $response = array();
        $selectSalesReturn = "SELECT * FROM `sales_return` WHERE `invoice_id` = '$invoiceId' AND `patient_id` = '$patientId'";
        $query = $this->conn->query($selectSalesReturn);
        while($result = $query->fetch_array()){
            $response[] = $result;
        }
        return $response;
    }

    function seletSalesReturnByDateTime($invoiceId, $patientId, $timeStamp){
        $response = array();
        $selectSalesReturn = "SELECT * FROM `sales_return` WHERE `invoice_id` = '$invoiceId' AND `patient_id` = '$patientId' AND `added_on` = '$timeStamp'";
        $query = $this->conn->query($selectSalesReturn);
        while($result = $query->fetch_array()){
            $response[] = $result;
        }
        return $response;
    }

    function salesReturnByID($invoiceId){
        $response = array();
        $selectSalesReturn = "SELECT * FROM `sales_return` WHERE `invoice_id` = '$invoiceId'";
        $query = $this->conn->query($selectSalesReturn);
        while($result = $query->fetch_array()){
            $response[] = $result;
        }
        return $response;
    }
    //------------------------------updating sales return table-------------- RD ----------------

    function updateSalesReturn($id, $returnDate, $gstAmount, $refundAmount, $refundMode, $added_by){    
        $updateSalesReturn = "UPDATE `sales_return` SET `return_date`='$returnDate',`gst_amount`='$gstAmount',`refund_amount`='$refundAmount',`refund_mode`='$refundMode',`added_by`='$added_by' WHERE `id`='$id'";

        $update = $this->conn->query($updateSalesReturn);

        return $update;
    }

    function updateStatus($id, $status){

        $updateSalesReturn = "UPDATE `sales_return` SET `status` = '$status' WHERE `id`='$id'";

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


    function addReturnDetails($SalesReturnId, $itemId, $batchNo, $weatage, $exp_date, $disc, $gst, $taxable, $returnQty, $refund){

        $insert = "INSERT INTO  sales_return_details (`sales_return_id`, `item_id`, `batch_no`, `weatage`, `exp`, `disc`, `gst`,	`taxable`, `return_qty`, `refund_amount`) VALUES  ('$SalesReturnId', '$itemId', '$batchNo', '$weatage', '$exp_date', '$disc', '$gst', '$taxable', '$returnQty', '$refund')";
        // echo $insertEmp.$this->conn->error;
        // exit;
        $res = $this->conn->query($insert);
        return $res;

    }//end addPharmacyBillDetails function


    //--------------------fetch sales return details table data----------------RD--------------

    function selectSalesReturnList($table, $data){
        $res = array();
        $sql = "SELECT * FROM sales_return_details WHERE `$table` = '$data'";
        $sqlQuery = $this->conn->query($sql);
        while($result = $sqlQuery->fetch_array()){
            $res[]	= $result;
        }
        return $res;
    }//end stockOutDetailsById function

    function salesReturnDetialSelect($invoiceId, $productId, $batchNo){
        $response = array();
        $salesReturnDetailsData = "SELECT * FROM `sales_return_details` WHERE `invoice_id` = '$invoiceId' AND `product_id` = '$productId' AND `batch_no` = '$batchNo'";
        $query = $this->conn->query($salesReturnDetailsData);
        while($result = $query->fetch_array()){
            $response[] = $result;
        }
        return $response;
    }

    function salesReturnbyInvoiceIdsalesReturnId($invoiceId, $salesRetundid){
        $response = array();
        $salesReturnDetailsData = "SELECT * FROM `sales_return_details` WHERE `invoice_id` = '$invoiceId' AND `sales_return_id`='$salesRetundid'";
        $query = $this->conn->query($salesReturnDetailsData);
        while($result = $query->fetch_array()){
            $response[] = $result;
        }
        return $response;
    }

    function seletReturnDetailsBy($table1, $data1, $table2, $data2){
        $response = array();
        $salesReturnDetailsData = "SELECT * FROM `sales_return_details` WHERE `$table1` = '$data1' AND `$table2`='$data2'";
        $query = $this->conn->query($salesReturnDetailsData);
        while($result = $query->fetch_array()){
            $response[] = $result;
        }
        return $response;
    }


    //--------------------update sales return details table----------------RD--------------

    function updateSalesReturnDetails($salesRetunId, $invoiceId, $productID, $batchNo, $discPercent, $gstPercent, $gstAmount, $returnQTY, $refundAmount, $addedBy, $addedOn){

        $updateSalseReturnDetails = "UPDATE `sales_return_details` SET `disc`='$discPercent',`gst`='$gstPercent',`amount`='$gstAmount',`return`='$returnQTY',`refund`='$refundAmount',`added_by`='$addedBy',`added_on`='$addedOn' WHERE `sales_return_id`='$salesRetunId' AND `product_id`='$productID' AND `invoice_id`='$invoiceId' AND `batch_no` = '$batchNo'";

        $updateDetails = $this->conn->query($updateSalseReturnDetails);

        return $updateDetails;
        
    }
}
    //end of salesReturnDetails Update -------------------------------------------------------

//     function updateBillDetail($invoiceId, $itemId, $itemName, $batchNo, $weatage, $exp_date, $qty, $looselyCount, $mrp, $disc, $dPrice, $gst, $netGst, $amount, $addedBy){

//         $updateBill = "UPDATE pharmacy_invoice SET `item_name` = '$itemName',	`batch_no` = '$batchNo', `weatage` = '$weatage', `exp_date` = '$exp_date', `qty` = '$qty', `loosely_count` = '$looselyCount', `mrp` = '$mrp', `disc` = '$disc', `d_price` = '$dPrice', `gst` = '$gst', `gst_amount` = '$netGst', `amount` = '$amount', `added_by` = '$addedBy' WHERE `invoice_id` = '$invoiceId' AND `item_id` = '$itemId'";

//         // echo $insertEmp.$this->conn->error;
//         // exit;
//         $updateBillQuery = $this->conn->query($updateBill);
//         return $updateBillQuery;

//     }//end updateBillDetail function


// eof LabBilling class



?>