<?php
require_once '../../php_control/products.class.php';

$Products     = new Products();
if (isset($_GET["id"])) {
    $showProducts = $Products->showProductsById($_GET["id"]);
    echo $showProducts[0]['power'];
}
// echo "Hi";

?>