<!-- Current Stock Quantity  -->
<div class="mb-4 ">
    <div class="card border-left-secondary shadow py-2 animated--grow-in">
        <div class="card-body">
            <div class="row no-gutters align-items-center">
                <div class="col mr-2">
                    <div class="text-xs font-weight-bold text-secondary text-uppercase mb-1">
                        Stock Quantity</div>
                    <div class="h5 mb-0 font-weight-bold text-gray-800">
                        <?php
                            $cStock = $CurrentStock->showCurrentStockbyAdminId($adminId);
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
                    <div class="h5 mb-0 font-weight-bold text-gray-800">
                        <?php
                            $cStock = $CurrentStock->showCurrentStockbyAdminId($adminId);
                            // print_r($cStock);
                            if($cStock != null){
                                $currentMRP = 0;
                                foreach ($cStock as $data) {
                                    $currentMRP +=  $data['mrp'];
                                }
                                echo '₹'.$currentMRP; 
                            }else {
                                echo '₹ 0';
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