<?php
$page = "stock-return";
require_once __DIR__ . '/config/constant.php';
require_once ROOT_DIR . '_config/sessionCheck.php';

require_once CLASS_DIR . 'dbconnect.php';
require_once ROOT_DIR . '_config/healthcare.inc.php';

require_once CLASS_DIR . 'distributor.class.php';
require_once CLASS_DIR . 'packagingUnit.class.php';


$Distributor        = new Distributor();
$showDistributor    = json_decode($Distributor->showDistributor());
$showDistributor = $showDistributor->data;

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    
    <link rel="icon" type="image/x-icon" href="<?= FAVCON_PATH ?>">
    <title>New Purchase Return - <?= $HEALTHCARENAME?></title>
    
    <link rel="stylesheet" href="<?= CSS_PATH ?>sb-admin-2.css" rel="stylesheet" type="text/css"/>
    <link rel="stylesheet" href="<?= CSS_PATH ?>custom/stock-return-item.css" type="text/css"/>
    <link rel="stylesheet" href="<?= CSS_PATH ?>custom-dropdown.css" type="text/css"/>
    <link rel="stylesheet" href="<?= PLUGIN_PATH ?>fontawesome-free/css/all.min.css" type="text/css"/>
    <script src="<?= CSS_PATH ?>sweetalert2/sweetalert2.min.css"></script>

</head>

