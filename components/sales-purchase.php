<?php
$date = new DateTime();
$today = $date->format('Y-m-d');

// Calculate dates for comparison
$before7day = $date->modify('-7 days')->format('Y-m-d');
$before30day = $date->modify('-30 days')->format('Y-m-d');
$before60day = $date->modify('-60 days')->format('Y-m-d');

?>


<div class="card border-left-primary shadow h-100 py-2 pending_border animated--grow-in">
    <div class="row mt-1">
        <div class="col-8">
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
        <div class="col-2">
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
        <div class="col-1">
            <button type="button" class="btn btn-sm btn-outline-primary card-btn dropdown font-weight-bold" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                <i class="fas fa-filter"></i>
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
    let salesPurchaseDataChart;

    // Function to show sales and purchase data on chart
    function salesPurchaseDataChartShow(dataArray) {
        salesPurchaseDataChart.data.labels = Object.keys(dataArray);
        salesPurchaseDataChart.data.datasets[0].data = Object.values(dataArray).map(data => parseFloat(data.sales_amount || 0));
        salesPurchaseDataChart.data.datasets[1].data = Object.values(dataArray).map(data => parseFloat(data.purchase_amount || 0));
        salesPurchaseDataChart.update();
    }

    // sales purchase data call function --------------
    const salesPurchaseDataCall = (startDate, endDate) => {

        salesPurchaseDataFetchUrl = `<?php echo URL ?>ajax/sales-purchae-data-fetch.ajax.php?startDt=${startDate}&endDt=${endDate}`;
        xmlhttp.open("GET", salesPurchaseDataFetchUrl, false);
        xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        xmlhttp.send(null);

        var salesPurchseData = JSON.parse(xmlhttp.responseText);

        if (salesPurchseData.status == '1') {
            // total sell purchse data display
            document.getElementById('sales-amount').innerHTML = salesPurchseData.totalSellAmount;
            document.getElementById('sales-count').innerHTML = salesPurchseData.totalSellCount;

            document.getElementById('purchae-amount').innerHTML = salesPurchseData.totalPurchaseAmount;
            document.getElementById('purchase-count').innerHTML = salesPurchseData.totalPurchaseCount;

            salesPurchaseDataChartShow(salesPurchseData.sellPurchaseDataArray);

        }

        if (salesPurchseData.status == '0') {
            document.getElementById('sales-amount').innerHTML = '0';
            document.getElementById('sales-count').innerHTML = '0';

            document.getElementById('purchae-amount').innerHTML = '0';
            document.getElementById('purchase-count').innerHTML = '0';
        }


    }


    const changeFilter = (id) => {
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
    }

    salesPurchaseDataChart = new Chart(document.getElementById('salesPurchaseDataChart').getContext('2d'), {

        type: 'bar',
        data: {
            labels: [],
            datasets: [{
                    label: "Sales",
                    data: [],
                    borderWidth: 0,
                    backgroundColor: 'rgb(179, 204, 255)',
                    minBarThickness: 5,
                    maxBarThickness: 15,
                },
                {
                    label: "Purchase",
                    data: [],
                    borderWidth: 0,
                    backgroundColor: 'rgb(102, 153, 255)',
                    minBarThickness: 5,
                    maxBarThickness: 15,
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


    changeFilter('sellPurchaseToday');
</script>