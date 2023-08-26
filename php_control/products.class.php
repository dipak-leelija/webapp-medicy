<?php

require_once 'dbconnect.php';


 

class Products extends DatabaseConnection{



    function addProducts($productId, $manufacturerid, $productName, $power, $productDsc, $packagingType, $unitQuantity, $unit, $mrp, $gst, $productComposition){

        $insertProducts = "INSERT INTO `products` (`product_id`, `manufacturer_id`, `name`, `power`, `dsc`, `packaging_type`, `unit_quantity`, `unit`, `mrp`, `gst`, `product_composition`) VALUES ('$productId', '$manufacturerid', '$productName', '$power', '$productDsc', '$packagingType', '$unitQuantity', '$unit', '$mrp', '$gst', '$productComposition')";

        $insertProductsQuery = $this->conn->query($insertProducts);
        // echo $insertProductsQuery.$this->conn->error;
        // exit;

        return $insertProductsQuery;
    }//eof addProduct function
    
    
    

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





    function updateProduct($productid, $productname, $productPower, $productManuf, $productDsc, $productPackaging, $unitQty, $unit, $mrp, $gst, $addedBy, $productComposition){

        $updateProduct = " UPDATE `products` SET `manufacturer_id`	= '$productManuf', `name` = '$productname',
        `power` = '$productPower', `dsc` = '$productDsc', `packaging_type` = '$productPackaging', `unit_quantity` = '$unitQty',`unit` ='$unit', `mrp` ='$mrp', `gst` = '$gst', `added_by` = '$addedBy', `product_composition` = '$productComposition' WHERE `products`.`id` = '$productid'";
        // echo $updateProduct.$this->conn->error;

        $updateQuery = $this->conn->query($updateProduct);

        return $updateQuery;

    }// end updateProduct function





    function deleteProduct($productTableId){

        $Delete = "DELETE FROM `products` WHERE `products`.`product_id` = '$productTableId'";
        $DeleteQuey = $this->conn->query($Delete);
        return $DeleteQuey;

    }//end deleteProduct function






}//eof Products class

