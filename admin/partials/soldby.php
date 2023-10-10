<?php
require_once dirname(dirname(__DIR__)).'/config/constant.php'; 
require_once ADM_DIR.'_config/sessionCheck.php';
// require_once ROOT_DIR.'config/sessionCheck.php';

?>

<div class="card border-left-primary h-100 py-2 animated--grow-in">
    <div class="card-body pb-0">
        <div class="row no-gutters align-items-center">
            <div class="col mr-2">
                <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                    Sold By <?php 
                    if($username = 'ADMIN'){
                        echo 'ALL';
                    }else{
                        echo $username;
                    } ?>
                </div>
                <div class="h5 mb-0 font-weight-bold text-gray-800">
                <?php
                    if($username == 'ADMIN'){
                        
                    }else{

                    }

                     $sold = $StockOut->amountSoldBy($username);
                    // print_r($total);
                    $amount = 0;
                    $items = 0;
                    foreach ($sold as $data) {
                        $amount += $data['amount'];
                        $items += $data['items'];
                    }
                    echo '₹' . $amount;
                    ?>
                </div>
                <p class="mb-0 pb-0"><small class="mb-0 pb-0"><?php echo $items; ?>
                        Items</small></p>
            </div>
            <div class="col-auto">
                <i class="fas fa-user"></i>
            </div>
        </div>
    </div>
</div>