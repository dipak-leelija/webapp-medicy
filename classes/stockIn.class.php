<?php


class StockIn extends DatabaseConnection{


    function addStockIn($distributorId, $distributorBill, $items, $totalQty, $billDate, $dueDate, $paymentMode, $Gst, $amount, $addedBy, $addedOn, $adminId){

        try{
            $addStockIn = "INSERT INTO `stock_in` (`distributor_id`, `distributor_bill`, `items`, `total_qty`, `bill_date`, `due_date`, `payment_mode`, `gst`, `amount`, `added_by`, `added_on`, `admin_id`) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)"; 

            $responce =  $this->conn->prepare($addStockIn);

            // binding parameters --------
            $responce->bind_param("isisssssssss", $distributorId, $distributorBill, $items, $totalQty, $billDate, $dueDate, $paymentMode, $Gst, $amount, $addedBy, $addedOn, $adminId); 

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

    



    function showStockInDecendingOrder($adminId) {
        try {
            $data = array();
    
            // Define the SQL query using a prepared statement
            $select = "SELECT * FROM stock_in WHERE `admin_id` = ? ORDER BY id DESC";
            
            // Prepare the SQL statement
            $stmt = $this->conn->prepare($select);
    
            if ($stmt) {
                // Bind the parameter
                $stmt->bind_param("s", $adminId);
    
                // Execute the query
                $stmt->execute();
    
                // Get the result
                $result = $stmt->get_result();
    
                // Check if the query was successful
                if ($result) {
                    while ($row = $result->fetch_array()) {
                        $data[] = $row;
                    }
                } else {
                    // Handle the case where the query failed
                    echo "Query failed: " . $this->conn->error;
                }
    
                // Close the statement
                $stmt->close();
            } else {
                // Handle the case where the statement preparation failed
                echo "Statement preparation failed: " . $this->conn->error;
            }
    
            return $data;
        } catch (Exception $e) {
            // Handle any exceptions that occur
            // Customize this part to suit your needs
            echo "Error: " . $e->getMessage();
            return array();
        }
    }
    





    function selectDistOnMaxPurchase($adminId) {
        try {
            $selectQuery = "SELECT distributor_id, SUM(amount) AS total_purchase_amount 
                       FROM stock_in
                       WHERE admin_id = ?
                       GROUP BY distributor_id
                       ORDER BY total_purchase_amount DESC
                       LIMIT 1";
    
            $stmt = $this->conn->prepare($selectQuery);
    
            if ($stmt) {
                $stmt->bind_param("s", $adminId);
                $stmt->execute();
                $result = $stmt->get_result();
    
                if ($result->num_rows > 0) {
                    $data = $result->fetch_object();
                    $data = json_encode($data);
                    return $data;
                } else {
                    return null;
                }
                $stmt->close();
            } else {
                echo "Statement preparation failed: " . $this->conn->error;
            }
        } catch (Exception $e) {
            echo "Error: " . $e->getMessage();
            return null; 
        }
    }








    function selectDistOnMaxItems($adminId) {
        try {
            $selectQuery = "SELECT distributor_id, COUNT(*) AS number_of_purchases
                           FROM stock_in
                           WHERE admin_id = ?
                           GROUP BY distributor_id
                           ORDER BY number_of_purchases DESC
                           LIMIT 1";
    
            $stmt = $this->conn->prepare($selectQuery);
    
            if ($stmt) {
                $stmt->bind_param("s", $adminId);
                $stmt->execute();
                $result = $stmt->get_result();
    
                if ($result->num_rows > 0) {
                    $data = $result->fetch_object();
                    $data = json_encode($data);
                } else {
                    return null;
                }
                $stmt->close();
            } else {
                echo "Statement preparation failed: " . $this->conn->error;
            }
            return $data;
        } catch (Exception $e) {
            echo "Error: " . $e->getMessage();
            return null;
        }
    }
    
    





    





    function updateStockIn($stockInid, $distributorId, $distributorBill, $items, $totalQty, $billDate, $dueDate, $paymentMode, $gst, $amount, $updatedBy, $updatedOn){

        $updateQry = "UPDATE `stock_in` SET `distributor_bill` = '$distributorBill', `distributor_id` = '$distributorId', `items` = '$items', `total_qty` = '$totalQty', `bill_date` = '$billDate', `due_date` = '$dueDate', `payment_mode` = '$paymentMode', `gst` = '$gst', `amount` = '$amount', `updated_by` = '$updatedBy', `updated_on` = '$updatedOn' WHERE `stock_in`.`id` = '$stockInid'";
        // echo $addStockIn.$this->conn->error;exit;
        $updateSql = $this->conn->query($updateQry);
        // echo var_dump($addStockInQuery);exit;
        return $updateSql;
    }//eof addProduct function 





    

    function stockInByAttributeByTable($table, $data){
        $ShowData   = array();
        $select = "SELECT * FROM stock_in WHERE `$table`= '$data'";
        $selectQuery = $this->conn->query($select);
        while ($result = $selectQuery->fetch_array()) {
            $ShowData[] = $result;
        }
        return $ShowData;
    }














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
    

    function stockInColumns($col, $data1, $col2, $data2){
        // $data   = array();
        $select = "SELECT * FROM stock_in WHERE `$col`= '$data1' AND `$col2`= '$data2'";
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
            $data[] = $result;
        }
        return $data;
    }// eof needsToPay



    


    function deleteStock($id){
        
        $deleteQry = "DELETE FROM `stock_in` WHERE `id` = '$id' ";
        $deleteQry = $this->conn->query($deleteQry);
        return $deleteQry;
    }

}//eof Products class
