<?php
$page = 'stock-expiring';

require_once __DIR__ . '/config/constant.php';
require_once ROOT_DIR . '_config/sessionCheck.php'; //check admin loggedin or not


require_once CLASS_DIR . 'dbconnect.php';
require_once ROOT_DIR . '_config/healthcare.inc.php';
require_once CLASS_DIR . 'currentStock.class.php';
require_once CLASS_DIR . 'products.class.php';


$CurrentStock = new CurrentStock();
$Products = new Products();

$thisMonth = date('m');
$thisYear = date('Y');

$modifiedMnth = intval($thisMonth) + intval(2);
if ($modifiedMnth > 12) {
    $expMnth = $modifiedMnth % 12;
    $expYr = intval($thisYear) + 1;
} else {
    $expMnth = $modifiedMnth;
    $expYr = $thisYear;
}

$expRange = $expMnth . '/' . $expYr;

$showExpiry = $CurrentStock->showStockExpiry(NOW, $adminId);
// print_r($showExpiry);

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <link rel="icon" type="image/x-icon" href="<?= FAVCON_PATH ?>">
    <title>Expiring Items - <?= $HEALTHCARENAME ?></title>

    <link rel="stylesheet" href="<?= CSS_PATH ?>sb-admin-2.css" type="text/css"/>
    <link rel="stylesheet" href="<?= PLUGIN_PATH ?>fontawesome-free/css/all.min.css" type="text/css"/>
    <link rel="stylesheet" href="<?= PLUGIN_PATH ?>product-table/dataTables.bootstrap4.css" type="text/css"/>

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
                    <!-- <h1 class="h3 mb-4 text-gray-800">Blank Page</h1> -->

                    <!-- Showing Sell Items  -->
                    <!-- DataTales Example -->
                    <div class="card shadow mb-4">
                        <div class="card-header pt-3 pb-1 d-flex d-flex justify-content-between">
                            <p class="m-0 font-weight-bold text-primary">Expiring in <?php echo $expRange; ?></p>
                            <div class="d-flex justify-content-end">
                                <!-- <div class="input-group h-75 w-75">
                                    <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" class="input-group h-100 w-100">
                                        <input type="text" class="form-control h-100" placeholder="Expiring In"
                                            aria-label="Expiring In" aria-describedby="exp-search" name="exp">
                                        <div class="input-group-append h-100">
                                            <button style="padding: 0.2rem 0.5rem;"
                                                class="btn btn-sm btn-outline-secondary" type="submit" name="exp-search"
                                                id="exp-search"><i class="fas fa-search"></i></button>
                                        </div>
                                    </form>
                                </div> -->
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-sm table-hover" id="dataTable" width="100%" cellspacing="0">
                                    <thead class="bg-primary text-light">
                                        <tr>
                                            <th>Product</th>
                                            <th>Batch</th>
                                            <th>Exp. Date</th>
                                            <th>Qty.</th>
                                            <th>Loose Qty.</th>
                                            <!-- <th>Action</th> -->
                                        </tr>
                                    </thead>
                                    <tbody>

                                        <?php
                                        foreach ($showExpiry as $item) {
                                            $productId    = $item['product_id'];

                                            $productDetails = json_decode($Products->showProductsById($productId));
                                            if (!$productDetails->status) {
                                                $tableName = 'product_request';
                                                $productDetails = json_decode($Products->showProductsByIdOnTableNameAdminId($productId, $adminId, $tableName));
                                            }

                                            // print_r($productDetails);

                                            $prodName = $productDetails->data->name;
                                            $batch        = $item['batch_no'];
                                            $expDate      = $item['exp_date'];
                                            $qty          = $item['qty'];
                                            $lCount       = $item['loosely_count'];

                                            echo "<tr>
                                                    <td>" . $prodName . "</td>
                                                    <td>" . $batch . "</td>
                                                    <td>" . $expDate . "</td>
                                                    <td>" . $qty . "</td>
                                                    <td>" . $lCount . "</td>
                                                </tr>";
                                        }
                                        ?>
                                        <!-- <td>
                                                        <a class='' data-toggle='modal' data-target='#manufacturerModal' onclick='viewSoldList(".$productId.")'><i class='fas fa-edit'></i></a>

                                                        <a class='ms-2' id='delete-btn' data-id=".$productId."><i class='far fa-trash-alt'></i></a>
                                                    </td> -->
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <!-- End of Showing Sell Items  -->

                </div>
                <!-- /.container-fluid -->

            </div>
            <!-- End of Main Content -->

        </div>
        <!-- End of Content Wrapper -->

    </div>
    <!-- End of Page Wrapper -->

    <!-- Scroll to Top Button-->
    <!-- <a class="scroll-to-top rounded" href="#page-top">
        <i class="fas fa-angle-up"></i>
    </a> -->
    <?php include ROOT_COMPONENT . 'generateTicket.php'; ?>

    <!-- Bootstrap core JavaScript-->
    <script src="<?= PLUGIN_PATH ?>jquery/jquery.min.js"></script>
    <script src="<?= JS_PATH ?>bootstrap-js-4/bootstrap.bundle.min.js"></script>

    <!-- Core plugin JavaScript-->
    <script src="<?= PLUGIN_PATH ?>jquery-easing/jquery.easing.min.js"></script>

    <!-- Custom scripts for all pages-->
    <script src="<?= JS_PATH ?>sb-admin-2.min.js"></script>

    <script src="<?= PLUGIN_PATH ?>product-table/jquery.dataTables.js"></script>
    <script src="<?= PLUGIN_PATH ?>product-table/dataTables.bootstrap4.js"></script>

    <!-- Page level custom scripts -->
    <script src="<?= JS_PATH ?>demo/datatables-demo.js"></script>

</body>

</html>