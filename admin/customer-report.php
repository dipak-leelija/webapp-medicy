<?php
require_once dirname(__DIR__) . '/config/constant.php';
require_once SUP_ADM_DIR      . '_config/sessionCheck.php';
require_once CLASS_DIR        . 'dbconnect.php';
require_once SUP_ADM_DIR      . '_config/healthcare.inc.php';
require_once CLASS_DIR        . 'employee.class.php';
require_once CLASS_DIR        . 'admin.class.php';
require_once CLASS_DIR        . "stockOut.class.php";
require_once CLASS_DIR        . "salesReturn.class.php";
require_once CLASS_DIR        . "stockin.class.php";
require_once CLASS_DIR        . "stockReturn.class.php";

$CustomerId = url_dec($_GET['report']);

$Employees   = new Employees;
$StockOut    = new StockOut();
$SalesReturn = new SalesReturn();
$StockIn     = new StockIn();
$StockReturn = new StockReturn();
$Admin       = new Admin();

///=================salse amount========================///
$CountSoldItems = count($StockOut->stockOutDisplay($CustomerId));
$soldItems      = $StockOut->stockOutDisplay($CustomerId);
$salesReturn    = count($SalesReturn->salesReturnDisplay($CustomerId));
// print_r($salesReturn);

$totalAmount = 0;
$creditCount = 0;
$paymentModeOccurrences = array();
foreach ($soldItems as $item) {
    $totalAmount += $item['amount'];
    $paymentMode = $item['payment_mode'];
    // print_r($paymentMode);
    if ($item['payment_mode'] === 'Credit') {
        $creditCount++;
    }

    if (array_key_exists($paymentMode, $paymentModeOccurrences)) {
        $paymentModeOccurrences[$paymentMode]++;
    } else {
        $paymentModeOccurrences[$paymentMode] = 1;
    }
}

///=========purches amount==================///
$CountPurchesItems = count($StockIn->showStockIn($CustomerId));
$PurchesItems      = $StockIn->showStockIn($CustomerId);
$PurchesRetun      = $StockReturn->showStockReturn($CustomerId);
$PurchesRetun      = json_decode($PurchesRetun, true);
// print_r($PurchesRetun);

