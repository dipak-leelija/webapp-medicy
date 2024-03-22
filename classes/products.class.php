<?php

class Products extends DatabaseConnection
{



    ##############################################################################################
    #                                        Product Type                                        #
    ##############################################################################################

    function productCategory()
    {
        try {
            $selectType = "SELECT * FROM product_type";
            $stmt = $this->conn->prepare($selectType);

            if (!$stmt) {
                throw new Exception("Statement preparation failed: " . $this->conn->error);
            }

            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                $products = array();
                while ($res = $result->fetch_object()) {
                    $products[] = $res;
                }
                $stmt->close();
                return json_encode(['status' => '1', 'message' => 'success', 'data' => $products]);
            } else {
                return json_encode(['status' => '0', 'message' => 'No category found!', 'data' => []]);
            }
        } catch (Exception $e) {
            return json_encode(['status' => '0', 'message' => $e->getMessage(), 'data' => []]);
        }
        return 0;
    }




    ##############################################################################################
    #                                          Products                                          #
    ##############################################################################################

    function addProducts($productId, $manufacturerid, $productName, $productComposition1, $productComposition2, $power, $productDsc, $packagingType, $unitQuantity, $unit, $unitName, $mrp, $gst, $verifyStatus, $addedOn, $adminId)
    {
        try {
            $insertProducts = "INSERT INTO `products` (`product_id`, `manufacturer_id`, `name`, `comp_1`,`comp_2`, `power`, `dsc`, `packaging_type`, `unit_quantity`, `unit_id`, `unit`, `mrp`, `gst`, `verified`, `added_on`, `admin_id`) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

            $stmt = $this->conn->prepare($insertProducts);
            $stmt->bind_param("sssssssssssssiss", $productId, $manufacturerid, $productName, $productComposition1, $productComposition2, $power, $productDsc, $packagingType, $unitQuantity, $unit, $unitName, $mrp, $gst, $verifyStatus, $addedOn, $adminId);

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


    /// ===========product add by superAdmin=========///
    function addProductBySuperAdmin($producid, $productName, $productComp1, $productComp2, $hsnNumber, $type, $packagingType, $medicinePower, $quantity, $qtyUnit, $itemUnit, $manufacturerId, $mrp, $gst, $productDesc, $addedBy, $verifyStatus, $addedOn)
    {
        try {
            $insertProducts = "INSERT INTO `products` (`product_id`, `name`, `comp_1`, `comp_2`, `hsno_number`, `type`, `packaging_type`, `power`, `unit_quantity`, `unit_id`, `unit`, `manufacturer_id`, `mrp`, `gst`, `dsc`, `added_by`, `verified`, `added_on`) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

            $stmt = $this->conn->prepare($insertProducts);
            $stmt->bind_param("ssssssissisissssis", $producid, $productName, $productComp1, $productComp2, $hsnNumber, $type, $packagingType, $medicinePower, $quantity, $qtyUnit, $itemUnit, $manufacturerId, $mrp, $gst, $productDesc, $addedBy, $verifyStatus, $addedOn);

            if ($stmt->execute()) {
                // Insert successful
                $stmt->close();

                // Return additional information if needed
                return json_encode(['status' => '1', 'message' => 'Product added successfully', 'productId' => $producid]);
            } else {
                // Insert failed
                throw new Exception("Error inserting data into the database: " . $stmt->error);
            }
        } catch (Exception $e) {
            // Handle the exception, log the error, or return an error message as needed
            return json_encode(['status' => '0', 'message' => 'Error: ' . $e->getMessage(),]);
        }
    }







    // product request from user ===============
    function addProductByUser($productId, $prodName, $hsnoNumber, $prodCategory, $medicinePower, $qantityUnit, $packegingUnit, $packegingType, $mrp, $gst, $employeeId, $addedOn, $adminId)
    {
        try {
            $insertProducts = "INSERT INTO `products` (`product_id`, `name`, `hsno_number`, `type`, `power`, `unit_quantity`, `unit`, `packaging_type`, `mrp`, `gst`, `added_by`, `added_on`, `admin_id`) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

            $stmt = $this->conn->prepare($insertProducts);
            $stmt->bind_param("sssssssssssss", $productId, $prodName, $hsnoNumber, $prodCategory, $medicinePower, $qantityUnit, $packegingUnit, $packegingType, $mrp, $gst, $employeeId, $addedOn, $adminId);

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





    /// ============ product edit update querry for super admin ==========
    function updateProductBySuperAdmin($productId, $prodName, $comp1, $comp2, $hsnNumber, $type, $packagingType, $power, $unitQty, $unitId, $unit, $manufId, $mrp, $gst, $dsc, $updatedBy, $updatedOn,  $verify)
    {
        try {
            $updateProdData = "UPDATE products SET `name`=?, `comp_1`=?, `comp_2`=?, `hsno_number`=?, `type`=?, `packaging_type`=?, `power`=?, `unit_quantity`=?, `unit_id`=?, `unit`=?, `manufacturer_id`=?, `mrp`=?, `gst`=?, `dsc`=?, `updated_by`=?, `updated_on`=?, `verified`=? WHERE `product_id`=?";

            $stmt = $this->conn->prepare($updateProdData);

            if (!$stmt) {
                throw new Exception("Error in preparing the SQL statement.");
            }

            $stmt->bind_param("sssssissisisssssis", $prodName, $comp1, $comp2, $hsnNumber, $type, $packagingType, $power, $unitQty, $unitId, $unit, $manufId, $mrp, $gst, $dsc, $updatedBy, $updatedOn,  $verify, $productId);

            if (!$stmt) {
                throw new Exception("Error in binding parameters.");
            }

            if ($stmt->execute()) {
                $stmt->close();
                return json_encode(['status' => '1', 'message' => 'success']);
            } else {
                throw ['status' => '0', 'message' => new Exception()];
            }
        } catch (Exception $e) {
            return json_encode(['status' => ' ', 'message' => $e->getMessage()]);
        }
    }




    function updateProductByUser($productId, $prodName, $prodCategory, $prodPackageType, $prodPower, $prodUnit, $prodQantityPerUnit, $prodMrp, $prodGst, $prodHSNO, $employeeId, $updatedOn)
    {
        try {
            $updateProdData = "UPDATE products SET `name`=?, `type`=?, `packaging_type`=?, `power`=?, `unit`=?, `unit_quantity`=?, `mrp`=?, `gst`=?, `hsno_number`=?, `updated_by`=?, `updated_on`=? WHERE `product_id`=?";

            $stmt = $this->conn->prepare($updateProdData);

            if (!$stmt) {
                throw new Exception("Error in preparing the SQL statement.");
            }

            $stmt->bind_param("ssssssssssss", $prodName, $prodCategory, $prodPackageType, $prodPower, $prodUnit, $prodQantityPerUnit, $prodMrp, $prodGst, $prodHSNO, $employeeId, $updatedOn, $productId);

            if (!$stmt) {
                throw new Exception("Error in binding parameters.");
            }

            if ($stmt->execute()) {
                $stmt->close();
                return ['status' => '1', 'message' => 'success'];
            } else {
                throw ['status' => '0', 'message' => new Exception()];
            }
        } catch (Exception $e) {
            return ['status' => ' ', 'message' => $e->getMessage()];
        }
    }





    function showProducts()
    {
        try {
            $selectProduct = "SELECT * FROM products LIMIT 20";
            $selectProductQuery = $this->conn->query($selectProduct);

            if (!$selectProductQuery) {
                throw new Exception("Query execution failed.");
            }

            $rows = $selectProductQuery->num_rows;
            if ($rows == 0) {
                return 0;
            } else {
                return $selectProductQuery;
            }
        } catch (Exception $e) {
            return error_log("Error in showProducts function: " . $e->getMessage());
        }
    }







    function productExistanceCheck($prodId)
    {
        try {
            $slectProduct = "SELECT `name` FROM products WHERE product_id = ?";
            $stmt = $this->conn->prepare($slectProduct);

            $stmt->bind_param("s", $prodId);

            $stmt->execute();

            $result = $stmt->get_result();

            if ($result->num_rows == 0) {
                return json_encode(['status' => '0', 'data' => '']);
            } else {
                return json_encode(['status' => '1', 'data' => $result]);
            }
        } catch (Exception $e) {
            return json_encode(['status' => ' ', 'data' => $e->getMessage()]);
        } finally {
            $stmt->close();
        }
    }






    function showAllProducts()
    {
        try {
            $products = array();
            $productReqst = array();

            $selectQuery1 = "SELECT * FROM products LIMIT 5";
            $stmt1 = $this->conn->prepare($selectQuery1);

            if (!$stmt1) {
                throw new Exception("Error in preparing the SQL statement for products.");
            }

            if ($stmt1->execute()) {
                $result1 = $stmt1->get_result();

                if ($result1->num_rows > 0) {
                    while ($res1 = $result1->fetch_object()) {
                        $products[] = $res1;
                    }
                }
            } else {
                throw new Exception("Error in statement1 execution.");
            }

            $selectQuery2 = "SELECT * FROM product_request";
            $stmt2 = $this->conn->prepare($selectQuery2);

            if (!$stmt2) {
                throw new Exception("Error in preparing the SQL statement for product requests.");
            }

            if ($stmt2->execute()) {
                $result2 = $stmt2->get_result();

                if ($result2->num_rows > 0) {
                    while ($res2 = $result2->fetch_object()) {
                        $productReqst[] = $res2;
                    }
                }
            } else {
                throw new Exception("Error in statement2 execution.");
            }

            $productData = array_merge($products, $productReqst);

            return json_encode(['status' => '1', 'message' => 'data found', 'data' => $productData]);
        } catch (Exception $e) {
            return json_encode(['status' => '0', 'message' => $e->getMessage(), 'data' => '']);
        }
    }









    function showProductsByLimit()
    {
        try {
            $selectProduct = "SELECT * FROM products LIMIT 5";
            $stmt = $this->conn->prepare($selectProduct);

            if (!$stmt) {
                throw new Exception("Statement preparation failed: " . $this->conn->error);
            }

            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                $products = array();
                while ($res = $result->fetch_object()) {
                    $products[] = $res;  // Fix: Append to the $products array
                }
                $stmt->close();
                return json_encode(['status' => '1', 'message' => 'success', 'data' => $products]);
            } else {
                return json_encode(['status' => '0', 'message' => 'No products found', 'data' => []]);
            }
        } catch (Exception $e) {
            return json_encode(['status' => '0', 'message' => $e->getMessage(), 'data' => []]);
        }
        return 0;
    }





    function showProductsByLimitForUser($adminId)
    {
        try {
            $selectProduct = "SELECT * FROM products LIMIT 5";
            $productStmt = $this->conn->prepare($selectProduct);

            if (!$productStmt) {
                throw new Exception("Product Statement preparation failed: " . $this->conn->error);
            }

            $productStmt->execute();
            $productResult = $productStmt->get_result();

            $products = array();
            while ($prodRes = $productResult->fetch_object()) {
                $products[] = $prodRes;
            }



            $selectProductRequest = "SELECT * FROM product_request WHERE admin_id = '$adminId' LIMIT 5";
            $productRequestStmt = $this->conn->prepare($selectProductRequest);

            if (!$productRequestStmt) {
                throw new Exception("Product Request Statement preparation failed: " . $this->conn->error);
            }

            $productRequestStmt->execute();
            $productRequestResult = $productRequestStmt->get_result();

            $productRequest = array();
            while ($prodReqRes = $productRequestResult->fetch_object()) {
                $productRequest[] = $prodReqRes;
            }

            // print_r($productRequest);

            if ($productResult->num_rows > 0 || $productRequestResult->num_rows > 0) {
                $products = array_merge($products, $productRequest);
                return json_encode(['status' => '1', 'message' => 'success', 'data' => $products]);
            } else {
                return json_encode(['status' => '0', 'message' => 'No products found', 'data' => []]);
            }

            $productStmt->close();
            $productRequestStmt->close();
        } catch (Exception $e) {
            return json_encode(['status' => '0', 'message' => $e->getMessage(), 'data' => []]);
        }
    }






    function showProductsByCol($col, $colValue)
    {
        try {
            $selectProduct = "SELECT * FROM products WHERE `$col` = ?";

            $stmt = $this->conn->prepare($selectProduct);
            if (!$stmt) {
                throw new Exception("Statement preparation failed: " . $this->conn->error);
            }

            // Bind parameter
            $stmt->bind_param("s", $colValue);

            if ($stmt->execute()) {
                $result = $stmt->get_result();
                $rows = $result->num_rows;
                if ($rows == 0) {
                    return 0;
                } else {
                    return $result;
                }
            } else {
                throw new Exception("Query execution failed: " . $stmt->error);
            }
        } catch (Exception $e) {
            return "Error: " . $e->getMessage();
        }
    }






    function showProductsById($productId)
    {
        try {
            $selectProduct = "SELECT * FROM products WHERE product_id = ?";
            $stmt = $this->conn->prepare($selectProduct);

            if (!$stmt) {
                throw new Exception("Statement preparation failed: " . $this->conn->error);
            }

            $stmt->bind_param("s", $productId);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows == 0) {
                $stmt->close();
                return json_encode(['status' => '0', 'message' => 'Product not found', 'data' => null]);
            } else {
                $data = array();
                while ($row = $result->fetch_assoc()) {
                    $data = $row;
                }

                $stmt->close();

                return json_encode(['status' => '1', 'message' => 'success', 'data' => $data]);
            }
        } catch (Exception $e) {
            // error_log("Error in showProductsById: " . $e->getMessage());
            return json_encode(['status' => 'error', 'message' => $e->getMessage(), 'data' => null]);
        }
        return 0;
    }





    function showProductNameById($productId)
    {
        try {
            $selectProduct = "SELECT name FROM products WHERE product_id = ?";
            $stmt = $this->conn->prepare($selectProduct);

            if (!$stmt) {
                throw new Exception("Statement preparation failed: " . $this->conn->error);
            }

            $stmt->bind_param("s", $productId);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows == 0) {
                $selectProductNameFromRequest = "SELECT name FROM product_request WHERE product_id = ?";
                $stmt2 = $this->conn->prepare($selectProductNameFromRequest);

                if (!$stmt2) {
                    throw new Exception("Statement2 preparation failed: " . $this->conn->error);
                }

                $stmt2->bind_param("s", $productId);
                $stmt2->execute();
                $result = $stmt2->get_result();

                if($result->num_rows == 0){
                    $stmt->close();
                    return json_encode(['status' => '0', 'message' => 'Product not found', 'data' => null]);
                } else {
                    $data = array();
                    while ($row = $result->fetch_assoc()) {
                        $data = $row;
                    }
                    $stmt->close();
                    return json_encode(['status' => '1', 'message' => 'success', 'data' => $data]);
                }

            } else {
                $data = array();
                while ($row = $result->fetch_assoc()) {
                    $data = $row;
                }

                $stmt->close();

                return json_encode(['status' => '1', 'message' => 'success', 'data' => $data]);
            }
        } catch (Exception $e) {
            // error_log("Error in showProductsById: " . $e->getMessage());
            return json_encode(['status' => 'error', 'message' => $e->getMessage(), 'data' => null]);
        }
        return 0;
    }





    function showProductsByIdOnUser($productId, $adminId, $editReqFlag, $prodReqStatus = '', $oldProdFlag = '')
    {
        $productData = array();
        $productReqData = array();
        try {
            if ($prodReqStatus == '' && $oldProdFlag == '' && $editReqFlag != '') {
                $selectProduct = "SELECT * FROM products WHERE product_id = ?";
                $prodStmt = $this->conn->prepare($selectProduct);

                if (!$prodStmt) {
                    throw new Exception("Statement preparation failed: " . $this->conn->error);
                }

                $prodStmt->bind_param("s", $productId);
                $prodStmt->execute();
                $prodResult = $prodStmt->get_result();

                while ($row = $prodResult->fetch_assoc()) {
                    $productData[] = $row;
                }
                $prodStmt->close();
            } else {

                $selectProductRequest = "SELECT * FROM product_request WHERE product_id = ? AND admin_id = ? AND (old_prod_flag = 1 OR prod_req_status = 0)";
                $prodReqStmt = $this->conn->prepare($selectProductRequest);

                if (!$prodReqStmt) {
                    throw new Exception("Statement preparation failed: " . $this->conn->error);
                }

                $prodReqStmt->bind_param("ss", $productId, $adminId);
                $prodReqStmt->execute();
                $prodReqResult = $prodReqStmt->get_result();

                while ($row = $prodReqResult->fetch_assoc()) {
                    $productReqData[] = $row;
                }
                $prodReqStmt->close();
            }
            // return $productReqData;
            if ($productData != null || $productReqData != null) {
                $productData = array_merge($productData, $productReqData);
                return json_encode(['status' => '1', 'message' => 'Product found', 'data' => $productData]);
            } else {
                return json_encode(['status' => '0', 'message' => 'Product not found', 'data' => '']);
            }
        } catch (Exception $e) {
            error_log("Error in showProductsById: " . $e->getMessage());
            return json_encode(['status' => 'error', 'message' => $e->getMessage(), 'data' => null]);
        }

        $prodStmt->close();
        $prodReqStmt->close();
        return 0;
    }








    function showProductsByIdOnTableName($productId, $tableName)
    {
        $productData = array();
        $productReqData = array();
        try {
            if ($tableName == 'products') {
                $selectProduct = "SELECT * FROM products WHERE product_id = ?";
                $prodStmt = $this->conn->prepare($selectProduct);

                if (!$prodStmt) {
                    throw new Exception("Statement preparation failed: " . $this->conn->error);
                }

                $prodStmt->bind_param("s", $productId);
                $prodStmt->execute();
                $prodResult = $prodStmt->get_result();

                if ($prodResult->num_rows > 0) {
                    while ($row = $prodResult->fetch_assoc()) {
                        $productData = $row;
                    }
                    return json_encode(['status' => '1', 'message' => 'Product found', 'data' => $productData]);
                    $prodStmt->close();
                } else {
                    return json_encode(['status' => '0', 'message' => 'Product not found', 'data' => '']);
                    $prodStmt->close();
                }
            }

            if ($tableName == 'product_request') {
                $selectProductRequest = "SELECT * FROM product_request WHERE product_id = ?";
                $prodReqStmt = $this->conn->prepare($selectProductRequest);

                if (!$prodReqStmt) {
                    throw new Exception("Statement preparation failed: " . $this->conn->error);
                }

                $prodReqStmt->bind_param("s", $productId);
                $prodReqStmt->execute();
                $prodReqResult = $prodReqStmt->get_result();

                if ($prodReqResult->num_rows > 0) {
                    while ($row = $prodReqResult->fetch_assoc()) {
                        $productReqData = $row;
                    }
                    return json_encode(['status' => '1', 'message' => 'Product found', 'data' => $productReqData]);
                    $prodReqStmt->close();
                } else {
                    return json_encode(['status' => '0', 'message' => 'Product not found', 'data' => '']);
                    $prodReqStmt->close();
                }
            }
        } catch (Exception $e) {
            return json_encode(['status' => 'error', 'message' => $e->getMessage(), 'data' => null]);
        }
    }







    function showProductsByIdOnTableNameAdminId($productId, $adminId, $tableName)
    {
        $productData = array();
        $productReqData = array();
        try {
            if ($tableName == 'products') {
                $selectProduct = "SELECT * FROM products WHERE product_id = ?";
                $prodStmt = $this->conn->prepare($selectProduct);

                if (!$prodStmt) {
                    throw new Exception("Statement preparation failed: " . $this->conn->error);
                }

                $prodStmt->bind_param("s", $productId);
                $prodStmt->execute();
                $prodResult = $prodStmt->get_result();

                if ($prodResult->num_rows > 0) {
                    while ($row = $prodResult->fetch_assoc()) {
                        $productData = $row;
                    }
                    return json_encode(['status' => '1', 'message' => 'Product found', 'data' => $productData]);
                    $prodStmt->close();
                } else {
                    return json_encode(['status' => '0', 'message' => 'Product not found', 'data' => '']);
                    $prodStmt->close();
                }
            }

            if ($tableName == 'product_request') {
                $selectProductRequest = "SELECT * FROM product_request WHERE product_id = ? AND admin_id = ?";
                $prodReqStmt = $this->conn->prepare($selectProductRequest);

                if (!$prodReqStmt) {
                    throw new Exception("Statement preparation failed: " . $this->conn->error);
                }

                $prodReqStmt->bind_param("ss", $productId, $adminId);
                $prodReqStmt->execute();
                $prodReqResult = $prodReqStmt->get_result();

                if ($prodReqResult->num_rows > 0) {
                    while ($row = $prodReqResult->fetch_assoc()) {
                        $productReqData = $row;
                    }
                    return json_encode(['status' => '1', 'message' => 'Product found', 'data' => $productReqData]);
                    $prodReqStmt->close();
                } else {
                    return json_encode(['status' => '0', 'message' => 'Product not found', 'data' => '']);
                    $prodReqStmt->close();
                }
            }
        } catch (Exception $e) {
            return json_encode(['status' => 'error', 'message' => $e->getMessage(), 'data' => null]);
        }
    }





    function prodSearchByMatch($match)
    {
        try {
            if ($match == 'all') {

                $select = "SELECT * FROM `products` LIMIT 6";
                $stmt = $this->conn->prepare($select);
            } else {

                $select = "SELECT * FROM `products` WHERE 
                       `name` LIKE CONCAT('%', ?, '%') OR 
                       `comp_1` LIKE CONCAT('%', ?, '%') OR
                       `comp_2` LIKE CONCAT('%', ?, '%') LIMIT 6";
                $stmt = $this->conn->prepare($select);
            }


            if ($stmt) {
                if ($match != 'all') {
                    $stmt->bind_param("sss", $match, $match, $match);
                }

                $stmt->execute();
                $result = $stmt->get_result();

                if ($result->num_rows > 0) {

                    while ($row = $result->fetch_object()) {
                        $data[] = $row;
                    }

                    return json_encode(['status' => 1, 'message' => 'success', 'data' => $data]);
                } else {
                    return json_encode(['status' => 0, 'message' => 'empty', 'data' => '']);
                }
                $stmt->close();
            } else {
                return json_encode(['status' => 0, 'message' => "Statement preparation failed: " . $this->conn->error, 'data' => '']);
            }
        } catch (Exception $e) {
            return json_encode(['status' => 0, 'message' => "Error: " . $e->getMessage(), 'data' => '']);
        }
    }








    function prodSearchByMatchForUser($match, $adminId)
    {
        try {
            $productData = array(); // Initialize productData array
            $productReqData = array(); // Initialize productReqData array

            if ($match == 'all') {
                $select = "SELECT * FROM `products` LIMIT 6";
                $stmt = $this->conn->prepare($select);
            } else {
                $select = "SELECT * FROM `products` WHERE 
                   `name` LIKE CONCAT('%', ?, '%') OR 
                   `comp_1` LIKE CONCAT('%', ?, '%') OR
                   `comp_2` LIKE CONCAT('%', ?, '%') LIMIT 6";
                $stmt = $this->conn->prepare($select);
            }

            if ($stmt) {
                if ($match != 'all') {
                    $stmt->bind_param("sss", $match, $match, $match);
                }

                $stmt->execute();
                $result = $stmt->get_result();

                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_object()) {
                        $productData[] = $row;
                    }
                }
            }

            // ----- SEARCH FROM PRODUCT REQUEST TABLE ----------
            if ($match == 'all') {
                $selectProdReq = "SELECT * FROM `product_request` WHERE `admin_id` = '$adminId' LIMIT 6";
                $prodReqStmt = $this->conn->prepare($selectProdReq);
            } else {
                $selectProdReq = "SELECT * FROM `product_request` WHERE 
                   `name` LIKE CONCAT('%', ?, '%') AND admin_id = ? LIMIT 6";
                $prodReqStmt = $this->conn->prepare($selectProdReq);
            }

            if ($prodReqStmt) {
                if ($match != 'all') {
                    $prodReqStmt->bind_param("ss", $match, $adminId);
                }

                $prodReqStmt->execute();
                $prodReqResult = $prodReqStmt->get_result();

                // print_r($prodReqResult);

                if ($prodReqResult->num_rows > 0) {
                    while ($row = $prodReqResult->fetch_object()) {
                        $productReqData[] = $row;
                    }
                }
            }

            if (!$stmt || !$prodReqStmt) {
                return json_encode(['status' => 0, 'message' => "Statement preparation failed: " . $this->conn->error, 'data' => '']);
            }

            if ($result->num_rows > 0 || $prodReqResult->num_rows > 0) {
                $data = array_merge($productData, $productReqData);
                // $data = $productReqData;

                return json_encode(['status' => 1, 'message' => 'success', 'data' => $data]);
            } else {
                return json_encode(['status' => 0, 'message' => 'empty', 'data' => '']);
            }
        } catch (Exception $e) {
            return json_encode(['status' => 0, 'message' => "Error: " . $e->getMessage(), 'data' => '']);
        }
    }







    function showProductsByTable($table, $data)
    {
        try {
            $slectProduct        = "SELECT * FROM products WHERE `$table` = ?";
            $stmt = $this->conn->prepare($slectProduct);
            $stmt->bind_param("s", $data);
            $stmt->execute();
            $slectProductQuery = $stmt->get_result();

            $resultData = [];

            if ($slectProductQuery->num_rows > 0) {
                while ($result = $slectProductQuery->fetch_assoc()) {
                    $resultData[] = $result;
                }
                return json_encode(['status' => '1', 'message' => 'data found', 'data' => $resultData]);
            } else {
                return json_encode(['status' => '0', 'message' => 'no data found', 'data' => '']);
            }
        } catch (Exception $e) {
            return json_encode(['status' => '0', 'message' => 'error', 'details' => $e->getMessage()]);
        }
    }







    function showProductsByTId($productTId)
    {
        $slectProduct        = "SELECT * FROM products WHERE `products`.`id` = '$productTId'";
        $slectProductQuery   = $this->conn->query($slectProduct);
        $rows                = $slectProductQuery->num_rows;
        if ($rows == 0) {
            return 0;
        } else {
            while ($result  = $slectProductQuery->fetch_array()) {
                $data[] = $result;
            }
            return $data;
        }
    } //eof showProductsById function






    function modifyProductByColData($prodId, $col1, $data1, $updatedBy, $updatedOn, $col2 = null, $data2 = null, $col3 = null, $data3 = null)
    {
        try {
            $updateProduct = "UPDATE `products` SET `$col1`=?, `updated_by`=?, `updated_on`=?, `admin_id`=?";

            // Append additional columns and parameters if provided
            $bindTypes = "ssss";
            $bindParams = array($data1, $updatedBy, $updatedOn, $prodId);
            if ($col2 !== null) {
                $updateProduct .= ", `$col2`=?";
                $bindTypes .= "s";
                $bindParams[] = $data2;
            }
            if ($col3 !== null) {
                $updateProduct .= ", `$col3`=?";
                $bindTypes .= "s";
                $bindParams[] = $data3;
            }
            $updateProduct .= " WHERE `product_id`=?";

            // Prepare and execute the statement
            $stmt = $this->conn->prepare($updateProduct);
            $stmt->bind_param($bindTypes, ...$bindParams);

            if ($stmt->execute()) {
                $stmt->close();
                return json_encode(['status' => '1', 'message' => 'success']);
            } else {
                return json_encode(['status' => '0', 'message' => 'fail']);
            }
        } catch (Exception $e) {
            return json_encode(['status' => '0', 'message' => 'error', 'details' => $e->getMessage()]);
        }
    }







    function updateProductValuebyCol($productid, $col, $value, $updatedBy, $updatedOn, $adminId)
    {
        try {

            $updateProduct = "UPDATE `products` SET `$col`=?, `updated_by`=?, `updated_on`=?, `admin_id`=? WHERE `product_id`=?";

            $stmt = $this->conn->prepare($updateProduct);
            $stmt->bind_param("sssss", $value, $updatedBy, $updatedOn, $adminId, $productid);

            if ($stmt->execute()) {
                $stmt->close();
                return json_encode(['status' => '1', 'message' => 'success']);
            } else {
                return json_encode(['status' => '0', 'message' => 'fail']);
            }
        } catch (Exception $e) {
            return json_encode(['status' => '0', 'message' => 'error', 'details' => $e->getMessage()]);
        }
    }




    function updateProduct($productid, $name, $manufacturerId, $type, $comp_1, $comp_2, $power, $dsc, $quantity, $quantityUnit, $itemUnit, $packagingType, $mrp, $gst, $updatedBy, $updatedOn)
    {
        try {
            $updateProduct = "UPDATE `products` SET `name`=?, `manufacturer_id`=?, `type`=?, `comp_1`=?, `comp_2`=?, `power`=?, `dsc`=?, `unit_quantity`=?, `unit_id`=?, `unit`=?, `packaging_type`=?, `mrp`=?, `gst`=?, `updated_by`=?, `updated_on`=? WHERE `product_id`=?";

            $stmt = $this->conn->prepare($updateProduct);
            $stmt->bind_param("ssssssssssssssss", $name, $manufacturerId, $type, $comp_1, $comp_2, $power, $dsc, $quantity, $quantityUnit, $itemUnit, $packagingType, $mrp, $gst, $updatedBy, $updatedOn, $productid);

            if ($stmt->execute()) {
                // Update successful
                $stmt->close();
                return true;
            } else {
                // Update failed
                throw new Exception("Error updating data in the database: " . $stmt->error);
            }
        } catch (Exception $e) {
            // Handle the exception, log the error, or return an error message as needed
            return "Error: " . $e->getMessage();
        }
    }


    //==================== product availibity on stock in =========================
    function selectItemLike($data)
    {
        $resultData = array();

        try {
            $searchSql = "SELECT * FROM `products` WHERE `name` LIKE ? OR `comp_1` LIKE ? OR `comp_2` LIKE ? LIMIT 10";
            $stmt = $this->conn->prepare($searchSql);

            if ($stmt) {

                $searchPattern = "%" . $data . "%";
                $stmt->bind_param("sss", $searchPattern, $searchPattern, $searchPattern);
                $stmt->execute();

                // Get the results
                $result = $stmt->get_result();

                while ($row = $result->fetch_assoc()) {
                    $resultData[] = $row;
                }

                $stmt->close();
            } else {
                throw new Exception("Failed to prepare the statement.");
            }
        } catch (Exception $e) {

            echo "Error: " . $e->getMessage();
        }

        return $resultData;
    }







    function selectItemLikeForStockInOut($data, $adminid)
    {
        $resultData1 = array();
        $resultData2 = array();

        try {
            $searchSql1 = "SELECT * FROM `products` WHERE `name` LIKE ? OR `comp_1` LIKE ? OR `comp_2` LIKE ?   LIMIT 10";
            $stmt1 = $this->conn->prepare($searchSql1);

            if ($stmt1) {
                $searchPattern = "%" . $data . "%";
                $stmt1->bind_param("sss", $searchPattern, $searchPattern, $searchPattern);
                $stmt1->execute();

                // Get the results
                $result1 = $stmt1->get_result();

                while ($row = $result1->fetch_assoc()) {
                    $resultData1[] = $row;
                }

                $stmt1->close();
            } else {
                throw new Exception("Failed to prepare the statement 1.");
            }

            $searchSql2 = "SELECT * FROM `product_request` WHERE `name` LIKE ? AND `admin_id`= ? LIMIT 10";
            $stmt2 = $this->conn->prepare($searchSql2);

            if ($stmt2) {
                $searchPattern = "%" . $data . "%";
                $stmt2->bind_param("ss", $searchPattern, $adminid);
                $stmt2->execute();

                // Get the results
                $result2 = $stmt2->get_result();

                while ($row = $result2->fetch_assoc()) {
                    $resultData2[] = $row;
                }

                $stmt2->close();
            } else {
                throw new Exception("Failed to prepare the statement 2.");
            }

            $mergedResults = array_merge($resultData1, $resultData2);

            return (['status' => '1', 'message' => 'data forund', 'data' => $mergedResults]);
        } catch (Exception $e) {
            // Log or handle the exception appropriately
            error_log("Error: " . $e->getMessage());
        }

        return (['status' => '0', 'message' => 'no data found']);
    }






    //==================== product availibity on stock in based on admin id ======================
    function selectItemLikeOnCol($data, $col, $colData)
    {
        $resultData = array();

        try {
            $searchSql = "SELECT * FROM `products` WHERE `products`.`name` LIKE ? AND `$col` = ?";
            $stmt = $this->conn->prepare($searchSql);

            if ($stmt) {
                $searchPattern = "%" . $data . "%";
                $stmt->bind_param("ss", $searchPattern, $colData);
                $stmt->execute();

                $result = $stmt->get_result();

                while ($row = $result->fetch_assoc()) {
                    $resultData[] = $row;
                }

                $stmt->close();
            } else {
                throw new Exception("Failed to prepare the statement.");
            }
        } catch (Exception $e) {
            echo "Error: " . $e->getMessage();
        }

        return $resultData;
    }





    function updateOnColData($col, $data, $prodId)
    {
        try {
            $updateOnColQry = "UPDATE products SET `$col` = ? WHERE `product_id` = ?";

            $stmt = $this->conn->prepare($updateOnColQry);

            $stmt->bind_param("is", $data, $prodId);

            if ($stmt->execute()) {
                $stmt->close();
                return true;
            } else {
                return new Exception("Error updating data in the database: " . $stmt->error);
            }
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }




    function deleteProduct($productId)
    {

        $Delete = "DELETE FROM `products` WHERE `products`.`product_id` = '$productId'";
        $DeleteQuey = $this->conn->query($Delete);
        return $DeleteQuey;
    } //end deleteProduct function






}//eof Products class
