<?php

class Appointments extends DatabaseConnection{

    // ============ apointments book by patients him/her self ==========
    function addAppointments($appointmentId, $patientId, $appointmentDate, $patientName, $patientGurdianNAme, $patientEmail, $patientPhoneNumber, $patientAge, $patientWeight, $gender, $patientAddress1, $patientAddress2, $patientPS, $patientDist, $patientPIN, $patientState, $patientDoctor, $patientDoctorShift){

        $insertApoointments = "INSERT INTO `appointments` (`appointment_id`, `patient_id`, `appointment_date`, `patient_name`, `patient_gurdian_name`, `patient_email`, `patient_phno`, `patient_age`, `patient_weight`, `patient_gender`, `patient_addres1`, `patient_addres2`, `patient_ps`, `patient_dist`, `patient_pin`, `patient_state`, `doctor_id`, `patient_doc_shift`) VALUES ('$appointmentId', $patientId, '$appointmentDate', '$patientName', '$patientGurdianNAme', '$patientEmail', '$patientPhoneNumber', '$patientAge', '$patientWeight', '$gender', '$patientAddress1', '$patientAddress2', '$patientPS', '$patientDist', '$patientPIN', '$patientState', '$patientDoctor', '$patientDoctorShift')";

        $insertQuery = $this->conn->query($insertApoointments);

        return $insertQuery;

    }// end addAppointments function


