<?php
require_once 'dbconnect.php';
class LabReport extends DatabaseConnection
{

    function patientDatafetch($patientId)
    {
        try {
            $data = 0;
            $sql = "SELECT * FROM `patient_details` where `patient_id` = '$patientId'";
            $query = $this->conn->query($sql);
            while ($result = $query->fetch_object()) {
                $data = $result;
            }
            $dataset = json_encode($data);
            return $dataset;
        } catch (Exception $e) {
            echo $e->getMessage();
        }
    }

    function patientTest($testId)
    {
        try {

            $sql = "SELECT * FROM `sub_tests` where `id` = '$testId'";
            $query = $this->conn->query($sql);
            while ($result = $query->fetch_object()) {
                $data = $result;
            }
            $dataset = json_encode($data);
            return $dataset;
        } catch (Exception $e) {
            echo $e->getMessage();
        }
    }


    function labReportAdd($billId,$patientId,$dateTime,$adminId){
        try{
            $sql = "INSERT INTO `lab_report` (`bill_id`,`patient_id`,`added_on`,`admin_id`) VALUES ('$billId','$patientId','$dateTime','$adminId')";
            $query = $this->conn->query($sql);
            $labreportId = $this->conn->insert_id;
            // $insertedId = $this->conn->lastInsertId(); // Retrieve the inserted ID
            return ["result" => true,"insert_id" =>  $labreportId];
        }catch(Exception $e){
            echo $e->getMessage();
        }
    }


    function labReportDetailsAdd($testValue, $unitValue, $testId,$reportId)
    {
        try {
            $testValue = $testValue . ' - ' . $unitValue;
            $stmt = $this->conn->prepare("INSERT INTO `lab_report_detail` (`report_id`,`test_value`,`test_id`) VALUES (?,?,?)");

            if ($stmt) {
                $stmt->bind_param("iss", $reportId,$testValue, $testId);

                if ($stmt->execute()) {
                    $stmt->close();
                    return $stmt; // Indicates successful insertion
                } else {
                    throw new Exception("Error executing statement: " . $stmt->error);
                }
            } else {
                throw new Exception("Error preparing statement: " . $this->conn->error);
            }
        } catch (Exception $e) {
            echo $e->getMessage();
            return false; // Indicates failed insertion
        }
    }

    
}
