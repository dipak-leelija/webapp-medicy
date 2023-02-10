<?php
require_once "../../php_control/products.class.php";
require_once "../../php_control/packagingUnit.class.php";
require_once "../../php_control/productsImages.class.php";
require_once "../../php_control/manufacturer.class.php";

$Products       = new Products();
$PackagingUnits = new PackagingUnits();
$ProductImages  = new ProductImages();
$Manufacturer   = new Manufacturer();

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="../../css/bootstrap 5/bootstrap.css">
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
        $product        = $Products->showProductsById($_GET['id']);
        $manuf          = $Manufacturer->showManufacturerById($product[0]['manufacturer_id']);

        $image = $ProductImages->showImageById($_GET['id']);

        if ($image != NULL) {
            $mainImage  = $image[0]['image'];
            $backImage  = $image[0]['back_image'];
            if ($backImage == NULL) {
                $backImage = "medicy-default-product-image.jpg";
            }
            $SideImage = $image[0]['side_image'];
            if ($SideImage == NULL) {
                $SideImage = "medicy-default-product-image.jpg";
            }
        } else {
            $mainImage = "medicy-default-product-image.jpg";
            $backImage = "medicy-default-product-image.jpg";
            $SideImage = "medicy-default-product-image.jpg";
        }

        $pack = $PackagingUnits->showPackagingUnitById($product[0]['packaging_type']);

    ?>
    <div class="container-fluid d-flex justify-content-center mt-2">
        <div class="row justify-content-center">
            <div class="col-12 col-sm-4">
                <div class="">
                    <div class="text-center border d-flex justify-content-center">
                        <img src="../../images/product-image/<?php echo $mainImage; ?>"
                            class="rounded ob-cover animated--grow-in" id="main-img" alt="...">
                    </div>
                    <div class="row height-3 mt-2 justify-content-center">
                        <div class="col-2 border p-0">
                            <img src="../../images/product-image/<?php echo $mainImage; ?>" id="front-img"
                                onclick="setImg(this.id)" class="rounded ob-cover h-100" alt="...">
                        </div>
                        <div class="col-2 border p-0" id="back-div">
                            <img src="../../images/product-image/<?php echo $backImage; ?>" id="back-img"
                                onclick="setImg(this.id)" class="rounded ob-cover h-100" alt="...">
                        </div>
                        <div class="col-2 border p-0" id="side-div">
                            <img src="../../images/product-image/<?php echo $SideImage; ?>" id="side-img"
                                onclick="setImg(this.id)" class="rounded ob-cover h-100" alt="...">
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-12 col-sm-6">
                <div class="">
                    <div class="text-start mb-0 pb-0">
                        <h4><?php echo $product[0]['name']; ?></h4>
                        <h7><?php echo $product[0]['product_composition']; ?></h7>
                        <p><small><?php echo $manuf[0]['name']; ?></small></p>
                        <h5 class="fs-5 fst-normal">₹ <?php echo $product[0]['mrp']; ?><span
                                class="fs-6 fw-light"><small> MRP</small></span></h5>
                        <p class="fst-normal"><?php echo $product[0]['unit_quantity']; ?>
                            <?php echo $product[0]['unit']; ?>/<?php echo $pack[0]['unit_name']; ?></p>
                    </div>
                    <div class="d-flex justify-content-center">
                        <hr class="text-center w-100" style="height: 2px;">
                        <!-- <hr class="divider d-md-block" style="height: 2px;> -->
                    </div>
                    <div class="text-start">
                        <p><?php echo $product[0]['dsc']; ?></p>
                    </div>
                    <div class="row justify-content-center mt-4">
                        <div class="col-2">
                            <a href="../edit-product.php?id=<?php echo $_GET['id']; ?>"
                                class="btn btn-sm btn-primary">Edit</a>
                        </div>
                        <div class="col-2">
                            <button class="btn btn-sm btn-danger" onclick="del(this)"
                                id=<?php echo $_GET['id']; ?>>Delete</button>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
    <?php
    }
    ?>
    <script src="../../js/bootstrap-js-5/bootstrap.js"></script>
    <script>
    const setImg = (id) => {
        img = document.getElementById(id).src;
        document.getElementById("main-img").src = img;
    }

    //========================= Delete Product =========================

    function del(e) {
        btnID = e.id;
        btn = this;
        swal({
                title: "Are you sure?",
                text: "Want to Delete This Manufacturer?",
                icon: "warning",
                buttons: true,
                dangerMode: true,
            })
            .then((willDelete) => {
                if (willDelete) {

                    $.ajax({
                        url: "product.Delete.ajax.php",
                        type: "POST",
                        data: {
                            id: btnID
                        },
                        success: function(data) {
                            if (data == 1) {

                                swal(
                                    "Deleted",
                                    "Manufacturer Has Been Deleted",
                                    "success"
                                ).then(function() {
                                    parent.location.reload();
                                });

                            } else {
                                swal("Failed", "Product Deletion Failed!",
                                    "error");
                                $("#error-message").html("Deletion Field !!!")
                                    .slideDown();
                                $("success-message").slideUp();
                            }
                        }
                    });
                }
                return false;
            });
    }
    </script>

    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"
        integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous">
    </script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.12.9/dist/umd/popper.min.js"
        integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous">
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.min.js"
        integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous">
    </script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.3/jquery.min.js"
        integrity="sha512-STof4xm1wgkfm7heWqFJVn58Hm3EtS31XFaagaa8VMReCXAkQnJZ+jEy8PCC/iT18dFy95WcExNHFTqLyp72eQ=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
</body>

</html>