<?php

// Cashfree configuration
// Production Credentials     

/**
 * 
 * 1. sandbox
 * 2. production
 * 
 *  */ 
$mode = "production";

define('MODE', $mode);

if (MODE === "production") {
    define('APPID',         '6898986b4a87b6c17e44798154898986');
    define('SECRECTKEY',    'cfsk_ma_prod_d0dcabd99b3cfcdc498faea97ccff060_7288151e');
    define('PAYMENTURL',    'https://api.cashfree.com/pg/orders');
    define('RESPONSEAPI',   'https://api.cashfree.com/pg/orders/');
}else {
    define('APPID',         'TEST101978339429a8c9ddfa1aed0eb633879101');
    define('SECRECTKEY',    'cfsk_ma_test_b383defee9a89462969452886a4840de_fa75a730');
    define('PAYMENTURL',    'https://sandbox.cashfree.com/pg/orders');
    define('RESPONSEAPI',   'https://sandbox.cashfree.com/pg/orders/');
}
    
    const RETURNURL         = URL.'cheakout/success.php';
    const NOTIFYURL         = URL.'cheakout/error.php';
    
?>