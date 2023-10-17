<<<<<<< HEAD
<?php 
require_once dirname(dirname(__DIR__)).'/config/constant.php';
require_once ADM_DIR.'_config/sessionCheck.php'; //check admin loggedin or not

require_once CLASS_DIR.'dbconnect.php';
require_once CLASS_DIR.'measureOfUnit.class.php';
=======
<?php
require_once dirname(dirname(__DIR__)).'/config/constant.php';
require_once CLASS_DIR.'dbconnect.php';

require_once CLASS_DIR.'measureOfUnit.class.php';

>>>>>>> 6e5e8fa380a91b275b8dbed76bdf3e4a986b70aa
$MeasureOfUnits = new MeasureOfUnits();

$unitId = $_GET['Id'];


$showMeasureOfUnit = $MeasureOfUnits->showMeasureOfUnitsById($unitId);

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
        foreach ($showMeasureOfUnit as $rowMeasureOfUnits) {
            $unitId      = $rowMeasureOfUnits['id'];
            $unitSrtName = $rowMeasureOfUnits['short_name'];
            $unitFullName  = $rowMeasureOfUnits['full_name'];
        }
    ?>

    <form>
        <input type="hidden" id="unitId" value="<?php echo $unitId;?>">
        <!-- <div class="form-group"> -->
            <label for="unit-srt-name" class="form-label mb-0">Unit Short Name:</label>
            <input type="text" class="form-control" id="unit-srt-name" value="<?php echo $unitSrtName; ?>">
            <!-- </div> -->
    
            <label for="unit" class="form-label mb-0 mt-2">Unit Full Name:</label>
            <input type="text" class="form-control" id="unit" value="<?php echo $unitFullName; ?>">

        <div class="mt-2 reportUpdate" id="reportUpdate">
            <!-- Ajax Update Reporet Goes Here -->
        </div>

        <div class="mt-2 d-flex justify-content-end">
            <button type="button" class="btn btn-sm btn-primary" onclick="editUnit()">Update</button>
            <!-- <button type="button" class="btn btn-sm btn-primary" onclick="window.location.reload();">Load</button> -->
        </div>

    </form>

    <script>
    function editUnit() {
        let unitId    = document.getElementById("unitId").value;
        let srtName   = document.getElementById("unit-srt-name").value;
        let fullName  = document.getElementById("unit").value;

        let url = "unit.Edit.ajax.php?id=" + escape(unitId) + "&unit-srt-name=" + escape(srtName) + "&unit=" + escape(fullName);
        
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


</body>

</html>