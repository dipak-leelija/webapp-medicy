<div class="card border-left-danger h-100 py-2 animated--grow-in">
    <div class="card-body pb-0">
        <div class="row no-gutters align-items-center">
            <div class="col mr-2">
                <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                    Expiring in 3 Months </div>
                <div class="h5 mb-0 font-weight-bold text-gray-800">
                    <?php 
                        $thisMonth = date("m/y");
                        $newMnth = date("m/y", strtotime("+2 months"));
                        $expStok = $CurrentStock->showStockExpiry($newMnth); 
                        $count = 0;
                        foreach($expStok as $expCount){
                            $expDt = $expCount["exp_date"];
                            $expDt = str_replace("/", "", $expDt);
                            $expiaryDtMnth = intval(substr($expDt,0,2));
                            $expiaryDtYr = intval(substr($expDt,2));

                            $newMnth = str_replace("/", "", $newMnth);
                            $newExpMnth = intval(substr($newMnth,0,2));
                            $newExpYr = intval(substr($newMnth,2));
                            
                            $thisMonth = str_replace("/", "", $thisMonth);
                            $thisMnth = intval(substr($thisMonth,0,2));
                            if($thisMnth == 11 || $thisMnth == 12){
                                $newExpYr = $newExpYr + 1;
                            }

                            if($newExpYr == $expiaryDtYr){
                                $count = $count + 1; 
                            }
                        }
                        echo $count;
                    ?> Stocks</div>
            </div>
            <div class="col-auto text-danger">
                <i class="fas fa-calendar-times"></i>
            </div>
        </div>
    </div>
</div>