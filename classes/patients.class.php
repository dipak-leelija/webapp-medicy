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


    
    function allPatients($admin = ''){
        $data = array();
    
        try {
            if (empty($admin)) {
                $query = "SELECT * FROM patient_details ORDER BY id DESC";
                $res = $this->conn->prepare($query);
            } else {
                $query = "SELECT * FROM patient_details WHERE admin_id = ? ORDER BY id DESC";
                $res = $this->conn->prepare($query);
                $res->bind_param("s", $admin); // Assuming admin_id is an integer
            }
    
            if ($res->execute()) {
                $result = $res->get_result();
                while ($row = $result->fetch_object()) {
                    $data[] = $row;
                }
            } else {
                throw new Exception("Query execution failed.");
            }
        } catch (Exception $e) {
            // Handle the error (e.g., log the error or return an error message)
            error_log("Error in allPatients: " . $e->getMessage());
            return array("error" => "An error occurred while fetching patient data.");
        }
    
        return json_encode($data);
    }
    
    



    function patientsDisplayById($patientId){
        $data = array();
        $selectById = "SELECT * FROM patient_details WHERE `id`= '$patientId'";
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