<?php
require_once 'dbconnect.php';

    $desRole = new Designation();
    $roleData = $desRole->designationRole();
    print_r($roleData);

    class LoginForm extends DatabaseConnection{

        function login($email,$password){
            
            $sql = "SELECT * FROM `admin` WHERE `email` = '$email'";
            $result = $this->conn->query($sql);  

            // var_dump($result);
                if($result->num_rows > 0){
                    while($data = $result->fetch_object()){
                       echo $dbPasshash = $data->password;
                        if(password_verify($password, $dbPasshash)){
                            session_start();
                            $_SESSION['loggedin'] = true;
                            $_SESSION['admin'] = true;
                            $_SESSION['userEmail'] = $email;
                           
                            header("Location: admin/index.php");
                        }else {
                            echo 'Wrong Password';
                        }
                    }
                }else{
                $sql = "SELECT * FROM `employees` WHERE `emp_email` = '$email'";
                $result = $this->conn->query($sql);
                if($result->num_rows > 0){
                    while($data = $result->fetch_object()){

                    $dbPasshash = $data->employee_password;

                    $empRole = $data->emp_role;

                        if(password_verify($password, $dbPasshash)){
                            session_start();
                            $_SESSION['loggedin'] = true;
                            $_SESSION['employees'] = true;
                            $_SESSION['userEmail'] = $email;
                            
                            if($empRole == 'Pharmacist'){
                                header("Location: pharmacist/index.php");
                            }elseif($empRole == 'Receptionist'){
                                header("Location: index.php");
                            }else{
                                 header("Location: login.php");
                            }
                            // header("Location: index.php");
                        }else {
                            echo 'Wrong Password';
                        }
                    }
                }else {
                    echo 'user not found';
                }
            }

            
        }
    }
