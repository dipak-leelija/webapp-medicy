<?php
require_once 'dbconnect.php';
 

class Manufacturer extends DatabaseConnection{


    function addManufacturer($manufacturerName, $shortName, $manufacturerDsc, $addedBy, $addedOn, $adminId) {
        try {
            // Define the SQL query using a prepared statement
            $insert = "INSERT INTO manufacturer (`name`, `short_name`, `dsc`, `added_by`, `added_on`, `admin_id`)   VALUES (?, ?, ?, ?, ?, ?)";

            // Prepare the SQL statement
            $stmt = $this->conn->prepare($insert);

            if ($stmt) {
                // Bind the parameters
                $stmt->bind_param("ssssss", $manufacturerName, $shortName, $manufacturerDsc, $addedBy, $addedOn,    $adminId);

                // Execute the query
                $insertQuery = $stmt->execute();
                $stmt->close();
                return $insertQuery;
            } else {
                throw new Exception("Failed to prepare the statement.");
            }
        } catch (Exception $e) {
            // Handle any exceptions that occur
            // Customize this part to suit your needs
            echo "Error: " . $e->getMessage();
            return false;
        }
    }





    function updateManufacturer($manufacturerName, $manufacturerDsc, $manufacturerId, $manufShortName,  $updatedBy, $updatedOn) {
        try {
            // Define the SQL query using a prepared statement
            $update = "UPDATE `manufacturer` SET `name`=?, `dsc`=?, `short_name`=?, `updated_by`=?,     `updated_on`=? WHERE `id`=?";

            // Prepare the SQL statement
            $stmt = $this->conn->prepare($update);

            if ($stmt) {
                // Bind the parameters
                $stmt->bind_param("sssssi", $manufacturerName, $manufacturerDsc, $manufShortName, $updatedBy,   $updatedOn, $manufacturerId);

                
                $updatedQuery = $stmt->execute();
                $stmt->close();

                return $updatedQuery;
            } else {
                throw new Exception("Failed to prepare the statement.");
            }
        } catch (Exception $e) {
            // Handle any exceptions that occur
            // Customize this part to suit your needs
            echo "Error: " . $e->getMessage();
            return false;
        }
    }




    function showManufacturer(){
        $select         = " SELECT * FROM `manufacturer`";
        $selectQuery    = $this->conn->query($select);
        while ($result  = $selectQuery->fetch_array() ) {
            $data[] = $result;
        }
        return $data;
    }//eof showManufacturer functiion





    function showManufacturerById($manufacturerId){
        $select         = " SELECT * FROM `manufacturer` WHERE `manufacturer`.`id` = '$manufacturerId'";
        $selectQuery    = $this->conn->query($select);
        $row = $selectQuery->num_rows;
        if ($row == 0) {
            return 0;
        }else {
            while ($result  = $selectQuery->fetch_array() ) {
                $data[] = $result;
            }
            return $data;
        }
    }//eof showManufacturerById functiion



    // function showManufacturerByDistributorId($distributorId){
    //     $select         = " SELECT * FROM manufacturer WHERE `manufacturer`.`distributor_id` ='$distributorId'";
    //     $selectQuery    = $this->conn->query($select);
    //     while ($result  = $selectQuery->fetch_array() ) {
    //         $data[] = $result;
    //     }
    //     return $data;
    // }//eof showManufacturer functiion




    



    function deleteManufacturer($manufacturerId) {
        try {
            $delete = "DELETE FROM `manufacturer` WHERE `id` = ?";
        
            $stmt = $this->conn->prepare($delete);
    
            if ($stmt) {
                $stmt->bind_param("i", $manufacturerId);
    
                $deleteQuery = $stmt->execute();
                $stmt->close();
                return $deleteQuery;
            } else {
                throw new Exception("Failed to prepare the statement.");
            }
        } catch (Exception $e) {
            echo "Error: " . $e->getMessage();
            return false;
        }
    }
    






    

}//end of LabTypes Class
