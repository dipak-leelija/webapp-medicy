<?php
class Subscription extends DatabaseConnection{
    
    function createSubscription($adminId, $startDate, $endDate) {
        try {
            // Query to insert subscription information for the given admin ID
            $query = "INSERT INTO subscriptions (admin_id, start_date, end_date) VALUES (?, ?, ?)";
            $stmt = $this->conn->prepare($query);
    
            if ($stmt) {
                $stmt->bind_param("sss", $adminId, $startDate, $endDate);
                $success = $stmt->execute();
                $stmt->close();
    
                // Return true if the insertion was successful, otherwise false
                return $success;
            }
    
            // Return false if the prepared statement could not be created
            return false;
        } catch (Exception $e) {
            // Handle any exceptions that may occur during the database operation
            error_log("Error creating subscription: " . $e->getMessage());
            return false;
        }
    }
      
    
}
?>