<?php
require_once '../../php_control/products.class.php';

$Products       = new Products();

if (isset($_GET["id"])) {
    $showProducts = $Products->showProductsById($_GET["id"]);
    if ($showProducts) {
        echo $showProducts[0]['unit_quantity'];
    }
}
// echo "Hi";

?>