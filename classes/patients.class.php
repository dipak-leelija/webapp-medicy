<?php

require_once 'dbconnect.php';




class Patients extends DatabaseConnection
{


    function addPatients($patientId, $patientName, $patientGurdianName, $patientEmail, $patientPhoneNumber, $patientAge, $gender, $patientAddress1, $patientAddress2, $patientPS, $patientDist, $patientPIN, $patientState, $visited, $employeeId, $addedOn, $adminId)
    {
        try {
            $insertPatients = "INSERT INTO `patient_details` (`patient_id`, `name`, `gurdian_name`, `email`, `phno`, `age`, `gender`, `address_1`, `address_2`, `patient_ps`, `patient_dist`, `patient_pin`, `patient_state`, `visited`, `added_by`, `added_on`, `admin_id`) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

            $stmt = $this->conn->prepare($insertPatients);

            $stmt->bind_param("sssssssssssssssss", $patientId, $patientName, $patientGurdianName, $patientEmail, $patientPhoneNumber, $patientAge, $gender, $patientAddress1, $patientAddress2, $patientPS, $patientDist, $patientPIN, $patientState, $visited, $employeeId, $addedOn, $adminId);

            if ($stmt->execute()) {
                $stmt->close();
                return true;
            } else {
                $stmt->close();
                return false;
            }
        } catch (Exception $e) {
            echo "Error: " . $e->getMessage();
            return false;
        }
    }





    function updatePatientsVisitingTime($patientId, $patientEmail, $patientPhoneNumber, $patientAge, $visited)
    {

        $insertPatients = " UPDATE `patient_details` SET `email` = '$patientEmail', `phno` = '$patientPhoneNumber', `age` = '$patientAge', `visited` = '$visited' WHERE `patient_details`.`patient_id` = '$patientId'";

        $insertQuery = $this->conn->query($insertPatients);

        return $insertQuery;
    } // end updatePatientsVisitingTime function





    function addLabPatients($patientId, $patientName, $patientGurdianName, $patientEmail, $patientPhoneNumber, $patientAge, $gender, $patientAddress1, $patientAddress2, $patientPS, $patientDist, $patientPIN, $patientState)
    {

        $insertPatients = "INSERT INTO `patient_details` (`patient_id`, `name`, `gurdian_name`, `email`, `phno`, `age`, `gender`, `address_1`, `address_2`, `patient_ps`, `patient_dist`, `patient_pin`, `patient_state`) VALUES
        ('$patientId', '$patientName', '$patientGurdianName', '$patientEmail', '$patientPhoneNumber', '$patientAge', '$gender', '$patientAddress1', '$patientAddress2', '$patientPS', '$patientDist', '$patientPIN', '$patientState')";

        $insertQuery = $this->conn->query($insertPatients);

        return $insertQuery;
    } // end addPatients function





    function updateLabVisiting($patientId, $Labvisited)
    {

        $insertPatients = " UPDATE `patient_details` SET `lab_visited` = '$Labvisited' WHERE `patient_details`.`patient_id` = '$patientId'";

        $insertQuery = $this->conn->query($insertPatients);

        return $insertQuery;
    } // end updatePatientsVisitingTime function



    function labVisists($patientId)
    {

        try {
            $query = "SELECT lab_visited FROM patient_details WHERE patient_id = ?";
            $res = $this->conn->prepare($query);
            $res->bind_param("s", $patientId); // Assuming admin_id is a string

            if ($res->execute()) {
                $result = $res->get_result();
                if ($result->num_rows === 0) {
                    $data = null;
                } else {
                    $data = $result->fetch_object();
                    $data = $data->lab_visited;
                }
            } else {
                throw new Exception("Query execution failed.");
            }
        } catch (Exception $e) {
            // Handle the error (e.g., log the error or return an error message)
            error_log("Error in labVisists: " . $e->getMessage());
            return "An error occurred while fetching labVisists.";
        }

        return $data;
    }


