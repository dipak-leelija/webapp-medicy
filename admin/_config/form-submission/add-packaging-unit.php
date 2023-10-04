<?php
require_once '../../../php_control/packagingUnit.class.php';

//Class initilization
$PackagingUnits = new PackagingUnits();

?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Manufacturer</title>
        <script src="../../../js/sweetAlert.min.js"></script>
</head>
<body>
<?php

if( isset($_POST['add-unit'])){
    $unitName  = $_POST['uni-name'];
    $addedby   = " ";
    $addPackagingUnits = $PackagingUnits->addPackagingUnit($unitName, $addedby);
    if ($addPackagingUnits) {

        //echo "<script>alert('Unit Added!'); window.location='../../packaging-unit.php';</script>";
        ?>
        <script>
            swal("Success", "Unit Added!", "success")
                .then((value) => {
                    window.location='../../packaging-unit.php';
                });
            </script>
        <?php
         
     }else{
        // echo "<script>alert('Unit Insertion Failed!'); window.location='../../packaging-unit.php';</script>";
         ?>
            <script>
            swal("Error", "Packaging Unit Addition Failed!", "error")
                .then((value) => {
                    window.location='../../packaging-unit.php';
                });
            </script>
         <?php
     }
     
 }

?>