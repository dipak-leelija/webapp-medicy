<?php

require_once realpath(dirname(dirname(dirname(__DIR__))).'/config/constant.php');
require_once SUP_ADM_DIR.'_config/sessionCheck.php';
require_once SUP_ADM_DIR . '_config/accessPermission.php';


require_once CLASS_DIR.'dbconnect.php';
require_once CLASS_DIR.'request.class.php';
require_once CLASS_DIR . 'encrypt.inc.php';

$Request = new Request;


if (isset($_POST['ticket-query-response-submit'])) {

    $tableName = $_POST['table-name'];

    $reqNo = $_POST['req-id'];
    $ticketNo = $_POST['ticket-no'];
    $title = $_POST['msg-title'];
    $message = $_POST['message'];
    $document = $_POST['document-data'];
    $response = $_POST['query-responce'];
    $queryCreater = $_POST['msg-sender'];
    $adminId = $_POST['user-id'];
    $status = 1;
    $viewStatus = 1;

    $emailId = $_POST['email'];
    $contactNo = $_POST['contact-no'];

    $addResponse = $Request->addResponseToTicketQueryTable($tableName, $reqNo, $ticketNo, $title, $message, $document, $response, $queryCreater, $adminId, $status, NOW, $viewStatus);

    $response = json_decode($addResponse);


    if($response->status){
        $status = 'INACTIVE';
        if($tableName == 'Generate Quarry'){
            $table = 'query_request';
        }
        if($tableName == 'Generate Ticket'){
            $table = 'ticket_request';
        }

        $updateMasterTable = $Request->updateStatusByTableName($table, $reqNo, $status, NOW);
        
        $updateStatus = json_decode($updateMasterTable);
        
    }
    
    if($response->status && $updateStatus->status){
        $redirectUrl = ADM_URL . "ticket-query-response.php?response=" . url_enc(1)."&message=".url_enc($updateStatus->message);
        header("Location: " . $redirectUrl);
    }else{
        $redirectUrl = ADM_URL . "ticket-query-response.php?response=" . url_enc(0)."&message=".url_enc($updateStatus->message);
        header("Location: " . $redirectUrl);
    }
}
/* ============================ End ============================ */

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Medicy Health Care Lab Test Bill</title>
    <link rel="stylesheet" href="<?= CSS_PATH ?>bootstrap 5/bootstrap.css">
    <link rel="stylesheet" href="<?= CSS_PATH ?>custom/test-bill.css">
    <script src="<?php echo JS_PATH ?>bootstrap-js-5/bootstrap.js"></script>
    <!-- Include SweetAlert2 CSS -->
    <link href="<?= CSS_PATH ?>sweetalert2/sweetalert2.min.css" rel="stylesheet">
</head>


<body>
   
</body>


</html>