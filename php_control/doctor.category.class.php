<?php

// ini_set('display_errors', '1');
// ini_set('display_startup_errors', '1');
// error_reporting(E_ALL);

require_once 'dbconnect.php';



class DoctorCategory extends DatabaseConnection{



    function addDoctorCategory($docCatNme, $docDesc){

        $selectDocCat = "INSERT INTO doctor_category (`category_name`, `category_descreption`) VALUES ('$docCatNme', '$docDesc')";
        $selectDocCatQuery = $this->conn->query($selectDocCat);
        // echo var_dump($selectDocCatQuery);
        return $selectDocCatQuery;

    }// end addDoctor function




    function showDoctorCategory(){
        $selectDoctorCategory = "SELECT * FROM `doctor_category`";
        $selectDoctorCategoryQuery = $this->conn->query($selectDoctorCategory);
        $row = $selectDoctorCategoryQuery->num_rows;
        if ($row == 0) {
            return 0;
        }else{
            while ($result = $selectDoctorCategoryQuery->fetch_array()) {
                $ctegoryData[] = $result;
            }
            return $ctegoryData;
        }

    }//end showDoctorCategoryById function







    function showDoctorCategoryById($docSpecialization){
        $selectDoctorCategoryById = "SELECT * FROM `doctor_category` WHERE `doctor_category`.`doctor_category_id`='$docSpecialization'";
        $selectDoctorCategoryByIdQuery = $this->conn->query($selectDoctorCategoryById);
        while ($result = $selectDoctorCategoryByIdQuery->fetch_array()) {
            $categoryDataById[] = $result;
        }
        return $categoryDataById;
    }//end showDoctorCategoryById function





    function updateDocCateory($docCatName, $docCatDesc, /*Last Variable for id which one you want to update */$updateDocCatId){

        $editDocCat = "UPDATE  `doctor_category` SET `category_name` = '$docCatName', `category_descreption`= '$docCatDesc' WHERE `doctor_category`.`doctor_category_id` = '$updateDocCatId'";
        $editQuery = $this->conn->query($editDocCat);
        return $editQuery;
        
    }





    function deleteDocCat($deleteDocCatId){

        $deleteDocCat = "DELETE FROM `doctor_category` WHERE `doctor_category`.`doctor_category_id` = '$deleteDocCatId'";

        $deleteDocCatQuery = $this->conn->query($deleteDocCat);

        return $deleteDocCatQuery;

    }// end deleteDocCat function







}





?>