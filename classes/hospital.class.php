<?php

class HealthCare extends DatabaseConnection{

    function addClinicInfo($clinicId, $adminId, $addedTime){

        $addClinicData = "INSERT INTO `clinic_info`(`hospital_id`, `admin_id`, `added_on`) VALUES ('$clinicId','$adminId','$addedTime')";
        $query = $this->conn->query($addClinicData);
        return $query;

    } //showHospital function end





    function showHealthCare($adminId) {
        $response = array();
    
        try {
            $sql = "SELECT * FROM clinic_info WHERE `admin_id` = ?";
            $stmt = $this->conn->prepare($sql);
    
            if ($stmt) {
                $stmt->bind_param("s", $adminId);
    
                if ($stmt->execute()) {
                    $result = $stmt->get_result();

                    if ($result->num_rows > 0) {
                        // Fetch all rows into an array
                        $row = $result->fetch_assoc();
                        $response = $row;
                        
                        return $response;
                    } else {
                        return $response;
                    }
                } else {
                    return $response; // Return null if the query execution fails
                }
            } else {
                throw new Exception("Error in preparing SQL statement");
            }
    
        } catch (Exception $e) {
            // Handle any exceptions that may occur
            throw new Exception($e->getMessage());
        }
    }
    
    

    function updateHealthCare($imgFolder, $healthCareName, $healthCareAddress1, $healthCareAddress2, $healthCareCity, $healthCareDist, $healthCarePin, $healthCareState, $healthCareEmail, $healthCareHelpLineNo, $healthCareApntBookingNo){

        $updateHealthCare = "UPDATE  clinic_info SET logo = '$imgFolder', hospital_name = '$healthCareName', address_1 = '$healthCareAddress1', address_2 = '$healthCareAddress2', city = '$healthCareCity', dist = '$healthCareDist', pin = '$healthCarePin', health_care_state = '$healthCareState', hospital_email = '$healthCareEmail', hospital_phno = '$healthCareHelpLineNo', appointment_help_line = '$healthCareApntBookingNo'";

        // echo $updateHealthCare.$this->conn->error;
        // exit;
        $updateHealthCareQuery = $this->conn->query($updateHealthCare);
        // echo $updateHealthCareQuery.$this->conn->error;
        // exit;
        return $updateHealthCareQuery;
        
    }// end updateAppointmentsbyId function



        // used in text update
        function updateHealthCareDesc($WhatWeDoText, $appointmentBookText, $subscribeText, $footerText){
            $updateDsc = "UPDATE clinic_info SET main_desc = '$WhatWeDoText', footer_desc = '$footerText', book_appointment_text = '$appointmentBookText', subscribe_text = '$subscribeText'";
            
            $updateDscQuery = $this->conn->query($updateDsc);
            // echo $updateDscQuery.$this->conn->error;
            // exit;
            return $updateDscQuery;
        }//eof updateHealthCareDesc function



}//Hospital Class end



// $hospital = new Hospital();
// $showHospitalDetails = $hospital-> showHospital();
// // echo count($showHospitalDetails);
// foreach($showHospitalDetails as $hospitalDetails){

//     echo $hospitalDetails['hospital_name'];
// }








?>