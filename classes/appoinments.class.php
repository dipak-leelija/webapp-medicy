<?php
ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);

require_once 'dbconnect.php';



class Appointments extends DatabaseConnection{



    function addAppointments($appointmentId, $patientId, $appointmentDate, $patientName, $patientGurdianNAme, $patientEmail, $patientPhoneNumber, $patientDOB, $patientWeight, $gender, $patientAddress1, $patientAddress2, $patientPS, $patientDist, $patientPIN, $patientState, $patientDoctor, $patientDoctorShift){

        $insertApoointments = "INSERT INTO `appointments` (`appointment_id`, `patient_id`, `appointment_date`, `patient_name`, `patient_gurdian_name`, `patient_email`, `patient_phno`, `patient_dob`, `patient_weight`, `patient_gender`, `patient_addres1`, `patient_addres2`, `patient_ps`, `patient_dist`, `patient_pin`, `patient_state`, `doctor_id`, `patient_doc_shift`) VALUES ('$appointmentId', $patientId, '$appointmentDate', '$patientName', '$patientGurdianNAme', '$patientEmail', '$patientPhoneNumber', '$patientDOB', '$patientWeight', '$gender', '$patientAddress1', '$patientAddress2', '$patientPS', '$patientDist', '$patientPIN', '$patientState', '$patientDoctor', '$patientDoctorShift')";

        $insertQuery = $this->conn->query($insertApoointments);

        return $insertQuery;

    }// end addAppointments function



    function addFromInternal($appointmentId, $patientId, $appointmentDate, $patientName, $patientGurdianName, $patientEmail, $patientPhoneNumber, $patientAge, $patientWeight, $gender, $patientAddress1, $patientAddress2, $patientPS, $patientDist, $patientPIN, $patientState, $patientDoctor){

        $insertApoointments = "INSERT INTO `appointments` (`appointment_id`, `patient_id`, `appointment_date`, `patient_name`, `patient_gurdian_name`, `patient_email`, `patient_phno`, `patient_dob`, `patient_weight`, `patient_gender`, `patient_addres1`, `patient_addres2`, `patient_ps`, `patient_dist`, `patient_pin`, `patient_state`, `doctor_id`) VALUES ('$appointmentId', '$patientId', '$appointmentDate', '$patientName', '$patientGurdianName', '$patientEmail', '$patientPhoneNumber', '$patientAge', '$patientWeight', '$gender', '$patientAddress1', '$patientAddress2', '$patientPS', '$patientDist', '$patientPIN', '$patientState', '$patientDoctor')";

        // echo $insertApoointments.$this->conn->error;exit;

        $insertQuery = $this->conn->query($insertApoointments);
        // echo var_dump($insertQuery);
        // exit;

        return $insertQuery;

    }// end addAppointments function



 
    function appointmentsDisplay(){

        $select = "SELECT * FROM appointments";
        $selectQuery = $this->conn->query($select);
        while($result = $selectQuery->fetch_array()){
            $data[]	= $result;
        }
        return $data;

    }//end appointmentsDisplay function




    function appointmentsDisplayOfLastMonth(){

        $selectLastMonth = "SELECT * FROM appointments WHERE YEAR(appointment_date) = YEAR(CURRENT_DATE - INTERVAL 1 MONTH) AND MONTH(appointment_date) = MONTH(CURRENT_DATE - INTERVAL 1 MONTH)";
        $selectLastMonthQuery = $this->conn->query($selectLastMonth);
        while($result = $selectLastMonthQuery->fetch_array()){
            $data[]	= $result;
        }

        return $data;

    }//end appointmentsDisplay function




    function appointmentsDisplaybyTableId($appointmentTableId){
        $selectById = "SELECT * FROM appointments WHERE `appointments`.`id` = '$appointmentTableId'";
        $selectByIdQuery = $this->conn->query($selectById);
        while($result = $selectByIdQuery->fetch_array()){
            $data[]	= $result;
        }
        return $data;
    }//end appointmentsDisplaybyId function
    
    


    function appointmentsDisplaybyId($appointmentId){

        $selectById = "SELECT * FROM appointments WHERE `appointments`.`appointment_id` = '$appointmentId'";
        $selectByIdQuery = $this->conn->query($selectById);
        while($result = $selectByIdQuery->fetch_array()){
            $data[]	= $result;
        }

        return $data;

    }//end appointmentsDisplaybyId function






