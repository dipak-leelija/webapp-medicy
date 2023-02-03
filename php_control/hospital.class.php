<?php
require_once 'dbconnect.php';



class HelthCare extends DatabaseConnection{


    function showhelthCare(){

        $selectHospital = "SELECT * FROM hospital_info";
        $query = $this->conn->query($selectHospital);
        while($result = $query->fetch_array()){
            $hospitalData[] = $result;
        }
        return $hospitalData;

    } //showHospital function end
 


    function updateHealthCare($imgFolder, $healthCareName, $healthCareAddress1, $healthCareAddress2, $healthCareCity, $healthCareDist, $healthCarePin, $healthCareState, $healthCareEmail, $healthCareHelpLineNo, $healthCareApntBookingNo){

        $updateHealthCare = "UPDATE  hospital_info SET logo = '$imgFolder', hospital_name = '$healthCareName', address_1 = '$healthCareAddress1', address_2 = '$healthCareAddress2', city = '$healthCareCity', dist = '$healthCareDist', pin = '$healthCarePin', health_care_state = '$healthCareState', hospital_email = '$healthCareEmail', hospital_phno = '$healthCareHelpLineNo', appointment_help_line = '$healthCareApntBookingNo'";

        // echo $updateHealthCare.$this->conn->error;
        // exit;
        $updateHealthCareQuery = $this->conn->query($updateHealthCare);
        // echo $updateHealthCareQuery.$this->conn->error;
        // exit;
        return $updateHealthCareQuery;
        
    }// end updateAppointmentsbyId function



        // used in text update
        function updateHealthCareDesc($WhatWeDoText, $appointmentBookText, $subscribeText, $footerText){
            $updateDsc = "UPDATE hospital_info SET main_desc = '$WhatWeDoText', footer_desc = '$footerText', book_appointment_text = '$appointmentBookText', subscribe_text = '$subscribeText'";
            
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