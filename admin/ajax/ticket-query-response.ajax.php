<?php

require_once realpath(dirname(dirname(__DIR__)).'/config/constant.php');
require_once SUP_ADM_DIR.'_config/sessionCheck.php';
require_once SUP_ADM_DIR . '_config/accessPermission.php';


require_once CLASS_DIR.'dbconnect.php';
require_once CLASS_DIR.'request.class.php';
require_once CLASS_DIR . 'encrypt.inc.php';

$Request = new Request;


if ($_SERVER['REQUEST_METHOD'] === 'POST') {


    $table = $_POST['responseTableName'];
    $masterTicketNo = $_POST['masterTicektNo'];
    $queryResponse = $_POST['response'];
    
    
    $ticketNo = $_POST['masterTicektNo'];
    $masterTable = $_POST['masterTable'];


    $status = 1;
    $viewStatus = 1;

    $addResponse = $Request->addResponseToTicketQueryTable($table, $masterTicketNo, $queryResponse, $status, NOW, $viewStatus);

    // print_r($addResponse);

    $response = json_decode($addResponse);
    if($response->status){
        $updatedStatus = 'INACTIVE';
        $updateMasterTable = $Request->updateStatusByTableName($masterTable, $ticketNo, $updatedStatus, NOW);

        $updateMasterTableStatus = json_decode($updateMasterTable);
        if($updateMasterTableStatus->status){
            print_r($updateMasterTable);
        }else{
            print_r($updateMasterTable);
        }
    }else{
        print_r($addResponse);
    }
}
/* ============================ End ============================ */

?>
