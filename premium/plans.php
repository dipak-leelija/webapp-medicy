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

    <!-- Custom styles for this template-->
    <link href="<?= CSS_PATH ?>sb-admin-2.min.css" rel="stylesheet">
    <link href="<?= CSS_PATH ?>register.css" rel="stylesheet">

    <style>
    .choose-plan {

        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(150px, 1fr));
        /* Each plan will take at least 200px and expand to fill the available space */
        gap: 10px;
        /* Optional gap between each plan */

        /* display: flex;
        align-items: center;
        padding: 0.375rem 0.75rem;
        margin-bottom: 0; */
        /* font-size: 1rem; */
        /* font-weight: 400; */
        /* line-height: 1.5; */
        /* color: #6e707e; */
        /* text-align: center; */
        /* white-space: nowrap; */
        /* background-color: #eaecf4; */
        /* border: 1px solid #d1d3e2; */
        /* border-radius: 0.35rem; */
    }
    </style>

</head>

<body class="bg-gradient-primary">

    <main>

        <div class="card o-hidden border-0 shadow-lg my-5">
            <div class="card-body p-0">
                <!-- Nested Row within Card Body -->
                <div class="p-5">
                    <div class="text-center">
                        <h1 class="h4 font-weight-bold text-gray-900 mb-4">Choose a Plan</h1>
                    </div>
                    <form class="user" action="cheakout.php" method="post">

                        <div class="choose-plan">

                            <?php foreach ($allPlans as $eachPlans) {?>

                            <div class="each-plan">
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
                            </div>

                            <?php } ?>

                        </div>

                        <button class="btn btn-primary btn-user btn-block" type="submit">Pay</button>
                    </form>
                </div>
            </div>
        </div>

    </main>

    <!-- Bootstrap core JavaScript-->
    <script src="<?= PLUGIN_PATH ?>jquery/jquery.min.js"></script>
    <script src="<?= JS_PATH ?>bootstrap-js-4/bootstrap.bundle.min.js"></script>

    <!-- Core plugin JavaScript-->
    <script src="<?= PLUGIN_PATH ?>jquery-easing/jquery.easing.min.js"></script>

    <!-- Custom scripts for all pages-->
    <script src="<?= JS_PATH ?>sb-admin-2.min.js"></script>

</body>

</html>