<?php
require_once dirname(__DIR__).'/config/constant.php';
<<<<<<< HEAD
=======
require_once ROOT_DIR.'_config/sessionCheck.php'; //check admin loggedin or not
>>>>>>> cdf4fe221f226b0f3209c8c8fe6f37aa93378637

require_once CLASS_DIR.'dbconnect.php';
require_once CLASS_DIR.'manufacturer.class.php';
$Manufacturer       = new Manufacturer();

$manufacturerId = $_POST['id'];
// echo $manufacturerId;

$deleteManufacturer = $Manufacturer->deleteManufacturer($manufacturerId);

if ($deleteManufacturer) {
   echo $deleteManufacturer;
}else {
    echo 0;
}


?>