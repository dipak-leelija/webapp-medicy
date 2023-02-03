<?php
ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);

require_once '../../php_control/manufacturer.class.php';


$manufacturerId = $_POST['id'];

$Manufacturer       = new Manufacturer();
$deleteManufacturer = $Manufacturer->deleteManufacturer($manufacturerId);

if ($deleteManufacturer) {
    echo 1;
}else {
    echo 0;
}


?>