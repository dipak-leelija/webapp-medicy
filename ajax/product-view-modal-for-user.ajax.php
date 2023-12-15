<?php
require_once dirname(__DIR__) . '/config/constant.php';
require_once ROOT_DIR . '_config/sessionCheck.php';

require_once CLASS_DIR . 'dbconnect.php';
require_once CLASS_DIR . "products.class.php";
require_once CLASS_DIR . "productCategory.class.php";
require_once CLASS_DIR . "quantityUnit.class.php";
require_once CLASS_DIR . "packagingUnit.class.php";
require_once CLASS_DIR . "itemUnit.class.php";
require_once CLASS_DIR . 'gst.class.php';


$Products       = new Products();
$ProductCategory = new ProductCategory;
$PackagingUnits = new PackagingUnits();
$ItemUnit       = new ItemUnit;
$QuantityUnit   = new QuantityUnit;
$Gst = new Gst;


?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>

    <link href="<?php echo PLUGIN_PATH ?>fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">

    <!-- Fontawsome Link -->
    <link rel="stylesheet" href="<?php echo CSS_PATH ?>font-awesome.css">

    <!-- Custom styles for this template -->
    <link href="<?php echo CSS_PATH ?>sb-admin-2.min.css" rel="stylesheet">
    <!-- new features added -->
    <link href="<?php echo CSS_PATH ?>add-new-product.css" rel="stylesheet">
</head>

