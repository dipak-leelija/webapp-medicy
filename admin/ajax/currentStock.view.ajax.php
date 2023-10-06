<?php

// echo $_GET['currentStockId'];

ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);

require_once '../../php_control/patients.class.php';
require_once '../../php_control/idsgeneration.class.php';
require_once '../../php_control/currentStock.class.php';
require_once '../../php_control/stockIn.class.php';
require_once '../../php_control/stockInDetails.class.php';
require_once '../../php_control/productsImages.class.php';
require_once '../../php_control/distributor.class.php';
require_once '../../php_control/products.class.php';
require_once '../../php_control/manufacturer.class.php';
require_once '../../php_control/packagingUnit.class.php';

//Classes Initilizing
// $Patients       =   new Patients();
$IdGeneration   =   new IdGeneration();
$CurrentStock   =   new CurrentStock();
$StockIn        =   new StockIn();
$StockInDetail  =   new StockInDetails();
$Product        =   new Products();
$ProductImages  =   new ProductImages();
$distributor    =   new Distributor();
$manufacturer   =   new Manufacturer();
$packagUnit     =   new PackagingUnits();


if (isset($_GET['currentStockId'])) {
    $productId =  $_GET['currentStockId'];
    $showStock = $CurrentStock->showCurrentStocByPId($productId);
    // print_r($showStock);
    // echo count($showStock);

    foreach ($showStock as $curntStk) {
        $productId      =  $curntStk['product_id'];
    }

    $prodcutDetails = $Product->showProductsById($productId);
    // echo "<br><br>"; print_r($prodcutDetails);

    $manufDetails = $manufacturer->showManufacturerById($prodcutDetails[0]['manufacturer_id']);
    // $manufDetails = $manufacturer->showManufacturer();
    // echo "<br><br>"; print_r($manufDetails);

    // $distributorDetails = $distributor->showDistributorById($showStock[0]['distributor_id']);
    // echo "<br><br>"; print_r($distributorDetails);


    $image = $ProductImages->showImageById($productId);
    // print_r($image);
    if ($image[0][2] != NULL) {
        $productImage = $image[0][2];
    } else {
        $productImage = 'medicy-default-product-image.jpg';
    }

    // ================= PRODUCT TOTAL STOCK IN QTY ==============
    $StockinQty = $StockInDetail->showStockInDetailsByPId($productId);
    // print_r($StockinQty); echo "<br><br>";
    if ($StockinQty != null) {
        $overallStockInQTY = 0;
        foreach ($StockinQty as $stockinQ) {
            $purchaseQty = $stockinQ['qty'];
            $freeQty = $stockinQ['free_qty'];
            $totalQ = intval($purchaseQty) + intval($freeQty);
            $overallStockInQTY += $totalQ;
        }
    }
    if ($StockinQty == null) {
        $overallStockInQTY = 0;
    }

    // ================= PRODUCT CURRENT STOCK IN QTY ============
    $currentStockQty = $CurrentStock->showCurrentStocByPId($productId);
    // print_r($currentStockQty);
    if ($currentStockQty != null) {
        $overallCurrentStock = 0;
        foreach ($currentStockQty as $currentQty) {
            $currentQty = $currentQty['qty'];
            $overallCurrentStock += $currentQty;
        }
    }
    if ($overallCurrentStock == null) {
        $overallCurrentStock = 0;
    }
}

?>

<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="../../css/bootstrap 5/bootstrap.css">
    <title>Product Details</title>

    <link href="../../assets/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">

    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">

    <!-- Sweet Alert Js  -->
    <script src="../../js/sweetAlert.min.js"></script>

    <!-- Core plugin JavaScript-->
    <!-- <script src="../../assets/jquery-easing/jquery.easing.min.js"></script> -->
    <!-- <script src="../../js/ajax.custom-lib.js"></script> -->
    <!-- <script src="../../js/jquery.prettyPhoto.js"></script>
    <script src="../../js/jquery.vide.js"></script> -->

    <script src="../../js/contact-us-js/jquery.min.js"></script>
    <script src="../../js/contact-us-js/jquery.validate.min.js"></script>

    <!-- Custom scripts for all pages-->
    <!-- <script src="../js/sb-admin-2.js"></script> -->

    <!-- <script src="../vendor/product-table/jquery.dataTables.js"></script>
    <script src="../vendor/product-table/dataTables.bootstrap4.js"></script> -->

    <!-- Bootstrap core JavaScript-->
    <script src="../../assets/jquery/jquery.min.js"></script>
    <!-- <script src="../../js/bootstrap-js-4/bootstrap.bundle.min.js"></script> -->

    <!-- Custom JS -->
    <script src="../js/custom-js.js"></script>
    <script src="../js/ajax.custom-lib.js"></script>
</head>

