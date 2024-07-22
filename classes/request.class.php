<?php

class Request
{
    use DatabaseConnection;

    function addNewProductRequest($productId, $prodName, $prodCategory, $packegingType,  $qantity, $packegingUnit, $medicinePower, $mrp, $gst, $hsnoNumber, $description, $addedBy, $addedOn, $adminId, $status)
    {
        try {
            $addQuery = "INSERT INTO `product_request`(`product_id`, `name`, `type`, `packaging_type`,  `unit_quantity`, `unit`, `power`, `mrp`, `gst`, `hsno_number`, `req_dsc`, `requested_by`, `requested_on`, `admin_id`, `prod_req_status`) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

            $stmt = $this->conn->prepare($addQuery);
            $stmt->bind_param("sssisssdisssssi", $productId, $prodName, $prodCategory, $packegingType,  $qantity, $packegingUnit, $medicinePower, $mrp, $gst, $hsnoNumber, $description, $addedBy, $addedOn, $adminId, $status);

            if ($stmt->execute()) {
                // Insert successful
                $stmt->close();
                return true;
            } else {
                // Insert failed
                throw new Exception("Error inserting data into the database: " . $stmt->error);
            }
        } catch (Exception $e) {
            // Handle the exception, log the error, or return an error message as needed
            return "Error: " . $e->getMessage();
        }
    }






    function
    addOldProductRequest($oldProdId, $productId, $prodName, $composition1, $composition2, $prodCategory, $packegingType, $qantity, $unitid, $packegingUnit, $manufid, $medicinePower, $mrp, $gst, $hsnoNumber, $description, $addedBy, $addedOn, $adminId, $status, $oldProdFlag)
    {
        try {

            // echo $oldProdId, $productId, $prodName, $composition1, $composition2, $prodCategory, $packegingType, $qantity, $packegingUnit, $medicinePower, $mrp, $description, $gst, $hsnoNumber, $addedBy, $addedOn, $adminId, $status, $oldProdFlag;

            $addQuery = "INSERT INTO `product_request`(`old_prod_id`, `product_id`, `name`, `comp_1`, `comp_2`, `type`, `packaging_type`, `unit_quantity`, `unit_id`, `unit`, `manufacturer_id`, `power`, `mrp`, `gst`, `hsno_number`, `req_dsc`, `requested_by`, `requested_on`, `admin_id`, `prod_req_status`, `old_prod_flag`) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

            $stmt = $this->conn->prepare($addQuery);

            if (!$stmt) {
                throw new Exception("Error preparing SQL query: " . $this->conn->error);
            }

            $bindResult = $stmt->bind_param("ssssssisisisdsisissii", $oldProdId, $productId, $prodName, $composition1, $composition2, $prodCategory, $packegingType, $qantity, $unitid, $packegingUnit, $manufid, $medicinePower, $mrp, $gst, $hsnoNumber, $description, $addedBy, $addedOn, $adminId, $status, $oldProdFlag);

            if (!$bindResult) {
                throw new Exception("Error binding parameters: " . $stmt->error);
            }

            $executeResult = $stmt->execute();
            if (!$executeResult) {
                throw new Exception("Error executing SQL query: " . $stmt->error);
            }

            if ($stmt->affected_rows > 0) {
                $stmt->close();
                return json_encode(['status' => '1', 'data' => 'success']);
            } else {
                $stmt->close();
                return json_encode(['status' => '0', 'data' => "No rows affected. Insertion failed."]);
            }
        } catch (Exception $e) {
            return json_encode(['status' => '0', "Error: " . $e->getMessage()]);
        }
    }







