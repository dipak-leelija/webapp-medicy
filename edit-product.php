<?php
require_once 'config/constant.php';
require_once ROOT_DIR . '_config/sessionCheck.php'; //check admin loggedin or not

require_once CLASS_DIR . 'dbconnect.php';
require_once CLASS_DIR . 'products.class.php';
require_once CLASS_DIR . 'productsImages.class.php';
require_once CLASS_DIR . 'manufacturer.class.php';
require_once CLASS_DIR . 'measureOfUnit.class.php';
require_once CLASS_DIR . 'packagingUnit.class.php';



$page = "products";

//objects Initilization
$Products           = new Products();
$Manufacturer       = new Manufacturer();
$MeasureOfUnits     = new MeasureOfUnits();
$PackagingUnits     = new PackagingUnits();
$ProductImages      = new ProductImages();

$showManufacturer   = $Manufacturer->showManufacturer();
$showMeasureOfUnits = $MeasureOfUnits->showMeasureOfUnits();
$showPackagingUnits = $PackagingUnits->showPackagingUnits();

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

    <!-- sweetAlert link -->
    <script src="<?= JS_PATH ?>sweetAlert.min.js"></script>

</head>

<body id="page-top">
    <?php

    if (isset($_POST['update-product'])) {

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

        $updatedBy        = $employeeId;
        $updatedOn         = NOW;


        $unit = $_POST['unit'];
        $unitType = $MeasureOfUnits->showMeasureOfUnitsById($unit);
        $unitName = $unitType[0]['short_name'];


        // echo "<br>product id : ",$_POST['product-id']; echo "<br>",gettype($_POST['product-id']);
        // echo "<br>manuf id : ",$_POST['manufacturer']; echo "<br>",gettype($_POST['manufacturer']);
        // echo "<br>prod name : ",$_POST['product-name']; echo "<br>",gettype($_POST['product-name']);
        // echo "<br>product composition : ",$_POST['product-composition']; echo "<br>",gettype($_POST['product-composition']);
        // echo "<br>med power : ",$_POST['medicine-power']; echo "<br>",gettype($_POST['medicine-power']);
        // echo "<br>product description : ",$_POST['product-descreption']; echo "<br>",gettype($_POST['product-descreption']);
        // echo "<br>packaging type : ",$_POST['packaging-type']; echo "<br>",gettype($_POST['packaging-type']);
        // echo "<br>unit qty : ",$_POST['unit-quantity']; echo "<br>",gettype($_POST['unit-quantity']);
        // echo "<br>unit : ",$_POST['unit']; echo "<br>",gettype($_POST['unit']);
        // echo "<br>unit name : ",$unitName; echo "<br>",gettype($unitName);
        // echo "<br>mrp : ",$_POST['mrp']; echo "<br>",gettype($_POST['mrp']);
        // echo "<br>gst : ",$_POST['gst']; echo "<br>",gettype($_POST['gst']);
        // echo "<br>updated by: ",$updatedBy; echo "<br>",gettype($updatedBy);
        // echo "<br>updated on : ",$updatedOn; echo "<br>",gettype($updatedOn);


        $updateProduct = $Products->updateProduct($_POST['product-id'], $_POST['manufacturer'], $_POST['product-name'], $_POST['product-composition'], $_POST['medicine-power'], $_POST['product-descreption'], $_POST['packaging-type'], $_POST['unit-quantity'], $_POST['unit'], $unitName, $_POST['mrp'], $_POST['gst'], $updatedBy, $updatedOn);

        // print_r($updateProduct);
        // exit;

        $updateImage = true;
        if ($updateProduct === true) {
            if ($updateImage === true) {
    ?>
                <script>
                    swal("Success", "Product updated successfully!", "success")
                        .then((value) => {
                            parent.location.reload();
                        });
                </script>
    <?php
            }
        }
    }
    ?>

    <!-- Page Wrapper -->
    <div id="wrapper">

        <!-- Content Wrapper -->
        <div id="content-wrapper" class="d-flex flex-column">

            <!-- Main Content -->
            <div id="content">

                <?php
                if (isset($_GET['id'])) {
                    $item = $Products->showProductsById($_GET['id']);
                    // print_r($item);
                    $image = $ProductImages->showImageById($_GET['id']);
                    // print_r($image);

                    if ($image != NULL) {
                        foreach ($image as $image) {
                            $Images  = $image['image'];
                        }


                        if ($Images == NULL) {
                            $Images = "medicy-default-product-image.jpg";
                        }
                    } else {
                        $Images = "medicy-default-product-image.jpg";
                    }
                    //print_r($item);
                    //print_r($image);

                    // $id = $item[0]['id'];
                    // $imgId = $image[0]['id'];
                    // echo $imgId;

                ?>
                    <!-- Add Product -->
                    <div class="card shadow mb-4 h-100">
                        <div class="card-body">
                            <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="post" enctype="multipart/form-data">
                                <div class="d-flex">
                                    <div class="col-md-6">
                                        <div class="row">
                                            <div class="col-md-12">
                                                <!-- <label class="mb-0 mt-1" for="product-name">Product Name</Address></label> -->
                                                <input class="c-inp w-100 p-1" id="product-name" name="product-name" placeholder="Product Name" value="<?php echo $item[0]['name'] ?>" required>
                                                <input class="d-none c-inp w-100 p-1" id="product-id" name="product-id" value="<?php echo $_GET['id'] ?>" required>
                                            </div>
                                        </div>
                                        <br>
                                        <div class="row">
                                            <div class="col-md-12">
                                                <input class="c-inp w-100 p-1" id="product-composition" name="product-composition" placeholder="Product Composition" value="<?php echo $item[0]['product_composition'] ?>" required>
                                            </div>
                                        </div>
                                        <br>
                                        <!-- Price Row -->
                                        <div class="row">
                                            <div class="col-md-4">
                                                <input type="number" class="c-inp p-1 w-100" name="unit-quantity" id="unit-quantity" placeholder="Enter Unit" value="<?php echo $item[0]['unit_quantity'] ?>">
                                            </div>

                                            <div class="col-md-4">
                                                <!-- <label class="mb-0 mt-1" for="unit">Select Unit</label> -->
                                                <select class="c-inp p-1 w-100" name="unit" id="unit">
                                                    <option value="" disabled selected>Select Unit</option>
                                                    <?php
                                                    foreach ($showMeasureOfUnits as $rowUnit) {
                                                    ?>
                                                        <option <?php if ($item[0]['unit'] == $rowUnit['short_name']) {
                                                                    echo 'selected';
                                                                } ?> value="<?php echo $rowUnit['id']; ?>"><?php echo $rowUnit['short_name']; ?></option>';
                                                    <?php
                                                    }
                                                    ?>
                                                </select>
                                            </div>
                                            <div class="col-md-4">
                                                <!-- <label class="mb-0 mt-1" for="packaging-unit">Packaging Type</label> -->
                                                <select class="c-inp p-1 w-100" name="packaging-type" id="packaging-type">
                                                    <option value="" disabled selected>Packaging Unit</option>
                                                    <?php
                                                    foreach ($showPackagingUnits as $rowPackagingUnits) {
                                                    ?>
                                                        <option <?php if ($item[0]['packaging_type'] == $rowPackagingUnits['id']) {
                                                                    echo 'selected';
                                                                } ?> value="<?php echo $rowPackagingUnits['id'] ?>"><?php echo $rowPackagingUnits['unit_name'] ?></option>';
                                                    <?php
                                                    }
                                                    ?>
                                                </select>
                                            </div>
                                        </div>
                                        <!--/End Price Row -->
                                        <br>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <!-- <label class="mb-0 mt-1" for="mrp">MRP ₹</label> -->
                                                <input type="number" class="c-inp w-100 p-1" name="mrp" id="mrp" placeholder="Enter MRP" onkeyup="getMarginMrp(this.value)" step="0.01" value="<?php echo $item[0]['mrp']; ?>">
                                            </div>

                                            <div class="col-md-6">
                                                <!-- <label class="mb-0 mt-1" for="gst">GST %</label> -->
                                                <select class="c-inp w-100 p-1" name="gst" id="gst" onchange="getMarginGst(this.value)">
                                                    <option <?php if ($item[0]['gst'] == "0") {
                                                                echo 'selected';
                                                            } ?> value="0">0</option>
                                                    <option <?php if ($item[0]['gst'] == "5") {
                                                                echo 'selected';
                                                            } ?> value="5">5</option>
                                                    <option <?php if ($item[0]['gst'] == "12") {
                                                                echo 'selected';
                                                            } ?> value="12">12</option>
                                                    <option <?php if ($item[0]['gst'] == "18") {
                                                                echo 'selected';
                                                            } ?> value="18">18</option>
                                                    <option <?php if ($item[0]['gst'] == "28") {
                                                                echo 'selected';
                                                            } ?> value="28">28</option>
                                                </select>
                                            </div>
                                        </div>
                                        <br>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <input class="c-inp w-100 p-1" type="text" name="medicine-power" id="medicine-power" placeholder="Enter Medicine Power" value="<?php echo $item[0]['power'] ?>">
                                            </div>
                                            <div class="col-md-6">
                                                <select class="c-inp w-100 p-1" name="manufacturer" id="manufacturer">
                                                    <option value="" disabled selected>Select Manufacturer</option>
                                                    <?php
                                                    foreach ($showManufacturer as $rowManufacturer) {
                                                        $manufId   = $rowManufacturer['id'];
                                                        $manufName = $rowManufacturer['name'];
                                                    ?>
                                                        <option <?php if ($manufId == $item[0]['manufacturer_id']) {
                                                                    echo 'selected';
                                                                } ?> value="<?php echo $manufId; ?>"><?php echo $manufName; ?></option>
                                                    <?php
                                                    }
                                                    ?>
                                                </select>
                                            </div>
                                        </div>
                                        <br>
                                        <div class="col-md-12">
                                            <!-- <label for="product-descreption"></label> -->
                                            <textarea class="form-control" name="product-descreption" id="product-descreption" placeholder="Product Description" cols="30" rows="3"><?php echo $item[0]['dsc'] ?></textarea>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="border p-1 rounded">
                                                    <div class="image-area <?php if (count($image) != 0) {
                                                                                echo 'activeted';
                                                                            } ?> rounded" id="imageArea">
                                                        <img class="browse" src="<?php echo PROD_IMG_PATH . $Images ?>" alt="">
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

                        <input type="" id="id" name="id" value="<?php echo $item[0]['product_id'] ?>">
                        <input type="" id="added-by" name="added-by" value="<?php echo $item[0]['added_by'] ?>">
                        <input type="" id="imgid" name="imgid" value="<?php echo $image[0]['product_id'] ?>">



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

    <!-- Custom scripts for all pages-->
    <script src="<?= JS_PATH ?>sb-admin-2.min.js"></script>

    <!-- Sweet Alert Js  -->
    <script src="<?= JS_PATH ?>sweetAlert.min.js"></script>

    <script src="<?= JS_PATH ?>custom/add-products.js"></script>


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

</body>

</html>