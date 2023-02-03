<?php
require_once '../../../php_control/packagingUnit.class.php';

//Class initilization
$PackagingUnits = new PackagingUnits();


if( isset($_POST['add-unit'])){
    $unitName  = $_POST['uni-name'];
    $addedby   = " ";
    $addPackagingUnits = $PackagingUnits->addPackagingUnit($unitName, $addedby);
    if ($addPackagingUnits) {

        echo "<script>alert('Unit Added!'); window.location='../../packaging-unit.php';</script>";
         
     }else{
         echo "<script>alert('Unit Insertion Failed!'); window.location='../../packaging-unit.php';</script>";
     }
     
 }

?>