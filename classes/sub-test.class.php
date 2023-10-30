<?php
require_once 'dbconnect.php';

class SubTests extends DatabaseConnection{



    function showSubTests(){
        $selectTest = "SELECT * FROM `sub_tests`";
        $testQuery = $this->conn->query($selectTest);
        while ($result = $testQuery->fetch_array()) {
            $data[] = $result;
        }
        return $data;
    }// end showSubTests function




    function addSubTests($subTestName, $parentTestId, $ageGroup, $subTestPrep, $subTestDsc, $price){
        $insertTest = "INSERT INTO sub_tests (sub_test_name, parent_test_id, age_group, test_preparation, test_dsc, price) VALUES ('$subTestName', '$parentTestId', '$ageGroup', '$subTestPrep', '$subTestDsc', '$price')";
        $insertTestQuery =$this->conn->query($insertTest);
        // echo $insertTest.$this->conn->error;
        // exit;
        
        return $insertTestQuery;
    }// end addLabTypes function



    function showSubTestsId($subTestId){
        // $data=0;
        $selectTestById = "SELECT * FROM sub_tests WHERE `sub_tests`.`id` = '$subTestId'";
        $subTestQuery = $this->conn->query($selectTestById);
        while ($result = $subTestQuery->fetch_array()) {
            $data[] = $result;
        }
        return $data;
    }// end showLabTypesById function

    

    function showSubTestsByCatId($showLabtypeId){
        $selectTestByCatId = "SELECT * FROM `sub_tests` WHERE `sub_tests`.`parent_test_id` = '$showLabtypeId'";
        $subTestCatQuery = $this->conn->query($selectTestByCatId);
        $row = $subTestCatQuery->num_rows;
        if ($row == 0) {
            return 0;
        }else{
            while($result = $subTestCatQuery->fetch_array()){
                $data[]	= $result;
            }
            return $data;
        }
        
    }// end showSubTestsByCatId function



    // function updateSubTests($testTypeName, $pvdBy, $dsc, /*Last Veriable to select the id of the lab tyoe whichi we wants to delete*/ $updateLabType){
    //     $editLabType = "UPDATE  `tests_types` SET `test_type_name` = '$testTypeName', `provided_by`='$pvdBy', `dsc`= '$dsc' WHERE `tests_types`.`id` = '$updateLabType'";
    //     $editLabTypeQuery = $this->conn->query($editLabType);
    //     // echo $editLabType.$this->conn->error;
    //     // exit;
    //     return $editLabTypeQuery; 
    // }// end editLabTypes function





    // function deleteSubTests($delTestTypeId){
    //     $deletelabType = "DELETE FROM `tests_types` WHERE `tests_types`.`id` = '$delTestTypeId'";
    //     $deletelabQuery = $this->conn->query($deletelabType);
    //     return $deletelabQuery;
    // }// end deleteLabTypes function


} //eof SubTests class


?>