<div class="card border-left-info border-right-info h-100 py-2 pending_border animated--grow-in">
    <div class="d-flex justify-content-end px-2">
        <div class="btn-group">
            <button type="button" class="btn btn-sm btn-outline-light text-dark card-btn dropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                <b>...</b>
            </button>
                <div class="dropdown-menu dropdown-menu-right">
                    <button class="dropdown-item" type="button" id="lst7" onclick="chkDays(this.id)">Last 7 Days</button>
                    <button class="dropdown-item" type="button" id="lst30" onclick="chkDays(this.id)">Last 1 Month</button>
                    <button class="dropdown-item" type="button" id="lstdt" onclick="chkDays(this.id)">By Date</button>
                </div>
        </div>
    </div>
    <div class="card-body pb-0">
        <div class="row no-gutters align-items-center">
            <div class="col mr-2">
                <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                    sales of the day</div>
                <div class="h5 mb-0 font-weight-bold text-gray-800">
                    <?php
                        // $id = $_POST['lst7'];
                        $frmDt = date("Y-m-d");
                        $toDt = date("Y-m-d");
                        // echo $id;
                        // $soldToday = $StockOut->soldByDate($toDay);
                        // print_r($soldToday);
                        $item = 0;
                        $bill = 0;
                        // foreach ($soldToday as $data) {
                        //     $item += $data['items'];
                        //     $bill += $data['amount'];
                        // }
                        echo '₹' . $bill;
                    ?>
                </div>
                <p class="mb-0 pb-0"><small class="mb-0 pb-0"><?php echo $item; ?>
                        Items</small></p>

            </div>
            <div class="col-auto">
                <i class="fas fa-rupee-sign"></i>
            </div>
        </div>
    </div>
</div>