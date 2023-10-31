<?php

class StockOut extends DatabaseConnection{

    function addStockOut($invoiceId, $customerId, $reffBy, $items, $qty, $mrp, $disc, $gst, $amount, $paymentMode, $status, $billDate, $addedBy, $addedOn, $adminId) {
        try {
            // Prepare the SQL statement with placeholders
            $insertBill = "INSERT INTO stock_out (`invoice_id`, `customer_id`, `reff_by`, `items`, `qty`, `mrp`, `disc`, `gst`, `amount`, `payment_mode`, `status`, `bill_date`, `added_by`, `added_on`, `admin_id`) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
            
            // Prepare and bind the parameters
            $stmt = $this->conn->prepare($insertBill);
            $stmt->bind_param("issiidsddssssss", $invoiceId, $customerId, $reffBy, $items, $qty, $mrp, $disc, $gst, $amount, $paymentMode, $status, $billDate, $addedBy, $addedOn, $adminId);
            
            // Execute the statement
            if ($stmt->execute()) {
                // Insert successful, return the inserted ID
                $insertedId = $stmt->insert_id;
                $stmt->close();
                return array("success" => true, "insert_id" => $insertedId);
            } else {
                // Insert failed
                throw new Exception("Error inserting data into the database: " . $stmt->error);
            }
        } catch (Exception $e) {
            // Handle the exception, log the error, or return an error message as needed
            return array("success" => false, "error" => "Error: " . $e->getMessage());
        }
    }
    

    


    function stockOutDisplay($adminId) {
        try {
            $billData = array();
    
            // Define the SQL query using a prepared statement
            $selectBill = "SELECT * FROM stock_out WHERE `admin_id` = ?";
            
            // Prepare the SQL statement
            $stmt = $this->conn->prepare($selectBill);
    
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
                        $billData[] = $row;
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
    
            return $billData;
        } catch (Exception $e) {
            // Handle any exceptions that occur
            // You can customize this part to suit your needs
            echo "Error: " . $e->getMessage();
            return array();
        }
    }
    




    // fethc sold amount on admin id
    function amountSoldByAll($adminId){
        try {
            $sold = array();
            $sql = "SELECT items,amount FROM stock_out WHERE `admin_id` = '$adminId'";
            $sqlQuery = $this->conn->query($sql);
            
            if ($sqlQuery) {
                while ($result = $sqlQuery->fetch_array()) {
                    $sold[] = $result;
                }
            } else {
                throw new Exception("Error executing SQL query");
            }
            
            return $sold;
        } catch (Exception $e) {
            // Handle the exception here, e.g., log the error or return an empty array
            error_log("Error: " . $e->getMessage());
            return array(); // Return an empty array in case of an error
        }
    }






