<?php
class DoctorCategory extends DatabaseConnection
{



    function addDoctorCategory($docCatName, $docDesc, $employee, $addedOn, $adminId){
        
        try {
            $insertDocCat = "INSERT INTO doctor_category (`category_name`, `category_descreption`, `added_by`,  `added_on`, `admin_id`) VALUES (?, ?, ?, ?, ?)";

            $stmt = $this->conn->prepare($insertDocCat);
            $stmt->bind_param("sssss", $docCatName, $docDesc, $employee, $addedOn, $adminId);

            if (!$stmt->execute()) {
                throw new Exception("Error in query execution: " . $stmt->error);
            }

            $stmt->close();
            return true;
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }




    function showDoctorCategory(){
        try {
            $selectDoctorCategory = "SELECT * FROM `doctor_category`";
            $selectDoctorCategoryQuery = $this->conn->query($selectDoctorCategory);
            
            if (!$selectDoctorCategoryQuery) {
                throw new Exception("Error in query: " . $this->conn->error);
            }
    
            $row = $selectDoctorCategoryQuery->num_rows;
    
            if ($row > 0) {
                while ($result = $selectDoctorCategoryQuery->fetch_array()) {
                    $categoryData[] = $result;
                }
                return $categoryData;
            } else {
                return [];
            }
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }
    





    function showDoctorCategoryByAdmin($adminId=''){
        try {
            if (!empty($adminId)) {
            $selectDoctorCategory = "SELECT * FROM `doctor_category` WHERE `admin_id` = ? ";
            $stmt = $this->conn->prepare($selectDoctorCategory);
            $stmt->bind_param("s", $adminId);
            }else{
            $selectDoctorCategory = "SELECT * FROM `doctor_category` ";
            $stmt = $this->conn->prepare($selectDoctorCategory);
            }
            // $stmt = $this->conn->prepare($selectDoctorCategory);
            // $stmt->bind_param("s", $adminId); 

            if (!$stmt->execute()) {
                throw new Exception("Error in query execution: " . $stmt->error);
            }

            $result = $stmt->get_result();
            $categoryData = [];

            while ($row = $result->fetch_assoc()) {
                $categoryData[] = $row;
            }

            $stmt->close();

            return $categoryData;
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }   








    // function showDoctorCategoryById($docSpecialization)
    // {
    //     $selectDoctorCategoryById = "SELECT * FROM `doctor_category` WHERE `doctor_category`.`doctor_category_id`='$docSpecialization'";
    //     $selectDoctorCategoryByIdQuery = $this->conn->query($selectDoctorCategoryById);
    //     while ($result = $selectDoctorCategoryByIdQuery->fetch_array()) {
    //         $categoryDataById[] = $result;
    //     }
    //     return $categoryDataById;
    // } //end showDoctorCategoryById function


    function showDoctorCategoryById($docSpecialization){
        try {
            // Use prepared statements to prevent SQL injection
            $selectDoctorCategoryById = "SELECT * FROM `doctor_category` WHERE `doctor_category`.`doctor_category_id`=?";
            $stmt = $this->conn->prepare($selectDoctorCategoryById);

            if (!$stmt) {
                throw new Exception("Prepare failed: (" . $this->conn->errno . ") " . $this->conn->error);
            }

            $stmt->bind_param("s", $docSpecialization);
            $stmt->execute();

            $result = $stmt->get_result();

            if (!$result) {
                throw new Exception("Execute failed: (" . $stmt->errno . ") " . $stmt->error);
            }

            if($result->num_rows === 1) {
                $response = $result->fetch_all(MYSQLI_ASSOC);
                $stmt->close();
                return json_encode(['status'=> 1, 'message'=> 'success', 'data' => $response]);
            }else {
                $stmt->close();
                return json_encode(['status'=> 0, 'message'=> 'empty', 'data' => '']);
            }



        } catch (Exception $e) {
            return json_encode(['status'=> 0, 'message'=> "Error: " . $e->getMessage(), 'data' => '']);
        }
    }




    function updateDocCateory($docCatName, $docCatDesc, /*Last Variable for id which one you want to update */ $updateDocCatId)
    {

        $editDocCat = "UPDATE  `doctor_category` SET `category_name` = '$docCatName', `category_descreption`= '$docCatDesc' WHERE `doctor_category`.`doctor_category_id` = '$updateDocCatId'";
        $editQuery = $this->conn->query($editDocCat);
        return $editQuery;
    }





    function deleteDocCat($deleteDocCatId)
    {
        try {
            $deleteDocCat = "DELETE FROM `doctor_category` WHERE `doctor_category`.`doctor_category_id` = '$deleteDocCatId'";

            $deleteDocCatQuery = $this->conn->query($deleteDocCat);

            return $deleteDocCatQuery;
        } catch (Exception $e) {
            echo $e->getMessage();
        }
    } // end deleteDocCat function







}
