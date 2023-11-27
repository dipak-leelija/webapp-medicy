<?php

class Employees extends DatabaseConnection
{


    function addEmp($adminId, $empUsername, $empName, $empRole, $empMail, $empAddress, $empPass)
    {
        try {
            $sql = "INSERT INTO `employees` (admin_id, emp_username, emp_name, emp_role, emp_email, emp_address, emp_password) VALUES (?, ?, ?, ?, ?, ?, ?)";

            $stmt = $this->conn->prepare($sql);
            if (!$stmt) {
                throw new Exception("Error preparing insert statement: " . $this->conn->error);
            }

            $stmt->bind_param("sssssss", $adminId, $empUsername, $empName, $empRole, $empMail, $empAddress, $empPass);

            if ($stmt->execute()) {
                return ["result" => true];
            } else {
                throw new Exception("Error executing insert statement: " . $stmt->error);
            }
        } catch (Exception $e) {
            return ["result" => false, "error" => $e->getMessage()];
        }
    }







    function employeesDisplay($adminId){
        $empData = array();
        $selectEmp = "SELECT emp_id,emp_username,emp_name,emp_role,emp_email FROM employees WHERE `admin_id` = '$adminId'";

        $empQuery = $this->conn->query($selectEmp);

        while ($result = $empQuery->fetch_array()) {
            $empData[] = $result;
        }

        return $empData;
    } //end employeesDisplay function





    function selectEmpByCol($col, $data){
        try {
            $selectEmp = "SELECT * FROM employees WHERE `$col` = ?";

            $stmt = $this->conn->prepare($selectEmp);

            if (!$stmt) {
                throw new Exception("Prepare statement failed.");
            }

            $stmt->bind_param("s", $data);
            $stmt->execute();

            $result = $stmt->get_result();

            if($result->num_rows > 0){
                $empData = array();
                while ($row = $result->fetch_object()) {
                    $empData[] = $row;
                }
                $stmt->close();
                return json_encode(['status' => '1', 'message' => 'success', 'data' => $empData]);
            }else{
                return json_encode(['status' => '0', 'message' => '', 'data' => '']);
            }
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }





    function employeesDisplayByUsername($empUsername){

        $select = "SELECT id,employee_username,employee_name,emp_role FROM employees WHERE employee_username = '$empUsername'";

        $query = $this->conn->query($select);

        while ($result = $query->fetch_array()) {

            $data = $result;
        }

        return $data;
    } //end selectAppointments function





    function empDisplayById($empId){
        $select = "SELECT * FROM employees WHERE emp_id = '$empId'";
        $query = $this->conn->query($select);
        while ($result = $query->fetch_object()) {
            $data = $result;
        }
        $data = json_encode($data);
        return $data;
    } //end empDisplayById function





    function empDisplayByAdminAndEmpId($empId, $admin) {
        try {
            $select = "SELECT * FROM employees WHERE emp_id = ? AND `admin_id` = ?";

            $stmt = $this->conn->prepare($select);

            if (!$stmt) {
                throw new Exception("Error in preparing statement: " . $this->conn->error);
            }

            $stmt->bind_param("ss", $empId, $admin);

            $stmt->execute();

            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                $data[] = array();
                while($resultData = $result->fetch_object()){
                    $data = $resultData;
                }
                return json_encode($data);
            }else{
                return null;
            }

            
        } catch (Exception $e) {
            return $e->getMessage();
        } finally {
            $stmt->close();
        }
    }





    
    function updateEmp($empUsername, $empName, $empRole, $empEmail,/*Last Variable for id which one you want to update */ $empId){
        $edit = "UPDATE  `employees` SET `emp_username` = '$empUsername', `emp_name`= '$empName', `emp_role` = '$empRole', `emp_email` = '$empEmail' WHERE `employees`.`emp_id` = '$empId'";
        $editQuery = $this->conn->query($edit);
        
        return $editQuery;
    } //end updateEmp function






    function deleteEmp($deleteEmpId){
        $delEmp = "DELETE FROM `employees` WHERE `employees`.`emp_id` = '$deleteEmpId'";
        $delEmpQuery = $this->conn->query($delEmp);
        return $delEmpQuery;
    } 




    

    
}//end class
