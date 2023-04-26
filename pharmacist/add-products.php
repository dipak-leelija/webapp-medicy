<?php

require_once '_config/sessionCheck.php';//check admin loggedin or not
require_once '../php_control/products.class.php';
require_once '../php_control/manufacturer.class.php';
require_once '../php_control/measureOfUnit.class.php';
require_once '../php_control/packagingUnit.class.php';


$page = "add-products";

//objects Initilization
$Products           = new Products();
$Manufacturer       = new Manufacturer();
$MeasureOfUnits     = new MeasureOfUnits();
$PackagingUnits     = new PackagingUnits();

$showManufacturer   = $Manufacturer->showManufacturer();
// print_r($showManufacturer);
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
    <link href="../assets/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link
        href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
        rel="stylesheet">

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

        <!-- sidebar -->
        <?php include 'partials/sidebar.php'; ?>
        <!-- end sidebar -->

        <!-- Content Wrapper -->
        <div id="content-wrapper" class="d-flex flex-column">

            <!-- Main Content -->
            <div id="content">

                <!-- Topbar -->
                <?php include 'partials/topbar.php'; ?>
                <!-- End of Topbar -->

                <!-- Begin Page Content -->
                <div class="container-fluid">

                    <!-- Page Heading -->
                    <h1 class="h3 mb-2 text-gray-800"> Add Product</h1>

                    <!-- Add Product -->
                    <div class="card shadow mb-4" style="min-height: 70vh;">
                        <div class="card-body">
                            <form action="_config\form-submission\add-new-product.php" enctype="multipart/form-data"
                                method="post" id="add-new-product-details">
                                <div class="row">
                                    <div class="col-12 col-sm-12 col-md-12 col-lg-6">
                                        <div class="col-md-12"> 
                                            <input class="c-inp w-100 p-1" id="product-name" name="product-name"
                                                placeholder="Product Name" required>
                                        </div><br>
                                        <div class="col-md-12"> 
                                            <input class="c-inp w-100 p-1" id="product-composition" name="product-composition"
                                                placeholder="Product Composition" required>
                                        </div>

                                        <div class="row p-3">
                                            <div class="col-md-6">
                                                <input class="c-inp w-100 p-1" type="text" name="medicine-power"
                                                    id="medicine-power" placeholder="Enter Medicine Power" required>
                                            </div>
                                            <div class="col-md-6 mt-3 mt-md-0">
                                                <select class="c-inp w-100 p-1" name="manufacturer" id="manufacturer" required>
                                                    <option value="" disabled selected>Select Manufacturer</option>
                                                    <?php
                                                    foreach ($showManufacturer as $rowManufacturer) {
                                                        $manufId   = $rowManufacturer['id'];
                                                        $manufName = $rowManufacturer['name'];
                                                        echo '<option value="'.$manufId.'">'.$manufName.'</option>';
                                                    }
                                                ?>
                                                </select>
                                            </div>
                                        </div>

                                        <div class="col-md-12 mt-3">
                                            <label for="product-descreption">Product Description</label>
                                            <textarea class="form-control" name="product-descreption"
                                                id="product-descreption" cols="30" rows="3" required></textarea>
                                        </div>
                                    </div>

                                    <div class="col-12 col-sm-12 col-md-12 col-lg-6 mt-2 mt-md-0 px-4 px-md-2">

                                        <!-- Product Image Row  -->
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="border p-1 rounded">
                                                    <div class="image-area rounded">
                                                        <h6 class="d-flex justify-content-center">Upload Product Image
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
                                                        </span>
                                                    </div>
                                                    <input id="product-image" name="product-image" type="file" hidden>
                                                </div>
                                            </div>

                                            <div class="col-md-6 mt-2  mt-md-0">
                                                <div>
                                                    <input type="file" name="back-image" class="back-file" accept="image/*"
                                                        hidden>
                                                    <div class="input-group back-img-field">
                                                        <input type="text" class="form-control" disabled
                                                            placeholder="Upload Back Image" id="back-file">
                                                        <div class="input-group-append">
                                                            <button type="button"
                                                                class="back btn btn-primary">Browse</button>
                                                        </div>
                                                    </div>

                                                    <img src="" id="back-preview" class="img-thumbnail">
                                                </div>


                                                <div class="mt-4">
                                                    <input type="file" name="side-image" class="side-file" accept="image/*"
                                                        hidden>
                                                    <div class="input-group side-img-field">
                                                        <input type="text" class="form-control" disabled
                                                            placeholder="Upload Side Image" id="side-file">
                                                        <div class="input-group-append">
                                                            <button type="button"
                                                                class="side btn btn-primary">Browse</button>
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
                                                <!-- <label class="mb-0 mt-1" for="unit-quantity">Unit Quantity</label> -->
                                                <input type="number" class="c-inp p-1 w-100" name="unit-quantity"
                                                    id="unit-quantity" placeholder="Enter Unit" required>
                                            </div>

                                            <div class="col-12 col-sm-6 col-md-4 mt-3">
                                                <!-- <label class="mb-0 mt-1" for="unit">Select Unit</label> -->
                                                <select class="c-inp p-1 w-100" name="unit" id="unit" required>
                                                    <option value="" disabled selected >Select Unit</option>
                                                    <?php
                                                    foreach ($showMeasureOfUnits as $rowUnit) {
                                                        
                                                        echo '<option value="'.$rowUnit['short_name'].'">'.$rowUnit['short_name'].'</option>';
                                                    }
                                                    ?>
                                                </select>
                                            </div>
                                            <div class="col-12 col-sm-6 col-md-4 mt-3">
                                                <!-- <label class="mb-0 mt-1" for="packaging-unit">Packaging Type</label> -->
                                                <select class="c-inp p-1 w-100" name="packaging-type"
                                                    id="packaging-type" required>
                                                    <option value="" disabled selected>Packaging Unit</option>
                                                    <?php
                                                    foreach ($showPackagingUnits as $rowPackagingUnits) {
                                                        echo '<option value="'.$rowPackagingUnits['id'].'">'.$rowPackagingUnits['unit_name'].'</option>';
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
                                                <input type="number" class="c-inp w-100 p-1" name="mrp" id="mrp"
                                                    placeholder="Enter MRP" 
                                                    step="0.25" required>
                                            </div>

                                            <div class="col-12 col-sm-6 col-md-6 mt-3">
                                                <label class="mb-0 mt-1" for="gst">GST %</label>
                                                <select class="c-inp w-100 p-1" name="gst" id="gst">
                                                    <option value="0">0</option>
                                                    <option value="5">5</option>
                                                    <option value="12">12</option>
                                                    <option value="18">18</option>
                                                    <option value="28">28</option>

                                                </select>

                                            </div>

                                        </div>
                                        <!--/End Price Row -->

                                    </div>
                                </div>

                                <div class="d-sm-flex justify-content-end mt-3">
                                    <button class="btn btn-danger mr-3" id="reset" type="reset">Reset</button>
                                    <button class="btn btn-primary" name="add-product" id="add-btn" type="submit">Add
                                        Product</button>

                                </div>
                            </form>
                        </div>
                    </div>
                    <!-- /end Add Product  -->
                    

                </div>
                <!-- /.container-fluid -->
                <!-- End of Main Content -->
                
            </div>
            <!-- End of Content Wrapper -->

            <!-- Footer -->
            <?php include_once 'partials/footer-text.php'; ?>
                <!-- End of Footer -->

        </div>
        <!-- End of Page Wrapper -->

        <!-- Product modal -->
        <!-- bd-example-modal-lg -->
        <div class="modal fade productModal" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel"
            aria-hidden="true">
            <div class="modal-dialog modal-xl">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalToggleLabel">Customize Product</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body productModalBody">

                    </div>
                </div>
            </div>
        </div>
        <!--/end Product modal -->


        <!-- Scroll to Top Button-->
        <a class="scroll-to-top rounded" href="#page-top">
            <i class="fas fa-angle-up"></i>
        </a>


        <!-- Logout Modal-->
        <?php require_once '_config/logoutModal.php'; ?>
        <!--End of Logout Modal-->

        <!-- Bootstrap core JavaScript-->
        <script src="../assets/jquery/jquery.min.js"></script>
        <script src="../js/bootstrap-js-4/bootstrap.bundle.min.js"></script>

        <!-- Core plugin JavaScript-->
        <!-- <script src="../assets/jquery-easing/jquery.easing.min.js"></script> -->

        <!-- Custom scripts for all pages-->
        <script src="js/sb-admin-2.min.js"></script>

        <!-- Sweet Alert Js  -->
        <script src="../js/sweetAlert.min.js"></script>

        <!-- Page level plugins -->
        <!-- <script src="../assets/datatables/jquery.dataTables.min.js"></script> -->
        <!-- <script src="../assets/datatables/dataTables.bootstrap4.min.js"></script> -->

        <!-- Page level custom scripts -->
        <!-- <script src="js/demo/datatables-demo.js"></script> -->

        <script src="js/custom/add-products.js"></script>


        <script>
        /*calculating profit only after entering MRP
        // function getMarginMrp(value) {
        //     this.value = parseFloat(this.value).toFixed(2);

        //     const mrp = parseFloat(value);
        //     const ptr = parseFloat(document.getElementById("ptr").value);
        //     const gst = parseFloat(document.getElementById("gst").value);

        //     var profit = (mrp - ptr);

        //     profit = parseFloat(profit - ((gst / 100) * ptr));

        //     document.getElementById("profit").value = profit.toFixed(2);
        // }


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
        }*/
        </script>
        <script>
        productViewAndEdit = (productId) => {
            // alert("productModalBody");
            let ViewAndEdit = productId;
            let url = "ajax/products.View.ajax.php?id=" + ViewAndEdit;
            $(".productModalBody").html(
                '<iframe width="99%" height="520px" frameborder="0" allowtransparency="true" src="' +
                url + '"></iframe>');
        }
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
