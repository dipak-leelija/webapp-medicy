<?php
require_once '../../php_control/doctor.category.class.php';

$updateDocCatId = $_GET['docCatId'];
$docCatName = $_GET['docCatName'];
$docCatDesc = $_GET['docCatDdsc'];

$DoctorCategory = new DoctorCategory();

$updateDocCateory = $DoctorCategory->updateDocCateory($docCatName, $docCatDesc, /*Last Variable for id which one you want to update */$updateDocCatId);
if ($updateDocCateory) {
    echo'<div class="alert alert-success fade show" role="alert">
    <strong>Success!</strong> Changes Has Saved Successfully..
</div>';
}else {
    echo'<div class="alert alert-warning fade show" role="alert">
    <strong>Failed!</strong> Updation Failed!
</div>';
}

?>