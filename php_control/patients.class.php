<?php

require_once 'dbconnect.php';





class Patients extends DatabaseConnection{




    function addPatients($patientId, $patientName, $patientGurdianName, $patientEmail, $patientPhoneNumber, $patientAge, $gender, $patientAddress1, $patientAddress2, $patientPS, $patientDist, $patientPIN, $patientState, $visited){

        $insertPatients = "INSERT INTO `patient_details` (`patient_id`, `name`, `gurdian_name`, `email`, `phno`, `age`, `gender`, `address_1`, `address_2`, `patient_ps`, `patient_dist`, `patient_pin`, `patient_state`, `visited`) VALUES
        ('$patientId', '$patientName', '$patientGurdianName', '$patientEmail', '$patientPhoneNumber', '$patientAge', '$gender', '$patientAddress1', '$patientAddress2', '$patientPS', '$patientDist', '$patientPIN', '$patientState', $visited)";

        $insertQuery = $this->conn->query($insertPatients);

        return $insertQuery;

    }// end addPatients function




    function updatePatientsVisitingTime($patientId, $patientEmail, $patientPhoneNumber, $patientAge, $visited){

        $insertPatients = " UPDATE `patient_details` SET `email` = '$patientEmail', `phno` = '$patientPhoneNumber', `age` = '$patientAge', `visited` = '$visited' WHERE `patient_details`.`patient_id` = '$patientId'";

        $insertQuery = $this->conn->query($insertPatients);

        return $insertQuery;

    }// end updatePatientsVisitingTime function





    function addLabPatients($patientId, $patientName, $patientGurdianName, $patientEmail, $patientPhoneNumber, $patientAge, $gender, $patientAddress1, $patientAddress2, $patientPS, $patientDist, $patientPIN, $patientState){

        $insertPatients = "INSERT INTO `patient_details` (`patient_id`, `name`, `gurdian_name`, `email`, `phno`, `age`, `gender`, `address_1`, `address_2`, `patient_ps`, `patient_dist`, `patient_pin`, `patient_state`) VALUES
        ('$patientId', '$patientName', '$patientGurdianName', '$patientEmail', '$patientPhoneNumber', '$patientAge', '$gender', '$patientAddress1', '$patientAddress2', '$patientPS', '$patientDist', '$patientPIN', '$patientState')";

        $insertQuery = $this->conn->query($insertPatients);

        return $insertQuery;

    }// end addPatients function





    function updateLabVisiting($patientId, $Labvisited){

        $insertPatients = " UPDATE `patient_details` SET `lab_visited` = '$Labvisited' WHERE `patient_details`.`patient_id` = '$patientId'";

        $insertQuery = $this->conn->query($insertPatients);

        return $insertQuery;

    }// end updatePatientsVisitingTime function





    function patientsDisplay(){

        $select = "SELECT * FROM patient_details";
        $selectQuery = $this->conn->query($select);
        while($result = $selectQuery->fetch_array()){
            $data[]	= $result;
        }
        return $data;

    }//end appointmentsDisplay function


 



    function patientsDisplayById($patientId){
        $data = array();
        $selectById = "SELECT * FROM patient_details WHERE `patient_details`.`id`= '$patientId'";
        $selectByIdQuery = $this->conn->query($selectById);
        // echo var_dump($selectByIdQuery);
        while($result = $selectByIdQuery->fetch_array()){
            $data[]	= $result;
        }
        return $data;

    }//end appointmentsDisplay function


    function patientsDisplayByPId($patientId){

        $selectByPId = "SELECT * FROM patient_details WHERE `patient_details`.`patient_id`= '$patientId'";
        $selectByPIdQuery = $this->conn->query($selectByPId);
        $rows = $selectByPIdQuery->num_rows;
        if($rows == 0 ){
            return 0;
        }else{
            while($result = $selectByPIdQuery->fetch_array()){
                $data[]	= $result;
            }
            return $data;
        }

    }//end appointmentsDisplay function


    
    



}//end class



?>