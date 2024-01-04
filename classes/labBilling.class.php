<?php

require_once 'dbconnect.php';





class LabBilling extends DatabaseConnection
{

    function addLabBill($billId, $billingDate, $patientId, $referedDoc, $testDate, $totalAmount, $discountOnTotal, $totalAfterDiscount, $cgst, $sgst, $paidAmount, $dueAmount, $status, $addedBy, $addedOn, $adminId)
    {

        try {
            $insertBill = "INSERT INTO lab_billing (`bill_id`, `bill_date`, `patient_id`, `refered_doctor`, `test_date`, `total_amount`, `discount`, `total_after_discount`, `cgst`, `sgst`, `paid_amount`, `due_amount`, `status`, `added_by`, `added_on`, `admin_id`) VALUES(?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

            $stmt = $this->conn->prepare($insertBill);

            if ($stmt) {
                $stmt->bind_param('isssssssssssssss', $billId, $billingDate, $patientId, $referedDoc, $testDate, $totalAmount, $discountOnTotal, $totalAfterDiscount, $cgst, $sgst, $paidAmount, $dueAmount, $status, $addedBy, $addedOn, $adminId);

                $res = $stmt->execute();

                $stmt->close();

                return $res;
            } else {
                return false;
            }
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }






    function labBillDisplay($adminId = '')
    {
        try {
            if (!empty($adminId)) {
                $selectBill = "SELECT * FROM lab_billing WHERE admin_id = '$adminId'";
            } else {
                $selectBill = "SELECT * FROM lab_billing ";
            }
            $stmt = $this->conn->prepare($selectBill);

            $stmt->execute();

            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                $billData = array();
                while ($rows = $result->fetch_object()) {
                    $billData[] = $rows;
                }
                return json_encode(['status' => '1', 'message' => 'success', 'data' => $billData]);
            } else {
                return json_encode(['status' => '0', 'message' => 'fail', 'data' => '']);
            }
        } catch (Exception $e) {
            return json_encode(['status' => '0', 'message' => $e->getMessage(), 'data' => '']);
        }
        return 0;
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




    function labBillFilter($adminId = '', $col = '', $filterVal = '')
    {
        try {
            if (!empty($adminId)) {
                if ($col == 'search') {
                    $selectBill = "SELECT * FROM lab_billing WHERE admin_id = '$adminId' AND (bill_id LIKE '%$filterVal%' OR patient_id LIKE '%$filterVal%') ORDER BY bill_id ASC";
                } else {
                    $selectBill = "SELECT * FROM lab_billing WHERE admin_id = '$adminId' AND $col = $filterVal";
                }
            } else {
                if ($col == 'search') {
                    $selectBill = "SELECT * FROM lab_billing WHERE  (bill_id LIKE '%$filterVal%' OR patient_id LIKE '%$filterVal%') ORDER BY bill_id ASC";
                } else {
                    $selectBill = "SELECT * FROM lab_billing WHERE  refered_doctor = ' $filterVal' ";
                }
            }


            $stmt = $this->conn->prepare($selectBill);

            $stmt->execute();

            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                $billData = array();
                while ($row = $result->fetch_object()) {
                    $billData[] = $row;
                }
                $stmt->close();
                return json_encode(['status' => '1', 'message' => 'success', 'data' => $billData]);
            } else {
                $stmt->close();
                return json_encode(['status' => '0', 'message' => 'fail', 'data' => '']);
            }
        } catch (Exception $e) {
            return json_encode(['status' => '0', 'message' => $e->getMessage(), 'data' => '']);
        }
    } //end employeesDisplay function


    ///====== Lab Bill Filter based on AdminId=====///
    function labBillFilterByAdminID($adminId)
    {
        try {
            $selectBill = "SELECT * FROM `lab_billing` WHERE `admin_id` = ?";

            $stmt = $this->conn->prepare($selectBill);
            $stmt->bind_param('s', $adminId);

            $stmt->execute();

            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                $billData = array();
                while ($row = $result->fetch_object()) {
                    $billData[] = $row;
                }
                $stmt->close();
                return json_encode(['status' => '1', 'message' => 'success', 'data' => $billData]);
            } else {
                $stmt->close();
                return json_encode(['status' => '0', 'message' => 'fail', 'data' => '']);
            }
        } catch (Exception $e) {
            return json_encode(['status' => '0', 'message' => $e->getMessage(), 'data' => '']);
        }
    }



    function labBillFilterByDate($adminId, $fromDate, $toDate)
    {
        try {


            $selectBill = "SELECT * FROM lab_billing 
                            WHERE date(added_on) BETWEEN '$fromDate' AND '$toDate' 
                            AND admin_id = '$adminId'";

            $stmt = $this->conn->prepare($selectBill);

            $stmt->execute();

            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                $billData = array();
                while ($row = $result->fetch_object()) {
                    $billData[] = $row;
                }
                $stmt->close();
                return json_encode(['status' => '1', 'message' => 'success', 'data' => $billData]);
            } else {
                $stmt->close();
                return json_encode(['status' => '0', 'message' => 'fail', 'data' => '']);
            }
        } catch (Exception $e) {
            return json_encode(['status' => '0', 'message' => $e->getMessage(), 'data' => '']);
        }
    } //end employeesDisplay function




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
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_object()) {
                    $rows[] = $row;
                }
                return $rows;
            } else {
                return null;
            }
        } catch (Exception $e) {
            $e->getMessage();
        }
    }


    // Function to get the last lab bill ID from the database
    function getLastLabBillId()
    {

        // Replace 'lab_bills' with your actual table name
        $query = "SELECT MAX(CAST(bill_id AS SIGNED)) AS largest_bill_id FROM lab_billing";

        $result = $this->conn->query($query);

        if ($result->num_rows > 0) {
            // Fetch the last lab bill ID
            $row = $result->fetch_assoc();

            return $row['largest_bill_id'];
        } else {
            // No lab bill ID found
            return null;
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
