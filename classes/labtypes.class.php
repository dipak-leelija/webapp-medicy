<?php
require_once 'dbconnect.php';

class LabTypes extends DatabaseConnection{

    function addLabTypes($imgFolder, $testName, $testPvdBy, $testDsc){
        $insert = "INSERT INTO tests_types (`image`, `test_type_name`, `provided_by`, `dsc`) VALUES ('$imgFolder','$testName', '$testPvdBy', '$testDsc')";
        $insertQuery =$this->conn->query($insert);
        // echo $insert.$this->conn->error;
        
        return $insertQuery;
    }// end addLabTypes function



    function showLabTypes(){
        $selectLabType = "SELECT * FROM tests_types";
        $labTypeQuery = $this->conn->query($selectLabType);
        $rows = $labTypeQuery->num_rows;
        if ($rows == 0) {
            return 0;
        }else{
            while ($result = $labTypeQuery->fetch_array()) {
                $data[] = $result;
            }
            return $data;
        }
    }// end showLabTypes function


    function showLabTypesById($showLabtypeId){
        $selectLabType = "SELECT * FROM tests_types WHERE `tests_types`.`id` = '$showLabtypeId'";
        $labTypeQuery = $this->conn->query($selectLabType);
        while ($result = $labTypeQuery->fetch_array()) {
            $data[] = $result;
        }
        return $data;
    }// end showLabTypesById function



    function updateLabTypes($testTypeName, $pvdBy, $dsc, /*Last Veriable to select the id of the lab tyoe whichi we wants to delete*/ $updateLabType){
        $editLabType = "UPDATE  `tests_types` SET `test_type_name` = '$testTypeName', `provided_by`='$pvdBy', `dsc`= '$dsc' WHERE `tests_types`.`id` = '$updateLabType'";
        $editLabTypeQuery = $this->conn->query($editLabType);
        // echo $editLabType.$this->conn->error;
        // exit;
        return $editLabTypeQuery; 
    }// end editLabTypes function





    function deleteLabTypes($delTestTypeId){
        $deletelabType = "DELETE FROM `tests_types` WHERE `tests_types`.`id` = '$delTestTypeId'";
        $deletelabQuery = $this->conn->query($deletelabType);
        return $deletelabQuery;
    }// end deleteLabTypes function




}//end of LabTypes Class


?>