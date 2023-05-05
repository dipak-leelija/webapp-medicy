<?php
require_once 'dbconnect.php';


class Distributor extends DatabaseConnection{


    
    function addDistributor($distributorName, $distributorAddress, $distributorAreaPIN, $distributorPhno, $distributorEmail, $distributorDsc){
        $insert = "INSERT INTO distributor (`name`, `address`, `area_pin_code`, `phno`, `email`, `dsc`) VALUES ('$distributorName', '$distributorAddress', '$distributorAreaPIN', '$distributorPhno', '$distributorEmail', '$distributorDsc')";

        // echo $insert.$this->conn->error;
        // exit;

        $insertQuery    =   $this->conn->query($insert);
        // echo $insert.$this->conn->error;
        // echo var_dump($insertQuery);
        // exit;
        
        return $insertQuery;
    }// end addLabTypes function


    function distrubutorDetail($DistributorId){
        $select = "SELECT  `name` FROM `distributor` WHERE `id` = '$DistributorId' ";
        $selectQuery = $this->conn->query($select);

        return $selectQuery;
    }

    function showDistributor(){ 
        $select         = " SELECT * FROM distributor";
        $selectQuery    = $this->conn->query($select);
        $rows           = $selectQuery->num_rows;
        if($rows == 0){
            return 0;
        }else{
            while ($result  = $selectQuery->fetch_array() ) {
                $data[] = $result;
            }
            return $data;
        }
        
    }//eof showDistributor functiion

    function showDistributorById($distributorId){
        $data = array();
        $select         = " SELECT * FROM `distributor` WHERE `distributor`.`id`= '$distributorId'";
        $selectQuery    = $this->conn->query($select);
        while ($result  = $selectQuery->fetch_array() ) {
            $data[] = $result;
        }
        return $data;
    }//eof showDistributorById functiion



    function selectDistributorByName($distributorName){
        print_r($distributorName);
        $select         = " SELECT * FROM `distributor` WHERE `distributor`.`name`= '$distributorName'";
        $selectQuery    = $this->conn->query($select);
        while ($result  = $selectQuery->fetch_array() ) {
            $data[] = $result;
        }
        return $data;
    }//eof showDistributorById functiion




    function updateDist($distributorName, $distributorAddress, $distributorAreaPIN, $distributorPhno, $distributorEmail, $distributorDsc, $distributorId){

        $update = "UPDATE  `distributor` SET `name` = '$distributorName', `address` = '$distributorAddress', `area_pin_code` = '$distributorAreaPIN', `phno` = '$distributorPhno', `email` = '$distributorEmail', `dsc` = '$distributorDsc' WHERE `distributor`.`id` = '$distributorId'";
        $updatedQuery = $this->conn->query($update);
        return $updatedQuery;

    }// end updateManufacturer function





    function deleteDist($distributorId){

        $Delete = "DELETE FROM `distributor` WHERE `distributor`.`id` = '$distributorId'";
        $DeleteQuey = $this->conn->query($Delete);
        return $DeleteQuey;

    }//end deleteManufacturer function




    

}//end of LabTypes Class


?>