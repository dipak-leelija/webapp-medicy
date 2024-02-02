<?php
require_once dirname(__DIR__).'/config/constant.php';
require_once ROOT_DIR.'_config/sessionCheck.php'; //check admin loggedin or not

require_once CLASS_DIR.'dbconnect.php';
require_once CLASS_DIR.'manufacturer.class.php';


$manufacturerId     = $_GET['id'];
$manufacturerName   = $_GET['name'];
$manufShortName     = $_GET['sname'];
$manufacturerDsc    = $_GET['dsc'];

$Manufacturer = new Manufacturer();

$showManufacturer = $Manufacturer->showManufacturerById($manufacturerId);
$showManufacturer = json_decode($showManufacturer,true);

if(isset($showManufacturer['status']) && $showManufacturer['status'] == '1'){
    $data = $showManufacturer['data'];
    // if(!empty($data)){
    //     $manufacturerName = $data['name'];
    //     $shortName        = $data['short_name'];
    //     $manufacturerDsc  = $data['dsc']; 
    // }

    if(!empty($data)){
        if($data['name'] != $manufacturerName){
            $manuEditName = 'Edited Manufacturer Name .';
        }else{
            $manuEditName = '';
        }
        if($data['short_name'] != $manufShortName){
            $manuEditShortName = 'Edited Manufacturer Short Name.';
        }else{
            $manuEditShortName = '';
        }
        if($data['dsc'] != $manufacturerDsc){
            $manuEditDsc = 'Edited Manufacturer Description.';
        }else{
            $manuEditDsc = '';
        }

        $reqDescription = $manuEditName . $manuEditShortName . $manuEditDsc;
    }
}
$updateManufacturer = $Manufacturer->insertRequestManufacturer($manufacturerId,$manufacturerName, $manufShortName, $manufacturerDsc,$reqDescription, NOW , $adminId);


//check if the data has been updated or not
if($updateManufacturer){
   echo '<div class="alert alert-primary alert-dismissible fade show" role="alert">
   <strong>Success!</strong> Manufacturer Has been Requested !
   <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
   </div>';
    
}else {
    echo '<div class="alert alert-warning alert-dismissible fade show" role="alert">
    <strong>Failed!</strong> Manufacturer Request Failed!
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>';
}


?>