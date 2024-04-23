<?php


class StockIn extends DatabaseConnection
{


    function addStockIn($distributorId, $distributorBill, $items, $totalQty, $billDate, $dueDate, $paymentMode, $Gst, $amount, $addedBy, $addedOn, $adminId)
    {

        try {
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
        } catch (Exception $e) {
            // Handle exceptions (e.g., log the error or return an error message)
            return $e; // You can customize the error handling as needed
        }
    } //eof addProduct function 



    function showStockIn($adminId = '')
    {
        try {
            $data   = array();
            if (empty($adminId)) {
                $select = "SELECT * FROM stock_in";
            } else {
                $select = "SELECT * FROM `stock_in` WHERE `admin_id`  = '$adminId' ";
            }

            $selectQuery = $this->conn->query($select);
            while ($result = $selectQuery->fetch_array()) {
                $data[] = $result;
            }
            return $data;
        } catch (Exception $e) {
            echo "Error: " . $e->getMessage();
        }
    } //eof showStockIn function





    function showStockInDecendingOrder($adminId = '')
    {
        try {
            $data = array();

            // Define the SQL query using a prepared statement
            if (!empty($adminId)) {
                $select = "SELECT * FROM stock_in WHERE `admin_id` = ? ORDER BY id ASC";
                $stmt = $this->conn->prepare($select);
                $stmt->bind_param("s", $adminId);
            } else {
                $select = "SELECT * FROM stock_in  ORDER BY id ASC";
                $stmt = $this->conn->prepare($select);
            }

            if ($stmt) {

                if ($stmt) {

                    $stmt->execute();

                    $result = $stmt->get_result();

                    if ($result) {
                        while ($row = $result->fetch_array()) {
                            $data[] = $row;
                        }
                    } else {
                        echo "Query failed: " . $this->conn->error;
                    }

                    $stmt->close();
                } else {
                    echo "Statement preparation failed: " . $this->conn->error;
                }

                return $data;
            }
        } catch (Exception $e) {
            echo "Error: " . $e->getMessage();
            return array();
        }
    }






    function maxPurchasedDistAmount($adminId = '')
    {
        try {
            if (!empty($adminId)) {
                $selectQuery = "SELECT distributor_id, SUM(amount) AS total 
                       FROM stock_in
                       WHERE admin_id = ?
                       GROUP BY distributor_id
                       ORDER BY total DESC
                       LIMIT 1";

                $stmt = $this->conn->prepare($selectQuery);
                $stmt->bind_param("s", $adminId);
            } else {
                $selectQuery = "SELECT distributor_id, SUM(amount) AS total 
                FROM stock_in
                GROUP BY distributor_id
                ORDER BY total DESC
                LIMIT 1";

                $stmt = $this->conn->prepare($selectQuery);
            }
            if ($stmt) {
                $stmt->execute();
                $result = $stmt->get_result();

                if ($result->num_rows > 0) {
                    $data = $result->fetch_object();
                    return json_encode(['status' => 1, 'message' => 'success', 'data' => $data]);
                } else {
                    return json_encode(['status' => 0, 'message' => 'empty', 'data' => '']);
                }
                $stmt->close();
            } else {
                return json_encode(['status' => 0, 'message' => 'Statement preparation failed:' . $this->conn->error, 'data' => '']);
            }
        } catch (Exception $e) {
            return json_encode(['status' => 0, 'message' => "Error: " . $e->getMessage(), 'data' => '']);
        }
    }








    function selectDistOnMaxItems($adminId = '')
    {
        try {
            if (!empty($adminId)) {
                $selectQuery = "SELECT distributor_id, COUNT(*) AS number_of_purchases
                           FROM stock_in
                           WHERE admin_id = ?
                           GROUP BY distributor_id
                           ORDER BY number_of_purchases DESC
                           LIMIT 1";

                $stmt = $this->conn->prepare($selectQuery);
                $stmt->bind_param("s", $adminId);
            } else {
                $selectQuery = "SELECT distributor_id, COUNT(*) AS number_of_purchases
                FROM stock_in
                GROUP BY distributor_id
                ORDER BY number_of_purchases DESC
                LIMIT 1";

                $stmt = $this->conn->prepare($selectQuery);
            }
            if ($stmt) {
                $stmt->execute();
                $result = $stmt->get_result();

                if ($result->num_rows > 0) {
                    $data = $result->fetch_object();
                    return json_encode(['status' => 1, 'message' => "success", 'data' => $data]);
                } else {
                    return json_encode(['status' => 0, 'message' => "empty", 'data' => '']);
                }
                $stmt->close();
            } else {
                return json_encode(['status' => 0, 'message' => "Statement preparation failed: " . $this->conn->error, 'data' => '']);
            }
        } catch (Exception $e) {
            return json_encode(['status' => 0, 'message' => "Error: " . $e->getMessage(), 'data' => '']);
        }
    }




    // ============== stock in select by stock in id ====================

