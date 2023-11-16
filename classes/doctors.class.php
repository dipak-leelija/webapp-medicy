<?php
require_once 'dbconnect.php';

class Doctors extends DatabaseConnection{



    function addDoctor($docRegNo, $docName, $docSpecialization, $docDegree, $alsoWith, $docAddress, $docEmail, $docPhno, $adminId) {
        try {
            // Use prepared statements to prevent SQL injection
            $insertDoc = "INSERT INTO doctors (`doctor_reg_no`, `doctor_name`, `doctor_specialization`, `doctor_degree`, `also_with`, `doctor_address`, `doctor_email`, `doctor_phno`, `admin_id`) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
            $stmt = $this->conn->prepare($insertDoc);
    
            if ($stmt) {
                // Bind parameters
                $stmt->bind_param("sssssssss", $docRegNo, $docName, $docSpecialization, $docDegree, $alsoWith, $docAddress, $docEmail, $docPhno, $adminId);
    
                // Execute the prepared statement
                $insertDocQuery = $stmt->execute();
    
                // Close the statement
                $stmt->close();
    
                return $insertDocQuery;
            } else {
                throw new Exception("Error in preparing SQL statement");
            }
        } catch (Exception $e) {
            // Handle any exceptions that may occur
            throw new Exception($e->getMessage());
        }
    }
    



    function showDoctors($adminId) {
        $data = array();
    
        try {
            // Use prepared statements to prevent SQL injection
            $selectDoctors = "SELECT * FROM `doctors` WHERE admin_id = ?";
            $stmt = $this->conn->prepare($selectDoctors);
    
            if ($stmt) {
                // Bind parameter
                $stmt->bind_param("s", $adminId);
    
                // Execute the prepared statement
                $stmt->execute();
    
                // Get the result set
                $result = $stmt->get_result();
    
                // Check if data exists before fetching
                if ($result->num_rows > 0) {
                    // Fetch results into an array
                    while ($row = $result->fetch_assoc()) {
                        $data[] = $row;
                    }
                }
    
                // Close the statement
                $stmt->close();
            } else {
                throw new Exception("Error in preparing SQL statement");
            }
        } catch (Exception $e) {
            // Handle any exceptions that may occur
            throw new Exception($e->getMessage());
        }
    
        return $data;
    }
    


 
    function showDoctorsForPatient($getDoctorForPatient){
        $data = array();
        $selectDoctorsForPatient = "SELECT * FROM `doctors` WHERE `doctors`.`doctor_id`  = '$getDoctorForPatient'";
        $selectDoctorsForPatientQuery = $this->conn->query($selectDoctorsForPatient);
        while($result = $selectDoctorsForPatientQuery->fetch_array()){
            $data[] = $result;
        }
        return $data;
    }// end showDoctorsbyId function




    function showDoctorByCatId($docCatId){
        $selectDocById = "SELECT * FROM `doctors` WHERE `doctors`.`doctor_specialization`='$docCatId'";
        $selectDocByIdQuery = $this->conn->query($selectDocById);
        $rows = $selectDocByIdQuery->num_rows;
        if($rows > 0 ){
            while ($result = $selectDocByIdQuery->fetch_array()) {
                $docCatData[] = $result;
            }
            return $docCatData;
        }else{
            return 0;
        }
        
        
    }//end showDoctorByCatId function




    
    function showDoctorById($docId){
        $selectDocById = "SELECT * FROM `doctors` WHERE `doctors`.`doctor_id`='$docId'";
        $selectDocByIdQuery = $this->conn->query($selectDocById);
        $rows = $selectDocByIdQuery->num_rows;
        if($rows > 0){
            while ($result = $selectDocByIdQuery->fetch_array()) {
                $docData[] = $result;
            }
            return $docData;
        }else{
            return 0;
        }
        
    }//end showDoctorByCatId function



    function doctorsTimingByDoctor($doctorId){
        $selectTiming = "SELECT * FROM `doctor_timing` WHERE `doctor_timing`.`doctor_id` = '$doctorId'";
        $timingQuery = $this->conn->query($selectTiming);
        while($result = $timingQuery->fetch_array()){
            $data[] = $result;
        }
        return $data;
    }// end doctorsTimingByDoctor function


    function updateDoc($docRegNo, $docName, $docSplz, $docDegree, $alsoWith, $docAddress, $docEmail, $docPhno,/*Last Variable for id which data we wants to update*/$updateDocId){
        $updateDoc = "UPDATE `doctors` SET `doctor_reg_no`= '$docRegNo', `doctor_name` = '$docName', `doctor_specialization` = '$docSplz', `doctor_degree` = '$docDegree', `also_with` = '$alsoWith', `doctor_address` = '$docAddress', `doctor_email` = '$docEmail', `doctor_phno` = '$docPhno' WHERE `doctors`.`doctor_id` = '$updateDocId'";
 
        $updateDocQuery = $this->conn->query($updateDoc);
        // echo $updateDocQuery.$this->conn->error;
        // exit;
        return $updateDocQuery;
    }//end updateDoc function


    function deleteDoc($deleteDocId){
        $deleteDoc = "DELETE FROM `doctors` WHERE `doctors`.`doctor_id` = '$deleteDocId'";
        $deleteDocQuery = $this->conn->query($deleteDoc);
        return $deleteDocQuery;
    }// end deleteDocCat function

}

?>