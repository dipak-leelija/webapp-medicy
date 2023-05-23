<?php
require_once '_config/sessionCheck.php'; //check admin loggedin or not
require_once '../php_control/currentStock.class.php';
require_once '../php_control/products.class.php';

$page = 'stock-expiring';

$CurrentStock = new CurrentStock();
$products = new Products();

$currentDate = date("m/y");
echo "check current month and year => $currentDate<br>";
$month       = date("m");
echo "check curretn month => $month<br>";
$year        = date("y");
echo "check current year => $year<br>";
// echo gettype($year);
if (($_SERVER['REQUEST_METHOD'] === 'POST')) {
    if (isset($_POST['exp-search'])) {

        $addMonth  = $_POST['exp'];

        $addMonth = $month + $addMonth;


        if ($addMonth > 12) {
            $totalYear = $addMonth / 12;
            $getYear = intval($totalYear);
            $year = $year + $getYear;
            $getMonth = $totalYear - $getYear;
            $getMonth = substr(round(round($getMonth, 2), 1), 2);

            if ($getMonth < 10) {
                $getMonth = "0" . $getMonth;
            }
            $newMnth = date($getMonth . "/" . $year);
        } else {
            $addMonth  = $_POST['exp'];
            $newMnth = date("m/y", strtotime("+" . $addMonth . " months"));
        }
    }
} else {
    $currentDate = date("m/y");
    // echo "<br>$currentDate<br><br>";
    $addMonth  = 2;
    $newMnth = date("m/y", strtotime("+" . $addMonth . " months"));
    echo "<br>New month -> $newMnth<br><br>";
}

// echo "<br>$newMnth<br><br>";
$showExpiry = $CurrentStock->showStockExpiry($newMnth);
echo "check months for november and december";
// print_r($showExpiry);


?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Expiring Items</title>

    <!-- Custom fonts for this template-->
    <link href="../assets/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">

    <!-- Custom styles for this template-->
    <link href="css/sb-admin-2.css" rel="stylesheet">

    <!-- Datatable Style CSS -->
    <link href="vendor/product-table/dataTables.bootstrap4.css" rel="stylesheet">

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
                    <!-- <h1 class="h3 mb-4 text-gray-800">Blank Page</h1> -->

                    <!-- Showing Sell Items  -->
                    <!-- DataTales Example -->
                    <div class="card shadow mb-4">
                        <div class="card-header pt-3 pb-1 d-flex d-flex justify-content-between">
                            <p class="m-0 font-weight-bold text-primary">Expiring in <?php echo $newMnth; ?></p>
                            <div class="d-flex justify-content-end">
                                <div class="input-group h-75 w-75">
                                    <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" class="input-group h-100 w-100">
                                        <input type="text" class="form-control h-100" placeholder="Expiring In" aria-label="Expiring In" aria-describedby="exp-search" name="exp" hidden>
                                        <div class="input-group-append h-100">
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-sm table-hover" id="dataTable" width="100%" cellspacing="0">
                                    <thead class="bg-primary text-light">
                                        <tr>
                                            <th hidden>StokIn Detials Id </th>
                                            <th>Product</th>
                                            <th>Batch</th>
                                            <th>Exp. Date</th>
                                            <th>Qty.</th>
                                            <th>L. Qty.</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>

                                        <?php
                                        foreach ($showExpiry as $item) {
                                            $expDate        = $item['exp_date'];

                                            $expiaryDt = str_replace("/", "", $expDate);
                                            $expiaryDtMnth = intval(substr($expiaryDt,0,2));
                                            $expiaryDtYr = substr($expiaryDt,2);
                                            
                                            $today = date("m/y");
                                            $chkMnth = substr($today,0,2);

                                            $chekExpMnth = substr($today,0,2) + 2;
                                            
                                            $chekExpMnth = $chekExpMnth % 12;
                                            
                                            if($chkMnth == 11 || $chkMnth == 12){
                                                $chkYr = date("y") + 1;
                                            }else{
                                                $chkYr = date("y");
                                            }
                                            
                                            // echo $chekExpMnth;
                                            if ($expiaryDtMnth <= $chekExpMnth && $expiaryDtYr == $chkYr) {
                                                $stokInDetialId = $item['stock_in_details_id'];
                                                $productId      = $item['product_id'];

                                                $productData = $products->showProductsById($productId);
                                                foreach ($productData as $data) {
                                                    $prodName = $data['name'];
                                                }

                                                $batch          = $item['batch_no'];
                                                $qty            = $item['qty'];
                                                $lCount         = $item['loosely_count'];

                                                echo "<tr>
                                                    <td hidden>" . $stokInDetialId . "</td>
                                                    <td>" . $prodName . "</td>
                                                    <td>" . $batch . "</td>
                                                    <td>" . $expDate . "</td>
                                                    <td>" . $qty . "</td>
                                                    <td>" . $lCount . "</td>
                                                    <td>
                                                    <a class='' data-toggle='modal' data-target='#productDetialsModal'
                                                    id=" . $stokInDetialId . " onclick='viewProductDetials(this.id)'><i class='fas fa-eye'></i></a>

                                                    <a class='ms-2' id='delete-btn' data-id=" . $productId . " hidden><i class='far fa-trash-alt'></i></a>
                                                </td>
                                                </tr>";
                                            }
                                        }
                                        ?>

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

            <!-- Footer -->
            <?php include_once 'partials/footer-text.php'; ?>
            <!-- End of Footer -->

        </div>
        <!-- End of Content Wrapper -->

    </div>
    <!-- End of Page Wrapper -->

    <!-- View currentStockModal Modal -->
    <div class="modal fade" id="productDetialsModal" tabindex="-1" role="dialog" aria-labelledby="currentStockModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="productDetialsModalTitle">Expiring Product Details</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body current-stock-view">
                </div>
            </div>
        </div>
    </div>
    <!-- End of View currentStockModal Modal -->

    <script>
        const viewProductDetials = (stokInDetialId) => {
            alert(stokInDetialId);
            let url = "ajax/stockExpiringDetailsView.ajax.php?stokInDetialId=" + stokInDetialId;
            $(".current-stock-view").html(
                '<iframe width="99%" height="520px" frameborder="0" allowtransparency="true" src="' +
                url + '"></iframe>');
        } // end of productDetialsModal function
    </script>


    <!-- Scroll to Top Button-->
    <a class="scroll-to-top rounded" href="#page-top">
        <i class="fas fa-angle-up"></i>
    </a>

    <!-- Logout Modal-->
    <?php require_once '_config\logoutModal.php'; ?>
    <!-- End Logout Modal-->

    <!-- Bootstrap core JavaScript-->
    <script src="../assets/jquery/jquery.min.js"></script>
    <script src="../js/bootstrap-js-4/bootstrap.bundle.min.js"></script>

    <!-- Core plugin JavaScript-->
    <script src="../assets/jquery-easing/jquery.easing.min.js"></script>

    <!-- Custom scripts for all pages-->
    <script src="js/sb-admin-2.min.js"></script>

    <script src="vendor/product-table/jquery.dataTables.js"></script>
    <script src="vendor/product-table/dataTables.bootstrap4.js"></script>

    <!-- Page level custom scripts -->
    <script src="js/demo/datatables-demo.js"></script>

</body>

</html>