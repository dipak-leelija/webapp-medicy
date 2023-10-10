<?php
class Admin extends DatabaseConnection{



    function registration($Fname, $Lname, $username, $password, $email, $mobNo, $address, $city){
        $register = " INSERT INTO `admin` (`fname`, `lname`, `username`, `password`, `email`, `mobile_no`, `address`, `city`) VALUES ('$Fname', '$Lname', '$username', '$password', '$email', '$mobNo', '$address', '$city')";
        $registerQuery = $this->conn->query($register);
        return $register;
    }//eof registration function




    function echeckUsername($username){
        $chkUser = " SELECT * FROM `admin` WHERE `username`= '$username' ";
        // echo $chkUser;
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