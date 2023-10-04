<?php
require_once '../../../php_control/measureOfUnit.class.php';

//Class initilization
$MeasureOfUnits = new MeasureOfUnits();

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

    if (isset($_POST['add-unit'])) {
        $srtName  = $_POST['unit-srt-name'];
        $fullName = $_POST['unit-full-name'];

        $addMeasureOfUnits = $MeasureOfUnits->addMeasureOfUnits($srtName, $fullName);

        if ($addMeasureOfUnits) {
           // echo "<script>alert('Unit Added!'); window.location='../../product-unit.php';</script>";
    ?>

            <script>
                swal("Success", "Unit Added!", "success")
                    .then((value) => {
                        window.location = '../../product-unit.php';
                    });
            </script>
        <?php
            //   echo "<script>alert('Unit Added!')</script>";

        } else {
           // echo "<script>alert('Unit Insertion Failed!'); window.location='../../product-unit.php';</script>";
        ?>
            <script>
                swal("Error", "Unit Addition Failed!", "error")
                    .then((value) => {
                        window.location = '../../product-unit.php';
                    });
            </script>
    <?php
        }
    }

    ?>

</body>

</html>