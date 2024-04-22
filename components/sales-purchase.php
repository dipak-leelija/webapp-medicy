<?php

$dailySoldData = $StockOut->mostSoldStockOutDataGroupByDay($adminId);

$weeklySoldData = $StockOut->mostSoldStockOutDataGroupByDtRng($lst7, $strtDt, $adminId);

$monthlySoldData = $StockOut->mostSoldStockOutDataGroupByDtRng($lst30, $strtDt, $adminId);

$biMonthlySoldData = $StockOut->mostSoldStockOutDataFromStart($adminId);

//====================================================================================================

// $dailyPurchaseData = $StockInDetails->mostSoldStockOutDataGroupByDay($adminId);

// $weeklyPurchaseData = $StockInDetails->mostSoldStockOutDataGroupByDtRng($lst7, $strtDt, $adminId);

// $montlyPurchaseData = $StockInDetails->mostSoldStockOutDataGroupByDtRng($lst30, $strtDt, $adminId);

// $biMonthlyPurchaseData = $StockInDetails->mostSoldStockOutDataFromStart($adminId);
?>

<div class="card border-left-primary shadow h-100 py-2 pending_border animated--grow-in">
    <div class="d-flex justify-content-between align-items-center">
        <div class="d-flex justify-content-end px-2">
            <div class="dropdown-menu dropdown-menu-right p-3 mt-n5" id="sales-purchase-dt-picker" style="display: none; margin-right:1rem;">
                <input type="date" id="sales-purchase-dt-input">
                <button class="btn btn-sm btn-primary" onclick="salesPurchaseDate()" style="height: 2rem;">Find</button>
            </div>
            <div class="dropdown-menu dropdown-menu-right p-3 mt-n5" id="sales-purchase-dt-range" style="display: none; margin-right:1rem; ">
                <label>Start Date</label>
                <input type="date" id="sales-purchase-start-dt">
                <label>End Date</label>
                <input type="date" id="sales-purchase-end-dt">
                <button class="btn btn-sm btn-primary" onclick="salesPurchaseDateRange()" style="height: 2rem;">Find</button>
            </div>
        </div>

        <div class="col-9 mt-1">
            <div class="container-fluid">
                <ul class="nav nav-tabs">
                    <li class="nav-item" style="font-size: medium;">
                        <button id="sellPurchaseToday" class="nav-link active" onclick="changeFilter(this.id)" style="color: blue; font-size: small; background-color: white;">Today</button>
                    </li>
                    <li class="nav-item">
                        <button id="sellPurchaseToday7days" class="nav-link" onclick="changeFilter(this.id)" style="font-size: small; background-color: white; border-bottom: 1px;">7 days</button>
                    </li>
                    <li class="nav-item">
                        <button id="sellPurchaseToday30days" class="nav-link" onclick="changeFilter(this.id)" style="font-size: small; background-color: white; border-bottom: 1px;">30 days</button>
                    </li>
                    <li class="nav-item">
                        <button id="sellPurchaseToday60days" class="nav-link" onclick="changeFilter(this.id)" style="font-size: small; background-color: white; border-bottom: 1px;">60 days</button>
                    </li>
                </ul>
            </div>
        </div>

        <div class="col-3">
            <button type="button" class="btn btn-sm btn-outline-primary card-btn dropdown font-weight-bold" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                <i class="fas fa-filter"></i> Filter Date
            </button>

            <div class="dropdown-menu dropdown-menu-right" style="background-color: rgba(255, 255, 255, 0.8);">
                <button class="dropdown-item  dropdown" type="button" id="soldOnDt" onclick="dataFilter(this.id)">By Date</button>
                <button class="dropdown-item  dropdown" type="button" id="soldOnDtRng" onclick="dataFilter(this.id)">By Range</button>
            </div>
        </div>
    </div>
    <div class="card-body mt-n2 pb-0">
        good evening everyone
    </div>
</div>


<script>
    const changeFilter = (id) => {
        if (id == "sellPurchaseToday") {
            console.log(id);
            document.getElementById('sellPurchaseToday').style.borderBottom = 'none';
            document.getElementById('sellPurchaseToday7days').style.borderBottom = '1px solid transparent';
            document.getElementById('sellPurchaseToday30days').style.borderBottom = '1px solid transparent';
            document.getElementById('sellPurchaseToday60days').style.borderBottom = '1px solid transparent';
            
        }

        if (id == "sellPurchaseToday7days") {
            console.log(id);
            document.getElementById('sellPurchaseToday').style.borderBottom = '1px solid transparent';;
            document.getElementById('sellPurchaseToday7days').style.borderBottom = 'none';;
            document.getElementById('sellPurchaseToday30days').style.borderBottom = '1px solid transparent';;
            document.getElementById('sellPurchaseToday60days').style.borderBottom = '1px solid transparent';;
        }

        if (id == "sellPurchaseToday30days") {
            console.log(id);
            document.getElementById('sellPurchaseToday').style.borderBottom = '1px solid transparent';
            document.getElementById('sellPurchaseToday7days').style.borderBottom = '1px solid transparent';
            document.getElementById('sellPurchaseToday30days').style.borderBottom = 'none';
            document.getElementById('sellPurchaseToday60days').style.borderBottom = '1px solid transparent';
        }

        if (id == "sellPurchaseToday60days") {
            console.log(id);
            document.getElementById('sellPurchaseToday').style.borderBottom = '1px solid transparent';
            document.getElementById('sellPurchaseToday7days').style.borderBottom = '1px solid transparent';
            document.getElementById('sellPurchaseToday30days').style.borderBottom = '1px solid transparent';
            document.getElementById('sellPurchaseToday60days').style.borderBottom = 'none';
        }
    }
</script>