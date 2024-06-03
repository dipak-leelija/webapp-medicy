<?php

require_once dirname(__DIR__) . '/config/constant.php';
require_once CLASS_DIR . 'dbconnect.php';
require_once CLASS_DIR . 'UtilityFiles.class.php';
require_once CLASS_DIR . 'labTestTypes.class.php';
require_once CLASS_DIR . 'sub-test.class.php';

$delTestTypeId = $_POST['delId'];

$labTypes = new LabTestTypes;
$SubTestClass = new SubTests;

$returnData = '';

$delLabType = json_decode($labTypes->deleteLabTypes($delTestTypeId));

if ($delLabType->status) {
    $chekExistance = json_decode($SubTestClass->showSubTestsByCatId($delTestTypeId));
    if($chekExistance->status){
        $delSubTestData = json_decode($SubTestClass->deleteSubTests($delTestTypeId));
        if($delSubTestData->status){
            $returnData = '1';
        }else{
            $returnData = '0';
        }
    }else{
        $returnData = '1';
    }
}else{
    $returnData = '0';
}

if ($delLabType) {
    echo $returnData;
} else {
    echo $returnData;
}

// echo $delTestTypeId;
