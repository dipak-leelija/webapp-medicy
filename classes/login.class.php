<?php
require_once CLASS_DIR.'encrypt.inc.php';

class LoginForm extends DatabaseConnection{

    function login($email, $password, $roleData){

        $sql = "SELECT * FROM `admin` WHERE `email` = '$email' OR `username` = '$email'";
        $result = $this->conn->query($sql);

        if ($result->num_rows > 0) {
            while ($data = $result->fetch_object()) {
                
                $dbPasshash = $data->password;
                $x_password = pass_dec($dbPasshash, ADMIN_PASS);
                // exit;

                if ($x_password === $password) {
                    
                    session_start();
                    $_SESSION['LOGGEDIN']           = true;
                    $_SESSION['ADMIN']              = true;
                    $_SESSION['USER_TYPE']          = 'NOT USER';
                    $_SESSION['ADMIN_EMAIL']        = $data->email;
                    $_SESSION['USER_ROLE']          = 'ADMIN';
                    $_SESSION['ADMIN_FNAME']        = $data->fname;
                    $_SESSION['ADMIN_LNAME']        = $data->lname;
                    $_SESSION['ADMIN_CONTACT_NO']   = $data->mobile_no;
                    $_SESSION['ADMIN_USERNAME']     = $data->username;
                    $_SESSION['ADMIN_PASSWORD']     = $data->password;
                    $_SESSION['ADMINID']            = $data->admin_id;
                        
                    header("Location: ".URL);
                    exit;

                } else {
                    return 'Wrong Password';
                }
            }
        } else {

            $sql = "SELECT * FROM `employees` WHERE `emp_email` = '$email' OR `emp_username` = '$email'";
            $result = $this->conn->query($sql);

            if ($result->num_rows > 0) {
                while ($data = $result->fetch_object()) {

                    $dbPasshash = $data->emp_password;
                    $x_password = pass_dec($dbPasshash, EMP_PASS);
                    
                    if ($x_password === $password) {

                        session_start();
                        $_SESSION['LOGGEDIN']       = true;
                        $_SESSION['ADMIN']          = false;
                        $_SESSION['USER_TYPE']      = 'USER';
                        $_SESSION['EMP_EMAIL']      = $email;
                        $_SESSION['EMP_CONTACT_NO'] = $data->emp_contact_no;
                        $_SESSION['EMP_ROLE']       = $data->emp_role;
                        $_SESSION['EMP_NAME']       = $data->emp_name;
                        $_SESSION['EMP_USERNAME']   = $data->emp_username;
                        $_SESSION['EMP_PASSWORD']   = $data->password;
                        $_SESSION['EMPID']          = $data->emp_id;
                        $_SESSION['ADMIN_ID']       = $data->admin_id;

                        header("Location: ".URL);
                        exit;
                    } else {
                        return 'Wrong Password';
                    }
                }
            } else {
                return 'not found';
            }
        }
    }

}
