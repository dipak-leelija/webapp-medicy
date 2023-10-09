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
                    $_SESSION['loggedin'] = true;
                    $_SESSION['admin'] = true;
                    
                    $_SESSION['user_email'] = $data->email;
                    $_SESSION['username'] = $data->username;
                    $_SESSION['userFname'] = $data->fname;
                    

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
                        $_SESSION['loggedin'] = true;
                        $_SESSION['employees'] = true;
                        
                        $_SESSION['user_email'] = $email;
                        $_SESSION['user_role'] = $data->emp_role;
                        $_SESSION['emp_name'] = $data->employee_name;
                        $_SESSION['username'] = $data->employee_username;

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
