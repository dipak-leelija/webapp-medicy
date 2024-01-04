<?php
require_once dirname(__DIR__) . '/config/constant.php';
require_once CLASS_DIR . 'dbconnect.php';
require_once ROOT_DIR . '_config/sessionCheck.php';

require_once CLASS_DIR . 'plan.class.php';
require_once CLASS_DIR . 'utility.class.php';
require_once CLASS_DIR . 'hospital.class.php';
require_once CLASS_DIR . 'encrypt.inc.php';


$Plan       = new Plan;
$Utility    = new Utility;
$HealthCare = new HealthCare;


$planId = $_POST['plan'];


######################################################################################################################
$currentPage    = $Utility->setCurrentPageSession();
$clinicInfo     = json_decode($HealthCare->showHealthCare($adminId));
if ($clinicInfo->status == 1) {
    // print_r($clinicInfo);
    $clinic = $clinicInfo->data;
    $clinicCity     = $clinic->city;
    $clinicState    = $clinic->health_care_state;
    $clinicPIN      = $clinic->pin;

}

$planResponse = json_decode($Plan->getPlan($planId));
// print_r($planResponse);
if ($planResponse->status == 1) {
    $plan           = $planResponse->data;
    $planName       = $plan->name;
    $planPrice      = $plan->price;
    $planDuration   = $plan->duration;
}

?>

<!DOCTYPE HTML>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="shortcut icon" href="<?= FAVCON_PATH ?>" type="image/png" />
    <link rel="apple-touch-icon" href="<?= FAVCON_PATH ?>" />
    <title><?= $planName ?> Plan - <?= SITE_NAME; ?></title>
    <meta name="description" content="" />
    <meta name="keywords" content="" />
    <link href="https://fonts.googleapis.com/css?family=Nunito+Sans:400,400i,700,900&display=swap" rel="stylesheet">
    <!-- Plugins Files -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
</head>

