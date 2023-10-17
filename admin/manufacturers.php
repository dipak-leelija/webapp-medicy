<?php
require_once dirname(__DIR__).'/config/constant.php';
require_once ADM_DIR.'_config/sessionCheck.php'; //check admin loggedin or not

require_once CLASS_DIR.'dbconnect.php';
require_once CLASS_DIR.'manufacturer.class.php';

$page = "manufacturer"; 

//Class Initilizing
// $Distributor = new Distributor();
$Manufacturer = new Manufacturer();


//alert for form data inserted or failed
if (isset($_GET['return'])) {
    if ($_GET['return'] == "true") {
        echo"<script>alert('Manufacturer Added!');</script>";
    }else{
        echo"<script>alert('Manufacturer Insertion Failed!');</script>";
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
                    <h1 class="h3 mb-4 text-gray-800">Manufacturers</h1>
                    <div class="row">
                        <div class="col-md-7">
                            <div class="card shadow mb-4">
                                <div class="card-body">
                                    <!-- Showing Unit Table -->
                                    <div class="table-responsive">
                                        <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                                            <thead>
                                                <tr>
                                                    <th>SL.</th>
                                                    <th>Name</th>
                                                    <th>Description</th>
                                                    <th>Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                                    $showManufacturer = $Manufacturer->showManufacturer();
                                                    foreach ($showManufacturer as $rowManufacturer) {
                                                        $manufacturerId          = $rowManufacturer['id'];
                                                        $manufacturerName        = $rowManufacturer['name'];
                                                        // $distributorId       = $rowManufacturer['distributor_id'];
                                                        $manufacturerDsc         = $rowManufacturer['dsc'];
                                                        

                                                    echo  '<tr>
                                                                <td>'.$manufacturerId.'</td>
                                                                <td>'.$manufacturerName.'</td>
                                                                <td>'.$manufacturerDsc.'</td>
                                                                <td>
                                                                    <a class="" data-toggle="modal" data-target="#manufacturerModal" onclick="manufViewAndEdit('.$manufacturerId.')"><i class="fas fa-edit"></i></a>

                                                                    <a class="ms-2" id="delete-btn" data-id='.$manufacturerId.' onclick="customDel('.$manufacturerId.',this.id)"><i class="far fa-trash-alt"></i></a>
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

                        <div class="col-md-5">
                            <div class="card shadow mb-4">
                                <div class="card-body">
                                    <form method="post" action="_config/form-submission/add-manufacturer.php">

                                        <div class="col-md-12">
                                            <label class="mb-0" for="manufacturer-name">Manufacturer Name</Address>
                                            </label>
                                            <input class="form-control" id="manufacturer-name" name="manufacturer-name"
                                                placeholder="Manufacturer Name" required>
                                        </div>

                                        <div class="col-md-12">
                                            <label class="mb-0" for="manufacturer-name">Manufacturer Mark</Address>
                                            </label>
                                            <input class="form-control" id="manufacturer-short-name" name="manufacturer-short-name"
                                                placeholder="Manufacturer Mark" required>
                                        </div>

                                        <div class="col-md-12 mt-2">
                                            <label class="mb-0" for="manufacturer-dsc">Description</Address></label>
                                            <textarea name="manufacturer-dsc" id="manufacturer-dsc" class="form-control"
                                                cols="30" rows="3" maxlength="400"></textarea>
                                        </div>

                                        <div class="d-grid gap-2 d-md-flex justify-content-md-end mt-3 me-md-2">
                                            <button class="btn btn-primary me-md-2" name="add-manufacturer"
                                                type="submit">Add Manufacturer</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
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
    <div class="modal fade" id="manufacturerModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">View and Edit Manufacturer Details</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true" onclick="relode()">&times;</span>
                    </button>
                </div>
                <div class="modal-body manufacturerModal">
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

    <!-- Sweet Alert Js  -->
    <script src="../js/sweetAlert.min.js"></script>
    

    <!-- Core plugin JavaScript-->
    <script src="../assets/jquery-easing/jquery.easing.min.js"></script>

    <!-- Custom scripts for all pages-->
    <script src="js/sb-admin-2.min.js"></script>


    <!-- Page level plugins -->
    <script src="../assets/datatables/jquery.dataTables.min.js"></script>
    <script src="../assets/datatables/dataTables.bootstrap4.min.js"></script>

    <!-- Page level custom scripts -->
    <script src="js/demo/datatables-demo.js"></script>

    <script>
    //View and Edit Manufacturer function
    const manufViewAndEdit = (manufacturerId) => {
        let ViewAndEdit = manufacturerId;
        let url = "ajax/manufacturer.View.ajax.php?Id=" + ViewAndEdit;
        $(".manufacturerModal").html(
            '<iframe width="99%" height="330px" frameborder="0" allowtransparency="true" src="' +
            url + '"></iframe>');
    } // end of viewAndEdit function
                    // <?php 
                    // $tp =  gettype("manufacturerId");
                    // echo $tp;
                    // ?>

    //delete manufacturer
    const customDel = (id)=>{
        // alert(id);
            
            swal({
                title: "Are you sure?",
                text: "Want to Delete This Manufacturer?",
                icon: "warning",
                buttons: true,
                dangerMode: true,
            })
            .then((willDelete) => {
                if (willDelete) {
                
                $.ajax({
                    url: "ajax/manufacturer.Delete.ajax.php",
                    type: "POST",
                    data: {
                        id: id
                    },
                    success: function(response) {
                        // alert(response);
                        // alert(id);
                        if (response.includes('1')) {
                            $(btn).closest("tr").fadeOut()
                            swal("Deleted", "Manufacturer Has Been Deleted", "success");
                        } else {
                            swal("Delete Not Possible", "Manufacturer can't be deleted as its product is in stock", "warning");
                            $("#error-message").html("Deletion Field !!!").slideDown();
                            $("success-message").slideUp();
                        }
                    }
                });

                }
            return false;
            });
    }
            
    

    //========edit modal on close parent location reload==============

    function relode(){
        parent.location.reload();
    }
    
    </script>


</body>

</html>