<?php
require_once CLASS_DIR . 'encrypt.inc.php';
class Admin extends DatabaseConnection
{



    function registration($adminId, $Fname, $Lname, $username, $password, $email, $mobNo, $added_on)
    {

        $password = pass_enc($password, ADMIN_PASS);

        try {
            $query = "INSERT INTO `admin` (`admin_id`, `fname`, `lname`, `username`, `password`, `email`, `mobile_no`, `added_on`) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
            $stmt = $this->conn->prepare($query);

            // Bind parameters
            $stmt->bind_param("ssssssss", $adminId, $Fname, $Lname, $username, $password, $email, $mobNo, $added_on);

            if ($stmt->execute()) {
                // Registration was successful
                return "Registration successful";
            } else {
                // Registration failed
                return "Registration failed";
            }
        } catch (Exception $e) {
            // Handle any exceptions that may occur
            return "Error: " . $e->getMessage();
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



    function updateAdminDetails($fname, $lname, $img, $username, $password, $email, $mobNo, $address, $updatedOn, $adminid){
        $updateQuery = "UPDATE `admin` SET `fname`='$fname',`lname`='$lname',`adm_img`='$img',`username`='$username',`password`='$password',`email`='$email',`mobile_no`='$mobNo',`address`='$address', `updated_on`='$updatedOn' WHERE `admin_id`='$adminid'";
    }
} //eof Admin Class
