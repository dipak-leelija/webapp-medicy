<?php
require_once 'dbconnect.php';
class LabReport extends DatabaseConnection{

    function patientDatafetch($patientId){
        try{
            $data=0;
            $sql = "SELECT * FROM `patient_details` where `patient_id` = '$patientId'";
            $query = $this->conn->query($sql);
            while($result = $query->fetch_object()){
                $data = $result;
            }
            $dataset = json_encode($data);
            return $dataset;

        }catch(Exception $e){
            echo $e->getMessage();
        }
    }

    function patientTest($testId){
        try{

            $sql = "SELECT * FROM `sub_tests` where `id` = '$testId'";
            $query = $this->conn->query($sql);
            while($result = $query->fetch_object()){
                $data = $result;
            }
            $dataset = json_encode($data);
            return $dataset;


        }catch(Exception $e){
            echo $e->getMessage();
        }
    }

    function labReportAdd($patient_name,$age,$sex,$test_name,$test_value,$test_date,$report_generate_date){
        
        try{
            $sql = " INSERT INTO `labreport_generate` (`patient_name`, `age`, `sex`, `test_name`, `test_value`,`test_date`,`report_generate_date`) VALUES ('$patient_name', '$age', '$sex', '$test_name', '$test_value','$test_date','$report_generate_date')";
            $query = $this->conn->query($sql);
            return $sql;
            
        }catch(Exception $e){
            echo $e->getMessage();
        }
    }
}
?>