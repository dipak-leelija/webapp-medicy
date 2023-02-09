<?php
require_once '../../php_control/products.class.php';

$Products = new Products();

$updateProduct = $Products->updateProduct($_POST['id'], $_POST['name'], $_POST['power'], $_POST['manuf'], $_POST['dsc'], $_POST['packaging'], $_POST['unit-qty'], $_POST['unit'], $_POST['mrp'], $_POST['gst'], $_POST['added_by'], $_POST['id'] );
if ($updateProduct) {
   echo '<div class="alert alert-primary alert-dismissible fade show mt-2" role="alert">
            <strong>Success!</strong> Product Has Been Updated!
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>';
}else{
    echo '<div class="alert alert-danger alert-dismissible fade show mt-2" role="alert">
            <strong>Error!</strong> Product Updation Failed!
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
          </div>';
}


?>