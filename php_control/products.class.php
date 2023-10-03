<?php

require_once 'dbconnect.php';


 

class Products extends DatabaseConnection{



    function addProducts($productId, $manufacturerid, $productName, $power, $productDsc, $packagingType, $unitQuantity, $unit, $unitName, $mrp, $gst, $productComposition) {
        try {
            $insertProducts = "INSERT INTO `products` (`product_id`, `manufacturer_id`, `name`, `power`, `dsc`, `packaging_type`, `unit_quantity`, `unit_id`, `unit`, `mrp`, `gst`, `product_composition`) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
    
            $stmt = $this->conn->prepare($insertProducts);
            $stmt->bind_param("ssssssssssss", $productId, $manufacturerid, $productName, $power, $productDsc, $packagingType, $unitQuantity, $unit, $unitName, $mrp, $gst, $productComposition);
    
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




    function showProductsById($productId){
        //echo $productId;
        $slectProduct   	 = "SELECT * FROM products WHERE `products`.`product_id` = '$productId'";
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





    function updateProduct($productid, $productname, $productPower, $productManuf, $productDsc, $productPackaging, $unitQty, $unit, $unitName, $mrp, $gst, $addedBy, $productComposition) {
        try {
            $updateProduct = "UPDATE `products` SET `manufacturer_id`=?, `name`=?, `power`=?, `dsc`=?, `packaging_type`=?, `unit_quantity`=?, `unit_id`=?, `unit`=?, `mrp`=?, `gst`=?, `added_by`=?, `product_composition`=? WHERE `product_id`=?";
    
            $stmt = $this->conn->prepare($updateProduct);
            $stmt->bind_param("sssssssssssss", $productManuf, $productname, $productPower, $productDsc, $productPackaging, $unitQty, $unit, $unitName, $mrp, $gst, $addedBy, $productComposition, $productid);
    
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
    




    function deleteProduct($productId){

        $Delete = "DELETE FROM `products` WHERE `products`.`product_id` = '$productId'";
        $DeleteQuey = $this->conn->query($Delete);
        return $DeleteQuey;

    }//end deleteProduct function






}//eof Products class