<body>
    <div class="container-fluid d-flex justify-content-center mt-2">
        <div class="container-fluid">
            <div class="row p-4 justify-content-left">
                <div class="col-sm-3 justify-content-center">
                    <div class="text-center border d-flex justify-content-center">
                        <img src="../../images/product-image/<?php echo $productImage ?>" class="img-fluid rounded" alt="...">
                        <!-- <hr class="hl justify-content-center" style="color: black;"> -->
                    </div>
                </div>
                <!-- <div class="col-sm-1 justify-content-center">
                 <div class="vl justify-content-center"></div> 
                </div> -->
                <div class="col-sm-6 justify-content-center" flex>
                    <h3><?php echo $prodcutDetails[0]['name']; ?></h3>
                    <h7>[<?php echo $prodcutDetails[0]['product_composition']; ?>]</h6>
                        <h5><?php echo $manufDetails[0]['name']; ?></h6>
                </div>
                <div class="col-sm-1 justify-content-center">

                </div>

                <div class="col-sm-2 justify-content-center">
                    <button class="button btn-danger" id="<?php echo $productId ?>" value1="<?php echo $overallStockInQTY ?>" value2="<?php echo $overallCurrentStock ?>" onclick="delAll('<?php echo $productId ?>', '<?php echo $overallStockInQTY ?>', '<?php echo $overallCurrentStock ?>', this.id, this.value1, this.value2)">Delete All</button>
                </div>

            </div>

            <!-- <div class="d-flex justify-content-top">
                <hr class="text-center w-100" style="height: 2px; color:black">
            </div> -->
            
            <?php
            $slNo = 1;
            foreach ($showStock as $stock) {
                // print_r($stock);
                $stokInID = $stock['stock_in_details_id'];
                $batchNo = $stock['batch_no'];
                $distId = $stock['distributor_id'];
                $currentStock = $stock['qty'];
                $looseStock = $stock['loosely_count'];

                //===============distributor details=============
                $distributorDetails = $distributor->showDistributorById($distId);
                // echo "<br><br>";
                // print_r($distributorDetails);
                $distName = $distributorDetails[0]['name'];

                $stokInDetailsCol1 = 'product_id';
                $stokInDetailsCol2 = 'batch_no';
                // ================ stok in detials ==================
                $stockInData = $StockInDetail->showStockInDetailsByTable($stokInDetailsCol1, $stokInDetailsCol2, $productId,  $batchNo);
                // echo "<br><br>";
                // print_r($stockInData);
                // $overallQTY = 0;
                foreach ($stockInData as $stockData) {
                    $purchaseDate = $stockData['added_on'];
                    $purchaseDate = date("d/m/Y", strtotime($purchaseDate));
                    $mfd = $stockData['mfd_date'];
                    $expDate = $stockData['exp_date'];
                    $purchaseQTY = $stockData['qty'];
                    $freeQTY = $stockData['free_qty'];
                    $MRP = $stockData['mrp'];
                    $PTR = $stockData['ptr'];
                    $gstParecent = $stockData['gst'];
                    $GST = $stockData['gst_amount'];
                    $customString1 = '(';
                    $customString2 = '%)';
                    $GST = $GST . $customString1 . $gstParecent . $customString2;
                    $discountParcent = $stockData['discount'];
                    $discountAmount = ($PTR * $discountParcent) / 100;
                    $discount = $discountAmount . $customString1 . $discountParcent . $customString2;
                    $basePrice = $stockData['base'];
                    $purchaseAmount = $basePrice * $stockData['qty'];

                    $ProductWeightage = $stockData['weightage'];
                    $productUnit = $stockData['unit'];

                    $packagingDetail = $ProductWeightage . " " . $productUnit . " / ";

                    $totalStockinQty = intval($purchaseQTY) + intval($freeQTY);

                    $perItemGst = floatval($GST)/intval($purchaseQTY);
                    $perItemGst = floatval($perItemGst);
                    $perItemGst = number_format($perItemGst, 2);
                    // $overallQTY = 0;
                }

                //=================== Packaging Detials ===================
                $packagingType = $prodcutDetails[0]['packaging_type'];
                $packagignData = $packagUnit->showPackagingUnitById($packagingType);
                $pacakagingUnitName = $packagignData[0]['unit_name'];

                // ================== product details ======================




            ?>
                <div class="d-flex justify-content-top">
                    <hr class="text-center w-100" style="height: 2px; color:black">
                </div>
                <div class="row mt-2 justify-content-center" flex id="<?php echo 'table-row-' . $slNo ?>">
                    <div class="col-12 ps-2">
                        <div class="row p-4">
                            <div class="col-6">

                                <strong>Distributor Name: </strong><span><?php echo $distName ?></span><br>
                                <strong>Batch No: </strong><span><?php echo $batchNo ?></span><br>
                                <strong>Purchase Date: </strong><span><?php echo $purchaseDate ?></span><br>
                                <strong>MFD: </strong><span><?php echo $mfd ?></span><br>
                                <strong>Exp Date: </strong><span><?php echo $expDate ?></span><br>
                                <strong>Packaging Details : </strong><span><?php echo $packagingDetail . $pacakagingUnitName ?></span><br>
                                <strong>Purchase Quantity: </strong><?php echo $purchaseQTY . " " . $pacakagingUnitName ?></span><br>
                                <strong>Free Quantity: </strong><span><?php echo $freeQTY ?></span><br>

                            </div>

                            <div class="col-5">

                                <strong>MRP: </strong><span><?php echo $MRP ?></span><br>
                                <strong>PTR: </strong><span><?php echo $PTR ?></span><br>
                                <strong>GST Amount/</strong><strong><?php echo $pacakagingUnitName ?> : </strong><span><?php echo $perItemGst ?></span><br>
                                <strong>Discount: </strong><span><?php echo $discount ?></span><br>
                                <strong>Base Price: </strong><span><?php echo $basePrice ?></span><br>
                                <strong>Purchase Amount: </strong><span><?php echo $purchaseAmount ?></span><br>
                                <strong>TOTAL GST: </strong><span><?php echo $GST ?></span><br>
                                <strong>Current Stock: </strong><span><?php echo $currentStock ?></span><br>
                                <strong>Loose Stock: </strong><span><?php echo $looseStock ?></span><br>
                                <!-- <strong>Row Serial No: </strong><span><?php echo $slNo ?></span><br> -->
                            </div>

                            <div class="col-1">
                                <button class="button btn-danger" onclick="customDelete('<?php echo $stokInID ?>','<?php echo $productId ?>','<?php echo $batchNo ?>','<?php echo $currentStock ?>','<?php echo 'table-row-' . $slNo ?>','<?php echo $totalStockinQty ?>')">Delete</button>
                            </div>

                        </div>
                    </div>
                </div>
            <?php
                $slNo++;
            }
            ?>
        </div>
    </div>
