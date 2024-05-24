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
        $query = "INSERT INTO contact_details (name, contact_number, email, subject, message) 
                  VALUES (?, ?, ?, ?, ?)";

        if ($stmt = $this->conn->prepare($query)) {
            $stmt->bind_param('sisss', $data['name'], $data['contact_number'], $data['email'], $data['subject'], $data['message']);

            if ($stmt->execute()) {
                return json_encode(['status'=>true, 'insertId'=>$stmt->insert_id]);
            } else {
                $errorMsg = error_log("Execute failed: (" . $stmt->errno . ") " . $stmt->error);
                return json_encode(['status'=>false, 'message'=>$errorMsg]);
            }
        } else {
            return error_log("Prepare failed: (" . $this->conn->errno . ") " . $this->conn->error);
        }
    }
}


