<?php
require_once dirname(__DIR__) . '/config/constant.php';
require_once SUP_ADM_DIR . '_config/sessionCheck.php'; //check admin loggedin or not

require_once CLASS_DIR . 'dbconnect.php';
require_once SUP_ADM_DIR . '_config/healthcare.inc.php';
require_once CLASS_DIR . 'plan.class.php';
require_once CLASS_DIR . 'pagination.class.php';

// $page = "manufacturer";

//Class Initilizing
// $Distributor = new Distributor();
$Plan = new Plan();
$Pagination = new Pagination;

$plans = json_decode($Plan->allPlans());
if ($plans->status == 1) {
    $plans = $plans->data;
}

//alert for form data inserted or failed
if (isset($_GET['return'])) {
    if ($_GET['return'] == "true") {
        echo "<script>alert('Manufacturer Added!');</script>";
    } else {
        echo "<script>alert('Manufacturer Insertion Failed!');</script>";
    }
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

    <title>Manufacturers of Medicy Health Care</title>

    <!-- Custom fonts for this template-->
    <link href="<?= PLUGIN_PATH ?>fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">

    <!-- Custom styles for this template-->
    <link href="<?= CSS_PATH ?>sb-admin-2.min.css" rel="stylesheet">

    <!-- <link href="<?= PLUGIN_PATH ?>datatables/dataTables.bootstrap4.min.css" rel="stylesheet"> -->


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
                    <div class="card shadow mb-4">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h6 class=" col-6 font-weight-bold text-primary">List of Plans</h6>
                            <button type="button" class="btn btn-sm btn-primary" data-toggle="modal" data-target="#staticBackdrop">
                                Add
                            </button>
                        </div>
                        <div class="card-body">
                            <!-- Showing Unit Table -->
                            <div class="table-responsive">
                                <table class="table table-sm table-bordered" id="dataTable" width="100%" cellspacing="0">
                                    <thead>
                                        <tr>
                                            <th>Plan Id</th>
                                            <th>Name</th>
                                            <th>Duration</th>
                                            <th>Price</th>
                                            <th>Sold</th>
                                            <th>Status</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php

                                        foreach ($plans as $eachPlan) {
                                            $planId         = $eachPlan->id;
                                            $planName       = $eachPlan->name;
                                            $duration       = $eachPlan->duration;
                                            $price          = $eachPlan->price;
                                            $status         = $eachPlan->status;

                                            $statusLabel = '';
                                            $statusColor = '';
                                            switch ($status) {
                                                case 2:
                                                    $statusLabel = 'Disabled';
                                                    $statusColor = 'danger';
                                                    break;
                                                case 0:
                                                    $statusLabel = 'Pending';
                                                    $statusColor = 'warning';
                                                    break;
                                                case 1:
                                                    $statusLabel = 'Active';
                                                    $statusColor = 'primary';
                                                    break;
                                                default:
                                                    $statusLabel = 'Disabled';
                                                    break;
                                            }
                                        ?>

                                            <tr>
                                                <td><?= $planId ?></td>
                                                <td><?= $planName ?></td>
                                                <td><?= $duration ?></td>
                                                <td><?= $price ?></td>
                                                <td><?php ?></td>
                                                <td>
                                                    <span class="badge badge-<?= $statusColor ?>"><?= $statusLabel ?></span>
                                                </td>
                                                <td>
                                                    <span class="text-primary cursor-pointer" data-toggle="modal" data-target="#planModal" onclick="getView('plans.view.update.ajax.php', 'Id', <?= $planId ?>, 'planModal')">
                                                        <i class="fas fa-edit"></i>
                                                    </span>

                                                    <a class="ms-2" id="delete-btn" data-id="' . $planId . '"><i class="far fa-trash-alt"></i></a>
                                                </td>
                                            </tr>
                                        <?php } ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <!-- / Card -->
                </div>
                <!-- /.container-fluid -->
            </div>
            <!--/ .End of Main Content -->
        </div>
        <!-- End of Content Wrapper -->

    </div>
    <!-- End of Page Wrapper -->

    <!-- Modal -->
    <div class="modal fade" id="staticBackdrop" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="staticBackdropLabel">Add New Plan</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div id="add-alert">

                    </div>
                    <form onsubmit="addPlan(event)" id="plan-form">
                        <div class="row mx-0">
                            <div class="col-12 py-1">
                                <input type="text" class="form-control" name="plan-name" placeholder="Plan Name">
                            </div>
                            <div class="col-12 py-1">
                                <input type="text" class="form-control" name="plan-duration" placeholder="Plan Duration">
                            </div>
                            <div class="col-6 py-1">
                                <input type="number" class="form-control" name="plan-price" placeholder="Price">
                            </div>
                            <div class="col-6 py-1">
                                <div class="form-group">
                                    <select class="form-control" name="plan-status">
                                        <option value="" selected disabled>Select Status</option>
                                        <option value="0">Deactive</option>
                                        <option value="1">Active</option>
                                    </select>
                                </div>
                            </div>

                            <div id="features" class="col-12 py-1">
                                <div class="form-group">
                                    <div class="d-flex my-2">
                                        <input type="text" class="form-control form-control-sm" name="features[]" placeholder="Feature">
                                        <button type="button" class="btn btn-sm btn-danger remove-feature rounded-right">
                                            <i class="far fa-times-circle"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                            <div class="col-12">
                                <button type="button" class="btn btn-sm btn-primary w-100" id="addFeature">
                                    Add Feature <i class="fas fa-plus-circle"></i>
                                </button>
                            </div>

                        </div>

                        <div class="mt-2 reportUpdate" id="reportUpdate">
                            <!-- Ajax Update Reporet Goes Here -->
                        </div>

                        <div class="mt-2 d-flex justify-content-end">
                            <button type="submit" class="btn btn-sm btn-primary">Add</button>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Manufacturer View and Edit Modal -->
    <div class="modal fade" id="planModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">View and Edit Plan Details</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body planModal">
                    <!-- Details Appeare Here by Ajax  -->
                </div>
            </div>
        </div>
    </div>
    <!--/end Manufacturer View and Edit Modal -->

    <!-- Bootstrap core JavaScript-->
    <script src="<?= PLUGIN_PATH ?>jquery/jquery.min.js"></script>
    <script src="<?= JS_PATH ?>bootstrap-js-4/bootstrap.bundle.min.js"></script>

    <!-- Sweet Alert Js  -->
    <!-- <script src="<?= JS_PATH ?>sweetAlert.min.js"></script> -->
    <!-- <script src="<?= JS_PATH ?>sweetalrt2.all.js"></script> -->



    <!-- Core plugin JavaScript-->
    <script src="<?= PLUGIN_PATH ?>jquery-easing/jquery.easing.min.js"></script>

    <!-- Custom scripts for all pages-->
    <script src="<?= JS_PATH ?>sb-admin-2.min.js"></script>
    <script src="<?= JS_PATH ?>ajax.custom-lib.js"></script>


    <script>
        document.addEventListener("DOMContentLoaded", function() {
            document.getElementById('addFeature').addEventListener('click', function() {
                var featureDiv = document.createElement('div');
                featureDiv.className = 'form-group';
                featureDiv.innerHTML = `<div class="d-flex my-2">
                    <input type="text" class="form-control form-control-sm" name="features[]" placeholder="Feature">
                    <button class="btn btn-sm btn-danger remove-feature rounded-right">
                        <i class="far fa-times-circle"></i>
                    </button>
                </div>`;

                document.getElementById('features').appendChild(featureDiv);

                // Add event listener to the new remove button
                featureDiv.querySelector('.remove-feature').addEventListener('click', function() {
                    featureDiv.remove();
                });
            });

            // Add event listener to the existing remove button
            document.querySelectorAll('.remove-feature').forEach(function(button) {
                button.addEventListener('click', function() {
                    this.parentElement.remove();
                });
            });
        });

        function addPlan(event) {
            // Prevent default form submission
            event.preventDefault();

            // Get the form element
            const form = event.target;

            // Create a new FormData object (preferred way to handle form data)
            const formData = new FormData(form);

            // Send AJAX request
            $.ajax({
                type: "POST", // Adjust according to your server-side script (POST/GET)
                url: "ajax/plans.add.ajax.php", // Replace with the actual target URL
                data: formData,
                processData: false, // Don't process data automatically with FormData
                contentType: false, // Set content type to false for FormData
                success: function(response) {
                    // Handle successful response from the server
                    if (response == 1) {
                        const successAlert = `<div class="alert alert-primary" role="alert">
                        <strong>Success</strong> New Plan Added!
                        </div>`;
                        document.getElementById('add-alert').innerHTML = successAlert;
                    }
                    document.getElementById("plan-form").reset();
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    // Handle any errors during the request
                    console.error("Error submitting form:", textStatus, errorThrown);
                    // You can display an error message to the user here
                }
            });

        }

        //delete manufacturer
        // $(document).ready(function() {
        //     $(document).on("click", "#delete-btn", function() {

        //         swal({
        //                 title: "Are you sure?",
        //                 text: "Want to Delete This Manufacturer?",
        //                 icon: "warning",
        //                 buttons: true,
        //                 dangerMode: true,
        //             })
        //             .then((willDelete) => {
        //                 if (willDelete) {

        //                     manufId = $(this).data("id");
        //                     btn = this;

        //                     $.ajax({
        //                         url: "ajax/manufacturer.Delete.ajax.php",
        //                         type: "POST",
        //                         data: {
        //                             id: manufId
        //                         },
        //                         success: function(response) {
        //                             alert(response);
        //                             if (response) {
        //                                 $(btn).closest("tr").fadeOut()
        //                                 swal("Deleted", "Manufacturer Has Been Deleted", "success");
        //                             } else {
        //                                 swal("Delete Not Possible", response, "warning");
        //                             }
        //                         }
        //                     });

        //                 }
        //                 return false;
        //             });

        //     })
        // })
    </script>


</body>

</html>