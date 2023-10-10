 <?php
 ini_set('error_reporting', E_ALL);
 error_reporting(E_ALL);

require_once 'dbconnect.php';

class IdGeneration extends DatabaseConnection{


    function gettingDoctor ($appointmentDate, $patientDoctor){
        $getDoc = "SELECT * FROM appointments WHERE appointment_date =  '$appointmentDate' AND doctor_id = '$patientDoctor' ";
        // echo $getDoc.$this->conn->error;
        // exit;
        $getDocQuery = $this->conn->query($getDoc);
        // echo count($getDocQuery);
        // echo $getDocQuery.$this->conn->error;
        // exit;
        while ($result = $getDocQuery->fetch_array()) {
            $data[] =$result;
        }
        return $data;
    }



}

 ?>