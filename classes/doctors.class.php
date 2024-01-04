<?php
require_once 'dbconnect.php';

class Doctors extends DatabaseConnection
{



    function addDoctor($docRegNo, $docName, $docSpecialization, $docDegree, $alsoWith, $docAddress, $docEmail, $docPhno, $adminId)
    {
        try {
            $insertDoc = "INSERT INTO doctors (`doctor_reg_no`, `doctor_name`, `doctor_specialization`, `doctor_degree`, `also_with`, `doctor_address`, `doctor_email`, `doctor_phno`, `admin_id`) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
            $stmt = $this->conn->prepare($insertDoc);

            if ($stmt) {
                $stmt->bind_param("sssssssss", $docRegNo, $docName, $docSpecialization, $docDegree, $alsoWith, $docAddress, $docEmail, $docPhno, $adminId);

                $insertDocQuery = $stmt->execute();

                $stmt->close();

                return $insertDocQuery;
            } else {
                throw new Exception("Error in preparing SQL statement");
            }
        } catch (Exception $e) {
            throw new Exception($e->getMessage());
        }
    }





    function showDoctors($adminId='')
    {
        try {
            if (!empty($adminId)) {
            $selectDoctors = "SELECT * FROM `doctors` WHERE admin_id = ?";
            $stmt = $this->conn->prepare($selectDoctors);
            $stmt->bind_param("s", $adminId);
            }else{
                $selectDoctors = "SELECT * FROM `doctors`";
                $stmt = $this->conn->prepare($selectDoctors);
            }
            // $stmt = $this->conn->prepare($selectDoctors);

            if ($stmt) {
                // $stmt->bind_param("s", $adminId);

                $stmt->execute();

                $result = $stmt->get_result();

                if ($result->num_rows > 0) {
                    $data = array();
                    while ($row = $result->fetch_assoc()) {
                        $data[] = $row;
                    }
                    return json_encode(['status' => 1, 'message' => 'success', 'data' => $data]);
                } else {
                    return json_encode(['status' => 0, 'message' => '', 'data' => '']);;
                }
                $stmt->close();
            } else {
                throw new Exception("Error in preparing SQL statement");
            }
        } catch (Exception $e) {
            throw new Exception($e->getMessage());
        }
        return 0;
    }






    function showDoctorsForPatient($getDoctorForPatient)
    {
        try {
            $data = [];
            $selectDoctorsForPatient = "SELECT * FROM `doctors` WHERE `doctors`.`doctor_id` = ?";
            $selectDoctorsForPatientQuery = $this->conn->prepare($selectDoctorsForPatient);

            if (!$selectDoctorsForPatientQuery) {
                throw new Exception("Query preparation failed.");
            }

            // Bind parameter
            $selectDoctorsForPatientQuery->bind_param("s", $getDoctorForPatient);

            $selectDoctorsForPatientQuery->execute();

            $result = $selectDoctorsForPatientQuery->get_result();

            if ($result->num_rows > 0) {
                $data = array();
                while ($row = $result->fetch_assoc()) {
                    $data[] = $row;
                }
                return $data;
            } else {
                return null;
            }
        } catch (Exception $e) {
            error_log("Error in showDoctorsForPatient: " . $e->getMessage());
        }
        return 0;
    }






    function showDoctorByCatId($docCatId)
    {
        $selectDocById = "SELECT * FROM `doctors` WHERE `doctors`.`doctor_specialization`='$docCatId'";
        $selectDocByIdQuery = $this->conn->query($selectDocById);
        $rows = $selectDocByIdQuery->num_rows;
        if ($rows > 0) {
            while ($result = $selectDocByIdQuery->fetch_array()) {
                $docCatData[] = $result;
            }
            return $docCatData;
        } else {
            return 0;
        }
    } //end showDoctorByCatId function





    function showDoctorNameById($docId){
        try {
            $selectDocById = "SELECT * FROM `doctors` WHERE `doctors`.`doctor_id`='$docId'";
            $selectDocByIdQuery = $this->conn->query($selectDocById);

            if ($selectDocByIdQuery->num_rows > 0) {
                $docData = array(); // Initialize an array to store data
                while ($result = $selectDocByIdQuery->fetch_object()) {
                    $docData[] = $result; // Append each doctor name to the array
                }
                return json_encode(['status' => '1', 'message' => 'success', 'data' => $docData]);
            } else {
                return json_encode(['status' => '0', 'message' => 'No data found', 'data' => '']);
            }
        } catch (Exception $e) {
            return json_encode(['status' => '0', 'message' => $e->getMessage(), 'data' => '']);
        }
    }




    function doctorsTimingByDoctor($doctorId)
    {
        $selectTiming = "SELECT * FROM `doctor_timing` WHERE `doctor_timing`.`doctor_id` = '$doctorId'";
        $timingQuery = $this->conn->query($selectTiming);
        while ($result = $timingQuery->fetch_array()) {
            $data[] = $result;
        }
        return $data;
    } // end doctorsTimingByDoctor function


    function updateDoc($docRegNo, $docName, $docSplz, $docDegree, $alsoWith, $docAddress, $docEmail, $docPhno, $updateDocId)
    {
        try {
            // Use prepared statements to prevent SQL injection
            $updateDoc = "UPDATE `doctors` SET `doctor_reg_no`= ?, `doctor_name` = ?, `doctor_specialization` = ?, `doctor_degree` = ?, `also_with` = ?, `doctor_address` = ?, `doctor_email` = ?, `doctor_phno` = ? WHERE `doctors`.`doctor_id` = ?";
            $stmt = $this->conn->prepare($updateDoc);

            if ($stmt) {
                // Bind parameters
                $stmt->bind_param("ssssssssi", $docRegNo, $docName, $docSplz, $docDegree, $alsoWith, $docAddress, $docEmail, $docPhno, $updateDocId);

                // Execute the prepared statement
                $updateDocQuery = $stmt->execute();

                // Close the statement
                $stmt->close();

                return $updateDocQuery;
            } else {
                throw new Exception("Error in preparing SQL statement");
            }
        } catch (Exception $e) {
            // Handle any exceptions that may occur
            throw new Exception($e->getMessage());
        }
    }



    function deleteDoc($deleteDocId)
    {
        $deleteDoc = "DELETE FROM `doctors` WHERE `doctors`.`doctor_id` = '$deleteDocId'";
        $deleteDocQuery = $this->conn->query($deleteDoc);
        return $deleteDocQuery;
    } // end deleteDocCat function

}
