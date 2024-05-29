<?php
require_once 'dbconnect.php';

class SubTests
{

    use DatabaseConnection;


    function showSubTests()
    {
        try {
            $data = [];
            $selectTest = "SELECT * FROM `sub_tests`";
            $testQuery = $this->conn->query($selectTest);
            while ($result = $testQuery->fetch_array()) {
                $data[] = $result;
            }
            return $data;
        } catch (Exception $e) {
            $e->getMessage();
        }
    } // end showSubTests function




    function addSubTests($subTestName, $subTestUnit, $parentTestId, $ageGroup, $subTestPrep, $subTestDsc, $price)
    {
        try {
            $insertTest = "INSERT INTO sub_tests (sub_test_name, unit, parent_test_id, age_group, test_preparation, test_dsc, price) VALUES (?, ?, ?, ?, ?, ?, ?)";
            $stmt = $this->conn->prepare($insertTest);

            $stmt->bind_param("ssisssd", $subTestName, $subTestUnit, $parentTestId, $ageGroup, $subTestPrep, $subTestDsc, $price);

            $stmt->execute();

            if($stmt->affected_rows > 0){
                $result = ['status'=>true, 'message'=>'data insert success'];
            }else{
                $result = ['status'=>false, 'message'=>'insertion fails'];
            }
            $stmt->close();

            return json_encode($result);
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }



    function showSubTestsId($subTestId)
    {
        $data = [];
        $selectTestById = "SELECT * FROM sub_tests WHERE `sub_tests`.`id` = '$subTestId'";
        $subTestQuery = $this->conn->query($selectTestById);
        while ($result = $subTestQuery->fetch_array()) {
            $data[] = $result;
        }
        return $data;
    } // end showLabTypesById function



    function showSubTestsByCatId($showLabtypeId)
    {
        $selectTestByCatId = "SELECT * FROM `sub_tests` WHERE `sub_tests`.`parent_test_id` = '$showLabtypeId'";
        $subTestCatQuery = $this->conn->query($selectTestByCatId);
        $row = $subTestCatQuery->num_rows;
        if ($row == 0) {
            return 0;
        } else {
            while ($result = $subTestCatQuery->fetch_array()) {
                $data[]    = $result;
            }
            return $data;
        }
    } // end showSubTestsByCatId function



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
