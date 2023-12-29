<?php
require_once CLASS_DIR . 'encrypt.inc.php';
class Admin extends DatabaseConnection
{

    function registration($adminId, $Fname, $Lname, $username, $password, $email, $mobNo, $expiry, $added_on, $status){

    $password = pass_enc($password, ADMIN_PASS);

    try {
        $insertAdmin = "INSERT INTO `admin` (`admin_id`, `fname`, `lname`, `username`, `password`, `email`, `mobile_no`, `expiry`, `added_on`, `reg_status`) VALUES ('$adminId', '$Fname', '$Lname', '$username', '$password', '$email', '$mobNo', '$expiry', '$added_on', '$status')";

        $insertQuery = $this->conn->prepare($insertAdmin);

        print_r($insertAdmin);

        if ($insertQuery->affected_rows > 0){
            
            return $insertQuery;

        }else {
            // Handle the case where the prepared statement couldn't be created
            throw new Exception("Failed to add data.");
        }

    } catch (Exception $e) {
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
            // Use prepared statements to prevent SQL injection
            $deleteFromAdmin = "DELETE FROM `admin` WHERE `admin_id` = ?";
            $deleteFromSubscription = "DELETE FROM `subscription` WHERE `admin_id` = ?";
            $deleteFromClinicInfo = "DELETE FROM `clinic_info` WHERE  `admin_id` = ?";
    
            // Use prepared statements to prevent SQL injection
            $stmt1 = $this->conn->prepare($deleteFromAdmin);
            $stmt2 = $this->conn->prepare($deleteFromSubscription);
            $stmt3 = $this->conn->prepare($deleteFromClinicInfo);
    
            // Bind parameters
            $stmt1->bind_param("s", $adminId);
            $stmt2->bind_param("s", $adminId);
            $stmt3->bind_param("s", $adminId);
    
            // Execute queries
            $stmt1->execute();
            $stmt2->execute();
            $stmt3->execute();
    
            if ($stmt1->error || $stmt2->error || $stmt3->error) {
                throw new Exception("Error executing query: " . $this->conn->error);
            }
    
            if ($stmt1->affected_rows > 0 || $stmt2->affected_rows > 0 || $stmt3->affected_rows > 0) {
                return true;
            } else {
                throw new Exception("No records deleted. Admin ID not found.");
            }
    
        } catch (Exception $e) {
            
            error_log("Error deleting admin data: " . $e->getMessage());
    
            return $e->getMessage();
        }
    }
    
    
} //eof Admin Class
