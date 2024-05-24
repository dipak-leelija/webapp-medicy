<?php

namespace Api\Controllers;

require_once dirname(__DIR__, 1) . '/models/ContactModel.php';

use Models\Contact;

class ApiContactController {

    public function createContact($data) {
        $contactModel = new Contact();
        $result = $contactModel->createContact($data);
        $result = json_decode($result, true);
        print_r($result);
        header("Content-Type: application/json; charset=UTF-8");

        $resultMessage = $result['status'];
        echo $resultMessage;

        if($resultMessage){
            echo json_encode(['status'=>true]);
        }else{
            echo json_encode(['status'=>false]);
        }
    }
}