    function selectStockInById($stockInId)
    {
        try {

            $select = "SELECT * FROM stock_in WHERE `id` = ?";
            $stmt = $this->conn->prepare($select);

            $stmt->bind_param("s", $stockInId);

            $stmt->execute();

            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                $ShowData = array();
                while ($row = $result->fetch_assoc()) {
                    $ShowData[] = $row;
                }
                return $ShowData;
                $stmt->close();
            } else {
                return null;
                $stmt->close();
            }
        } catch (Exception $e) {
            if ($e) {
                echo "Error: " . $e->getMessage();
            } else {
                return null;
            }
        }
    }



    // ============== stock in select query by col ===========
    function stockInByAttributeByTable($table, $data)
    {
        try {
            $ShowData = array();
            $select = "SELECT * FROM stock_in WHERE `$table` = ?";
            $stmt = $this->conn->prepare($select);

            $stmt->bind_param("s", $data);

            $stmt->execute();

            $result = $stmt->get_result();

            while ($row = $result->fetch_assoc()) {
                $ShowData[] = $row;
            }

            $stmt->close();

            return $ShowData;
        } catch (Exception $e) {
            if ($e) {
                echo "Error: " . $e->getMessage();
            } else {
                return null;
            }
        }
    }
    // ============================================================

    // =================== stock in data fetch by date range ===================

    function purchaseDatafetchByDateRange($startDt, $endDt, $adminId)
    {
        try {
            $selectStockIn = "SELECT id, amount, bill_date FROM stock_in
                              WHERE admin_id = ?
                              AND DATE(added_on) BETWEEN ? AND ?";

            $stmt = $this->conn->prepare($selectStockIn);

            if (!$stmt) {
                throw new Exception("Error in preparing SQL statement: " . $this->conn->error);
            }

            $stmt->bind_param("iss", $adminId, $startDt, $endDt);

            $stmt->execute();

            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                $data[] = $result->fetch_object(); 
                $stmt->close();
                return json_encode(['status' => '1', 'data' => $data]);
            } else {
                $stmt->close();
                return json_encode(['status' => '0', 'data' => '']);
            }
        } catch (Exception $e) {
            error_log("Error: " . $e->getMessage());      
        }
    }





    // =================== purchase today data by date range ==================
    // salse of the day in a specific date function
    function purchaseTodayByDateRange($startDate, $endDate, $adminId)
    {
        try {
            $select = "SELECT SUM(amount) AS purchase_amount, SUM(items) AS purchase_item_count 
            FROM stock_in
            WHERE admin_id = '$adminId'
            AND DATE(added_on) BETWEEN '$startDate' AND '$endDate'";

            $selectQuery = $this->conn->query($select);
            if ($selectQuery->num_rows > 0) {
                while ($result = $selectQuery->fetch_object()) {
                    $ShowData = $result;
                }
                return $ShowData;
            } else {
                return null;
            }
        } catch (Exception $e) {
            echo "Error: " . $e->getMessage();
        }
    }



    function customerPurchDayRange($startDate, $endDate, $adminId)
    {
        try {
            $select = "SELECT * FROM stock_in WHERE DATE(added_on) BETWEEN ? AND ? AND admin_id = ?";

            $stmt = $this->conn->prepare($select);

            if ($stmt) {
                $stmt->bind_param("sss", $startDate, $endDate, $adminId);
                $stmt->execute(); // Corrected here
                $result = $stmt->get_result();

                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_object()) {
                        $data[] = $row;
                    }
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
        }
    }



    // =============== update stock in on current stock deletion ========================
    function updateStockInOnModifyCurrentStock($stockInid, $items, $totalQty, $gst, $amount, $updatedBy, $updatedOn)
    {
        try {
            $updateQry = "UPDATE `stock_in` SET `items` = ?, `total_qty` = ?, `gst` = ?, `amount` = ?, `updated_by` = ?, `updated_on` = ? WHERE `id` = ?";

            $stmt = $this->conn->prepare($updateQry);

            $stmt->bind_param("iiddssi", $items, $totalQty, $gst, $amount, $updatedBy, $updatedOn, $stockInid);

            $stmt->execute();

            $result = $stmt->affected_rows;

            if ($result > 0) {
                return true;
            } else {
                return false;
            }
            $stmt->close();
        } catch (Exception $e) {
            if ($e) {
                echo "Error: " . $e->getMessage();
            } else {
                return 0;
            }
        }
    }

    // ============================== update stock in data funcion ===================================

    function updateStockIn($stockInid, $distributorId, $distributorBill, $items, $totalQty, $billDate, $dueDate, $paymentMode, $gst, $amount, $updatedBy, $updatedOn)
    {
        try {
            $updateQry = "UPDATE `stock_in` SET `distributor_bill` = ?, `distributor_id` = ?, `items` = ?,  `total_qty` = ?, `bill_date` = ?, `due_date` = ?, `payment_mode` = ?, `gst` = ?, `amount` = ?,   `updated_by` = ?, `updated_on` = ? WHERE `stock_in`.`id` = ?";
            $stmt = $this->conn->prepare($updateQry);

            $stmt->bind_param("siiisssddssi", $distributorBill, $distributorId, $items, $totalQty, $billDate,    $dueDate, $paymentMode, $gst, $amount, $updatedBy, $updatedOn, $stockInid);

            $stmt->execute();

            $result = $stmt->affected_rows;

            if ($result > 0) {
                return true;
            } else {
                return false;
            }

            $stmt->close();
        } catch (Exception $e) {
            if ($e) {
                echo "Error: " . $e->getMessage();
            } else {
                return 0;
            }
        }
    }
    // ================================================================================================






    function showStockInByTable($table1, $table2, $data1, $data2)
    {
        $data   = array();
        $select = "SELECT * FROM stock_in WHERE `$table1`= '$data1' AND `$table2`= '$data2'";
        $selectQuery = $this->conn->query($select);
        while ($result = $selectQuery->fetch_array()) {
            $data[] = $result;
        }
        return $data;
    } //eof showStockInByTable function





    // function showStockInByTables($table1, $table2, $table3, $table4, $data1, $data2, $data3, $data4)
    // {
    //     $data   = array();
    //     $select = "SELECT * FROM stock_in WHERE `$table1`= '$data1' AND `$table2`= '$data2' AND `$table3`= '$data3' AND `$table4`= '$data4'";
    //     $selectQuery = $this->conn->query($select);
    //     while ($result = $selectQuery->fetch_array()) {
    //         $data[] = $result;
    //     }
    //     return $data;
    // } //eof showStockInByTable function






    function showStockInById($distBill)
    {
        $data   = array();
        $select = "SELECT * FROM stock_in WHERE `distributor_bill`= '$distBill'";
        $selectQuery = $this->conn->query($select);
        while ($result = $selectQuery->fetch_array()) {
            $data[] = $result;
        }
        return $data;
    } //eof showStockIn function



    function stockInByDate($date)
    {
        $data   = array();
        $select = "SELECT * FROM stock_in WHERE `stock_in`.`added_on`= '$date'";
        $selectQuery = $this->conn->query($select);
        while ($result = $selectQuery->fetch_array()) {
            $data[] = $result;
        }
        return $data;
    } //eof stockInByDate function

    function stockInDistIdandDateTime($distId, $date, $time)
    {
        $data   = array();
        $select = "SELECT * FROM stock_in WHERE `stock_in`.`distributor_id`= '$distId' AND `stock_in`.`added_on`= '$date' AND `stock_in`.`added_time`= '$time'";
        $selectQuery = $this->conn->query($select);
        while ($result = $selectQuery->fetch_array()) {
            $data[] = $result;
        }
        return $data;
    } //eof stockInByDate function




    function stockIndataOnBillno($distributorId, $billNo)
    {
        $data   = array();
        $select = "SELECT * FROM stock_in WHERE `distributor_id`= '$distributorId' AND `distributor_bill`= '$billNo'";
        $selectQuery = $this->conn->query($select);
        while ($result = $selectQuery->fetch_array()) {
            $data[] = $result;
        }
        return $data;
    } //eof stockInByDist function




    function needsToPay($adminId = '')
    {
        $data = array();

        try {
            if (!empty($adminId)) {

                $sql = "SELECT * FROM stock_in WHERE `payment_mode` = 'Credit' AND admin_id = ?";
            } else {
                $sql = "SELECT * FROM stock_in WHERE `payment_mode` = 'Credit'";
            }

            $stmt = $this->conn->prepare($sql);

            if ($stmt) {
                if (!empty($adminId)) {
                    // Bind parameters
                    $stmt->bind_param('s', $adminId); // Assuming admin_id is a string
                }

                // Execute the prepared statement
                $stmt->execute();

                // Get the result set
                $result = $stmt->get_result();

                // Fetch results into an array
                while ($row = $result->fetch_assoc()) {
                    $data[] = $row;
                }

                // Close the statement
                $stmt->close();
            } else {
                throw new Exception("Error in preparing SQL statement");
            }
        } catch (Exception $e) {
            return array("error" => $e->getMessage());
        }

        return $data;
    }






    /// ===================== ///////////////// delete query \\\\\\\\\\\\\\ =====================

    function deleteStock($id)
    {
        try {
            $deleteQry = "DELETE FROM `stock_in` WHERE `id` = ?";
            $stmt = $this->conn->prepare($deleteQry);

            $stmt->bind_param("i", $id);

            $stmt->execute();

            $result = $stmt->affected_rows;

            $stmt->close();

            if ($result > 0) {
                return true;
            } else {
                return $result;
            }
        } catch (Exception $e) {
            if ($e) {
                echo "Error: " . $e->getMessage();
            } else {
                return 0;
            }
        }
    }
}//eof Products class
