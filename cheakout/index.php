<?php
require_once dirname(__DIR__) . '/config/constant.php';
require_once CLASS_DIR . 'dbconnect.php';
require_once ROOT_DIR . '_config/sessionCheck.php';

require_once CLASS_DIR . 'plan.class.php';
require_once CLASS_DIR . 'utility.class.php';
require_once CLASS_DIR . 'hospital.class.php';
require_once CLASS_DIR . 'admin.class.php';
require_once CLASS_DIR . 'encrypt.inc.php';


$Plan       = new Plan;
$Admin      = new Admin;
$Utility    = new Utility;
$HealthCare = new HealthCare;

if (isset($_POST['plan']) ||  isset($_SESSION['PURCHASEPLANID'])) {

    if (isset($_POST['plan'])) {
        $planId = $_POST['plan'];
        $_SESSION['PURCHASEPLANID'] = $planId;
    }

    if (isset($_SESSION['PURCHASEPLANID'])) {
        $planId = $_SESSION['PURCHASEPLANID'];
        $_SESSION['PURCHASEPLANID'] = $planId;
    }
} else {
    header("Location: plans.php");
}

######################################################################################################################
$currentPage    = $Utility->setCurrentPageSession();
$clinicInfo     = json_decode($HealthCare->showHealthCare($ADMINID));
if ($clinicInfo->status == 1) {
    $clinic = $clinicInfo->data;
    $clinicCity     = $clinic->city;
    $clinicState    = $clinic->health_care_state;
    $clinicPIN      = $clinic->pin;
}

$adminDetails = json_decode($Admin->adminDetails($ADMINID));
if ($adminDetails) {
    $adminDetails = $adminDetails->data[0];
    $adminFname = $adminDetails->fname;
    $adminLname = $adminDetails->lname;
    $ADMINCONTACT = $adminDetails->mobile_no;
}

$planResponse = json_decode($Plan->getPlan($planId));
if ($planResponse->status == 1) {
    $plan           = $planResponse->data;
    $planName       = $plan->name;
    $planPrice      = $plan->price;
    $planDuration   = $plan->duration;
}


#######################################################################################################


?>

<!DOCTYPE HTML>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="shortcut icon" href="<?= FAVCON_PATH ?>" type="image/png" />
    <link rel="apple-touch-icon" href="<?= FAVCON_PATH ?>" />
    <title><?= $planName ?> Plan - <?= SITE_NAME; ?></title>
    <!-- Plugins Files -->
    <link rel="stylesheet" href="<?= PLUGIN_PATH ?>/bootstrap/5.3.3/dist/css/bootstrap.css" type="text/css" />

</head>

