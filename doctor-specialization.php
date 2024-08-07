<?php
$page = "doc-specialization";
require_once __DIR__ . '/config/constant.php';
require_once ROOT_DIR . '_config/sessionCheck.php';


require_once CLASS_DIR . 'dbconnect.php';
require_once ROOT_DIR . '_config/healthcare.inc.php';
require_once CLASS_DIR . 'doctor.category.class.php';

$nodataFound = 'NO DATA FOUND';
// INITILIZATION CLASSES
$DoctorCategory = new DoctorCategory();

// FUNCTION INITILIZATION 
$showDoctorCategory = $DoctorCategory->showDoctorCategory();


?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    
    <link rel="icon" type="image/x-icon" href="<?= FAVCON_PATH ?>">
    <title>Doctor Specialization - <?= $healthCareName ?></title>

    <link rel="stylesheet" href="<?= CSS_PATH ?>sb-admin-2.css" type="text/css">
    <link rel="stylesheet" href="<?= PLUGIN_PATH ?>fontawesome-free/css/all.min.css" type="text/css">


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
                    <div class="row">
                        <div class="col-md-5">
                            <div class="card shadow mb-4">
                                <div class="card-body">
                                    <!-- <form method="post"> -->

                                    <div class="col-md-12">
                                        <label class="mb-0 mt-1" for="splz-name">Specialization<span class="text-danger font-weight-bold">*</span></Address></label>
                                        <input class="form-control" id="splz-name" name="splz-name" placeholder="Doctor Specialization Name" required>
                                    </div>

                                    <div class="col-md-12 mt-3">
                                        <label class="mb-0 mt-1" for="splz-dsc">Description</Address></label>
                                        <textarea class="form-control" name="splz-dsc" id="splz-dsc" cols="30" rows="4" required></textarea>
                                    </div>

                                    <div class="d-grid gap-2 d-md-flex justify-content-md-end mt-3 me-md-2">
                                        <button class="btn btn-primary me-md-2 add-doctor-specialization" id="add-btn" name="add" type="submit">Add</button>
                                    </div>
                                    <!-- </form> -->
                                </div>
                            </div>
                        </div>

                        <div class="col-md-7">
                            <div class="card shadow mb-4">
                                <div class="card-body">
                                    <!-- Showing Unit Table -->
                                    <div class="table-responsive">
                                        <table class="table table-sm table-bordered">
                                            <thead>
                                                <tr>
                                                    <th>ID</th>
                                                    <th>Specialization</th>
                                                    <th>Description</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                                if (is_array($showDoctorCategory) && $showDoctorCategory != null) {
                                                    foreach ($showDoctorCategory as $rowDoctorCategory) {
                                                        $specializationid = $rowDoctorCategory['doctor_category_id'];
                                                        $specializationName = $rowDoctorCategory['category_name'];
                                                        $specializationDsc = $rowDoctorCategory['category_descreption'];

                                                        echo '<tr>
                                                            <td>' . $specializationid . '</td>
                                                            <td>' . $specializationName . '</td>
                                                            <td>' . substr($specializationDsc, 0, 65) . '..</td>
                                                        </tr>';
                                                    }
                                                }else {
                                                    echo '<tr class="text-center"><td colspan="3">No Data Found</td></tr>';
                                                }
                                                ?>

                                            </tbody>
                                        </table>
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
            <!-- <?php include ROOT_COMPONENT . 'footer-text.php'; ?> -->
            <!-- End of Footer -->

        </div>
        <!-- End of Content Wrapper -->

    </div>
    <!-- End of Page Wrapper -->

    <?php include ROOT_COMPONENT . 'generateTicket.php'; ?>

    <!-- View & Edit Doctor Category Modal -->
    <div class="modal fade docCatModal" tabindex="-1" role="dialog" aria-labelledby="docCatModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLongTitle">Doctor Specialization Details</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body view-and-edit-specialization">
                    <!-- Appointments Details Goes Here By Ajax -->
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-sm btn-secondary" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-sm btn-primary" onclick="window.location.reload()">Update</button>
                </div>
            </div>
        </div>
    </div>
    <!-- /End View & Edit Doctor Category Modal -->

    <!-- Bootstrap core JavaScript-->
    <script src="<?php echo PLUGIN_PATH ?>jquery/jquery.min.js"></script>
    <script src="<?php echo JS_PATH ?>bootstrap-js-4/bootstrap.bundle.min.js"></script>

    <!-- Custom JS -->
    <script src="<?php echo JS_PATH ?>custom-js.js"></script>
    <script src="<?php echo JS_PATH ?>ajax.custom-lib.js"></script>

    <!-- Core plugin JavaScript-->
    <script src="<?php echo PLUGIN_PATH ?>jquery-easing/jquery.easing.min.js"></script>

    <!-- Custom scripts for all pages-->
    <script src="<?php echo JS_PATH ?>sb-admin-2.min.js"></script>

    <!-- Page level custom scripts -->
    <script src="<?php echo JS_PATH ?>sweetalert2/sweetalert2.all.min.js"></script>



    <script>
        // add doctor specialization 
        $(document).ready(function() {
            $(document).on("click", ".add-doctor-specialization", function() {
                let docSpecialization = document.getElementById('splz-name').value;
                let docSpecializationDescription = document.getElementById('splz-dsc').value;

                if (docSpecialization != '' && docSpecializationDescription != '') {

                    // ajax/doc-specialization-add.ajax.php
                    $.ajax({
                        url: "ajax/doc-specialization-add.ajax.php",
                        type: "POST",
                        data: {
                            docSpeclzn: docSpecialization,
                            docSpeclznDsc: docSpecializationDescription
                        },
                        success: function(response) {
                            // alert(response);
                            if (response) {
                                // Swal.fire('Success', 'Data added successfully', 'success');
                                window.location.reload();
                            } else {
                                // Swal.fire('Fail', 'Fail to add data.', 'error');
                                window.location.reload();
                            }
                        }
                    });

                } else {
                    Swal.fire('Check', 'Fill both Doctor specialization and Description field.', 'info');
                }

                document.getElementById('splz-name').value = '';
                document.getElementById('splz-dsc').value = '';
            })
        })


        //View and Update Doctor Specialization
        viewAndEdit = (specializationid) => {
            let url = "ajax/docCatView.ajax.php?specializationid=" + specializationid;
            $(".view-and-edit-specialization").html(
                '<iframe width="99%" height="340px" frameborder="0" allowtransparency="true" src="' +
                url + '"></iframe>');
        }


        // Delete Doctor Specialization
        $(document).ready(function() {
            $(document).on("click", ".delete-btn", function() {

                Swal.fire({
                    title: "Are you sure?",
                    text: "You won't be able to revert this!",
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#3085d6",
                    cancelButtonColor: "#d33",
                    confirmButtonText: "Yes, delete it!"

                }).then((result) => {
                    if (result.isConfirmed) {

                        CatId = $(this).data("id");
                        // alert(CatId);
                        btn = this;

                        $.ajax({
                            url: "ajax/docCat.delete.ajax.php",
                            type: "POST",
                            data: {
                                id: CatId
                            },
                            success: function(response) {
                                if (response == 1) {
                                    $(btn).closest("tr").fadeOut()
                                } else {
                                    Swal.fire("Error", "Deletion Failed!", "Error");
                                    $("#error-message").html("Deletion Failed !!!").slideDown();
                                    $("success-message").slideUp();
                                }
                            }
                        });
                    }
                });
            })
        })
    </script>


</body>

</html>