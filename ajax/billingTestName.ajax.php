<?php
require_once dirname(__DIR__).'/config/constant.php';

require_once CLASS_DIR.'dbconnect.php';
require_once CLASS_DIR.'Pathology.class.php';


$subTestId = $_GET['subtest_id'];

$Pathology = new Pathology();
$showSubTestsId = $Pathology->showTestById($subTestId);

foreach($showSubTestsId as $rowsSubTest){
    $subTestName = $rowsSubTest['name'];
    echo $subTestName;

}


?>