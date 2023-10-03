<?php
    require_once 'dbconnect.php';

class Designation extends DatabaseConnection{

    function selectFromEmployees() {
        $data = array();
        $sqlSelect = "SELECT `id`, `desig_name`, `add on`, `add by` FROM `designation`";
        
        $selectQuery = $this->conn->query($sqlSelect);
        while ($result = $selectQuery->fetch_array()) {
            $data[] = $result;
        }
        return $data;
    }
    
}



?>