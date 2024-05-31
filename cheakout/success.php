<?php
require_once dirname(__DIR__) . '/config/constant.php';

require_once CLASS_DIR . 'dbconnect.php';
require_once ROOT_DIR . '_config/sessionCheck.php';

require_once CLASS_DIR . 'plan.class.php';
require_once CLASS_DIR . 'subscription.class.php';
require_once CLASS_DIR . 'utility.class.php';

require_once CLASS_DIR . 'encrypt.inc.php';
require_once 'keys.php';

print_r($_SESSION);

$Plan           = new Plan;
$Subscription   = new Subscription;

if (isset($_GET['key'])) {
    
    $ORDERID = url_dec($_GET['key']);
    
    $curl = curl_init();
    $APPID          = APPID;
    $SECRECTKEY     = SECRECTKEY;

    curl_setopt_array($curl, array(
        CURLOPT_URL => RESPONSEAPI."$ORDERID/payments",
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'GET',
        CURLOPT_HTTPHEADER => array(
            "x-client-id: $APPID",
            "x-client-secret: $SECRECTKEY",
            'Accept: application/json',
            'Content-Type: application/json',
            'x-api-version: 2023-08-01'
        ),
    ));

    $response = curl_exec($curl);

    curl_close($curl);
    $response = json_decode($response);
    $response = $response[0];
    // echo '<pre>';
    // print_r($response);
    // echo '</pre>';

    // exit;

    $amount         = $response->order_amount;
    $payment_mode   = $response->payment_group;
    $gatewayId      = MODE === "production" ?  $response->cf_payment_id : $response->payment_gateway_details->gateway_order_id;
    $referenceId    = MODE === "production" ?  $response->bank_reference : $response->payment_gateway_details->gateway_payment_id;
    $txn_msg        = $response->payment_message;
    $status         = $response->payment_status;
    $txn_time       = $response->payment_time;

}else {
    header("Location: ".URL);
}

$planId         = $_SESSION['PURCHASEPLANID'];
$planResponse   = json_decode($Plan->getPlan($planId));
if ($planResponse->status == 1) {
    $plan = $planResponse->data;

    $planDuration   = $plan->duration;
}

$expDate    = getNextDate(TODAY, $planDuration);

$response = $Subscription->updateSubscription($ADMINID, $ORDERID, $referenceId, $gatewayId, $txn_msg, $txn_time, $amount, $payment_mode, $status, NOW, $expDate);
$response = json_decode($response);
$result = $response->status;
if ($result != 1) {
    header("Location: error.php");
    exit;
}
if ($status != 'SUCCESS') {
    echo "Something is Wrong!<br>";
    print_r($_POST);
}
?>
<html>

<head>
    <link href="https://fonts.googleapis.com/css?family=Nunito+Sans:400,400i,700,900&display=swap" rel="stylesheet">
    <!-- Plugins Files -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
</head>
<style>
    /* body {
    background: #EBF0F5;
} */

    h1 {
        color: #88B04B;
        font-family: "Nunito Sans", "Helvetica Neue", sans-serif;
        font-weight: 900;
        font-size: 40px;
        margin-bottom: 10px;
    }

    p {
        color: #404F5E;
        font-family: "Nunito Sans", "Helvetica Neue", sans-serif;
        font-size: 20px;
        margin: 0;
    }

    i {
        color: #9ABC66;
        font-size: 100px;
        line-height: 200px;
        margin-left: -15px;
    }

    .card {
        background: white;
        padding: 60px;
        /* border-radius: 4px; */
        box-shadow: 0 2px 3px #C8D0D8;
        display: inline-block;
        margin: 0 auto;
    }
</style>

<body>
    <div class="h-100 d-flex justify-content-center align-items-center text-center bg-light">
        <div class="card rounded shadow">
            <div class="rounded-circle" style="height:200px; width:200px; background: #F8FAF5; margin:0 auto;">
                <i class="checkmark">✓</i>
            </div>
            <h1>Success</h1>
            <p>We received your purchase request;<br /> we'll be in touch shortly!</p>
            <a href="<?= URL ?>" class="btn btn-sm btn-primary w-100 mt-4">Dashboard</a>
        </div>
    </div>
</body>

</html>