</body>

<script>
    // ============================ DELETE ALL STOCK DATA ================================
    const delAll = (id, value1, value2) => {
        // alert(id);
        // alert(value1);
        // alert(value2);
        let stokInQty = value1;
        let currentQty = value2;

        if (stokInQty != currentQty) {
            swal({
                icon: 'error',
                title: 'Oops...',
                text: 'Some customer have this product'
            })
        }

        if (stokInQty == currentQty) {
            swal({
                    title: "Are you sure?",
                    text: "Want to Delete This Data?",
                    icon: "warning",
                    buttons: true,
                    dangerMode: true,
                })
                .then((willDelete) => {
                    if (willDelete) {
                        // alert(id)
                        $.ajax({
                            url: "currentStock.delete.ajax.php",
                            type: "POST",
                            data: {
                                delID: id
                            },
                            success: function(response) {
                                alert(response);
                                if (response.includes('1')) {
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

    }

    // =================================== DELTE PERTICULER STOCK DATA =======================

    const customDelete = (id, value1, value2, value3, value4, value5) => {

        // alert(id);     // stok in detials id
        // alert(value1); // product Id
        // alert(value2); // batch not
        // alert(value3); // current stock count
        // alert(value4); // row number
        // alert(value5); // total stock in qty 

        let btnId = document.getElementById(id);
        let row = document.getElementById(value4);

        if (value3 != value5) {
            swal({
                icon: 'error',
                title: 'Oops...',
                text: 'Some customer have this product'
            })
        }

        if (value3 == value5) {
            swal({
                    title: "Are you sure?",
                    text: "Want to Delete This Data?",
                    icon: "warning",
                    buttons: true,
                    dangerMode: true,
                })
                .then((willDelete) => {
                    if (willDelete) {
                        // alert(value1);
                        // alert(value2);
                        $.ajax({
                            url: "currentStock.delete.ajax.php",
                            type: "POST",
                            data: {
                                pId: value1,
                                pBatchNO: value2
                            },
                            success: function(response) {
                                // alert(response);
                                if (response.includes('1')) {
                                    swal(
                                        "Deleted",
                                        "Manufacturer Has Been Deleted",
                                        "success"
                                    ).then(function() {
                                        row.parentNode.removeChild(row);
                                        // $(id).closest("tr").fadeOut()
                                    });

                                } else {
                                    swal("Failed", "Product Deletion Failed!",
                                        "error");
                                    $("#error-message").html("Deletion Field !!!")
                                        .slideDown();
                                    $("success-message").slideUp();
                                }

                                // row.parentNode.removeChild(row);

                            }
                        });
                    }
                    return false;
                });
        }

    }
</script>

</html>