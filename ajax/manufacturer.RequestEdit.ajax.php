<?php
require_once dirname(__DIR__).'/config/constant.php';
require_once ROOT_DIR.'_config/sessionCheck.php'; //check admin loggedin or not

require_once CLASS_DIR.'dbconnect.php';
require_once CLASS_DIR.'manufacturer.class.php';


$manufacturerId     = $_GET['id'];
$manufacturerName   = $_GET['name'];
$manufShortName     = $_GET['sname'];
$manufacturerDsc    = $_GET['dsc'];

$Manufacturer = new Manufacturer();
$updateManufacturer = $Manufacturer->insertRequestManufacturer($manufacturerId,$manufacturerName, $manufShortName, $manufacturerDsc, NOW , $adminId);


//check if the data has been updated or not
if($updateManufacturer){
   echo '<div class="alert alert-primary alert-dismissible fade show" role="alert">
   <strong>Success!</strong> Manufacturer Has been Requested !
   <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
   </div>';
    
}else {
    echo '<div class="alert alert-warning alert-dismissible fade show" role="alert">
    <strong>Failed!</strong> Manufacturer Request Failed!
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>';
}


?>