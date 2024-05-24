<?php

namespace Models;

require_once dirname(__DIR__, 2) . '/classes/dbconnection.php';

use DatabaseConnection\DatabaseConnection;
use Exception;

class Contact {
    private $conn;

    public function __construct() {
        $db = new DatabaseConnection();
        $this->conn = $db->conn;
    }

    public function createContact($data) {
        try {
            
            if (!empty($data['name']) && !empty($data['contact_number']) && !empty($data['email']) && !empty($data['subject']) && !empty($data['message'])) {
                
                $query = "INSERT INTO contact_details (name, contact_number, email, subject, message) 
                          VALUES (?, ?, ?, ?, ?)";
                
                if ($stmt = $this->conn->prepare($query)) {
                    $stmt->bind_param('sisss', $data['name'], $data['contact_number'], $data['email'], $data['subject'], $data['message']);
                    
                    if ($stmt->execute()) {
                        $insertId = $stmt->insert_id;
                        return json_encode(['status' => true, 'insertId' => $insertId, 'message'=>'Data stored successfully']);
                    } else {
                        error_log("Execute failed: (" . $stmt->errno . ") " . $stmt->error);
                        return json_encode(['status' => false, 'message' => 'Code Executin failed: ' . $stmt->error]);
                    }
                } else {
                    error_log("Prepare failed: (" . $this->conn->errno . ") " . $this->conn->error);
                    return json_encode(['status' => false, 'message' => 'Data Preparation failed: ' . $this->conn->error]);
                }
            } else {
                return json_encode(['status' => false, 'message' => 'Blank data input']);
            }
        } catch (Exception $e) {
            // error_log("Exception: " . $e->getMessage());
            return json_encode(['status' => false, 'message' => 'Exception: ' . $e->getMessage()]);
        }
    }

}
