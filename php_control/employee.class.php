<?php

require_once 'dbconnect.php';





class Employees extends DatabaseConnection{





    function addEmp($empUsername, $empName, $empRole, $empMail, $empAddress, $empPass){
        
        $insertEmp = "INSERT INTO  employees (`employee_username`, `employee_name`, `emp_role`, `emp_email`, `emp_address`, `employee_password`) VALUES ('$empUsername', '$empName', '$empRole', '$empMail', '$empAddress', '$empPass')";
        // echo $insertEmp.$this->conn->error;
        // exit;
        $insertEmpQuery = $this->conn->query($insertEmp);
        return $insertEmpQuery;

    }//end addEmp function







    function employeesDisplay(){

        $selectEmp = "SELECT id,employee_username,employee_name,emp_role,emp_email FROM employees";

        $empQuery = $this->conn->query($selectEmp);

        while($result = $empQuery->fetch_array()){

            $empData[]	= $result;

        }

        return $empData;

    }//end employeesDisplay function







    function employeesDisplayByUsername($empUsername){

        $select = "SELECT id,employee_username,employee_name,emp_role FROM employees WHERE employee_username = '$empUsername'";

        $query = $this->conn->query($select);

        while($result = $query->fetch_array()){

            $data[]	= $result;

        }

        return $data;

    }//end selectAppointments function





    function empDisplayById($empId){
        $select = "SELECT * FROM employees WHERE id = '$empId'";
        $query = $this->conn->query($select);
        while($result = $query->fetch_array()){
            $data[]	= $result;
        }
        return $data;
    }//end empDisplayById function







   // used in emp edit from admin section
    function updateEmp($empUsername, $empName, $empRole, $empEmail,/*Last Variable for id which one you want to update */ $empId){
        $edit = "UPDATE  `employees` SET `employee_username` = '$empUsername', `employee_name`= '$empName', `emp_role` = '$empRole', `emp_email` = '$empEmail' WHERE `employees`.`id` = '$empId'";
        $editQuery = $this->conn->query($edit);
        // echo $editQuery.$this->conn->error;
        // exit;
        return $editQuery;   
    }//end updateEmp function







    function deleteEmp($deleteEmpId){
        $delEmp = "DELETE FROM `employees` WHERE `employees`.`id` = '$deleteEmpId'";
        $delEmpQuery = $this->conn->query($delEmp);
        return $delEmpQuery;
    }// end deleteDocCat function
       






}//end class









?>