<?php
require_once __DIR__ . '/config/constant.php';
require_once ROOT_DIR . '_config/sessionCheck.php'; //check admin loggedin or not
// require_once ROOT_DIR . '_config/accessPermission.php';

require_once CLASS_DIR . 'dbconnect.php';
require_once ROOT_DIR . '_config/healthcare.inc.php';
require_once CLASS_DIR . 'products.class.php';
require_once CLASS_DIR . 'request.class.php';
require_once CLASS_DIR . 'productsImages.class.php';
require_once CLASS_DIR . 'pagination.class.php';
// require_once ROOT_DIR . '_config/accessPermission.php';
require_once CLASS_DIR . 'encrypt.inc.php';

//Intitilizing Doctor class for fetching doctors
$Products       = new Products();
$Request        = new Request;
$Pagination     = new Pagination();
$ProductImages  = new ProductImages();




$allRequestResult = [];

$requestTypes = [
    'product_request' => ['tableName' => 'Product Request', 'data' => []],
    'distributor_request' => ['tableName' => 'Distributor Request', 'data' => []],
    'manufacturer_request' => ['tableName' => 'Manufacturer Request', 'data' => []],
    'packtype_request' => ['tableName' => 'Packtype Request', 'data' => []],
    'distributor' => ['tableName' => 'Distributer Add', 'data' => []],
    'manufacturer' => ['tableName' => 'manufacturer Add', 'data' => []],
    'packaging_type' => ['tableName' => 'packaging Add', 'data' => []],
    'quantity_unit' => ['tableName' => 'quantity add', 'data' => []]
];

foreach ($requestTypes as $table => &$requestType) {

    // print_r($table);

    $requestData = json_decode($Request->fetchRequestDataByTableName($table, $adminId));
    // print_r($requestData);

    if ($requestData->status) {
        $requestType['data'] = $requestData->data;
    } else {
        $requestType['data'] = [];
    }

    // foreach ($requestType['data'] as $requestDataItem) {
    //     // print_r($requestType);
    //     if ($requestType['tableName'] != 'Product Request') {
    //         if ($requestType['tableName'] == 'packaging_type') {
    //             $allRequestResult[] = [
    //                 'tableName' => $requestType['tableName'],
    //                 'name' => $requestDataItem->unit_name,
    //                 // 'description' => $requestDataItem->req_dsc
    //                 // 'description' => property_exists($requestDataItem, 'dsc') ? $requestDataItem->dsc : ''
    //             ];
    //         } else {
    //             $allRequestResult[] = [
    //                 'tableName' => $requestType['tableName'],
    //                 'name' => $requestDataItem->name,
    //                 // 'description' => $requestDataItem->dsc
    //                 'description' => property_exists($requestDataItem, 'dsc') ? $requestDataItem->dsc : ''
    //             ];
    //         }
    //     } elseif ($requestType['tableName'] == 'Product Request') {
    //         $allRequestResult[] = [
    //             'tableName' => $requestType['tableName'],
    //             'name' => $requestDataItem->name,
    //             'description' => $requestDataItem->req_dsc
    //             // 'description' => property_exists($requestDataItem, 'dsc') ? $requestDataItem->dsc : ''
    //         ];
    //     }
    // }

    foreach ($requestType['data'] as $requestDataItem) {
        // print_r($requestType);
        if ($requestType['tableName'] == 'Product Request') {
            $allRequestResult[] = [
                'tableName'   => $requestType['tableName'],
                'name'        => $requestDataItem->name,
                'description' => $requestDataItem->req_dsc
            ];
        } elseif ($requestType['tableName'] == 'Packtype Request') {
            $allRequestResult[] = [
                'tableName'   => $requestType['tableName'],
                'name'        => $requestDataItem->unit_name,
                'description' => property_exists($requestDataItem, 'dsc') ? $requestDataItem->dsc : ''
            ];
        } elseif ($requestType['tableName'] == 'packaging Add') {
            $allRequestResult[] = [
                'tableName'   => $requestType['tableName'],
                'name'        => $requestDataItem->unit_name,
                'description' => property_exists($requestDataItem, 'dsc') ? $requestDataItem->dsc : ''
            ];
        } elseif ($requestType['tableName'] == 'quantity add') {
            $allRequestResult[] = [
                'tableName'   => $requestType['tableName'],
                'name'        => $requestDataItem->short_name,
                'description' => property_exists($requestDataItem, 'dsc') ? $requestDataItem->dsc : ''
            ];
        } else {
            $allRequestResult[] = [
                'tableName'   => $requestType['tableName'],
                'name'        => $requestDataItem->name,
                'description' => property_exists($requestDataItem, 'dsc') ? $requestDataItem->dsc : ''
            ];
        }
    }
}


