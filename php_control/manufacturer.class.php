<?php
require_once 'dbconnect.php';
 

class Manufacturer extends DatabaseConnection{

    function addManufacturer( $manufacturerName, $manufacturerDsc){
        $insert = "INSERT INTO manufacturer (`name`, `dsc`) VALUES ('$manufacturerName', '$manufacturerDsc')";

        $insertQuery    =   $this->conn->query($insert);

        return $insertQuery;
    }// end addManufacturer function



    function showManufacturer(){
        $select         = " SELECT * FROM manufacturer";
        $selectQuery    = $this->conn->query($select);
        while ($result  = $selectQuery->fetch_array() ) {
            $data[] = $result;
        }
        return $data;
    }//eof showManufacturer functiion





    function showManufacturerById($manufacturerId){
        $select         = " SELECT * FROM manufacturer WHERE `manufacturer`.`id` = '$manufacturerId'";
        $selectQuery    = $this->conn->query($select);
        $row = $selectQuery->num_rows;
        if ($row == 0) {
            return 0;
        }else {
            while ($result  = $selectQuery->fetch_array() ) {
                $data[] = $result;
            }
            return $data;
        }
    }//eof showManufacturerById functiion



    // function showManufacturerByDistributorId($distributorId){
    //     $select         = " SELECT * FROM manufacturer WHERE `manufacturer`.`distributor_id` ='$distributorId'";
    //     $selectQuery    = $this->conn->query($select);
    //     while ($result  = $selectQuery->fetch_array() ) {
    //         $data[] = $result;
    //     }
    //     return $data;
    // }//eof showManufacturer functiion




    function updateManufacturer($manufacturerName, $manufacturerDsc, $manufacturerId){

        $update = "UPDATE  `manufacturer` SET `name` = '$manufacturerName', `dsc` = '$manufacturerDsc' WHERE `manufacturer`.`id` = '$manufacturerId'";
        $updatedQuery = $this->conn->query($update);
        return $updatedQuery;

    }// end updateManufacturer function



    function deleteManufacturer($manufacturerId){

        echo $manufacturerId;

        $Delete = "DELETE FROM `manufacturer` WHERE `manufacturer`.`id` = '$manufacturerId'";
        $DeleteQuey = $this->conn->query($Delete);
        return $DeleteQuey;

    }//end deleteManufacturer function







    

}//end of LabTypes Class


?>