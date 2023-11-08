<?php

require_once dirname(__DIR__).'/config/constant.php';
require_once ADM_DIR.'_config/sessionCheck.php'; //check admin loggedin or not

require_once CLASS_DIR.'dbconnect.php';
require_once CLASS_DIR.'products.class.php';
require_once CLASS_DIR.'productsImages.class.php';


$page = "products";

//Intitilizing Doctor class for fetching doctors
$Products      = new Products();
$ProductImages = new ProductImages();

// Function INitilized 
$col = 'admin_id';
$allProducts = $Products->showProducts();
// print_r($allProducts);

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Medicy Items</title>

    <!-- Custom fonts for this template -->
    <link href="../assets/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="css/sb-admin-2.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/custom/products.css">
    <!-- Custom styles for this page -->
    <link href="../assets/datatables/dataTables.bootstrap4.min.css" rel="stylesheet">

</head>

<body id="page-top">

    <!-- Page Wrapper -->
    <div id="wrapper">

        <!-- sidebar -->
        <?php include PORTAL_COMPONENT.'sidebar.php'; ?>
        <!-- end sidebar -->

        <!-- Content Wrapper -->
        <div id="content-wrapper" class="d-flex flex-column">

            <!-- Main Content -->
            <div id="content">

                <!-- Topbar -->
                <?php include PORTAL_COMPONENT.'topbar.php'; ?>
                <!-- End of Topbar -->

                <!-- Begin container-fluid -->
                <div class="container-fluid">

                    <!-- New Section -->
                    <div class="col">
                        <div class="card shadow mb-4">
                            <div class="card-header py-3">
                                <div class="d-flex justify-content-between">
                                    <h6 class="m-0 font-weight-bold text-primary">Total Items:
                                        <?php
                                        if ($allProducts != NULL) {
                                            echo $allProducts->num_rows;
                                        } else {
                                            echo 0;
                                        }
                                        ?>
                                    </h6>
                                    <a class="btn btn-sm btn-primary" href="add-products.php"><i class="fas fa-plus"></i> Add</a>
                                </div>
                            </div>
                            <div class="card-body">
                                        
                                <div class="d-flex justify-content-center">
                                    <div class="row card-div">

                                        <section class="gallery">
                                            <div class="row gallery-items">

                                                <?php
                                                if ($allProducts != NULL) {
                                                    foreach ($allProducts as $item) {
                                                        
                                                        $image = $ProductImages->showImageById($item['product_id']);
                                                        // print_r($image);
                                                        if ($image[0][2] != NULL) {
                                                            $productImage = $image[0][2];
                                                            // $productImage = 'medicy-default-product-image.jpg';
                                                        } else {
                                                            $productImage = 'medicy-default-product-image.jpg';
                                                        }
                                                        if ($item['dsc'] == NULL) {
                                                            $dsc = '';
                                                        } else {
                                                            $dsc = $item['dsc'].'...';
                                                        }
                                                        
                                                ?>

                                                        <div class="item col-12 col-sm-6 col-md-3 " style="width: 100%;">
                                                            <div class="card  m-2" id="allProducts">
                                                                <img src="../images/product-image/<?php echo $productImage ?>" class="card-img-top" alt="...">
                                                                <div class="card-body">
                                                                    <label><b><?php echo $item['name']; ?></b></label>
                                                                    <p class="mb-0"><b><?php $item['name'] ?></b></p>
                                                                    <small class="card-text mt-0" style="text-align: justify;"><?php echo substr($dsc, 0, 65) ?>...</small>

                                                                </div>


                                                                <div class="row px-3 pb-2">
                                                                    <div class="col-6">₹ <?php echo $item['mrp'] ?></div>
                                                                    <div class="col-6 d-flex justify-content-end">
                                                                        <button class="btn btn-sm border border-info" data-toggle="modal" data-target="#productModal" id="<?php echo $item['product_id'] ?>" onclick="viewItem(this.id)">View</button>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                <?php
                                                    }
                                                } else {
                                                    echo "No Item Avilable";
                                                }

                                                ?>

                                            </div>
                                            <div class="d-flex justify-content-end mt-3">
                                                <nav aria-label="Page navigation">
                                                    <ul class="pagination">
                                                        <li class="prev page-item"><a class="page-link" href="#">Previous</a>
                                                        </li>
                                                        <!-- <div id="pageNums"> -->
                                                        <li class="page page-item">
                                                            <a class="page-link">Page <span class="page-num"></a>
                                                        </li>
                                                        <!-- </div> -->
                                                        <li class="next page-item"><a class="page-link" href="#">Next</a></li>
                                                    </ul>
                                                </nav>
                                            </div>
                                        </section>

                                    </div>
                                </div>
                            </div>

                        </div>
                        <!-- End of Wrapper -->

                    </div>
                    <!-- End of Container -->

                </div>
                <!-- End of container-fluid -->

            </div>
            <!-- End of Main Content -->

            <!-- Footer -->
            <?php include_once PORTAL_COMPONENT.'footer-text.php'; ?>
            <!-- End of Footer -->

        </div>
        <!--End of Content Wrapper -->

    </div>
    <!-- End of Page Wrapper -->

    <!-- Product Modal -->
    <div class="modal fade" id="productModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title text-center" id="exampleModalLabel">View Product</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body productModal">
                    <!-- Product Details goes here by ajax  -->
                </div>
            </div>
        </div>
    </div>
    <!--End of Product Modal -->

    <!-- End of Page Wrapper -->

    <!-- Scroll to Top Button-->
    <a class="scroll-to-top rounded" href="#page-top">
        <i class="fas fa-angle-up"></i>
    </a>

    <!-- Bootstrap core JavaScript-->
    <script src="../assets/jquery/jquery.min.js"></script>
    <script src="../js/bootstrap-js-4/bootstrap.bundle.min.js"></script>

    <!-- Core plugin JavaScript-->
    <script src="../assets/jquery-easing/jquery.easing.min.js"></script>

    <!-- Custom scripts for all pages-->
    <script src="js/sb-admin-2.min.js"></script>


    <script>
        const galleryItems = document.querySelector(".gallery-items").children;
        const prev = document.querySelector(".prev");
        const next = document.querySelector(".next");
        const page = document.querySelector(".page-num");
        const maxItem = 8;
        let index = 1;

        const pagination = Math.ceil(galleryItems.length / maxItem);

        prev.addEventListener("click", function() {
            index--;
            check();
            showItems();
        })
        next.addEventListener("click", function() {
            index++;
            check();
            showItems();
        })

        function check() {
            if (index == pagination) {
                next.classList.add("disabled");
            } else {
                next.classList.remove("disabled");
            }

            if (index == 1) {
                prev.classList.add("disabled");
            } else {
                prev.classList.remove("disabled");
            }
        }

        function showItems() {
            for (let i = 0; i < galleryItems.length; i++) {
                galleryItems[i].classList.remove("show");
                galleryItems[i].classList.add("hide");


                if (i >= (index * maxItem) - maxItem && i < index * maxItem) {
                    // if i greater than and equal to (index*maxItem)-maxItem;
                    // means  (1*8)-8=0 if index=2 then (2*8)-8=8
                    galleryItems[i].classList.remove("hide");
                    galleryItems[i].classList.add("show");
                }
                page.innerHTML = index;
            }


        }

        window.onload = function() {
            showItems();
            check();
        }
    </script>
    <script>
        const viewItem = (value) => {
            // console.info(value);
            let url = 'ajax/product-view-modal.ajax.php?id=' + value;

            $(".productModal").html(
                '<iframe width="99%" height="500px" frameborder="0" allowtransparency="true" src="' +
                url + '"></iframe>');
        }
    </script>


</body>

</html>