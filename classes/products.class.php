<?php

class Products extends DatabaseConnection{



    function addProducts($productId, $manufacturerid, $productName, $productComposition1, $productComposition2, $power, $productDsc, $packagingType, $unitQuantity, $unit, $unitName, $mrp, $gst, $addedBy, $addedOn, $adminId) {
        try {
            $insertProducts = "INSERT INTO `products` (`product_id`, `manufacturer_id`, `name`, `comp_1`,`comp_2`, `power`, `dsc`, `packaging_type`, `unit_quantity`, `unit_id`, `unit`, `mrp`, `gst`, `added_by`, `added_on`, `admin_id`) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
    
            $stmt = $this->conn->prepare($insertProducts);
            $stmt->bind_param("ssssssssssssssss", $productId, $manufacturerid, $productName, $productComposition1, $productComposition2, $power, $productDsc, $packagingType, $unitQuantity, $unit, $unitName, $mrp, $gst, $addedBy, $addedOn, $adminId);
    
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
    

    

    function showProducts(){
        $slectProduct   	 = "SELECT * FROM products";
        $slectProductQuery   = $this->conn->query($slectProduct);
        $rows                = $slectProductQuery->num_rows;
        if ($rows == 0) {
            return 0;
        }else{
            return $slectProductQuery;
        }

    }//eof showProducts function




    function showProductsByLimit() {
        try {
            $selectProduct = "SELECT * FROM products LIMIT 10";
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
    




    function showProductsByCol($col, $adminId){
        try {
            $selectProduct = "SELECT * FROM products WHERE `$col` = ?";
            
            $stmt = $this->conn->prepare($selectProduct);
            if (!$stmt) {
                throw new Exception("Statement preparation failed: " . $this->conn->error);
            }
            
            // Bind parameter
            $stmt->bind_param("s", $adminId);
            
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
    



    
    function showProductsById($productId) {
        try {
            $selectProduct = "SELECT * FROM products WHERE product_id = ?";
            $stmt = $this->conn->prepare($selectProduct);
    
            $stmt->bind_param("s", $productId);
            $stmt->execute();
            $result = $stmt->get_result();
    
            if ($result->num_rows == 0) {
                return 0; 
            } else {
                $data = array();
    
                while ($row = $result->fetch_assoc()) {
                    $data[] = $row;
                }
    
                $stmt->close();
    
                return $data;
            }
        } catch (Exception $e) {
            return null;
        }
    }
    






    function showProductsByTable($table, $data){
        //echo $productId;
        $slectProduct   	 = "SELECT * FROM products WHERE `$table` = '$data'";
        $slectProductQuery   = $this->conn->query($slectProduct);
        $rows                = $slectProductQuery->num_rows;
        if ($rows == 0) {
            return 0;
        }else{
            while ($result  = $slectProductQuery->fetch_array() ) {
                $data[] = $result;
            }
            return $data;
        }
    }//eof showProductsById function



    function showProductsByTId($productTId){
        $slectProduct   	 = "SELECT * FROM products WHERE `products`.`id` = '$productTId'";
        $slectProductQuery   = $this->conn->query($slectProduct);
        $rows                = $slectProductQuery->num_rows;
        if ($rows == 0) {
            return 0;
        }else{
            while ($result  = $slectProductQuery->fetch_array() ) {
                $data[] = $result;
            }
            return $data;
        }
    }//eof showProductsById function



    
    function updateProduct($productid, $name, $manufacturerId, $type, $comp_1, $comp_2, $power, $dsc, $quantity, $quantityUnit, $itemUnit, $packagingType, $mrp, $gst, $updatedBy, $updatedOn) {
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
    function selectItemLike($data){
        $resultData = array();
        
        try {
            $searchSql = "SELECT * FROM `products` WHERE `name` LIKE ? OR `comp_1` LIKE ? OR `comp_2` LIKE ? LIMIT 10";
            $stmt = $this->conn->prepare($searchSql);

            if ($stmt) {
        
                $searchPattern = "%".$data ."%";
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






    //==================== product availibity on stock in based on admin id ======================
    function selectItemLikeOnCol($data, $col, $colData){
        $resultData = array();
        
        try {
            // Prepare the SQL statement with placeholders
            $searchSql = "SELECT * FROM `products` WHERE `products`.`name` LIKE ? AND `$col` = ?";
            $stmt = $this->conn->prepare($searchSql);

            if ($stmt) {
                // Bind the parameters and execute the query
                $searchPattern = "%" . $data . "%";
                $stmt->bind_param("ss", $searchPattern, $colData);
                $stmt->execute();

                // Get the results
                $result = $stmt->get_result();

                while ($row = $result->fetch_assoc()) {
                    $resultData[] = $row;
                }

                // Close the statement
                $stmt->close();
            } else {
                throw new Exception("Failed to prepare the statement.");
            }
        } catch (Exception $e) {
            // Handle the exception (e.g., log the error, return an error message, etc.)
            // You can customize this part to suit your needs.
            echo "Error: " . $e->getMessage();
        }

        return $resultData;
    }




    function deleteProduct($productId){

        $Delete = "DELETE FROM `products` WHERE `products`.`product_id` = '$productId'";
        $DeleteQuey = $this->conn->query($Delete);
        return $DeleteQuey;

    }//end deleteProduct function






}//eof Products class

