<?php

// echo $_GET['currentStockId'];


ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);

require_once '../../php_control/patients.class.php';
require_once '../../php_control/idsgeneration.class.php';


//Classes Initilizing
$Patients        = new Patients();
$IdGeneration    = new IdGeneration();


if (isset($_GET['currentStockId'])) {
    $_GET['currentStockId'];
}


?>

<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="../../css/bootstrap 5/bootstrap.css">
    <title>Enter Patient Details</title>

    <link href="../vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">

    <link
        href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
        rel="stylesheet">
</head>

<body>
    <div class="container-fluid">
        <!-- <div class="row mt-2">
            <div class="col-4">
                <img src="../img/lady.jpg" class="img-fluid rounded" alt="...">
                <div class="row justify-content-evenly mt-1">
                    <div class="col-3 min-vw-25">
                        <img src="../img/lady.jpg" class="img-fluid rounded" alt="...">
                    </div>
                    <div class="col-3 min-vw-25">
                        <img src="../img/lady.jpg" class="img-fluid rounded" alt="...">
                    </div>
                    <div class="col-3 min-vw-25">
                        <img src="../img/lady.jpg" class="img-fluid rounded" alt="...">
                    </div>
                </div>
            </div>
            <div class="col-8 ps-2">
                <h3>Paracetamol</h3>
                <div class="mb-1 text-muted">Dr. Reddys Pvt Ltd.</div>
                <div class="row p-4">
                    <div class="col-6">
                        <strong>Distributor: </strong><span>10JD76</span><br>
                        <strong>Batch No: </strong><span>10JD76</span><br>
                        <strong>Exp Date: </strong><span>10/25</span><br>
                        <strong>In Stock: </strong><span>10</span><br>
                        <strong>Loose Stock: </strong><span>100</span><br>
                    </div>
                    <div class="col-6">
                        <strong>MRP: </strong><span>25</span><br>
                        <strong>PTR: </strong><span>20</span><br>
                        <strong>Discount: </strong><span>4.5%</span><br>
                        <strong>Base: </strong><span>19.5</span><br>
                        <strong>GST: </strong><span>100</span><br>

                    </div>
                </div>
            </div>
        </div>
        <p class="mb-auto">Lorem ipsum dolor sit amet consectetur adipisicing elit. Dolorem, consequatur aperiam, consectetur accusantium ab ratione rerum mollitia ipsam voluptatum assumenda inventore veniam quidem numquam?</p> -->
        
        <?php require_once '../partials/under-construction.php'; ?>
        
    </div>


</body>

</html>