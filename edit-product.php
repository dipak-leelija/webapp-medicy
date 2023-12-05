<?php
require_once 'config/constant.php';
require_once ROOT_DIR . '_config/sessionCheck.php'; //check admin loggedin or not

require_once CLASS_DIR . 'dbconnect.php';
require_once CLASS_DIR . 'products.class.php';
require_once CLASS_DIR . 'productsImages.class.php';
require_once CLASS_DIR . 'manufacturer.class.php';
require_once CLASS_DIR . 'measureOfUnit.class.php';
require_once CLASS_DIR . 'packagingUnit.class.php';
require_once CLASS_DIR . 'itemUnit.class.php';



//objects Initilization
$Products           = new Products();
$Manufacturer       = new Manufacturer();
$MeasureOfUnits     = new MeasureOfUnits();
$PackagingUnits     = new PackagingUnits();
$ProductImages      = new ProductImages();
$ItemUnit           = new ItemUnit();


$showManufacturer   = json_decode($Manufacturer->showManufacturerWithLimit());
$showMeasureOfUnits = $MeasureOfUnits->showMeasureOfUnits();
$showPackagingUnits = $PackagingUnits->showPackagingUnits();
$itemUnits          = $ItemUnit->showItemUnits();
?>


<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Add Items</title>

    <!-- Custom fonts for this template -->
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">

    <!-- Fontawsome Link -->
    <link href="<?= PLUGIN_PATH ?>fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link href="<?= CSS_PATH ?>font-awesome.css" rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="<?= CSS_PATH ?>sb-admin-2.min.css" rel="stylesheet">

    <!--Custom CSS -->
    <link href="<?php echo CSS_PATH ?>custom/add-products.css" rel="stylesheet">
    <link rel="stylesheet" href="<?= CSS_PATH ?>custom-dropdown.css">

    <!-- <link href="<?= PLUGIN_PATH ?>choices/assets/styles/choices.min.css" rel="stylesheet" /> -->

    <!-- sweetAlert link -->
    <script src="<?= JS_PATH ?>sweetAlert.min.js"></script>


</head>

