<?php
// echo dirname(dirname(__DIR__)) . '/config/constant.php';
require_once dirname(dirname(__DIR__)) . '/config/constant.php';
require_once ROOT_DIR . '_config/sessionCheck.php'; //check admin loggedin or not
require_once CLASS_DIR . 'dbconnect.php';
require_once CLASS_DIR . 'request.class.php';
require_once CLASS_DIR . 'idsgeneration.class.php';
require_once CLASS_DIR . 'utility.class.php';
require_once CLASS_DIR . 'encrypt.inc.php';


$Request        = new Request;
$Utility        = new Utility;
$IdsGeneration  = new IdsGeneration;




$uniqueNumber = $Utility->ticketNumberGenerator();;


if (isset($_POST['ticket-submit'])) {
    $user = $_POST['current-usr1'];
    $email = $_POST['email1'];
    $contact = $_POST['mobile-number1'];
    $query = $_POST['ticket-description1'];
    $documentName           = $_FILES['fileInput1']['name'];
    $tempDocumentName       = $_FILES['fileInput1']['tmp_name'];
    $status = 'ACTIVE';

    $submitTicket = $Request->addNewTicketRequest($uniqueNumber, $email, $contact, $user, $query, $documentName, $adminId, $status, NOW);

    $respoce = json_decode($submitTicket);

    if ($respoce->status) {
        $documentFolder     = TICKET_DOCUMENT_DIR . $documentName;
        echo $documentFolder;
        move_uploaded_file($tempDocumentName, $documentFolder);
    }
    $redirectUrl = URL . "ticket-query-response.php?response=" . url_enc($submitTicket);
    header("Location: " . $redirectUrl);
}


if (isset($_POST['query-submit'])) {
    $user = $_POST['current-usr2'];
    $email = $_POST['email2'];
    $contact = $_POST['mobile-number2'];
    $query = $_POST['ticket-description2'];
    $documentName           = $_FILES['fileInput2']['name'];
    $tempDocumentName       = $_FILES['fileInput2']['tmp_name'];
    $status = 'ACTIVE';

    $submitRequest = $Request->addNewQueryRequest($uniqueNumber, $email, $contact, $user, $query, $documentName, $adminId, $status, NOW);

    $respoce = json_decode($submitRequest);

    if ($respoce->status) {
        $documentFolder     = TICKET_DOCUMENT_DIR . $documentName;
        echo $documentFolder;
        move_uploaded_file($tempDocumentName, $documentFolder);
    }

    $redirectUrl = URL . "ticket-query-response.php?response=" . url_enc($submitRequest);
    header("Location: " . $redirectUrl);
}
