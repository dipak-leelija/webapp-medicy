<?php
    require_once 'dbconnect.php';

class Designation extends DatabaseConnection{

    function designationRole(){

        $sql = "SELECT id, emp_role FROM `employees` ";
        $result = $this->conn->query($sql);

        if($result->num_rows > 0){
            while($data = $result->fetch_object()){
                $data->emp_role = strtoupper($data->emp_role);
                $json_data = json_encode($data->emp_role);
                // echo $json_data;
            }
        }
    }
}
?>