// print_r($allRequestResult);


// $allRequestResult = array_merge($prodReqObjData, $distReqObjData);
// $allRequestResult = array_merge($allRequestResult, $manufReqObjData);

$pagination = json_decode($Pagination->arrayPagination($allRequestResult));


if ($pagination->status == 1) {
    $result = $pagination;
    $allProducts = $pagination->items;
    $totalPtoducts = $pagination->totalitem;
} else {
    // Handle the case when status is not 1
    $result = $pagination;
    $allProducts = [];
    $totalPtoducts = 0;
}

// print_r($result);
?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Edit Requests</title>

    <!-- Custom fonts for this template -->
    <link href="<?php echo PLUGIN_PATH ?>fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">

    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="<?php echo CSS_PATH ?>sb-admin-2.min.css" rel="stylesheet">
    <link rel="stylesheet" href="<?php echo CSS_PATH ?>custom/products.css">
    <!-- Custom styles for this page -->
    <link href="<?php echo PLUGIN_PATH ?>datatables/dataTables.bootstrap4.min.css" rel="stylesheet">
    <link rel="stylesheet" href="<?= CSS_PATH ?>custom-dropdown.css">


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

                <!-- Begin container-fluid -->
                <div class="container-fluid">

                    <div class="card-body">
                        <div class="card-header py-3 justify-content-between">

                        </div>

                        <div class="table-responsive">
                            <table class="table table-bordered sortable-table" id="appointments-dataTable" width="100%" cellspacing="0">
                                <thead>
                                    <tr>
                                        <th class="col-2">Category</th>
                                        <th class="col-3">Item Name</th>
                                        <th class="col-6">Description</th>
                                        <th class="col-1">Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    if (!empty($result)) {
                                        $resultItems = $result->items;
                                        $count = 0;
                                        foreach ($resultItems as $resItems) {
                                            $count++;
                                            // print_r($resItems);
                                            if ($resItems->tableName != null) {
                                                $tableName = $resItems->tableName;
                                            } else {
                                                $tableName = '';
                                            }
                                            // echo $count;

                                            if ($resItems->name != null) {
                                                $itemName = $resItems->name;
                                            } else {
                                                $itemName = '';
                                            }

                                            if ($resItems->description != null) {
                                                $description = $resItems->description;
                                            } else {
                                                $description = '';
                                            }

                                            $status = 'Request Pending';

                                            echo '<tr>
                                                        <td>' . $tableName . '</td>
                                                        <td>' . $itemName . '</td>
                                                        <td>' . $description . '</td>
                                                        <td style="color: red;">' . $status . '</td>
                                                    </tr>';
                                        }
                                    }
                                    // href="ajax/appointment.delete.ajax.php?appointmentId='.$appointmentID.'"
                                    ?>

                                </tbody>
                            </table>
                        </div>
                        <div class="d-flex justify-content-center" id="pagination-control">
                            <?= $result->paginationHTML ?>
                        </div>
                    </div>

                </div>
                <!-- End of container-fluid -->

            </div>
            <!-- End of Main Content -->

            <!-- Footer -->
            <?php include_once ROOT_COMPONENT . 'footer-text.php'; ?>
            <!-- End of Footer -->

        </div>
        <!--End of Content Wrapper -->

    </div>
    <!-- End of Page Wrapper -->
    
        <a class="scroll-to-top rounded" href="#page-top">
            <i class="fas fa-angle-up"></i>
        </a>

        <!-- Bootstrap core JavaScript-->
        <script src="<?= PLUGIN_PATH ?>jquery/jquery.min.js"></script>
        <script src="<?= JS_PATH ?>bootstrap-js-4/bootstrap.bundle.min.js"></script>
        <!-- <script src="<?= JS_PATH ?>sweetAlert.min.js"></script> -->
        <!-- Core plugin JavaScript-->
        <!-- <script src="../assets/jquery-easing/jquery.easing.min.js"></script> -->

        <!-- Custom scripts for all pages-->
        <script src="<?= JS_PATH ?>sb-admin-2.min.js"></script>
        <!-- <script src="../js/ajax.custom-lib.js"></script> -->
        <!-- <script src="../js/sweetAlert.min.js"></script> -->
</body>
        
</html>