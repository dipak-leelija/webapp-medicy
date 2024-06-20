<?php
require_once __DIR__ . '/config/constant.php';
require_once ROOT_DIR . '_config/sessionCheck.php'; //check admin loggedin or not

require_once CLASS_DIR . 'dbconnect.php';
require_once ROOT_DIR . '_config/healthcare.inc.php';
require_once CLASS_DIR . 'stockIn.class.php';
require_once CLASS_DIR . 'stockInDetails.class.php';
require_once CLASS_DIR . 'distributor.class.php';
require_once CLASS_DIR . 'encrypt.inc.php';



$StockIn = new StockIn;
$StockInDetails = new StockInDetails;
$Distributor = new Distributor;


$stockInData = $StockIn->showStockIn($adminId);
$distributorList = json_decode($Distributor->showDistributor($adminId));



if (isset($_GET['reportGenerat'])) {
    if ($_GET['reportGenerat']) {
        $filterItem = '';
        $startDate = $_GET['startDate'];
        $endDate = $_GET['endDate'];
        $distId = $_GET['distId'];
        $purchaseType = $_GET['purchaeType'];

        $purchaseReport = json_decode($StockIn->stockInSearch($filterItem, $distId, $startDate, $endDate, $purchaseType, $adminId));
    }
} else {
    $purchaseReport = [];
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

    <title>Reports</title>

    <!-- Custom fonts for this template-->
    <link href="<?php echo PLUGIN_PATH; ?>fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">

    <link href="<?php echo CSS_PATH; ?>sb-admin-2.css" rel="stylesheet">
    <link href="<?php echo CSS_PATH; ?>bootstrap 5/bootstrap.css" rel="stylesheet">
    <link href="<?php echo CSS_PATH; ?>custom/custom.css" rel="stylesheet">
    <link rel="stylesheet" href="<?= CSS_PATH ?>custom-dropdown.css">

</head>

<body id="page-top">

    <!-- Page Wrapper -->
    <div id="wrapper">

        <!-- sidebar -->
        <?php
        // include ROOT_COMPONENT.'sidebar.php'; 
        ?>
        <!-- end sidebar -->

        <!-- Content Wrapper -->
        <div id="content-wrapper" class="d-flex flex-column">

            <!-- Main Content -->
            <div id="content">

                <!-- Topbar -->
                <?php include ROOT_COMPONENT . 'report-topbar.php'; ?>
                <!-- End of Topbar -->

                <!-- Begin Page Content -->
                <div class="container-fluid">
                    <!-- Page Heading -->
                    <div class="row">
                        <div class="col-md-9 pt-2 pl-4">
                            <nav style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="reports.php" class="text-decoration-none">Reports</a></li>
                                    <li class="breadcrumb-item active" aria-current="page">Purchase Report</li>
                                </ol>
                            </nav>
                        </div>
                        <div class="col-md-3 d-flex text-center p-3 pt-0">
                            <div class="col-sm-4">
                                <button type="button" id="print-report" name="print-report" class="btn btn-sm btn-primary border-0 w-100 text-center">
                                    Print
                                </button>
                            </div>
                            <div class="col-sm-8 bg-info bg-opacity-10">
                                <select class="c-inp p-1 w-100 text-primary bg-transparent" id="download-file-type" name="download-file-type" onchange="selectDownloadType(this)">
                                    <option value='' disabled selected>Download</option>
                                    <option value='exl'>Download Excel</option>
                                    <option value='csv'>Download CSV File</option>
                                    <option value='pdf'>Download PDF File</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class=" shadow rounded" style="min-height: 70vh;">
                        <div class="row reportNavbar mx-0 rounded d-flex justify-content-start align-items-center">
                            <!-- optional search filter by item name or composition -->
                            <!-- <div class="col-md-3 mt-2">
                                <div class="input-group h-100">
                                    <input class="cvx-inp border-0 w-75" type="text" placeholder="Search By Item Name/Composition" name="appointment-search" id="search-by-item-name" style="outline: none;" value="<?= isset($match) ? $match : ''; ?>" />

                                    <button class="d-none btn btn-sm btn-outline-primary shadow-none input-group-append h-100" id="filter-reset-1" type="button" onclick="resteUrl(this.id)"><i class="fas fa-times"></i></button>
                                </div>
                            </div> -->
                            <!-- filter date -->
                            <div class="col-md-2 bg-white me-3 selectDiv">
                                <select class="cvx-inp1 border-0 p-1 w-100" name="date-filter" id="date-filter" /*onchange="filterAppointmentByValue()" * / onchange="filterDate(this)">
                                    <option value="A">Select All Date</option>
                                    <option value="T">Today</option>
                                    <option value="Y">yesterday</option>
                                    <option value="LW">Last 7 Days</option>
                                    <option value="LM">Last 30 Days</option>
                                    <option value="LQ">Last 90 Days</option>
                                    <option value="CFY">Current Fiscal Year</option>
                                    <option value="PFY">Previous Fiscal Year</option>
                                    <option value="CR">Custom Range </option>
                                </select>
                                <!-- <button class="btn btn-sm btn-outline-primary rounded-0 shadow-none" type="button" id="filter-reset-2" onclick="resteUrl(this.id)" style="z-index: 100; background: white;"><i class="fas fa-times"></i></button> -->
                                <label class="d-none" id="dt-fltr-val">A</label>
                                <label class="d-none" id="select-start-date"></label>
                                <label class="d-none" id="select-end-date"></label>
                            </div>
                            <!-- filter distributor -->
                            <div class="col-md-2 bg-white me-3 selectDiv">
                                <!-- <div class="input-group "> -->
                                    <!-- <input type="text" name="distributor-id" id="distributor-id" class="c-inp w-100 p-1" disable hidden> -->

                                    <!-- <input type="text" name="distributor" id="distributor" class="c-inp w-75 border-0 p-1"> -->

                                    <!-- <button class="btn btn-sm btn-outline-primary shadow-none input-group-append h-100" id="filter-reset-2" type="button" onclick="resteUrl(this.id)"><i class="fas fa-times"></i></button> -->

                                    <!-- <div class="p-2 bg-light col-md-12 c-dropdown" id="dist-list">
                                        <div class="lists" id="lists">
                                            <?php
                                            // if ($distributorList->status) {
                                            //     foreach ($distributorList->data as $distData) {
                                            ?>
                                                    <div class="p-1 border-bottom list" id="<?= $distData->id ?>" onclick="setDistributor(this)">
                                                        <?= $distData->name ?>
                                                    </div>
                                                <?php
                                                // }
                                                ?>
                                        </div>
                                    <?php
                                    // } else {
                                    ?>
                                        <div class="col-sm-12 text-center">
                                            <label for="alert" class="text-danger">No Data Found</label>
                                        </div>
                                    <?php
                                    // }
                                    ?>

                                    </div> -->

                                    <select class="cvx-inp1 border-0 p-1 w-100" name="distributor-filter" id="distributor-filter" /*onchange="filterAppointmentByValue()" * / onchange="filterDistributor(this)">
                                        <option value="AD">All Distributor</option>
                                        <?php
                                        if ($distributorList->status) {
                                            foreach ($distributorList->data as $distData) {
                                        ?>
                                                <option value="<?= $distData->id; ?>"><?= $distData->name; ?></option>
                                        <?php
                                            }
                                        }
                                        ?>
                                    </select>
                                <!-- </div> -->
                                <label class="d-none" id="selected-dist-id">AD</label>
                            </div>
                            <!-- filter purchase type -->
                            <div class="col-md-2 bg-white me-3 selectDiv">
                                <select class="cvx-inp1 border-0 p-1 w-100" name="p-type" id="p-type" onchange="filterPurchaseType(this)">
                                    <option value="APD">All Purchase</option>
                                    <option value="WG">With GST</option>
                                    <option value="NG">Without GST</option>
                                    <option value="C">Cradite</option>
                                    <option value="P">Paid</option>
                                </select>
                                <label class="d-none" id="selected-purchse-type">APD</label>
                            </div>
                            <!-- find button on filter -->
                            <div class="col-md-2 searchFilterDiv">
                                <button type="button" id="search-filter" name="search-filter" class="btn btn-primary text-center btn-sm" onclick="filterSearch()">
                                    Go <i class="fas fa-arrow-right"></i>
                                </button>
                            </div>
                        </div>

                        <div class="row d-flex">
                            <!-- date picker dive -->
                            <label class="d-none" id="date-range-control-flag">0</label>
                            <label class="d-none" id="url-control-flag">0</label>
                            <div class="row dropdown-menu border-0" id="dtPickerDiv" style="display:none;">
                                <div class=" col-md-12">
                                    <div class="d-flex">
                                        <div class="dtPicker" style="margin-right: 1rem;">
                                            <label>Strat Date</label>
                                            <input type="date" id="from-date" name="from-date" onchange="selectStartDate(this)">
                                        </div>
                                        <div class="dtPicker" style="margin-right: 1rem;">
                                            <label>End Date</label>
                                            <input type="date" id="to-date" name="to-date" onchange="selectEndDate(this)">
                                        </div>
                                        <!-- <div class="dtPicker">
                                            <button class="btn btn-sm btn-primary" /*onclick="filterAppointmentByValue()" * />Find</button>
                                        </div> -->
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="table-responsive">
                        <table class="table table-lg">
                            <thead class="text-primary">
                                <tr>
                                    <th scope="col" class="ps-4 pb-4 pt-3">Sl No.</th>
                                    <th scope="col" class="pb-4">Bill No.</th>
                                    <th scope="col" class="pb-4">Entry Date</th>
                                    <th scope="col" class="pb-4">Bill Date</th>
                                    <th scope="col" class="pb-4 w-25">Distributor</th>
                                    <th scope="col" class="text-end ps-3 pb-4">Taxable</th>
                                    <th scope="col" class="text-end ps-0 pb-4">CESS</th>
                                    <th scope="col" class="text-end ps-0 pb-4">SGST</th>
                                    <th scope="col" class="text-end ps-0 pb-4">CGST</th>
                                    <th scope="col" class="text-end ps-0 pb-4">IGST</th>
                                    <th scope="col" class="text-end ps-0 pe-4 pb-4">Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                if (!empty($purchaseReport)) {
                                    if ($purchaseReport->status) {
                                        $purchaseReportData = $purchaseReport->data;
                                        $sl = 0;
                                        foreach ($purchaseReportData as $purchaseData) {
                                            // print_r($purchaseData);
                                            // echo "<br><br>";
                                            $sl++;
                                            $billNo = $purchaseData->distributor_bill;
                                            $entryDate = $purchaseData->added_on;
                                            $billDate = $purchaseData->bill_date;
                                            $distData = json_decode($Distributor->showDistributorById($purchaseData->distributor_id));
                                            if ($distData->status) {
                                                $distName = $distData->data->name;
                                            } else {
                                                $distName = '';
                                            }
                                            $taxable = floatval($purchaseData->amount) - floatval($purchaseData->gst);
                                            $totalGst = $purchaseData->gst;
                                            $cess = '';
                                            $cgst = floatval($totalGst) / 2;
                                            $cgst = $sgst = number_format($cgst, 2);
                                            $igst = '';
                                            $totalAmount = $purchaseData->amount;
                                ?>
                                            <tr>
                                                <td class="ps-4"><?= $sl; ?></td>
                                                <td><?= $billNo; ?></td>
                                                <td><?= $entryDate; ?></td>
                                                <td><?= $billDate; ?></td>
                                                <td><?= $distName; ?></td>
                                                <td class="text-end"><?= $taxable; ?></td>
                                                <td class="text-end"><?= $cess; ?></td>
                                                <td class="text-end"><?= $cgst; ?></td>
                                                <td class="text-end"><?= $sgst; ?></td>
                                                <td class="text-end"><?= $igst; ?></td>
                                                <td class="text-end pe-4"><?= $totalAmount; ?></td>
                                            </tr>
                                <?php
                                        }
                                    } else{
                                        echo "<td colspan='12' class='text-center border-0 pt-4'>
                                        No data found</td>";
                                    }
                                }
                                ?>
                            </tbody>
                        </table>
                        </div>
                    </div>
                </div>
                <!-- /.container-fluid -->

            </div>
            <!-- End of Main Content -->

        </div>
        <!-- End of Content Wrapper -->

    </div>
    <!-- End of Page Wrapper -->

    <!-- Scroll to Top Button-->
    <a class="scroll-to-top rounded" href="#page-top">
        <i class="fas fa-angle-up"></i>
    </a>

    <script src="<?php echo JS_PATH; ?>ajax.custom-lib.js"></script>
    <script src="<?php echo PLUGIN_PATH; ?>jquery/jquery.min.js"></script>
    <script src="<?php echo JS_PATH; ?>bootstrap-js-4/bootstrap.bundle.min.js"></script>

    <!-- Core plugin JavaScript-->
    <script src="<?php echo PLUGIN_PATH; ?>jquery-easing/jquery.easing.min.js"></script>

    <!-- Custom scripts for all pages-->
    <script src="<?php echo JS_PATH; ?>sb-admin-2.min.js"></script>
    <script src="<?= PLUGIN_PATH ?>choices/assets/scripts/choices.js"></script>

    <!-- custom script for report filter -->
    <script src="<?php echo JS_PATH; ?>purchase-report-filter.js"></script>
</body>

</html>