    function stokOutDataByTwoCol($table1, $data1, $table2, $data2) {
        try {
            $stockOutSelect = array();
    
            // Define the SQL query using a prepared statement
            $selectSql = "SELECT * FROM `stock_out` WHERE $table1 = ? AND $table2 = ?";
            
            // Prepare the SQL statement
            $stmt = $this->conn->prepare($selectSql);
    
            if ($stmt) {
                // Bind the parameters
                $stmt->bind_param("ss", $data1, $data2);
    
                // Execute the query
                $stmt->execute();
    
                // Get the result
                $result = $stmt->get_result();
    
                // Check if the query was successful
                if ($result) {
                    while ($row = $result->fetch_array()) {
                        $stockOutSelect[] = $row;
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
    
            return $stockOutSelect;
        } catch (Exception $e) {
            // Handle any exceptions that occur
            // You can customize this part to suit your needs
            echo "Error: " . $e->getMessage();
            return array();
        }
    }






    function updateStockOut($invoiceId, $customerId, $reffBy, $itemsNo, $qty, $mrp, $disc, $gst, $amount, $paymentMode, $billDate, $updatedBy, $updatedOn) {
        try {
            $updateBill = "UPDATE stock_out SET `customer_id` = ?, `reff_by` = ?, `items` = ?, `qty` = ?, `mrp` = ?, `disc` = ?, `gst` = ?, `amount` = ?, `payment_mode` = ?, `bill_date` = ?, `updated_by` = ?, `updated_on` = ? WHERE `invoice_id` = ?";
            $stmt = $this->conn->prepare($updateBill);
    
            if ($stmt) {
                $stmt->bind_param("ssiiddddssssi", $customerId, $reffBy, $itemsNo, $qty, $mrp, $disc, $gst, $amount, $paymentMode, $billDate, $updatedBy, $updatedOn, $invoiceId);
                $updateBillQuery = $stmt->execute();
                $stmt->close();
                return $updateBillQuery;
            } else {
                throw new Exception("Failed to prepare the statement.");
            }
        } catch (Exception $e) {
            // Handle any exceptions that occur
            // You can customize this part to suit your needs
            echo "Error: " . $e->getMessage();
            return false;
        }
    }
    





    function mostVisitCustomersByDay($adminId) {
        try {
            $selectQuery = "SELECT customer_id, COUNT(*) AS visit_count
            FROM stock_out
            WHERE admin_id = ? AND DATE(added_on) = CURDATE()
            GROUP BY customer_id
            ORDER BY visit_count DESC
            LIMIT 10";
            
            $stmt = $this->conn->prepare($selectQuery);
    
            if ($stmt) {
                $stmt->bind_param("s", $adminId);
                $stmt->execute();
                $result = $stmt->get_result();
    
                if ($result->num_rows > 0) {
                    $data = array();
                    while ($row = $result->fetch_object()) {
                        $data[] = $row;
                    }
                    return $data;
                } else {
                    echo "Query returned no results.";
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

    


    // fethc sold amount on admin id and employee id
    function amountSoldByEmployee($pharmacist, $adminId){
        $sold = array();
        $sql = "SELECT items,amount FROM stock_out WHERE `added_by` = '$pharmacist' AND `admin_id` = '$adminId'";
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


//     #############################################################################################################
//     #                                                                                                           #
//     #                                BILL / PHARMACY INVOICE DETAILS                         #
//     #                                                                                                           #
//     #############################################################################################################


//     function addPharmacyBillDetails($invoiceId,	$itemId, $itemName,	$batchNo, $weatage,	$exp_date, $qty, $looselyCount, $mrp, $disc, $taxable, $gst,	$netGst, $amount, $addedBy){
        
//         $insert = "INSERT INTO  pharmacy_invoice (`invoice_id`,	`item_id`, `item_name`,	`batch_no`,	`weatage`,	`exp_date`, `qty`, `loosely_count`, `mrp`, `disc`, `taxable`, `gst`, `gst_amount`, `amount`, `added_by`) VALUES  ('$invoiceId', '$itemId', '$itemName',	'$batchNo', '$weatage',	'$exp_date', '$qty', '$looselyCount', '$mrp', '$disc', '$taxable', '$gst', '$netGst', '$amount', '$addedBy')";
//         // echo $insertEmp.$this->conn->error;
//         // exit;
//         $insertQuery = $this->conn->query($insert);
//         return $insertQuery;

//     }//end addPharmacyBillDetails function



    
//     function stockOutDetailsById($billId){
//         $billData = array();
//         $selectBill = "SELECT * FROM `pharmacy_invoice` WHERE `invoice_id` = '$billId'";
//         $billQuery = $this->conn->query($selectBill);
//         while($result = $billQuery->fetch_array()){
//             $billData[]	= $result;
//         }
//         return $billData;
        
//     }//end stockOutDetailsById function

//     function invoiceDetialsByTableData($table, $data){
//         $billData = array();
//         $selectBill = "SELECT * FROM pharmacy_invoice WHERE `$table` = '$data'";
//         $billQuery = $this->conn->query($selectBill);
//         while($result = $billQuery->fetch_array()){
//             $billData[]	= $result;
//         }
//         return $billData;
//     }//end of stockOutDetail fetch from pharmacy_invoice table function



//     function invoiceDetialsByTables($table1, $data1, $table2, $data2){
//         $billData = array();
//         $selectBill = "SELECT * FROM pharmacy_invoice WHERE `$table1` = '$data1' AND `$table2` = '$data2'";
//         $billQuery = $this->conn->query($selectBill);
//         while($result = $billQuery->fetch_array()){
//             $billData[]	= $result;
//         }
//         return $billData;
//     }//end of stockOutDetail fetch from pharmacy_invoice table function

    

    // function stockOutSelect($invoice, $itemId){
    //     $billData = array();
    //     $selectBill = "SELECT * FROM pharmacy_invoice WHERE `invoice_id` = '$invoice' AND `item_id` = '$itemId'";
    //     $billQuery = $this->conn->query($selectBill);
    //     while($result = $billQuery->fetch_array()){
    //         $billData[]	= $result;
    //     }
    //     return $billData;
        
    // }//end of stockOutDetail fetch from pharmacy_invoice table function



//     function updatePharmacyDataById($id, $qty, $looseCount, $disc, $taxable, $gstAmount, $Amount, $addedBy){
//         $updateDetails = "UPDATE `pharmacy_invoice` SET `qty`='$qty',`loosely_count`='$looseCount',`disc`='$disc',`taxable`='$taxable',`gst_amount`= '$gstAmount',`amount`='$Amount',`added_by`='$addedBy' WHERE `id`='$id'";
//         $updateBillQuery = $this->conn->query($updateDetails);
//         return $updateBillQuery;
//     }



//     function updatePharmacyDataByStockInEdit($itemId, $batchNo, $expDate, $addedBy){
//         $updateDetails = "UPDATE `pharmacy_invoice` SET `batch_no`='$batchNo',`exp_date`='$expDate',`added_by`='$addedBy' WHERE `item_id`='$itemId'";
//         $updateBillQuery = $this->conn->query($updateDetails);
//         return $updateBillQuery;
//     }



//     //======= delet function === delete from item_invocie table =================
//     function delteItemFromInvoice($id){
//         $delete = "DELETE FROM `pharmacy_invoice` WHERE `id` = '$id'";
//         $delteQuery = $this->conn->query($delete);
//         return $delteQuery;
//     }

// // eof LabBilling class




###########################################################################################################
#                                                                                                         #
#                                            STOCK OUT DETAILS                                            #
#                                                                                                         #
###########################################################################################################
                 

    function addStockOutDetails($invoiceId, $itemId, $productId, $productName, $batchNo, $expDate, $weightage, $unit, $qty, $looselyCount, $mrp, $ptr, $discount, $gst, $gstAmount, $margin, $taxable, $amount){
        try{
            $addStockOutDetails = $this->conn->prepare("INSERT INTO `stock_out_details`(`invoice_id`, `item_id`, `product_id`, `item_name`, `batch_no`, `exp_date`, `weightage`, `unit`, `qty`, `loosely_count`, `mrp`, `ptr`, `discount`, `gst`, `gst_amount`, `margin`, `taxable`, `amount`) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");

            if (!$addStockOutDetails) {
                return false; // Return false on query failure
            }

            $addStockOutDetails->bind_param("iissssssssddssdddd", $invoiceId, $itemId, $productId, $productName, $batchNo, $expDate, $weightage, $unit, $qty, $looselyCount, $mrp, $ptr, $discount, $gst, $gstAmount, $margin, $taxable, $amount);

            if ($addStockOutDetails->execute()) {
                $addStockOutDetails->close();
                return true; // Return true on successful insertion
            } else {
                $addStockOutDetails->close();
                return false; // Return false on execution failure
            }

        }catch (Exception $e) {
            return false;
        }
    }





    function updateStockOutDetaislById($id, $qty, $looseQty, $disc, $margin, $taxable, $gstAmount, $amount, $updatedBy, $updatedOn) {
        try {
            $updateQuery = "UPDATE `stock_out_details` SET `qty`=?, `loosely_count`=?, `discount`=?, `margin`=?, `taxable`=?, `gst_amount`=?, `amount`=?, `updated_by`=?, `updated_on`=? WHERE `id`=?";
            $stmt = $this->conn->prepare($updateQuery);
    
            if ($stmt) {
                $stmt->bind_param("iiiddddssi", $qty, $looseQty, $disc, $margin, $taxable, $gstAmount, $amount, $updatedBy, $updatedOn, $id);

                if ($stmt->execute()) {
                    $stmt->close();
                    return true; // Return true on successful insertion
                } else {
                    $stmt->close();
                    return false; // Return false on execution failure
                }

            } else {
                throw new Exception("Failed to prepare the statement.");
            }
        } catch (Exception $e) {
            // Handle any exceptions that occur
            // You can customize this part to suit your needs
            echo "Error: " . $e->getMessage();
            return false;
        }
    }
    




    function stokOutDetailsDataByTwoCol($table1, $data1, $table2, $data2) {
        try {
            $stockOutSelect = array();
    
            // Define the SQL query using a prepared statement
            $selectSql = "SELECT * FROM `stock_out_details` WHERE $table1 = ? AND $table2 = ?";
            
            // Prepare the SQL statement
            $stmt = $this->conn->prepare($selectSql);
    
            if ($stmt) {
                // Bind the parameters
                $stmt->bind_param("ss", $data1, $data2);
    
                // Execute the query
                $stmt->execute();
    
                // Get the result
                $result = $stmt->get_result();
    
                // Check if the query was successful
                if ($result) {
                    while ($row = $result->fetch_array()) {
                        $stockOutSelect[] = $row;
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
    
            return $stockOutSelect;
        } catch (Exception $e) {
            // Handle any exceptions that occur
            // You can customize this part to suit your needs
            echo "Error: " . $e->getMessage();
            return array();
        }
    }
    






    function stokOutDetailsDataOnTable($table, $data) {
        try {
            $stockOutSelect = array();
    
            $selectBill = "SELECT * FROM `stock_out_details` WHERE `$table` = ?";
            
            $stmt = $this->conn->prepare($selectBill);
    
            if ($stmt) {
                $stmt->bind_param("s", $data);
                $stmt->execute();
                $result = $stmt->get_result();
    
                if ($result) {
                    while ($row = $result->fetch_array()) {
                        $stockOutSelect[] = $row;
                    }
                } else {
                    echo "Query failed: " . $this->conn->error;
                }
                $stmt->close();
            } else {
                echo "Statement preparation failed: " . $this->conn->error;
            }
    
            return $stockOutSelect;
        } catch (Exception $e) {
            echo "Error: " . $e->getMessage();
            return array();
        }
    }
    







    function updateStockOutDetaisOnStockInEdit($itemId, $batchNo, $expDate, $updatedBy, $updatedOn) {
        try {
            // Define the SQL query using a prepared statement
            $updateQuery = "UPDATE `stock_out_details` SET `batch_no`=?, `exp_date`=?, `updated_by`=?, `updated_on`=? WHERE `item_id`=?";
            $stmt = $this->conn->prepare($updateQuery);
    
            if ($stmt) {
                // Bind the parameters
                $stmt->bind_param("ssssi", $batchNo, $expDate, $updatedBy, $updatedOn, $itemId);
    
                // Execute the query
                $updateStockOutDetails = $stmt->execute();
                $stmt->close();
                return $updateStockOutDetails;
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

    



//  ================== most sold item check query ====================


    function mostSoldStockOutDataGroupByDay($adminId) {
        try {
            $selectQuery = "SELECT sod.product_id, SUM(sod.qty) AS total_sold
                            FROM stock_out_details sod
                            JOIN stock_out so ON sod.invoice_id = so.invoice_id
                            WHERE so.admin_id = ?
                              AND so.added_on >= NOW() - INTERVAL 24 HOUR
                            GROUP BY sod.product_id
                            ORDER BY total_sold DESC
                            LIMIT 10";
            
            $stmt = $this->conn->prepare($selectQuery);
    
            if ($stmt) {
                $stmt->bind_param("s", $adminId);
                $stmt->execute();
                $result = $stmt->get_result();
    
                if ($result->num_rows > 0) {
                    $data = array();
                    while ($row = $result->fetch_object()) {
                        $data[] = $row;
                    }
                    return $data;
                } else {
                    echo "Query returned no results.";
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





    function mostSoldStockOutDataGroupByWeek($adminId) {
        try {
            $selectQuery = "SELECT sod.product_id, SUM(sod.qty) AS total_sold
                            FROM stock_out_details sod
                            JOIN stock_out so ON sod.invoice_id = so.invoice_id
                            WHERE so.admin_id = ?
                              AND so.added_on >= DATE_SUB(NOW(), INTERVAL 7 DAY)
                            GROUP BY sod.product_id
                            ORDER BY total_sold DESC
                            LIMIT 10";
            
            $stmt = $this->conn->prepare($selectQuery);
    
            if ($stmt) {
                $stmt->bind_param("s", $adminId);
                $stmt->execute();
                $result = $stmt->get_result();
    
                if ($result->num_rows > 0) {
                    $data = array();
                    while ($row = $result->fetch_object()) {
                        $data[] = $row;
                    }
                    return $data;
                } else {
                    echo "Query returned no results.";
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





    function mostSoldStockOutDataGroupByMonth($adminId) {
        try {
            $selectQuery = "SELECT sod.product_id, SUM(sod.qty) AS total_sold
                            FROM stock_out_details sod
                            JOIN stock_out so ON sod.invoice_id = so.invoice_id
                            WHERE so.admin_id = ?
                              AND so.added_on >= DATE_SUB(NOW(), INTERVAL 30 DAY)
                            GROUP BY sod.product_id
                            ORDER BY total_sold DESC
                            LIMIT 10";
            
            $stmt = $this->conn->prepare($selectQuery);
    
            if ($stmt) {
                $stmt->bind_param("s", $adminId);
                $stmt->execute();
                $result = $stmt->get_result();
    
                if ($result->num_rows > 0) {
                    $data = array();
                    while ($row = $result->fetch_object()) {
                        $data[] = $row;
                    }
                    return $data;
                } else {
                    echo "Query returned no results.";
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


    




    function mostSoldStockOutDataGroupByDtRange($dtRange, $adminId) {
        try {
            $selectQuery = "SELECT sod.product_id, SUM(sod.qty) AS total_sold
                            FROM stock_out_details sod
                            JOIN stock_out so ON sod.invoice_id = so.invoice_id
                            WHERE so.admin_id = ?
                              AND so.added_on >= DATE_SUB(NOW(), INTERVAL $dtRange DAY)
                            GROUP BY sod.product_id
                            ORDER BY total_sold DESC
                            LIMIT 10";
            
            $stmt = $this->conn->prepare($selectQuery);
    
            if ($stmt) {
                $stmt->bind_param("s", $adminId);
                $stmt->execute();
                $result = $stmt->get_result();
    
                if ($result->num_rows > 0) {
                    $data = array();
                    while ($row = $result->fetch_object()) {
                        $data[] = $row;
                    }
                    return $data;
                } else {
                    echo "Query returned no results.";
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
//  ============= end of most sold item check query ===========

/// ========= less sold item check query ================


    function leastSoldStockOutDataGroupByDay($adminId) {
        try {
            $query = "SELECT product_id, SUM(qty) AS total_sold
                      FROM stock_out_details
                      WHERE invoice_id IN (
                          SELECT invoice_id
                          FROM stock_out
                          WHERE admin_id = ?
                            AND added_on >= DATE_SUB(NOW(), INTERVAL 1 DAY)
                      )
                      GROUP BY product_id
                      ORDER BY total_sold
                      LIMIT 10";
            
            $stmt = $this->conn->prepare($query);
    
            if ($stmt) {
                $stmt->bind_param("s", $adminId);
                $stmt->execute();
                $result = $stmt->get_result();
    
                if ($result->num_rows > 0) {
                    $data = $result->fetch_all(MYSQLI_ASSOC);
                    return $data;
                } else {
                    echo "Query returned no results.";
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







    function leastSoldStockOutDataGroupByWeek($adminId) {
        try {
            $query = "SELECT product_id, SUM(qty) AS total_sold
                      FROM stock_out_details
                      WHERE invoice_id IN (
                          SELECT invoice_id
                          FROM stock_out
                          WHERE admin_id = ?
                            AND added_on >= DATE_SUB(NOW(), INTERVAL 7 DAY)
                      )
                      GROUP BY product_id
                      ORDER BY total_sold
                      LIMIT 10";
            
            $stmt = $this->conn->prepare($query);
    
            if ($stmt) {
                $stmt->bind_param("s", $adminId);
                $stmt->execute();
                $result = $stmt->get_result();
    
                if ($result->num_rows > 0) {
                    $data = $result->fetch_all(MYSQLI_ASSOC);
                    return $data;
                } else {
                    echo "Query returned no results.";
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






    function leastSoldStockOutDataGroupByMonth($adminId) {
        try {
            $query = "SELECT product_id, SUM(qty) AS total_sold
                      FROM stock_out_details
                      WHERE invoice_id IN (
                          SELECT invoice_id
                          FROM stock_out
                          WHERE admin_id = ?
                            AND added_on >= DATE_SUB(NOW(), INTERVAL 30 DAY)
                      )
                      GROUP BY product_id
                      ORDER BY total_sold
                      LIMIT 10";
            
            $stmt = $this->conn->prepare($query);
    
            if ($stmt) {
                $stmt->bind_param("s", $adminId);
                $stmt->execute();
                $result = $stmt->get_result();
    
                if ($result->num_rows > 0) {
                    $data = $result->fetch_all(MYSQLI_ASSOC);
                    return $data;
                } else {
                    echo "Query returned no results.";
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
    
    

/// ========= end of less sold item check query ================



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





    function stokOutDetailsDataOnTables($table1, $data1, $table2, $data2, $table3, $data3,){
        $stockOutSelect = array();
        $selectBill = "SELECT * FROM `stock_out_details` WHERE `$table1` = '$data1' AND `$table2` = '$data2' AND `$table3` = '$data3'";
        
        $stockOutDataQuery = $this->conn->query($selectBill);

        while($result = $stockOutDataQuery->fetch_array()){
            $stockOutSelect[]	= $result;
        }
        return $stockOutSelect;
    }//eof stockOut details by tabel and data


    



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



    
    

    ///////////////////////////////////////////////////////////////////////////////
    //updateStockOutDetaislById($stock_out_id, $item_qty, $item_loose_qty, $disc_parcent, $margin_amount, $taxable_amount, $gst_amount, $payble_amount, $addedBy);
    ///////////////////////////////////////////////////////////////////////////////


    /* stock_out_details table update on stock in edit update */
    



    function deleteFromStockOutDetailsOnId($id){
        
            $delete = "DELETE FROM `stock_out_details` WHERE `id` = '$id'";
            $delteQuery = $this->conn->query($delete);
            return $delteQuery;
        }

}
