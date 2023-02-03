<?php
require_once '../../../php_control/measureOfUnit.class.php';

//Class initilization
$MeasureOfUnits = new MeasureOfUnits();


if( isset($_POST['add-unit'])){
    $srtName  = $_POST['unit-srt-name'];
    $fullName = $_POST['unit-full-name'];
    
    $addMeasureOfUnits = $MeasureOfUnits->addMeasureOfUnits($srtName, $fullName);
    if ($addMeasureOfUnits) {

        echo "<script>alert('Unit Added!'); window.location='../../product-unit.php';</script>";

        //   echo "<script>alert('Unit Added!')</script>";
         
     }else{
         echo "<script>alert('Unit Insertion Failed!'); window.location='../../product-unit.php';</script>";
     }
     
 }

?>