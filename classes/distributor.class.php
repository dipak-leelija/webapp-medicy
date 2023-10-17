<?php
require_once 'dbconnect.php';


class Distributor extends DatabaseConnection{


    function addDistributor($distributorName, $distributorAddress, $distributorAreaPIN, $distributorPhno, $distributorEmail, $distributorDsc, $addedBy, $addedOn, $adminId) {
        try {
            // Define the SQL query using a prepared statement
            $insert = "INSERT INTO distributor (`name`, `address`, `area_pin_code`, `phno`, `email`, `dsc`, `added_by`, `added_on`, `admin_id`) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
            
            // Prepare the SQL statement
            $stmt = $this->conn->prepare($insert);
    
            if ($stmt) {
                // Bind the parameters
                $stmt->bind_param("ssiisssss", $distributorName, $distributorAddress, $distributorAreaPIN, $distributorPhno, $distributorEmail, $distributorDsc, $addedBy, $addedOn, $adminId);
    
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
    




    function updateDist($distributorName, $distributorAddress, $distributorAreaPIN, $distributorPhno, $distributorEmail, $distributorDsc, $updatedBy, $updatedOn, $distributorId) {
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
        $select         = " SELECT * FROM `distributor` WHERE `distributor`.`name`= '$distributorName'";
        $selectQuery    = $this->conn->query($select);
        while ($result  = $selectQuery->fetch_array() ) {
            $data[] = $result;
        }
        return $data;
    }//eof showDistributorByName functiion



    



    function deleteDist($distributorId){

        $Delete = "DELETE FROM `distributor` WHERE `distributor`.`id` = '$distributorId'";
        $DeleteQuey = $this->conn->query($Delete);
        return $DeleteQuey;

    }//end deleteManufacturer function




    

}//end of LabTypes Class


?>