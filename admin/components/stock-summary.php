<div class="mb-4">
    <div class="card border-top-primary pending_border animated--grow-in">
        <div class="card-body">
            <a class="text-decoration-none" href="#">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                            Stock details
                        </div>
                        <?php
                                                    $current = $CurrentStock->showCurrentStockbyAdminId($adminId);
                                                    // print_r($current);
                                                    $currentPTR = 0;
                                                    $currentMRP = 0;
                                                    foreach ($current as $data) {
                                                        $batchNo =  $data['batch_no'];
                                                        $currentQty =  $data['qty'];
                                                        $items = $StockInDetails->showStockInByBatch($batchNo);
                                                        // print_r($items);

                                                        foreach ($items as $item) {
                                                            $currentPTR += $item['amount'];
                                                            $currentMRP = $currentQty * $item['amount'];
                                                        }

                                                    } 
                                                ?>
                        <div class="table-responsive">

                            <table class="table">
                                <thead>
                                    <tr>
                                        <th scope="col">Stock</th>
                                        <th scope="col">By PTR</th>
                                        <th scope="col">By MRP</th>
                                        <th scope="col">Margin</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <th scope="row">Current</th>
                                        <td><?php echo $currentPTR; ?></td>
                                        <td><?php echo $currentMRP; ?></td>
                                        <td>00.00</td>

                                    </tr>
                                    <tr>
                                        <th scope="row">Expired</th>
                                        <td>00.00</td>
                                        <td>00.00</td>
                                        <td>00.00</td>

                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </a>
        </div>
    </div>
</div>