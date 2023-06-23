<?php

require_once 'dbconnect.php';

class StockOut extends DatabaseConnection{

    function addStockOut($invoiceId, $customerId, $reffBy, $itemsNo, $qty, $mrp, $disc, $gst, $amount, $paymentMode, $billDate, $addedBy){
        
        $insertBill = "INSERT INTO  stock_out (`invoice_id`, `customer_id`, `reff_by`, `items`, `qty`, `mrp`, `disc`, `gst`, `amount`,	`payment_mode`, `bill_date`, `added_by`) VALUES ('$invoiceId', '$customerId', '$reffBy', '$itemsNo', '$qty', '$mrp', '$disc', '$gst', '$amount', '$paymentMode', '$billDate', '$addedBy')";
        // echo $insertEmp.$this->conn->error;
        // exit;
        $insertBillQuery = $this->conn->query($insertBill);
        return $insertBillQuery;

    }//end addLabBill function


    function stockOutDisplay(){
        $billData  = array();
        $selectBill = "SELECT * FROM stock_out";
        $billQuery  = $this->conn->query($selectBill);
        while($result = $billQuery->fetch_array()){
            $billData[]	= $result;
        }
        return $billData;

    }//end employeesDisplay function


    function stockOutDisplayById($invoiceId){
        $billData = array();
        $selectBill = "SELECT * FROM stock_out WHERE `invoice_id` = '$invoiceId'";
        // echo $selectBill.$this->conn->error;

        $billQuery = $this->conn->query($selectBill);
        while($result = $billQuery->fetch_array()){
            $billData[]	= $result;
        }
        return $billData;
    }// eof stockOutDisplayById 

    
    function updateLabBill($invoiceId, $customerId, $reffBy, $itemsNo, $qty, $mrp, $disc, $gst, $amount, $paymentMode, $billDate, $addedBy ){

        $updateBill = "UPDATE stock_out SET `customer_id` = '$customerId', `reff_by` = '$reffBy', `items` = '$itemsNo', `qty` = '$qty', `mrp` = '$mrp', `disc` = '$disc', `gst` = '$gst', `amount` = '$amount', `payment_mode` = '$paymentMode', `bill_date` = '$billDate', `added_by` = '$addedBy' WHERE `invoice_id` = '$invoiceId'";

        // echo $insertEmp.$this->conn->error;
        // exit;
        $updateBillQuery = $this->conn->query($updateBill);
        return $updateBillQuery;

    }//end updateLabBill function


    function amountSoldBy($pharmacist){
        $sold = array();
        $sql = "SELECT items,amount FROM stock_out WHERE `stock_out`.`added_by` = '$pharmacist'";
        $sqlQuery = $this->conn->query($sql);
        while($result = $sqlQuery->fetch_array()){
            $sold[]	= $result;
        }
        return $sold;
    }// eof amountSoldBy



    function soldByDate($billFromDate, $billToDate){
        $data = array();
        $sql = "SELECT items,amount FROM stock_out WHERE `stock_out`.`added_on` BETWEEN $billFromDate AND $billToDate";
        $sqlQuery = $this->conn->query($sql);
        while($result = $sqlQuery->fetch_array()){
            $data[]	= $result;
        }
        return $data;
    }// eof amountSoldBy



    function needsToCollect(){
        $data = array();
        $sql = "SELECT items,amount FROM stock_out WHERE `stock_out`.`payment_mode` = 'Credit'";
        $sqlQuery = $this->conn->query($sql);
        while($result = $sqlQuery->fetch_array()){
            $data[]	= $result;
        }
        return $data;
    }// eof amountSoldBy




    function cancelLabBill($billId, $status){
        
        $cancelBill = "UPDATE `stock_out` SET `status` = '$status' WHERE `stock_out`.`bill_id` = '$billId'";
        // echo $cancelBill.$this->conn->error;
        // exit;
        $cancelBillQuery = $this->conn->query($cancelBill);
        // echo $cancelBillQuery.$this->conn->error;
        // exit;
        return $cancelBillQuery;

    }//end cancelLabBill function


    ################################################################################################################################
    #                                                                                                                              #
    #                                BILL/INVOICE DETAILS                                           #
    #                                                                                                                              #
    ################################################################################################################################


