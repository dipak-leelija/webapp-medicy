<?php
    require_once 'dbconnect.php';

class Designation extends DatabaseConnection{

    function designationRole(){

        $sql = "SELECT * FROM `employees` ";
        $result = $this->conn->query($sql);

        $data = array();

        if($result->num_rows > 0){
            while($row = $result->fetch_object()){
                $row->emp_role = strtoupper($row->emp_role);
                $data[]= $row;
            }
            return json_encode($data);
        }
        return $data;
    }
    
}



?>