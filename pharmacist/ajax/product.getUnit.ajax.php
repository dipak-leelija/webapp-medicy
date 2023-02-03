<?php
require_once '../../php_control/products.class.php';
// require_once '../../php_control/measureOfUnit.class.php';

$Products       = new Products();
// $MeasureOfUnits = new MeasureOfUnits();
if (isset($_GET["id"])) {
    // echo $_GET["id"];
    $showProducts = $Products->showProductsById($_GET["id"]);
        echo $showProducts[0]['unit'];
  
}
// echo "Hi";

?>