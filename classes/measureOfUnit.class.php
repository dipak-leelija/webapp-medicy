<?php
require_once 'dbconnect.php';


class MeasureOfUnits extends DatabaseConnection{

    function addMeasureOfUnits($shortName, $fullName, $addedBy, $addedOn, $adminId) {
        try {
            // Define the SQL query using a prepared statement
            $insert = "INSERT INTO quantity_unit (`short_name`, `full_name`, `added_by`, `added_on`, `admin_id`) VALUES (?, ?, ?, ?, ?)";
            
            // Prepare the SQL statement
            $stmt = $this->conn->prepare($insert);
    
            if ($stmt) {
                // Bind the parameters
                $stmt->bind_param("ssisi", $shortName, $fullName, $addedBy, $addedOn, $adminId);
    
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
    


 

    function updateUnit($shortName, $fullName, $unitId, $updatedBy, $updatedOn) {
        try {
            $update = "UPDATE `quantity_unit` SET `short_name` = ?, `full_name` = ?, `updated_by` = ?, `updated_on` = ? WHERE `id` = ?";
            
            $stmt = $this->conn->prepare($update);
    
            if ($stmt) {
                $stmt->bind_param("ssisi", $shortName, $fullName, $updatedBy, $updatedOn, $unitId);

                $updatedQuery = $stmt->execute();
                $stmt->close();
                return $updatedQuery;
            } else {
                throw new Exception("Failed to prepare the statement.");
            }
        } catch (Exception $e) {
            echo "Error: " . $e->getMessage();
            return false;
        }
    }





    function showMeasureOfUnits(){
        $data           = array();
        $select         = " SELECT * FROM quantity_unit";
        $selectQuery    = $this->conn->query($select);
        while ($result  = $selectQuery->fetch_array() ) {
            $data[] = $result;
        }
        return $data;
    }//eof showMeasureOfUnits





    function showMeasureOfUnitsById($unitId){
        $data          = array();
        $select        = " SELECT * FROM quantity_unit WHERE `quantity_unit`.`id` = '$unitId'";
        $selectQuery   = $this->conn->query($select);
        while ($result = $selectQuery->fetch_assoc() ) {
            $data = $result;
        }
        return $data;
    }//eof showMeasureOfUnits



    
    





    function deleteUnit($unitId){

        $Delete = "DELETE FROM `quantity_unit` WHERE `quantity_unit`.`id` = '$unitId'";
        $DeleteQuey = $this->conn->query($Delete);
        return $DeleteQuey;

    }//end deleteManufacturer function



    

}//end of LabTypes Class


?>