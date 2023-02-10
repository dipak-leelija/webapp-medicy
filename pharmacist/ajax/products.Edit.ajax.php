<?php
require_once '../../php_control/products.class.php';

$Products = new Products();


if (isset($_POST['id'])) {

$updateProduct = $Products->updateProduct($_POST['id'], $_POST['product-name'], $_POST['medicine-power'], $_POST['manufacturer'], $_POST['product-descreption'], $_POST['packaging-type'], $_POST['unit-quantity'], $_POST['unit'], $_POST['mrp'], $_POST['gst'], $_POST['added-by'], $_POST['product-composition']);

if ($updateProduct == TRUE) {
   return true;
   
}

}
