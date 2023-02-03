<?php

require_once '../../php_control/sub-test.class.php';


$subTestId = $_GET['subtest_id'];

$SubTests = new SubTests();
$showSubTestsId = $SubTests->showSubTestsId($subTestId);

foreach($showSubTestsId as $rowsSubTest){
    $subTestName = $rowsSubTest['sub_test_name'];
    echo $subTestName;

}


?>