<body id="page-top">
    <?php
    if (isset($_GET['id'])) {

        $productId = $_GET['id'];

        if (isset($_POST['update-product'])) {

            $productName      = $_POST['product-name'];

            $productComp1     = $_POST['product-composition1'];
            $productComp2     = $_POST['product-composition2'];
            $manufacturer     = $_POST['manufacturer'];

            // $searchTerms      = $_POST['search_terms'];

            $quantity         = $_POST['quantity'];
            $qtyUnit          = $_POST['qty-unit'];
            $itemUnit         = $_POST['item-unit'];
            $packagingType    = $_POST['packaging-type'];


            $medicinePower    = $_POST['medicine-power'];
            $mrp              = $_POST['mrp'];
            $gst              = $_POST['gst'];
            $productDesc      = $_POST['product-description'];

            // for img //
            $imageName        = $_FILES['img-files']['name'];
            $tempImgName       = $_FILES['img-files']['tmp_name'];
            $imageArrayCaount = count($imageName);
            $tempImageNameArrayCaount = count($tempImgName);
            print_r($tempImgName);

            $updateProduct = $Products->updateProduct($productId, $productName, $manufacturer, $type = '', $productComp1, $productComp2, $medicinePower, $productDesc, $quantity, $qtyUnit, $itemUnit, $packagingType, $mrp, $gst, $employeeId, NOW);

            if ($updateProduct === true) {

                for ($j = 0; $j < $imageArrayCaount && $j < $tempImageNameArrayCaount; $j++) {
                    ////////// RANDOM 12DIGIT STRING GENERATOR FOR IMAGE NAME PRIFIX \\\\\\\\\\\\\
    
                    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
                    $randomString = '';
                    for ($i = 0; $i < 9; $i++) {
                        $randomString .= $characters[rand(0, strlen($characters) - 1)];
                    }
    
                    $randomString = $randomString;
    
                    ////////////////////////////\\\\\\\\\\\\\\\\\\\\\\\
                    //===== Main Image 
                    $image         = $imageName[$j];
    
    
                    if ($image) {
    
                        $ImgNm = '';
                        $extention = '';
                        $countImageLen = 0;
    
    
                        // echo "<br>Checking image name on entry : $image";
    
                        if ($image != '') {
                            if ($image != null) {
                                if (file_exists(PROD_IMG_PATH . $randomString . '_' . $image)) {
                                    $image = 'medicy-' . $randomString . $image;
                                }
                            }
    
                            $countImageLen = strlen($image);
                            for ($l = 0; $l < intval($countImageLen) - 4; $l++) {
                                $ImgNm .= $image[$l];
                            }
                            for ($k = intval($countImageLen) - 4; $k < $countImageLen; $k++) {
                                $extention .= $image[$k];
                            }
    
                            $image         = $ImgNm . '-' . $randomString . $extention;
                            $imgFolder     = PROD_IMG_DIR . $image;
    
                            move_uploaded_file($tempImgname, $imgFolder);
                            $image         = addslashes($image);
                        }
    
                        if ($image == '') {
                            $image = '';
                        }
    
                        $setPriority = '';
    
                        $updateImage = $ProductImages->updateImage($productId, $image);
                    } else {
                        $addImage = true;
                    }
                }
            }

            

        //     $updateImage = true;
        //     if ($updateProduct === true) {
        //         if ($updateImage === true) {
        //      ?>
        //             <script>
        //                 swal("Success", "Product updated successfully!", "success").then((value) => {
        //                     parent.location.reload();
        //                 });
        //             </script>
        // <?php
        //         }
        //     }
        }


        // ===================== Fetching Product Details =====================

        $product = json_decode($Products->showProductsById($productId));
        $product = $product->data;

        $productName    = $product[0]->name;
        $manufacturer   = $product[0]->manufacturer_id;
        $manufData = json_decode($Manufacturer->showManufacturerById($manufacturer));
        $manufacturerName = ($manufData->status == 1 && isset($manufData->data)) ? $manufData->data->name : 'unable to retrieve';

        $qty            = $product[0]->unit_quantity;
        $qtyUnit        = $product[0]->unit_id;
        $itemUnit       = $product[0]->unit;
        $packagingType  = $product[0]->packaging_type;
        $type           = $product[0]->type;
        $power          = $product[0]->power;
        $dsc            = $product[0]->dsc;
        $mrp            = $product[0]->mrp;
        $gst            = $product[0]->gst;
        $comp1          = $product[0]->comp_1;
        $comp2          = $product[0]->comp_2;
        $added_by       = $product[0]->added_by;
        $added_on       = $product[0]->added_on;
        $updated_by     = $product[0]->updated_by;
        $updated_on     = $product[0]->updated_on;
        $admin_id       = $product[0]->admin_id;


        $images = json_decode($ProductImages->showImageById($productId));

        $allImg = array();
        if ($images->status == 1 && !empty($images->data)) {
            foreach ($images->data as $image) {
                $allImg[] = $image->image;
            }
        } else {
            $allImg[] = "medicy-default-product-image.jpg";
        }

        ?>

        <!-- Page Wrapper -->
        <div id="wrapper">

            <!-- Content Wrapper -->
            <div id="content-wrapper" class="d-flex flex-column">

                <!-- Main Content -->
                <div id="content">
                    <!-- Add Product -->
                    <div class="card shadow mb-4 h-100">
                        <div class="card-body">
                            <form action="<?= htmlspecialchars(CURRENT_URL); ?>" method="post" enctype="multipart/form-data">
                                <div class="d-flex">
                                    <div class="col-md-7">
                                        <div class="row">
                                            <div class="col-md-12">
                                                <input class="c-inp w-100 p-1" id="product-name" name="product-name" placeholder="Product Name" value="<?= $productName ?>" required>
                                            </div>
                                        </div>
                                        <br>
                                        <div class="row">
                                            <div class="d-flex col-md-12">
                                                <div class="col-md-6">
                                                    <input class="c-inp w-100 p-1" id="product-composition1" name="product-composition1" placeholder="Product Composition 1" value="<?= $comp1  ?>" required>
                                                </div>

                                                <div class="col-md-6">
                                                    <input class="c-inp w-100 p-1" id="product-composition2" name="product-composition2" placeholder="Product Composition 2" value="<?= $comp2  ?>" required>
                                                </div>

                                            </div>
                                        </div>

                                        <!-- manufacturer row -->
                                        <div class="row">
                                            <div class="col-md-12 mt-2">

                                                <input type="text" name="manufacturer" id="manufacturer" class="c-inp w-100 p-1" disable hidden>

                                                <input type="text" name="manufacturer-id" id="manufacturer-id" value="<?= $manufacturerName ?>" class="c-inp w-100 p-1">

                                                <div class="p-2 bg-light col-md-12 c-dropdown" id="manuf-list" style="display: none;">
                                                    <div class="lists" id="lists">
                                                        <!-- <?php
                                                                if (!empty($showManufacturer)) {
                                                                    foreach ($showManufacturer as $eachManuf) {
                                                                        // print_r($eachManuf);
                                                                ?>
                                                                <div class="p-1 border-bottom list" id="<?= $eachManuf->id ?>" onclick="setManufacturer(this)">
                                                                    <?= $eachManuf->name ?>
                                                                </div>
                                                            <?php
                                                                    }
                                                            ?> -->
                                                    </div>

                                                    <div class="d-flex flex-column justify-content-center mt-1" data-toggle="modal" data-target="#add-manufacturer" onclick="addManufacturer()">
                                                        <button type="button" id="add-manuf-btn" class="text-primary border-0">
                                                            <i class="fas fa-plus-circle"></i>
                                                            Add Now
                                                        </button>
                                                    </div>

                                                <?php
                                                                } else {
                                                ?>
                                                    <p class="text-center font-weight-bold">Manufacturer Not Found!</p>
                                                    <div class="d-flex flex-column justify-content-center" data-toggle="modal" data-target="#add-manufacturer" onclick="addManufacturer()">
                                                        <button type="button" id="add-manuf-btn" class="text-primary border-0 mt-2"><i class="fas fa-plus-circle"></i>
                                                            Add Now</button>
                                                    </div>
                                                <?php
                                                                }
                                                ?>
                                                </div>

                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-3 mt-4">
                                                <input type="number" class="c-inp p-1 w-100" name="quantity" id="quantity" placeholder="Enter Quantity" value="<?= $qty ?>">
                                            </div>

                                            <div class="col-md-3 mt-4">
                                                <select class="c-inp p-1 w-100" name="qty-unit" id="qty-unit">
                                                    <option value="" disabled selected>Select Quantity Unit</option>
                                                    <?php
                                                    foreach ($showMeasureOfUnits as $rowUnit) {
                                                    ?>
                                                        <option <?= $qtyUnit == $rowUnit['id'] ? 'selected' : ''; ?> value="<?= $rowUnit['id']; ?>">
                                                            <?= $rowUnit['short_name']; ?></option>';
                                                    <?php
                                                    }
                                                    ?>
                                                </select>
                                            </div>

                                            <div class="col-md-3 mt-4">
                                                <select class="c-inp p-1 w-100" name="item-unit" id="item-unit">
                                                    <option value="" disabled selected>Select Item Unit</option>
                                                    <?php
                                                    foreach ($itemUnits as $eachUnit) {
                                                    ?>
                                                        <option <?= $itemUnit == $eachUnit['id'] ? 'selected' : ''; ?> value="<?= $eachUnit['id']; ?>">
                                                            <?= $eachUnit['name']; ?></option>';
                                                    <?php
                                                    }
                                                    ?>
                                                </select>
                                            </div>

                                            <div class="col-md-3 mt-4">
                                                <!-- <label class="mb-0 mt-1" for="packaging-unit">Packaging Type</label> -->
                                                <select class="c-inp p-1 w-100" name="packaging-type" id="packaging-type">
                                                    <option value="" disabled selected>Packaging Type</option>
                                                    <?php
                                                    foreach ($showPackagingUnits as $packType) {
                                                    ?>
                                                        <option <?= $packagingType == $packType['id'] ? 'selected' : ''; ?> value="<?= $packType['id'] ?>">
                                                            <?= $packType['unit_name'] ?>
                                                        </option>';
                                                    <?php
                                                    }
                                                    ?>
                                                </select>
                                            </div>
                                        </div>
                                        <!--/End Price Row -->
                                        <br>
                                        <div class="row">
                                            <div class="col-md-4">
                                                <input class="c-inp w-100 p-1" type="text" name="medicine-power" id="medicine-power" placeholder="Enter Medicine Power" value="<?= $power ?>">
                                            </div>

                                            <div class="col-md-4">
                                                <input type="number" class="c-inp w-100 p-1" name="mrp" id="mrp" placeholder="Enter MRP" onkeyup="getMarginMrp(this.value)" step="0.01" value="<?= $mrp; ?>">
                                            </div>

                                            <div class="col-md-4">
                                                <select class="c-inp w-100 p-1" name="gst" id="gst" onchange="getMarginGst(this.value)">
                                                    <option <?= $gst == "0" ? 'selected' : ''; ?> value="0">0</option>
                                                    <option <?= $gst == "5" ? 'selected' : ''; ?> value="5">5</option>
                                                    <option <?= $gst == "12" ? 'selected' : ''; ?> value="12">12</option>
                                                    <option <?= $gst == "18" ? 'selected' : ''; ?> value="18">18</option>
                                                    <option <?= $gst == "28" ? 'selected' : ''; ?> value="28">28</option>
                                                </select>
                                            </div>
                                        </div>

                                        <div class="col-md-12 mt-4">
                                            <textarea class="c-inp w-100 p-1" name="product-description" id="product-description" placeholder="Product Description" cols="30" rows="3"><?= $dsc ?></textarea>
                                        </div>
                                    </div>

                                    <div class="col-md-5">
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="border p-1 rounded">
                                                    <div class="row height-3 mt-2 justify-content-center">
                                                        <?php foreach ($allImg as $index => $imagePath) : ?>
                                                            <div class="col-2 border p-2">
                                                                <img src="<?= PROD_IMG_PATH ?><?php echo $imagePath; ?>" id="img-<?php echo $index; ?>" onclick="setImg(this.id)" class="rounded ob-cover h-100" alt="...">
                                                            </div>
                                                        <?php endforeach; ?>
                                                    </div>
                                                </div>
                                                <div id="img-div">
                                                    <div class="container-fluid" id="img-container">
                                                        <input type="file" name="img-files[]" id="img-file-input" accept=".jpg,.png" onchange="preview()" multiple>
                                                        <label for="img-file-input" id="img-container-label">Choose Images &nbsp;<i class="fas fa-upload"></i></label>
                                                        <p id="num-of-files">No files chosen</p>
                                                        <div>
                                                            <div id="images">

                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <!--/End Product Image Row  -->
                                        <br>
                                        <div class="row">
                                            <div class="col-md-12">
                                                <!-- <button class="btn btn-danger mr-3" id="reset" type="button">Reset</button> -->
                                                <button class="btn btn-primary" name="update-product" id="update-btn" type="submit">Update</button>
                                            </div>

                                        </div>
                                    </div>
                                </div>
                        </div>
                        </form>
                    </div>
                </div>
                <!-- /end Add Product  -->
            <?php
        }
            ?>
            </div>
            <!-- End of Content Wrapper -->

        </div>
        <!-- End of Page Wrapper -->

        <!-- Bootstrap core JavaScript-->
        <script src="<?= PLUGIN_PATH ?>jquery/jquery.min.js"></script>
        <script src="<?= JS_PATH ?>bootstrap-js-4/bootstrap.bundle.min.js"></script>
        <!-- <script src="<?= PLUGIN_PATH ?>choices/assets/scripts/choices.js"></script> -->

        <!-- Custom scripts for all pages-->
        <script src="<?= JS_PATH ?>sb-admin-2.min.js"></script>
        <script src="<?= JS_PATH ?>custom/add-products.js"></script>
        <!-- Sweet Alert Js  -->
        <script src="<?= JS_PATH ?>sweetAlert.min.js"></script>

        <script src="<?= JS_PATH ?>ajax.custom-lib.js"></script>




        <script>
            const customClick1 = (id) => {
                document.getElementById(id).click();

            }

            const customClick2 = (id) => {
                document.getElementById(id).click();
            }


            //calculating profit only after entering MRP
            function getMarginMrp(value) {
                this.value = parseFloat(this.value).toFixed(2);
                const mrp = parseFloat(value);
                const ptr = parseFloat(document.getElementById("ptr").value);
                const gst = parseFloat(document.getElementById("gst").value);

                var profit = (mrp - ptr);

                profit = parseFloat(profit - ((gst / 100) * ptr));

                document.getElementById("profit").value = profit.toFixed(2);
            }


            //calculate after entering PTR
            function getMarginPtr(value) {
                const ptr = parseFloat(value);
                const mrp = parseFloat(document.getElementById("mrp").value);
                const gst = parseFloat(document.getElementById("gst").value);

                var profit = parseFloat(mrp - ptr);

                profit = parseFloat(profit - ((gst / 100) * ptr));

                document.getElementById("profit").value = profit.toFixed(2);
            }

            //calculate after entering GST
            function getMarginGst(value) {
                const gst = parseFloat(value);
                const ptr = parseFloat(document.getElementById("ptr").value);
                const mrp = parseFloat(document.getElementById("mrp").value);

                var profit = parseFloat(mrp - ptr);

                profit = parseFloat(profit - ((gst / 100) * ptr));

                document.getElementById("profit").value = profit.toFixed(2);
            }
            //image selection//
        </script>
        <script>
            // productViewAndEdit = (productId) => {
            //     // alert("productModalBody");
            //     let ViewAndEdit = productId;
            //     let url = "ajax/products.View.ajax.php?id=" + ViewAndEdit;
            //     $(".productModalBody").html(
            //         '<iframe width="99%" height="520px" frameborder="0" allowtransparency="true" src="' +
            //         url + '"></iframe>');
            // }

            // function update(e) {
            //     btnID = e.id;
            //     btn = this;
            //     $.ajax({
            //         url: "ajax/products.Edit.ajax.php",
            //         type: "POST",
            //         data: {
            //             id: btnID
            //         },
            //         success: function(data) {
            //             if (data == 1) {
            //                 Swal.fire({
            //                     position: 'top-end',
            //                     icon: 'success',
            //                     title: 'Your work has been saved',
            //                     showConfirmButton: false,
            //                     timer: 1500
            //                 }).then(function() {
            //                         parent.location.reload();
            //                     })

            //             } else {
            //                 $("#error-message").html("Deletion Field !!!")
            //                     .slideDown();
            //                 $("success-message").slideUp();
            //             }

            //         }
            //     });

            //     return false;
            // }

            //========================= Delete Product =========================
            // $(document).ready(function() {
            //     $(document).on("click", "#delete-btn", function() {

            //         swal({
            //                 title: "Are you sure?",
            //                 text: "Want to Delete This Manufacturer?",
            //                 icon: "warning",
            //                 buttons: true,
            //                 dangerMode: true,
            //             })
            //             .then((willDelete) => {
            //                 if (willDelete) {

            //                     productId = $(this).data("id");
            //                     btn = this;

            //                     $.ajax({
            //                         url: "ajax/product.Delete.ajax.php",
            //                         type: "POST",
            //                         data: {
            //                             id: productId
            //                         },
            //                         success: function(data) {
            //                             // alert(data);
            //                             if (data == 1) {
            //                                 $(btn).closest("tr").fadeOut()
            //                                 swal("Deleted", "Manufacturer Has Been Deleted",
            //                                     "success");
            //                             } else {
            //                                 swal("Failed", "Product Deletion Failed!",
            //                                     "error");
            //                                 $("#error-message").html("Deletion Field !!!")
            //                                     .slideDown();
            //                                 $("success-message").slideUp();
            //                             }
            //                         }
            //                     });

            //                 }
            //                 return false;
            //             });

            //     })

            // })
        </script>
        <script>
            $(document).on("click", ".back", function() {
                var backFile = $(this).parents().find(".back-file");
                backFile.trigger("click");
            });
            $('.back-file').change(function(e) {
                $(".back-img-field").hide();
                $("#back-preview").show();


                var fileName = e.target.files[0].name;
                $("#back-file").val(fileName);

                var reader = new FileReader();
                reader.onload = function(e) {
                    // get loaded data and render thumbnail.
                    document.getElementById("back-preview").src = e.target.result;
                };
                // read the image file as a data URL.
                reader.readAsDataURL(this.files[0]);
            });
        </script>

        <script>
            $(document).on("click", ".side", function() {
                var SideFile = $(this).parents().find(".side-file");
                SideFile.trigger("click");
            });
            $('.side-file').change(function(img) {
                $(".side-img-field").hide();
                $("#side-preview").show();


                var sideImgName = img.target.files[0].name;
                $("#side-file").val(sideImgName);

                var reader = new FileReader();
                reader.onload = function(img) {
                    // get loaded data and render thumbnail.
                    document.getElementById("side-preview").src = img.target.result;
                };
                // read the image file as a data URL.
                reader.readAsDataURL(this.files[0]);
            });
        </script>


        <!-- <script>
            document.addEventListener('DOMContentLoaded', function() {
                new Choices('#manufacturer', {
                    allowHTML: true,
                    removeItemButton: true,
                });
            });
        </script> -->


</body>

</html>