<?php
class Subscription extends DatabaseConnection
{

    function allPlans()
    {
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


    function createSubscription($adminId, $plan, $startDate, $endDate, $paidAmount)
    {
        try {
            // Query to insert subscription information for the given admin ID
            $query = "INSERT INTO subscription (admin_id, plan, start, end, paid) VALUES (?, ?, ?, ?, ?)";
            $stmt = $this->conn->prepare($query);

            if ($stmt) {
                $stmt->bind_param("sssss", $adminId, $plan, $startDate, $endDate, $paidAmount);
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



    function getSubscription($adminId = '')
    {
        try {
            // Query to get subscription information for the given admin ID
            if (!empty($adminId)) {
                $query = "SELECT * FROM subscription WHERE admin_id = ?";
                $stmt = $this->conn->prepare($query);
                $stmt->bind_param("s", $adminId);
            } else {
                $query = "SELECT * FROM subscription ";
                $stmt = $this->conn->prepare($query);
            }
            // $stmt = $this->conn->prepare($query);

            if ($stmt) {
                // $stmt->bind_param("s", $adminId); // Use "s" for integer
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


    function checkSubscription($adminId = '', $today = '')
    {
        $endDate = '';
        // Query to get subscription information for the given admin ID
        if (!empty($adminId)) {
            $query = "SELECT end FROM subscription WHERE admin_id = ?";
            $stmt = $this->conn->prepare($query);
            $stmt->bind_param("s", $adminId);
        } else {
            $query = "SELECT end FROM subscription ";
            $stmt = $this->conn->prepare($query);
        }
        // $stmt = $this->conn->prepare($query);

        if ($stmt) {
            // $stmt->bind_param("s", $adminId); // Use "s" for integer
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                $endDates = [];
                while ($row = $result->fetch_assoc()) {
                    $endDates[] = $row['end'];
                }

                $stmt->close();

                // Check if any subscription end date is greater than or equal to the current date
                foreach ($endDates as $endDate) {
                    $endDateObject = new DateTime($endDate);
                    $endDateObject->setTime(0, 0, 0);

                    $todayObject = new DateTime($today);
                    $todayObject->setTime(0, 0, 0);

                    if ($todayObject <= $endDateObject) {
                        // Subscription is not expired
                        return true;
                    }
                }

                // All subscriptions are expired
                return false;
            } else {
                // No subscriptions found for the given admin ID
                $stmt->close();
                return false;
            }
        } else {
            // Error in preparing the statement
            return false;
        }
    }



    function updateSubscription($adminId, $plan, $startDate, $expiry, $paidAmount)
    {
        try {
            // Start a transaction
            $this->conn->begin_transaction();

            // Query to insert subscription information for the given admin ID
            $subscriptionQuery = "INSERT INTO subscription (admin_id, plan, start, end, paid) VALUES (?, ?, ?, ?, ?)";
            $subscriptionStmt = $this->conn->prepare($subscriptionQuery);

            if ($subscriptionStmt) {
                $subscriptionStmt->bind_param("sssss", $adminId, $plan, $startDate, $expiry, $paidAmount);
                $subscriptionSuccess = $subscriptionStmt->execute();

                if ($subscriptionSuccess) {
                    $subscriptionStmt->close();

                    // Query to update expiry in the admin table
                    $updateAdminQuery = "UPDATE admin SET expiry = ? WHERE admin_id = ?";
                    $updateAdminStmt = $this->conn->prepare($updateAdminQuery);

                    if ($updateAdminStmt) {
                        $updateAdminStmt->bind_param("ss", $expiry, $adminId);
                        $updateAdminSuccess = $updateAdminStmt->execute();

                        if ($updateAdminSuccess) {
                            $updateAdminStmt->close();

                            // Commit the transaction
                            $this->conn->commit();

                            // return true;
                            return json_encode(['status' => 1, 'msg' => "success"]);
                        } else {
                            $updateAdminStmt->close();
                            throw new Exception("Admin update failed: " . $updateAdminStmt->error);
                        }
                    } else {
                        throw new Exception("Error preparing admin update statement: " . $this->conn->error);
                    }
                } else {
                    $subscriptionStmt->close();
                    throw new Exception("Subscription creation failed: " . $subscriptionStmt->error);
                }
            } else {
                throw new Exception("Error preparing subscription statement: " . $this->conn->error);
            }
        } catch (Exception $e) {
            // Rollback the transaction on error
            $this->conn->rollback();

            // Log the error and return false
            error_log("Error: " . $e->getMessage());
            return json_encode(['status' => 0, 'msg' => "Error: " . $e->getMessage()]);
        }
    }


    // get plans features//

    function planFeatures(){
        try {
            $query = "SELECT * FROM plans_features";
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
}
