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
    <link href="<?= CSS_PATH ?>custom/add-products.css" rel="stylesheet">
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

            $searchTerms      = $_POST['search_terms'];

            $quantity         = $_POST['quantity'];
            $qtyUnit          = $_POST['qty-unit'];
            $itemUnit         = $_POST['item-unit'];
            $packagingType    = $_POST['packaging-type'];


            $medicinePower    = $_POST['medicine-power'];
            $mrp              = $_POST['mrp'];
            $gst              = $_POST['gst'];
            $productDesc      = $_POST['product-description'];

            // $productId = $_POST['imgid'];
            // $imageCheck = $ProductImages->showImageById($productId);
            // // print_r($imageCheck); echo "<br><br>";
            // foreach ($imageCheck as $imgChk) {
            //     $mnImgChk = $imgChk['image'];
            //     $sideImgChk = $imgChk['side_image'];
            //     $bkImgChk = $imgChk['back_image'];
            // }

            // //print_r($_FILES)

            // //===== Main Image 
            // $image         = $_FILES['product-image']['name'];
            // $tempImgname   = $_FILES['product-image']['tmp_name'];
            // if ($image != null) {
            //     if (file_exists("../../../images/product-image/" . $image)) {
            //         $image = 'medicy-' . $image;
            //     }
            // } elseif ($image == null) {
            //     if ($mnImgChk != null) {
            //         $image = $mnImgChk;
            //     }
            // }

            // $imgFolder     = "../images/product-image/" . $image;
            // move_uploaded_file($tempImgname, $imgFolder);
            // $image         = addslashes($image);

            // //===== Back Image 
            // $backImage         = $_FILES['back-image']['name'];
            // $tempBackImg       = $_FILES['back-image']['tmp_name'];
            // if ($backImage != null) {
            //     if (file_exists("../../../images/product-image/" . $backImage)) {
            //         $backImage = 'medicy-' . $backImage;
            //     }
            // } elseif ($backImage == null) {
            //     if ($bkImgChk != null) {
            //         $backImage = $bkImgChk;
            //     }
            // }

            // $imgFolder     = "../images/product-image/" . $backImage;
            // move_uploaded_file($tempBackImg, $imgFolder);
            // $backImage         = addslashes($backImage);

            // //===== Side Image 
            // $sideImage         = $_FILES['side-image']['name'];
            // $tempSideImg       = $_FILES['side-image']['tmp_name'];
            // if ($backImage != null) {
            //     if (file_exists("../../../images/product-image/" . $sideImage)) {
            //         $sideImage = 'medicy-' . $sideImage;
            //     }
            // } elseif ($sideImage == null) {
            //     if ($sideImgChk != null) {
            //         $sideImage = $sideImgChk;
            //     }
            // }

            // $imgFolder         = "../images/product-image/" . $sideImage;
            // move_uploaded_file($tempSideImg, $imgFolder);
            // $sideImage         = addslashes($sideImage);
            //_________________________________________________________________________________________


            $updateProduct = $Products->updateProduct($productId, $productName, $manufacturer, $type = '', $productComp1, $productComp2, $medicinePower, $productDesc, $quantity, $qtyUnit, $itemUnit, $packagingType, $mrp, $gst, $employeeId, NOW);

            $updateImage = true;
            if ($updateProduct === true) {
                if ($updateImage === true) {
    ?>
                    <script>
                        swal("Success", "Product updated successfully!", "success").then((value) => {
                            parent.location.reload();
                        });
                    </script>
        <?php
                }
            }
        }


// ===================== Fetching Product Details =====================

        $product = json_decode($Products->showProductsById($productId));
        $product = $product->data;

        $productName    = $product->name;
        $manufacturer   = $product->manufacturer_id;
        $manufData = json_decode($Manufacturer->showManufacturerById($manufacturer));
        
        $qty            = $product->unit_quantity;
        $qtyUnit        = $product->unit_id;
        $itemUnit       = $product->unit;
        $packagingType  = $product->packaging_type;
        $type           = $product->type;
        $power          = $product->power;
        $dsc            = $product->dsc;
        $mrp            = $product->mrp;
        $gst            = $product->gst;
        $comp1          = $product->comp_1;
        $comp2          = $product->comp_2;
        $added_by       = $product->added_by;
        $added_on       = $product->added_on;
        $updated_by     = $product->updated_by;
        $updated_on     = $product->updated_on;
        $admin_id       = $product->admin_id;


        $image = json_decode($ProductImages->showImageById($productId));
        
        // print_r($image);

        if ($image->status != null && is_array($image->status)) {
            $image= $image->data;

            foreach ($image as $image) {
                $Images  = $image->image;
            }

            if ($Images == NULL) {
                $Images = "medicy-default-product-image.jpg";
            }
        } else {
            $Images = "medicy-default-product-image.jpg";
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
                                                    <input class="c-inp w-100 p-1" id="product-composition-1" name="product-composition-1" placeholder="Product Composition 1" required>
                                                </div>

                                                <div class="col-md-6">
                                                    <input class="c-inp w-100 p-1" id="product-composition-2" name="product-composition-2" placeholder="Product Composition 2" required>
                                                </div>

                                            </div>
                                        </div>

                                        <!-- manufacturer row -->
                                        <div class="row">
                                            <div class="col-md-12 mt-2">

                                                <input type="text" name="manufacturer" id="manufacturer" class="c-inp w-100 p-1" disable hidden>

                                                <input type="text" name="manufacturer-id" id="manufacturer-id" value="<?= $manufData->name ?>" class="c-inp w-100 p-1" >

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
                                                    <div class="image-area <? !empty($image) ? 'activeted' : ''; ?> rounded" id="imageArea">
                                                        <img class="browse" src="<?= PROD_IMG_PATH . $Images ?>" alt="">
                                                    </div>
                                                    <input id="product-image" name="product-image" type="file" hidden onchange="updateImage(this)">
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


                        <input type="" id="imgid" name="imgid" value="<?php echo isset($image[0]['product_id']) ? $image[0]['product_id'] : ''; ?>" class="d-none">



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

        <!-- Sweet Alert Js  -->
        <script src="<?= JS_PATH ?>sweetAlert.min.js"></script>

        <script src="<?= JS_PATH ?>ajax.custom-lib.js"></script>
        <script src="<?php echo JS_PATH ?>custom/add-products.js"></script>



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
            //image change 
            function updateImage(input) {
                var imageArea = document.getElementById('imageArea');
                var reader = new FileReader();

                reader.onload = function(e) {
                    imageArea.innerHTML = '<img class="browse" src="' + e.target.result + '" alt="">';
                };

                reader.readAsDataURL(input.files[0]);
            }
            // Click event to trigger file input when clicking on the image area
            $(document).ready(function() {
                $('.image-area').click(function() {
                    $('#product-image').click();
                });
            });
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