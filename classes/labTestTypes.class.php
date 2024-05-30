<?php

class LabTestTypes extends UtilityFiles
{

    use DatabaseConnection;


    function addLabTypes($image, $testName, $testPvdBy, $testDsc)
    {
        try {
            $addLabType = "INSERT INTO `tests_types` (`image`, `test_type_name`, `provided_by`, `dsc`) VALUES (?, ?, ?, ?)";
            $stmt = $this->conn->prepare($addLabType);

            if ($stmt === false) {
                throw new Exception('Prepare failed: ' . $this->conn->error);
            }

            $stmt->bind_param('ssss', $image, $testName, $testPvdBy, $testDsc);

            $result = $stmt->execute();

            if ($stmt->affected_rows > 0) {
                $response = ['status' => true, 'message' => 'success'];
            } else {
                $response = ['status' => false, 'message' => 'Data insertion fails'];
            }

            $stmt->close();
            return json_encode($response);
        } catch (Exception $e) {
            return json_encode(['status' => false, 'message' => 'Error: ' . $e->getMessage()]);
        }
    }






    function showLabTypes()
    {
        try {
            $data = [];
            $selectLabType = "SELECT * FROM `tests_types`";
            $labTypeQuery = $this->conn->query($selectLabType);
            $rows = $labTypeQuery->num_rows;
            if ($rows == 0) {
                $data = [];
            } else {
                while ($result = $labTypeQuery->fetch_array()) {
                    $data[] = $result;
                }
                return json_encode(['status' => 1, 'message' => 'success', 'data' => $data]);
            }
        } catch (Exception $e) {
            $e->getMessage();
        }
    } // end showLabTypes function




    function searchLabTest($search)
    {
        try {
            $data = [];
            $searchLabTestData = "SELECT * FROM `tests_types` WHERE `test_type_name` LIKE '%$search%'";
            $stmt = $this->conn->prepare($searchLabTestData);

            if ($stmt) {
                $stmt->execute();
                $result = $stmt->get_result();

                if ($result->num_rows > 0) {
                    while ($resultData = $result->fetch_array()) {
                        $data[] = $resultData;
                    }
                }
                $stmt->close();
                return json_encode(['status' => 1, 'data' => $data]);
            } else {
                throw new Exception("Failed to prepare statement");
            }
        } catch (Exception $e) {
            return json_encode(['status' => 0, 'message' => $e->getMessage()]);
        }
    } // end searchLabTest function





    function showLabTypesById($showLabtypeId)
    {
        try {
            $data = [];
            $selectLabType = "SELECT * FROM tests_types WHERE `tests_types`.`id` = '$showLabtypeId'";
            $labTypeQuery = $this->conn->query($selectLabType);
            while ($result = $labTypeQuery->fetch_array()) {
                $data[] = $result;
            }
            return $data;
        } catch (Exception $e) {
            $e->getMessage();
        }
    } // end showLabTypesById function



    function updateLabTypes($testTypeName, $pvdBy, $dsc, /*Last Veriable to select the id of the lab tyoe whichi we wants to delete*/ $updateLabType)
    {
        $editLabType = "UPDATE  `tests_types` SET `test_type_name` = '$testTypeName', `provided_by`='$pvdBy', `dsc`= '$dsc' WHERE `tests_types`.`id` = '$updateLabType'";
        $editLabTypeQuery = $this->conn->query($editLabType);
        // echo $editLabType.$this->conn->error;
        // exit;
        return $editLabTypeQuery;
    } // end editLabTypes function





    // function deleteLabTypes($delTestTypeId){

    //     $deletelabType = "DELETE FROM `tests_types` WHERE `tests_types`.`id` = '$delTestTypeId'";
    //     $deletelabQuery = $this->conn->query($deletelabType);
    //     return $deletelabQuery;
    // } // end deleteLabTypes function

    function deleteLabTypes($delTestTypeId)
    {
        try {

            $imgdelete = $this->deleteFile($delTestTypeId, 'id', 'image', 'tests_types', LABTEST_IMG_DIR);
            if ($imgdelete) {
                $deletelabType = "DELETE FROM `tests_types` WHERE `id` = ?";

                $stmt = $this->conn->prepare($deletelabType);

                if ($stmt === false) {
                    throw new Exception('Statement preparation failed: ' . $this->conn->error);
                }

                $stmt->bind_param("i", $delTestTypeId);

                $stmt->execute();

                if ($stmt->error) {
                    throw new Exception('Statement execution failed: ' . $stmt->error);
                }

                if ($stmt->affected_rows > 0) {
                    return json_encode(['status' => true, 'message' => 'success']);
                } else {
                    return json_encode(['status' => false, 'message' => 'Image Deleted But Details Not Deleted!']);
                }

                $stmt->close();
            } else {
                return json_encode(['status' => false, 'message' => 'Image Not Deleted!']);
            }
        } catch (Exception $e) {
            error_log($e->getMessage()); // Log the error message
            return false; // Return false in case of error
        }
    } // end deleteLabTypes function


} //end of LabTypes Class
