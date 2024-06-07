<?php
class IdsGeneration{

    use DatabaseConnection;

    function generateOrderId() {
        // Generate random number
        $randomNumber = mt_rand(1, 99999);
    
        // Generate product ID with prefix "MED"
        $orderId = "MED" . str_pad($randomNumber, 9, "0", STR_PAD_LEFT);
    
        // Check if product ID exists in the database
        $stmt = $this->conn->prepare("SELECT * FROM subscription WHERE order_id = ?");
        $stmt->bind_param("s", $orderId);
        $stmt->execute();
        $result = $stmt->get_result();
    
        // If product ID exists, generate a new one recursively
        if ($result->num_rows > 0) {
            // Generate a new product ID recursively
            return $this->generateOrderId();
        } else {
            // Product ID does not exist, return the generated ID
            return $orderId;
        }
    }

    function generateAdminId()
    {

        $dateTimeFormatted = date("ymdHis", strtotime(NOW));

        $dateTime = new DateTime();
        $microsecond =  $dateTime->format("u");
        $uniquenumber = $dateTimeFormatted . $microsecond;
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





    



   



    




    function otpGgenerator(){

        $characters = '0123456789';
        $randomString = '';
        for ($i = 0; $i < 6; $i++) {
            $randomString .= $characters[rand(0, strlen($characters) - 1)];
        }

        // $randomString = $randomString;

        return $randomString;
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


    function generateProductId() {
        // Generate random number
        $randomNumber = mt_rand(1, 999999999999);
    
        // Generate product ID with prefix "PR"
        $productId = "PR" . str_pad($randomNumber, 12, "0", STR_PAD_LEFT);
    
        // Check if product ID exists in the database
        $stmt = $this->conn->prepare("SELECT * FROM products WHERE product_id = ?");
        $stmt->bind_param("s", $productId);
        $stmt->execute();
        $result = $stmt->get_result();
    
        // If product ID exists, generate a new one recursively
        if ($result->num_rows > 0) {
            // Generate a new product ID recursively
            return $this->generateProductId();
        } else {
            // Product ID does not exist, return the generated ID
            return $productId;
        }
    }


    public function generateLabBillId() {
        $query = "SELECT bill_id FROM lab_billing";
        $result = $this->conn->query($query);
        
        $existingBillIds = [];
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $existingBillIds[] = $row['bill_id'];
            }
        }
        
        $newBillId = 'ML' . str_pad(mt_rand(1, 999999), 6, '0', STR_PAD_LEFT);;
        
        if (in_array($newBillId, $existingBillIds)) {
            $this->generateLabBillId();
        }else {
            return $newBillId;
        }
    }
    

}

// $id = new IdsGeneration();

// echo $id->lastAdminId();
// echo '<br>';
// echo $id->generateAdminId();