<body>
    <div>
        <section class="order-summary-section pb-4 px-3 px-md-0">
            <div class="container">

                <form id="paymentForm" name="paymentForm" action="" method="POST">
                    <div class="row pt-4 px-4 mt-4">
                        <h4
                            class="text-light bg-secondary rounded w-100 py-2 border-start border-4 border-primary px-4 mb-4">
                            Billing Summary</h4>

                        <div class="col-lg-7 px-4">
                            <div class="row">
                                <div class="col-sm-6 mb-3">
                                    <div class="form-group">
                                    
                                    <input type="hidden" name="planid" value="<?= url_enc($planId) ?>">
                                        <input type="text" minlength="4" class="form-control shadow-none" id="firstname"
                                            name="firstname" value="<?= $userFname; ?>" placeholder="First Name" required>
                                    </div>
                                </div>

                                <div class="col-sm-6 mb-3">
                                    <div class="form-group">
                                        <input type="text" minlength="4" class="form-control shadow-none" id="lastName"
                                            name="lastName" value="<?= $adminLname; ?>" placeholder="Last Name" required>
                                    </div>
                                </div>

                                <div class="col-sm-6 mb-3">
                                    <div class="form-group">
                                        <input type="email" class="form-control shadow-none" id="email" name="email"
                                            value="<?= $userEmail; ?>" placeholder="Email Address" disabled required>
                                    </div>
                                </div>
                                <div class="col-sm-6 mb-3">
                                    <div class="form-group">
                                        <input type="text" onkeypress="return event.charCode >= 48 && event.charCode <= 57" minlength="10" pattern="[0-9]+" maxlength="10" class="form-control shadow-none" id="mob-no" name="mob-no" value="<?= $adminContact; ?>" placeholder="Contact No" required>
                                    </div>
                                </div>
                                <div class="col-sm-6 mb-3">
                                    <div class="form-group">
                                        <input class="form-control shadow-none" name="city" id="city" value="<?= $clinicCity; ?>" placeholder="City" required>
                                    </div>
                                </div>

                                <div class="col-sm-6 mb-3">
                                    <div class="form-group">
                                        <input class="form-control shadow-none" name="state" id="state" value="<?= $clinicState; ?>" required />
                                    </div>
                                </div>

                                <div class="col-sm-6 mb-3">
                                    <div class="form-group">
                                        <input class="form-control shadow-none" name="country" id="country" value="India" required>
                                    </div>
                                </div>

                                <div class="col-sm-6 mb-3">
                                    <div class="form-group">
                                        <input type="text"
                                            onkeypress="return event.charCode >= 48 && event.charCode <= 57"
                                            minlength="6" pattern="[0-9]+" maxlength="6"
                                            class="form-control shadow-none" id="pin-code" name="pin-code"
                                            value="<?= $clinicPIN;  ?>" required>
                                    </div>
                                </div>


                                <div class="col-sm-12 mb-3">
                                    <small class="fw-normal">
                                        Please ensure that you provide accurate and complete information to avoid any delays or issues with your order. Once you have reviewed and submitted your details you can place order.
                                    </small>
                                    <!-- <div class="d-flex justify-content-start p-4 ps-0">
                                        <button type="button" class="btn btn-secondary d-none d-lg-block"
                                            onclick="goback()">
                                            Previous Page
                                        </button>
                                    </div> -->

                                </div>
                            </div>
                        </div>

                        <div class="col-lg-5 order-details-right d-flex px-4">
                            <div class="d-flex flex-column justify-content-between w-100">

                                <div class="invoice-items" id="preview">
                                    <div class="cart-contents">
                                        <div class="mb-4 d-flex justify-content-between">
                                            <div>
                                                <div class="h2 text-start fw-bold" style="font-family: 'Nunito Sans', 'Helvetica Neue', sans-serif;"><?= $planName; ?></div>
                                                <div class="text-500 text-muted text-start">Duration</div>
                                            </div>
                                            <div>
                                                <div class="text-end text-500"><?= CURRENCY . $planPrice; ?></div>
                                                <div><?= $planDuration ?></div>
                                            </div>

                                        </div>
                                    </div>
                                </div>

                                <div class="form-group text-center total_cart">

                                    <hr>
                                    <!-- Show total and payment terms -->
                                    <!-- <div class="mb-1 d-flex justify-content-between">
                                        <div class="text-start">
                                            <p>Gross Total</p>
                                        </div>
                                        <div class="text-end">
                                            <p><?= CURRENCY . $planPrice; ?></p>
                                        </div>
                                    </div> -->

                                    <!-- <div class="mb-1 d-flex justify-content-between">
                                        <div class="text-start">
                                            Discount
                                        </div>
                                        <div class="text-end">10%</div>
                                    </div> -->

                                    <!-- <div class="mb-4 d-flex justify-content-between">
                                        <div class="text-start">
                                            <h2 class="text-500">Net Pay</h2>
                                        </div>
                                        <div class="text-end">
                                            <h2 class="text-500"><?= CURRENCY . $planPrice; ?></h2>
                                        </div>
                                    </div> -->

                                    <!-- <hr> -->

                                    <button type="button" id="continue-btn" class="btn btn-primary w-100">
                                        Continue
                                    </button>
                                    
                                    <!-- <button type="button" class="btn btn-secondary w-100 mt-2 "> -->
                                        <a href="#" class="text-info" onclick="goback()">
                                            Previous Page
                                        </a>
                                    <!-- </button> -->
                                </div>

                            </div>
                        </div>
                    </div>
                </form>
            </div>

        </section>
    </div>
    <!-- /Footer -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous">
    </script>


    <script>
    let continueBtn = document.getElementById('continue-btn');

    continueBtn.addEventListener("click", function() {
        document.getElementById("paymentForm").action = "<?= URL?>premium/success.php";
        if (checkAddress("paymentForm") != false) {
            document.getElementById("paymentForm").submit();
        }
    });


    const checkAddress = (formName) => {
            var elements = document.forms[formName].elements;
            for (i = 0; i < elements
                .length; i++) {
                    console.log(elements[i]);
                    let type=elements[i].type;
                    if (type != 'button' && type != 'submit') {
                        if (elements[i].value=='' ) {
                            alert('Please Fill The Complete Biling Address!');
                            return false;
                        }
                    }
                }
            } 
    </script>
</body>

</html>