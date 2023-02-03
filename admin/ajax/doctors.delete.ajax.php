<?php
ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);

require_once '../../php_control/doctors.class.php';


$deleteDocId = $_POST['id'];
// $deleteDocId = 5418;

$doctors = new Doctors();
$docDelete = $doctors->deleteDoc($deleteDocId);

if ($docDelete) {
    echo 1;
}else {
    echo 0;
}


?>