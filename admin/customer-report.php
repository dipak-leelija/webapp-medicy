<?php
require_once dirname(__DIR__) . '/config/constant.php';
require_once SUP_ADM_DIR      . '_config/sessionCheck.php';
require_once CLASS_DIR        . 'dbconnect.php';
require_once SUP_ADM_DIR      . '_config/healthcare.inc.php';
require_once CLASS_DIR        . 'employee.class.php';
require_once CLASS_DIR        . 'admin.class.php';
require_once CLASS_DIR        . "stockOut.class.php";

$CustomerId = url_dec($_GET['report']);
echo $CustomerId;
$Employees   = new Employees;
$StockOut    = new StockOut();
$Admin       = new Admin();

$CountSoldItems = count($StockOut->stockOutDisplay($CustomerId));
$soldItems      = $StockOut->stockOutDisplay($CustomerId);
// print_r($soldItems);

$totalAmount = 0;
$paymentModeOccurrences = array();
foreach ($soldItems as $item) {
    $totalAmount += $item['amount'];
    $paymentMode = $item['payment_mode'];
    print_r($paymentMode);

    if (array_key_exists($paymentMode, $paymentModeOccurrences)) {
        // If yes, increment the count
        $paymentModeOccurrences[$paymentMode]++;
    } else {
        // If no, initialize the count to 1
        $paymentModeOccurrences[$paymentMode] = 1;
    }
}

foreach ($paymentModeOccurrences as $mode => $count) {
    echo "Payment Mode: $mode, Occurrences: $count\n";
}



?>

<!DOCTYPE html>
<html lang="en">

<head>
    <!--git test -->
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>All Customer | <?= SITE_NAME ?></title>

    <!-- Custom fonts for this template -->
    <link href="<?php echo PLUGIN_PATH ?>fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="<?php echo CSS_PATH ?>sb-admin-2.min.css" rel="stylesheet">

    <!-- Custom styles for this page -->
    <link rel="stylesheet" href="<?php echo CSS_PATH ?>custom/appointment.css">
    <link rel="stylesheet" href="<?php echo CSS_PATH ?>custom/return-page.css">
    <script src="<?php echo PLUGIN_PATH; ?>chartjs-4.4.0/updatedChart.js"></script>

</head>

<body id="page-top">

    <!-- Page Wrapper -->
    <div id="wrapper">

        <!-- sidebar -->
        <?php include SUP_ROOT_COMPONENT . 'sidebar.php'; ?>
        <!-- end sidebar -->

        <!-- Content Wrapper -->
        <div id="content-wrapper" class="d-flex flex-column">

            <!-- Main Content -->
            <div id="content">

                <!-- Topbar -->
                <?php include SUP_ROOT_COMPONENT . 'topbar.php'; ?>
                <!-- End of Topbar -->

                <!-- Begin Page Content -->
                <div class="container-fluid">

                    <!-- DataTales Example -->
                    <div class="card shadow mb-4">

                        <div class="card-header py-3 justify-content-between">

                            <div class="col-12 d-flex justify-content-between">
                                <div class="">
                                    <h6 class="font-weight-bold text-primary">Total sold item: <?= $CountSoldItems ?> </h6>
                                </div>
                            </div>

                            <div class="card-body">
                                <div class="d-flex justify-content-between">
                                    <div class="bg-white border border-0 rounded" style="width: 50%;">
                                        <div class="ml-5" style="width: 70%;">
                                            <h5 class="pt-3" style="color: #5a5c69;font-weight: 600;">Sales Item Based On Payment Mode</h5>
                                            <div class='bg-white pl-5 pr-5 pt-3 pb-2'>
                                                <canvas id="myChart" width="50" height="50"></canvas>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="bg-white border border-0 rounded " style="width: 45%;"></div>
                                </div>
                            </div>

                        </div>
                    </div>

                </div>
                <!-- /.container-fluid -->

            </div>
            <!-- End of Main Content -->

            <!-- Footer -->
            <?php include  SUP_ROOT_COMPONENT . 'footer-text.php'; ?>
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
    <script src="<?php echo PLUGIN_PATH ?>jquery/jquery.min.js"></script>
    <script src="<?php echo JS_PATH ?>bootstrap-js-4/bootstrap.bundle.min.js"></script>

    <!-- Custom JS -->
    <script src="<?php echo JS_PATH ?>custom-js.js"></script>
    <script src="<?php echo JS_PATH ?>ajax.custom-lib.js"></script>

    <!-- Core plugin JavaScript-->
    <script src="<?php echo PLUGIN_PATH ?>jquery-easing/jquery.easing.min.js"></script>

    <!-- Custom scripts for all pages-->
    <script src="<?= JS_PATH ?>sb-admin-2.min.js"></script>
    <!-- <script src="<?= JS_PATH ?>filter.js"></script> -->

    <script>
        const labels = Object.keys(<?php echo json_encode($paymentModeOccurrences); ?>);
        // console.log(labels);
        const data = Object.values(<?php echo json_encode($paymentModeOccurrences); ?>);
        // console.log(data);
        const backgroundColors = generateRandomColors(data.length);

        // Create the chart
        const ctx = document.getElementById('myChart').getContext('2d');
        new Chart(ctx, {
            type: 'pie',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Most Payment',
                    data: data,
                    backgroundColor: backgroundColors,
                    hoverOffset: 4
                }]
            },
            options: {
                labels: {
                    display: true,
                    position: 'left',
                },
            },
        });

        //==== generate random colors===//
        function generateRandomColors(numColors) {
            const colors = [];
            for (let i = 0; i < numColors; i++) {
                colors.push(getRandomColor());
            }
            return colors;
        }

        function getRandomColor() {
            const letters = '0123456789ABCDEF';
            let color = '#';
            for (let i = 0; i < 6; i++) {
                color += letters[Math.floor(Math.random() * 16)];
            }
            return color;
        }
    </script>

</body>

</html>