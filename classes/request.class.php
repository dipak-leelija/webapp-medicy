<?php

class Request extends DatabaseConnection
{

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






    function addOldProductRequest($oldProdId, $productId, $prodName, $composition1, $composition2, $prodCategory, $packegingType, $qantity, $unitid, $packegingUnit, $medicinePower, $mrp, $gst, $hsnoNumber, $description, $addedBy, $addedOn, $adminId, $status, $oldProdFlag){
        try {

            // echo $oldProdId, $productId, $prodName, $composition1, $composition2, $prodCategory, $packegingType, $qantity, $packegingUnit, $medicinePower, $mrp, $description, $gst, $hsnoNumber, $addedBy, $addedOn, $adminId, $status, $oldProdFlag;

            $addQuery = "INSERT INTO `product_request`(`old_prod_id`, `product_id`, `name`, `comp_1`, `comp_2`, `type`, `packaging_type`, `unit_quantity`, `unit_id`, `unit`, `power`, `mrp`, `gst`, `hsno_number`, `req_dsc`, `requested_by`, `requested_on`, `admin_id`, `prod_req_status`, `old_prod_flag`) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

            $stmt = $this->conn->prepare($addQuery);

            if (!$stmt) {
                throw new Exception("Error preparing SQL query: " . $this->conn->error);
            }

            $bindResult = $stmt->bind_param("ssssssisissdsisissii", $oldProdId, $productId, $prodName, $composition1, $composition2, $prodCategory, $packegingType, $qantity, $unitid, $packegingUnit, $medicinePower, $mrp, $gst, $hsnoNumber, $description, $addedBy, $addedOn, $adminId, $status, $oldProdFlag);

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
            return "Error: " . $e->getMessage();
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








    function selectProductData($prodId){
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





    function editUpdateProductRequest($productId, $prodName, $composition1, $composition2, $prodCategory,   $packagingType, $quantity, $packagingUnit, $medicinePower, $mrp, $gst, $hsnoNumber, $description,     $addedBy, $addedOn, $prodReqStatus, $oldProdFlag, $adminId){
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
            }

            if ($newDistributer) {
                if ($tableName == 'quantity_unit') {
                    $requestQuery = "SELECT $name,$newDistributer FROM $tableName WHERE admin_id = ? AND new = 1";
                } elseif ($tableName == 'packaging_type') {
                    $requestQuery = "SELECT $name, $statusColumn, $newDistributer FROM $tableName WHERE admin_id = ? AND new = 1";
                } else {
                    $requestQuery = "SELECT $name, $description, $statusColumn, $newDistributer FROM $tableName WHERE admin_id = ? AND new = 1";
                }
            } else {
                $requestQuery = "SELECT $name, $description, $statusColumn FROM $tableName WHERE admin_id = ?";
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




    function deleteProductOnTable($prodId, $table){
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

}
