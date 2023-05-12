<?php

require_once 'dbconnect.php';




class StockIn extends DatabaseConnection{


    function addStockIn($distributorId, $distributorBill, $items, $totalQty, $billDate, $dueDate, $paymentMode, $gst, $amount, $addedBy){

        $addStockIn = "INSERT INTO `stock_in` (`distributor_id`, `distributor_bill`, `items`, `total_qty`, `bill_date`, `due_date`, `payment_mode`, `gst`, `amount`, `added_by`) VALUES ('$distributorId', '$distributorBill', '$items', '$totalQty', '$billDate', '$dueDate', '$paymentMode', '$gst', '$amount', '$addedBy')";
        // echo $addStockIn.$this->conn->error;exit;
        $addStockInQuery = $this->conn->query($addStockIn);
        // echo var_dump($addStockInQuery);exit;
        return $addStockInQuery;
    }//eof addProduct function 



    function showStockIn(){
        $data   = array();
        $select = "SELECT * FROM stock_in";
        $selectQuery = $this->conn->query($select);
        while ($result = $selectQuery->fetch_array()) {
            $data[] = $result;
        }
        return $data;
    }//eof showStockIn function



    function showStockInByTable($table1, $table2, $data1, $data2){
        $data   = array();
        $select = "SELECT * FROM stock_in WHERE `$table1`= '$data1' AND `$table2`= '$data2'";
        $selectQuery = $this->conn->query($select);
        while ($result = $selectQuery->fetch_array()) {
            $data[] = $result;
        }
        return $data;
    }//eof showStockInByTable function




    function showStockInById($distBill){
        $data   = array();
        $select = "SELECT * FROM stock_in WHERE `stock_in`.`distributor_bill`= '$distBill'";
        $selectQuery = $this->conn->query($select);
        while ($result = $selectQuery->fetch_array()) {
            $data[] = $result;
        }
        return $data;
    }//eof showStockIn function



    function stockInByDate($date){
        $data   = array();
        $select = "SELECT * FROM stock_in WHERE `stock_in`.`added_on`= '$date'";
        $selectQuery = $this->conn->query($select);
        while ($result = $selectQuery->fetch_array()) {
            $data[] = $result;
        }
        return $data;
    }//eof stockInByDate function

    function stockInDistIdandDateTime($distId, $date, $time){
        $data   = array();
        $select = "SELECT * FROM stock_in WHERE `stock_in`.`distributor_id`= '$distId' AND `stock_in`.`added_on`= '$date' AND `stock_in`.`added_time`= '$time'";
        $selectQuery = $this->conn->query($select);
        while ($result = $selectQuery->fetch_array()) {
            $data[] = $result;
        }
        return $data;
    }//eof stockInByDate function
    

    function stockInByDist($distributorId){
        $data   = array();
        $select = "SELECT * FROM stock_in WHERE `stock_in`.`distributor_id`= '$distributorId'";
        $selectQuery = $this->conn->query($select);
        while ($result = $selectQuery->fetch_array()) {
            $data[] = $result;
        }
        return $data;
    }//eof stockInByDist function


    function needsToPay(){
        $data = array();
        $sql = "SELECT items,amount FROM stock_in WHERE `stock_in`.`payment_mode` = 'Credit'";
        $sqlQuery = $this->conn->query($sql);
        while($result = $sqlQuery->fetch_array()){
            $data[]	= $result;
        }
        return $data;
    }// eof needsToPay



    function updateStockIn($distributorId, $distributorBill, $items, $totalQty, $billDate, $dueDate, $paymentMode, $gst, $amount, $addedBy){

        $updateQry = "UPDATE `stock_in` SET `stock_in`.`items` = '$items', `stock_in`.`total_qty` = '$totalQty', `stock_in`.`bill_date` = '$billDate', `stock_in`.`due_date` = '$dueDate', `stock_in`.`payment_mode` = '$paymentMode', `stock_in`.`gst` = '$gst', `stock_in`.`amount` = '$amount', `stock_in`.`added_by` = '$addedBy' WHERE `stock_in`.`distributor_bill` = '$distributorBill' AND `stock_in`.`distributor_id` = '$distributorId'";
        // echo $addStockIn.$this->conn->error;exit;
        $updateSql = $this->conn->query($updateQry);
        // echo var_dump($addStockInQuery);exit;
        return $updateSql;
    }//eof addProduct function 


    function deleteStock($id){
        
        $deleteQry = "DELETE FROM `stock_in` WHERE `id` = '$id' ";

        $deleteQry = $this->conn->query($deleteQry);
        
        return $deleteQry;
    }

}//eof Products class

