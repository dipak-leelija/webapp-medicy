<?php
ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);

require_once '../../php_control/manufacturer.class.php';
$Manufacturer       = new Manufacturer();

$manufacturerId = $_POST['id'];
// echo $manufacturerId;

$deleteManufacturer = $Manufacturer->deleteManufacturer($manufacturerId);
// $data = var_dump($deleteManufacturer);
// return $data;
if ($deleteManufacturer == true) {
    echo 1;
}else {
    echo 0;
}


?>