<?php

class PathologyReport
{
    use DatabaseConnection;

    /********************************************************************************************
     *                                      Test Report Table                                   *
     ********************************************************************************************/

    function addTestReport($bill_id, $adminId, $addedBy, $added_on = NOW)
    {
        try {
            $addQuery = "INSERT INTO `test_report` (`bill_id`, `admin_id`, `created_by`, `added_on`) VALUES (?, ?, ?, ?)";
            $stmt = $this->conn->prepare($addQuery);

            if ($stmt === false) {
                throw new Exception('Prepare failed: ' . $this->conn->error);
            }

            $stmt->bind_param('ssss', $bill_id, $adminId, $addedBy, $added_on);

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

    function reportStatus($billId)
    {
        try {
            $query1 = "SELECT id FROM test_report WHERE bill_id = $billId";
            $stmt = $this->conn->prepare($query1);
            if ($stmt) {
                $stmt->execute();
                $result = $stmt->get_result();

                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        $reportIds[] = $row['id'];
                    }
                    // print_r($reportIds);
                    foreach ($reportIds as $eachReport) {
                        $reportDetails = $this->labReportDetailbyId($eachReport);
                        $reportDetails = json_decode($reportDetails);
                        foreach ($reportDetails as $eachDetail) {
                            $params[] = $eachDetail->param_id;
                        }
                    }
                    // print_r($params);
                    $returnData = ['status' => true, 'message' => 'success', 'data' => $params];
                } else {
                    $returnData = ['status' => false, 'message' => 'No data found'];
                }
                $stmt->close();

                return $returnData;
            } else {
                throw new Exception('Statement prepare exception');
            }
        } catch (Exception $e) {
            return ['status' => false, 'message' => $e->getMessage()];

        }
    }

    function testReportById($report_id)
    {
        try {
            $sql = "SELECT * FROM `test_report` WHERE `id` = '$report_id'";
            $query = $this->conn->query($sql);
            $result = $query->fetch_assoc();
            $data = $result;
            $data['details'] = $this->reportDetails($report_id);
            $dataset = json_encode($data);
            return $dataset;
        } catch (Exception $e) {
            echo $e->getMessage();
        }
    }


    function testReportFetch($adminId = "")
    {
        try {
            $datas = array();
            if (!empty($adminId)) {
                $sql = "SELECT * FROM `test_report` WHERE `admin_id` = '$adminId'";
            } else {
                $sql = "SELECT * FROM `test_report`";
            }
            $query = $this->conn->query($sql);
            while ($result = $query->fetch_object()) {
                $datas[] = $result;
            }
            $dataset = json_encode($datas);
            return $dataset;
        } catch (Exception $e) {
            echo $e->getMessage();
        }
    }


    function labReportbyReportId($reportId)
    {
        try {
            $datas = null;
            $sql = "SELECT * FROM `test_report` where `bill_id`='$reportId'";
            $query = $this->conn->query($sql);
            while ($result = $query->fetch_object()) {
                $datas = $result;
            }
            $dataset = json_encode($datas);
            return $dataset;
        } catch (Exception $e) {
            echo $e->getMessage();
        }
    }

    function getReportParamsByBill($billId)
    {
        try {
            $sql = "SELECT id FROM `test_report` WHERE `bill_id`= $billId";
            $query = $this->conn->query($sql);

            $reports = [];
            while ($result = $query->fetch_object()) {
                $reports[] = $result->id;
            }

            $existingParams = [];
            if (!empty($reports)) {
                foreach ($reports as $eachReport) {
                    $response = $this->reportDetails($eachReport);
                    if ($response) {
                        foreach ($response as $eachRes) {
                            $existingParams[] = $eachRes['param_id'];
                        }
                    }
                }
            }

            return $existingParams;
        } catch (Exception $e) {
            echo $e->getMessage();
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

    function addReportDetails($reportId, $param_id, $param_value)
    {
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


    // lab report data fetch by Id
    function labReportDetailbyId($reportId)
    {
        try {
            $datas = array();
            $sql = "SELECT * FROM `test_report_details` where `report_id`= '$reportId'";
            $query = $this->conn->query($sql);
            while ($result = $query->fetch_object()) {
                $datas[] = $result;
            }
            $dataset = json_encode($datas);
            return $dataset;
        } catch (Exception $e) {
            echo $e->getMessage();
        }
    }

    function reportDetails($reportId)
    {
        try {
            $datas = null;
            $sql = "SELECT * FROM `test_report_details` where `report_id`=$reportId";
            $query = $this->conn->query($sql);
            while ($result = $query->fetch_assoc()) {
                $datas[] = $result;
            }
            $dataset = $datas;
            return $dataset;
        } catch (Exception $e) {
            echo $e->getMessage();
        }
    }
}
