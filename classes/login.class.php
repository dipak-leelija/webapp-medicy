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
                    
                    $checkSubs =$this->checkSubscription($data->admin_id, NOW);
                    if ($checkSubs){

                        session_start();
                        $_SESSION['LOGGEDIN']           = true;
                        $_SESSION['ADMIN']              = true;
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
                    }else {
                        return 'ENDED';
                    }
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
                        $checkSubs =$this->checkSubscription($data->admin_id, NOW);
                        if ($checkSubs){

                            session_start();
                            $_SESSION['LOGGEDIN']       = true;
                            $_SESSION['ADMIN']          = false;
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
                        }else {
                            return 'ENDED';
                        }
                    } else {
                        return 'Wrong Password';
                    }
                }
            } else {
                return 'not found';
            }
        }
    }


    function checkSubscription($adminId, $today) {

        // Query to get subscription information for the given admin ID
        $query = "SELECT end FROM subscription WHERE admin_id = ?";
        $stmt = $this->conn->prepare($query);
        if ($stmt) {
            $stmt->bind_param("s", $adminId); // Use "s" for integer
            $stmt->execute();
            // $result = $stmt->get_result();
            $stmt->bind_result($endDate);
            $stmt->fetch();

            $stmt->close();
        }

        // Check if the current date is within the subscription period
        $todayDate = new DateTime($today);
        $todayDate->setTime(0, 0, 0);

        $endDateObject = new DateTime($endDate);
        $endDateObject->setTime(0, 0, 0);

        return ($todayDate <= $endDateObject);
    }
}
