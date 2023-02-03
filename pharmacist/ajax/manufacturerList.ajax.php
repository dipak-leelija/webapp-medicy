<?php
require_once '../../php_control/manufacturer.class.php';

$distributorId = $_GET['manufacturer_id'];
// $distributorId = 2;

$Manufacturer         = new Manufacturer();
$showManufacturerByDistributorId = $Manufacturer->showManufacturerByDistributorId($distributorId);

// if($doctor!=""){
    foreach($showManufacturerByDistributorId as $rowManufacturer){
        $manufacturerId   = $rowManufacturer['id'];
        $manufacturerName = $rowManufacturer['name'];
        // echo $days , $shift;
        echo'<option value="'.$manufacturerId.'">'. $manufacturerName.'</option>';
    }
// }
                                 
?>