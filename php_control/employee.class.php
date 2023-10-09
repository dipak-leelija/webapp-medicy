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





    function employeesDisplayByTables($table, $data) {
        try {
            $selectEmp = "SELECT * FROM employees WHERE `$table` = ?";
            
            // Use prepared statement for security
            $stmt = $this->conn->prepare($selectEmp);
            
            if (!$stmt) {
                throw new Exception("Prepare statement failed.");
            }
            
            // Bind the parameter and execute the query
            $stmt->bind_param("s", $data);
            $stmt->execute();
    
            // Get the result set
            $result = $stmt->get_result();
            
            $empData = array();
            
            while ($row = $result->fetch_assoc()) {
                $empData[] = $row;
            }
            
            $stmt->close();
    
            return $empData;
        } catch (Exception $e) {
            // Handle exceptions, log errors, or return an empty array as needed
            return array();
        }
    }
    



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
       


    function showDesignation() {
        try {
            $sql = "SELECT * FROM designation";
            $stmt = $this->conn->prepare($sql);
    
            if (!$stmt) {
                throw new Exception("Prepare statement failed.");
            }
    
            if (!$stmt->execute()) {
                throw new Exception("Database query execution failed: " . $stmt->error);
            }
    
            $result = $stmt->get_result();
    
            if (!$result) {
                throw new Exception("Result retrieval failed: " . $stmt->error);
            }
    
            return $result;
        } catch (Exception $e) {
            // Handle exceptions, log errors, or return an error message as needed
            return false;
        }
    }
    



}//end class
