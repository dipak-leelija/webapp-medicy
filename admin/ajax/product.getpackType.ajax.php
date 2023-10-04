<?php
require_once '../../php_control/products.class.php';
require_once '../../php_control/packagingUnit.class.php';

$Products       = new Products();
$PackagingUnits = new PackagingUnits();
if (isset($_GET["id"])) {
    $showProducts = $Products->showProductsById($_GET["id"]);

    $showPackType = $PackagingUnits->showPackagingUnitById($showProducts[0]['packaging_type']);
    foreach ($showPackType as $row) {
       echo '<option value="'.$row["id"].'">'.$row["unit_name"].'</option>';

    }
}
// echo "Hi";

?>