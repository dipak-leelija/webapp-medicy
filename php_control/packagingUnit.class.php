<?php
require_once 'dbconnect.php';


class PackagingUnits extends DatabaseConnection{

    function addPackagingUnit($unitName, $addedby){
        $insert         = "INSERT INTO packaging_unit (`unit_name`, `added_by`) VALUES ('$unitName', '$addedby')";
        // echo $insertQuery.$this->conn->error; exit;

        $insertQuery    =   $this->conn->query($insert);
        // echo $insertQuery.$this->conn->error; exit;
        
        return $insertQuery;
    }// end addLabTypes function


 
    function showPackagingUnits(){
        $select         = " SELECT * FROM packaging_unit";
        $selectQuery    = $this->conn->query($select);
        while ($result  = $selectQuery->fetch_array() ) {
            $data[] = $result;
        }
        return $data;
    }//eof showMeasureOfUnits





    function showPackagingUnitById($unitId){
        $select        = " SELECT * FROM packaging_unit WHERE `packaging_unit`.`id` = '$unitId'";
        $selectQuery   = $this->conn->query($select);
        if ( $selectQuery->num_rows > 0) {
            while ($result = $selectQuery->fetch_array() ) {
                $data[] = $result;
            }
            return $data;
        }
    }//eof showMeasureOfUnits



    function updateUnit($unitName, $unitId){

        $update = "UPDATE  `packaging_unit` SET `unit_name` = '$unitName' WHERE `packaging_unit`.`id` = '$unitId'";
        $updatedQuery = $this->conn->query($update);
        return $updatedQuery;

    }// end updateManufacturer function





    function deleteUnit($unitId){

        $Delete = "DELETE FROM `packaging_unit` WHERE `packaging_unit`.`id` = '$unitId'";
        $DeleteQuey = $this->conn->query($Delete);
        return $DeleteQuey;

    }//end deleteManufacturer function



    

}//end of LabTypes Class


?>