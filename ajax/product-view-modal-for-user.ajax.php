<?php
require_once dirname(__DIR__) . '/config/constant.php';
require_once ROOT_DIR . '_config/sessionCheck.php';

require_once CLASS_DIR . 'dbconnect.php';
require_once CLASS_DIR . "products.class.php";
require_once CLASS_DIR . "quantityUnit.class.php";
require_once CLASS_DIR . "packagingUnit.class.php";
require_once CLASS_DIR . "itemUnit.class.php";
require_once CLASS_DIR . "productsImages.class.php";
require_once CLASS_DIR . "manufacturer.class.php";
require_once CLASS_DIR . "currentStock.class.php";

$Products       = new Products();
$PackagingUnits = new PackagingUnits();
$ItemUnit       = new ItemUnit;
$ProductImages  = new ProductImages();
$Manufacturer   = new Manufacturer();
$CurrentStock   = new CurrentStock();
$QuantityUnit   = new QuantityUnit;

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
    <style>
        #main-img {
            animation: show .5s ease;
        }

        @keyframes show {
            0% {
                opacity: 0;
                transform: scale(0.9);
            }

            100% {
                opacity: 1;
                transform: scale(1);
            }
        }


        .height-4 {
            height: 3rem;
        }

        .ob-cover {
            width: 100%;
            object-fit: cover;
        }

        #main-img {
            width: 18rem;
            height: 20rem;
            overflow: hidden;
            display: flex;
            align-items: center;
            justify-content: center;
        }
    </style>
</head>

<body>
    <?php
    if (isset($_GET['id'])) {

        $productId = $_GET['id'];
        $product        = json_decode($Products->showProductsById($_GET['id']));
        $product        = $product->data;
        $manuf          = json_decode($Manufacturer->showManufacturerById($product[0]->manufacturer_id));
        $itemstock      = $CurrentStock->showCurrentStocByPId($_GET['id']);
        $image          = json_decode($ProductImages->showImageById($_GET['id']));
        // print_r($image );

        if ($image->status) {
            $image = $image->data;
            foreach ($image as $image) {
                $Images[] = $image->image;
                $productId = $image->product_id;
            }
        } else {
            $Images[] = "medicy-default-product-image.jpg";
        }
        echo '<script>';
        echo 'var productId = ' . json_encode($productId) . '; console.log("pID-"+productId)';
        echo '</script>';

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

    ?>

        <div class="col-12 d-flex justify-content-center container-fluid" style="min-height: 50vh; max-width: 100vh;">

            <form action="_config\form-submission\add-new-product.php" enctype="multipart/form-data" method="post" id="update-product-data-from-user">
                <!-- product name row -->
                <div class="row">
                    <div class="col-12">
                        <div class="col-md-12">
                            <label class="mb-0 mt-1" for="product-name">Prodcut Name</label>
                            <input class="c-inp w-100 p-1" id="product-name" name="product-name" required>
                        </div>

                    </div>
                </div>

                <!-- product hsno and category row -->
                <div class="row mt-2">
                    <div class="d-flex col-12">
                        <div class="col-md-6">
                            <label class="mb-0 mt-1" for="product-catagory">Prodcut Catagory</label>
                            <select class="c-inp p-1 w-100" name="product-catagory" id="product-catagory" required>
                                <option value="" disabled selected>Select</option>
                                <?php
                                if ($prodCategory->status == 1 && is_array($prodCategory->data)) {
                                    $prodCategory = $prodCategory->data;

                                    foreach ($prodCategory as $category) {
                                        echo '<option value="' . $category->id . '">' . $category->prod_category_name . '</option>';
                                    }
                                }
                                ?>
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label class="mb-0 mt-1" for="packeging-type">Packeging In</label>
                            <select class="c-inp p-1 w-100" name="packeging-type" id="packeging-type" required>
                                <option value="" disabled selected>Select</option>
                                <?php
                                foreach ($packagingUnits as $eachPackUnit) {
                                    echo "<option value='{$eachPackUnit['id']}'>{$eachPackUnit['unit_name']}</option>";
                                }
                                ?>
                            </select>
                        </div>

                    </div>
                </div>

                <!-- catagory - packging - power and unit row  -->
                <div class="row mt-2">
                    <div class="d-flex col-12">
                        <div class="col-md-6">
                            <label class="mb-0 mt-1" for="medicine-power">Medicine Power</label>
                            <input class="c-inp w-100 p-1" id="medicine-power" name="medicine-power" required>
                        </div>

                        <div class="col-md-6">
                            <label class="mb-0 mt-1" for="unit">Unit</label>
                            <select class="c-inp p-1 w-100" id="unit" name="unit" required>
                                <option value='' disabled selected>Select</option>
                                <?php
                                foreach ($itemUnists as $eachUnit) {
                                    echo "<option value='" . $eachUnit['id'] . "'>" . $eachUnit['name'] . "</option>";
                                }
                                ?>
                            </select>


                        </div>
                    </div>
                </div>

                <!-- catagory - packging - power and unit row  -->
                <div class="row mt-2">
                    <div class="d-flex col-12">
                        <div class="col-md-6">
                            <label class="mb-0 mt-1" for="qantity-unit">Qantity</label>
                            <input class="c-inp w-100 p-1" id="qantity-unit" name="qantity-unit" required>

                        </div>
                        <div class="col-md-6">
                            <label class="mb-0 mt-1" for="mrp">Enter MRP</label>
                            <input class="c-inp w-100 p-1" id="mrp" name="mrp" required>
                        </div>
                    </div>
                </div>

                <!-- mrp, gst and hsno number row  -->
                <div class="row mt-4">
                    <div class="col-md-12 d-flex">
                        <div class="col-sm-6">
                            <label class="mb-0 mt-1" for="gst">Enter GST</label>
                            <select class="c-inp p-1 w-100" name="gst" id="gst" required>
                                <option value="" disabled selected>Select</option>
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
                            <input class="c-inp w-100 p-1" id="hsno-number" name="hsno-number" required>
                        </div>
                    </div>
                </div>

                <div class="row mt-4">
                    <div class="col-md-12">
                        <div class="col-sm-12 d-flex justify-content-center">
                            <button class="btn btn-primary col-sm-12" name="add-new-product" id="add-new-product" type="submit">Update</button>
                        </div>
                    </div>
                </div>
            </form>

        </div>
    <?php
    }
    ?>

    <script src="<?= PLUGIN_PATH ?>jquery/jquery.min.js"></script>
    <script src="<?= JS_PATH ?>bootstrap-js-4/bootstrap.min.js"></script>

    <!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.3/jquery.min.js" integrity="sha512-STof4xm1wgkfm7heWqFJVn58Hm3EtS31XFaagaa8VMReCXAkQnJZ+jEy8PCC/iT18dFy95WcExNHFTqLyp72eQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script> -->
    <script src="<?= JS_PATH ?>sweetalert2/sweetalert2.all.min.js"></script>
</body>

</html>