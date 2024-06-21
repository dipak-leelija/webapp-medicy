<?php
require_once dirname(__DIR__) . '/config/constant.php';
require_once ROOT_DIR . '_config/sessionCheck.php'; //check admin loggedin or not
require_once CLASS_DIR . 'dbconnect.php';
require_once CLASS_DIR . 'subscription.class.php';
require_once CLASS_DIR . 'plan.class.php';
require_once CLASS_DIR . 'utility.class.php';

$Plan           = new Plan;
$Subscription   = new Subscription;
$Utility        = new Utility;

if (isset($_SESSION['PURCHASEPLANID'])) {
    unset($_SESSION['PURCHASEPLANID']);
}

$SubsDetails = $Subscription->getSubscription($adminId);
$getPlans   = json_decode($Plan->allPlans());
if ($getPlans->status) {
    $allPlans = $getPlans->data;
}

$planFeature = json_decode($Plan->planFeatures());
if ($planFeature->status) {
    $features = $planFeature->data;
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
        rel="stylesheet"/>

    <!-- Custom styles for this template-->
    <link href="<?= CSS_PATH ?>sb-admin-2.min.css" rel="stylesheet">

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

    .plan-label:hover {
        background-color: #044c9d;
        color: #fff;
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

    .cardText {
        font-size: 18px;
        font-family: "Plus Jakarta Sans", sans-serif;
    }
    </style>

</head>

<body class="bg-gradient-primary">

    <div class="py-5 mx-3 px-3 mx-md-2 px-md-2 mx-lg-3 px-lg-3 mx-xl-5 px-xl-5">
        <div class="text-center">
            <h1 class=" font-weight-bold text-white mb-2">Choose a Plan</h1>
        </div>
        <form class="user" action="<?= URL ?>cheakout/" method="post">

            <div class="row mx-1 px-1 mx-md-2 px-md-2 mx-lg-4 px-lg-4 mx-xl-5 px-xl-5">
                <?php foreach ($allPlans as $eachPlans) { ?>
                <div class="col-sm-6 col-md-6 col-lg-4">
                    <div class="card my-4">
                        <div class="card-body">
                            <h5 class="card-titleColor text-center">
                                <p class="h4 font-weight-bold mb-4"><?= $eachPlans->name ?></p>
                                <p>
                                    <small>&#x20b9;</small>
                                    <strong class="h3 font-weight-bold dark-primary"><?= $eachPlans->price ?></strong>/
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
                                <?php foreach ($features as $feature) : ?>
                                <?php if ($feature->plan_id == $eachPlans->id) : ?>
                                <div class="d-flex justify-content-between">
                                    <p class="card-text cardText">
                                        <?= htmlspecialchars($feature->features, ENT_QUOTES, 'UTF-8') ?></p>
                                    <?php if ($feature->status == 1) : ?>
                                    <i class="fas fa-check-circle" style="color: #63E6BE;"></i>
                                    <?php else : ?>
                                    <i class="far fa-times-circle" style="color: #ff0000;"></i>
                                    <?php endif; ?>
                                </div>
                                <?php endif; ?>
                                <?php endforeach; ?>
                            </div>
                            <div class="text-center">

                                <input type="submit" id="plan-<?= $eachPlans->id ?>" class="plan-input" name="plan"
                                    value="<?= $eachPlans->id ?>">
                                <label for="plan-<?= $eachPlans->id ?>" class="plan-label mt-2">
                                    Select Plan
                                </label>

                            </div>
                        </div>
                    </div>
                </div>
                <?php } ?>

            </div>
        </form>
    </div>

    <!-- Bootstrap core JavaScript-->
    <script src="<?= PLUGIN_PATH ?>jquery/jquery.min.js"></script>
    <script src="<?= JS_PATH ?>bootstrap-js-4/bootstrap.bundle.min.js"></script>

</body>

</html>