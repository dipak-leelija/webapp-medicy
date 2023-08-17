<?php

require_once '_config/sessionCheck.php'; //check admin loggedin or not
require_once '../php_control/products.class.php';
// require_once '../php_control/manufacturer.class.php';
require_once '../php_control/distributor.class.php';
// require_once '../php_control/measureOfUnit.class.php';
require_once '../php_control/stockReturn.class.php';
// require_once '../php_control/currentStock.class.php';
require_once '../php_control/packagingUnit.class.php';




$page = "purchase-management";

//objects Initilization
$products           = new Products();
$Distributor        = new Distributor();
$StockReturn        = new StockReturn();
// $Manufacturer       = new Manufacturer();
// $MeasureOfUnits     = new MeasureOfUnits();
// $StockIn            = new StockIn();
// $CurrentStock       = new CurrentStock();
// $PackagingUnits     = new PackagingUnits();


//function's called
// $showStockIn           = $StockIn->showStockIn();
// $showProducts          = $Products->showProducts();
$showDistributor       = $Distributor->showDistributor();
// $showManufacturer      = $Manufacturer->showManufacturer();
// $showMeasureOfUnits    = $MeasureOfUnits->showMeasureOfUnits();
// $showPackagingUnits = $PackagingUnits->showPackagingUnits();


