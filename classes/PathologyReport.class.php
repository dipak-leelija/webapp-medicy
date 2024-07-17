<?php

class PathologyReport
{
    use DatabaseConnection;

    /********************************************************************************************
     *                                      Test Report Table                                   *
    ********************************************************************************************/

    function addTestReport($bill_id, $adminId, $added_on = NOW)
    {
        try {
            $addQuery = "INSERT INTO `test_report` (`bill_id`, `admin_id`, `added_on`) VALUES (?, ?, ?)";
            $stmt = $this->conn->prepare($addQuery);

            if ($stmt === false) {
                throw new Exception('Prepare failed: ' . $this->conn->error);
            }

            $stmt->bind_param('sss', $bill_id, $adminId, $added_on);

            $result = $stmt->execute();
            if ($result) {
                $last_id = $this->conn->insert_id;
            }
            if ($stmt->affected_rows > 0) {
                $response = ['status' => true, 'message' => 'success', 'reportid' => $last_id];
            } else {
                $response = ['status' => false, 'message' => 'Data insertion failed!'];
            }

            $stmt->close();
            return json_encode($response);
        } catch (Exception $e) {
            return json_encode(['status' => false, 'message' => 'Error: ' . $e->getMessage()]);
        }
    }


    // function checkTestBill($billId)
    // {
    //     $checkSql = "SELECT COUNT(*) FROM test_report WHERE id = ?";
    //     $stmt = $this->conn->prepare($checkSql);
    //     $stmt->bind_param("s", $billId);
    //     $stmt->execute();
    //     $stmt->bind_result($count);
    //     $stmt->fetch();
    //     $stmt->close();
    //     if ($count > 0) {
    //         return True;
    //     } else {
    //         return False;
    //     }
    // }


    /********************************************************************************************
    *                                  Test Report Details Table                                *
    ********************************************************************************************/
    
    function addReportDetails($reportId, $param_id, $param_value){
        try {
            $addQuery = "INSERT INTO `test_report_details` (`report_id`, `param_id`, `param_value`) VALUES (?, ?, ?)";
            $stmt = $this->conn->prepare($addQuery);

            if ($stmt === false) {
                throw new Exception('Prepare failed: ' . $this->conn->error);
            }

            $stmt->bind_param('sss', $reportId, $param_id, $param_value);
            $stmt->execute();
            
            if ($stmt->affected_rows > 0) {
                $response = ['status' => true, 'message' => 'success',];
            } else {
                $response = ['status' => false, 'message' => 'Data insertion failed!'];
            }

            $stmt->close();
            return json_encode($response);
        } catch (Exception $e) {
            return json_encode(['status' => false, 'message' => 'Error: ' . $e->getMessage()]);
        }
    }	

}
