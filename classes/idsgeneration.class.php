<?php
require_once 'dbconnect.php';
class IdsGeneration extends DatabaseConnection
{


    function patientidGenerate()
    {

        $select = "SELECT * FROM patient_details";
        $selectQuery = $this->conn->query($select);
        $data = [];
        while ($result = $selectQuery->fetch_array()) {
            $data[]    = $result;
        }
        $sl = count($data) + 1;
        if ($sl < 10) {
            $sl = "000000000$sl";
        } elseif ($sl >= 10 && $sl < 100) {
            $sl = "00000000$sl";
        } elseif ($sl >= 100 && $sl < 1000) {
            $sl = "0000000$sl";
        } elseif ($sl >= 1000 && $sl < 10000) {
            $sl = "000000$sl";
        } elseif ($sl >= 10000 && $sl < 100000) {
            $sl = "00000$sl";
        } elseif ($sl >= 100000 && $sl < 1000000) {
            $sl = "0000$sl";
        } elseif ($sl >= 1000000 && $sl < 10000000) {
            $sl = "000$sl";
        } elseif ($sl >= 10000000 && $sl < 100000000) {
            $sl = "00$sl";
        } elseif ($sl >= 100000000 && $sl < 1000000000) {
            $sl = "0$sl";
        } else {
            $sl = $sl;
        }
        $alph = 'A';
        $patientId = "PE$alph$sl";
        return $patientId;
    }



    function getAppointmentIds()
    {
        $data = array(); // Initialize the array

        try {
            $select = "SELECT appointment_id FROM appointments ORDER BY added_on ASC";
            $stmt = $this->conn->prepare($select);

            if ($stmt) {
                $stmt->execute();
                $result = $stmt->get_result();

                while ($row = $result->fetch_array()) {
                    $data[] = $row;
                }

                $stmt->close();
            } else {
                echo "Statement preparation failed: " . $this->conn->error;
            }
        } catch (Exception $e) {
            echo "Error: " . $e->getMessage();
        }

        return $data;
    }



    function concatId($half, $lastid)
    {

        if ($lastid < 10) {
            $lastid = "00000$lastid";
        } elseif ($lastid >= 10 && $lastid < 100) {
            $lastid = "0000$lastid";
        } elseif ($lastid >= 100 && $lastid < 1000) {
            $lastid = "000$lastid";
        } elseif ($lastid >= 1000 && $lastid < 10000) {
            $lastid = "00$lastid";
        } elseif ($lastid >= 10000 && $lastid < 100000) {
            $lastid = "0$lastid";
        } else {
            $lastid = $lastid;
        }
        $alph = 'A';
        $tempappointmentid = "$half$alph$lastid";
        return $tempappointmentid;
    }





    function appointmentidGeneration($half)
    {
        // echo $half;
        $idList = $this->getAppointmentIds();

        $lastid = 0;

        if (!empty($idList)) {
            $lastAppointment = end($idList);

            $lastAppointment = $lastAppointment['appointment_id'];
            $lastid = preg_replace('/\D/', '', $lastAppointment);
            $lastid = substr($lastid, -6);
            // $lastid = intval(substr(json_encode($lastAppointment), 9)); // Extract the numeric part 
        }

        // Increment the last ID
        $lastid += 1;

        // Generate a new appointment ID
        $tempappointmentid = $this->concatId($half, $lastid);

        // Check if the new ID already exists
        while (in_array($tempappointmentid, $idList)) {
            $lastid += 1;
            $tempappointmentid = $this->concatId($half, $lastid);
        }

        // Output the generated ID
        return $tempappointmentid;

        // You can return the generated ID instead of exiting
        return $tempappointmentid;
    }



