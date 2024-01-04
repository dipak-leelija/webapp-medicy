<div class="mb-4">
    <div class="card border-left-info shadow py-2 pending_border animated--grow-in">
        <div class="card-body">
            <a class="text-decoration-none" href="#">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-sm font-weight-bold text-info text-uppercase mb-1">
                            Needs to PAY
                            <i class="text-danger fas fa-arrow-up"></i>
                        </div>
                        <?php 
                            $pays = $StockIn->needsToPay();
                            $payee = 0;
                            foreach($pays as $data){
                                $payee += $data['amount'];
                            }
                            $noOfPayees = count($pays);
                        ?>

                        <?php if($noOfPayees > 0): ?>

                        <div class="row">

                            <div class="col-12 d-flex align-items-center justify-content-around w-100">
                                <div class="w-25 h5 mb-0 font-weight-bold text-gray-800">
                                    <img class="w-50" src="<?= ASSETS_PATH ?>img/need-to-pay.svg" alt="...">
                                </div>

                                <div class="h5 mb-0 font-weight-bold text-gray-800">
                                    <?= '₹'.$payee; ?>
                                </div>

                                <div class="h5 mb-0 font-weight-bold text-gray-800">
                                    <?= $noOfPayees; ?> bills
                                </div>
                            </div>

                            <div class="col-12 table-responsive mt-2" id="sales-margin-data-table">
                                <table class="table table-sm">
                                    <thead>
                                        <tr>
                                            <th scope="col">Distributor</th>
                                            <th scope="col">Due Date</th>
                                            <th scope="col">Amount</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        foreach($pays as $data){
                                        $distributorName = $Distributor->distributorName($data['distributor_id']);
                                            ?>
                                            <tr>
                                                <td><?= $distributorName ?></td>
                                                <td><?= $data['due_date'] ?></td>
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
                                <p class="text-center font-weight-light">You don't have to pay to any distributor!</p>
                            </div>

                        <?php endif; ?>

                    </div>
                </div>
            </a>
        </div>
        <div class="card-footer">
            <small class="text-muted">Try to pay the due as soon as posibble</small>
        </div>
    </div>
</div>