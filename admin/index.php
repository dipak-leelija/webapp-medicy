<?php
require_once dirname(__DIR__) . '/config/constant.php';
require_once ADM_DIR . '_config/sessionCheck.php'; //check admin loggedin or not

require_once CLASS_DIR . 'dbconnect.php';
require_once ADM_DIR . '_config/user-details.inc.php';
require_once CLASS_DIR . 'appoinments.class.php';
require_once CLASS_DIR . 'currentStock.class.php';
require_once CLASS_DIR . 'stockOut.class.php';
require_once CLASS_DIR . 'stockIn.class.php';
require_once CLASS_DIR . 'stockInDetails.class.php';
require_once CLASS_DIR . 'distributor.class.php';
require_once CLASS_DIR . 'patients.class.php';

$page = "dashboard";

$appoinments = new Appointments();
$CurrentStock      = new CurrentStock();
$StockOut          = new StockOut();
$StockIn           = new StockIn();
$StockInDetails    = new StockInDetails();
$Distributor       = new Distributor;
$Patients          = new Patients;

$totalAppointments = $appoinments->appointmentsDisplay();

if ($_SESSION['ADMIN'] == false) {
    echo "<br>ADMIN ID : $adminId<br>";
} else {
    echo "<br>ADMIN LOGIN - ADMIN ID : $adminId<br>";
}

// $startDate = '2022-03-09';
// $endDate   = '2022-03-30';

$newPatients             = $Patients->newPatientCount($adminId);
$newPatientLast24Hours   = $Patients->newPatientCountLast24Hours($adminId);
$newPatientsByDay        = $Patients->newPatientByDay($adminId, $startDate);
$newPatientLast7Days     = $Patients->newPatientCountLast7Days($adminId);
$newPatientLast30Days    = $Patients->newPatientCountLast30Days($adminId);
$newPatientsInRangeDate = $Patients->findPatientsInRangeDate($adminId, $startDate, $endDate);








?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Medicy Health Care - Admin Portal</title>

    <!-- Custom fonts for this template-->
    <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">

    <!-- Custom styles for this template-->
    <link href="css/sb-admin-2.min.css" rel="stylesheet">
    <link href="css/custom/custom.css" rel="stylesheet">
    <link rel="stylesheet" href="css/custom-dashboard.css">

    <script src="js\ajax.custom-lib.js"></script>

</head>

