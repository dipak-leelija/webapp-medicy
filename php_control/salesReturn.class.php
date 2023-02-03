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
//     #                                                      Sales Return Details                                                    #
//     #                                                                                                                              #
//     ################################################################################################################################


    function addReturnDetails($invoiceId, $itemId, $batchNo, $weatage, $exp_date, $qty, $disc, $gst, $amount, $return, $refund, $addedBy){
        
        $insert = "INSERT INTO  sales_return_details (`invoice_id`,	`product_id`, `batch_no`, `weatage`, `exp`, `qty`, `disc`, `gst`,	`amount`, `return`, `refund`, `added_by`) VALUES  ('$invoiceId', '$itemId', '$batchNo', '$weatage', '$exp_date', '$qty', '$disc', '$gst', '$amount', '$return', '$refund', '$addedBy')";
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



//     function updateBillDetail($invoiceId, $itemId, $itemName, $batchNo, $weatage, $exp_date, $qty, $looselyCount, $mrp, $disc, $dPrice, $gst, $netGst, $amount, $addedBy){

//         $updateBill = "UPDATE pharmacy_invoice SET `item_name` = '$itemName',	`batch_no` = '$batchNo', `weatage` = '$weatage', `exp_date` = '$exp_date', `qty` = '$qty', `loosely_count` = '$looselyCount', `mrp` = '$mrp', `disc` = '$disc', `d_price` = '$dPrice', `gst` = '$gst', `gst_amount` = '$netGst', `amount` = '$amount', `added_by` = '$addedBy' WHERE `invoice_id` = '$invoiceId' AND `item_id` = '$itemId'";

//         // echo $insertEmp.$this->conn->error;
//         // exit;
//         $updateBillQuery = $this->conn->query($updateBill);
//         return $updateBillQuery;

//     }//end updateBillDetail function


}// eof LabBilling class



?>