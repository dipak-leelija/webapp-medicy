<?php
require_once 'dbconnect.php';

class LoginForm extends DatabaseConnection
{

    function login($email, $password, $roleData)
    {

        $sql = "SELECT * FROM `admin` WHERE `email` = '$email'";
        $result = $this->conn->query($sql);

        // var_dump($result);
        if ($result->num_rows > 0) {
            while ($data = $result->fetch_object()) {
                echo $dbPasshash = $data->password;
                if (password_verify($password, $dbPasshash)) {
                    session_start();
                    $_SESSION['loggedin'] = true;
                    $_SESSION['admin'] = true;
                    $_SESSION['userEmail'] = $email;

                    header("Location: admin/index.php");
                } else {
                    echo 'Wrong Password';
                }
            }
        } else {
            $sql = "SELECT * FROM `employees` WHERE `emp_email` = '$email'";
            $result = $this->conn->query($sql);
            if ($result->num_rows > 0) {
                while ($data = $result->fetch_object()) {

                    $dbPasshash = $data->employee_password;

                    $empRole = $data->emp_role;

                    //catch emp_role from designation
                    // echo "data..." . $roleData;
                    $decodedData = json_decode($roleData, true);
                    if ($decodedData) {
                        foreach ($decodedData as $data) {
                            $desigempRole = $data['emp_role'];
                            // echo "Employee Role: " . $desigempRole . "<br>";
                        }
                    }

                    if (password_verify($password, $dbPasshash)) {
                        session_start();
                        $_SESSION['loggedin'] = true;
                        $_SESSION['employees'] = true;
                        $_SESSION['userEmail'] = $email;

                        echo "Employee Role: " . $desigempRole;

                        if($empRole == $desigempRole){
                            echo "send";
                            // header("Location: pharmacist/index.php");
                        }
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
