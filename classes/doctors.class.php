<?php
require_once 'dbconnect.php';

class Doctors extends DatabaseConnection{



    //used to add doctors
    function addDoctor($docRegNo, $docName, $docSpecialization, $docDegree, $alsoWith, $docAddress, $docEmail, $docPhno){
        $insertDoc = "INSERT INTO doctors (`doctor_reg_no`, `doctor_name`, `doctor_specialization`, `doctor_degree`, `also_with`, `doctor_address`, `doctor_email`, `doctor_phno`) VALUES ('$docRegNo', '$docName', '$docSpecialization', '$docDegree', '$alsoWith', '$docAddress', '$docEmail', '$docPhno')";
        $insertDocQuery = $this->conn->query($insertDoc);
        // echo $insertDocQuery.$this->conn->error;
        // exit;
        return $insertDocQuery;
    }//end addDoctor function



    function showDoctors(){
        $selectDoctors = "SELECT * FROM `doctors`";
        $doctorsQuery = $this->conn->query($selectDoctors);
        while($result = $doctorsQuery->fetch_array()){
            $data[] = $result;
        }
        return $data;
    }// end showDoctors function


 
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