<?php
require_once 'dbconnect.php';
class Admin extends DatabaseConnection{



    function registration($adminId, $Fname, $Lname, $username, $password, $email, $mobNo, $address, $city) {
        try {
            $query = "INSERT INTO `admin` (`admin_id`, `fname`, `lname`, `username`, `password`, `email`, `mobile_no`, `address`, `city`) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
            $stmt = $this->conn->prepare($query);
    
            // Bind parameters
            $stmt->bind_param("sssssssss", $adminId, $Fname, $Lname, $username, $password, $email, $mobNo, $address, $city);
    
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
    




    function echeckUsername($username){
        $chkUser = " SELECT * FROM `admin` WHERE `username`= '$username' ";
        return $chkUser;
        $chkUserQuery = $this->conn->query($chkUser);
        // echo $chkUserQuery.$this->conn->error;
        // echo count($chkUserQuery);
        while($result = $chkUserQuery->fetch_array()){
            $data[] = $result;
        }
        if ($data > 0) {
            return 1;
        }else{
            return 0;
        }

    }//eof CheckEmail





    function echeckEmail($email){
        $chkEmail = " SELECT * FROM `admin` WHERE `email`= '$email' ";
        $chkEmailQuery = $this->conn->query($chkEmail);

        while($result = $chkEmailQuery->fetch_array()){
            $data[] = $result;
        }
        if ($data > 0) {
            return 1;
        }else{
            return 0;
        }
    }//eof CheckEmail function



    function login($email){
        $login = "SELECT * FROM `admin` WHERE `email` = '$email'";
        $loginQuery = $this->conn->query($login);
        // echo var_dump($loginQuery);
        // exit;
        if ($loginQuery == false) {
            return FALSE;
        }
        if($loginQuery != FALSE){
            while ($result = $loginQuery->fetch_array()) {
                $data[] = $result;
            }
            return $data;
        }
        
    }


}//eof Admin Class


?>