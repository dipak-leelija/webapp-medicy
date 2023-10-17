<?php 
require_once dirname(dirname(__DIR__)).'/config/constant.php';
require_once ADM_DIR.'_config/sessionCheck.php'; //check admin loggedin or not

require_once CLASS_DIR.'dbconnect.php';
require_once CLASS_DIR.'distributor.class.php';


$distributorId = $_GET['Id'];

$Distributor = new Distributor();
$showDistributor = $Distributor->showDistributorById($distributorId);

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <!-- Custom fonts for this template-->
    <link href="../vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link
        href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
        rel="stylesheet">

    <!-- Custom styles for this template-->
    <!-- <link href="../css/sb-admin-2.min.css" rel="stylesheet"> -->
    <link rel="stylesheet" href="../../css/bootstrap 5/bootstrap.css">

</head>

<body class="mx-2">

    <?php
        foreach ($showDistributor as $rowDistributor) {
            $DistributorName     = $rowDistributor['name'];
            $DistributorAddress  = $rowDistributor['address'];
            $DistributorPIN      = $rowDistributor['area_pin_code'];
            $DistributorPhno     = $rowDistributor['phno'];
            $DistributorEmail    = $rowDistributor['email'];
            $DistributorDsc      = $rowDistributor['dsc'];

        }
    ?>

    <form >
        <input type="hidden" id="distributorId" value="<?php echo $distributorId;?>">
        <div class="form-group">
            <label for="distributor-name" class="form-label mb-0 mt-0">Distributor Name:</label>
            <input type="text" class="form-control" id="distributor-name" value="<?php echo $DistributorName; ?>">
        </div>

        <div class="form-group">
            <label for="distributor-phno" class="form-label mb-0">Distributor Contact:</label>
            <input type="text" class="form-control" id="distributor-phno" value="<?php echo $DistributorPhno; ?>">
        </div>

        <div class="form-group">
            <label for="distributor-email" class="form-label mb-0">Distributor Email:</label>
            <input type="text" class="form-control" id="distributor-email" value="<?php echo $DistributorEmail; ?>">
        </div>


        <div class="form-group">
            <label for="distributor-address" class="form-label mb-0 mt-2">Distributor Address:</label>
            <textarea class="form-control" id="distributor-address" rows="3"><?php echo $DistributorAddress; ?></textarea>
        </div>

        <div class="form-group">
            <label for="distributor-area-pin" class="form-label mb-0">Area PIN:</label>
            <input type="text" class="form-control" id="distributor-pin" value="<?php echo $DistributorPIN; ?>">
        </div>

        <div class="form-group">
            <label for="distributor-dsc" class="form-label mb-0 mt-2">Description:</label>
            <textarea class="form-control" id="distributor-dsc" rows="2"><?php echo $DistributorDsc; ?></textarea>
        </div>


        <div class="mt-2 reportUpdate" id="reportUpdate">
            <!-- Ajax Update Reporet Goes Here -->
        </div>

        <div class="mt-2 d-flex justify-content-end">
            <button type="button" class="btn btn-sm btn-primary" onclick="editDist();">Update</button>
        </div>

    </form>

    

    <script src="../js/ajax.custom-lib.js"></script>

    <!-- Bootstrap core JavaScript-->
    <script src="../vendor/jquery/jquery.min.js"></script>
    <script src="../vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

    <!-- Bootstrap Js -->
    <script src="../../js/bootstrap-js-5/bootstrap.js"></script>
    <script src="../../js/bootstrap-js-5/bootstrap.min.js"></script>


    <!-- Core plugin JavaScript-->
    <!-- <script src="../vendor/jquery-easing/jquery.easing.min.js"></script> -->

    <!-- Custom scripts for all pages-->
    <!-- <script src="../js/sb-admin-2.min.js"></script> -->
    <script>
    // function editDist() 
    function editDist(){
        // alert('Working');
        // let Id        = $("#distributorId").val();
        let Id      = document.getElementById("distributorId").value;
        let name      = document.getElementById("distributor-name").value;
        let phno      = document.getElementById("distributor-phno").value;
        let email     = document.getElementById("distributor-email").value;
        let address   = document.getElementById("distributor-address").value;
        let areaPin  = document.getElementById("distributor-pin").value;
        let dsc       = document.getElementById("distributor-dsc").value;


        let url = "distributor.Edit.ajax.php?id=" + escape(Id) + "&name=" + escape(name) + "&phno=" + escape(phno) + "&email=" + escape(email) + "&address=" + escape(address) + "&pin=" + escape(areaPin) + "&dsc=" + escape(dsc);
        
        request.open('GET', url, true);
 
        request.onreadystatechange = getEditUpdates;

        request.send(null);
    }//eof editDist

    function getEditUpdates() {
        if (request.readyState == 4) {
            if (request.status == 200) {
                var xmlResponse = request.responseText;
                document.getElementById('reportUpdate').innerHTML = xmlResponse;
            } else if (request.status == 404) {
                alert("Request page doesn't exist");
            } else if (request.status == 403) {
                alert("Request page doesn't exist");
            } else {
                alert("Error: Status Code is " + request.statusText);
            }
        }
    } //eof getEditUpdates

    </script>

</body>

</html>