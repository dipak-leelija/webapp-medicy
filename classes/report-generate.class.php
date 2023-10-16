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

    // function labReportAdd($testValue,$unitValue){
    //     $testValue = $testValue.$unitValue;
    //     try{
    //             $sql = " INSERT INTO `lab_report_detail` (`test_value`) VALUES ('$testValue')";
    //             $query = $this->conn->query($sql);
    //             return $sql;
            
    //     }catch(Exception $e){
    //         echo $e->getMessage();
    //         // return false;
    //     }
    // }

    function labReportAdd($testValue, $unitValue,$testId) {
    try {
        $testValue = $testValue.'-' . $unitValue;

        $stmt = $this->conn->prepare("INSERT INTO `lab_report_detail` (`test_value`,`test_id`) VALUES (?,?)");

        if ($stmt) {
            $stmt->bind_param("ss", $testValue,$testId);

            if ($stmt->execute()) {
                $stmt->close();
                return true; // Indicates successful insertion
            } else {
                throw new Exception("Error executing statement: " . $stmt->error);
            }
        } else {
            throw new Exception("Error preparing statement: " . $this->conn->error);
        }

    } catch(Exception $e) {
        echo $e->getMessage();
        return false; // Indicates failed insertion
    }
    }

}