<body>
    <div>
        <section class="order-summary-section pb-4 px-3 px-md-0">
            <div class="container">
                <form id="formSubmit" class="needs-validation" name="formSubmit" action="payment.php" method="POST" novalidate>
                    <div class="row pt-4 px-4 mt-4">
                        <h4 class="text-light bg-secondary rounded w-100 py-2 border-start border-4 border-primary px-4 mb-4">
                            Billing Summary</h4>
                        <div class="col-lg-7 px-4">

                            <div class="row">
                                <div class="col-sm-6 mb-3">
                                    <div class="form-group">
                                        <input type="hidden" name="planid" value="<?= $planId ?>">
                                        <input type="text" minlength="4" class="form-control shadow-none" id="firstname" name="firstname" value="<?= $USERFNAME; ?>" placeholder="First Name" required onfocusout="checkData()">
                                    </div>
                                </div>

                                <div class="col-sm-6 mb-3">
                                    <div class="form-group">
                                        <input type="text" minlength="4" class="form-control shadow-none" id="lastName" name="lastName" value="<?= $USERLNAME; ?>" placeholder="Last Name" required>
                                    </div>
                                </div>

                                <div class="col-sm-6 mb-3">
                                    <div class="form-group">
                                        <input type="email" class="form-control shadow-none" id="email" name="email" value="<?= $USEREMAIL; ?>" placeholder="Email Address" readonly required>
                                    </div>
                                </div>
                                <div class="col-sm-6 mb-3">
                                    <div class="form-group">
                                        <input type="text" onkeypress="return event.charCode >= 48 && event.charCode <= 57" minlength="10" pattern="[0-9]+" maxlength="10" class="form-control shadow-none" id="mob-no" name="mob-no" value="<?= $ADMINCONTACT; ?>" placeholder="Contact No" required>
                                    </div>
                                </div>
                                <div class="col-sm-6 mb-3">
                                    <div class="form-group">
                                        <input class="form-control shadow-none" name="city" id="city" value="<?= $clinicCity; ?>" placeholder="City" required>
                                    </div>
                                </div>

                                <div class="col-sm-6 mb-3">
                                    <div class="form-group">
                                        <input class="form-control shadow-none" name="state" id="state" value="<?= $clinicState; ?>" placeholder="State" required />
                                    </div>
                                </div>

                                <div class="col-sm-6 mb-3">
                                    <div class="form-group">
                                        <input class="form-control shadow-none" name="country" id="country" value="India" required>
                                    </div>
                                </div>

                                <div class="col-sm-6 mb-3">
                                    <div class="form-group">
                                        <input type="number" onkeypress="return event.charCode >= 48 && event.charCode <= 57" minlength="6" pattern="[0-9]+" maxlength="6" class="form-control shadow-none" id="pin-code" name="pin-code" value="<?= $clinicPIN;  ?>" placeholder="PIN Code" required>
                                    </div>
                                </div>


                                <div class="col-sm-12 mb-3">
                                    <small class="fw-normal">
                                        Please ensure that you provide accurate and complete information to avoid any delays or issues with your order. Once you have reviewed and submitted your details you can place order.
                                    </small>
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
                                    <div class="mb-1 d-flex justify-content-between">
                                        <div class="text-start">
                                            <p>Gross Total</p>
                                        </div>
                                        <div class="text-end">
                                            <p><?= CURRENCY . $planPrice; ?></p>
                                        </div>
                                    </div>

                                    <div class="mb-1 d-flex justify-content-between">
                                        <div class="text-start">
                                            Discount
                                        </div>
                                        <div class="text-end">10%</div>
                                    </div>

                                    <div class="mb-4 d-flex justify-content-between">
                                        <div class="text-start">
                                            <h2 class="text-500">Net Pay</h2>
                                        </div>
                                        <div class="text-end">
                                            <h2 class="text-500"><?= CURRENCY . $planPrice; ?></h2>
                                            <input type="hidden" name="plan-price" value="<?= $planPrice ?>">
                                        </div>
                                    </div>

                                    <div class="mb-2">
                                        <div class="form-check text-start">
                                            <input class="form-check-input" type="checkbox" value="tncAccept" id="itemCheck" name="itemCheck" required>
                                            <label class="form-check-label" for="itemCheck">
                                                <span>Please Accept terms and conditions</span>
                                            </label>
                                            <div class="invalid-feedback">
                                                You must agree before submitting.
                                            </div>
                                        </div>

                                    </div>

                                    <button type="submit" id="payment-btn" name="payment-btn" class="btn btn-primary w-100">Continue</button>

                                    <a href="#" class="text-info" onclick="goback()">
                                        Previous Page
                                    </a>
                                </div>

                            </div>
                        </div>
                    </div>
                </form>
            </div>

        </section>
    </div>
    <script src="<?= PLUGIN_PATH ?>bootstrap/5.3.3/dist/js/bootstrap.min.js"></script>
    <script src="<?= JS_PATH ?>main.js"></script>
    <script src="<?= JS_PATH ?>cheakout.js"></script>



    <script>
        (() => {
            'use strict'

            // Fetch all the forms we want to apply custom Bootstrap validation styles to
            const forms = document.querySelectorAll('.needs-validation')

            // Loop over them and prevent submission
            Array.from(forms).forEach(form => {
                form.addEventListener('submit', event => {
                    if (!form.checkValidity()) {
                        event.preventDefault()
                        event.stopPropagation()
                    }

                    form.classList.add('was-validated')
                }, false)
            })
        })()

        const checkAddress = (formName) => {
            var elements = document.forms[formName].elements;
            for (i = 0; i < elements
                .length; i++) {
                console.log(elements[i]);
                let type = elements[i].type;
                if (type != 'button' && type != 'submit') {
                    if (elements[i].value == '') {
                        alert('Please Fill The Complete Biling Address!');
                        return false;
                    }
                }
            }
        }
    </script>
</body>

</html>