<?php

namespace Models;

require_once dirname(__DIR__, 2) . '/classes/dbconnection.php';

use DatabaseConnection\DatabaseConnection;

class Contact {
    private $conn;

    public function __construct() {
        $db = new DatabaseConnection();
        $this->conn = $db->conn;
    }

    public function createContact($data) {
        $query = "INSERT INTO contact_details (name, email, subject, message) 
                  VALUES (?, ?, ?, ?)";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param('ssss', $data['name'], $data['email'], $data['subject'], $data['message']);
        return $stmt->execute();
    }

}
