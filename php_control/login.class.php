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
                $dbPasshash = $data->password;
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

            // $resultData = array();
            $sql = "SELECT * FROM `employees` WHERE `emp_email` = '$email'";
            $result = $this->conn->query($sql);

            // print_r($result);

            // $data = array();
            // $select = "SELECT * FROM current_stock GROUP BY `current_stock`.`product_id`";
            // $selectQuery = $this->conn->query($select);
            // while ($result = $selectQuery->fetch_array()) {
            //     $data[] = $result;
            // }
            // return $data;


            if ($result->num_rows > 0) {
                while ($data = $result->fetch_object()) {

                    // $resultData[] = $data;
                    // print_r($resultData);

                    $dbPasshash = $data->employee_password;
                    $empRole = $data->emp_role;
                    // echo "$empRole";

                    $decodedData = json_decode($roleData, true);
                    // echo "<br>"; print_r($decodedData);
                    if ($decodedData) {
                        foreach ($decodedData as $data) {
                            $empUserName = $data['employee_username'];
                            $desigempRole = $data['emp_role'];
                            // echo "<br>Employee username & Role: $empUserName && " . $desigempRole . "<br>";
                        }
                    }

                    if (password_verify($password, $dbPasshash)) {
                        session_start();
                        $_SESSION['loggedin'] = true;
                        $_SESSION['employees'] = true;
                        $_SESSION['userEmail'] = $email;

                        // echo "Employee Role: " . $desigempRole;

                        if ($empRole == $desigempRole) {
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
