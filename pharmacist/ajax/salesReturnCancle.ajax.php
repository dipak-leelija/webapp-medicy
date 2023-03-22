<?php
require_once '../../php_control/salesReturn.class.php';
require_once '../../php_control/patients.class.php';
require_once '../../php_control/products.class.php';


// classes initiating 
$SalesReturn    = new SalesReturn();
$Patients       = new Patients();
$products       = new Products();


if (isset($_POST['id'])) {

   
    $SalesReturnId = $_POST['id'];
    $status = "CANCEL";
    
    $updateStatus = $SalesReturn->updateStatus($SalesReturnId, $status);
    
    if($updateStatus == true){
        echo 1;
    }
}
