<?php

class ProductCategory extends DatabaseConnection{

    function addCategory($category, $addedBy, $addedOn) {
        try {
            $insertProdCategory = "INSERT INTO `product_category` (`prod_category`, `added_by`, `added_on`) VALUES (?, ?, ?)";
    
            $stmt = $this->conn->prepare($insertProdCategory);
            $stmt->bind_param("sss", $category, $addedBy, $addedOn);
    
            if ($stmt->execute()) {
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


    function selectAllProdCategory() {
        try {
            $selectAllCategory = "SELECT * FROM `product_category`";
        
            $selectProductQuery = $this->conn->query($selectAllCategory);
    
            if (!$selectProductQuery) {
                throw new Exception("Query execution failed");
            }
    
            $rows = $selectProductQuery->num_rows;
    
            if ($rows > 0) {
               
                $categories = array();
    
                while ($category = $selectProductQuery->fetch_object()) {
                    $categories[] = $category;
                }
                return json_encode(['status'=>'1', 'message' => 'success', 'data' => $categories]);
            }else {

                return json_encode(['status' => '0', 'message' => 'No categories found.', 'data' => '']);
            
            }
        } catch (Exception $e) {
            return json_encode(['status' => '', 'message' => $e->getMessage(), 'data'=>'']);
        }
        return 0;
    }
    
}