<body id="page-top">

    <!-- Page Wrapper -->
    <div id="wrapper">

        <!-- sidebar -->
        <?php include PORTAL_COMPONENT . "sidebar.php"; ?>
        <!-- end sidebar -->

        <!-- Content Wrapper -->
        <div id="content-wrapper" class="d-flex flex-column">

            <!-- Main Content -->
            <div id="content">

                <!-- Topbar-->
                <?php include PORTAL_COMPONENT . "topbar.php"; ?>
                <!-- End of Tobbar-->

                <!-- Begin Page Content -->
                <div class="container-fluid">

                    <!-- Page Heading -->
                    <div class="d-sm-flex align-items-center justify-content-between mb-4">
                        <h1 class="h3 mb-0 text-gray-800">Dashboard</h1>
                        <a href="#" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm"><i class="fas fa-download fa-sm text-white-50"></i> Generate Report</a>
                    </div>


                    <!-- CONTENT USER DATA ROW -->
                    <div class="row">
                        <!-- Sold By Card  -->
                        <div class="col-xl-3 col-md-6 mb-4">
                            <?php require_once PORTAL_COMPONENT . "soldby.php"; ?>
                        </div>
                    </div>


                    <!-- Content Row -->
                    <div class="row">
                        <!-- Earnings (Monthly) Card Example -->
                        <div class="col-xl-3 col-md-6 mb-4">
                            <div class="card border-left-primary shadow h-100 py-2">
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                                Total Appointments</div>
                                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                                <?php echo count($totalAppointments); ?> </div>
                                        </div>
                                        <div class="col-auto">
                                            <i class="fas fa-calendar-check"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Earnings (Monthly) Card Example -->
                        <div class="col-xl-3 col-md-6 mb-4">
                            <div class="card border-left-success shadow h-100 py-2">
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                                <i class="fas fa-user-plus"></i> New Patients
                                            </div>
                                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                                <span id="newPatients"><?= $newPatients ?></span>
                                            </div>
                                        </div>
                                        <div class="d-flex justify-content-end px-2">
                                            <div id="mostVistedCustomerDtPkr" style="display: none;">
                                                <input type="date" id="newPatientDt">
                                                <button class="btn btn-sm btn-primary" onclick="newPatientByDt()" style="height: 2rem;">Find</button>
                                            </div>
                                            <div id="mostVistedCustomerDtPkrRng" style="display: none;">
                                                <label>Start Date</label>
                                                <input type="date" id="newPatientStartDate">
                                                <label>End Date</label>
                                                <input type="date" id="newPatientEndDate">
                                                <button class="btn btn-sm btn-primary" onclick="newPatientDateRange()" style="height: 2rem;">Find</button>
                                            </div>
                                            <div class="btn-group">
                                                <button type="button" class="btn btn-sm btn-outline-light text-dark card-btn dropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                                                    <b>...</b>
                                                </button>
                                                <div class="dropdown-menu dropdown-menu-right" style="background-color: rgba(255, 255, 255, 0.8);">
                                                    <button class="dropdown-item" type="button" id="newPatientLst24hrs" onclick="newPatientCount(this.id)">Last 24 hrs</button>
                                                    <button class="dropdown-item" type="button" id="newPatientLst7" onclick="newPatientCount(this.id)">Last 7 Days</button>
                                                    <button class="dropdown-item" type="button" id="newPatientLst30" onclick="newPatientCount(this.id)">Last 30 DAYS</button>
                                                    <button class="dropdown-item" type="button" id="newPatientOnDt" onclick="newPatientCount(this.id)">By Date</button>
                                                    <button class="dropdown-item" type="button" id="newPatientDtRng" onclick="newPatientCount(this.id)">By Range</button>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- <canvas id="myChart"></canvas> -->
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Earnings (Monthly) Card Example -->
                        <div class="col-xl-3 col-md-6 mb-4">
                            <div class="card border-left-info shadow h-100 py-2">
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Patient
                                                Treated
                                            </div>
                                            <div class="row no-gutters align-items-center">
                                                <div class="col-auto">
                                                    <div class="h5 mb-0 mr-3 font-weight-bold text-gray-800">0</div>
                                                </div>
                                                <div class="col">
                                                    <div class="progress progress-sm mr-2">
                                                        <div class="progress-bar bg-info" role="progressbar" style="width: 50%" aria-valuenow="50" aria-valuemin="0" aria-valuemax="100"></div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-auto">
                                            <i class="fas fa-theater-masks"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Pending Requests Card Example -->
                        <div class="col-xl-3 col-md-6 mb-4">
                            <div class="card border-left-warning shadow h-100 py-2 pending_border">
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                                Pending Requests</div>
                                            <div class="h5 mb-0 font-weight-bold text-gray-800">0</div>
                                        </div>
                                        <div class="col-auto">
                                            <i class="fas fa-pencil-ruler"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>


                    <!-- ================ THIRD ROW ================ -->
                    <div class="row">

                        <!-- Expiring in 3 Months Card -->
                        <div class="col-xl-3 col-md-6 mb-4">
                            <?php require_once PORTAL_COMPONENT . "expiring.php"; ?>
                        </div>

                        <!----------- Sales of the day card ----------->
                        <div class="col-xl-3 col-md-6 mb-4">
                            <?php require_once PORTAL_COMPONENT . "salesoftheday.php"; ?>
                        </div>

                        <!----------- Purchase today card ----------->
                        <div class="col-xl-3 col-md-6 mb-4">
                            <?php require_once PORTAL_COMPONENT . "purchasedToday.php"; ?>
                        </div>

                    </div>

                    <!-- ================ FORTH ROW ROW ================ -->
                    <div class="row">

                        <div class="col-xl-6 col-md-6 mb-4">
                            <?php require_once PORTAL_COMPONENT . "mostsolditems.php"; ?>
                        </div>
                        <div class="col-xl-6 col-md-6 mb-4">
                            <?php require_once PORTAL_COMPONENT . "lesssolditems.php"; ?>
                        </div>
                        <br>
                        <div class="col-xl-6 col-md-6 mb-4">
                            <?php require_once PORTAL_COMPONENT . "mostvisitedcustomer.php"; ?>
                        </div>
                        <br>
                        <div class="col-xl-6 col-md-6 mb-4">
                            <?php require_once PORTAL_COMPONENT . "highestpurchasedcustomer.php"; ?>
                        </div>

                    </div>

                    <div class="row">
                        <div class="col-xl-3 col-md-6 mb-4">
                            <?php require_once PORTAL_COMPONENT . "mopdByAmount.php"; ?>
                        </div>

                        <div class="col-xl-3 col-md-6 mb-4">
                            <?php require_once PORTAL_COMPONENT . "mopdByItems.php"; ?>
                        </div>

                    </div>

                    <!-- ================== SECOND ROW ================== -->
                    <div class="row">
                        <div class="col-xl-6 col-md-12">
                            <!------------- NEEDS TO COLLECT PAYMENTS -------------->
                            <div class="mb-4">
                                <div class="card border-top-primary pending_border animated--grow-in">
                                    <div class="card-body">
                                        <a class="text-decoration-none" href="#">
                                            <div class="row no-gutters align-items-center">
                                                <div class="col mr-2">
                                                    <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                                        Sales Margin
                                                    </div>
                                                    <div class="table-responsive">

                                                        <table class="table">
                                                            <thead>
                                                                <tr>
                                                                    <th scope="col">Item Name</th>
                                                                    <th scope="col">Pack</th>
                                                                    <th scope="col">MRP</th>
                                                                    <th scope="col">Margin</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                <tr>
                                                                    <th scope="row">Current</th>
                                                                    <td>00.00</td>
                                                                    <td>00.00</td>
                                                                    <td>00.00</td>
                                                                </tr>
                                                                <tr>
                                                                    <th scope="row">Expired</th>
                                                                    <td>00.00</td>
                                                                    <td>00.00</td>
                                                                    <td>00.00</td>
                                                                </tr>
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div>
                                            </div>
                                        </a>
                                    </div>
                                </div>
                            </div>
                            <!------------- END NEEDS TO COLLECT PAYMENTS -------------->
                        </div>
                        <div class="col-xl-3 col-md-6">
                            <!------------- NEEDS TO COLLECT PAYMENTS -------------->
                            <?php require_once PORTAL_COMPONENT . "needstocollect.php"; ?>
                            <!------------- END NEEDS TO COLLECT PAYMENTS -------------->

                        </div>

                        <div class="col-xl-3 col-md-6">
                            <!------------- NEEDS TO PAY  -------------->
                            <?php require_once PORTAL_COMPONENT . "needtopay.php"; ?>
                            <!------------- END NEEDS TO PAY  -------------->
                        </div>

                    </div>
                    <!-- ================ END SECOND ROW ================ -->

                    <!-- ================ STRAT THIRD ROW ================ -->
                    <div class="row">
                        <div class="col-xl-3 col-md-6">
                            <!-- Current Stock Quantity & MRP  -->
                            <?php require_once PORTAL_COMPONENT . "stock-mrp-ptr.php"; ?>
                        </div>
                        <div class="col-xl-9 col-md-6">
                            <!------------- Stock Summary -------------->
                            <?php require_once PORTAL_COMPONENT . "stock-summary.php"; ?>
                            <!------------- end Stock Summary -------------->

                        </div>
                    </div>
                    <!-- ================ END OF THIRD ROW ================ -->

                </div>
                <!-- /.container-fluid -->
            </div>
            <!-- End of Main Content -->

            <!-- Footer -->
            <?php include PORTAL_COMPONENT . 'footer-text.php'; ?>
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
    <script src="vendor/jquery/jquery.min.js"></script>
    <script src="../js/bootstrap-js-4/bootstrap.bundle.min.js"></script>

    <!-- Core plugin JavaScript-->
    <script src="vendor/jquery-easing/jquery.easing.min.js"></script>

    <!-- Custom scripts for all pages-->
    <script src="js/sb-admin-2.min.js"></script>

    <!-- Page level plugins -->
    <!-- <script src="vendor/chart.js/Chart.min.js"></script> -->

    <!-- Page level custom scripts -->
    <!-- <script src="js/demo/chart-area-demo.js"></script>
    <script src="js/demo/chart-pie-demo.js"></script> -->

    <!-- ======== CUSTOM JS FOR INDEX PAGE ======= -->


    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        function newPatientByDt() {
            var newPatientDt = document.getElementById('newPatientDt').value;
            var dataToSend = `mostVstCstmrByDt=${newPatientDt}`;

            newPatientDtUrl = `../admin/ajax/most-visit-and-purchase-customer.ajax.php`;
            xmlhttp.open("POST", newPatientDtUrl, false);
            xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
            xmlhttp.send(dataToSend);
            var newPatientDataByDate = xmlhttp.responseText;

            newPatientDataFunction(JSON.parse(newPatientDataByDate));
        }

        function newPatientCount(buttonId) {

            switch (buttonId) {
                case 'newPatientLst24hrs':
                    document.getElementById('newPatients').textContent = <?= $newPatientLast24Hours ?>;
                    break;
                case 'newPatientLst7':
                    document.getElementById('newPatients').textContent = <?= $newPatientLast7Days ?>;
                    break;
                case 'newPatientLst30':
                    document.getElementById('newPatients').textContent = <?= $newPatientLast30Days ?>;
                    break;
                case 'newPatientOnDt':
                    document.getElementById('mostVistedCustomerDtPkr').style.display = 'block';
                    newPatientDataFunction
                    break;
                case 'newPatientDtRng':
                    document.getElementById('newPatients').textContent = <?= $newPatientsInRangeDate ?>;
                    break;
                default:
                    document.getElementById('newPatients').textContent = <?= $newPatients   ?>;
                    break;
            }
        }





        /// for line chart ///
        const ctx = document.getElementById('myChart');
        const labels = ['Red', 'Blue', 'Yellow', 'Green', 'Purple', 'Orange'];
        new Chart(ctx, {
            type: 'line',
            data: {
                labels: labels,
                datasets: [{
                    label: '# of Votes',
                    data: [65, 59, 80, 81, 56, 55, 40],
                    borderColor: 'rgb(75, 192, 192)',
                    tension: 0.1
                }]
            }
        });
    </script>

</body>

</html>