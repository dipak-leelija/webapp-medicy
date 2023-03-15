<?php

require_once 'dbconnect.php';





class SalesReturn extends DatabaseConnection{

    function addSalesReturn($invoiceId, $patientId, $billdate, $returnDate, $items, $gstAmount, $refundAmount, $refundMode, $added_by){	
        $addReturn = "INSERT INTO  sales_return (`invoice_id`, `patient_id`, `bill_date`, `return_date`, `items`, `gst_amount`, `refund_amount`, `refund_mode`, `added_by`) VALUES ('$invoiceId', '$patientId', '$billdate', '$returnDate', '$items', '$gstAmount', '$refundAmount', '$refundMode', '$added_by')";
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

    function selectSalesReturnByInvoiceIdandPatientIdandDateTime($invoiceId, $patientId, $timeStamp){
        $response = array();
        $selectSalesReturn = "SELECT * FROM `sales_return` WHERE `invoice_id` = '$invoiceId' AND `patient_id` = '$patientId' AND `added_on` = '$timeStamp'";
        $query = $this->conn->query($selectSalesReturn);
        while($result = $query->fetch_array()){
            $response[] = $result;
        }
        return $response;
    }
    //------------------------------updating sales return table-------------- RD ----------------

    // function updateSalesReturn($invoiceId, $patientId, $billdate, $returnDate, $items, $gstAmount, $refundAmount, $refundMode, $added_by){    
        
    //     $updateSalesReturn = "UPDATE `sales_return` SET `bill_date`='$billdate',`return_date`='$returnDate',`items`='$items',`gst_amount`='$gstAmount',`refund_amount`='$refundAmount',`refund_mode`='$refundMode',`added_by`='$added_by',`added_on`='$billdate' WHERE `invoice_id`='$invoiceId',`patient_id`='$patientId'";
        
    // }

    //end of sales return update-----------------


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


    function addReturnDetails($SalesReturnId, $invoiceId, $itemId, $batchNo, $weatage, $exp_date, $qty, $disc, $gst, $amount, $return, $refund, $addedBy){

        $insert = "INSERT INTO  sales_return_details (`sales_return_id`, `invoice_id`,	`product_id`, `batch_no`, `weatage`, `exp`, `qty`, `disc`, `gst`,	`amount`, `return`, `refund`, `added_by`) VALUES  ('$SalesReturnId', '$invoiceId', '$itemId', '$batchNo', '$weatage', '$exp_date', '$qty', '$disc', '$gst', '$amount', '$return', '$refund', '$addedBy')";
        // echo $insertEmp.$this->conn->error;
        // exit;
        $res = $this->conn->query($insert);
        return $res;

    }//end addPharmacyBillDetails function



    
    function selectSalesReturnList($table, $data){
        $res = array();
        $sql = "SELECT * FROM sales_return_details WHERE `$table` = '$data'";
        $sqlQuery = $this->conn->query($sql);
        while($result = $sqlQuery->fetch_array()){
            $res[]	= $result;
        }
        return $res;
    }//end stockOutDetailsById function


    //--------------------fetch sales return details table data----------------RD--------------

    function selectSalesReturnDetailsbyInvoiceIdProductIdBatchNo($invoiceId, $productId, $batchNo){
        $response = array();
        $salesReturnDetailsData = "SELECT * FROM `sales_return_details` WHERE `invoice_id` = '$invoiceId' AND `product_id` = '$productId' AND `batch_no` = '$batchNo'";
        $query = $this->conn->query($salesReturnDetailsData);
        while($result = $query->fetch_array()){
            $response[] = $result;
        }
        return $response;
    }

    function salesReturnDetailsbyInvoiceId($invoiceId){
        $response = array();
        $salesReturnDetailsData = "SELECT * FROM `sales_return_details` WHERE `invoice_id` = '$invoiceId'";
        $query = $this->conn->query($salesReturnDetailsData);
        while($result = $query->fetch_array()){
            $response[] = $result;
        }
        return $response;
    }

    //--------------------update sales return details table----------------RD--------------

    function updateSalesReturnDetails(){

    }
    //end of salesReturnDetails Update ---------------------

//     function updateBillDetail($invoiceId, $itemId, $itemName, $batchNo, $weatage, $exp_date, $qty, $looselyCount, $mrp, $disc, $dPrice, $gst, $netGst, $amount, $addedBy){

//         $updateBill = "UPDATE pharmacy_invoice SET `item_name` = '$itemName',	`batch_no` = '$batchNo', `weatage` = '$weatage', `exp_date` = '$exp_date', `qty` = '$qty', `loosely_count` = '$looselyCount', `mrp` = '$mrp', `disc` = '$disc', `d_price` = '$dPrice', `gst` = '$gst', `gst_amount` = '$netGst', `amount` = '$amount', `added_by` = '$addedBy' WHERE `invoice_id` = '$invoiceId' AND `item_id` = '$itemId'";

//         // echo $insertEmp.$this->conn->error;
//         // exit;
//         $updateBillQuery = $this->conn->query($updateBill);
//         return $updateBillQuery;

//     }//end updateBillDetail function


}// eof LabBilling class



?>