if (isset($_GET["returnId"])) {
    $returnId = $_GET["returnId"];
    // echo $returnId;
    $table = 'id';
    $value = $returnId;
    $stokReturnData = $StockReturn->stockReturnFilter($table, $value);
    // print_r($stokReturnData);
    foreach ($stokReturnData as $returnData) {
        $stockReturnId = $returnData['id'];
        $distId = $returnData["distributor_id"];
        $billNo = $returnData["bill_no"];
        $returnDate = $returnData["return_date"];
        $itemsCount = $returnData["items"];
        $totalReturnQty = $returnData["total_qty"];
        $returnGSTAmount = $returnData["gst_amount"];
        $refundMode = $returnData["refund_mode"];
        $NetRefundAmount = $returnData["refund_amount"];        
    }


    $distData = $Distributor->showDistributorById($distId);
    $distName = $distData[0]["name"];


    // print_r($distData);
}

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
    <!-- <link rel="stylesheet" href="../css/font-awesome-6.1.1-pro.css"> -->
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="css/sb-admin-2.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/custom/stock-in.css">


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
                    <!-- <h1 class="h3 mb-2 text-gray-800"> Purchase Return</h1> -->

                    <!-- Add Product -->
                    <div class="card shadow mb-3">
                        <div class="card-body">
                            <div class="row ">
                                <div class="col-md-3 col-12 ">
                                    <label class="mb-1 mt-3" for="distributor-id">Distributor :</label>
                                    <!--<select class="upr-inp mb-1" id="distributor-id" onchange="getItemList(this)" readonly>
                                        <option value="" disabled selected>Select Distributor</option>
                                        <?php
                                        foreach ($showDistributor as $rowDistributor) {
                                            $rowDistributor['id'];
                                            $rowDistributor['name'];
                                            echo '<option value="' . $rowDistributor['id'] . '">' . $rowDistributor['name'] . '</option>';
                                        }
                                        ?>
                                    </select> -->
                                    <input class="upr-inp mb-1" id="distributor_name" name="<?php echo $distId ?>" value="<?php echo $distName ?>" onload="getItemList(this)" readonly>
                                </div>

                                <div class="col-md-2 col-12 ">
                                    <label for="bill-no" class="mb-1 mt-3">Bill No</label>
                                    <input class="upr-inp mb-1" id="bill-no" value="<?php echo $billNo; ?>" readonly>
                                </div>

                                <div class="col-md-5 col-12 ">
                                    <label for="product-name" class="mb-1 mt-3">Product Name</label>
                                    <!-- <input class="upr-inp mb-1" id="product_name" name="product-name" placeholder="Search Product" onkeyup="searchItem(this.value)" autocomplete="off">
                                     <!-- onchange="getDtls(this);"  -->
                                    <!-- <div class="p-2 bg-light " id="product-select">
                                        <div class="m-0 text-danger text-center">
                                            <b> Select Distributor First </b>
                                        </div>
                                    </div>-->
                                    <input type="text" id="product-id" >
                                    <input class="upr-inp mb-1" id="product_name" value="" readonly>
                                </div>

                                <!-- ==================================== CHECKING ======================================= -->
                                <!-- <div class="col-md-7 col-12 ">
                                    <label for="product-name" class="mb-1 mt-3">Product Name</label>
                                    <input class="upr-inp mb-1" id="product-name" name="product-name"
                                        placeholder="Search Product" onkeyup="searchItem(this.value)"
                                        autocomplete="off">
                                    <!-- onchange="getDtls(this);" --
                                    <div class="p-2 bg-light " id="product-select">
                                        <div class="m-0 text-danger text-center">
                                            <b> Select Distributor First </b>
                                        </div>
                                    </div>
                                    <input type="text" id="product-id" hidden>
                                </div> -->
                                <!-- ===================================================================================== -->

                                <div class="col-md-2 col-12 mt-2 mt-md-0 mx-auto">
                                    <label class="mb-1 mt-3" for="return-mode">Return Mode :</label>
                                    <select class="upr-inp" name="return-mode" id="return-mode" onchange="setReturnMode(this.value)">
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
                                    <!-- <input class="upr-inp mb-1" id="refund-mode" value="" readonly> -->
                                </div>



                            </div>
                            <hr>

                            <div class="row">
                                <div class="col-md-6 col-12 ">
                                    <div class="row">
                                        <div class="col-md-4 col-12">
                                            <label class="mb-1 mt-3" for="batch-number">Batch Number :</label>
                                            <input class="upr-inp mb-1" id="batch-number" value="" readonly>
                                        </div>

                                        <div class="col-md-4 col-12">
                                            <label class="mb-1 mt-3" for="stock-in-item-id">Stock In Item Id :</label>
                                            <input class="upr-inp mb-1" id="stock-in-item-id" value="" readonly>
                                        </div>

                                        <div class="col-md-4 col-12">
                                            <label class="mb-1 mt-3" for="stock-return-id">Stock Return Id</label>
                                            <input class="upr-inp mb-1" id="stock-return-id" value="" readonly>
                                        </div>

                                        <div class="col-md-6 col-12">
                                            <label class="mb-1 mt-3" for="stock-returned-details-item-id">Stock Return Details Item Id</label>
                                            <input class="upr-inp mb-1" id="stock-returned-details-item-id" value="" readonly>
                                        </div>

                                        <div class="col-md-4 col-12">
                                            <label class="mb-1 mt-3" for="bill-date">Bill Date :</label>
                                            <input class="upr-inp mb-1" id="bill-date" value="" readonly>
                                        </div>

                                        <div class="col-md-4 col-12">
                                            <label class="mb-1 mt-3" for="returnDate">Return Date :</label>
                                            <input class="upr-inp mb-1" id="returnDate" value="" readonly>
                                        </div>

                                    </div>

                                    <div class="col-12 mt-3">
                                        <label for="returnDescription">Description</label>
                                        <textarea class="form-control" id="retrunDescription" rows="3"></textarea>
                                    </div>

                                </div>

                                <div class="col-md-6 col-12 mt-3">
                                    <!-- first row  -->
                                    <div class="row">

                                        <div class="col-md-2 col-6">
                                            <label class="mb-0 mt-1" for="unit"> Unit</label>
                                            <input type="text" class="upr-inp" id="unit" value="" readonly>
                                        </div>

                                        <div class="col-md-2 col-6">
                                            <label class="mb-0 mt-1" for="mrp">MRP</label>
                                            <input type="text" class="upr-inp" name="mrp" id="mrp" readonly>
                                        </div>

                                        <div class="col-md-3 col-6">
                                            <label class="mb-0 mt-1" for="ptr">PTR</label>
                                            <input type="text" class="upr-inp" name="ptr" id="ptr" readonly>
                                        </div>

                                        <div class="col-md-3 col-6">
                                            <label class="mb-0 mt-1" for="discount">Disc% </label>
                                            <input type="text" class="upr-inp" name="discount" id="discount" value="" readonly>
                                        </div>

                                        <div class="col-md-2 col-6">
                                            <label class="mb-0 mt-1" for="gst">GST%</label>
                                            <input type="text" class="upr-inp" name="gst" id="gst" readonly>
                                        </div>

                                    </div>
                                    <!-- first row end  -->
                                    <!-- second row  -->
                                    <div class="row mt-md-2">

                                    </div>
                                    <!-- end second row  -->

                                    <!-- third row  -->
                                    <div class="row mt-md-2">
                                        <div class="col-md-3 col-6">
                                            <label class="mb-0 mt-1" for="exp-date">Expiry</label>
                                            <input class="upr-inp" type="text" id="exp-date" value="" readonly>
                                        </div>

                                        <div class="col-md-3 col-6">
                                            <label class="mb-0 mt-1" for="current-qty">Live Qty: </label>
                                            <input type="text" class="upr-inp" name="current-qty" id="current-qty" readonly>
                                        </div>

                                        <div class="col-md-3 col-6">
                                            <label class="mb-0 mt-1" for="current-free-qty">Live Free Qty:</label>
                                            <input type="text" class="upr-inp" name="current-free-qty" id="current-free-qty" readonly>
                                        </div>
                                        <div class="col-md-3 col-6">
                                            <label class="mb-0 mt-1" for="current-total-qty">Current Qty:</label>
                                            <input type="text" class="upr-inp" name="current-total-qty" id="current-total-qty" readonly>
                                        </div>

                                        <div class="col-md-3 col-6">
                                            <label class="mb-0 mt-1" for="purchse-qty">Purchase Qty:</label>
                                            <input type="text" class="upr-inp" name="purchse-qty" id="purchse-qty" readonly>
                                        </div>

                                        <div class="col-md-3 col-6">
                                            <label class="mb-0 mt-1" for="purchse-free-qty">Free Qty:</label>
                                            <input type="text" class="upr-inp" name="purchse-free-qty" id="purchse-free-qty" readonly>
                                        </div>

                                        <div class="col-md-3 col-6">
                                            <label class="mb-0 mt-1" for="prev-ret-qty">Prev return Qty:</label>
                                            <input type="text" class="upr-inp" name="prev-ret-qty" id="prev-ret-qty" readonly>
                                        </div>

                                        <div class="col-md-3 col-6">
                                            <label class="mb-0 mt-1" for="prev-ret-free-qty">Rrev Free Return Qty:</label>
                                            <input type="text" class="upr-inp" name="prev-ret-free-qty" id="prev-ret-free-qty" readonly>
                                        </div>


                                    </div>
                                    <!-- end third row  -->

                                    <!-- fourth row  -->
                                    <div class="row mt-md-2">
                                        <div class="col-md-3 col-6">
                                            <label class="mb-0 mt-1" for="return-qty">Returned Qty:</label>
                                            <input type="text" class="upr-inp focus-border" name="return-qty" id="return-qty" value="" onkeyup="getRefund(this.value);">
                                        </div>
                                        <div class="col-md-3 col-6">
                                            <label class="mb-0 mt-1" for="ret-free-qty">Return F.Qty:</label>
                                            <input type="text" class="upr-inp focus-border" name="ret-free-qty" id="ret-free-qty" value="" onkeypress="freeReturnCheck(this.value)">
                                        </div>

                                        <div class="col-md-3 col-6">
                                            <label class="mb-0 mt-1" for="refund-amount">Refund:</label>
                                            <input type="text" class="upr-inp focus-border" name="refund-amount" id="refund-amount" value="">
                                        </div>

                                        <div class="col-md-3 col-6">
                                            <label class="mb-0 mt-1" for="gst-amount">Gst Amount:</label>
                                            <input type="text" class="upr-inp focus-border" name="gst-amount" id="gst-amount" value="">
                                        </div>

                                        <div class="col-md-3 col-6 mt-auto text-right">
                                            <button class="btn btn-primary w-100 " onclick="addData()">Add
                                                <i class="fas fa-plus"></i></button>
                                        </div>
                                    </div>
                                    <!-- fourth row  -->

                                </div>
                            </div>


                        </div>
                        <div class="row">

                            <!-- </form> -->
                        </div>
                    </div>
                    <!-- /end Add Product  -->

                    <!--=========================== Show Bill Items ===========================-->
                    <div class="card shadow mb-4">
                        <form action="_config\form-submission\stock-return-edit.php" method="post">
                            <div class="card-body stock-in-summary">
                                <div class="table-responsive">


                                    <table class="table item-table">
                                        <thead class="thead-light">
                                            <tr>
                                                <th scope="col"><input type="number" value="0" id="dynamic-id" style="display:none"></th>
                                                <th scope="col" style="width: 0.5rem; padding:0%"></th>
                                                <th scope="col" >StockReturnId</th>
                                                <th scope="col" >StockReturnDetailsId</th>
                                                <th scope="col">StockInDetailsId</th>

                                                <th scope="col" style="font-size: 0.7rem;">Items</th>
                                                <th scope="col" style="font-size: 0.7rem; width: 4rem;">Batch No.</th>
                                                <th scope="col" style="font-size: 0.7rem;">Exp</th>
                                                <th scope="col" style="font-size: 0.7rem;">Unit</th>
                                                <th scope="col" style="font-size: 0.7rem;" hidden>P.Qty.</th>
                                                <th scope="col" style="font-size: 0.7rem;" hidden>Free</th>
                                                <th scope="col" style="font-size: 0.7rem;">MRP</th>
                                                <th scope="col" style="font-size: 0.7rem;">PTR</th>
                                                <th scope="col" style="font-size: 0.7rem;">Disc%</th>
                                                <th scope="col" style="font-size: 0.7rem;">GST%</th>
                                                <th scope="col" style="font-size: 0.7rem;">Rt.Qty</th>
                                                <th scope="col" style="font-size: 0.7rem;">Rt.F.Qty</th>
                                                <th scope="col" style="font-size: 0.7rem;">Refund</th>

                                            </tr>
                                        </thead>
                                        <tbody id="dataBody">
                                            <?php
                                            $slno = 0;
                                            // showStockReturnById();
                                            $returnBills = $StockReturn->showStockReturnDetails($_GET['returnId']);

                                            // print_r($returnBills);

                                            foreach ($returnBills as $bill) {

                                                // print_r($bill);

                                                // echo $gstPerItem;
                                                $returnQTYperItems = $bill['return_qty'];
                                                // echo $returnQTYperItems;
                                                $returnFreeQtyPerItems = $bill['return_free_qty'];
                                                $perItemsTotalReturnQty = intval($returnQTYperItems) + intval($returnFreeQtyPerItems);
                                                // echo "total",$perItemsTotalReturnQty;
                                                $refundAmountPerItem = $bill['refund_amount'];
                                                
                                                $productid = $bill['product_id'];
                                                $productDetails = $products->showProductsById($productid);
                                                //print_r($productDetails);
                                                //echo "<br><br><br>";
                                                $productName = $productDetails[0]['name'];
                                                $productUnit = $productDetails[0]['unit'];

                                                $slno += 1;
                                            ?>

                                                <tr id="<?php echo 'table-row-' . $slno; ?>" value="<?php echo  $bill['id'] ?>">

                                                    <td style="color: red;" style="padding-top: 0rem"> <i class="fas fa-trash p-0 pt-3 " onclick="delData(<?php echo $slno; ?>, <?php echo $gstPerItem; ?>, <?php echo $perItemsTotalReturnQty; ?>, <?php echo $refundAmountPerItem; ?>)"></i>
                                                    </td>

                                                    <td class="p-0 pt-3" id="<?php echo 'table-row-' . $slno; ?>" value="<?php echo  $bill['id'] ?>" onclick='customEdit("<?php echo "table-row-" . $slno  ?>","<?php echo $bill["id"]  ?>", this.id, this.value)'>
                                                        <input class="col table-data w-2r" type="text" name="slNo[]" value="<?php echo $slno; ?>" readonly style="text-align: start; font-size: 0.7rem; ">
                                                    </td>

                                                    <td class=" p-0 pt-3" id="<?php echo 'table-row-' . $slno; ?>" value="<?php echo  $bill['id'] ?>" onclick='customEdit("<?php echo "table-row-" . $slno  ?>","<?php echo $bill["id"]  ?>", this.id, this.value)'>
                                                        <input class="col table-data w-6r" type="text" name="stock-return-id[]" id="stock-return-id" value="<?php echo $bill['stock_return_id']; ?>" readonly>
                                                    </td>

                                                    <td class=" p-0 pt-3" id="<?php echo 'table-row-' . $slno; ?>" value="<?php echo  $bill['id'] ?>" onclick='customEdit("<?php echo "table-row-" . $slno  ?>","<?php echo $bill["id"]  ?>", this.id, this.value)'>
                                                        <input class="col table-data w-6r" type="text" name="stock-return-details-item-id[]" id="stock-return-details-item-id" value="<?php echo $bill['id']; ?>" readonly>
                                                    </td>

                                                    <td class=" p-0 pt-3" id="<?php echo 'table-row-' . $slno; ?>" value="<?php echo  $bill['id'] ?>" onclick='customEdit("<?php echo "table-row-" . $slno  ?>","<?php echo $bill["id"]  ?>", this.id, this.value)'>
                                                        <input class="col table-data w-6r" type="text" name="stockin-details-item-id[]" id="stockin-details-item-id" value="<?php echo $bill['stokIn_details_id']; ?>" readonly>
                                                    </td>

                                                    <td class="p-0 pt-3" id="<?php echo 'table-row-' . $slno; ?>" value="<?php echo  $bill['id'] ?>" onclick='customEdit("<?php echo "table-row-" . $slno  ?>","<?php echo $bill["id"]  ?>", this.id, this.value)'>
                                                        <input class="col table-data w-9r" type="text" name="productName[]" value="<?php echo $productName; ?>" readonly style="text-align: start; font-size: 0.7rem; padding-left: 2%; padding-right: 2%;">
                                                        <input class="col table-data w-10r" type="text" name="productId[]" value="<?php echo $bill['product_id']; ?>" readonly style="text-align: start;" >
                                                    </td>

                                                    <td class="p-0 pt-3" id="<?php echo 'table-row-' . $slno; ?>" value="<?php echo  $bill['id'] ?>" onclick='customEdit("<?php echo "table-row-" . $slno  ?>","<?php echo $bill["id"]  ?>", this.id, this.value)'>
                                                        <input class="col table-data w-6r" type="text" name="batchNo[]" value="<?php echo $bill['batch_no']; ?>" readonly style="text-align: start; font-size: 0.6rem">
                                                    </td>

                                                    <td class="p-0 pt-3" id="<?php echo 'table-row-' . $slno; ?>" value="<?php echo  $bill['id'] ?>" onclick='customEdit("<?php echo "table-row-" . $slno  ?>","<?php echo $bill["id"]  ?>", this.id, this.value)'>
                                                        <input class="col table-data w-4r" type="text" name="expDate[]" value="<?php echo $bill['exp_date']; ?>" readonly style="text-align: start; font-size: 0.6rem">
                                                    </td>

                                                    <td class="p-0 pt-3" id="<?php echo 'table-row-' . $slno; ?>" value="<?php echo  $bill['id'] ?>" onclick='customEdit("<?php echo "table-row-" . $slno  ?>","<?php echo $bill["id"]  ?>", this.id, this.value)'>
                                                        <input class="col table-data w-4r" type="text" name="setof[]" value="<?php echo $bill['unit']; ?>" readonly style="text-align: start; font-size: 0.6rem">
                                                    </td>

                                                    <td class="d-none p-0 pt-3" id="<?php echo 'table-row-' . $slno; ?>" value="<?php echo  $bill['id'] ?>" onclick='customEdit("<?php echo "table-row-" . $slno  ?>","<?php echo $bill["id"]  ?>", this.id, this.value)'>
                                                        <input class="col table-data w-3r" type="text" name="purchasedQty[]" value="<?php echo $bill['purchase_qty']; ?>" readonly style="text-align: end; font-size: 0.6rem">
                                                    </td>

                                                    <td class="d-none p-0 pt-3" id="<?php echo 'table-row-' . $slno; ?>" value="<?php echo  $bill['id'] ?>" onclick='customEdit("<?php echo "table-row-" . $slno  ?>","<?php echo $bill["id"]  ?>", this.id, this.value)'>
                                                        <input class="col table-data w-2r" type="text" name="freeQty[]" value="<?php echo $bill['free_qty']; ?>" readonly style="text-align: end; font-size: 0.6rem">
                                                    </td>

                                                    <td class="p-0 pt-3" id="<?php echo 'table-row-' . $slno; ?>" value="<?php echo  $bill['id'] ?>" onclick='customEdit("<?php echo "table-row-" . $slno  ?>","<?php echo $bill["id"]  ?>", this.id, this.value)'>
                                                        <input class="col table-data w-4r" type="text" name="mrp[]" value="<?php echo $bill['mrp']; ?>" readonly style="text-align: start; font-size: 0.6rem">
                                                    </td>

                                                    <td class="p-0 pt-3" id="<?php echo 'table-row-' . $slno; ?>" value="<?php echo  $bill['id'] ?>" onclick='customEdit("<?php echo "table-row-" . $slno  ?>","<?php echo $bill["id"]  ?>", this.id, this.value)'>
                                                        <input class="col table-data w-4r" type="text" name="ptr[]" value="<?php echo $bill['ptr']; ?>" readonly style="text-align: start; font-size: 0.6rem">
                                                    </td>

                                                    <td class="p-0 pt-3" id="<?php echo 'table-row-' . $slno; ?>" value="<?php echo  $bill['id'] ?>" onclick='customEdit("<?php echo "table-row-" . $slno  ?>","<?php echo $bill["id"]  ?>", this.id, this.value)'>
                                                        <input class="col table-data w-5r" type="text" name="disc-percent[]" value="<?php echo $bill['disc'].'%'; ?>" readonly style="text-align: start; font-size: 0.6rem">
                                                    </td>

                                                    <td class="p-0 ps-1 pt-3" id="<?php echo 'table-row-' . $slno; ?>" value="<?php echo  $bill['id'] ?>" onclick='customEdit("<?php echo "table-row-" . $slno  ?>","<?php echo $bill["id"]  ?>", this.id, this.value)'>
                                                        <input class="col table-data w-3r" type="text" name="gst[]" value="<?php echo $bill['gst']; ?>%" readonly>
                                                    </td>

                                                    <td class="p-0 pt-3" id="<?php echo 'table-row-' . $slno; ?>" value="<?php echo  $bill['id'] ?>" onclick='customEdit("<?php echo "table-row-" . $slno  ?>","<?php echo $bill["id"]  ?>", this.id, this.value)'>
                                                        <input class="col table-data w-3r" type="text" name="return-qty[]" value="<?php echo $bill['return_qty']; ?>" readonly style="text-align: start; font-size: 0.6rem">
                                                    </td>

                                                    <td class="p-0 pt-3" id="<?php echo 'table-row-' . $slno; ?>" value="<?php echo  $bill['id'] ?>" onclick='customEdit("<?php echo "table-row-" . $slno  ?>","<?php echo $bill["id"]  ?>", this.id, this.value)'>
                                                        <input class="col table-data w-3r" type="text" name="return-free-qty[]" value="<?php echo $bill['return_free_qty']; ?>" readonly style="text-align: start; font-size: 0.6rem">
                                                    </td>

                                                    <td class=" amnt-td p-0 pt-3" id="<?php echo 'table-row-' . $slno; ?>" value="<?php echo  $bill['id'] ?>" onclick='customEdit("<?php echo "table-row-" . $slno  ?>","<?php echo $bill["id"]  ?>", this.id, this.value)'>
                                                        <input class="col table-data W-4r" type="text" name="refund-amount[]" value="<?php echo $bill['refund_amount']; ?>" readonly style="text-align: end; font-size: 0.6rem">
                                                    </td>
                                                </tr>

                                            <?php
                                            }
                                            ?>


                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            <div class="  p-3 m-2  font-weight-bold text-light purchase-items-summary rounded">
                                <div class="row ">
                                    <div class="col-md-3 col-6 mb-3 d-flex justify-content-start">
                                        <p>Distributor :
                                            <input class="summary-inp w-60" type="text" id="dist-name" name="dist-name" value="<?php echo $distName ?>" readonly style="margin-left:0rem;">
                                            <input class="summary-inp w-60" name="dist-id" id="dist-id" type="text" value="<?php echo $distId ?>" readonly>
                                            <input class="summary-inp w-60" name="stock-returned-id" id="stock-returned-id" type="text" value="<?php echo $stockReturnId ?>" readonly>

                                        </p>
                                    </div>

                                    <div class="col-md-3 col-6 mb-3 d-flex justify-content-start">
                                        <p>Return Date : <input class="summary-inp w-6r" name="return-date" id="return-date" type="text" value="<?php $today = date("d-m-Y");
                                                                                                                                                echo $today; ?>" readonly>
                                        </p>
                                    </div>

                                    <div class="col-md-3 col-6 mb-3  d-flex justify-content-start">
                                        <p>Items : <input class="summary-inp w-6r" name="items-qty" id="items-qty" type="text" value="<?php echo $itemsCount ?>" readonly></p>

                                    </div>

                                    <div class="col-md-3 col-6 mb-2 d-flex justify-content-start">
                                        <p>Refund Mode : <input class="summary-inp w-6r" name="refund-mode" id="refund-mode" value="<?php echo $refundMode ?>" type="text" readonly> </p>
                                    </div>

                                    <div class="col-md-3 col-6 mb-2 d-flex justify-content-start">
                                        <p>Qty : <input class="summary-inp w-65" name="total-return-qty" id="total-return-qty" type="text" value="<?php echo $totalReturnQty ?>" readonly> </p>
                                    </div>
                                    <div class="col-md-3 col-6 mb-2 d-flex justify-content-start">
                                        <p>GST : <input class="summary-inp w-65" name="return-gst" id="return-gst" type="text" value="<?php echo $returnGSTAmount ?>" readonly> </p>
                                    </div>
                                    <div class="col-md-3 mb-2 col-6 mb-2 d-flex justify-content-start">
                                        <p>Net : <input class="summary-inp w-65" name="NetRefund" id="NetRefund" type="text" value="<?php echo $NetRefundAmount ?>" readonly> </p>
                                    </div>
                                    <div class="col-md-3 mb-2 col-6 text-right">
                                        <button class="btn btn-sm btn-primary" style="width: 50%;" type="submit" name="stock-return-edit">Save</button>
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
                <?php include_once 'partials/footer-text.php'; ?>
                <!-- End of Footer -->

            </div>
            <!-- End of Content Wrapper -->

        </div>
        <!-- End of Page Wrapper -->

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

        <!-- Custom scripts for all pages-->
        <script src="js/sb-admin-2.min.js"></script>
        <script src="../js/sweetAlert.min.js"></script>
        <script src="../js/ajax.custom-lib.js"></script>
        <script src="js/purchase-return-item-edit.js"></script>

        <!--====================================on clik custom select action =========================================

        go to js/purchase-return-item-edit.js for function customEdit() -->

</body>

</html>