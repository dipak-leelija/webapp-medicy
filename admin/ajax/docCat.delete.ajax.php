<?php
require_once '../../php_control/doctor.category.class.php';

$deleteDocCatId = $_POST['id']; 

$DoctorCategory = new DoctorCategory();

$deleteDocCat = $DoctorCategory->deleteDocCat($deleteDocCatId);

if ($deleteDocCat) {
    echo 1;
}else {
    echo 0;
}


?>