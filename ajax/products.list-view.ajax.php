<?php
require_once dirname(__DIR__) . '/config/constant.php';
require_once ROOT_DIR . '_config/sessionCheck.php'; //check admin loggedin or not

require_once CLASS_DIR . 'dbconnect.php';
require_once CLASS_DIR . 'products.class.php';


$match = $_GET['match'];

$Products        = new Products();

if ($match == 'all') {
    $showProducts   = json_decode($Products->prodSearchByMatchForUser($match, $adminId));
} else {
    $showProducts   = json_decode($Products->prodSearchByMatchForUser($match, $adminId));
}


if ($showProducts->status) {
    $showProducts = $showProducts->data;
    
    foreach ($showProducts as $showProducts) {
        // print_r($showProducts);

        if(property_exists($showProducts, 'comp_1') || property_exists($showProducts, 'comp_2')){
            $comp1 = $showProducts->comp_1;
            $comp2 = $showProducts->comp_2;
        }else{
            $comp1 = '';
            $comp2 = '';
        }


        if(property_exists($showProducts, 'prod_req_status')){
            $prodReqStatus = $showProducts->prod_req_status;
        }else{
            $prodReqStatus = null;
        }

        // echo  $prodReqStatus;

        echo "<div class='p-1 border-bottom list'>
                <div class='' id='$showProducts->product_id' value1='$prodReqStatus' onclick='searchProduct(this)'>
                    $showProducts->name
                </div>

                <div>
                    <small>" . $comp1 . " , " . $comp2 . "</small>
                </div>
            </div>";
    }
} else {
    // echo "<p class='text-center font-weight-bold'>manufacturerNot Found!</p>";
    echo "<div class='p-1 border-bottom list'> No data found </div>";
}
