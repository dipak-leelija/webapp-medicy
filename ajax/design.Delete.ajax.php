<?php

require_once dirname(__DIR__) . '/config/constant.php';
require_once CLASS_DIR . 'dbconnect.php';
require_once CLASS_DIR . 'designation.class.php';

$deleteEmpId = $_POST['id'];

$desigRol = new Designation();
$desinDelete = $desigRol->deleteDesign($deleteEmpId);

if ($desinDelete) {
    echo 1;
} else {
    echo "Error: " . $this->conn->error;
}
