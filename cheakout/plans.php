<?php
require_once dirname(__DIR__).'/config/constant.php';
require_once ROOT_DIR . '_config/sessionCheck.php'; //check admin loggedin or not
require_once CLASS_DIR . 'dbconnect.php';
require_once CLASS_DIR . 'subscription.class.php';
require_once CLASS_DIR . 'utility.class.php';


$Subscription   = new Subscription;
$Utility        = new Utility;

$SubsDetails = $Subscription->getSubscription($adminId);
$getPlans   = json_decode($Subscription->allPlans());
if($getPlans->status){
    $allPlans = $getPlans->data;
}

$planFeature = json_decode($Subscription->planFeatures());
    if($planFeature->status){
        $features = $planFeature->data;
        // print_r($features);
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

    <title>Choose a Plan - <?= SITE_NAME ?></title>

    <!-- Custom fonts for this template -->
    <link href="<?php echo PLUGIN_PATH ?>fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link
        href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
        rel="stylesheet">

    <!-- Custom styles for this template-->
    <link href="<?php echo PLUGIN_PATH ?>datatables/dataTables.bootstrap4.min.css" rel="stylesheet">
    <link href="<?= CSS_PATH ?>sb-admin-2.min.css" rel="stylesheet">
    <link href="<?= CSS_PATH ?>register.css" rel="stylesheet">

    <style>
    .plan-input {
        display: none;
    }
    .plan-label {
        display: block;
        padding: 6px;
        font-weight: bold;
        border: 1px solid #044c9d;
        color: #044c9d;
        border-radius: 10rem;
        text-align: center;
        cursor: pointer;
        transition: background-color 0.3s, color 0.3s;
    }
    .plan-input:checked+.plan-label {
        background-color: #044c9d;
        color: #fff;
    }
    .card-titleColor {
        color: #044c9d;
    }
    .card-titleColor small {
        color: #044c9d99;
    }
    .enabled-button{
        box-shadow: #ffff 0px 1px 10px;
    }
    </style>

</head>

<body class="bg-gradient-primary">

    <main>

        <!-- <div class="card o-hidden border-0 shadow-lg my-5"> -->
        <!-- <div class="card-body p-0"> -->
        <!-- Nested Row within Card Body -->
        <div class="py-5">
            <div class="text-center">
                <h1 class=" font-weight-bold text-white mb-2">Choose a Plan</h1>
            </div>
            <form class="user" action="index.php" method="post">

                <div class="row">

                    <?php foreach ($allPlans as $eachPlans) {?>
                    <div class="col-sm-6">
                        <div class="card my-4">
                            <div class="card-body">
                                <h5 class="card-titleColor text-center">
                                    <p class="h4 font-weight-bold mb-4"><?=$eachPlans->name?></p>
                                    <p>
                                        <small>&#x20b9;</small>
                                        <strong
                                            class="h3 font-weight-bold dark-primary"><?=$eachPlans->price?></strong>/
                                        <small>
                                            <?php
                                            // Extract numeric value
                                            preg_match('/(\d+)\s*(\w+)/', $eachPlans->duration, $matches);
                                            $number = $matches[1];
                                            $unit = $matches[2];
                                            // Remove number only if it's 1
                                            if ($number == 1) {
                                                echo $unit;
                                            } else {
                                                echo $eachPlans->duration;
                                            }
                                            ?>
                                        </small>
                                    </p>
                                </h5>
                                <div>
                                    <?php foreach ( $features as $feature ) {
                                        if ($feature->plan_id == $eachPlans->id) { ?>
                                    <div class="d-flex justify-content-between">
                                        <p class="card-text"><?php echo $feature->features;?></p>
                                        <?php if( $feature->status == 1 ){
                                        echo '<i class="fas fa-check-circle" style="color: #63E6BE;"></i>'
                                        ?> <?php } else { ?> <i class="far fa-times-circle"
                                            style="color: #ff0000;"></i> <?php } ?>

                                    </div>
                                    <?php } } ?>
                                </div>
                                <div class="text-center">
                                    <!-- <input id="plan-<?= $eachPlans->id?>" type="radio" name="plan"
                                        value="<?= $eachPlans->id?>" style='width:30px;height:30px;border-radius:4px'> -->

                                    <input id="plan-<?= $eachPlans->id?>" class="plan-input" type="radio" name="plan"
                                        value="<?= $eachPlans->id?>">
                                    <label for="plan-<?= $eachPlans->id?>" class="plan-label mt-2">
                                        Select Plan
                                    </label>

                                </div>
                            </div>
                        </div>
                    </div>

                    <?php } ?>

                </div>
                <div class="d-flex justify-content-center">
                    <button id='continue-button' class="btn btn-primary rounded-pill font-weight-bold w-75"
                        type="submit" disabled>Continue</button>
                </div>
            </form>
        </div>
        <!-- </div> -->
        <!-- </div> -->

    </main>

    <!-- Bootstrap core JavaScript-->
    <script src="<?= PLUGIN_PATH ?>jquery/jquery.min.js"></script>
    <script src="<?= JS_PATH ?>bootstrap-js-4/bootstrap.bundle.min.js"></script>

    <!-- Core plugin JavaScript-->
    <script src="<?= PLUGIN_PATH ?>jquery-easing/jquery.easing.min.js"></script>

    <!-- Custom scripts for all pages-->
    <script src="<?= JS_PATH ?>sb-admin-2.min.js"></script>

    <script>
    $(document).ready(function() {
        $('input[name="plan"]').change(function() {
            if ($(this).is(':checked')) {
                $('#continue-button').prop('disabled', false).addClass('enabled-button');
            } else {
                $('#continue-button').prop('disabled', true).removeClass('enabled-button');
            }
        });
    });
    </script>

</body>

</html>


<!-- <div class="each-plan">
                                <input id="plan-<?= $eachPlans->id?>" type="radio" name="plan" value="<?= $eachPlans->id?>">
                                <label for="plan-<?= $eachPlans->id?>">
                                    <div class="">
                                        <div class="" id="">
                                            <div class="">
                                                <p class=""><?=$eachPlans->name?></p>
                                                <p><strong><?=$eachPlans->price?></strong>/<?=$eachPlans->duration?></p>
                                            </div>
                                        </div>
                                    </div>
                                </label>
                            </div> -->