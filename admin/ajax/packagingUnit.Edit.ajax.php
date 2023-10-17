<?php
require_once dirname(dirname(__DIR__)).'/config/constant.php';
<<<<<<< HEAD
require_once ADM_DIR.'_config/sessionCheck.php'; //check admin loggedin or not

=======
>>>>>>> 307031ac70a3889184a4963a98140a78ca828e8c
require_once CLASS_DIR.'dbconnect.php';
require_once CLASS_DIR.'packagingUnit.class.php';


$unitId     = $_GET['id'];
$unitName   = $_GET['unit-name'];


$PackagingUnits = new PackagingUnits();
$updateUnit = $PackagingUnits->updateUnit($unitId, $unitName, $employeeId, NOW);

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