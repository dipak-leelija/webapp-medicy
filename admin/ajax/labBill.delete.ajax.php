<?php
require_once '../../php_control/labBilling.class.php';


$billId = $_POST['billId'];
$status = $_POST['status'];
// echo $billId.'-'.$status;

// $billId = 1;
// $status = "Cancalled";

$LabBilling = new LabBilling();

$billUpdate = $LabBilling->cancelLabBill($billId, $status);
// echo var_dump($billUpdate);

if ($billUpdate == 1) {
    echo 1;
}else {
    echo 0;
}


?>