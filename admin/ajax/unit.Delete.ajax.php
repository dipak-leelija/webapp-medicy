<?php
require_once dirname(dirname(__DIR__)).'/config/constant.php';
<<<<<<< HEAD
require_once ADM_DIR.'_config/sessionCheck.php'; //check admin loggedin or not

require_once CLASS_DIR.'dbconnect.php';
=======
require_once CLASS_DIR.'dbconnect.php';

>>>>>>> 6e5e8fa380a91b275b8dbed76bdf3e4a986b70aa
require_once CLASS_DIR.'measureOfUnit.class.php';


$unitId = $_POST['id'];

$MeasureOfUnits       = new MeasureOfUnits();
$deleteUnit           = $MeasureOfUnits->deleteUnit($unitId);

if ($deleteUnit) {
    echo 1;
}else {
    echo 0;
}


?>