<?php
require_once dirname(__DIR__).'/config/constant.php';
require_once ADM_DIR.'_config/sessionCheck.php'; //check admin loggedin or not

require_once CLASS_DIR.'dbconnect.php';
require_once CLASS_DIR.'distributor.class.php';

$page = "distributor";

//Class Initilizing
$Distributor = new Distributor();

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Distributor of Medicy Health Care</title>

    <!-- Custom fonts for this template-->
    <link href="../assets/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link
        href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
        rel="stylesheet">

    <!-- Custom styles for this template-->
    <link href="css/sb-admin-2.min.css" rel="stylesheet">

    <link href="../assets/datatables/dataTables.bootstrap4.min.css" rel="stylesheet">


</head>

<body id="page-top">

    <!-- Page Wrapper -->
    <div id="wrapper">

        <!-- sidebar -->
        <?php include PORTAL_COMPONENT.'sidebar.php'; ?>
        <!-- end sidebar -->

        <!-- Content Wrapper -->
        <div id="content-wrapper" class="d-flex flex-column">

            <!-- Main Content -->
            <div id="content">

                <!-- Topbar -->
                <?php include PORTAL_COMPONENT.'topbar.php'; ?>
                <!-- End of Topbar -->

                <!-- Begin Page Content -->
                <div class="container-fluid">

                    <!-- Page Heading -->
                    <h1 class="h3 mb-4 text-gray-800">Distributor Management</h1>
                    <div class="row">

                        <!-- Show Distributor -->
                        <div class="col-sm-7">
                            <div class="card shadow">
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                                            <thead>
                                                <tr>
                                                    <th>SL.</th>
                                                    <th>Name</th>
                                                    <th>Contact</th>
                                                    <th>Area PIN</th>
                                                    <th>Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                                    $showDistributor = $Distributor->showDistributor();
                                                    foreach($showDistributor as $rowDistributor){
                                                        $distributorId      = $rowDistributor['id'];
                                                        $distributorName    = $rowDistributor['name'];
                                                        $distributorPhno    = $rowDistributor['phno'];
                                                        $distributorPin     = $rowDistributor['area_pin_code'];

                                                        echo '<tr>
                                                                <td>'.$distributorId.'</td>
                                                                <td>'.$distributorName.'</td>
                                                                <td>'.$distributorPhno.'</td>
                                                                <td>'.$distributorPin.'</td>
                                                                <td>
                                                                    <a class="mx-1" data-toggle="modal" data-target="#distributorModal" onclick="distViewAndEdit('.$distributorId.')"><i class="fas fa-edit"></i></a>

                                                                    <a class="mx-1" id="delete-btn" data-id="'.$distributorId.'"><i class="far fa-trash-alt"></i></a>
                                                                </td>
                                                               </tr>';
                                                    }
                                                    ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- /end Show Distributor -->

                        <!-- Add Distributor -->
                        <div class="col-sm-5">
                            <div class="card shadow">
                                <div class="card-body">
                                    <form method="post" action="_config/form-submission/add-distributor.php">

                                        <div class="col-md-12">
                                            <label class="mb-0 mt-1" for="distributor-name">Distributor Name</Address>
                                            </label>
                                            <input class="form-control" id="distributor-name" name="distributor-name"
                                                placeholder="Distributor Name" maxlength="155" required>
                                        </div>

                                        <div class="col-md-12">
                                            <label class="mb-0 mt-1" for="distributor-phno">Mobile Number</Address>
                                            </label>
                                            <input type="number" class="form-control" id="distributor-phno"
                                                name="distributor-phno" placeholder="Distributor Mobile Number"
                                                oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);"
                                                maxlength="10"
                                                oninput="javascript: if (this.value.length > this.minLength) this.value = this.value.slice(0, this.minLength);"
                                                minlength="10" required>
                                        </div>

                                        <div class="col-md-12">
                                            <label class="mb-0 mt-1" for="distributor-email">Email Address</Address>
                                            </label>
                                            <input type="email" class="form-control" id="distributor-email"
                                                name="distributor-email" placeholder="Distributor Email Address"
                                                maxlength="50">
                                        </div>

                                        <div class="col-md-12">
                                            <label class="mb-0 mt-1" for="distributor-area-pin">Area PIN Code</Address>
                                            </label>
                                            <input type="number" class="form-control" id="distributor-area-pin"
                                                name="distributor-area-pin" placeholder="Distributor Area PIN Code"
                                                oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);"
                                                maxlength="7"
                                                oninput="javascript: if (this.value.length > this.minLength) this.value = this.value.slice(0, this.minLength);"
                                                minlength="7" required>
                                        </div>

                                        <div class="col-md-12">
                                            <label class="mb-0 mt-1" for="distributor-address">Address</Address></label>
                                            <textarea name="distributor-address" id="distributor-address"
                                                class="form-control" cols="30" rows="3" maxlength="255"
                                                required></textarea>
                                        </div>

                                        <div class="col-md-12">
                                            <label class="mb-0 mt-1" for="distributor-dsc">Description</Address></label>
                                            <textarea name="distributor-dsc" id="distributor-dsc" class="form-control"
                                                cols="30" rows="3" maxlength="355"></textarea>
                                        </div>



                                        <div class="d-grid gap-2 d-md-flex justify-content-md-end mt-3 me-md-2">
                                            <button class="btn btn-primary me-md-2" name="add-distributor"
                                                type="submit">Add Distributor</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                        <!-- /end Add Distributor  -->

                    </div>

                </div>
                <!-- /.container-fluid -->

            </div>
            <!-- End of Main Content -->

            <!-- Footer -->
            <?php include_once PORTAL_COMPONENT.'footer-text.php'; ?>
            <!-- End of Footer -->

        </div>
        <!-- End of Content Wrapper -->

    </div>
    <!-- End of Page Wrapper -->

    <!-- Manufacturer View and Edit Modal -->
    <div class="modal fade" id="distributorModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">View and Edit Distributor Details</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body distributorModal">
                    <!-- Details Appeare Here by Ajax  -->
                </div>
            </div>
        </div>
    </div>
    <!--/end Manufacturer View and Edit Modal -->

    <!-- Logout Modal-->
    <?php require_once '_config/logoutModal.php'; ?>
    <!-- End Logout Modal-->


    <!-- Scroll to Top Button-->
    <a class="scroll-to-top rounded" href="#page-top">
        <i class="fas fa-angle-up"></i>
    </a>

    <!-- Bootstrap core JavaScript-->
    <script src="../assets/jquery/jquery.min.js"></script>
    <script src="../js/bootstrap-js-4/bootstrap.bundle.min.js"></script>

    
    <!-- Core plugin JavaScript-->
    <script src="../assets/jquery-easing/jquery.easing.min.js"></script>

    <!-- Custom scripts for all pages-->
    <script src="js/sb-admin-2.min.js"></script>

    <!-- Sweet Alert Js  -->
    <script src="../js/sweetAlert.min.js"></script>

    <!-- Page level plugins -->
    <script src="../assets/datatables/jquery.dataTables.min.js"></script>
    <script src="../assets/datatables/dataTables.bootstrap4.min.js"></script>

    <!-- Page level custom scripts -->
    <script src="js/demo/datatables-demo.js"></script>

    <script>
    //View and Edit Manufacturer function
    distViewAndEdit = (distributorId) => {
        let ViewAndEdit = distributorId;
        let url = "ajax/distributor.View.ajax.php?Id=" + ViewAndEdit;
        $(".distributorModal").html(
            '<iframe width="99%" height="530px" frameborder="0" allowtransparency="true" src="' +
            url + '"></iframe>');
    } // end of viewAndEdit function



    //delete distributor
    $(document).ready(function() {
        $(document).on("click", "#delete-btn", function() {

            swal({
                title: "Are you sure?",
                text: "Want to Delete This Manufacturer?",
                icon: "warning",
                buttons: true,
                dangerMode: true,
            })
            .then((willDelete) => {
                if (willDelete) {

                distributorId = $(this).data("id");
                btn = this;

                $.ajax({
                    url: "ajax/distributor.Delete.ajax.php",
                    type: "POST",
                    data: {
                        id: distributorId
                    },
                    success: function(data) {
                        if (data == 1) {
                            $(btn).closest("tr").fadeOut()
                            swal("Deleted", "Manufacturer Has Been Deleted", "success");
                        } else {
                            $("#error-message").html("Deletion Field !!!").slideDown();
                            $("success-message").slideUp();
                        }
                    }
                });

                }
            return false;
            });

        })

    })
    </script>


</body>

</html>