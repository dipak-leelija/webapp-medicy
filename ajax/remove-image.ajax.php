<?php
require_once dirname(__DIR__).'/config/constant.php';
require_once ROOT_DIR.'_config/sessionCheck.php'; //check admin loggedin or not

require_once CLASS_DIR.'dbconnect.php';

require_once CLASS_DIR.'productsimages.class.php';

$imageId = $_POST['imageID'];

$ProductImages = new ProductImages;

$deleteImg = $ProductImages->deleteImage($imageId) ;

if($deleteImg){
    echo "selected image delete";
}else{
    echo "selected image not delete";
}

?>