$totalPurchesAmount = 0;
$creditPurchesCount = 0;
$totalPurchesRetun  = 0;
$purchesPaymentModeOccur = array();
foreach ($PurchesItems as $item) {
    $totalPurchesAmount += $item['amount'];
    $paymentMode = $item['payment_mode'];

    if ($item['payment_mode'] === 'Credit') {
        $creditPurchesCount++;
    }

    if (array_key_exists($paymentMode, $purchesPaymentModeOccur)) {
        $purchesPaymentModeOccur[$paymentMode]++;
    } else {
        $purchesPaymentModeOccur[$paymentMode] = 1;
    }
}
// count return purches amount //
if ($PurchesRetun['status'] == 1 && isset($PurchesRetun['data']) && is_array($PurchesRetun['data'])) {
    $data = $PurchesRetun['data'];
    $totalPurchesRetun = count($data);
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

                    <div class="card shadow mb-2">
                        <div class="card-body">
                            <h6 class="font-weight-bold text-secondary mb-0 pb-0">Report Parameters</h6>
                        </div>
                    </div>
                    <div class="card shadow mb-4">
                        <div class="card-header py-3 justify-content-between">
                            <div class="d-flex justify-content-around w-100">
                                <div class="d-flex justify-content-start">
                                    <h6 class="font-weight-bold text-secondary"><b>Total Sold Item :</b> <?= $CountSoldItems ?></h6>
                                </div>
                                <div class="d-flex justify-content-start">
                                    <h6 class="font-weight-bold text-secondary"><b>Total Purches item :</b><?= $CountPurchesItems ?> </h6>
                                </div>
                            </div>

                            <div class="card-body pt-1">
                                <div class="d-flex justify-content-between flex-wrap">
                                    <div class="bg-white border border-0 rounded shadow" style="width: 50%;">
                                        <div class="ml-5" style="width: 70%;">
                                            <div class="d-flex justify-content-between mb-0 pb-0" style="width: 127%;">
                                                <h5 class="pt-3 pb-n5 mb-0" style="color: #5a5c69;font-weight: 600;">Sales Item Based On Payment Mode</h5>
                                                <!-- <button class="btn btn-sm d-flex justify-content-end m-3 mb-0 pb-0 border ">...</button> -->
                                                <?php require_once SUP_ROOT_COMPONENT . "customerSale.php"; ?>
                                                <!-- <div class="btn-group">
                                                    <button type="button" class="btn btn-sm btn-outline-light text-dark card-btn dropdown font-weight-bold shadow mt-2 mr-2 pt-0 pb-0" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                                                    <i class="fas fa-ellipsis-v"></i>
                                                    </button>
                                                    <div class="dropdown-menu dropdown-menu-right">
                                                        <button class="dropdown-item" type="button" id="sodCurrentDt" onclick="chkSod(this.id)">Today</button>
                                                        <button class="dropdown-item" type="button" id="sodLst24hrs" onclick="chkSod(this.id)">Last 24 hrs</button>
                                                        <button class="dropdown-item" type="button" id="sodLst7" onclick="chkSod(this.id)">Last 7 Days</button>
                                                        <button class="dropdown-item" type="button" id="sodLst30" onclick="chkSod(this.id)">Last 30 Days</button>
                                                        <button class="dropdown-item dropdown" type="button" id="sodGvnDt" onclick="chkSod(this.id)">By Date</button>
                                                        <button class="dropdown-item dropdown" type="button" id="sodDtRng" onclick="chkSod(this.id)">By Date Range</button>
                                                    </div>
                                                </div> -->
                                            </div>
                                            <div class='bg-white pl-5 pr-5 pt-1 pb-2'>
                                                <canvas id="myChart" width="50" height="50"></canvas>
                                            </div>
                                            <div class="ml-n4 d-flex justify-content-between" style="width: 130%;">
                                                <h6 class="font-weight-bold text-secondary"><b>Total Amount : </b><?= $totalAmount ?></h6>
                                                <h6 class="font-weight-bold text-secondary  mb-0 pb-0"><b>Return Item : </b><?= $salesReturn ?></h6>
                                                <h6 class="font-weight-bold text-secondary  mb-0 pb-0"><b>Credit Amount : </b><?= $creditCount ?> </h6>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="bg-white border border-0 rounded shadow" style="width: 49%;">
                                        <div class="ml-5" style="width: 70%;">
                                            <div class="d-flex justify-content-between mb-0 pb-0" style="width: 127%;">
                                                <h5 class="pt-3" style="color: #5a5c69;font-weight: 600;">Purches Item Based On Payment Mode</h5>
                                                <!-- <button class="btn btn-sm d-flex justify-content-end mr-1 mt-3 ml-3 mb-0 pb-0 border ">...</button> -->
                                                <div class="btn-group">
                                                    <button type="button" class="btn btn-sm btn-outline-light text-dark card-btn dropdown font-weight-bold shadow mt-2 mr-2 pt-0 pb-0" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                                                        <!-- <i class="fas fa-bars"></i> -->
                                                        <i class="fas fa-ellipsis-v"></i>
                                                    </button>
                                                    <div class="dropdown-menu dropdown-menu-right">
                                                        <button class="dropdown-item" type="button" id="sodCurrentDt" onclick="chkSod(this.id)">Today</button>
                                                        <button class="dropdown-item" type="button" id="sodLst24hrs" onclick="chkSod(this.id)">Last 24 hrs</button>
                                                        <button class="dropdown-item" type="button" id="sodLst7" onclick="chkSod(this.id)">Last 7 Days</button>
                                                        <button class="dropdown-item" type="button" id="sodLst30" onclick="chkSod(this.id)">Last 30 Days</button>
                                                        <button class="dropdown-item dropdown" type="button" id="sodGvnDt" onclick="chkSod(this.id)">By Date</button>
                                                        <button class="dropdown-item dropdown" type="button" id="sodDtRng" onclick="chkSod(this.id)">By Date Range</button>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class='bg-white pl-5 pr-5 pt-1 pb-2'>
                                                <canvas id="myChart1" width="50" height="50"></canvas>
                                            </div>
                                            <div class=" d-flex justify-content-between" style="width: 140%; margin-left:-13%;">
                                                <h6 class="font-weight-bold text-secondary mb-0 pb-0"><b>Total Amount: </b><?= $totalPurchesAmount ?></h6>
                                                <h6 class="font-weight-bold text-secondary mb-0 pb-0"><b>Return Item: </b><?= $totalPurchesRetun ?></h6>
                                                <h6 class="font-weight-bold text-secondary mb-0 pb-0"><b>Credit Amount:</b><?= $creditPurchesCount ?> </h6>
                                            </div>
                                        </div>
                                    </div>
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
        const labels1 = Object.keys(<?php echo json_encode($purchesPaymentModeOccur); ?>);
        // console.log(labels1);
        const data = Object.values(<?php echo json_encode($paymentModeOccurrences); ?>);
        const data1 = Object.values(<?php echo json_encode($purchesPaymentModeOccur); ?>);
        // console.log(data1);
        const backgroundColors = generateRandomColors(data.length);
        const backgroundColors1 = generateRandomColors1(data1.length1);

        // .....Create the chart for sales...//
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

        //...Create the chart for purches...//
        const ctx1 = document.getElementById('myChart1').getContext('2d');
        new Chart(ctx1, {
            type: 'pie',
            data: {
                labels: labels1,
                datasets: [{
                    label: 'Most Payment',
                    data: data1,
                    backgroundColors1: backgroundColors1,
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

        function generateRandomColors1(numColors) {
            const colors = [];
            for (let i = 0; i < numColors; i++) {
                colors.push(getRandomColor1());
            }
            return colors;
        }

        function getRandomColor1() {
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