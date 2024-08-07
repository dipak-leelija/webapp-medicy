<?php
$date = new DateTime();
$today = $date->format('Y-m-d');

// Calculate dates for comparison
$before7day = $date->modify('-7 days')->format('Y-m-d');
$before30day = $date->modify('-30 days')->format('Y-m-d');
$before60day = $date->modify('-60 days')->format('Y-m-d');

?>


<div class="card border-left-primary shadow-sm h-100 py-2 pending_border animated--grow-in">
    <div class="row mt-1">
        <div class="col-7">
            <div class="container-fluid">
                <ul class="nav nav-tabs" style="size: small;">
                    <li class="nav-item" style="font-size: medium;">
                        <button id="sellPurchaseToday" class="nav-link" onclick="sellPurchseDataFilter(this.id)" style="color: blue; font-size: small; background-color: white;">Today</button>
                    </li>
                    <li class="nav-item">
                        <button id="sellPurchaseToday7days" class="nav-link" onclick="sellPurchseDataFilter(this.id)" style="font-size: small; background-color: white; border-bottom: 1px;">7 days</button>
                    </li>
                    <li class="nav-item">
                        <button id="sellPurchaseToday30days" class="nav-link" onclick="sellPurchseDataFilter(this.id)" style="font-size: small; background-color: white; border-bottom: 1px;">30 days</button>
                    </li>
                    <li class="nav-item">
                        <button id="sellPurchaseToday60days" class="nav-link" onclick="sellPurchseDataFilter(this.id)" style="font-size: small; background-color: white; border-bottom: 1px;">60 days</button>
                    </li>
                </ul>
            </div>
        </div>

        <div class="col-3">
            <div class="d-flex justify-content-end px-2">
                <div class="dropdown-menu dropdown-menu-right p-3 mt-n5 p-3 mt-n5" id="sales-purchase-dt-picker" style="display: none;">
                    <input type="date" class="small-date-input" id="sales-purchase-dt-input">
                    <button class="btn btn-sm btn-primary" id="sales-purchase-dt-picker-input" onclick="sellPurchseDataFilter(this.id)"><i class="fas fa-search"></i></button>
                </div>
                <div class="dropdown-menu dropdown-menu-right p-3 mt-n5 p-3 mt-n5" id="sales-purchase-dt-picker-range" style="display: none;">
                    <label>Start Date</label>
                    <input type="date" id="sales-purchase-rng-start-dt">
                    <label>End Date</label>
                    <input type="date" id="sales-purchase-rng-end-dt">
                    <button class="btn btn-sm btn-primary" id="sales-purchase-dt-picker-range-input" onclick="sellPurchseDataFilter(this.id)" style="height: 2rem;"><i class="fas fa-search"></i></button>
                </div>
            </div>
        </div>

        <div class="col-2 d-flex justify-content-center">
            <button type="button" class="btn btn-sm btn-outline-primary card-btn dropdown font-weight-bold" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                <i class="fas fa-filter"></i>
            </button>

            <div class="dropdown-menu dropdown-menu-right" style="background-color: rgba(255, 255, 255, 0.8);">
                <button class="dropdown-item  dropdown" type="button" id="sold-purchase-OnDt" onclick="dateFilter(this.id)">By Date</button>
                <button class="dropdown-item  dropdown" type="button" id="sold-purchase-OnDtRng" onclick="dateFilter(this.id)">By Range</button>
            </div>
        </div>
    </div>

    <div class="row d-flex mt-2">
        <div class="col-6 d-flex justify-content-center">
            <label for=""><b>Sales</b></label>
        </div>
        <div class="col-6 d-flex justify-content-center">
            <label for=""><b>Purchase</b></label>
        </div>
    </div>
    <div class="row d-flex">
        <div class="col-6 d-flex justify-content-center" style="font-size: x-large; color: #9ae5e5;">
            <b>
                <p>&#x20b9;</p>
            </b>&nbsp;<b><label id="sales-amount">0</label></b>
        </div>
        <div class="col-6 d-flex justify-content-center" style="font-size: x-large; color: #34cbcb;">
            <b>
                <p>&#x20b9;</p>
            </b>&nbsp;<b><label id="purchae-amount">0</label></b>
        </div>
    </div>
    <div class="row d-flex">
        <div class="col-6 d-flex justify-content-center">
            <b><label id="sales-count">0</label></b><label>&nbsp;Orders</label>
        </div>
        <div class="col-6 d-flex justify-content-center">
            <b><label id="purchase-count">0</label></b><label>&nbsp;Orders</label>
        </div>
    </div>

    <div class="d-flex justify-content-between align-items-center">
        <div class="card-body mt-n2 pb-0">
            <div class="row no-gutters align-items-center">
                <div class="col mr-2">
                    <div style="width: 100%; margin: 0 auto; height: 75%" id="salesPurchaseDataChartDiv">
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
    let salesPurchaseDataChart;

    // Function to show sales and purchase data on chart
    function salesPurchaseDataChartShow(dataArray, yAxisVal) {

        salesPurchaseDataChart.data.labels = Object.keys(dataArray);
        salesPurchaseDataChart.data.datasets[0].data = Object.values(dataArray).map(data => parseFloat(data.sales_amount || 0));
        salesPurchaseDataChart.data.datasets[1].data = Object.values(dataArray).map(data => parseFloat(data.purchase_amount || 0));
        salesPurchaseDataChart.options.scales.y.max = yAxisVal;
        salesPurchaseDataChart.options.scales.y.min = 0;
        // salesPurchaseDataChart.options.scales.y.ticks.stepSize = 5000;
        salesPurchaseDataChart.update();

    }

    // reset chart as no value found
    function resetChart(filterDateArray) {

        // console.log(filterDateArray);

        salesPurchaseDataChart.data.labels = filterDateArray;
        salesPurchaseDataChart.data.datasets[0].data = [];
        salesPurchaseDataChart.data.datasets[1].data = [];
        salesPurchaseDataChart.options.scales.y.max = 5;
        salesPurchaseDataChart.options.scales.y.min = -5;

        salesPurchaseDataChart.update();

    }


    // sales purchase data call function --------------
    const salesPurchaseDataCall = (startDate, endDate) => {

        salesPurchaseDataFetchUrl = `<?php echo URL ?>ajax/sales-purchae-data-fetch.ajax.php?startDt=${startDate}&endDt=${endDate}`;
        request.open("GET", salesPurchaseDataFetchUrl, false);
        request.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        request.send(null);
        var salesPurchseData = JSON.parse(request.responseText);

        if (salesPurchseData.status == '1') {

            document.getElementById('sales-amount').innerHTML = salesPurchseData.totalSellAmount;
            document.getElementById('sales-count').innerHTML = salesPurchseData.totalSellCount;

            document.getElementById('purchae-amount').innerHTML = salesPurchseData.totalPurchaseAmount;
            document.getElementById('purchase-count').innerHTML = salesPurchseData.totalPurchaseCount;

            salesPurchaseDataChartShow(salesPurchseData.sellPurchaseDataArray, salesPurchseData.yAxisVal);

        } else {

            document.getElementById('sales-amount').innerHTML = '0';
            document.getElementById('sales-count').innerHTML = '0';

            document.getElementById('purchae-amount').innerHTML = '0';
            document.getElementById('purchase-count').innerHTML = '0';

            resetChart(salesPurchseData.filterDate);
        }
    }


    const sellPurchseDataFilter = (id) => {
        document.getElementById('sales-purchase-dt-picker').style.display = 'none';
        document.getElementById('sales-purchase-dt-picker-range').style.display = 'none';

        //================================================================
        const ids = ['sellPurchaseToday', 'sellPurchaseToday7days', 'sellPurchaseToday30days', 'sellPurchaseToday60days'];

        ids.forEach(ids => {
            document.getElementById(ids).style.color = 'black';
            document.getElementById(ids).style.border = 'none';
            document.getElementById(ids).style.borderBottom = '1px solid #e6e6e6';
        });

        //=================================================
        document.getElementById(id).classList.add('active');
        document.getElementById(id).style.color = 'blue';
        document.getElementById(id).style.borderBottom = '1px solid white';
        document.getElementById(id).style.borderTop = '1px solid #e6e6e6';
        document.getElementById(id).style.borderLeft = '1px solid #e6e6e6';
        document.getElementById(id).style.borderRight = '1px solid #e6e6e6';



        if (id == "sellPurchaseToday") {
            salesPurchaseDataCall('<?php echo $today; ?>', '<?php echo $today; ?>');
        }

        if (id == "sellPurchaseToday7days") {
            salesPurchaseDataCall('<?php echo $before7day; ?>', '<?php echo $today; ?>');
        }

        if (id == "sellPurchaseToday30days") {
            salesPurchaseDataCall('<?php echo $before30day; ?>', '<?php echo $today; ?>');
        }

        if (id == "sellPurchaseToday60days") {
            salesPurchaseDataCall('<?php echo $before60day; ?>', '<?php echo $today; ?>');
        }

        if (id == "sales-purchase-dt-picker-input") {
            var dtPickerStartDt = document.getElementById('sales-purchase-dt-input').value;
            salesPurchaseDataCall(dtPickerStartDt, dtPickerStartDt);
        }

        if (id == "sales-purchase-dt-picker-range-input") {
            var dtPickerStartDt = document.getElementById('sales-purchase-rng-start-dt').value;
            var dtPickerEndDt = document.getElementById('sales-purchase-rng-end-dt').value;
            salesPurchaseDataCall(dtPickerStartDt, dtPickerEndDt);
        }
    }



    const dateFilter = (id) => {
        if (id == 'sold-purchase-OnDt') {
            document.getElementById('sales-purchase-dt-picker').style.display = 'block';
            document.getElementById('sales-purchase-dt-picker-range').style.display = 'none';
        }

        if (id == 'sold-purchase-OnDtRng') {
            document.getElementById('sales-purchase-dt-picker').style.display = 'none';
            document.getElementById('sales-purchase-dt-picker-range').style.display = 'block';
        }
    }


    salesPurchaseDataChart = new Chart(document.getElementById('salesPurchaseDataChart').getContext('2d'), {
        type: 'bar',
        data: {
            labels: [],
            datasets: [{
                    label: "Sales",
                    data: [],
                    borderWidth: 0,
                    backgroundColor: 'rgba(57, 137, 250, 1)',
                    minBarThickness: 2,
                    maxBarThickness: 15,
                },
                {
                    label: "Purchase",
                    data: [],
                    borderWidth: 0,
                    backgroundColor: 'rgba(245, 39, 93, 0.8)',
                    minBarThickness: 5,
                    maxBarThickness: 12,
                }
            ]
        },
        options: {
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        callback: function(value, index, values) {
                            return Math.round(value); // Round off values here
                        }
                    }
                }
            }
        }
    });


    sellPurchseDataFilter('sellPurchaseToday7days');
</script>