<?php
require_once CLASS_DIR . 'encrypt.inc.php';
class SuperAdmin extends DatabaseConnection
{
function supAdminDetails(){
        try{
            $chkUser = " SELECT * FROM `super_admin` ";

            $stmt = $this->conn->prepare($chkUser);

            $stmt->execute();

            $res = $stmt->get_result();

            if ($res->num_rows > 0) {
                $adminData = array();
                while ($result = $res->fetch_object()) {
                    $adminData[] = $result;
                }
                $stmt->close();
                return json_encode(['status'=>'1', 'message'=>'success', 'data'=>$adminData]);
            } else {
                $stmt->close();
                return json_encode(['status'=>'0', 'message'=>'no data', 'data'=> '']);
            }
        } catch (Exception $e) {
            return json_encode(['status'=>'', 'message'=>$e->getMessage(), 'data'=> '']);
        }
        return 0;
    } //eof CheckEmail

    function updateSupAdminDetails($fname, $lname, $img, $email, $mobNo, $address, $updatedOn, $supadminid) {
        try {
            $updateQuery = "UPDATE `super_admin` SET `fname`=?, `lname`=?, `adm_img`=?, `email`=?, `mobile_no`=?, `address`=?, `updated_on`=? WHERE `admin_id`=?";
            
            $stmt = $this->conn->prepare($updateQuery);
    
            $stmt->bind_param("ssssssss", $fname, $lname, $img, $email, $mobNo, $address, $updatedOn, $supadminid);
    
            $stmt->execute();
    
            $stmt->close();
    
            return ['result' => '1'];
        } catch (Exception $e) {
            return ['result' => '0', 'message' => $e->getMessage()];
        }
    }
}

?>