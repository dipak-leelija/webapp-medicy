<?php
require_once CLASS_DIR . 'encrypt.inc.php';
class Admin extends DatabaseConnection
{



    function registration($adminId, $Fname, $Lname, $username, $password, $email, $mobNo, $expiry, $added_on, $status)
    {

        $password = pass_enc($password, ADMIN_PASS);

        try {
            $query = "INSERT INTO `admin` (`admin_id`, `fname`, `lname`, `username`, `password`, `email`, `mobile_no`, `expiry`, `added_on`, `reg_status`) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
            $stmt = $this->conn->prepare($query);

            // Bind parameters
            $stmt->bind_param("ssssssssss", $adminId, $Fname, $Lname, $username, $password, $email, $mobNo, $expiry, $added_on, $status);

            if ($stmt->execute()) {
                
                return 1;
            } else {
                // Registration failed
                return 0;
            }
        } catch (Exception $e) {
            // Handle any exceptions that may occur
            return "Error => " . $e->getMessage();
        }
    }





    function adminDetails($adminId){
        try{
            $chkUser = " SELECT * FROM `admin` WHERE `admin_id`= '$adminId' ";

            $stmt = $this->conn->prepare($chkUser);

            $stmt->execute();

            $res = $stmt->get_result();

            if ($res->num_rows > 0) {
                $data = array();
                while ($result = $res->fetch_object()) {
                    $adminData[] = $result;
                }
                $stmt->close();
                return json_encode(['status'=>'1', 'message'=>'success', 'data'=>$adminData]);
            } else {
                $stmt->close();
                return json_encode(['status'=>'0', 'message'=>'no data', 'data'=> '']);
            }
        } catch (Exception $e) {
            return json_encode(['status'=>'', 'message'=>$e->getMessage(), 'data'=> '']);
        }
        return 0;
    } //eof CheckEmail






    function echeckUsername($username)
    {
        $chkUser = " SELECT * FROM `admin` WHERE `username`= '$username' ";
        $chkUserQuery = $this->conn->query($chkUser);
        // echo $chkUserQuery.$this->conn->error;
        // echo count($chkUserQuery);
        if ($chkUserQuery->num_rows > 0) {

            while ($result = $chkUserQuery->fetch_array()) {
                $data[] = $result;
            }
            if ($data > 0) {
                return 1;
            } else {
                return 0;
            }
        }
    } //eof CheckEmail





    function echeckEmail($email)
    {
        $chkEmail = " SELECT * FROM `admin` WHERE `email`= '$email' ";
        $chkEmailQuery = $this->conn->query($chkEmail);

        if ($chkEmailQuery->num_rows > 0) {
            while ($result = $chkEmailQuery->fetch_array()) {
                $data[] = $result;
            }
            if ($data > 0) {
                return 1;
            } else {
                return 0;
            }
        }
    } //eof CheckEmail function




    function login($email)
    {
        $login = "SELECT * FROM `admin` WHERE `email` = '$email'";
        $loginQuery = $this->conn->query($login);
        // echo var_dump($loginQuery);
        // exit;
        if ($loginQuery == false) {
            return FALSE;
        }
        if ($loginQuery != FALSE) {
            while ($result = $loginQuery->fetch_array()) {
                $data[] = $result;
            }
            return $data;
        }
    }





    function updateAdminStatus($admId, $status) {
        try {
            $updateQuery = "UPDATE `admin` SET `reg_status`=? WHERE `admin_id`=?";
            
            $stmt = $this->conn->prepare($updateQuery);
    
            $stmt->bind_param("is", $status, $admId);
    
            $stmt->execute();
    
            $stmt->close();
    
            return ['result' => '1'];

        } catch (Exception $e) {
            return ['result' => '0', 'message' => $e->getMessage()];
        }
    }





    function updateAdminDetails($fname, $lname, $img, $email, $mobNo, $address, $updatedOn, $adminid) {
        try {
            $updateQuery = "UPDATE `admin` SET `fname`=?, `lname`=?, `adm_img`=?, `email`=?, `mobile_no`=?, `address`=?, `updated_on`=? WHERE `admin_id`=?";
            
            $stmt = $this->conn->prepare($updateQuery);
    
            $stmt->bind_param("ssssssss", $fname, $lname, $img, $email, $mobNo, $address, $updatedOn, $adminid);
    
            $stmt->execute();
    
            $stmt->close();
    
            return ['result' => '1'];
        } catch (Exception $e) {
            return ['result' => '0', 'message' => $e->getMessage()];
        }
    }




    function updateAdminPassword($newPass, $adminid){
        $password = pass_enc($newPass, ADMIN_PASS);

        try{
            $updatePass = "UPDATE `admin` SET `password`=? WHERE `admin_id`=?";

            $stmt = $this->conn->prepare($updatePass);
    
            $stmt->bind_param("ss", $password, $adminid);

            $stmt->execute();
    
            $stmt->close();

            return ['result' => '1'];

        }catch(Exception $e){
            return json_encode(['status'=> '0', 'message'=>$e->getMessage(), 'data'=> '']);
        }
    }
    




    // admin delete function

    function deleteAdminData($adminId) {
        try {
            $deleteAdminQuery = "DELETE FROM `admin` WHERE `admin_id` = '$adminId'";
            $query = $this->conn->query($deleteAdminQuery);
    
            if ($query === false) {
                throw new Exception("Error executing query: " . $this->conn->error);
            }
    
            return true;
            
        } catch (Exception $e) {
            // return false;
            return $e->getMessage();
        }
    }
    
} //eof Admin Class
