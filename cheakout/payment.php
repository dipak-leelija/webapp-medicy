<?php
require_once dirname(__DIR__) . '/config/constant.php';
require_once CLASS_DIR . 'dbconnect.php';
require_once ROOT_DIR . '_config/sessionCheck.php';

require_once CLASS_DIR . 'plan.class.php';
require_once CLASS_DIR . 'subscription.class.php';
require_once CLASS_DIR . 'utility.class.php';
require_once CLASS_DIR . 'hospital.class.php';
require_once CLASS_DIR . 'encrypt.inc.php';


$Plan           = new Plan;
$Subscription   = new Subscription;
$Utility        = new Utility;
$HealthCare     = new HealthCare;

if (isset($_POST['payment-btn'])) {
    $planid         = $_POST['planid'];
    $plan_price     = $_POST['plan-price'];
    $customerName   = $_POST['firstname'] . ' ' . $_POST['lastName'];
    $email          = $_POST['email'];
    $mob_no         = $_POST['mob-no'];
    $city           = $_POST['city'];
    $state          = $_POST['state'];
    $country        = $_POST['country'];
    $pin_code       = $_POST['pin-code'];

    $Subscription->createSubscription($ADMINID, $planid, NOW, NOW, 00, 0);
}

// Cashfree configuration    
// define('APPID', '6898986b4a87b6c17e44798154898986'); // Replace "TEST" AppId to PROD AppId
// define('SECRECTKEY', 'cfsk_ma_prod_d0dcabd99b3cfcdc498faea97ccff060_7288151e'); // Replace "TEST" Secret key to PROD Secret key
define('APPID', 'TEST101978339429a8c9ddfa1aed0eb633879101'); // Replace "TEST" AppId to PROD AppId
define('SECRECTKEY', 'cfsk_ma_test_b383defee9a89462969452886a4840de_fa75a730'); // Replace "TEST" Secret key to PROD Secret key

define('RETURNURL', 'http://localhost/medicy.in/cheakout/success.php');
define('NOTIFYURL', 'http://localhost/medicy.in/cheakout/error.php');
$mode = "TEST"; // Change to TEST for test server, PROD for production

$secretKey = SECRECTKEY; // Secret key
$orderId = "WC" . mt_rand(11111, 99999); // Create a unique order ID

$postData = array(
    "appId" => APPID,
    "orderId" => $orderId,
    "orderAmount" => $plan_price,
    "orderCurrency" => "INR",
    "customerName" => $customerName,
    "customerPhone" => $mob_no,
    "customerEmail" => $email,
    "returnUrl" => RETURNURL,
    "notifyUrl" => NOTIFYURL,
);

ksort($postData);
$signatureData = "";
foreach ($postData as $key => $value) {
    $signatureData .= $key . $value;
}

$signature = hash_hmac('sha256', $signatureData, $secretKey, true);
$signature = base64_encode($signature);

if ($mode == "PROD") {
    $url = "https://www.cashfree.com/checkout/post/submit";
} else {
    $url = "https://test.cashfree.com/billpay/checkout/post/submit";
}

?>


<form action="<?= $url; ?>" name="formSubmit" method="post">
    <p>Please wait.......</p>
    <input type="hidden" name="signature" value='<?= $signature; ?>' />
    <input type="hidden" name="appId" value='<?= APPID; ?>' />
    <input type="hidden" name="orderId" value='<?= $orderId; ?>' />
    <input type="hidden" name="orderCurrency" value='INR' />
    <input type="hidden" name="customerName" value='<?= $customerName ?>' />
    <input type="hidden" name="customerEmail" value='<?= $email ?>' />
    <input type="hidden" name="customerPhone" value='<?= $mob_no ?>' />
    <input type="hidden" name="orderAmount" value='<?= $plan_price ?>' />
    <input type="hidden" name="notifyUrl" value='<?= NOTIFYURL; ?>' />
    <input type="hidden" name="returnUrl" value='<?= RETURNURL; ?>' />
    <!-- <button type="submit">submit</button> -->
</form>
<script type="text/javascript">
    window.onload = function() {
        document.forms['formSubmit'].submit();
    };
</script>