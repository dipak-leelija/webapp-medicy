<?php
require_once CLASS_DIR.'encrypt.inc.php';

class Employees
{
    use DatabaseConnection;

    function addEmp($empId, $adminId, $empUsername, $fName,  $lName, $empRole, $empMail, $contactNo, $empAddress, $empPass)
    {
        $password = pass_enc($empPass, EMP_PASS);

        try {
            $sql = "INSERT INTO `employees` (emp_id, admin_id, emp_username, fname, lname, emp_role, emp_email, contact, emp_address, emp_password) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

            $stmt = $this->conn->prepare($sql);
            if (!$stmt) {
                throw new Exception("Error preparing insert statement: " . $this->conn->error);
            }

            $stmt->bind_param("sssssssiss", $empId, $adminId, $empUsername, $fName,  $lName, $empRole, $empMail, $contactNo, $empAddress, $password);

            if ($stmt->execute()) {
                return ["result" => true];
            } else {
                throw new Exception("Error executing insert statement: " . $stmt->error);
            }
        } catch (Exception $e) {
            return ["result" => false, "error" => $e->getMessage()];
        }
    }




    function employeeDetails($empId, $adminId){
        try{
            $selectEmp = "SELECT * FROM employees WHERE `emp_id` = '$empId' AND `admin_id` = '$adminId'";

            $stmt = $this->conn->prepare($selectEmp);

            $stmt->execute();

            $res = $stmt->get_result();

            if($res->num_rows > 0){
                $empData = array();
                while ($result = $res->fetch_object()) {
                    $empData[] = $result;
                }
                $stmt->close();
                return json_encode(['status'=>'1', 'message'=>'success', 'data'=>$empData]);
            } else {
                $stmt->close();
                return json_encode(['status'=>'0', 'message'=>'no data', 'data'=> '']);
            }
        } catch(Exception $e) {
            return json_encode(['status'=>'0', 'message'=>$e->getMessage(), 'data'=> '']);
        }
    } //end employeesDisplay function




    function employeesDisplay($adminId=''){
        $empData = array();
        
        if(!empty($adminId)){
        $selectEmp = "SELECT emp_id,emp_username,fname,lname,emp_role,emp_email,updated_on FROM employees WHERE `admin_id` = '$adminId'";
        }else{
            $selectEmp = "SELECT * FROM employees ";  
        }
        $empQuery = $this->conn->query($selectEmp);

        while ($result = $empQuery->fetch_assoc()) {
            $empData[] = $result;
        }

        return $empData;
    } //end employeesDisplay function





    function selectEmpByCol($col='', $data=''){
        try {
            if (!empty($data)) {
            $selectEmp = "SELECT * FROM employees WHERE `$col` = ?";
            $stmt = $this->conn->prepare($selectEmp);
            $stmt->bind_param("s", $data);
            }else{
                $selectEmp = "SELECT * FROM employees ";  
                $stmt = $this->conn->prepare($selectEmp);
            }
            // $stmt = $this->conn->prepare($selectEmp);

            if (!$stmt) {
                throw new Exception("Prepare statement failed.");
            }

            // $stmt->bind_param("s", $data);
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
                return json_encode(['status' => '0', 'message' => 'no data found', 'data' => '']);
            }
        } catch (Exception $e) {
            return json_encode(['status' => '0', 'message' => $e->getMessage(), 'data' => '']);
        }
    }





    function selectEmpByColData($col, $data){
        try {
            $selectEmp = "SELECT * FROM employees WHERE `$col` = ?";
            $stmt = $this->conn->prepare($selectEmp);
            $stmt->bind_param("s", $data);
           

            if (!$stmt) {
                throw new Exception("Prepare statement failed.");
            }

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
                return json_encode(['status' => '0', 'message' => 'no data found', 'data' => '']);
            }
        } catch (Exception $e) {
            return json_encode(['status' => '0', 'message' => $e->getMessage(), 'data' => '']);
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






    function updateEmpData($name, $email, $contactNo, $address, $updatedOn, $empid, $adminid) {
        try {
            $updateQuery = "UPDATE `employees` SET `emp_name`=?, `emp_email`=?, `contact`=?, `emp_address`=?, `updated_on`=? WHERE `emp_id`=? AND `admin_id`=?";
            
            $stmt = $this->conn->prepare($updateQuery);
    
            $stmt->bind_param("sssssss", $name, $email, $contactNo, $address, $updatedOn, $empid, $adminid);
    
            $stmt->execute();
    
            $stmt->close();
    
            return ['result' => '1'];
        } catch (Exception $e) {
            return ['result' => '0', 'message' => $e->getMessage()];
        }
    }





    function updateEmployeePassword($newPass, $empid, $adminid){
        $password = pass_enc($newPass, EMP_PASS);

        try{
            $updateEmpPass = "UPDATE `employees` SET `emp_password`=? WHERE `emp_id`=? AND `admin_id`=?";

            $stmt = $this->conn->prepare($updateEmpPass);
    
            $stmt->bind_param("sss", $password, $empid, $adminid);

            $stmt->execute();
    
            $stmt->close();

            return ['result' => '1'];

        }catch(Exception $e){
            return ['status'=> '0', 'message'=>$e->getMessage(), 'data'=> ''];
        }
    }


    
    // function updateEmp($empUsername, $empName, $empRole, $empEmail, $empContact, /*Last Variable for id which one you want to update */ $empId){
    //     $edit = "UPDATE  `employees` SET `emp_username` = '$empUsername', `emp_name`= '$empName', `emp_role` = '$empRole', `emp_email` = '$empEmail', `contact` = '$empContact' WHERE `employees`.`emp_id` = '$empId'";

    //     $editQuery = $this->conn->query($edit);
        
    //     return $editQuery;
    // } //end updateEmp function


    function updateEmp($empUsername, $firstName, $lastName, $empRole, $empEmail, $empContact, $empId) {
        try {
            // Prepare the SQL statement with placeholders
            $edit = "UPDATE `employees` SET `emp_username` = ?, `fname` = ?, `lname` = ?, `emp_role` = ?, `emp_email` = ?, `contact` = ? WHERE `emp_id` = ?";
            $stmt = $this->conn->prepare($edit);
    
            // Bind parameters to the placeholders
            $stmt->bind_param("sssssss", $empUsername, $firstName, $lastName, $empRole, $empEmail, $empContact, $empId);
    
            // Execute the prepared statement
            $stmt->execute();
    
            // Check if the query was successful
            if ($stmt->affected_rows > 0) {
                return true; // Update successful
            } else {
                throw new Exception("Error updating employee: " . $this->conn->error);

                return false; // No rows affected, probably the ID doesn't exist
            }
        } catch (Exception $e) {

            return ['status'=> '0', 'message'=>$e->getMessage()];

        } 
        // finally {
        //     // Close the statement regardless of the outcome
        //     $stmt->close();
        // }
    }
    





    function deleteEmp($deleteEmpId){
        $delEmp = "DELETE FROM `employees` WHERE `employees`.`emp_id` = '$deleteEmpId'";
        $delEmpQuery = $this->conn->query($delEmp);
        return $delEmpQuery;
    } 

    
}//end class
