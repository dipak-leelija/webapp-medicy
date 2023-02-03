<?php

require_once 'dbconnect.php';


 

class ProductImages extends DatabaseConnection{



    function addImage($productId, $productImage, $backImage, $sideImage, $addedBy){

        $insertImage = "INSERT INTO `product_images` (`product_id`, `image`, `back_image`, `side_image`, `added_by`) VALUES ('$productId', '$productImage', '$backImage', '$sideImage', '$addedBy')";

        $insertImageQuery = $this->conn->query($insertImage);
        // echo $insertProductsQuery.$this->conn->error;
        // exit;

        return $insertImageQuery;
    }//eof addProduct function
    
    


    function showImages(){
        $slectAll   	 = "SELECT * FROM product_images";
        $slectAllQuery   = $this->conn->query($slectAll);
        $rows                = $slectAllQuery->num_rows;
        if ($rows == 0) {
            return 0;
        }else{
            return $slectAllQuery;
        }

    }//eof showProducts function




    function showImageById($productId){
        $slectImage   	 = "SELECT * FROM product_images WHERE `product_images`.`product_id` = '$productId'";
        $slectImageQuery   = $this->conn->query($slectImage);
        $rows                = $slectImageQuery->num_rows;
        if ($rows == 0) {
            return 0;
        }else{
            while ($result  = $slectImageQuery->fetch_array() ) {
                $data[] = $result;
            }
            return $data;
        }
    }//eof showProductsById function


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

    // }// end updateProduct function





    // function deleteProduct($productTableId){

    //     $Delete = "DELETE FROM `products` WHERE `products`.`id` = '$productTableId'";
    //     $DeleteQuey = $this->conn->query($Delete);
    //     return $DeleteQuey;

    // }//end deleteProduct function






}//eof Products class

