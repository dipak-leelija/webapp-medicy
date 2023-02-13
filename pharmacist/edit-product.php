<?php

require_once '_config/sessionCheck.php'; //check admin loggedin or not
require_once '../php_control/products.class.php';
require_once '../php_control/productsImages.class.php';
require_once '../php_control/manufacturer.class.php';
require_once '../php_control/measureOfUnit.class.php';
require_once '../php_control/packagingUnit.class.php';


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

// if (isset($_POST['update-product'])) {
//     echo 'Hi';
// }

//======================== PRODUCT UPDATE BLOCK ====================================

if (isset($_POST['update-product'])) {

    $productId = $_POST['imgid'];
    
// ?><br><br><?php

   //===== Main Image 
    $image         = $_FILES['product-image']['name'];
    $tempImgname   = $_FILES['product-image']['tmp_name'];
    if (file_exists("../images/product-image/".$image)) {
        $image = 'medicy-'.$image;
    }

    $imgFolder     = "../images/product-image/".$image;
    move_uploaded_file($tempImgname, $imgFolder);
    $image         = str_replace("<", "&lt", $image);
    $image         = str_replace(">", "&gt", $image);
    $image         = str_replace("'", "&#39", $image);

    //===== Back Image 
    $backImage         = $_FILES['back-image']['name'];
    $tempBackImg       = $_FILES['back-image']['tmp_name'];
    if (file_exists("../images/product-image/".$backImage)) {
        $backImage = 'medicy-'.$backImage;
    }

    $imgFolder     = "../images/product-image/".$backImage;
    move_uploaded_file($tempBackImg, $imgFolder);
    $backImage         = str_replace("<", "&lt", $backImage);
    $backImage         = str_replace(">", "&gt", $backImage);
    $backImage         = str_replace("'", "&#39", $backImage);


     //===== Side Image 
     $sideImage         = $_FILES['side-image']['name'];
     $tempSideImg       = $_FILES['side-image']['tmp_name'];
     if (file_exists("../images/product-image/".$sideImage)) {
         $sideImage = 'medicy-'.$sideImage;
     }
 
     $imgFolder         = "../images/product-image/".$sideImage;
     move_uploaded_file($tempSideImg, $imgFolder);
     $sideImage         = str_replace("<", "&lt", $sideImage);
     $sideImage         = str_replace(">", "&gt", $sideImage);
     $sideImage         = str_replace("'", "&#39", $sideImage);
 //_________________________________________________________________________________________


    $updateProduct = $Products-> updateProduct($_POST['id'], $_POST['product-name'], $_POST['medicine-power'], $_POST['manufacturer'], $_POST['product-descreption'], $_POST['packaging-type'], $_POST['unit-quantity'], $_POST['unit'], $_POST['mrp'], $_POST['gst'], $_POST['added-by'], $_POST['product-composition']);

    $updateImage = $ProductImages-> updateImage( $productId, $image, $backImage, $sideImage );

    //echo $image, $backImage, $sideImage;

    if($updateProduct == true){
        if($updateImage == true){
            ?>
            <script>
            //window.alert("Data is Updated")
            parent.location.reload();
            </script>
          <?php
        }
       
      
    }
    
}

//====================== END OF PRODUCT UPDATE ========================================
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
    <link href="../assets/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">

    <!-- Fontawsome Link -->
    <link rel="stylesheet" href="../css/font-awesome.css">

    <!-- Custom styles for this template -->
    <link href="css/sb-admin-2.min.css" rel="stylesheet">

    <!--Custom CSS -->
    <!-- <link href="css/add-products.css" rel="stylesheet"> -->
    <link href="css/custom/add-products.css" rel="stylesheet">



</head>

