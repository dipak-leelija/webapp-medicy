<?php

require_once dirname(__DIR__) . '/config/constant.php';
require_once CLASS_DIR.'dbconnect.php';
require_once CLASS_DIR . 'UtilityFiles.class.php';
require_once CLASS_DIR . 'labTestTypes.class.php';

$delTestTypeId = $_POST['delId'];

$labTypes = new LabTestTypes;

// $delLabType = $labTypes->deleteLabTypes($delTestTypeId);
// print_r($delLabType);
// if ($delLabType){
//     header("location: lab-tests.php");
// 	echo "<script>alert('Record Deleted!')</script>";

// }else{
//   echo "<script>alert('Deletion Faield')</script>";
// }

echo $delTestTypeId;

?>