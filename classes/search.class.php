<?php

class Search
{
    use DatabaseConnection;

    function searchForSale($data)
    {
        $res = array();
        $searchSql = "SELECT * FROM `products` WHERE `products`.`name` LIKE '%$data%'";
        $query     = $this->conn->query($searchSql);
        while ($result = $query->fetch_assoc()) {
            $res = $result;
        }
        return $res;
    }



    function searchCustomer($data)
    {
        $res = array();
        $searchSql = "SELECT * FROM `patient_details` WHERE `patient_details`.`name` LIKE '%$data%' OR `patient_details`.`phno` LIKE '%$data%'";
        $query     = $this->conn->query($searchSql);
        while ($result = $query->fetch_array()) {
            $res[] = $result;
        }
        return $res;
    }



    function searchCustomerByAdmin($data, $adminId)
    {
        $res = array();
        $searchSql = "SELECT * FROM `patient_details` WHERE (`patient_details`.`name` LIKE '%$data%' OR `patient_details`.`phno` LIKE '%$data%') AND `patient_details`.`admin_id`= '$adminId'";
        $query     = $this->conn->query($searchSql);
        while ($result = $query->fetch_array()) {
            $res[] = $result;
        }
        return $res;
    }



    function searchFor($table, $column, $data)
    {
        $res = array();
        $searchSql = "SELECT * FROM `$table` WHERE `$table`.`$column` LIKE '%$data%'";
        $query     = $this->conn->query($searchSql);
        while ($result = $query->fetch_array()) {
            $res[] = $result;
        }
        return $res;
    }

}//eof Products class

// $Search = new Search();

// $result = $Search->searchCustomer("Dip");
// // echo var_dump($result).'<br>';

// foreach ($result as $row) {
//     echo $row['name'].'<br>';
// }