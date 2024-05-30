<?php
require_once dirname(__DIR__) . '/config/constant.php';

// exit;
// // Prevent page from being cached
// header("Cache-Control: no-cache, must-revalidate");
// header("Expires: Sat, 26 Jul 1997 05:00:00 GMT");

// // Force the browser to check for updates on every request
// header("Pragma: no-cache");

// // Set the Content-Type header to HTML
// header("Content-Type: text/html; charset=utf-8");

// // Use JavaScript to disable the back button
// echo '<script>
//     if (window.history.replaceState) {
//         window.history.replaceState(null, null, window.location.href);
//     }
// </script>';

require_once CLASS_DIR . 'dbconnect.php';
require_once ROOT_DIR . '_config/sessionCheck.php';

require_once CLASS_DIR . 'plan.class.php';
require_once CLASS_DIR . 'subscription.class.php';
require_once CLASS_DIR . 'utility.class.php';

require_once CLASS_DIR . 'encrypt.inc.php';

$Plan           = new Plan;
$Subscription   = new Subscription;

if (isset($_POST['orderAmount']) || isset($_POST['orderId']) || isset($_POST['paymentMode']) || isset($_POST['referenceId']) || isset($_POST['signature']) || isset($_POST['txMsg']) || isset($_POST['txStatus']) || isset($_POST['txTime'])) {

    $ORDERID        = $_POST['orderId'];
    $amount         = $_POST['orderAmount'];
    $payment_mode   = $_POST['paymentMode'];
    $referenceId    = $_POST['referenceId'];
    $signature      = $_POST['signature'];
    $txn_msg        = $_POST['txMsg'];
    $status         = $_POST['txStatus'];
    $txn_time       = $_POST['txTime'];

}

$planId         = $_SESSION['PURCHASEPLANID'];
$planResponse   = json_decode($Plan->getPlan($planId));
if($planResponse->status == 1){
    $plan = $planResponse->data;

    $planDuration   = $plan->duration;

}

$expDate    = getNextDate(TODAY, $planDuration);

$response = $Subscription->updateSubscription($ADMINID, $ORDERID, $referenceId, $txn_msg, $txn_time, $amount, $payment_mode, $status, NOW, $expDate);
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