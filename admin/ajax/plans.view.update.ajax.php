<?php
require_once realpath(dirname(dirname(__DIR__)) . '/config/constant.php');

require_once CLASS_DIR . 'dbconnect.php';
require_once CLASS_DIR . 'plan.class.php';
$Plan = new Plan();

$planId = $_GET['Id'];


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $planName = $_POST['plan-name'];
    $duration = $_POST['plan-duration'];
    $price    = $_POST['plan-price'];
    $status   = $_POST['plan-status'];

    // print_r($_POST['features']);

    $response = $Plan->updatePlan($planId, $planName, $price, $duration, $status);
    $response = json_decode($response);
    if ($response->status == 1) {
        $deleteRes = json_decode($Plan->deletePlanFeatures($planId));
        if ($deleteRes->status == 1) {
            foreach ($_POST['features'] as $eachFeature) {
                $finalResponse = $Plan->addPlanFeature($planId, $eachFeature, 1);
                print_r($finalResponse);
            }
        }
    }
}




$plans = $Plan->getPlan($planId);
$plans = json_decode($plans);
if (isset($plans->status) && $plans->status == 1) {
    if (isset($plans->data) && is_object($plans->data)) {
        $planId     = $plans->data->id;
        $planName   = $plans->data->name;
        $duration   = $plans->data->duration;
        $price      = $plans->data->price;
        $status     = $plans->data->status;

        if ($plans->data->features->status == 1) {
            $featureArr = $plans->data->features->data;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <!-- Custom fonts for this template-->
    <link href="<?= PLUGIN_PATH ?>fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">

    <!-- Custom styles for this template-->
    <link rel="stylesheet" href="<?= CSS_PATH ?>bootstrap/bootstrap.css">

</head>

<body class="mx-2">
    <form action="<?= CURRENT_URL ?>" method="POST">
        <div class="row mx-0">
            <div class="col-12 py-1">
                <input type="text" class="form-control" name="plan-name" value="<?= $planName; ?>">
            </div>
            <div class="col-12 py-1">
                <input type="text" class="form-control" name="plan-duration" value="<?= $duration; ?>">
            </div>
            <div class="col-6 py-1">
                <input type="number" class="form-control" name="plan-price" value="<?= $price; ?>">
            </div>
            <div class="col-6 py-1">
                <div class="form-group">
                    <select class="form-control" name="plan-status">
                        <option value="0" <?= $status == 0 ? 'selected' : ''; ?>>Deactive</option>
                        <option value="1" <?= $status == 1 ? 'selected' : ''; ?>>Active</option>
                    </select>
                </div>
            </div>

            <div id="features" class="col-12 py-1">
                <?php
                if (!empty($featureArr)) {
                    foreach ($featureArr as $eachFeature) {
                ?>
                        <div class="form-group">
                            <div class="d-flex my-2">
                                <input type="text" class="form-control form-control-sm" name="features[]" value="<?= $eachFeature->feature; ?>">
                                <button class="btn btn-sm btn-danger remove-feature rounded-right">
                                    <i class="far fa-times-circle"></i>
                                </button>
                            </div>
                        </div>
                <?php
                    }
                }
                ?>
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
            <button type="submit" class="btn btn-sm btn-primary">Update</button>
        </div>

    </form>




    <script src="<?= JS_PATH ?>ajax.custom-lib.js"></script>

    <!-- Bootstrap core JavaScript-->
    <script src="<?= PLUGIN_PATH ?>jquery/jquery.min.js"></script>

    <!-- Bootstrap Js -->
    <script src="<?= JS_PATH ?>bootstrap-js-5/bootstrap.js"></script>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            document.getElementById('addFeature').addEventListener('click', function() {
                var featureDiv = document.createElement('div');
                featureDiv.className = 'form-group';
                featureDiv.innerHTML = `<div class="d-flex my-2">
                    <input type="text" class="form-control form-control-sm" name="features[]" placeholder="Enter feature">
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
    </script>

</body>

</html>