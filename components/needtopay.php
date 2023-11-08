<div class="mb-4">
    <div class="card border-left-info py-2 pending_border animated--grow-in">
        <div class="card-body">
            <a class="text-decoration-none" href="#">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                            Needs to PAY</div>
                        <div class="row">
                            <div class="col">
                                <img src="<?php echo ASSETS_PATH ?>img/need-to-pay.svg" alt="..." style="width: 70% !important;">
                            </div>
                            <div class="col d-flex align-items-center">
                                <div>

                                    <div class="row d-flex align-items-center">
                                        <div class="h5 mb-0 font-weight-bold text-gray-800">
                                            <?php 
                                                                        $pays = $StockIn->needsToPay();
                                                                        $pay = 0;
                                                                        foreach($pays as $data){
                                                                            $pay += $data['amount'];
                                                                        }
                                                                        echo '₹'.$pay.'  <i class="text-danger fas fa-arrow-up"></i> ';
                                                                        ?>
                                        </div>

                                        <p class="mt-1 mb-0 pb-0"><small
                                                class="text-danger mt-1 mb-0 pb-0"><?php  echo ' '.count($pays).' Bills';?></small>
                                        </p>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </a>
        </div>
        <div class="card-footer">
            <small class="text-muted">Try to pay the due as soon as posibble</small>
        </div>
    </div>
</div>