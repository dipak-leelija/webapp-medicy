<?php
require_once dirname(__DIR__).'/config/constant.php';
require_once ROOT_DIR.'_config/sessionCheck.php'; //check admin loggedin or not

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
        <script src="<?= JS_PATH ?>sweetAlert.min.js"></script>
</head>
<body>

<?php

//Class initilization
$Manufacturer = new Manufacturer();

if(isset($_POST['add-manufacturer'])){
    $manufacturerName   = $_POST['manufacturer-name'];
    $manufacturerDsc    = $_POST['manufacturer-dsc'];
    $shortName          = $_POST['manufacturer-short-name'];
    $manufactureStatus  = 0;
    $newData            = 1;


    // last inserted manufacturer data fetch ---------
    $manufData = json_decode($Manufacturer->lastManufDataFetch());
    if($manufData != null){
        $manufId = intval($manufData->id) + 1;
    }else{
        $manufId = 1;
    }


    $addManufacturer = $Manufacturer->addManufacturer($manufId, $manufacturerName, $shortName, $manufacturerDsc, $employeeId, NOW, $manufactureStatus, $newData, $adminId);
        if ($addManufacturer) {
            ?> 
             <script>
            swal("Success", "Manufacturer Added!", "success")
                .then((value) => {
                    window.location = '<?= URL ?>manufacturers.php';
                });
            </script>
             <?php
        }else{
            ?>
            <script>
            swal("Error", "Manufacturer Addition Failed!", "error")
                .then((value) => {
                    window.location = '<?= URL ?>manufacturers.php';
                });
            </script>
            <?php
        }
}


// ============== ADD MANUFACTUERE ON ADD NEW PRODUCTS OR EDIT PRODUCTS ===================


if(isset($_POST['add-new-manuf'])){
    $manufacturerName   = $_POST['manuf-name'];
    $shortName          = $_POST['manuf-mark'];
    $manufacturerDsc    = $_POST['manuf-dsc'];
    $manufactureStatus  = 0;
    $newData            = 1;

    // last inserted manufacturer data fetch ---------
    $manufData = json_decode($Manufacturer->lastManufDataFetch());
    if($manufData != null){
        $manufId = intval($manufData->id) + 1;
    }else{
        $manufId = 1;
    }
    
    //Inserting Manufacturer Into Database
    $addManufacturer = $Manufacturer->addManufacturer($manufId, $manufacturerName, $shortName, $manufacturerDsc, $employeeId, NOW, $manufactureStatus, $newData, $adminId);
        if ($addManufacturer) {
            ?> 
             <script>
            swal("Success", "Manufacturer Adding Request Sent!", "success")
                .then((value) => {
                    window.location = '<?= URL ?>purchesmaster.php';
                });
            </script>
             <?php
        }else{
            ?>
            <script>
            swal("Error", "Manufacturer Addition Failed!", "error")
                .then((value) => {
                    window.location = '<?= URL ?>purchesmaster.php';
                });
            </script>
            <?php
        }
}
    ?>
</body>

</html>