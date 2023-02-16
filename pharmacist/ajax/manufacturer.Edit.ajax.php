<?php
require_once '../../php_control/manufacturer.class.php';


$manufacturerId     = $_GET['id'];
$manufacturerName   = $_GET['name'];
$manufacturerDsc    = $_GET['dsc'];


$Manufacturer = new Manufacturer();
$updateManufacturer = $Manufacturer->updateManufacturer($manufacturerName, $manufacturerDsc, $manufacturerId);

//check if the data has been updated or not
if($updateManufacturer){
   echo '<div class="alert alert-primary alert-dismissible fade show" role="alert">
   <strong>Success!</strong> Manufacturer Has been Updated!
   <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
   </div>';
    
}else {
    echo '<div class="alert alert-warning alert-dismissible fade show" role="alert">
    <strong>Failed!</strong> Manufacturer Updation Failed!
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>';
}


?>