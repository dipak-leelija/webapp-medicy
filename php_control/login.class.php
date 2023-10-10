<?php
require_once 'dbconnect.php';

class LoginForm extends DatabaseConnection
{

    function login($email, $password, $roleData)
    {

        $sql = "SELECT * FROM `admin` WHERE `email` = '$email' OR `username` = '$email'";
        $result = $this->conn->query($sql);

        if ($result->num_rows > 0) {
            while ($data = $result->fetch_object()) {
                
                $dbPasshash = $data->password;
                if (password_verify($password, $dbPasshash)) {
                    session_start();
                    $_SESSION['LOGGEDIN']   = true;
                    $_SESSION['ADMIN']      = true;
                    $_SESSION['USER_EMAIL'] = $data->email;
                    $_SESSION['USER_FNAME']  = $data->fname;
                    $_SESSION['USERNAME']   = $data->username;
                    $_SESSION['ADMINID']   = $data->id;

                    // echo "admin login";
                    // exit;
                    header("Location: admin/index.php");
                } else {
                    echo 'Wrong Password';
                }
            }
        } else {

            $sql = "SELECT * FROM `employees` WHERE `emp_email` = '$email' OR `employee_username` = '$email'";
            $result = $this->conn->query($sql);

            if ($result->num_rows > 0) {
                while ($data = $result->fetch_object()) {

                    $dbPasshash = $data->employee_password;
                    
                    if (password_verify($password, $dbPasshash)) {
                        session_start();
                        $_SESSION['LOGGEDIN']   = true;
                        $_SESSION['ADMIN']      = false;
                        $_SESSION['USER_EMAIL'] = $email;
                        $_SESSION['USER_ROLE']  = $data->emp_role;
                        $_SESSION['USER_FNAME']   = $data->employee_name;
                        $_SESSION['USERNAME']   = $data->employee_username;
                        $_SESSION['EMPID']   = $data->id;

                        // echo "employee login";
                        // exit;
                        header("Location: admin/index.php");
                    } else {
                        echo 'Wrong Password';
                    }
                }
            } else {
                echo 'user not found';
            }
        }
    }
}
