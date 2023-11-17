<?php

class HealthCare extends DatabaseConnection
{

    
    function addClinicInfo($clinicId, $adminId, $addedTime){
        try {
            $addClinicData = "INSERT INTO `clinic_info`(`hospital_id`, `admin_id`, `added_on`) VALUES (?, ?, ?)";
            $stmt = $this->conn->prepare($addClinicData);

            if (!$stmt) {
                throw new Exception("Error in preparing statement: " . $this->conn->error);
            }

            $stmt->bind_param("iis", $clinicId, $adminId, $addedTime);

            if (!$stmt->execute()) {
                throw new Exception("Error executing statement: " . $stmt->error);
            }
            return true; 
        } catch (Exception $e) {
            return $e->getMessage(); 
        }
    }






    function showHealthCare($adminId)
    {
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




    // update helthcare details function ------------------

    function updateHealthCare($imgFolder, $healthCareName, $healthCareAddress1, $healthCareAddress2, $healthCareCity, $healthCareDist, $healthCarePin, $healthCareState, $healthCareEmail, $healthCareHelpLineNo, $healthCareApntBookingNo, $adminId){
        try {
            $updateHealthCare = "UPDATE clinic_info SET logo = ?, hospital_name = ?, address_1 = ?, address_2   = ?, city = ?, dist = ?, pin = ?, health_care_state = ?, hospital_email = ?, hospital_phno = ?,   appointment_help_line = ? WHERE admin_id = ?";

            $stmt = $this->conn->prepare($updateHealthCare);

            $stmt->bind_param("ssssssssssss", $imgFolder, $healthCareName, $healthCareAddress1,     $healthCareAddress2, $healthCareCity, $healthCareDist, $healthCarePin, $healthCareState,    $healthCareEmail, $healthCareHelpLineNo, $healthCareApntBookingNo, $adminId);

            $stmt->execute();

            $updateHealthCareQuery = $stmt->affected_rows;

        

            $stmt->close();

            return $updateHealthCareQuery;
        } catch (Exception $e) {
            return $e->getMessage();
        }
        return 0; // sql fail.
    }





    // used in text update
    function updateHealthCareDesc($WhatWeDoText, $appointmentBookText, $subscribeText, $footerText)
    {
        $updateDsc = "UPDATE clinic_info SET main_desc = '$WhatWeDoText', footer_desc = '$footerText', book_appointment_text = '$appointmentBookText', subscribe_text = '$subscribeText'";

        $updateDscQuery = $this->conn->query($updateDsc);
        // echo $updateDscQuery.$this->conn->error;
        // exit;
        return $updateDscQuery;
    } //eof updateHealthCareDesc function



} //Hospital Class end



// $hospital = new Hospital();
// $showHospitalDetails = $hospital-> showHospital();
// // echo count($showHospitalDetails);
// foreach($showHospitalDetails as $hospitalDetails){

//     echo $hospitalDetails['hospital_name'];
// }
