<?php
require_once dirname(__DIR__).'/config/constant.php';

require_once CLASS_DIR.'dbconnect.php';
require_once CLASS_DIR.'Pathology.class.php';


$subTestId = $_GET['subtest_id'];

$Pathology = new Pathology();
$showSubTest = $Pathology->showTestById($subTestId);
echo $showSubTest['name'];
?>