    function addPharmacyBillDetails($invoiceId,	$itemId, $itemName,	$batchNo, $weatage,	$exp_date, $qty, $looselyCount, $mrp, $disc, $dPrice, $gst,	$netGst, $amount, $addedBy){
        
        $insert = "INSERT INTO  pharmacy_invoice (`invoice_id`,	`item_id`, `item_name`,	`batch_no`,	`weatage`,	`exp_date`, `qty`, `loosely_count`, `mrp`, `disc`, `d_price`, `gst`, `gst_amount`, `amount`, `added_by`) VALUES  ('$invoiceId', '$itemId', '$itemName',	'$batchNo', '$weatage',	'$exp_date', '$qty', '$looselyCount', '$mrp', '$disc', '$dPrice', '$gst', '$netGst', '$amount', '$addedBy')";
        // echo $insertEmp.$this->conn->error;
        // exit;
        $insertQuery = $this->conn->query($insert);
        return $insertQuery;

    }//end addPharmacyBillDetails function



    
    function stockOutDetailsById($billId){
        $billData = array();
        $selectBill = "SELECT * FROM `pharmacy_invoice` WHERE `pharmacy_invoice`.`invoice_id` = '$billId'";
        $billQuery = $this->conn->query($selectBill);
        while($result = $billQuery->fetch_array()){
            $billData[]	= $result;
        }
        return $billData;
        
    }//end stockOutDetailsById function


    
    function stockOutSelect($invoice, $productId, $batchNo){
        $billData = array();
        $selectBill = "SELECT * FROM pharmacy_invoice WHERE `invoice_id` = '$invoice' AND `item_id` = '$productId' AND `batch_no` = '$batchNo'";
        $billQuery = $this->conn->query($selectBill);
        while($result = $billQuery->fetch_array()){
            $billData[]	= $result;
        }
        return $billData;
        
    }//end of stockOutDetail fetch from pharmacy_invoice table function



    function updateBillDetail($invoiceId, $itemId, $itemName, $batchNo, $weatage, $exp_date, $qty, $looselyCount, $mrp, $disc, $dPrice, $gst, $netGst, $amount, $addedBy){

        $updateBill = "UPDATE pharmacy_invoice SET `item_name` = '$itemName',	`batch_no` = '$batchNo', `weatage` = '$weatage', `exp_date` = '$exp_date', `qty` = '$qty', `loosely_count` = '$looselyCount', `mrp` = '$mrp', `disc` = '$disc', `d_price` = '$dPrice', `gst` = '$gst', `gst_amount` = '$netGst', `amount` = '$amount', `added_by` = '$addedBy' WHERE `invoice_id` = '$invoiceId' AND `item_id` = '$itemId'";

        // echo $insertEmp.$this->conn->error;
        // exit;
        $updateBillQuery = $this->conn->query($updateBill);
        return $updateBillQuery;

    }//end updateBillDetail function


// eof LabBilling class

###########################################################################################################
#                                                                                                         #
#                                            STOCK OUT DETAILS                                            #
#                                                                                                         #
###########################################################################################################

    function addStockOutDetails($invoiceId, $productId, $batchNo, $expDate, $weightage, $unit, $qty, $looselyCount, $mrp, $ptr, $discount, $gst, $margin, $amount, $addedBy, $addedOn){

        $addStockOutDetails = "INSERT INTO `stock_out_details`(`invoice_id`, `product_id`, `batch_no`, `exp_date`, `weightage`, `unit`, `qty`, `loosely_count`, `mrp`, `ptr`, `discount`, `gst`, `margin`, `amount`, `added_by`, `added_on`) VALUES ('$invoiceId','$productId','$batchNo','$expDate','$weightage','$unit','$qty','$looselyCount','$mrp','$ptr','$discount','$gst','$margin','$amount','$addedBy','$addedOn')";

        $addDetails = $this->conn->query($addStockOutDetails);

        return $addDetails;
    }


    function stockOutDetailsDisplayById($invoiceId){
        $billData = array();
        $selectBill = "SELECT * FROM `stock_out_details` WHERE `invoice_id` = '$invoiceId'";
        // echo $selectBill.$this->conn->error;

        $billQuery = $this->conn->query($selectBill);
        while($result = $billQuery->fetch_array()){
            $billData[]	= $result;
        }
        return $billData;
    }// eof stockOutDisplayById 

    // function salesReturnDetails($invoice, $productId, $batchNo){
    //     $salesReturn = array();
    //     $selectBill = "SELECT * FROM `sales_return_details` WHERE `invoice_id` = '$invoice' AND `product_id` = '$productId' AND `batch_no` = '$batchNo'";
        
    //     $salesReturnQury = $this->conn->query($selectBill);

    //     while($result = $salesReturnQury->fetch_array()){
    //         $salesReturn[]	= $result;
    //     }
    //     return $salesReturn;

    // }//end of sales return details query

    function stockOutDetailsSelect($invoice, $productId, $batchNo){

        //$stockOutDetailData = array();
        $stockOutDetailData = array();

        $selectData= "SELECT * FROM `stock_out_details` WHERE `stock_out_details`.`invoice_id` = '$invoice' AND `stock_out_details`.`product_id` = '$productId' AND `stock_out_details`.`batch_no` = '$batchNo'";
        
        $dataQuery = $this->conn->query($selectData);

        while($result = $dataQuery->fetch_array()){
            $stockOutDetailData[]	= $result;
        }

        return $stockOutDetailData;
        
    }//end of stockOutDetail fetch from pharmacy_invoice table function
}

?>
