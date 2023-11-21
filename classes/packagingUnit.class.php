<?php

class PackagingUnits extends DatabaseConnection{

    function addPackagingUnit($unitName, $addedby, $addedOn, $adminId) {
        try {
            // Define the SQL query using a prepared statement
            $insert = "INSERT INTO packaging_type (`unit_name`, `added_by`, `added_on`, `admin_id`) VALUES (?, ?, ?, ?)";
            
            // Prepare the SQL statement
            $stmt = $this->conn->prepare($insert);
    
            if ($stmt) {
                // Bind the parameters
                $stmt->bind_param("ssss", $unitName, $addedby, $addedOn, $adminId);
    
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
    


 

    function updateUnit($unitId, $unitName, $updatedBy, $updatedOn) {
        try {
            $update = "UPDATE `packaging_type` SET `unit_name` = ?, `updated_by` = ?, `updated_on` = ? WHERE `id` = ?";
            
            $stmt = $this->conn->prepare($update);
    
            if ($stmt) {
                $stmt->bind_param("sssi", $unitName, $updatedBy, $updatedOn, $unitId);
    
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



    function showPackagingUnits(){
        $select         = " SELECT * FROM packaging_type";
        $selectQuery    = $this->conn->query($select);
        while ($result  = $selectQuery->fetch_array() ) {
            $data[] = $result;
        }
        return $data;
    }//eof showMeasureOfUnits




    function showPackagingUnitById($unitId){
        $select        = " SELECT * FROM packaging_type WHERE `id` = '$unitId'";
        $selectQuery   = $this->conn->query($select);
        if ( $selectQuery->num_rows > 0) {
            while ($result = $selectQuery->fetch_array() ) {
                $data[] = $result;
            }
            return $data;
        }
    }//eof showMeasureOfUnits




    function deleteUnit($unitId){

        $Delete = "DELETE FROM `packaging_type` WHERE `packaging_type`.`id` = '$unitId'";
        $DeleteQuey = $this->conn->query($Delete);
        return $DeleteQuey;

    }//end deleteManufacturer function



    

}//end of LabTypes Class


?>