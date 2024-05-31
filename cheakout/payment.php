<?php
require_once dirname(__DIR__) . '/config/constant.php';
require_once CLASS_DIR . 'dbconnect.php';
require_once ROOT_DIR . '_config/sessionCheck.php';

require_once CLASS_DIR . 'plan.class.php';
require_once CLASS_DIR . 'subscription.class.php';
require_once CLASS_DIR . 'idsgeneration.class.php';
require_once CLASS_DIR . 'utility.class.php';
require_once CLASS_DIR . 'hospital.class.php';
require_once CLASS_DIR . 'encrypt.inc.php';

require_once 'keys.php';

$Plan           = new Plan;
$Subscription   = new Subscription;
$IdsGeneration  = new IdsGeneration;
$Utility        = new Utility;

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

    $ORDERID = $IdsGeneration->generateOrderId();

    $Subscription->createSubscription($ORDERID, $ADMINID, $planid, NOW, '', 00, 0);
}

// API credentials
$clientId = APPID;
$clientSecret = SECRECTKEY;
// API URL
$url = PAYMENTURL;

// Request headers
$headers = [
    'X-Client-Secret: ' . $clientSecret,
    'X-Client-Id: ' . $clientId,
    'x-api-version: 2023-08-01',
    'Content-Type: application/json',
    'Accept: application/json'
];

// Request data
$data = [
    "order_id" => $ORDERID,
    "order_amount" => $plan_price,
    "order_currency" => "INR",
    "customer_details" => [
        "customer_id" => $ADMINID,
        "customer_name" => $customerName,
        "customer_email" => $email,
        "customer_phone" => $mob_no
    ],
    "order_meta" => [
        "return_url" => RETURNURL . '?key=' . url_enc($ORDERID),
        "notify_url" => NOTIFYURL . '?key=' . url_enc($ORDERID)
    ]
];

// Initialize cURL session
$ch = curl_init($url);

// Set cURL options
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));

// Execute cURL request
$response = curl_exec($ch);

// Check for cURL errors
if ($response === false) {
    $error = curl_error($ch);
    curl_close($ch);
    die('cURL error: ' . $error);
}

// Close cURL session
curl_close($ch);

// Handle response
$responseData = json_decode($response, true);

?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Please wait..</title>
    <script src="https://sdk.cashfree.com/js/v3/cashfree.js"></script>
    <style>
        body,
        html {
            height: 100%;
            margin: 0;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .container {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100%;
        }

        .loading-circle {
            border: 4px solid #f3f3f3;
            border-top: 4px solid #3498db;
            border-radius: 50%;
            width: 50px;
            height: 50px;
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            0% {
                transform: rotate(0deg);
            }

            100% {
                transform: rotate(360deg);
            }
        }
    </style>
</head>

<body>
    <div>
        <div class="loading-circle"></div>
    </div>
    <button style="display: none;" id="renderBtn">Pay Now</button>
    <script>
        const cashfree = Cashfree({
            // "production" for Production
            // "sandbox" for Testing
            mode: "<?= MODE ?>",
        });
        document.getElementById("renderBtn").addEventListener("click", () => {
            let checkoutOptions = {
                paymentSessionId: "<?= $responseData['payment_session_id']; ?>",
                redirectTarget: "_self",
            };
            cashfree.checkout(checkoutOptions);
        });


        document.addEventListener("DOMContentLoaded", function(event) {
            // This function will be called when the DOM is fully loaded
            var button = document.getElementById("renderBtn");
            // Trigger the click event on the button
            button.click();
        });
    </script>
</body>

</html>


<!-- 
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cashfree Checkout Integration</title>
    <script src="https://sdk.cashfree.com/js/v3/cashfree.js"></script>
</head>

<body>
    <div class="row">
        <p>Click below to open the checkout page in popup</p>
        <button id="renderBtn">Pay Now</button>
    </div>
    <script>
        const cashfree = Cashfree({
            mode: "sandbox",
        });
        document.getElementById("renderBtn").addEventListener("click", () => {
            let checkoutOptions = {
                paymentSessionId: "<?php //echo $responseData['payment_session_id']; 
                                    ?>",
                redirectTarget: "_modal",
            };
            cashfree.checkout(checkoutOptions).then((result) => {
                console.log(result)
                if (result.error) {
                    // This will be true whenever user clicks on close icon inside the modal or any error happens during the payment
                    // console.log("User has closed the popup or there is some payment error, Check for Payment Status");
                    // console.log(result.error);
                }
                if (result.redirect) {
                    // This will be true when the payment redirection page couldnt be opened in the same window
                    // This is an exceptional case only when the page is opened inside an inAppBrowser
                    // In this case the customer will be redirected to return url once payment is completed
                    console.log("Payment will be redirected");
                }
                if (result.paymentDetails) {
                    // This will be called whenever the payment is completed irrespective of transaction status
                    console.log("Payment has been completed, Check for Payment Status");
                    // console.log(result.paymentDetails.paymentMessage);
                    console.log(result);

                }
            });
        });
    </script>
</body>

</html> -->