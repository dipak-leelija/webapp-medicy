<?php

$date = new DateTime();

$today = $date->format('Y-m-d');

// Clone the $date object for independent modifications
$before7day = clone $date;
$before7day->modify('-7 days');
$before7day = $before7day->format('Y-m-d');

$before30day = clone $date;
$before30day->modify('-30 days');
$before30day = $before30day->format('Y-m-d');

$before60day = clone $date;
$before60day->modify('-60 days');
$before60day = $before60day->format('Y-m-d');

$dailySalesData = $StockOut->selectStockOutDataOnDateFilter($today, $today, $adminId);
$weeklySalesData = $StockOut->selectStockOutDataOnDateFilter($before7day, $today, $adminId);
$monthlySalesData = $StockOut->selectStockOutDataOnDateFilter($before30day, $today, $adminId);
$biMonthlySalesData = $StockOut->selectStockOutDataOnDateFilter($before60day, $today, $adminId);
print_r($monthlySalesData);


$dailyPurchaseData = $StockIn->purchaseDatafetchByDateRange($today, $today, $adminId);
$weeklyPurchaseData = $StockIn->purchaseDatafetchByDateRange($before7day, $today, $adminId);
$montlyPurchaseData = $StockIn->purchaseDatafetchByDateRange($before30day, $today, $adminId);
$biMonthlyPurchaseData = $StockIn->purchaseDatafetchByDateRange($before60day, $today, $adminId);
print_r($montlyPurchaseData);

?>


<div class="card border-left-primary shadow h-100 py-2 pending_border animated--grow-in">
    <div class="row mt-1">
        <div class="col-7">
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
        </div>
        <div class="col-2">
            <button type="button" class="btn btn-sm btn-outline-primary card-btn dropdown font-weight-bold" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                <i class="fas fa-filter"></i> Filter
            </button>

            <div class="dropdown-menu dropdown-menu-right" style="background-color: rgba(255, 255, 255, 0.8);">
                <button class="dropdown-item  dropdown" type="button" id="soldOnDt" onclick="dataFilter(this.id)">By Date</button>
                <button class="dropdown-item  dropdown" type="button" id="soldOnDtRng" onclick="dataFilter(this.id)">By Range</button>
            </div>
        </div>
    </div>
    <div class="row mt-2 d-flex mt-1 mb-1">
        <div class="col-1"></div>
        <div class="col-4">
            <div class="row d-flex">
                <div class="col-md-12">
                    <label for="">Sales : </label>
                    <label id="sales-amount">0</label>
                </div>
            </div>
            <div class="row">
                <label class="ml-3" id="sales-count">0</label>
            </div>
        </div>
        <div class="col-3"></div>
        <div class="col-4">
            <div class="row d-flex">
                <div class="col-md-12">
                    <label for="">Purchase : </label>
                    <label id="purchae-amount">0</label>
                </div>
            </div>
            <div class="row">
                <label class="ml-3" id="purchase-count">0</label>
            </div>
        </div>
    </div>
    <div class="d-flex justify-content-between align-items-center">


        <div class="card-body mt-n2 pb-0">
            <div class="row no-gutters align-items-center">
                <div class="col mr-2">
                    <div style="width: 100%; margin: 0 auto;" id="salesPurchaseDataChartDiv">
                        <canvas id="salesPurchaseDataChart"></canvas>
                    </div>
                    <div style="width: 100%; margin: 0 auto; display:none" id="sales-purchase-no-data-found-div">
                        <p class="text-warning">Oops!, the requested data isn't in our records.</p>
                    </div>
                </div>
            </div>
        </div>
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


    // ========= chart control area ============= \\
    var salesData = JSON.parse('<?php echo $monthlySalesData; ?>');
    var purchaseData = JSON.parse('<?php echo $montlyPurchaseData; ?>');

    var salesAmount = salesData.data.map(item => parseFloat(item.amount));
    var salesDates = salesData.data.map(item => item.bill_date);

    // Extracting purchase amount and stock in dates
    var purchaseAmount = purchaseData.data.map(item => parseFloat(item.amount));
    var purchaseDates = purchaseData.data.map(item => item.bill_date);

    var labels = salesDates.concat(purchaseDates);
    var salesPurchaseDataCtx = document.getElementById('salesPurchaseDataChart').getContext('2d');
    var salesPurchaseChart = new Chart(salesPurchaseDataCtx, {
        type: 'bar',
        data: {
            labels: labels,
            datasets: [{
                    label: "Sales",
                    data: salesAmount,
                    borderWidth: 1,
                    barThickness: 10,
                    maxBarThickness: 10,
                },
                {
                    label: "Purchase",
                    data: purchaseAmount,
                    borderWidth: 1,
                    barThickness: 10,
                    maxBarThickness: 10,
                }
            ]
        },
        options: {
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });
</script>