<?php

class Pathology{

    use DatabaseConnection;

    /********************************************************************************************
     *                                  Test Category Table                                     *
     ********************************************************************************************/

    // function addTestCategory($image, $testName, $testPvdBy, $testDsc)
    // {
    //     try {
    //         $addLabType = "INSERT INTO `test_category` (`name`, `dsc`, `image`, `status`, `added_on`) VALUES (?, ?, ?, ?)";
    //         $stmt = $this->conn->prepare($addLabType);

    //         if ($stmt === false) {
    //             throw new Exception('Prepare failed: ' . $this->conn->error);
    //         }

    //         $stmt->bind_param('ssss', $image, $testName, $testPvdBy, $testDsc);

    //         $result = $stmt->execute();

    //         if ($stmt->affected_rows > 0) {
    //             $response = ['status' => true, 'message' => 'success'];
    //         } else {
    //             $response = ['status' => false, 'message' => 'Data insertion fails'];
    //         }

    //         $stmt->close();
    //         return json_encode($response);
    //     } catch (Exception $e) {
    //         return json_encode(['status' => false, 'message' => 'Error: ' . $e->getMessage()]);
    //     }
    // }






    function showTestCategories()
    {
        try {
            $data = [];
            $selectLabType = "SELECT * FROM `test_category`";
            $labTypeQuery = $this->conn->query($selectLabType);
            $rows = $labTypeQuery->num_rows;

            if ($rows > 0) {
                while ($result = $labTypeQuery->fetch_array()) {
                    $data[] = $result;
                }
                return json_encode(['status' => 1, 'message' => 'success', 'data' => $data]);
            }else{
                return json_encode(['status' => 0]);
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
                    return json_encode(['status' =>1, 'data' => $data]);
                }else{
                    return json_encode(['status' =>0]);
                }
                $stmt->close();
                
            } else {
                throw new Exception("Failed to prepare statement");
            }
        } catch (Exception $e) {
            return json_encode(['status' => 0, 'message' => $e->getMessage()]);
        }
    } // end searchLabTest function





    function showLabCat($showLabtypeId)
    {
        try {
            $data = [];
            $selectLabType = "SELECT * FROM test_category WHERE `id` = '$showLabtypeId'";
            $labTypeQuery = $this->conn->query($selectLabType);
            while ($result = $labTypeQuery->fetch_assoc()) {
                $data[] = $result;
            }
            return $data;
        } catch (Exception $e) {
            $e->getMessage();
        }
    } // end showLabCat function



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

            // $imgdelete = $this->deleteFile($delTestTypeId, 'id', 'image', 'tests_types', LABTEST_IMG_DIR);
            // if ($imgdelete) {
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
            // } else {
            //     return json_encode(['status' => false, 'message' => 'Image Not Deleted!']);
            // }
        } catch (Exception $e) {
            error_log($e->getMessage()); // Log the error message
            return false; // Return false in case of error
        }
    } // end deleteLabTypes function

    
    /********************************************************************************************
     *                                      Test List Table                                     *
     ********************************************************************************************/

     
    function showTestList()
    {
        try {
            $selectTest = "SELECT * FROM `test_list`";
            $testQuery = $this->conn->query($selectTest);
            while ($result = $testQuery->fetch_array()) {
                $data[] = $result;
            }
            return $data;
        } catch (Exception $e) {
            $e->getMessage();
        }
    } // end showSubTests function

    function showTestById($testId)
    {
        $selectTestById = "SELECT * FROM test_list WHERE `id` = '$testId'";
        $subTestQuery = $this->conn->query($selectTestById);
        while ($result = $subTestQuery->fetch_assoc()) {
            $data = $result;
        }
        return $data;
    } // end showLabTypesById function

    function showTestByCat($catId)
    {
        try {
            $query = "SELECT * FROM `test_list` WHERE `cat_id` = '$catId'";

            $stmt = $this->conn->prepare($query);
            $stmt->execute();

            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                $data = [];
                while ($row = $result->fetch_assoc()) {
                    $data[] = $row;
                }
                return json_encode(['status' => true, 'message' => 'Data retrieved successfully', 'data' => $data]);
            } else {
                return json_encode(['status' => false, 'message' => 'No data found']);
            }
        } catch (Exception $e) {
            return ['status' => false, 'message' => $e->getMessage()];
        }
    }

} //end of LabTypes Class