    function generateAdminId()
    {

        /*
        // geeting the current last admin id
        $currentID = $this->lastAdminId();

        // first 9 laters (e.g. ADMDATE, ADM091123) are removed
        $currentID = substr($currentID, 9);

        // Extract the numeric part and increment it (similar to the previous code)
        if ($currentID !== null) {
            preg_match('/\d+$/', $currentID, $matches);
            if (!empty($matches)) {
                $nextNumber = intval($matches[0]) + 1;
            } else {
                $nextNumber = 1;
            }
        } else {
            $nextNumber = 1;
        }
    
        // Pad the numeric part with zeros to ensure a minimum length of 5 characters
        $formattedNumber = str_pad($nextNumber, 3, '0', STR_PAD_LEFT);
    
        // Get the current date in the format 'Ymd' (e.g., 091123 for November 9, 2023)
        $currentDate = date('dmy');*/

        $dateTimeFormatted = date("ymdHis", strtotime(NOW));

        $dateTime = new DateTime();
        $microsecond =  $dateTime->format("u");
        $uniquenumber = $dateTimeFormatted.$microsecond;
        $uniqueID = substr($uniquenumber, 0, 15);

        // Construct the final ADM ID with the current date
        $newID = "ADM{$uniqueID}";

        return $newID;
    }



    function generateClinicId($adminId)
    {

        $newId = filter_var($adminId, FILTER_SANITIZE_NUMBER_INT);
        return $newId;
    }




    function lastAdminId()
    {
        $sql = "SELECT admin_id FROM `admin` ORDER BY added_on DESC LIMIT 1";
        $query = $this->conn->query($sql);
        if ($query->num_rows > 0) {

            while ($result = $query->fetch_array()) {
                $data = $result['admin_id'];
            }
            return $data;
        }
        return;
    }
    // function appointmentidGeneration($half){

    //     $idList = $this->getAppointmentIds();

    //     if(in_array('ME310322A000012', $idList )) {
    //         echo 'exist';
    //         $lastid +=1;
    //         $tempappointmentid = $this->concatId($half, $lastid);

    //     }
    //     echo $tempappointmentid;
    //         foreach ($idList as $rowdata) {
    //             $rowdata['appointment_id'];
    //         }
    //         $lastid = $rowdata['appointment_id'];
    //         $lastid = substr($lastid, 9);
    //         // print_r($lastid); exit;
    //         $lastid +=1;
    //         // echo $lastid;exit;
    //         $tempappointmentid = $this->concatId($half, $lastid);



    //             $lastid +=1;
    //         while(array_search($tempappointmentid, $idList)) {
    //             $tempappointmentid = $this->concatId($half, $lastid);

    //         }
    //         echo $tempappointmentid;
    //         exit;
    //         return $tempappointmentid;
    //     }







    //function appointmentidGeneration($half){

    //     $select = "SELECT appointment_id FROM appointments";
    //     $selectQuery = $this->conn->query($select);
    //     while($result = $selectQuery->fetch_array()){
    //         $data[]	= $result;
    //     }
    //     print_r($data); exit;
    //     foreach ($data as $rowdata) {
    //         $rowdata['appointment_id'];
    //     }
    //     $lastid = $rowdata['appointment_id'];
    //     // $lastid = substr($lastid, 9);
    //     // print_r($lastid); exit;
    //     $lastid +=1;
    //     // echo $lastid;exit;
    //     if ($lastid < 10) {
    //         $lastid = "00000$lastid";
    //     }elseif($lastid >=10 && $lastid < 100){
    //         $lastid = "0000$lastid";
    //     }elseif($lastid >=100 && $lastid < 1000){
    //         $lastid = "000$lastid";
    //     }elseif($lastid >=1000 && $lastid < 10000){
    //         $lastid = "00$lastid";
    //     }elseif($lastid >=10000 && $lastid < 100000){
    //         $lastid = "0$lastid";
    //     }else {
    //         $lastid = $lastid;
    //     }
    //     $alph = 'A';
    //     $tempappointmentid = "$half$alph$lastid";

    //     if (array_search($tempappointmentid, $data)) {
    //         # code...
    //     }
    //     return $tempappointmentid;
    // }


    function pharmecyInvoiceId()
    {
        $data = array();
        $select = "SELECT * FROM stock_out";
        $selectQuery = $this->conn->query($select);
        while ($result = $selectQuery->fetch_array()) {
            $data[]    = $result;
        }
        $invoice = count($data) + 1;
        return $invoice;
    }


    function stockReturnId()
    {
        $data = array();
        $select = "SELECT * FROM stock_return";
        $selectQuery = $this->conn->query($select);
        while ($result = $selectQuery->fetch_array()) {
            $data[]    = $result;
        }
        $id = count($data) + 1;
        return $id;
    }
}


// $id = new IdsGeneration();

// echo $id->lastAdminId();
// echo '<br>';
// echo $id->generateAdminId();
