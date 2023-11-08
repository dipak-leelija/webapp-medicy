<?php
require_once dirname(dirname(dirname(__DIR__))).'/config/constant.php';
require_once ADM_DIR.'_config/sessionCheck.php'; //check admin loggedin or not

require_once CLASS_DIR.'dbconnect.php';
require_once CLASS_DIR.'manufacturer.class.php';

//Class initilization
$Manufacturer = new Manufacturer();


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

//Class initilization
$Manufacturer = new Manufacturer();

if(isset($_POST['add-manufacturer'])){
    $manufacturerName = $_POST['manufacturer-name'];
    $manufacturerName = str_replace("<", "&lt", $manufacturerName);
    $manufacturerName = str_replace(">", "&gt", $manufacturerName);
    $manufacturerName = str_replace("'", "&#39", $manufacturerName);



    $manufacturerDsc = $_POST['manufacturer-dsc'];
    $manufacturerDsc = str_replace("<", "&lt", $manufacturerDsc);
    $manufacturerDsc = str_replace(">", "&gt", $manufacturerDsc);
    $manufacturerDsc = str_replace("'", "&#39", $manufacturerDsc);
    // echo $manufacturerDsc;

    $shortName = $_POST['manufacturer-short-name'];
    $shortName = str_replace("<", "&lt", $shortName);
    $shortName = str_replace(">", "&gt", $shortName);
    $shortName = str_replace("'", "&#39", $shortName);

    //Inserting Manufacturer Into Database
    $addManufacturer = $Manufacturer->addManufacturer( $manufacturerName, $shortName, $manufacturerDsc, $employeeId, NOW, $adminId);
        if ($addManufacturer) {
            ?> 
             <script>
            swal("Success", "Manufacturer Added!", "success")
                .then((value) => {
                    window.location='../../manufacturers.php';
                });
            </script>
             <?php
        }else{
            ?>
            <script>
            swal("Error", "Manufacturer Addition Failed!", "error")
                .then((value) => {
                    window.location='../../manufacturers.php';
                });
            </script>
            <?php
        }
}
    ?>
</body>

</html>