    function addImageRequest($productId, $productImage, $addedBy, $addedOn, $adminId, $status)
    {
        try {
            if (!empty($adminId)) {
                $insertImage = "INSERT INTO `product_images` (`product_id`, `image`, `added_by`, `added_on`, `admin_id`, `status`) VALUES (?, ?, ?, ?, ?, ?)";
                $stmt = $this->conn->prepare($insertImage);
                $stmt->bind_param("sssssi", $productId, $productImage, $addedBy, $addedOn, $adminId, $status);
            } else {
                $insertImage = "INSERT INTO `product_images` (`product_id`, `image`, `added_by`, `added_on`, `status`) VALUES (?, ?, ?, ?, ?)";
                $stmt = $this->conn->prepare($insertImage);
                $stmt->bind_param("ssssi", $productId, $productImage, $addedBy, $addedOn, $status);
            }


            if ($stmt->execute()) {
                // Insert successful
                $stmt->close();
                return true;
            } else {
                // Insert failed
                throw new Exception("Error inserting data into the database: " . $stmt->error);
            }
        } catch (Exception $e) {
            return "Error: " . $e->getMessage();
        }
    }







    function selectProductById($prodId, $adminId)
    {
        $resultData = array();

        try {
            $searchSql = "SELECT * FROM `product_request` WHERE `old_prod_id` = ? AND `admin_id` = ?";
            $stmt = $this->conn->prepare($searchSql);

            if ($stmt) {
                $stmt->bind_param("ss", $prodId, $adminId);
                $stmt->execute();

                // Get the results
                $result = $stmt->get_result();

                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        $resultData = $row;
                    }
                    return json_encode(['status' => '1', 'message' => 'data found', 'data' => $resultData]);
                } else {
                    return json_encode(['status' => '0', 'message' => 'no data found', 'data' => []]);
                }
            } else {
                throw new Exception("Failed to prepare the statement.");
            }
        } catch (Exception $e) {
            echo "Error: " . $e->getMessage();
        } finally {
            if (isset($stmt)) {
                $stmt->close();
            }
        }

        return 0;
    }








    function selectProductData($prodId)
    {
        $resultData = array();

        try {
            $searchSql = "SELECT * FROM `product_request` WHERE `product_id` = ?";
            $stmt = $this->conn->prepare($searchSql);

            if ($stmt) {
                $stmt->bind_param("s", $prodId);
                $stmt->execute();

                // Get the results
                $result = $stmt->get_result();

                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        $resultData = $row;
                    }
                    return json_encode(['status' => '1', 'message' => 'data found', 'data' => $resultData]);
                } else {
                    return json_encode(['status' => '0', 'message' => 'no data found', 'data' => []]);
                }
            } else {
                throw new Exception("Failed to prepare the statement.");
            }
        } catch (Exception $e) {
            echo "Error: " . $e->getMessage();
        } finally {
            if (isset($stmt)) {
                $stmt->close();
            }
        }

        return 0;
    }








    function selectProductReqData($prodId, $adminId)
    {
        $resultData = array();

        try {
            $searchSql = "SELECT * FROM `product_request` WHERE `product_id` = ? AND `admin_id` = ?";
            $stmt = $this->conn->prepare($searchSql);

            if ($stmt) {
                $stmt->bind_param("ss", $prodId, $adminId);
                $stmt->execute();

                // Get the results
                $result = $stmt->get_result();

                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        $resultData[] = $row;
                    }
                    return json_encode(['status' => '1', 'message' => 'data found', 'data' => $resultData]);
                } else {
                    return json_encode(['status' => '0', 'message' => 'no data found', 'data' => []]);
                }
            } else {
                throw new Exception("Failed to prepare the statement.");
            }
        } catch (Exception $e) {
            echo "Error: " . $e->getMessage();
        } finally {
            if (isset($stmt)) {
                $stmt->close();
            }
        }

        return 0;
    }











    function selectItemLikeProdReqest($data, $adminId)
    {
        $resultData = array();

        try {
            $searchSql = "SELECT * FROM `product_request` WHERE `name` LIKE ? AND `admin_id`= ? LIMIT 10";
            $stmt = $this->conn->prepare($searchSql);

            if ($stmt) {

                $searchPattern = "%" . $data . "%";
                $stmt->bind_param("ss", $searchPattern, $adminId);
                $stmt->execute();

                // Get the results
                $result = $stmt->get_result();

                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        $resultData[] = $row;
                    }
                    return json_encode(['status' => '1', 'message' => 'data found', 'data' => $resultData]);
                } else {
                    return json_encode(['status' => '0', 'message' => 'no data found', 'data' => '']);
                }
            } else {
                throw new Exception("Failed to prepare the statement.");
            }
            $stmt->close();
        } catch (Exception $e) {

            echo "Error: " . $e->getMessage();
        }

        return 0;
    }





    function editUpdateProductRequest($productId, $prodName, $composition1, $composition2, $prodCategory,   $packagingType, $quantity, $packagingUnit, $medicinePower, $mrp, $gst, $hsnoNumber, $description,     $addedBy, $addedOn, $prodReqStatus, $oldProdFlag, $adminId)
    {
        try {
            $updateProdRequest = "UPDATE product_request SET `name` = ?, `comp_1` = ?, `comp_2` = ?, `type`     = ?, `packaging_type` = ?, `unit_quantity` = ?, `unit` = ?, `power` = ?, `mrp` = ?,  `gst` = ?,     `hsno_number` = ?, `req_dsc` = ?, `requested_by` = ?, `requested_on` = ?, `prod_req_status` = ?,    `old_prod_flag` = ? WHERE product_id = ? AND `admin_id` = ?";

            $stmt = $this->conn->prepare($updateProdRequest);

            if (!$stmt) {
                throw new Exception("Error preparing update statement: " . $this->conn->error);
            }

            $stmt->bind_param("ssssisssdissssiiss", $prodName, $composition1, $composition2, $prodCategory,     $packagingType, $quantity, $packagingUnit, $medicinePower, $mrp, $gst, $hsnoNumber, $description,   $addedBy, $addedOn, $prodReqStatus, $oldProdFlag, $productId, $adminId);

            if (!$stmt->execute()) {
                throw new Exception("Error updating product request: " . $stmt->error);
            }

            $affectedRows = $stmt->affected_rows;
            $stmt->close();

            if ($affectedRows > 0) {
                return json_encode(['status' => '1', 'data' => 'success']);
            } else {
                return json_encode(['status' => '0', 'data' => 'fail']);
            }
        } catch (Exception $e) {
            return json_encode(['status' => '0', 'data' => $e->getMessage()]);
        }
    }







    function lastRowId()
    {
        $sql = "SELECT * FROM product_request ORDER BY id DESC LIMIT 1";

        try {
            $stmt = $this->conn->prepare($sql);

            if ($stmt->execute()) {

                $result = $stmt->get_result();

                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        $resultData[] = $row;
                    }
                    return json_encode(['status' => '1', 'data' => $resultData]);
                } else {
                    return json_encode(['status' => '0', 'data' => '']);
                }
            } else {
                return null;
            }
        } catch (PDOException $e) {
            return $e->getMessage();
        }
    }





    /*
    function fetchRequestDataByTableName($tableName, $adminId, $name = '', $statusColumn = '', $description = '', $newDistributer = '')
    {
        try {
            if ($tableName == 'product_request') {
                $name         = 'name';
                $description  = 'req_dsc';
                $statusColumn = 'prod_req_status';
            } elseif ($tableName == 'distributor_request') {
                $name         = 'name';
                $description  = 'req_dsc';
                $statusColumn = 'status';
            } elseif ($tableName == 'manufacturer_request') {
                $name         = 'name';
                $description  = 'req_dsc';
                $statusColumn = 'status';
            } elseif ($tableName == 'packtype_request') {
                $name         = 'unit_name';
                $description  = 'req_dsc';
                $statusColumn = 'status';
            } elseif ($tableName == 'distributor') {
                $name           = 'name';
                $description    = 'dsc';
                $statusColumn   = 'status';
                $newDistributer = 'new';
            } elseif ($tableName == 'manufacturer') {
                $name           = 'name';
                $description    = 'dsc';
                $statusColumn   = 'status';
                $newDistributer = 'new';
            } elseif ($tableName == 'packaging_type') {
                $name           = 'unit_name';
                $description    = 'Add New Packaging Unit';
                $statusColumn   = 'status';
                $newDistributer = 'new';
            } elseif ($tableName == 'quantity_unit') {
                $name           = 'short_name';
                $newDistributer = 'new';
            } elseif ($tableName == 'query_request') {
                $name = true;
            } elseif ($tableName == 'ticket_request') {
                $name = true;
            }

            if ($newDistributer) {
                if ($tableName == 'quantity_unit') {
                    $requestQuery = "SELECT id, $name,$newDistributer FROM $tableName WHERE admin_id = ? AND new = 1";
                } elseif ($tableName == 'packaging_type') {
                    $requestQuery = "SELECT id, $name, $statusColumn, $newDistributer FROM $tableName WHERE admin_id = ? AND new = 1";
                } else {
                    $requestQuery = "SELECT id, $name, $description, $statusColumn, $newDistributer FROM $tableName WHERE admin_id = ? AND new = 1";
                }
            } else if($name){
                if ($tableName == 'query_request') {
                    $requestQuery = "SELECT 'ticket_no','message','status' FROM $tableName WHERE admin_id = ?";
                } elseif ($tableName == 'ticket_request') {
                    $requestQuery = "SELECT 'ticket_no', 'description','status' FROM $tableName WHERE admin_id = ?";
                }
                
            } else{
                $requestQuery = "SELECT id, $name, $description, $statusColumn FROM $tableName WHERE admin_id = ?";
            }
           
            $requestStmt = $this->conn->prepare($requestQuery);

            if (!$requestStmt) {
                throw new Exception("Error preparing SQL query: " . $this->conn->error);
            }

            $requestStmt->bind_param("s", $adminId);

            if (!$requestStmt->execute()) {
                throw new Exception("Error executing product request query: " . $requestStmt->error);
            }

            $requestResult = $requestStmt->get_result();

            if (!$requestResult) {
                throw new Exception("Error fetching product request result: " . $this->conn->error);
            }

            if ($requestResult->num_rows > 0) {
                $requestResultData = array();
                while ($row = $requestResult->fetch_assoc()) {
                    $requestResultData[] = $row;
                }
                return json_encode(['status' => '1', 'data' => $requestResultData]);
            } else {
                return json_encode(['status' => '0', 'data' => 'No data found.']);
            }
        } catch (Exception $e) {
            return json_encode(['status' => '0', 'error' => $e->getMessage()]);
        }
    }
*/

    function fetchRequestDataByTableName($tableName, $adminId)
    {
        try {
            $queries = [
                'product_request' => ['id', 'name', 'req_dsc', 'prod_req_status'],
                'distributor_request' => ['id', 'name', 'req_dsc', 'status'],
                'manufacturer_request' => ['id', 'name', 'req_dsc', 'status'],
                'packtype_request' => ['id', 'unit_name', 'req_dsc', 'status'],
                'distributor' => ['id', 'name', 'dsc', 'status', 'new'],
                'manufacturer' => ['id', 'name', 'dsc', 'status', 'new'],
                'packaging_type' => ['id', 'unit_name', 'status', 'new'],
                'quantity_unit' => ['id', 'short_name', 'new'],
                'query_request' => ['ticket_no', 'message', 'status'],
                'ticket_request' => ['ticket_no', 'description', 'status']
            ];

            if (!array_key_exists($tableName, $queries)) {
                throw new Exception("Invalid table name: " . $tableName);
            }

            $columns = $queries[$tableName];
            $selectColumns = implode(", ", $columns);

            $condition = ($tableName === 'quantity_unit' || $tableName === 'packaging_type' || in_array('new', $columns)) ? "AND new = 1" : "";

            $requestQuery = "SELECT $selectColumns FROM $tableName WHERE admin_id = ? $condition";

            $requestStmt = $this->conn->prepare($requestQuery);
            if (!$requestStmt) {
                throw new Exception("Error preparing SQL query: " . $this->conn->error);
            }

            $requestStmt->bind_param("s", $adminId);
            if (!$requestStmt->execute()) {
                throw new Exception("Error executing query: " . $requestStmt->error);
            }

            $requestResult = $requestStmt->get_result();
            if (!$requestResult) {
                throw new Exception("Error fetching result: " . $this->conn->error);
            }

            if ($requestResult->num_rows > 0) {
                $requestResultData = [];
                while ($row = $requestResult->fetch_assoc()) {
                    $requestResultData[] = $row;
                }
                return json_encode(['status' => '1', 'data' => $requestResultData]);
            } else {
                return json_encode(['status' => '0', 'data' => 'No data found.']);
            }
        } catch (Exception $e) {
            return json_encode(['status' => '0', 'error' => $e->getMessage()]);
        }
    }





    ///// ======= for super admin view ===========
    function fetchAllRequestDataByTableName($tableName)
    {
        try {
            $newDistributer = false;
            $name = '';
            $description = '';
            $statusColumn = '';

            switch ($tableName) {
                case 'product_request':
                    $name = 'name';
                    $description = 'req_dsc';
                    $statusColumn = 'prod_req_status';
                    break;
                case 'distributor_request':
                    $name = 'name';
                    $description = 'req_dsc';
                    $statusColumn = 'status';
                    break;
                case 'manufacturer_request':
                    $name = 'name';
                    $description = 'req_dsc';
                    $statusColumn = 'status';
                    break;
                case 'packtype_request':
                    $name = 'unit_name';
                    $description = 'req_dsc';
                    $statusColumn = 'status';
                    break;
                case 'distributor':
                    $name = 'name';
                    $description = 'dsc';
                    $statusColumn = 'status';
                    $newDistributer = true;
                    break;
                case 'manufacturer':
                    $name = 'name';
                    $description = 'dsc';
                    $statusColumn = 'status';
                    $newDistributer = true;
                    break;
                case 'packaging_type':
                    $name = 'unit_name';
                    $description = 'Add New Packaging Unit';
                    $statusColumn = 'status';
                    $newDistributer = true;
                    break;
                case 'quantity_unit':
                    $name = 'short_name';
                    $newDistributer = true;
                    break;
                case 'query_request':
                    $name = true;
                    break;
                case 'ticket_request':
                    $name = true;
                    break;
            }

            if ($newDistributer) {
                if ($tableName == 'quantity_unit') {
                    $requestQuery = "SELECT id, $name, new FROM $tableName WHERE new = 1";
                } elseif ($tableName == 'packaging_type') {
                    $requestQuery = "SELECT id, $name, $statusColumn, new FROM $tableName WHERE new = 1";
                } else {
                    $requestQuery = "SELECT id, $name, $description, $statusColumn, new FROM $tableName WHERE new = 1";
                }
            } elseif ($name) {
                if ($tableName == 'query_request') {
                    $requestQuery = "SELECT * FROM $tableName WHERE `status`='ACTIVE'";
                } elseif ($tableName == 'ticket_request') {
                    $requestQuery = "SELECT * FROM $tableName WHERE `status`='ACTIVE'";
                } 
                else {
                    $requestQuery = "SELECT * FROM $tableName";
                }
            } else {
                throw new Exception("Invalid table name specified.");
            }

            $requestStmt = $this->conn->prepare($requestQuery);

            if (!$requestStmt) {
                throw new Exception("Error preparing SQL query: " . $this->conn->error);
            }

            if (!$requestStmt->execute()) {
                throw new Exception("Error executing product request query: " . $requestStmt->error);
            }

            $requestResult = $requestStmt->get_result();

            if (!$requestResult) {
                throw new Exception("Error fetching product request result: " . $this->conn->error);
            }

            if ($requestResult->num_rows > 0) {
                $requestResultData = array();
                while ($row = $requestResult->fetch_assoc()) {
                    $requestResultData[] = $row;
                }
                return json_encode(['status' => '1', 'data' => $requestResultData]);
            } else {
                return json_encode(['status' => '0', 'data' => 'No data found.']);
            }
        } catch (Exception $e) {
            return json_encode(['status' => '0', 'error' => $e->getMessage()]);
        }
    }






    // ======================== DATA FETCH QUERY ========================
    function fetchDataByTableName($data, $table) {
        try {
        
            $fetchQuery = "SELECT * FROM $table WHERE ticket_no = ?";
    
            $requestStmt = $this->conn->prepare($fetchQuery);
        
            if (!$requestStmt) {
                throw new Exception("Error preparing statement: " . $this->conn->error);
            }
            $requestStmt->bind_param('s', $data); 
            $requestStmt->execute();
            $result = $requestStmt->get_result();
            
            $responseData = [];
            
            if ($result->num_rows > 0) {
                while ($res = $result->fetch_object()) {
                    $responseData = $res;
                }
                $requestStmt->close();
                return json_encode(['status' => true, 'data' => $responseData]);
            } else {
                $requestStmt->close();
                return json_encode(['status' => false, 'data' => []]);
            }
        } catch (Exception $e) {
            return json_encode(['status' => false, 'message' => $e->getMessage()]);
        }
    }
    
    



    // =========== add data to response tabel (ticket / query response table) ============
    function addResponseToTicketQueryTable($tableName, $requestId, $ticketNo, $queryTitle, $queryMessage, $document, $response, $requestCreater, $sender, $status, $addedOn, $viewStatus) {
        try {
            if ($tableName == 'Generate Quarry') {
                $table = 'query_response';
                $col1 = 'query_id';
                $col2 = 'ticket_no';
                $col3 = 'title';
                $col4 = 'message';
                $col5 = 'attachment';
                $col6 = 'response';
                $col7 = 'query_creater';
                $col8 = 'sender';
                $col9 = 'status';
                $col10 = 'added_on';
                $col11 = 'view_status';
            } else if ($tableName == 'Generate Ticket') {
                $table = 'ticket_response';
                $col1 = 'ticket_id';
                $col2 = 'ticket_no';
                $col3 = 'title';
                $col4 = 'description';
                $col5 = 'attachment';
                $col6 = 'response';
                $col7 = 'request_creater';
                $col8 = 'sender';
                $col9 = 'status';
                $col10 = 'added_on';
                $col11 = 'view_status';
            }
    
            $addQuery = "INSERT INTO $table($col1, $col2, $col3, $col4, $col5, $col6, $col7, $col8, $col9, $col10, $col11) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
    
            $stmt = $this->conn->prepare($addQuery);
    
            if ($stmt === false) {
                throw new Exception('Prepare statement failed: ' . $this->conn->error);
            }
    
            // Adjust the types according to your database schema, here it's assumed that $addedOn is a string
            $stmt->bind_param("isssssssssi", $requestId, $ticketNo, $queryTitle, $queryMessage, $document, $response, $requestCreater, $sender, $status, $addedOn, $viewStatus);
    
            if (!$stmt->execute()) {
                throw new Exception('Execute statement failed: ' . $stmt->error);
            }
            return json_encode(['status' => true, 'rowId' => $stmt->insert_id]);
            $stmt->close();
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }
    



    // =========================== update query/table data ==================================
    function updateStatusByTableName($table, $id, $status) {
        try {
            $updateQuery = "UPDATE `$table` SET `status` = ? WHERE `id` = ?";
            
            $stmt = $this->conn->prepare($updateQuery);
    
            if ($stmt === false) {
                throw new Exception('Prepare statement failed: ' . $this->conn->error);
            }
    
            $stmt->bind_param("si", $status, $id);
    
            if (!$stmt->execute()) {
                throw new Exception('Execute statement failed: ' . $stmt->error);
            }
    
            $stmt->close();
    
            return json_encode(['status' => true, 'message' => 'Status updated successfully']);
        } catch (Exception $e) {
            return json_encode(['status' => false, 'message' => $e->getMessage()]);
        }
    }
    





    function deleteRequest($prodId)
    {
        try {
            $sql = "DELETE FROM product_request WHERE `product_id` = ?";
            $statement = $this->conn->prepare($sql);

            if ($statement) {
                $statement->bind_param("s", $prodId);
                $statement->execute();

                $result = $statement->get_result();

                if ($statement->affected_rows > 0) {
                    return json_encode(['status' => '1', 'message' => 'Data deleted successfully']);
                } else {
                    return json_encode(['status' => '0', 'message' => 'No data found for deletion']);
                }
            } else {
                throw new Exception("Error preparing delete statement: " . $this->conn->error);
            }
        } catch (Exception $e) {
            return json_encode(['status' => '0', 'message' => $e->getMessage()]);
        }
    }





    function deleteProductOnTable($prodId, $table)
    {
        try {
            $sql = "DELETE FROM $table WHERE `product_id` = ?";
            $statement = $this->conn->prepare($sql);

            if (!$statement) {
                throw new Exception("Error preparing delete statement: " . $this->conn->error);
            }

            $statement->bind_param("s", $prodId);
            $statement->execute();

            if ($statement->affected_rows > 0) {
                return json_encode(['status' => '1', 'message' => 'Data deleted successfully']);
            } else {
                return json_encode(['status' => '0', 'message' => 'No data found for deletion']);
            }
        } catch (Exception $e) {
            return json_encode(['status' => '0', 'message' => $e->getMessage()]);
        }
    }




    // ====================== ticket request =====================
    function addNewTicketRequest($ticketNo, $email, $contact, $name, $description, $document, $admin, $status, $time)
    {
        // echo $ticketNo, $email, $contact, $name, $description, $document, $admin, $status, $time;
        try {
            $addQuery = "INSERT INTO `ticket_request`(`ticket_no`, `email`, `phone`, `name`, `description`, `document`, `admin_id`, `status`, `added_on`) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";

            $stmt = $this->conn->prepare($addQuery);
            if (!$stmt) {
                throw new Exception("Error preparing statement: " . $this->conn->error);
            }

            $stmt->bind_param("ssissssss", $ticketNo, $email, $contact, $name, $description, $document, $admin, $status, $time);

            if ($stmt->execute()) {
                $insertId = $this->conn->insert_id;
                $stmt->close();
                return json_encode(['status' => true, 'insert_id' => $insertId]);
            } else {
                throw new Exception("Error inserting data into the database: " . $stmt->error);
            }
        } catch (Exception $e) {
            return json_encode(['status' => false, 'error' => $e->getMessage()]);
        }
    }


    // ====================== query request ======================
    function addNewQueryRequest($ticketNo, $email, $contact, $name, $description, $document, $admin, $status, $time)
    {
        try {
            $addQuery = "INSERT INTO `query_request`(`ticket_no`, `email`, `contact`, `name`, `message`, `attachment`, `admin_id`, `status`, `added_on`) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";

            $stmt = $this->conn->prepare($addQuery);
            if (!$stmt) {
                throw new Exception("Error preparing statement: " . $this->conn->error);
            }

            $stmt->bind_param("ssissssss", $ticketNo, $email, $contact, $name, $description, $document, $admin, $status, $time);

            if ($stmt->execute()) {
                $insertId = $this->conn->insert_id;
                $stmt->close();
                return json_encode(['status' => true, 'insert_id' => $insertId]);
            } else {
                throw new Exception("Error inserting data into the database: " . $stmt->error);
            }
        } catch (Exception $e) {
            return json_encode(['status' => false, 'error' => $e->getMessage()]);
        }
    }
}