    // ======== booked by admin or employeee ==============
    function addFromInternal($appointmentId, $patientId, $appointmentDate, $patientName, $patientGurdianName, $patientEmail, $patientPhoneNumber, $patientAge, $patientWeight, $gender, $patientAddress1, $patientAddress2, $patientPS, $patientDist, $patientPIN, $patientState, $patientDoctor, $addedBy, $addedOn, $adminId) {
        try {
            $insertAppointments = "INSERT INTO `appointments` (`appointment_id`, `patient_id`, `appointment_date`, `patient_name`, `patient_gurdian_name`, `patient_email`, `patient_phno`, `patient_age`, `patient_weight`, `patient_gender`, `patient_addres1`, `patient_addres2`, `patient_ps`, `patient_dist`, `patient_pin`, `patient_state`, `doctor_id`, `added_by`, `added_on`, `admin_id`) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
    
            $stmt = $this->conn->prepare($insertAppointments);
    
            if ($stmt) {
                $stmt->bind_param("ssssssssisssssssssss", $appointmentId, $patientId, $appointmentDate, $patientName, $patientGurdianName, $patientEmail, $patientPhoneNumber, $patientAge, $patientWeight, $gender, $patientAddress1, $patientAddress2, $patientPS, $patientDist, $patientPIN, $patientState, $patientDoctor, $addedBy, $addedOn, $adminId);
    
                if ($stmt->execute()) {
                    return true; // Success
                } else {
                    return false; // Failed to execute the query
                }
    
                $stmt->close();
            } else {
                return false; // Statement preparation failed
            }
        } catch (Exception $e) {
            echo $e;
        }
    }
    // end addAppointments function



 
    function appointmentsDisplay($adminId) {
        $data = array();
    
        try {
            $stmt = $this->conn->prepare("SELECT * FROM appointments WHERE admin_id = ? ORDER BY id DESC");
        
            if ($stmt) {
                $stmt->bind_param("s", $adminId);

                $stmt->execute();
                $result = $stmt->get_result();
                 
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_object()) {
                        $data[] = $row;
                    }
                    $stmt->close();
                    return json_encode(['status' => 1, 'message' => 'success', 'data' => $data]);
                } else {
                    return json_encode(['status' => 0, 'message' => '', 'data' => '']);
                }
            } else {
                throw new Exception("Error statement preparation: $stmt->error");
            }

        } catch (Exception $e) {
            error_log("Error in appointmentsDisplay: " . $e->getMessage());
        }
    
        return 0;
    }


    function filterAppointments($filterBy, $column, $adminId) {
        $data = array();

        if ($column == 'search') {
            # code...
        }
        try {
            // Create a prepared statement
            $stmt = $this->conn->prepare("SELECT * FROM appointments WHERE admin_id = ? ORDER BY id DESC");
        
            if ($stmt) {
                // Bind the parameter (adminId) to the statement
                $stmt->bind_param("s", $adminId);
                
                // Execute the statement
                if ($stmt->execute()) {
                    $result = $stmt->get_result();
                    
                    while ($row = $result->fetch_assoc()) {
                        $data[] = $row;
                    }
                    
                    $stmt->close(); // Close the statement
                } else {
                    // Handle query execution error here, if needed
                    throw new Exception("Error execution query: $stmt->error");
                    
                }
            } else {
                // Handle statement preparation error here, if needed
                throw new Exception("Error statement preparation: $stmt->error");
            }
        } catch (Exception $e) {
            error_log("Error in appointmentsDisplay: " . $e->getMessage());
        }
    
        return $data;
    }
        




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






    function updateAppointmentsbyTableId($appointmentDate,$patientName,$patientGurdianName,$patientEmail,$patientPhoneNumber,$patientAge,$patientWeight,$gender,$patientAddress1,$patientAddress2,$patientPS,$patientDist,$patientPIN,$patientState,$patientDoctor,$patientDoctorTiming, /*Last Parameter For Appointment Id Which Details You Want to Update*/$appointmentTableId){

        $updateById = "UPDATE  `appointments` SET `appointment_date` = '$appointmentDate', `patient_name` = '$patientName', `patient_gurdian_name` = '$patientGurdianName', `patient_email`= '$patientEmail', `patient_phno` = '$patientPhoneNumber', `patient_age` = '$patientAge', `patient_weight` = '$patientWeight', `patient_gender` = '$gender', `patient_addres1` = '$patientAddress1', `patient_addres2` = '$patientAddress2', `patient_ps` = '$patientPS', `patient_dist` = '$patientDist',`patient_pin` = '$patientPIN', `patient_state` = '$patientState', `doctor_id` = '$patientDoctor', `patient_doc_shift` = '$patientDoctorTiming' WHERE `appointments`.`id` = '$appointmentTableId'";

        // echo $updateById.$this->conn->error;
        // exit;

        $updatedByIdQuery = $this->conn->query($updateById);
        
        return $updatedByIdQuery;
    }// end updateAppointmentsbyId function






    function updateAppointmentsbyId($appointmentDate,$patientName,$patientGurdianNAme,$patientEmail,$patientPhoneNumber,$patientAge,$patientWeight,$gender,$patientAddress1,$patientAddress2,$patientPS,$patientDist,$patientPIN,$patientState,$patientDoctor,$patientDoctorTiming, /*Last Parameter For Appointment Id Which Details You Want to Update*/$appointmentID){

        $updateById = "UPDATE  `appointments` SET `appointment_date` = '$appointmentDate', `patient_name` = '$patientName', `patient_gurdian_name` = '$patientGurdianNAme', `patient_email`= '$patientEmail', `patient_phno` = '$patientPhoneNumber', `patient_age` = '$patientAge', `patient_weight` = '$patientWeight', `patient_gender` = '$gender', `patient_addres1` = '$patientAddress1', `patient_addres2` = '$patientAddress2', `patient_ps` = '$patientPS', `patient_dist` = '$patientDist',`patient_pin` = '$patientPIN', `patient_state` = '$patientState', `doctor_id` = '$patientDoctor', `patient_doc_shift` = '$patientDoctorTiming' WHERE `appointments`.`appointment_id` = '$appointmentID'";

        $updatedByIdQuery = $this->conn->query($updateById);

        return $updatedByIdQuery;

    }// end updateAppointmentsbyId function

    
    // //  start appointment entry function
    // function appointmententry($appointmentId, $appointmentDate, $patientName, $patientGurdianNAme, $patientEmail, $patientPhoneNumber, $patientAge, $patientWeight, $gender, $patientAddress1, $patientAddress2, $patientPS, $patientDist, $patientPIN, $patientState, $patientDoctor){

    //     $insertApoointments = "INSERT INTO `appointments` (`appointment_id`, `appointment_date`, `patient_name`, `patient_gurdian_name`, `patient_email`, `patient_phno`, `patient_age`, `patient_weight`, `patient_gender`, `patient_addres1`, `patient_addres2`, `patient_ps`, `patient_dist`, `patient_pin`, `patient_state`, `doctor_id`) VALUES ('$appointmentId', '$appointmentDate', '$patientName', '$patientGurdianNAme', '$patientEmail', '$patientPhoneNumber', '$patientAge', '$patientWeight', '$gender', '$patientAddress1', '$patientAddress2', '$patientPS', '$patientDist', '$patientPIN', '$patientState', '$patientDoctor' )";
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