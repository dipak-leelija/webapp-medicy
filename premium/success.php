<?php
require_once dirname(__DIR__) . '/config/constant.php';
// Prevent page from being cached
header("Cache-Control: no-cache, must-revalidate");
header("Expires: Sat, 26 Jul 1997 05:00:00 GMT");

// Force the browser to check for updates on every request
header("Pragma: no-cache");

// Set the Content-Type header to HTML
header("Content-Type: text/html; charset=utf-8");

// Use JavaScript to disable the back button
echo '<script>
    if (window.history.replaceState) {
        window.history.replaceState(null, null, window.location.href);
    }
</script>';

require_once CLASS_DIR . 'dbconnect.php';
require_once ROOT_DIR . '_config/sessionCheck.php';

require_once CLASS_DIR . 'plan.class.php';
require_once CLASS_DIR . 'utility.class.php';
require_once CLASS_DIR . 'hospital.class.php';
require_once CLASS_DIR . 'subscription.class.php';
require_once CLASS_DIR . 'utility.class.php';

require_once CLASS_DIR . 'encrypt.inc.php';

$HealthCare     = new HealthCare;
$Plan           = new Plan;
$Subscription   = new Subscription;
$Utility        = new Utility;

$planId     = url_dec($_POST['planid']);

$planResponse = json_decode($Plan->getPlan($planId));
if($planResponse->status == 1){
    $plan = $planResponse->data;

    $planPrice      = $plan->price;
    $planDuration   = $plan->duration;

}

$startDate  = new DateTime(TODAY);
$startDate  = $startDate->format('Y-m-d');
$expDate    = getNextDate(TODAY, $planDuration);
$paidAmount = 00;

$response = json_decode($Subscription->updateSubscription($adminId, $planId, $startDate, $expDate, $paidAmount));
// $result = $response->status;
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

<body class="d-flex justify-content-center align-items-center text-center bg-light">
    <div class="card rounded shadow">
        <div class="rounded-circle" style="height:200px; width:200px; background: #F8FAF5; margin:0 auto;">
            <i class="checkmark">✓</i>
        </div>
        <h1>Success</h1>
        <p>We received your purchase request;<br /> we'll be in touch shortly!</p>
    </div>
</body>

</html>