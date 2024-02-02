<!-- Current Stock Quantity  -->
<div class="mb-4 ">
    <div class="card border-left-secondary shadow py-2 animated--grow-in">
        <div class="card-body">
            <div class="row no-gutters align-items-center">
                <div class="col mr-2">
                    <div class="text-xs font-weight-bold text-secondary text-uppercase mb-1">
                        Stock Quantity</div>
                    <div class="mb-0 font-weight-bold text-gray-800">
                        <?php
                            $column = 'qty';
                            $cStock = $CurrentStock->selectColumOnStockByAdmin($column, $adminId);
                            // print_r($cStock);
                            if($cStock != null){
                                $currentQty = 0;
                                foreach ($cStock as $data) {
                                    $currentQty += $data['qty'];
                                }
                                    echo $currentQty; 
                                }else {
                                    echo '0';
                                }
                        ?> Units
                    </div>
                </div>
                <div class="col-auto">
                    <i class="fas fa-shopping-cart"></i>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Current Stock MRP -->
<div class="mb-4">
    <div class="card border-left-secondary shadow py-2 animated--grow-in">
        <div class="card-body">
            <div class="row no-gutters align-items-center">
                <div class="col mr-2">
                    <div class="text-xs font-weight-bold text-secondary text-uppercase mb-1">
                        Total Stock MRP</div>
                    <div class="mb-0 font-weight-bold text-gray-800">
                        <?php
                            $currentMRP = 0;
                            $column = 'mrp';
                            $cStock = $CurrentStock->showCurrentStockbyAdminId($adminId);
                            // print_r($cStock);
                            if($cStock != null){
                            for($i=0; $i<count($cStock); $i++){
                                // print_r($cStock[$i]);
                                    foreach ($cStock as $data) {
                                        // print_r($data);
                                        $currentMRP = floatval($currentMRP) + (floatval($data['mrp'])*intval($data['qty']));
                                        // echo "<br>".$currentMRP;
                                    }
                                }
                                echo '₹'.round($currentMRP,2); 
                            }else{
                                echo '₹'.'0';
                            }
                        ?>
                    </div>
                </div>
                <div class="col-auto">
                    <i class="fas fa-rupee-sign"></i>
                </div>
            </div>
        </div>
    </div>
</div>