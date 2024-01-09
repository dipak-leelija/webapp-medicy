<?php

class ProductImages extends DatabaseConnection
{



    function addImages($productId='', $productImage='', $addedBy='', $addedOn='', $adminId='')
    {
        try {
            if(!empty($adminId)){
                $insertImage = "INSERT INTO `product_images` (`product_id`, `image`, `added_by`, `added_on`, `admin_id`) VALUES (?, ?, ?, ?, ?)";
                $stmt = $this->conn->prepare($insertImage);
                $stmt->bind_param("sssss", $productId, $productImage, $addedBy, $addedOn, $adminId);
            }else{
                $insertImage = "INSERT INTO `product_images` (`product_id`, `image`, `added_by`, `added_on`) VALUES (?, ?, ?, ?)";
                $stmt = $this->conn->prepare($insertImage);
                $stmt->bind_param("ssss", $productId, $productImage, $addedBy, $addedOn);
            }

            // Prepare the SQL statement
            // $stmt = $this->conn->prepare($insertImage);
            // Bind parameters

            // $stmt->bind_param("sssss", $productId, $productImage, $addedBy, $addedOn, $adminId);

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



    function showImages()
    {
        $slectAll        = "SELECT * FROM product_images";
        $slectAllQuery   = $this->conn->query($slectAll);
        $rows                = $slectAllQuery->num_rows;
        if ($rows == 0) {
            return 0;
        } else {
            return $slectAllQuery;
        }
    }
    //eof showProducts function




    function showImageById($productId)
    {
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






    function showImageByPrimay($productId)
    {
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


    function showImageByImgId($productId)
    {
        try {

            $selectImage = "SELECT * FROM product_images WHERE id = ?";
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


    function updateImage($productId, $image, $updatedOn, $updatedBy)
    {

        $updateImage = "UPDATE `product_images` SET `image`='$image', `updated_on`='$updatedOn', `updated_by`='$updatedBy' WHERE `product_images`.`product_id`='$productId'";


        $updateQuery = $this->conn->query($updateImage);

        return $updateQuery;
    }

    function updatePriority($image, $setPriority, $productId)
    {
        $updateImage = "UPDATE `product_images` SET `set_priority`='$setPriority' WHERE `product_images`.`image`='$image' AND `product_images`.`product_id`='$productId'";
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

    function deleteImage($imageId)
    {
        try {
            $delImage = "DELETE FROM `product_images` WHERE `id`='$imageId'";

            $delQry = $this->conn->query($delImage);

            return $delQry;
        } catch (Exception $e) {
            $e->getMessage();
        }
    }

    function deleteImageByPID($productId)
    {
        $delImage = "DELETE FROM `product_images` WHERE `product_id`='$productId'";

        $delQry = $this->conn->query($delImage);

        return $delQry;
    }
}//eof Products class