    function allPatients($admin)
    {

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
                return json_encode(['status' => 1, 'message' => 'success', 'data' => $data]);
            } else {
                // throw new Exception("Query execution failed.");
                return json_encode(['status' => 0, 'message' => 'empty', 'data' => '']);
            }
        } catch (Exception $e) {
            // Handle the error (e.g., log the error or return an error message)
            // error_log("Error in allPatients: " . $e->getMessage());
            // return array("error" => "An error occurred while fetching patient data.");
            echo  $e->getMessage();
            return json_encode(['status' => 0, 'message' => $e->getMessage(), 'data' => '']);
        }

        return 0;
        // return json_encode(['status' => 0, 'message' => $e->getMessage(), 'data' => $data]);
    }




    function filterPatientByNameOrPid($data, $adminId)
    {
        // function filterAppointmentsByIdOrName($col, $data, $adminId){
        try {

            $stmt = $this->conn->prepare("SELECT * FROM `patient_details` WHERE `patient_id` LIKE ? OR  `name` LIKE ? AND admin_id = ? ORDER BY id DESC");

            if ($stmt) {

                $searchPattern = "%" . $data . "%";
                $stmt->bind_param("sss", $searchPattern, $searchPattern, $adminId);

                $stmt->execute();
                $result = $stmt->get_result();

                if ($result->num_rows > 0) {
                    $resultData = array();
                    while ($row = $result->fetch_object()) {
                        $resultData[] = $row;
                    }
                    return json_encode(['status' => '1', 'message' => 'success', 'data' => $resultData]);
                } else {
                    return json_encode(['status' => '0', 'message' => '', 'data' => '']);
                }
                $stmt->close();
            } else {
                throw new Exception("Error statement preparation: $stmt->error");
            }
        } catch (Exception $e) {
            error_log("Error in appointmentsDisplay: " . $e->getMessage());
        }
        return 0;
    }





    function patientFilterByColData($col, $data, $admin)
    {
        try {
            $selectById = "SELECT * FROM patient_details WHERE `$col` = ? AND `admin_id` = ?";
            $stmt = $this->conn->prepare($selectById);

            if ($stmt) {
                $stmt->bind_param("si", $data, $admin);

                $stmt->execute();

                $result = $stmt->get_result();

                if ($result->num_rows > 0) {
                    $patiensData = [];
                    while ($row = $result->fetch_object()) {
                        $patiensData[] = $row;
                    }
                    return json_encode(['status' => '1', 'message' => 'success', 'data' => $patiensData]);
                } else {
                    return json_encode(['status' => '0', 'message' => 'fail', 'data' => '']);
                }
                $stmt->close();
            } else {
                throw new Exception("Error statement preparation: " . $this->conn->error);
            }
        } catch (Exception $e) {
            return json_encode(['status' => '', 'message' => $e->getMessage(), 'data' => '']);
        }
        return 0;
    }





    function patientFilterByDate($fromDate, $toDate, $admin)
    {
        try {
            $selectByDate = "SELECT * FROM `patient_details`
                            WHERE added_on BETWEEN '$fromDate' AND '$toDate'
                            AND admin_id = '$admin'";

            $stmt = $this->conn->prepare($selectByDate);

            $stmt->execute();

            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                $patiensData = [];
                while ($row = $result->fetch_object()) {
                    $patiensData[] = $row;
                }
                $stmt->close();
                return json_encode(['status' => '1', 'message' => 'success', 'data' => $patiensData]);
            } else {
                $stmt->close();
                return json_encode(['status' => '0', 'message' => 'fail', 'data' => '']);
            }
        } catch (Exception $e) {
            return json_encode(['status' => '', 'message' => $e->getMessage(), 'data' => '']);
        }
        return 0;
    }





    function patientsDisplayById($patientId)
    {
        $data = array();
        $selectById = "SELECT * FROM patient_details WHERE `id`= '$patientId'";
        $selectByIdQuery = $this->conn->query($selectById);
        // echo var_dump($selectByIdQuery);
        while ($result = $selectByIdQuery->fetch_array()) {
            $data[]    = $result;
        }
        return $data;
    } //end appointmentsDisplay function

    function patientName($patientId)
    {
        try {
            $selectById = "SELECT name FROM patient_details WHERE `patient_id`= ?";
            $stmt = $this->conn->prepare($selectById);

            if (!$stmt) {
                throw new Exception("Failed to prepare the statement.");
            }

            $stmt->bind_param("s", $patientId);
            $stmt->execute();

            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                $data = $result->fetch_assoc()['name'];
            } else {
                $data = $patientId; // return as it is
            }

            $stmt->close();

            return $data;
        } catch (Exception $e) {
            echo "Error: " . $e->getMessage();
            return null;
        }
    }



    function patientsDisplayByPId($patientId)
    {
        try {
            // Initialize the data array
            $data = array();

            // Prepare the SQL statement with a parameter
            $sql = "SELECT * FROM patient_details WHERE patient_id = ?";
            $stmt = $this->conn->prepare($sql);

            if ($stmt) {
                // Bind the parameter
                $stmt->bind_param("s", $patientId);

                // Execute the statement
                if ($stmt->execute()) {
                    $result = $stmt->get_result();

                    // Check the number of rows returned
                    if ($result->num_rows === 0) {
                        return $data; // No rows found
                    } else {
                        while ($row = $result->fetch_object()) {
                            $data = $row;
                        }
                        return json_encode($data);
                    }
                } else {
                    throw new Exception("Query execution failed.");
                }
            } else {
                throw new Exception("Statement preparation failed.");
            }
        } catch (Exception $e) {
            // Handle the error (e.g., log the error or return an error message)
            error_log("Error in patientsDisplayByPId: " . $e->getMessage());
            return false;
        }
    }

    // ///count patient Times of visits ////
    function patientVisitCount($Name, $patientId)
    {
        try {
            // $sql = "SELECT COUNT(id), added_on FROM `patient_details` WHERE `name` = '$Name' ";
            $sql = "SELECT COUNT(id) as count, MAX(added_on) as Last_Visited FROM `patient_details` WHERE `name`= '$Name' AND `patient_id` = '$patientId'";
            $result = $this->conn->query($sql);
            if ($result !== false) {
                $row = $result->fetch_assoc();
                return $row;
            } else {
                throw new Exception("Error executing the query.");
            }
        } catch (Exception $e) {
            $e->getMessage();
        }
    }



    //======================///


    /// find new patient using visited and lab_visited attribute ///
    function newPatientCount($adminId)
    {
        try {
            $sql = "SELECT COUNT(*) as patient_count, added_on FROM `patient_details` WHERE `admin_id` = '$adminId' AND `visited` = '1' AND `lab_visited` = '1' GROUP BY added_on";
            // print_r($sql);
            $result = $this->conn->query($sql);
            if ($result->num_rows > 0) {
                $rows = [];
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

    // new patient by day //
    function newPatientByDay($adminId, $startDate)
    {
        try {
            $sql    = "SELECT COUNT(*) as patient_count, added_on FROM `patient_details` WHERE `admin_id` = '$adminId' AND `visited` = 1 AND `lab_visited`= 1 AND `added_on`= '$startDate'";
            $result = $this->conn->query($sql);
            if ($result !== false) {
                $row = [];
                while ($row = $result->fetch_object()) {
                    $rows[] = $row;
                }
                return $rows;
                // $row = $result->fetch_object();
                // return $row->patient_count;
            } else {
                return [];
            }
        } catch (Exception $e) {
            $e->getMessage();
            return [];
        }
    }

    /// new patient count last 24 hrs ///
    function newPatientCountLast24Hours($adminId)
    {
        try {
            $sql = "SELECT COUNT(*) as patient_count , added_on
                FROM `patient_details` 
                WHERE `admin_id` = '$adminId' 
                AND `visited` = '1' AND `lab_visited` = '1'
                AND `added_on` >= DATE_SUB(NOW(), INTERVAL 1 DAY)";

            $result = $this->conn->query($sql);
            if ($result !== false) {
                $row = [];
                while ($row = $result->fetch_object()) {
                    $rows[] = $row;
                }
                return $rows;
            } else {
                return [];
            }
        } catch (Exception $e) {
            $e->getMessage();
        }
    }

    /// new patient count last 7 days /// 
    function newPatientCountLast7Days($adminId)
    {
        try {
            $sql = "SELECT COUNT(*) as patient_count , added_on
                FROM `patient_details` 
                WHERE `admin_id` = '$adminId' 
                AND `visited` = '1' AND `lab_visited` = '1'
                AND `added_on` >= DATE_SUB(NOW(), INTERVAL 7 DAY)";

            $result = $this->conn->query($sql);
            if ($result !== false) {
                $row = [];
                while ($row = $result->fetch_object()) {
                    $rows[] = $row;
                }
                return $rows;
            } else {
                return [];
            }
        } catch (Exception $e) {
            $e->getMessage();
        }
    }


    /// new patient count last 30 Days ///
    function newPatientCountLast30Days($adminId)
    {
        try {
            $sql = "SELECT COUNT(*) as patient_count , added_on 
                FROM `patient_details` 
                WHERE `admin_id` = '$adminId' 
                AND `visited` = '1' AND `lab_visited` = '1'
                AND `added_on` >= DATE_SUB(NOW(), INTERVAL 30 DAY)";

            $result = $this->conn->query($sql);
            if ($result !== false) {
                $row = [];
                while ($row = $result->fetch_object()) {
                    $rows[] = $row;
                }
                return $rows;
            } else {
                return [];
            }
        } catch (Exception $e) {
            $e->getMessage();
        }
    }

    /// new patient count based on range //
    function findPatientsInRangeDate($adminId, $startDate, $endDate)
    {
        try {
            $sql = "SELECT COUNT(*) AS patient_count,added_on
            FROM `patient_details` 
            WHERE `admin_id` = '$adminId' 
            AND `visited` = '1' AND `lab_visited` = '1'
            AND `added_on` BETWEEN '$startDate' AND '$endDate' GROUP BY added_on";

            $result = $this->conn->query($sql);
            if ($result !== false) {
                $rows = [];
                while ($row = $result->fetch_object()) {
                    $rows[] = $row;
                }
                return $rows;
            } else {
                return [];
            }
        } catch (Exception $e) {
            $e->getMessage();
            return [];
        }
    }
}//end class
