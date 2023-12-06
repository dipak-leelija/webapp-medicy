<?php
class Subscription extends DatabaseConnection{
    
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
    
            return json_encode(['status' => 0, 'msg' => 'No subscriptions found for the given admin ID']);
        } catch (Exception $e) {
            // Handle any exceptions that may occur during the database operation
            error_log("Error getting subscription: " . $e->getMessage());
            return json_encode(['status' => 0, 'msg' => "Error: " . $e->getMessage()]);
        }   
    }


    function createSubscription($adminId, $plan, $startDate, $endDate, $paid){
        try {
            // Query to insert subscription information for the given admin ID
            $query = "INSERT INTO subscription (admin_id, plan, start, end, paid) VALUES (?, ?, ?, ?, ?)";
            $stmt = $this->conn->prepare($query);
    
            if ($stmt) {
                $stmt->bind_param("sssss", $adminId, $plan, $startDate, $endDate, $paid);
                $success = $stmt->execute();
    
                if ($success) {
                    $stmt->close();
                    return true;
                } else {
                    $stmt->close();
                    throw new Exception("Subscription creation failed: " . $stmt->error);
                }
            } else {
                throw new Exception("Error preparing subscription statement: " . $this->conn->error);
            }
        } catch (Exception $e) {
            // Log the error and return false
            error_log("Error: " . $e->getMessage());
            return "Error => " . $e->getMessage();
        }
    }
    
      

    function getSubscription($adminId){
        try {
            // Query to get subscription information for the given admin ID
            $query = "SELECT * FROM subscription WHERE admin_id = ?";
            $stmt = $this->conn->prepare($query);
    
            if ($stmt) {
                $stmt->bind_param("s", $adminId); // Use "s" for integer
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
    
            return json_encode(['status' => 0, 'msg' => 'No subscriptions found for the given admin ID']);
        } catch (Exception $e) {
            // Handle any exceptions that may occur during the database operation
            error_log("Error getting subscription: " . $e->getMessage());
            return json_encode(['status' => 0, 'msg' => "Error: " . $e->getMessage()]);
        }
    }    

    function checkSubscription($adminId, $today) {
        $endDate ='';
        // Query to get subscription information for the given admin ID
        $query = "SELECT end FROM subscription WHERE admin_id = ?";
        $stmt = $this->conn->prepare($query);
    
        if ($stmt) {
            $stmt->bind_param("s", $adminId); // Use "s" for integer
            $stmt->execute();
            $stmt->bind_result($endDate);
            $stmt->fetch();
            $stmt->close();
    
            // Check if $endDate is not null before creating DateTime objects
            if ($endDate !== null) {
                $todayDate = new DateTime($today);
                $todayDate->setTime(0, 0, 0);
    
                $endDateObject = new DateTime($endDate);
                $endDateObject->setTime(0, 0, 0);
    
                return ($todayDate <= $endDateObject);
            }
        }
    
        // Handle the case when $endDate is null (return false or any appropriate logic)
        return false;
    }
    
    
}
?>