<div class="mb-4">
    <div class="card border-left-warning py-2 pending_border animated--grow-in">
        <div class="card-body">
            <div class="row no-gutters align-items-center">
                <div class="col mr-2">
                    <div class="text-sm font-weight-bold text-warning text-uppercase mb-1">
                        Needs to collect
                        <i class="text-danger fas fa-arrow-down"></i>
                    </div>

                    <?php
                            $credits = $StockOut->needsToCollect($adminId);
                            $collect = 0;
                            foreach($credits as $credit){
                                $collect += $credit['amount'];
                            }
                            $payer  = count($credits);
                        ?>
                    <?php if ($payer > 0): ?>
                    <div class="row">

                        <div class="col-12 d-flex align-items-center justify-content-around w-100">
                            <div class="w-25 h5 mb-0 font-weight-bold text-gray-800">
                                <img class="w-50" src="<?= ASSETS_PATH ?>img/needs-to-collect.svg"
                                    alt="needs to collect">
                            </div>

                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                <?= '₹'.$collect; ?>
                            </div>

                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                <?= $payer ?> Bills
                            </div>
                        </div>
                        <div class="col-12 table-responsive mt-2" id="sales-margin-data-table">

                            <table class="table table-sm">
                                <thead>
                                    <tr>
                                        <th scope="col">Stock</th>
                                        <th scope="col">Pack</th>
                                        <th scope="col">MRP</th>
                                        <th scope="col">Margin</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                        foreach($credits as $data){
                                        $patientName = $Patients->patientName($data['customer_id']);
                                    ?>
                                    <tr>
                                        <td><?= $patientName ?></td>
                                        <td><?= $data['bill_date'] ?></td>
                                        <td><?= $data['amount'] ?></td>
                                    </tr>
                                    <?php } ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <?php else: ?>

                    <div class="col-12">
                        <p class="text-center">
                            <i class="far fa-laugh-beam display-4"></i>
                            <br>
                            <span>Wohoo!</span>
                        </p>
                        <p class="text-center font-weight-light">You don't have to collect from any customer/patient!
                        </p>
                    </div>

                    <?php endif; ?>

                </div>
            </div>
        </div>
        <div class="card-footer">
            <small class="text-muted">Avoid Sales in Credit to get more Profit</small>
        </div>
    </div>
</div>