<body id="page-top">

    <!-- Page Wrapper -->
    <div id="wrapper">

        <!-- Content Wrapper -->
        <div id="content-wrapper" class="d-flex flex-column">

            <!-- Main Content -->
            <div id="content">

                <?php
                if (isset($_GET['id'])) {

                    $item = $Products->showProductsById($_GET['id']);
                    $image = $ProductImages->showImageById($_GET['id']);


                    //print_r($item);
                    //print_r($image);
                    // // value="<?php echo ?><br><br><?php
                    // // $id = $item[0]['id'];
                    // $imgId = $image[0]['id'];
                    // echo $imgId;

                ?>
                    <!-- Add Product -->
                    <div class="card shadow mb-4 h-100">
                        <div class="card-body">
                            <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="post" enctype="multipart/form-data">
                                <div class="row">
                                    <div class="col-12 col-sm-12 col-md-12 col-lg-6">
                                        <div class="col-md-12">
                                            <!-- <label class="mb-0 mt-1" for="product-name">Product Name</Address></label> -->
                                            <input class="c-inp w-100 p-1" id="product-name" name="product-name" placeholder="Product Name" value="<?php echo $item[0]['name'] ?>" required>
                                        </div><br>

                                        <div class="col-md-12">
                                            <input class="c-inp w-100 p-1" id="product-composition" name="product-composition" placeholder="Product Composition" value="<?php echo $item[0]['product_composition'] ?>" required>
                                        </div>

                                        <div class="row p-3">
                                            <div class="col-md-6">
                                                <input class="c-inp w-100 p-1" type="text" name="medicine-power" id="medicine-power" placeholder="Enter Medicine Power" value="<?php echo $item[0]['power'] ?>">
                                            </div>
                                            <div class="col-md-6 mt-3 mt-md-0">
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

                                        <div class="col-md-12 mt-3">
                                            <label for="product-descreption">Product Description</label>
                                            <textarea class="form-control" name="product-descreption" id="product-descreption" cols="30" rows="3"><?php echo $item[0]['dsc'] ?></textarea>
                                        </div>
                                    </div>

                                    <div class="col-12 col-sm-12 col-md-12 col-lg-6 mt-2 mt-md-0 px-4 px-md-2">

                                        <!-- Product Image Row  -->
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="border p-1 rounded">
                                                    <div class="image-area <?php if (count($image) != 0) {
                                                                                echo 'activeted';
                                                                            } ?> rounded">
                                                        <img class="browse" src="<?php echo '../images/product-image/' . $image[0]['image']; ?>" alt="">
                                                        <!-- <h6 class="d-flex justify-content-center">Upload Product Image
                                                        </h6>
                                                        <div class="icon ">
                                                            <i class="fa fa-file-image-o" aria-hidden="true"></i>
                                                        </div>

                                                        <span class="upload-img-span1 ">
                                                            <small>Drag & Drop</small>
                                                        </span>
                                                        <span class="upload-img-span ">
                                                            <small>Or <span class="browse">Browse</span></small>
                                                        </span>
                                                        <span class="upload-img-type ">
                                                            <small><i>Formats: JPG, JPEG & PNG</i></small>
                                                        </span> -->
                                                    </div>
                                                    <input id="product-image" name="product-image" type="file" accept="image/*" hidden>
                                                    
                                                </div>
                                            </div>

                                            <div class="col-md-6 mt-2  mt-md-0">
                                                <div>
                                                    <input type="file" name="back-image" class="back-file" accept="image/*" hidden>
                                                    <div class="input-group back-img-field">
                                                        <input type="text" class="form-control" disabled placeholder="Upload Back Image" id="back-file">
                                                        <div class="input-group-append">
                                                            <button type="button" class="back btn btn-primary">Browse</button>
                                                        </div>
                                                    </div>

                                                    <img src="" id="back-preview" class="img-thumbnail">
                                                </div>


                                                <div class="mt-4">
                                                    <input type="file" name="side-image" class="side-file" accept="image/*" hidden>
                                                    <div class="input-group side-img-field">
                                                        <input type="text" class="form-control" disabled placeholder="Upload Side Image" id="side-file">
                                                        <div class="input-group-append">
                                                            <button type="button" class="side btn btn-primary">Browse</button>
                                                        </div>
                                                    </div>

                                                    <img src="" id="side-preview" class="img-thumbnail">
                                                </div>




                                            </div>
                                        </div>
                                        <!--/End Product Image Row  -->

                                        <!-- Price Row -->
                                        <div class="row">

                                            <div class="col-12 col-sm-6 col-md-4 mt-3">
                                                <input type="number" class="c-inp p-1 w-100" name="unit-quantity" id="unit-quantity" placeholder="Enter Unit" value="<?php echo $item[0]['unit_quantity'] ?>">
                                            </div>

                                            <div class="col-12 col-sm-6 col-md-4 mt-3">
                                                <!-- <label class="mb-0 mt-1" for="unit">Select Unit</label> -->
                                                <select class="c-inp p-1 w-100" name="unit" id="unit">
                                                    <option value="" disabled selected>Select Unit</option>
                                                    <?php
                                                    foreach ($showMeasureOfUnits as $rowUnit) {
                                                    ?>
                                                        <option <?php if ($item[0]['unit'] == $rowUnit['short_name']) {
                                                                    echo 'selected';
                                                                } ?> value="<?php echo $rowUnit['short_name']; ?>"><?php echo $rowUnit['short_name']; ?></option>';
                                                    <?php
                                                    }
                                                    ?>
                                                </select>
                                            </div>
                                            <div class="col-12 col-sm-6 col-md-4 mt-3">
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

                                        <!-- Price Row -->
                                        <div class="row">
                                            <div class="col-12 col-sm-6 col-md-6 mt-3">
                                                <label class="mb-0 mt-1" for="mrp">MRP ₹</label>
                                                <input type="number" class="c-inp w-100 p-1" name="mrp" id="mrp" placeholder="Enter MRP" onkeyup="getMarginMrp(this.value)" step="0.25" value="<?php echo $item[0]['mrp']; ?>">
                                            </div>

                                            <div class="col-12 col-sm-6 col-md-6 mt-3">
                                                <label class="mb-0 mt-1" for="gst">GST %</label>
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
                                        <!--/End Price Row -->

                                    </div>
                                </div>

                                <input type="hidden" id="id" name="id" value="<?php echo $item[0]['id'] ?>">
                                <input type="hidden" id="added-by" name="added-by" value="<?php echo $item[0]['added_by'] ?>">
                                <input type="hidden" id="imgid" name="imgid" value="<?php echo $image[0]['product_id'] ?>">
                              

                                <div class="d-sm-flex justify-content-end mt-3">
                                    <!-- <button class="btn btn-danger mr-3" id="reset" type="button">Reset</button> -->
                                    <button class="btn btn-primary" name="update-product" id="update-btn" type="submit">Update</button>

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


        <!-- Logout Modal-->
        <?php require_once '_config/logoutModal.php'; ?>
        <!--End of Logout Modal-->

        <!-- Bootstrap core JavaScript-->
        <script src="../assets/jquery/jquery.min.js"></script>
        <script src="../js/bootstrap-js-4/bootstrap.bundle.min.js"></script>

        <!-- Custom scripts for all pages-->
        <script src="js/sb-admin-2.min.js"></script>

        <!-- Sweet Alert Js  -->
        <script src="../js/sweetAlert.min.js"></script>

        <script src="js/custom/add-products.js"></script>


        <script>
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