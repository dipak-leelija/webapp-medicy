<div class="mb-4">
    <div class="card border-left-warning py-2 pending_border animated--grow-in">
        <div class="card-body">
            <a class="text-decoration-none" href="#">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                            Needs to collect</div>
                        <div class="row">
                            <div class="col">
                                <img class="w-75" src="<?php echo ASSETS_PATH ?>img/needs-to-collect.svg" alt="...">
                            </div>
                            <div class="col d-flex align-items-center">
                                <div>

                                    <div class="row d-flex align-items-center">
                                        <div class="h5 mb-0 font-weight-bold text-gray-800">
                                            <?php 
                                                                        $credits = $StockOut->needsToCollect();
                                                                        // print_r($collect);
                                                                        $collect = 0;
                                                                        foreach($credits as $credit){
                                                                            $collect += $credit['amount'];
                                                                        }
                                                                        echo '₹'.$collect.'  <i class="text-danger fas fa-arrow-down"></i>';
                                                                        ?>
                                        </div>
                                        <p><small
                                                class="text-danger mt-1 mb-0 pb-0"><?php  echo count($credits).' Bills';?></small>
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
            <small class="text-muted">Avoid Sales in Credit to get more Profit</small>
        </div>
    </div>
</div>