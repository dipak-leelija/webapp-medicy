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




    function showManufacturer() {
        try {
            $data = array();
            $select = "SELECT * FROM `manufacturer`";
            $selectQuery = $this->conn->prepare($select);
    
            if (!$selectQuery) {
                throw new Exception("Query preparation failed.");
            }
    
            $selectQuery->execute();
    
            $result = $selectQuery->get_result();
    
            if($result->num_rows > 0){
                while ($row = $result->fetch_object()) {
                    $data[] = $row;
                }
                return json_encode($data);
            }else{
                return null;
            }
        } catch (Exception $e) {
            echo "Error in showManufacturer: " . $e->getMessage();
        }
        return 0;
    }
    

    




    function showManufacturerById($manufacturerId) {
        try {
            $select = "SELECT * FROM `manufacturer` WHERE `manufacturer`.`id` = ?";
            $stmt = $this->conn->prepare($select);
    
            $stmt->bind_param("s", $manufacturerId); 
            $stmt->execute();
    
            $result = $stmt->get_result();
    
            if ($result->num_rows > 0) {
                $data = array();
                while ($row = $result->fetch_assoc()) {
                    $data[] = $row;
                }
                $stmt->close();
                return $data;
            } else {
                $stmt->close();
                return 0;
            }
        } catch (Exception $e) {
            
            return null;
        }
    }
    



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
