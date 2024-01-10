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






    function addImageRequest($productId='', $productImage='', $addedBy='', $addedOn='', $adminId='', $status='')
    {
        try {
            if(!empty($adminId)){
                $insertImage = "INSERT INTO `product_images` (`product_id`, `image`, `added_by`, `added_on`, `admin_id`, `status`) VALUES (?, ?, ?, ?, ?, ?)";
                $stmt = $this->conn->prepare($insertImage);
                $stmt->bind_param("sssssi", $productId, $productImage, $addedBy, $addedOn, $adminId, $status);
            }else{
                $insertImage = "INSERT INTO `product_images` (`product_id`, `image`, `added_by`, `added_on`, `status`) VALUES (?, ?, ?, ?, ?)";
                $stmt = $this->conn->prepare($insertImage);
                $stmt->bind_param("ssssi", $productId, $productImage, $addedBy, $addedOn, $status);
            }

            // Prepare the SQL statement
            // $stmt = $this->conn->prepare($insertImage);
            // Bind parameters

            // $stmt->bind_param("sssssi", $productId, $productImage, $addedBy, $addedOn, $adminId, $status);

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






    function selectItemLikeProdReqest($data,$adminId)
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

                if($result->num_rows > 0){
                    while ($row = $result->fetch_assoc()) {
                        $resultData[] = $row;
                    }
                    return json_encode(['status'=>'1', 'message'=>'data found', 'data'=>$resultData]);
                }else{
                    return json_encode(['status'=>'0', 'message'=>'no data found', 'data'=>'']);
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
}

?>


