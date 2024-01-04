<?php
class Plan extends DatabaseConnection{
    
    function allPlans(){
        try {
            $query = "SELECT * FROM plans";
            $stmt = $this->conn->prepare($query);
    
            if ($stmt) {
                $stmt->execute();
                $result = $stmt->get_result();
    
                if ($result->num_rows > 0) {
                    $subscriptions = [];
                    while ($row = $result->fetch_assoc()) {
                        $subscriptions[] = $row;
                    }
                    return json_encode(['status' => 1, 'msg' => 'success', 'data' => $subscriptions]);
                }
    
                $stmt->close();
            }
    
            return json_encode(['status' => 0, 'msg' => 'No subscriptions found for the given Plan ID']);
        } catch (Exception $e) {
            // Handle any exceptions that may occur during the database operation
            error_log("Error getting subscription: " . $e->getMessage());
            return json_encode(['status' => 0, 'msg' => "Error: " . $e->getMessage()]);
        }   
    }
    
    function getPlan($planId){
        try {
            $query = "SELECT * FROM plans WHERE id = $planId AND status = 1";
            $stmt = $this->conn->prepare($query);
    
            if ($stmt) {
                $stmt->execute();
                $result = $stmt->get_result();
    
                if ($result->num_rows > 0) {
                    $plan = $result->fetch_assoc();
                        
                    return json_encode(['status' => 1, 'msg' => 'success', 'data' => $plan]);
                }
    
                $stmt->close();
            }
    
            return json_encode(['status' => 0, 'msg' => 'No subscriptions found for the given Plan ID']);
        } catch (Exception $e) {
            // Handle any exceptions that may occur during the database operation
            error_log("Error getting subscription: " . $e->getMessage());
            return json_encode(['status' => 0, 'msg' => "Error: " . $e->getMessage()]);
        }   
    }
}
?>