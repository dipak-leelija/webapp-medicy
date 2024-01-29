<?php
require_once dirname(__DIR__).'/config/constant.php';
require_once ROOT_DIR. '_config/sessionCheck.php';

require_once CLASS_DIR.'dbconnect.php';
require_once CLASS_DIR.'packagingUnit.class.php';


$unitId     = $_GET['id'];
$unitName   = $_GET['unit-name'];


$PackagingUnits = new PackagingUnits();

$insertUnitRequest = $PackagingUnits->insertPackagingRequest($unitId , $unitName, NOW, $adminId);
if($insertUnitRequest){
    
}

//check if the data has been updated or not
if($insertUnitRequest){
   echo '<div class="alert alert-primary alert-dismissible fade show" role="alert">
   <strong>Success!</strong> Unit Has been Requested!
   <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
   </div>';
}else {
    echo '<div class="alert alert-warning alert-dismissible fade show" role="alert">
    <strong>Failed!</strong> Unit Request Failed!
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>';
}


?>