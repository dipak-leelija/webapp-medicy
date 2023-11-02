<?php

require_once 'dbconnect.php';





class LabBilling extends DatabaseConnection
{





    function addLabBill($billId, $billingDate, $patientId, $referedDoc, $testDate,  $totalAmount, $discountOnTotal, $totalAfterDiscount, $cgst, $sgst, $paidAmount, $dueAmount, $status)
    {

        $insertBill = "INSERT INTO  lab_billing (`bill_id`, `bill_date`, `patient_id`, `refered_doctor`, `test_date`, `total_amount`, `discount`, `total_after_discount`, `cgst`, `sgst`, `paid_amount`, `due_amount`, `status`) VALUES ('$billId', '$billingDate', '$patientId', '$referedDoc', '$testDate', '$totalAmount', '$discountOnTotal', '$totalAfterDiscount', '$cgst', '$sgst', '$paidAmount', '$dueAmount', '$status')";
        // echo $insertEmp.$this->conn->error;
        // exit;
        $insertBillQuery = $this->conn->query($insertBill);
        return $insertBillQuery;
    } //end addLabBill function






    function labBillDisplay()
    {

        $selectBill = "SELECT * FROM lab_billing";
        $billQuery = $this->conn->query($selectBill);
        $rows = $billQuery->num_rows;
        if ($rows > 0) {
            while ($result = $billQuery->fetch_array()) {
                $billData[]    = $result;
            }
            return $billData;
        } else {
            return 0;
        }
    } //end employeesDisplay function




    function labBillDisplayById($billId)
    {

        $selectBill = "SELECT * FROM lab_billing WHERE `lab_billing`.`bill_id` = '$billId'";
        $billQuery = $this->conn->query($selectBill);
        $rows = $billQuery->num_rows;
        if ($rows > 0) {
            while ($result = $billQuery->fetch_array()) {
                $billData[]    = $result;
            }
            return $billData;
        } else {
            return 0;
        }
    }



    function updateLabBill($billId, $referedDoc, $testDate,  $totalAmount, $discountOnTotal, $totalAfterDiscount, $cgst, $sgst, $paidAmount, $dueAmount, $status)
    {

        $updateBill = "UPDATE  lab_billing SET  `refered_doctor` = '$referedDoc', `test_date` = '$testDate', `total_amount` = '$totalAmount', `discount` = '$discountOnTotal', `total_after_discount` = '$totalAfterDiscount', `cgst` = '$cgst', `sgst` = '$sgst', `paid_amount` = '$paidAmount', `due_amount` = '$dueAmount', `status` = '$status' WHERE `lab_billing`.`bill_id` = '$billId'";
        // echo $insertEmp.$this->conn->error;
        // exit;
        $updateBillQuery = $this->conn->query($updateBill);
        return $updateBillQuery;
    } //end updateLabBill function


    function cancelLabBill($billId, $status)
    {

        $cancelBill = "UPDATE `lab_billing` SET `status` = '$status' WHERE `lab_billing`.`bill_id` = '$billId'";
        // echo $cancelBill.$this->conn->error;
        // exit;
        $cancelBillQuery = $this->conn->query($cancelBill);
        // echo $cancelBillQuery.$this->conn->error;
        // exit;
        return $cancelBillQuery;
    } //end updateLabBill function

    /// Lab bill details by patient Id ///
    function labBiilingDetailsByPatientId($patientId)
    {
        try {
            $sql = "SELECT *  FROM lab_billing WHERE `lab_billing`.`patient_id` = '$patientId'";
            $result = $this->conn->query($sql);
            if($result->num_rows >0){
                while($row = $result->fetch_object()){
                    $rows[] = $row;
                }
                return $rows;
            }else{
                return null;
            }
        } catch (Exception $e) {
            $e->getMessage();
        }
    }

    // $query = $this->conn->query($sql);
    // while ($result = $query->fetch_object()) {
    //     $data = $result;
    // }
    // $dataset = json_encode($data);
    // return $dataset;
    //     function labBiilingDetailsByPatientId($patientId){
    //     try {
    //         $sql = "SELECT *, MAX(bill_date) AS max_bill_date 
    //                 FROM lab_billing 
    //                 WHERE `lab_billing`.`patient_id` = '$patientId'";
    //         $result = $this->conn->query($sql);

    //         if ($result->num_rows > 0) {
    //             while ($row = $result->fetch_object()) {
    //                 $rows[] = $row;
    //             }
    //             return $rows;
    //         } else {
    //             return null;
    //         }
    //     } catch (Exception $e) {
    //         return $e->getMessage();
    //     }
    // }
}

// eof LabBilling class

// }
