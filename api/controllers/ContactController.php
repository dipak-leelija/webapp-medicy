<?php

namespace Api\Controllers;

require_once dirname(__DIR__, 1) . '/models/ContactModel.php';

use Models\Contact;

class ApiContactController {

    public function createContact($data) {
        $contactModel = new Contact();
        $result = $contactModel->createContact($data);
        header("Content-Type: application/json; charset=UTF-8");
        echo json_encode(['success' => $result]);
    }
}
