<?php
require_once '../php_control/labtypes.class.php';
$delTestTypeId = $_GET['deletetestype'];

$labTypes = new LabTypes();

$delLabType = $labTypes->deleteLabTypes($delTestTypeId);
if ($delLabType){
    header("location: lab-tests.php");
	echo "<script>alert('Record Deleted!')</script>";

}else{
  echo "<script>alert('Deletion Faield')</script>";
}

?>