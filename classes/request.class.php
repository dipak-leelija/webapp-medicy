<?php

class Request extends DatabaseConnection
{

    function addNewProductRequest($productId, $prodName, $prodCategory, $packegingType,  $qantity, $packegingUnit, $medicinePower, $mrp, $gst, $hsnoNumber, $addedBy, $addedOn, $adminId, $status)
    {
        try {
            $addQuery = "INSERT INTO `product_request`(`product_id`, `name`, `type`, `packaging_type`,  `unit_quantity`, `unit`, `power`, `mrp`, `gst`, `hsno_number`, `requested_by`, `requested_on`, `admin_id`, `status`) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

            $stmt = $this->conn->prepare($addQuery);
            $stmt->bind_param("sssisssdisisss", $productId, $prodName, $prodCategory, $packegingType,  $qantity, $packegingUnit, $medicinePower, $mrp, $gst, $hsnoNumber, $addedBy, $addedOn, $adminId, $status);
          
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






    function addImageRequest($productId, $productImage, $addedBy, $addedOn, $adminId, $status)
    {
        try {

            $insertImage = "INSERT INTO `product_images` (`product_id`, `image`, `added_by`, `added_on`, `admin_id`, `status`) VALUES (?, ?, ?, ?, ?, ?)";

            // Prepare the SQL statement
            $stmt = $this->conn->prepare($insertImage);
            // Bind parameters

            $stmt->bind_param("sssssi", $productId, $productImage, $addedBy, $addedOn, $adminId, $status);

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
}

?>


