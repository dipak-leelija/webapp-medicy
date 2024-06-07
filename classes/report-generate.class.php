<?php
require_once 'dbconnect.php';
class LabReport
{
    use DatabaseConnection;

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
            $data = null;
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


    function labReportAdd($billId, $patientId, $dateTime, $adminId)
    {
        try {
            $sql = "INSERT INTO `lab_report` (`bill_id`,`patient_id`,`added_on`,`admin_id`) VALUES ('$billId','$patientId','$dateTime','$adminId')";
            $query = $this->conn->query($sql);
            $labreportId = $this->conn->insert_id;
            // $insertedId = $this->conn->lastInsertId(); // Retrieve the inserted ID
            return ["result" => true, "insert_id" =>  $labreportId];
        } catch (Exception $e) {
            echo $e->getMessage();
        }
    }
    /// LabReport data fetch by id///
    function labReportShow($billId)
    {
        try {
            $datas = null;
            $sql = "SELECT * FROM `lab_report` where `bill_id`='$billId'";
            $query = $this->conn->query($sql);
            while ($result = $query->fetch_object()) {
                $datas = $result;
            }
            $dataset = json_encode($datas);
            return $dataset;
        } catch (Exception $e) {
            echo $e->getMessage();
        }
    }
    
    function labReportbyReportId($reportId)
    {
        try {
            $datas = null;
            $sql = "SELECT * FROM `lab_report` where `id`='$reportId'";
            $query = $this->conn->query($sql);
            while ($result = $query->fetch_object()) {
                $datas = $result;
            }
            $dataset = json_encode($datas);
            return $dataset;
        } catch (Exception $e) {
            echo $e->getMessage();
        }
    }



    // LabReport fetch //
    function labreportfetch($adminId = "")
    {
        try {
            $datas = array();
            if (!empty($adminId)) {
                $sql = "SELECT * FROM `lab_report` WHERE `admin_id` = '$adminId'";
            } else {
                $sql = "SELECT * FROM `lab_report`";
            }
            $query = $this->conn->query($sql);
            while ($result = $query->fetch_object()) {
                $datas[] = $result;
            }
            $dataset = json_encode($datas);
            return $dataset;
        } catch (Exception $e) {
            echo $e->getMessage();
        }
    }



    function patientDatafetchByPatientAndAdmin($patientId, $adminId)
    {
        try {
            $data = [];
            $sql = "SELECT * FROM `lab_report` WHERE `patient_id` = '$patientId' AND `admin_id`='$adminId'";
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




    ///insert lab report details ///
    function labReportDetailsAdd($testValue, $unitValue, $testId, $reportId)
    {
        try {
            $testValue = $testValue . ' - ' . $unitValue;
            $stmt = $this->conn->prepare("INSERT INTO `lab_report_detail` (`report_id`,`test_value`,`test_id`) VALUES (?,?,?)");

            if ($stmt) {
                $stmt->bind_param("iss", $reportId, $testValue, $testId);

                if ($stmt->execute()) {
                    $stmt->close();
                    return true; // Indicates successful insertion
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

    ///lab report data fetch by Id ///
    function labReportDetailbyId($reportId)
    {
        try {
            $datas = array();
            $sql = "SELECT * FROM `lab_report_detail` where `report_id`= '$reportId'";
            $query = $this->conn->query($sql);
            while ($result = $query->fetch_object()) {
                $datas[] = $result;
            }
            $dataset = json_encode($datas);
            return $dataset;
        } catch (Exception $e) {
            echo $e->getMessage();
        }
    }

    ///lab report data fetch///
    function labReportDetail()
    {
        try {
            $datas = array();
            $sql = "SELECT * FROM `lab_report_detail`";
            $query = $this->conn->query($sql);
            while ($result = $query->fetch_object()) {
                $datas[] = $result;
            }
            $dataset = json_encode($datas);
            return $dataset;
        } catch (Exception $e) {
            echo $e->getMessage();
        }
    }
}
