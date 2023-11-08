<?php 
require_once dirname(dirname(__DIR__)).'/config/constant.php';
require_once ADM_DIR.'_config/sessionCheck.php'; //check admin loggedin or not

require_once CLASS_DIR.'dbconnect.php';
require_once CLASS_DIR.'manufacturer.class.php';
$Manufacturer = new Manufacturer();

$manufacturerId = $_GET['Id'];

$showManufacturer = $Manufacturer->showManufacturerById($manufacturerId);

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
        foreach ($showManufacturer as $rowManufacturer) {
            $manufacturerName = $rowManufacturer['name'];
            $shortName = $rowManufacturer['short_name'];
            $manufacturerDsc  = $rowManufacturer['dsc'];
        }
    ?>

    <form>
        <input type="hidden" id="manufacturerId" name="" value="<?php echo $manufacturerId;?>">
        <!-- <div class="form-group"> -->
            <label for="manufacturer" class="form-label mb-0">Manufacturer Name:</label>
            <input type="text" class="form-control" id="manufacturer" value="<?php echo $manufacturerName; ?>">
        <!-- </div> -->

            <label for="manufacturer" class="form-label mb-0">Manufacturer Mark:</label>
            <input type="text" class="form-control" id="manufShortName" value="<?php echo $shortName; ?>">

        <!-- <div class="form-group"> -->
            <label for="description" class="form-label mb-0 mt-2">Description:</label>
            <textarea class="form-control" id="description" rows="4"><?php echo $manufacturerDsc; ?></textarea>
        <!-- </div> -->


        <div class="mt-2 reportUpdate" id="reportUpdate">
            <!-- Ajax Update Reporet Goes Here -->
        </div>

        <div class="mt-2 d-flex justify-content-end">
            <button type="button" class="btn btn-sm btn-primary" onclick="editManufacturer()">Update</button>
        </div>
        
    </form>

    


    <script src="../js/ajax.custom-lib.js"></script>

    <!-- Bootstrap core JavaScript-->
    <script src="../vendor/jquery/jquery.min.js"></script>
    <!-- <script src="../vendor/bootstrap/js/bootstrap.bundle.min.js"></script> -->

    <!-- Bootstrap Js -->
    <script src="../../js/bootstrap-js-5/bootstrap.js"></script>
    <script src="../../js/bootstrap-js-5/bootstrap.min.js"></script>

    <script>
    function editManufacturer() {
        let manufacturerId  = document.getElementById("manufacturerId").value;
        let manufacturer    = document.getElementById("manufacturer").value;
        let manufShortName = document.getElementById("manufShortName").value;
        let description     = document.getElementById("description").value;

        let url = `manufacturer.Edit.ajax.php?id=${manufacturerId}&name=${manufacturer}&sname=${manufShortName}&dsc=${description}`;
        
        request.open('GET', url, true);
 
        request.onreadystatechange = getEditUpdates;

        request.send(null);
    }

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
    <!-- Core plugin JavaScript-->
    <!-- <script src="../vendor/jquery-easing/jquery.easing.min.js"></script> -->

    <!-- Custom scripts for all pages-->
    <!-- <script src="../js/sb-admin-2.min.js"></script> -->


    

</body>

</html>