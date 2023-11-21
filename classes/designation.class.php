<?php

class Designation extends DatabaseConnection
{

    function addDesigRole($desigName, $adminId)
    {
        try {
            $sql = "INSERT INTO `designation` (desig_name, admin_id) VALUES (?, ?)";
            $stmt = $this->conn->prepare($sql);
            $stmt->bind_param("ss", $desigName, $adminId);

            $result = $stmt->execute();
            $stmt->close();

            return $result;
        } catch (Exception $e) {
            $e->getMessage();
        }
    }


    function designationRole($adminId)
    {
        $data = [];
        $sql = "SELECT * FROM `designation` WHERE `admin_id` = '$adminId' ";
        $result = $this->conn->query($sql);
        while ($results =  $result->fetch_object()) {
            $data[] = $results;
        }
        $data = json_encode($data);
        return $data;
    }

    function designationRoleID($adminId,$desinId)
    {
        $data = '';
        $sql = "SELECT * FROM `designation` WHERE `admin_id` = '$adminId' AND `id` = '$desinId' ";
        $result = $this->conn->query($sql);
        while ($results =  $result->fetch_object()) {
            $data = $results;
        }
        $data = json_encode($data);
        return $data;
    }

    function deleteDesign($deleteRole)
    {
        $delEmp = "DELETE FROM `designation` WHERE `designation`.`id` = '$deleteRole'";
        $delEmpQuery = $this->conn->query($delEmp);
        return $delEmpQuery;
    } // end deleteDocCat function

    // function editDesign($desigName, $designId){
    //     $edit = "UPDATE  `designation` SET `desig_name` = '$desigName' WHERE `designation`.`id` = '$designId'";
    //     $editQuery = $this->conn->query($edit);
    //     return $editQuery;
    // }

    function editDesign($desigName, $designId)
    {
        try {
            $edit = "UPDATE `designation` SET `desig_name` = ? WHERE `id` = ?";
            $stmt = $this->conn->prepare($edit);

            if (!$stmt) {
                throw new Exception("Prepare failed: (" . $this->conn->errno . ") " . $this->conn->error);
            }

            // Bind parameters
            $stmt->bind_param("ss", $desigName, $designId);

            // Execute the statement
            $editQuery = $stmt->execute();

            if (!$editQuery) {
                throw new Exception("Execute failed: (" . $stmt->errno . ") " . $stmt->error);
            }

            return $editQuery;
        } catch (Exception $e) {
            // Handle the exception (e.g., log the error, display an error message, etc.)
            echo "Error: " . $e->getMessage();
            return false;
        } finally {
            // Close the statement
            $stmt->close();
        }
    }


    function desigShowID($designId)
    {
        $select = "SELECT * FROM `designation` WHERE `id` = '$designId'";
        $query = $this->conn->query($select);
        while ($result = $query->fetch_object()) {
            $data = $result;
        }
        $data = json_encode($data);
        return $data;
    }
}
