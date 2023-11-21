<?php 
class AccessPermission extends DatabaseConnection{


    function showPermission($roleId, $adminId) {
        try {
            $select = "SELECT * FROM `access_permission` WHERE `role_id` = ? AND `admin_id` = ?";
            
            $stmt = $this->conn->prepare($select);
            
            if ($stmt === false) {
                throw new Exception("Error in preparing statement: " . $this->conn->error);
            }
    
            $stmt->bind_param("is", $roleId, $adminId);
    
            $stmt->execute();
    
            $result = $stmt->get_result();
    
            if ($result->num_rows > 0) {
                while($resultData = $result->fetch_object()){
                    // $data[] = array();
                    $data[] = $resultData;
                }
                return json_encode($data);
            } else {
                return null;
            }
        } catch (Exception $e) {
            return $e->getMessage();
        } finally {
            if (isset($stmt)) {
                $stmt->close();
            }
        }
        return 0;
    }
    
}
?>
