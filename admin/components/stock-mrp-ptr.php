<!-- Current Stock Quantity  -->
<div class="mb-4 ">
    <div class="card border-left-secondary py-2 animated--grow-in">
        <div class="card-body">
            <div class="row no-gutters align-items-center">
                <div class="col mr-2">
                    <div class="text-xs font-weight-bold text-secondary text-uppercase mb-1">
                        Stock Quantity</div>
                    <div class="h5 mb-0 font-weight-bold text-gray-800">
                        <?php
                            $qStock = $CurrentStock->showCurrentStockbyAdminId($adminId);
                            // print_r($qStock);
                            $currentQty = 0;
                            foreach ($qStock as $data) {
                                $currentQty += $data['qty'];
                            }
                            echo $currentQty; 
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
    <div class="card border-left-secondary py-2 animated--grow-in">
        <div class="card-body">
            <div class="row no-gutters align-items-center">
                <div class="col mr-2">
                    <div class="text-xs font-weight-bold text-secondary text-uppercase mb-1">
                        Total Stock MRP</div>
                    <div class="h5 mb-0 font-weight-bold text-gray-800">
                        <?php
                            $qStock = $CurrentStock->showCurrentStockbyAdminId($adminId);
                            // print_r($qStock);
                            $currentQty = 0;
                            foreach ($qStock as $data) {
                                $currentQty += $data['mrp'];
                            }
                            echo '₹'.$currentQty; 
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