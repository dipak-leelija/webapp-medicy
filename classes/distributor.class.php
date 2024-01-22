<?php
require_once 'dbconnect.php';


class Distributor extends DatabaseConnection
{


    function addDistributor($distributorName, $distributorGSTID, $distributorAddress, $distributorAreaPIN, $distributorPhno, $distributorEmail, $distributorDsc, $addedBy, $addedOn, $distributorStatus, $adminId)
    {
        try {
            // Define the SQL query using a prepared statement
            $insert = "INSERT INTO distributor (`name`, `gst_id`, `address`, `area_pin_code`, `phno`, `email`, `dsc`, `added_by`, `added_on`,`dis_status`,`admin_id`) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?,?,?)";

            // Prepare the SQL statement
            $stmt = $this->conn->prepare($insert);

            if ($stmt) {
                // Bind the parameters
                $stmt->bind_param("sssisssssis", $distributorName, $distributorGSTID, $distributorAddress, $distributorAreaPIN, $distributorPhno, $distributorEmail, $distributorDsc, $addedBy, $addedOn, $distributorStatus, $adminId);

                // Execute the query
                $insertQuery = $stmt->execute();
                $stmt->close();
                return $insertQuery;
            } else {
                throw new Exception("Failed to prepare the statement.");
            }
        } catch (Exception $e) {
            // Handle any exceptions that occur
            // Customize this part to suit your needs
            echo "Error: " . $e->getMessage();
            return false;
        }
    }


    function updateDist($distributorName, $distributorAddress, $distributorAreaPIN, $distributorPhno, $distributorEmail, $distributorDsc, $updatedBy, $updatedOn, $distributorId)
    {
        try {
            // Define the SQL query using a prepared statement
            $update = "UPDATE `distributor` SET `name`=?, `address`=?, `area_pin_code`=?, `phno`=?, `email`=?, `dsc`=?, `updated_by`=?, `updated_on`=? WHERE `id`=?";

            // Prepare the SQL statement
            $stmt = $this->conn->prepare($update);

            if ($stmt) {
                // Bind the parameters
                $stmt->bind_param("ssssssssi", $distributorName, $distributorAddress, $distributorAreaPIN, $distributorPhno, $distributorEmail, $distributorDsc, $updatedBy, $updatedOn, $distributorId);

                // Execute the query
                $updatedQuery = $stmt->execute();
                $stmt->close();
                return $updatedQuery;
            } else {
                throw new Exception("Failed to prepare the statement.");
            }
        } catch (Exception $e) {
            // Handle any exceptions that occur
            // Customize this part to suit your needs
            echo "Error: " . $e->getMessage();
            return false;
        }
    }



    function updateDistStatus($status, $distributorId)
    {
        try {
            $update =  "UPDATE `distributor` SET `dis_status`=? WHERE `id`=?";
            $stmt = $this->conn->prepare($update);

            if ($stmt) {
                // Bind the parameters
                $stmt->bind_param("ii", $status, $distributorId);

                // Execute the query
                $updatedQuery = $stmt->execute();
                $stmt->close();
                return $updatedQuery;
            } else {
                throw new Exception("Failed to prepare the statement.");
            }
        } catch (Exception $e) {
            echo "Error: " . $e->getMessage();
            return false;
        }
    }


    function distributorName($DistributorId)
    {
        try {
            $select = "SELECT `name` FROM `distributor` WHERE `id` = ?";
            $stmt = $this->conn->prepare($select);

            if ($stmt) {
                $stmt->bind_param("i", $DistributorId);
                $stmt->execute();
                $result = $stmt->get_result();

                if ($result->num_rows > 0) {
                    $row = $result->fetch_object();
                    $data = $row->name;
                    // $row = json_encode($row);
                } else {
                    echo "Query returned no results.";
                }
                $stmt->close();
            } else {
                echo "Statement preparation failed: " . $this->conn->error;
            }
            return $data;
        } catch (Exception $e) {
            echo "Error: " . $e->getMessage();
            return null;
        }
    }



    function showDistributor()
    {
        try {
            $select = "SELECT * FROM distributor";
            $selectQuery = $this->conn->prepare($select);

            if (!$selectQuery) {
                throw new Exception("Error preparing the query: " . $this->conn->error);
            }

            $selectQuery->execute();

            if ($selectQuery->error) {
                throw new Exception("Error executing the query: " . $selectQuery->error);
            }

            $result = $selectQuery->get_result();

            while ($row = $result->fetch_assoc()) {
                $data[] = $row;
            }

            if (empty($data)) {
                return json_encode(['status' => 0, 'message' => 'empty', 'data' => '']);
            }

            return json_encode(['status' => 1, 'message' => 'success', 'data' => $data]);
        } catch (Exception $e) {
            return json_encode(['status' => 0, 'message' => 'Error: ' . $e->getMessage(), 'data' => '']);
        }
    }

    function showDistributorById($distributorId)
    {
        try {
            $select         = " SELECT * FROM `distributor` WHERE `distributor`.`id`= '$distributorId'";
            $selectQuery    = $this->conn->query($select);
            if ($selectQuery->num_rows > 0) {
                $data = array();
                while ($result  = $selectQuery->fetch_assoc()) {
                    $data = $result;
                }
                return json_encode(['status' => '1', 'message' => 'success', 'data' => $data]);
            } else {
                return json_encode(['status' => '0', 'message' => 'empty', 'data' => array()]);
            }
        } catch (Exception $e) {
            return json_encode(['status' => ' ', 'message' => $e->getMessage(), 'data' => '']);
        }
    } //eof showDistributorById functiion



    function selectDistributorByName($distributorName)
    {
        $select         = " SELECT * FROM `distributor` WHERE `distributor`.`name`= '$distributorName'";
        $selectQuery    = $this->conn->query($select);
        while ($result  = $selectQuery->fetch_array()) {
            $data[] = $result;
        }
        return $data;
    } //eof showDistributorByName functiion



    function distributorSearch($match)
    {
        try {
            if ($match == 'all') {

                $select = "SELECT * FROM `distributor` LIMIT 6";
                $stmt = $this->conn->prepare($select);
            } else {

                $select = "SELECT * FROM `distributor` WHERE 
                       `name` LIKE CONCAT('%', ?, '%') OR 
                       `id` LIKE CONCAT('%', ?, '%') OR 
                       `address` LIKE CONCAT('%', ?, '%')";
                $stmt = $this->conn->prepare($select);
            }


            if ($stmt) {
                if ($match != 'all') {
                    $stmt->bind_param("sss", $match, $match, $match);
                }

                $stmt->execute();
                $result = $stmt->get_result();

                if ($result->num_rows > 0) {

                    while ($row = $result->fetch_object()) {
                        $data[] = $row;
                    }

                    return json_encode(['status' => 1, 'message' => 'success', 'data' => $data]);
                } else {
                    return json_encode(['status' => 0, 'message' => 'empty', 'data' => '']);
                }
                $stmt->close();
            } else {
                return json_encode(['status' => 0, 'message' => "Statement preparation failed: " . $this->conn->error, 'data' => '']);
            }
        } catch (Exception $e) {
            return json_encode(['status' => 0, 'message' => "Error: " . $e->getMessage(), 'data' => '']);
        }
    }


    function deleteDist($distributorId)
    {

        $Delete = "DELETE FROM `distributor` WHERE `distributor`.`id` = '$distributorId'";
        $DeleteQuey = $this->conn->query($Delete);
        return $DeleteQuey;
    } //end deleteManufacturer function






} //end of LabTypes Class