    function updateAppointmentsbyTableId($appointmentDate,$patientName,$patientGurdianName,$patientEmail,$patientPhoneNumber,$patientDOB,$patientWeight,$gender,$patientAddress1,$patientAddress2,$patientPS,$patientDist,$patientPIN,$patientState,$patientDoctor,$patientDoctorTiming, /*Last Parameter For Appointment Id Which Details You Want to Update*/$appointmentTableId){

        $updateById = "UPDATE  `appointments` SET `appointment_date` = '$appointmentDate', `patient_name` = '$patientName', `patient_gurdian_name` = '$patientGurdianName', `patient_email`= '$patientEmail', `patient_phno` = '$patientPhoneNumber', `patient_dob` = '$patientDOB', `patient_weight` = '$patientWeight', `patient_gender` = '$gender', `patient_addres1` = '$patientAddress1', `patient_addres2` = '$patientAddress2', `patient_ps` = '$patientPS', `patient_dist` = '$patientDist',`patient_pin` = '$patientPIN', `patient_state` = '$patientState', `doctor_id` = '$patientDoctor', `patient_doc_shift` = '$patientDoctorTiming' WHERE `appointments`.`id` = '$appointmentTableId'";

        // echo $updateById.$this->conn->error;
        // exit;

        $updatedByIdQuery = $this->conn->query($updateById);
        
        return $updatedByIdQuery;
    }// end updateAppointmentsbyId function






    function updateAppointmentsbyId($appointmentDate,$patientName,$patientGurdianNAme,$patientEmail,$patientPhoneNumber,$patientDOB,$patientWeight,$gender,$patientAddress1,$patientAddress2,$patientPS,$patientDist,$patientPIN,$patientState,$patientDoctor,$patientDoctorTiming, /*Last Parameter For Appointment Id Which Details You Want to Update*/$appointmentID){

        $updateById = "UPDATE  `appointments` SET `appointment_date` = '$appointmentDate', `patient_name` = '$patientName', `patient_gurdian_name` = '$patientGurdianNAme', `patient_email`= '$patientEmail', `patient_phno` = '$patientPhoneNumber', `patient_dob` = '$patientDOB', `patient_weight` = '$patientWeight', `patient_gender` = '$gender', `patient_addres1` = '$patientAddress1', `patient_addres2` = '$patientAddress2', `patient_ps` = '$patientPS', `patient_dist` = '$patientDist',`patient_pin` = '$patientPIN', `patient_state` = '$patientState', `doctor_id` = '$patientDoctor', `patient_doc_shift` = '$patientDoctorTiming' WHERE `appointments`.`appointment_id` = '$appointmentID'";

        $updatedByIdQuery = $this->conn->query($updateById);

        return $updatedByIdQuery;

    }// end updateAppointmentsbyId function

    
    // //  start appointment entry function
    // function appointmententry($appointmentId, $appointmentDate, $patientName, $patientGurdianNAme, $patientEmail, $patientPhoneNumber, $patientDOB, $patientWeight, $gender, $patientAddress1, $patientAddress2, $patientPS, $patientDist, $patientPIN, $patientState, $patientDoctor){

    //     $insertApoointments = "INSERT INTO `appointments` (`appointment_id`, `appointment_date`, `patient_name`, `patient_gurdian_name`, `patient_email`, `patient_phno`, `patient_dob`, `patient_weight`, `patient_gender`, `patient_addres1`, `patient_addres2`, `patient_ps`, `patient_dist`, `patient_pin`, `patient_state`, `doctor_id`) VALUES ('$appointmentId', '$appointmentDate', '$patientName', '$patientGurdianNAme', '$patientEmail', '$patientPhoneNumber', '$patientDOB', '$patientWeight', '$gender', '$patientAddress1', '$patientAddress2', '$patientPS', '$patientDist', '$patientPIN', '$patientState', '$patientDoctor' )";
    //     $insertQuery = $this->conn->query($insertApoointments);

    //     return $insertQuery;
    // }// end addAppointments function




    function deleteAppointmentsById($appointmentId){

        $appointmentDelete = "DELETE FROM `appointments` WHERE `appointments`.`appointment_id` = '$appointmentId'";
        $DeleteQuey = $this->conn->query($appointmentDelete);
        return $DeleteQuey;

    }//end deleteAppointmentsById function






}//end class



?>