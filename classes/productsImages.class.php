<?php

class ProductImages extends DatabaseConnection{



    function addImages($productId, $productImage, $setPriority, $addedBy, $addedOn, $adminId) {
        try {
    
            $insertImage = "INSERT INTO `product_images` (`product_id`, `image`, `set_priority`, `added_by`, `added_on`, `admin_id`) VALUES (?, ?, ?, ?, ?, ?)";
    
            // Prepare the SQL statement
            $stmt = $this->conn->prepare($insertImage);
            // Bind parameters
            
            $stmt->bind_param("ssssss", $productId, $productImage, $setPriority, $addedBy, $addedOn, $adminId);
    
            // Execute the statement
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



    function showImages(){
        $slectAll   	 = "SELECT * FROM product_images";
        $slectAllQuery   = $this->conn->query($slectAll);
        $rows                = $slectAllQuery->num_rows;
        if ($rows == 0) {
            return 0;
        }else{
            return $slectAllQuery;
        }

    }
    //eof showProducts function




    function showImageById($productId) {
        try {
            
            $selectImage = "SELECT * FROM product_images WHERE product_id = ?";
            $stmt = $this->conn->prepare($selectImage);
    
            $stmt->bind_param("s", $productId);
            $stmt->execute();
    
            $result = $stmt->get_result();
    
            if ($result->num_rows > 0) {
                $data = array();
                while ($row = $result->fetch_assoc()) {
                    $data[] = $row;
                }
                $stmt->close();
                return json_encode(['status' => '1', 'message' => 'Images found', 'data' => $data]);
            } else {
                $stmt->close();
                return json_encode(['status' => '0', 'message' => 'No images found', 'data' => null]);
            }
        } catch (Exception $e) {
            error_log("Error in showImageById: " . $e->getMessage());
    
            return json_encode(['status' => 'error', 'message' => $e->getMessage(), 'data' => null]);
        }
    }
    





    function showImageByPrimay($productId) {
        try {
            
            $selectImage = "SELECT * FROM product_images WHERE product_id = ? AND set_priority = '1'";
            $stmt = $this->conn->prepare($selectImage);
    
            $stmt->bind_param("s", $productId);
            $stmt->execute();
    
            $result = $stmt->get_result();
    
            if ($result->num_rows > 0) {
                $data = array();
                while ($row = $result->fetch_assoc()) {
                    $data = $row;
                }
                $stmt->close();
                return json_encode(['status' => '1', 'message' => 'Images found', 'data' => $data]);
            } else {
                $stmt->close();
                return json_encode(['status' => '0', 'message' => 'No images found', 'data' => null]);
            }
        } catch (Exception $e) {
            error_log("Error in showImageById: " . $e->getMessage());
    
            return json_encode(['status' => 'error', 'message' => $e->getMessage(), 'data' => null]);
        }
    }
    
    
    //eof showProductsById function


    // function showProductsByTId($productTId){
    //     $slectProduct   	 = "SELECT * FROM products WHERE `products`.`id` = '$productTId'";
    //     $slectProductQuery   = $this->conn->query($slectProduct);
    //     $rows                = $slectProductQuery->num_rows;
    //     if ($rows == 0) {
    //         return 0;
    //     }else{
    //         while ($result  = $slectProductQuery->fetch_array() ) {
    //             $data[] = $result;
    //         }
    //         return $data;
    //     }
    // }//eof showProductsById function


    // function updateProduct($productid, $productname, $productPower, $productManuf, $productDsc, $productPackaging, $unitQty, $unit, $mrp, $gst, $addedBy){

    //     $updateProduct = " UPDATE `products` SET `manufacturer_id`	= '$productManuf', `name` = '$productname',
    //     `power` = '$productPower', `dsc` = '$productDsc', `packaging_type` = '$productPackaging', `unit_quantity` = '$unitQty',`unit` ='$unit', `mrp` ='$mrp', `gst` = '$gst', `added_by` = '$addedBy' WHERE `products`.`id` = '$productid'";
    //     // echo $updateProduct.$this->conn->error;

    //     $updateQuery = $this->conn->query($updateProduct);

    //     return $updateQuery;

    // }

    
    function updateImage($productId, $image)
    {
        
        $updateImage = "UPDATE `product_images` SET `image`='$image' WHERE `product_images`.`product_id`='$productId'";

    
        $updateQuery = $this->conn->query($updateImage);

        return $updateQuery;
    }

    // end updateProduct function*/





    // function deleteProduct($productTableId){

    //     $Delete = "DELETE FROM `products` WHERE `products`.`id` = '$productTableId'";
    //     $DeleteQuey = $this->conn->query($Delete);
    //     return $DeleteQuey;

    // }//end deleteProduct function


    //delete product image

    function deleteImage($productId){
        $delImage = "DELETE FROM `product_images` WHERE `id`='$productId'";

        $delQry = $this->conn->query($delImage);
        
        return $delQry;
    }



}//eof Products class