<body id="page-top">

    <!-- Page Wrapper -->
    <div id="wrapper">

        <!-- sidebar -->
        <?php include ROOT_COMPONENT . 'sidebar.php'; ?>
        <!-- end sidebar -->

        <!-- Content Wrapper -->
        <div id="content-wrapper" class="d-flex flex-column">

            <!-- Main Content -->
            <div id="content">

                <!-- Topbar -->
                <?php include ROOT_COMPONENT . 'topbar.php'; ?>
                <!-- End of Topbar -->

                <!-- Begin Page Content -->
                <div class="container-fluid">
                    <div class="row" style="z-index: 999;">
                        <div class="col-12">
                            <?php include ROOT_COMPONENT . "drugPermitDataAlert.php"; ?>
                        </div>
                    </div>
                    <!-- Page Heading -->
                    <!-- <h1 class="h3 mb-2 text-gray-800"> Purchase Return</h1> -->

                    <!-- Add Product -->
                    <div class="card shadow mb-3">
                        <div class="card-body">
                            <div class="row ">
                                <div class="col-md-2 col-12 ">

                                    <label class="mb-1 mt-3" for="distributor-name">Distributor :</label>
                                    <span class="text-danger">*</span>
                                    <input type="text" name="" id="distributor-name" class="upr-inp" placeholder="Select Distributor" autocomplete="off">



                                    <div class="p-2 bg-light col-md-6 c-dropdown" id="distributor-list">
                                        <?php if (!empty($showDistributor)) : ?>
                                            <div class="lists" id="lists">
                                                <?php foreach ($showDistributor as $eachDistributor) { ?>
                                                    <div class="p-1 border-bottom list" id="<?= $eachDistributor->id ?>" onclick="setDistributor(this)">
                                                        <?= $eachDistributor->name ?>
                                                    </div>
                                                <?php } ?>
                                            </div>
                                        <?php endif; ?>
                                    </div>

                                </div>

                                <!-- <div class="col-md-2 col-12 ">
                                    <label for="product-name" class="mb-1 mt-3">Select Bill No.</label>
                                    <input class="upr-inp mb-1" id="select-bill-no" name="select-bill-no" placeholder="Search Bill" onkeyup="getItemList(this.value)" autocomplete="off" readonly>
                                    !-- onchange="getDtls(this);" --
                                    <div class="p-2 bg-light" id="select-bill" style="margin-left: 0rem;box-shadow: 0 5px 10px rgb(0 0 0 / 30%); transition: 3.3s ease; overflow: auto; display: none;  max-width: 100%; min-width: 100%; position: absolute; z-index: 100;">
                                    </div>
                                    <input type="text" id="bill-no" hidden>
                                </div> -->

                                <div class="col-md-8 col-12 ">
                                    <label for="product-name" class="mb-1 mt-3">Product Name</label>
                                    <span class="text-danger">*</span>
                                    <input class="upr-inp mb-1" id="product-name" name="product-name" placeholder="Search Product" onkeyup="searchItem(this.value)" autocomplete="off">
                                    <!-- onchange="getDtls(this);" -->
                                    <div class="p-2 bg-light " id="product-select">
                                        <div class="m-0 text-danger text-center">
                                            <b> Select Distributor First </b>
                                        </div>
                                    </div>
                                    <input class="d-none" type="text" id="product-id">
                                </div>

                                <div class="col-md-2 col-12 mt-2 mt-md-0 mx-auto">
                                    <label class="mb-1 mt-3" for="return-mode">Return Mode :</label>
                                    <span class="text-danger">*</span>
                                    <select class="upr-inp" name="return-mode" id="return-mode" onchange="setMode(this.value)">
                                        <option value="" selected disabled>Select</option>
                                        <option value="Credit">Credit</option>
                                        <option value="Cash">Cash</option>
                                        <option value="UPI">UPI</option>
                                        <option value="Paypal">Paypal</option>
                                        <option value="Bank Transfer">Bank Transfer</option>
                                        <option value="Credit Card">Credit Card</option>
                                        <option value="Debit Card">Debit Card</option>
                                        <option value="Net Banking">Net Banking</option>
                                    </select>
                                </div>
                            </div>


                            <form id='stock-return-item-data'>
                                <div class="row">
                                    <div class="col-md-5 col-12 ">
                                        <div class="row">
                                            <div class="d-none col-md-6 col-12">
                                                <label class="mb-1 mt-3" for="select-item-div">Selected Item Div</label>
                                                <input class="upr-inp" id="select-item-div" readonly>
                                            </div>

                                            <div class="d-none col-md-6 col-12">
                                                <label class="mb-1 mt-3" for="stokInDetailsId">Stock In Detaisl Id :</label>
                                                <input class="upr-inp" id="stokInDetailsId" readonly>
                                            </div>

                                            <div class="col-md-6 col-12">
                                                <label class="mb-1 mt-3" for="bill-number">Bill Number :</label>
                                                <input class="upr-inp" id="bill-number" readonly>
                                            </div>
                                            <div class="col-md-6 col-12">
                                                <label class="mb-1 mt-3" for="batch-number">Batch Number :</label>
                                                <input class="upr-inp" id="batch-number" readonly>
                                            </div>
                                            <div class="col-md-6 col-12">
                                                <label class="mb-1 mt-3" for="bill-date">Purchase Date :</label>
                                                <input class="upr-inp" id="bill-date" readonly>
                                            </div>

                                            <div class="col-md-6 col-12">
                                                <label class="mb-1 mt-3" for="exp-date">Expiry</label>
                                                <input class="upr-inp mb-1" type="text" id="exp-date" readonly>
                                            </div>

                                        </div>

                                        <div class="col-12 mt-3">
                                            <label for="exampleFormControlTextarea1">Description</label>
                                            <textarea class="form-control" id="exampleFormControlTextarea1" rows="3"></textarea>
                                        </div>

                                        <input class="d-none" type="any" name="weatage" id="weatage" readonly>
                                        <input class="d-none" type="text" name="gst" id="gst" readonly>
                                        <input class="d-none" type="text" name="mrp" id="mrp" readonly>
                                        <input class="d-none" type="text" name="net-buy-qty" id="net-buy-qty">
                                        <input class="d-none" type="text" name="gstAmountPerQty" id="gstAmountPerQty" readonly>
                                        <input class="d-none" type="any" name="taxable" id="taxable" readonly>

                                    </div>

                                    <div class="col-md-7 col-12 mt-3">
                                        <!-- first row  -->
                                        <div class="row">

                                            <div class="col-md-3 col-7">
                                                <label class="mb-0 mt-1" for="ptr">PTR</label>
                                                <input type="text" class="upr-inp" name="ptr" id="ptr" onkeyup="getBillAmount()" readonly>
                                            </div>

                                            <div class="col-md-3 col-7">
                                                <label class="mb-0 mt-1" for="discount">Disc% </label>
                                                <input type="text" class="upr-inp" name="discount" id="discount" value="" readonly>
                                            </div>

                                            <div class="col-md-3 col-7">
                                                <label class="mb-0 mt-1" for="taxable">D.Price</label>
                                                <input type="any" class="upr-inp" name="dprice" id="dprice" readonly>
                                            </div>

                                            <div class="col-md-3 col-7">
                                                <label class="mb-0 mt-1" for="amount">Amount</label>
                                                <input type="any" class="upr-inp" name="amount" id="amount" readonly>
                                            </div>


                                        </div>
                                        <!-- first row end  -->

                                        <!-- second row  -->
                                        <div class="row mt-md-2">

                                            <div class="col-md-3 col-7">
                                                <label class="mb-0 mt-1" for="purchased-qty">Purchased Qty</label>
                                                <input type="text" class="upr-inp" name="purchased-qty" id="purchased-qty">
                                            </div>

                                            <div class="col-md-2 col-7">
                                                <label class="mb-0 mt-1" for="free-qty">Free Qty</label>
                                                <input type="text" class="upr-inp" name="free-qty" id="free-qty">
                                            </div>

                                            <!-- <div class="col-md-2 col-7">
                                                <label class="mb-0 mt-1" for="current-purchase-qty">Current P.Qty</label>
                                                <input type="text" class="upr-inp" name="current-purchase-qty" id="current-purchase-qty">
                                            </div>

                                            <div class="  col-md-2 col-6">
                                                <label class="mb-0 mt-1" for="current-free-qty">Current F.Qty:</label>
                                                <input type="text" class="upr-inp" name="current-free-qty" id="current-free-qty">
                                            </div> -->

                                            <div class="col-md-2 col-7">
                                                <label class="mb-0 mt-1" for="current-qty">Current Qty:</label>
                                                <input type="text" class="upr-inp" name="current-qty" id="current-qty">
                                            </div>

                                        </div>
                                        <!-- end second row  -->

                                        <!-- fifth row  -->
                                        <div class="row mt-md-2">
                                            <div class="col-md-3 col-7">
                                                <label class="mb-0 mt-1" for="return-qty">Return Qty:</label>
                                                <span class="text-danger">*</span>
                                                <input type="number" class="upr-inp focus-border" id="return-qty" value="" onkeyup="getRefund(this.value);">
                                            </div>

                                            <!-- <div class="col-md-3 col-7">
                                                <label class="mb-0 mt-1" for="return-free-qty">Return F.Qty:</label>
                                                <span class="text-danger">*</span>
                                                <input type="text" class="upr-inp focus-border" name="return-free-qty" id="return-free-qty" value="" onkeyup="checkFQty(this.value);">
                                            </div> -->

                                            <div class="d-none col-md-3 col-7">
                                                <label class="mb-0 mt-1" for="return-gst-amount">Return GST Amount </label>
                                                <input type="text" class="upr-inp focus-border" name="return-gst-amount" id="return-gst-amount" value="" readonly>
                                            </div>

                                            <div class="col-md-3 col-6">
                                                <label class="mb-0 mt-1" for="refund-amount">Refund:</label>
                                                <input type="text" class="upr-inp focus-border" name="refund-amount" id="refund-amount" readonly>
                                            </div>

                                            <div class="col-md-3 col-6 mt-auto text-right">
                                                <button type="button" class="btn btn-primary w-100 " onclick="addData()">Add
                                                    <i class="fas fa-plus"></i></button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </form>

                        </div>

                    </div>
                    <!-- /end Add Product  -->

                    <!--=========================== Show Bill Items ===========================-->
                    <div class="card shadow mb-4">
                        <form action="_config\form-submission\stock-return-form.php" method="post">
                            <div class="card-body stock-in-summary">
                                <div class="table-responsive">

                                    <table class="table item-table">
                                        <thead class="thead-light">
                                            <tr>
                                                <th scope="col" class="">
                                                    <input type="number" value="0" id="dynamic-id" style="width: 1rem;" hidden>
                                                </th>
                                                <th scope="col" class="">
                                                    <input type="number" value="0" id="serial-control" style="width: 1rem;" hidden>
                                                </th>
                                                <th scope="col">Items</th>
                                                <th scope="col" class="text-right">Batch</th>
                                                <th scope="col" class="text-right">Exp</th>
                                                <th scope="col" class="text-right">MRP</th>
                                                <th scope="col" class="text-right">PTR</th>
                                                <th scope="col" class="text-right">Disc%</th>
                                                <th scope="col" class="text-right">GST%</th>
                                                <th scope="col" class="text-right">Return Qty</th>
                                                <th scope="col" class="text-right">Refund</th>

                                            </tr>
                                        </thead>
                                        <tbody id="dataBody">


                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            <div class="p-3 m-2  font-weight-bold text-light purchase-items-summary rounded">
                                <div class="row ">
                                    <div class="col-md-3 col-6 mb-3 d-flex justify-content-start">
                                        <p>Distributor :
                                            <input class="summary-inp w-60" type="text" id="dist-name" name="dist-name" readonly style="margin-left: 0rem;">
                                            <input class="summary-inp w-60" name="dist-id" id="dist-id" type="text" hidden readonly>
                                            <input class="summary-inp w-60" name="dist-bill-no" id="dist-bill-no" type="text" readonly>
                                        </p>
                                    </div>
                                    <div class="col-md-3 col-6 mb-3 d-flex justify-content-start">
                                        <p>Return Date : <input class="summary-inp w-6r" name="return-date" id="return-date" type="text" value="<?= date("d-m-Y") ?>" readonly>
                                        </p>
                                    </div>

                                    <div class="col-md-3 col-6 mb-3  d-flex justify-content-start">
                                        <p>Items : <input class="summary-inp w-6r" name="items-qty" id="items-qty" type="text" value="0" readonly></p>
                                    </div>

                                    <div class="col-md-3 col-6 mb-2 d-flex justify-content-start">
                                        <p>Refund Mode : <input class="summary-inp w-6r" name="refund-mode" id="refund-mode" type="text" readonly> </p>
                                    </div>

                                    <div class="col-md-3 col-6 mb-2 d-flex justify-content-start">
                                        <p>Qty : <input class="summary-inp w-65" name="total-return-qty" id="total-return-qty" type="text" value="0" readonly> </p>
                                    </div>
                                    <div class="col-md-3 col-6 mb-2 d-flex justify-content-start">
                                        <p>GST : <input class="summary-inp w-65" name="return-gst-val" id="return-gst-val" type="text" value="0" readonly> </p>
                                    </div>
                                    <div class="col-md-3 mb-2 col-6 mb-2 d-flex justify-content-start">
                                        <p>Net : <input class="summary-inp w-65" name="refund" id="refund" type="text" value="0" readonly> </p>
                                    </div>
                                    <div class="col-md-3 mb-2 col-6 text-right">
                                        <button class="btn btn-sm btn-primary" style="width: 50%;" type="submit" id="stock-return-save" name="stock-return">Save</button>
                                    </div>

                                    <div class="col-md-3 col-6 mb-2 d-flex justify-content-start" hidden>
                                        <p hidden>StockIn Id : <input class="summary-inp w-6r" name="stockInId" id="stockInId" type="text" readonly> </p>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                    <!--=========================== Show Bill Items ===========================-->


                </div>
                <!-- /.container-fluid -->
                <!-- End of Main Content -->

                <!-- Footer -->
                <?php include_once ROOT_COMPONENT . 'footer-text.php'; ?>
                <!-- End of Footer -->

            </div>
            <!-- End of Content Wrapper -->

        </div>
        <!-- End of Page Wrapper -->

        <!-- Scroll to Top Button-->
        <a class="scroll-to-top rounded" href="#page-top">
            <i class="fas fa-angle-up"></i>
        </a>

        <!-- Bootstrap core JavaScript-->
        <script src="<?= PLUGIN_PATH ?>jquery/jquery.min.js"></script>
        <script src="<?= JS_PATH ?>bootstrap-js-4/bootstrap.bundle.min.js"></script>

        <!-- Custom scripts for all pages-->
        <script src="<?= JS_PATH ?>sb-admin-2.min.js"></script>
        <script src="<?= JS_PATH ?>sweetAlert.min.js"></script>
        <script src="<?= JS_PATH ?>sweetalert2/sweetalert2.all.min.js"></script>
        <script src="<?= JS_PATH ?>ajax.custom-lib.js"></script>
        <script src="<?= JS_PATH ?>purchase-return-item.js"></script>

</body>

</html>