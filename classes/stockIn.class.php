<?php


class StockIn extends DatabaseConnection{


    function addStockIn($distributorId, $distributorBill, $items, $totalQty, $billDate, $dueDate, $paymentMode, $gst, $amount, $addedBy){

        try{
            $addStockIn = "INSERT INTO `stock_in` (`distributor_id`, `distributor_bill`, `items`, `total_qty`, `bill_date`, `due_date`, `payment_mode`, `gst`, `amount`, `added_by`) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)"; 

            $responce =  $this->conn->prepare($addStockIn);

            // binding parameters --------
            $responce->bind_param("ssssssssss", $distributorId, $distributorBill, $items, $totalQty, $billDate, $dueDate, $paymentMode, $gst, $amount, $addedBy); 

            // Execute the prepared statement
            if ($responce->execute()) {
                // Get the ID of the newly inserted record
                $addStockInId = $this->conn->insert_id;
                // return id and result
                return ["result" => true, "stockIn_id" => $addStockInId];
            } else {
                // Handle the error (e.g., log or return an error message)
                throw new Exception("Error executing SQL statement: " . $responce->error);
            }
        }catch (Exception $e) {
            // Handle exceptions (e.g., log the error or return an error message)
            return $e; // You can customize the error handling as needed
        }        
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

    function showStockInDecendingOrder(){
        $data   = array();
        $select = "SELECT * FROM stock_in ORDER BY stock_in.id ASC";
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



    function showStockInByTables($table1, $table2, $table3, $table4, $data1, $data2, $data3, $data4){
        $data   = array();
        $select = "SELECT * FROM stock_in WHERE `$table1`= '$data1' AND `$table2`= '$data2' AND `$table3`= '$data3' AND `$table4`= '$data4'";
        $selectQuery = $this->conn->query($select);
        while ($result = $selectQuery->fetch_array()) {
            $data[] = $result;
        }
        return $data;
    }//eof showStockInByTable function



    function stockInByAttributeByTable($table, $data){
        $ShowData   = array();
        $select = "SELECT * FROM stock_in WHERE `$table`= '$data'";
        $selectQuery = $this->conn->query($select);
        while ($result = $selectQuery->fetch_array()) {
            $ShowData[] = $result;
        }
        return $ShowData;
    }


    function showStockInById($distBill){
        $data   = array();
        $select = "SELECT * FROM stock_in WHERE `distributor_bill`= '$distBill'";
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
    

    function stockInByAttribute($attrib, $data){
        // $data   = array();
        $select = "SELECT * FROM stock_in WHERE `$attrib`= '$data'";
        $selectQuery = $this->conn->query($select);
        // while ($result = $selectQuery->fetch_array()) {
        //     $data[] = $result;
        // }
        return $selectQuery;
    }

    function stockIndataOnBillno($distributorId, $billNo){
        $data   = array();
        $select = "SELECT * FROM stock_in WHERE `distributor_id`= '$distributorId' AND `distributor_bill`= '$billNo'";
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



    function updateStockIn($stockInid, $distributorId, $distributorBill, $items, $totalQty, $billDate, $dueDate, $paymentMode, $gst, $amount, $addedBy){

        $updateQry = "UPDATE `stock_in` SET `distributor_bill` = '$distributorBill', `distributor_id` = '$distributorId', `items` = '$items', `total_qty` = '$totalQty', `bill_date` = '$billDate', `due_date` = '$dueDate', `payment_mode` = '$paymentMode', `gst` = '$gst', `amount` = '$amount', `added_by` = '$addedBy' WHERE `stock_in`.`id` = '$stockInid'";
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

