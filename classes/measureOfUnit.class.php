<?php
require_once 'dbconnect.php';


class MeasureOfUnits extends DatabaseConnection{

    function addMeasureOfUnits($srtName, $fullName){
        $insert         = "INSERT INTO measure_of_unit (`short_name`, `full_name`) VALUES ('$srtName', '$fullName')";
        $insertQuery    =   $this->conn->query($insert);
        // echo $insert.$this->conn->error;
        
        return $insertQuery;
    }// end addLabTypes function


 
    function showMeasureOfUnits(){
        $data           = array();
        $select         = " SELECT * FROM measure_of_unit";
        $selectQuery    = $this->conn->query($select);
        while ($result  = $selectQuery->fetch_array() ) {
            $data[] = $result;
        }
        return $data;
    }//eof showMeasureOfUnits





    function showMeasureOfUnitsById($unitId){
        $data          = array();
        $select        = " SELECT * FROM measure_of_unit WHERE `measure_of_unit`.`id` = '$unitId'";
        $selectQuery   = $this->conn->query($select);
        while ($result = $selectQuery->fetch_array() ) {
            $data[] = $result;
        }
        return $data;
    }//eof showMeasureOfUnits



    function updateUnit($srtName, $fullName, $unitId){

        $update = "UPDATE  `measure_of_unit` SET `short_name` = '$srtName', `full_name` = '$fullName' WHERE `measure_of_unit`.`id` = '$unitId'";
        $updatedQuery = $this->conn->query($update);
        return $updatedQuery;

    }// end updateManufacturer function





    function deleteUnit($unitId){

        $Delete = "DELETE FROM `measure_of_unit` WHERE `measure_of_unit`.`id` = '$unitId'";
        $DeleteQuey = $this->conn->query($Delete);
        return $DeleteQuey;

    }//end deleteManufacturer function



    

}//end of LabTypes Class


?>