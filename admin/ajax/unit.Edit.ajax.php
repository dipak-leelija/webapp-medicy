<?php
require_once dirname(dirname(__DIR__)).'/config/constant.php';
require_once CLASS_DIR.'dbconnect.php';

require_once CLASS_DIR.'measureOfUnit.class.php';


$unitId    = $_GET['id'];
$srtName   = $_GET['unit-srt-name'];
$fullName  = $_GET['unit'];


$MeasureOfUnits = new MeasureOfUnits();
$updateUnit = $MeasureOfUnits->updateUnit($srtName, $fullName, $unitId);

//check if the data has been updated or not
if($updateUnit){
   echo '<div class="alert alert-primary alert-dismissible fade show" role="alert">
   <strong>Success!</strong> Unit Has been Updated!
   <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
   </div>';
}else {
    echo '<div class="alert alert-warning alert-dismissible fade show" role="alert">
    <strong>Failed!</strong> Unit Updation Failed!
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>';
}


?>