<body>
    <?php
    if (isset($_GET['id'])) {

        $productId      = $_GET['id'];
        $product        = json_decode($Products->showProductsById($_GET['id']));
        $product        = $product->data;
        // print_r($product);

        $prodCategory = json_decode($ProductCategory->selectAllProdCategory());
        $prodCategoryList = $prodCategory->data;

        if ($product[0]->type != null) {

            if ($product[0]->type == 'allopathy') {
                $prodCategoryId = '1';
                $prodCategoryName = 'Allopathy';
            } else {
                $prodType = json_decode($ProductCategory->selectAllProdCategoryById($product[0]->type));
                $prodType = $prodType->data;

                $prodCategoryId = $prodType[0]->id;
                $prodCategoryName = $prodType[0]->name;
            }
        }

        if ($product[0]->type == null) {

            $prodCategoryId = '';
            $prodCategoryName = 'Select';
        }



        $packetUnit = $PackagingUnits->showPackagingUnits();
        $pack = $PackagingUnits->showPackagingUnitById($product[0]->packaging_type);

        $itemQuantityUnit = $QuantityUnit->quantityUnitName($product[0]->unit_id);
        $itemQuantityUnit = json_decode($itemQuantityUnit, true);
        if ($itemQuantityUnit) {
            if (isset($itemQuantityUnit['data']['short_name'])) {
                $qantityName = $itemQuantityUnit['data']['short_name'];
            } else {
                $qantityName = '';
            }
        }

        $itemUnitName = $ItemUnit->itemUnitName($product[0]->unit);
        $itemUnit = $ItemUnit->showItemUnits();

        $gstData = json_decode($Gst->seletGst());
        $gstData = $gstData->data;


        if ($product[0]->gst != null) {
            if ($product[0]->gst == '0') {
                $prevGstId = '';
                $prevGstVal = 'Select';
            } else {
                $col = 'id';
                $gstVal = json_decode($Gst->seletGstByColVal($col, $product[0]->gst));
                $gstVal = $gstVal->data;

                $prevGstId = $product[0]->gst;
                $prevGstVal = $gstVal[0]->percentage;
            }
        } else {
            $prevGstId = '';
            $prevGstVal = 'Select';
        }

    ?>

        <div class="col-12 d-flex justify-content-center container-fluid" style="min-height: 50vh; max-width: 100vh;">

        <form action="../_config/form-submission/update-new-product-data.php" enctype="multipart/form-data" method="post" id="update-new-product-data">
                <!-- product name row -->
                <div class="row">
                    <div class="col-12">
                        <div class="col-md-12">
                            <label class="mb-0 mt-1" for="product-name">Prodcut Name</label>
                            <input class="c-inp w-100 p-1" id="product-name" name="product-name" value="<?php echo $product[0]->name ?>" required>

                            <input class="c-inp w-100 p-1" id="product-id" name="product-id" value="<?php echo $product[0]->product_id ?>" required hidden>
                        </div>

                    </div>
                </div>

                <!-- product packeging and category row -->
                <div class="row mt-2">
                    <div class="d-flex col-12">
                        <div class="col-md-6">
                            <label class="mb-0 mt-1" for="product-catagory">Prodcut Catagory</label>
                            <select class="c-inp p-1 w-100" name="product-catagory" id="product-catagory" required>

                                <option value='<?php echo $prodCategoryId; ?>'><?php echo $prodCategoryName; ?></option>

                                <?php

                                foreach ($prodCategoryList as $category) {

                                    echo '<option value="' . $category->id . '">' . $category->name . '</option>';
                                }

                                ?>

                            </select>
                        </div>

                        <div class="col-md-6">
                            
                            <label class="mb-0 mt-1" for="packeging-type">Packeging In</label>
                            <select class="c-inp p-1 w-100" name="packeging-type" id="packeging-type" required>
                                <option value="<?php echo $product[0]->packaging_type ?>"> <?php echo $pack[0]['unit_name']; ?></option>
                                <?php
                                foreach ($packetUnit as $eachPackUnit) {
                                    echo "<option value='{$eachPackUnit['id']}'>{$eachPackUnit['unit_name']}</option>";
                                }
                                ?>
                            </select>
                        </div>

                    </div>
                </div>

                <!-- power and unit row  -->
                <div class="row mt-2">
                    <div class="d-flex col-12">
                        <div class="col-md-6">
                            <label class="mb-0 mt-1" for="medicine-power">Medicine Power</label>
                            <input class="c-inp w-100 p-1" id="medicine-power" name="medicine-power" value="<?php echo $product[0]->power ?>" required>
                        </div>

                        <div class="col-md-6">
                            <label class="mb-0 mt-1" for="unit">Unit</label>
                            <select class="c-inp p-1 w-100" id="unit" name="unit" required>
                                <option value="<?php echo $product[0]->unit ?>"><?php echo $itemUnitName ?></option>
                                <?php
                                foreach ($itemUnit as $eachUnit) {
                                    echo "<option value='" . $eachUnit['id'] . "'>" . $eachUnit['name'] . "</option>";
                                }
                                ?>
                            </select>


                        </div>
                    </div>
                </div>

                <!-- qantity and mrp row  -->
                <div class="row mt-2">
                    <div class="d-flex col-12">
                        <div class="col-md-6">
                            <label class="mb-0 mt-1" for="qantity-unit">Qantity</label>
                            <input class="c-inp w-100 p-1" id="qantity-unit" name="qantity-unit" value="<?php echo $product[0]->unit_quantity ?>" required>

                        </div>
                        <div class="col-md-6">
                            <label class="mb-0 mt-1" for="mrp">Enter MRP</label>
                            <input class="c-inp w-100 p-1" id="mrp" name="mrp" value="<?php echo $product[0]->mrp ?>" required>
                        </div>
                    </div>
                </div>

                <!-- gst and hsno number row  -->
                <div class="row mt-4">
                    <div class="col-md-12 d-flex">
                        <div class="col-sm-6">
                            <label class="mb-0 mt-1" for="gst">Enter GST</label>
                            <select class="c-inp p-1 w-100" name="gst-percent" id="gst-percent" required>
                                <option value="<?php echo $prevGstId ?>"><?php echo $prevGstVal ?></option>
                                <?php
                                if (is_array($gstData)) {
                                    foreach ($gstData as $gstPercent) {
                                        echo '<option value="' . $gstPercent->id . '" >' . $gstPercent->percentage . '</option>';
                                    }
                                }
                                ?>
                            </select>
                        </div>

                        <div class="col-sm-6">
                            <label class="mb-0 mt-1" for="hsno-number">HSNO Number</label>
                            <input class="c-inp w-100 p-1" id="hsno-number" name="hsno-number" value="<?php echo $product[0]->hsno_number ?>" required>
                        </div>
                    </div>
                </div>

                <div class="row mt-4">
                    <div class="col-md-12 d-flex justify-content-center">
                        <button class="btn btn-primary col-sm-12" name="update-new-product-data" id="update-new-product-data" type="submit">Add</button>
                    </div>
                </div>
            </form>

        </div>
    <?php
    }
    ?>
    <!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.3/jquery.min.js" integrity="sha512-STof4xm1wgkfm7heWqFJVn58Hm3EtS31XFaagaa8VMReCXAkQnJZ+jEy8PCC/iT18dFy95WcExNHFTqLyp72eQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script> -->
    <script src="<?= JS_PATH ?>sweetalert2/sweetalert2.all.min.js"></script>
</body>

</html>