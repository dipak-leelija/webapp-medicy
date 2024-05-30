<?php

// Cashfree configuration
// Production Credentials     
const APPID             = '6898986b4a87b6c17e44798154898986';
const SECRECTKEY        = 'cfsk_ma_prod_d0dcabd99b3cfcdc498faea97ccff060_7288151e';
const PAYMENTURL        = 'https://api.cashfree.com/pg/orders';


// Test Credentials 
// const APPID             = 'TEST101978339429a8c9ddfa1aed0eb633879101';
// const SECRECTKEY        = 'cfsk_ma_test_b383defee9a89462969452886a4840de_fa75a730';
// const PAYMENTURL        = 'https://sandbox.cashfree.com/pg/orders';



// const RETURNURL         = 'http://localhost/medicy.in/cheakout/success.php';
// const NOTIFYURL         = 'http://localhost/medicy.in/cheakout/error.php';

const RETURNURL         = URL.'/cheakout/success.php';
const NOTIFYURL         = URL.'cheakout/error.php';

?>