<?php
require_once CLASS_DIR . 'encrypt.inc.php';

class recoverPass extends DatabaseConnection
{


    function recoverPassword($user)
    {
        try {
            $chkAdmin = " SELECT * FROM `admin` WHERE `username`= '$user' OR `email` = '$user'";

            $chkEmployee = " SELECT * FROM `employees` WHERE `emp_username`= '$user' OR `emp_email` = '$user' ";

            $stmt1 = $this->conn->prepare($chkAdmin);
            $stmt2 = $this->conn->prepare($chkEmployee);

            $stmt1->execute();
            $stmt2->execute();

            $res1 = $stmt1->get_result();
            $res2 = $stmt2->get_result();


            if ($res1->num_rows > 0 || $res2->num_rows > 0) {
                $data = array();
                while ($result = $res1->fetch_object() || $result = $res2->fetch_object()) {
                    $adminData[] = $result;
                }
                $stmt1->close();
                return json_encode(['status' => '1', 'message' => 'success', 'data' => $adminData]);
            } else {
                $stmt2->close();
                return json_encode(['status' => '0', 'message' => 'no data', 'data' => '']);
            }
        } catch (Exception $e) {
            return json_encode(['status' => '', 'message' => $e->getMessage(), 'data' => '']);
        }
        return 0;
    }





    function adminPassRecover($user){
        try {
            $stmt = $this->conn->prepare("SELECT * FROM `admin` WHERE (`email` = ? OR `username` = ?) AND `reg_status` = 1");

            $stmt->bind_param("ss", $user, $user);

            $stmt->execute();

            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                $admData = array();
                while ($res = $result->fetch_object()) {
                    $admData[] = $res;
                }
                $response = ['status' => '1', 'data' => $admData];
            } else {
                $response = ['status' => '0', 'message' => 'No data found', 'data' => ''];
            }

            $stmt->close();

            return json_encode($response);
        } catch (Exception $e) {
            return "Error: " . $e->getMessage();
        }
    }







    function adminData($adm)
    {
        try {
            $stmt = $this->conn->prepare("SELECT * FROM `admin` WHERE (`email` = ? OR `username` = ?) AND `reg_status` = 1");

            $stmt->bind_param("ss", $adm, $adm);

            $stmt->execute();

            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                $admData = array();
                while ($res = $result->fetch_object()) {
                    $admData[] = $res;
                }
                $response = ['status' => '1', 'data' => $admData];
            } else {
                $response = ['status' => '0', 'message' => 'No data found', 'data' => ''];
            }

            $stmt->close();

            return json_encode($response);
        } catch (Exception $e) {
            return json_encode(['status' => '0', 'message' => 'Error: ' . $e->getMessage(), 'data' => '']);
        }
    }






    function employeePassRecover($adminId, $user)
    {
        try {
            $stmt = $this->conn->prepare("SELECT * FROM `employees` WHERE (`emp_email` = ? OR `emp_username` = ?) AND `admin_id` = ?");

            $stmt->bind_param("sss", $user, $user, $adminId);

            $stmt->execute();

            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                $empData = array();
                while ($res = $result->fetch_object()) {
                    $empData[] = $res;
                }
                $response = ['status' => '1', 'data' => $empData];
            } else {
                $response = ['status' => '0', 'message' => 'No data found', 'data' => ''];
            }

            $stmt->close();

            return json_encode($response);

        } catch (Exception $e) {
            return "Error: " . $e->getMessage();
        }
    }
} //eof recover password Class
