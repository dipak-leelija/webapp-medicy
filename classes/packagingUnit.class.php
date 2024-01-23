<?php

class PackagingUnits extends DatabaseConnection{

    function addPackagingUnit($unitName, $addedby, $addedOn, $packStatus, $adminId) {
        try {
            // Define the SQL query using a prepared statement
            $insert = "INSERT INTO packaging_type (`unit_name`, `added_by`, `added_on`,`pack_status`, `admin_id`) VALUES (?, ?, ?, ?, ?)";
            
            // Prepare the SQL statement
            $stmt = $this->conn->prepare($insert);
    
            if ($stmt) {
                // Bind the parameters
                $stmt->bind_param("sssis", $unitName, $addedby, $addedOn, $packStatus, $adminId);
    
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

    ///======= Update Packaging Status ======///
    function updatePackStatus($newStatus , $packagingUnitId){
        try {
            $update = "UPDATE `packaging_type` SET `status` = ? WHERE `id` = ?";
            
            $stmt = $this->conn->prepare($update);
    
            if ($stmt) {
                $stmt->bind_param("ii", $newStatus, $packagingUnitId);
    
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
    } ///======= End Update Packaging Status ======///


    function showPackagingUnits($adminId = ''){
        $data = [];
        if(!empty($adminId)){
            $select         = " SELECT * FROM `packaging_type` WHERE `admin_id` = '$adminId' OR `status` = '2'";
        }else{
            $select         = " SELECT * FROM `packaging_type` ";
        }
        $selectQuery    = $this->conn->query($select);
        while ($result  = $selectQuery->fetch_assoc() ) {
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

    function packagingTypeName($unitId){
        $select        = " SELECT unit_name FROM packaging_type WHERE `id` = '$unitId'";
        $selectQuery   = $this->conn->query($select);
        if ( $selectQuery->num_rows > 0) {
            while ($result = $selectQuery->fetch_array() ) {
                $data = $result['unit_name'];
            }
            return $data;
        }
    }//eof showMeasureOfUnits



    function deleteUnit($unitId){

        $Delete = "DELETE FROM `packaging_type` WHERE `packaging_type`.`id` = '$unitId'";
        $DeleteQuey = $this->conn->query($Delete);
        return $DeleteQuey;

    }//end deleteManufacturer function



    function packUnitSearch($match, $adminId) {
        try {
            if ($match == 'all') {
                
                $select = "SELECT * FROM `packaging_type` WHERE `admin_id` = ? OR `status` = '2' LIMIT 6";
                $stmt = $this->conn->prepare($select);
                $stmt->bind_param("s", $adminId);

            }else {
                
                $select = "SELECT * FROM `packaging_type` WHERE 
                       `unit_name` LIKE CONCAT('%', ?, '%') OR 
                       `id` LIKE CONCAT('%', ?, '%') AND `admin_id` = ?  OR `status` = '2' LIMIT 6";
                $stmt = $this->conn->prepare($select);
                $stmt->bind_param("sss", $match, $match, $adminId);
            }
                       

            if ($stmt) {
                // if ($match != 'all') {
                //     $stmt->bind_param("ss", $match, $match);
                // }
                
                $stmt->execute();
                $result = $stmt->get_result();
    
                if ($result->num_rows > 0) {
    
                    while ($row = $result->fetch_object()) {
                        $data[] = $row;
                    }
    
                    return json_encode(['status' => 1, 'message' => 'success', 'data'=> $data]);
                } else {
                    return json_encode(['status' => 0, 'message' => 'empty', 'data'=> '']);
                }
                $stmt->close();
            } else {
                return json_encode(['status' => 0, 'message' => "Statement preparation failed: ".$this->conn->error, 'data'=> '']);
            }

        } catch (Exception $e) {
            return json_encode(['status' => 0, 'message' => "Error: " . $e->getMessage(), 'data'=> '']);

        }
    }

    

}//end of LabTypes Class


?>