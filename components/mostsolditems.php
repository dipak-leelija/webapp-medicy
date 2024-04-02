<div class="card border-left-primary shadow h-100 py-2 pending_border animated--grow-in">
    <div class="d-flex justify-content-between align-items-center">
        <div class="col ml-2 mt-3">
            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                most sold 10 items</div>
        </div>
        <div class="d-flex justify-content-end px-2">
            <div class="dropdown-menu dropdown-menu-right p-3 mt-n5" id="mostSoldDtPickerDiv" style="display: none; margin-right:1rem;">
                <input type="date" id="mostSoldDateInput">
                <button class="btn btn-sm btn-primary" onclick="mostSoldItemsChkDate()" style="height: 2rem;">Find</button>
            </div>
            <div class="dropdown-menu dropdown-menu-right p-3 mt-n5" id="mostSoldDtRngPickerDiv" style="display: none; margin-right:1rem; ">
                <label>Start Date</label>
                <input type="date" id="mostSoldStarDate">
                <label>End Date</label>
                <input type="date" id="mostSoldEndDate">
                <button class="btn btn-sm btn-primary" onclick="mostSoldItemsChkDateRng()" style="height: 2rem;">Find</button>
            </div>
            <div class="">
                <!-- <label class="d-none" id="asc-dsc-val">asc</label> -->
                <select class="upr-inp" name="sold-item-order" id="sold-item-order" onchange="soldItemOrder(this)">
                    <option value="" selected disabled>Order</option>
                    <option value="asc">ASC</option>
                    <option value="dsc">DSC</option>
                </select>
            </div>
            <div class="btn-group">
                <button type="button" class="btn btn-sm btn-outline-light text-dark card-btn dropdown font-weight-bold" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                    <b>...</b>
                </button>
                <div class="dropdown-menu dropdown-menu-right" style="background-color: rgba(255, 255, 255, 0.8);">
                    <button class="dropdown-item" type="button" id="mostSoldLst24hrs" onclick="mostStoldItemCheck(this.id)">Last 24 hrs</button>
                    <button class="dropdown-item" type="button" id="mostSoldLst7" onclick="mostStoldItemCheck(this.id)">Last 7 Days</button>
                    <button class="dropdown-item" type="button" id="mostSoldLst30" onclick="mostStoldItemCheck(this.id)">Last 30 DAYS</button>
                    <button class="dropdown-item  dropdown" type="button" id="mostSoldOnDt" onclick="mostStoldItemCheck(this.id)">By Date</button>
                    <button class="dropdown-item  dropdown" type="button" id="mostSoldOnDtRng" onclick="mostStoldItemCheck(this.id)">By Range</button>
                </div>
            </div>
        </div>
    </div>
    <div class="card-body mt-n2 pb-0">
        <div class="row no-gutters align-items-center">
            <div style="width: 100%; margin: 0 auto;" id='mostsolditemchartDiv'>
                <canvas id="mostsolditemchart"></canvas>
            </div>
            <div style="width: 100%; margin: 0 auto;" id='mostsolditemNDFDiv'>
                <p class="text-warning">Oops!, the requested data isn't in our records.</p>
            </div>
        </div>
    </div>
</div>

<script src="<?php echo PLUGIN_PATH; ?>chartjs-4.4.0/updatedChart.js"></script>


<?php

$strtDt = date('Y-m-d');
$lst7 = date('Y-m-d', strtotime($strtDt . ' - 7 days'));
$lst30 = date('Y-m-d', strtotime($strtDt . ' - 30 days'));

if ($_GET['searchSold'] == 'asc') {
    $stoldItemsFromStart = $StockOut->mostSoldStockOutDataFromStart($adminId);

    $dailySoldItems = $StockOut->mostSoldStockOutDataGroupByDay($adminId);

    $weeklSoldItems = $StockOut->mostSoldStockOutDataGroupByDtRng($lst7, $strtDt, $adminId);

    $monthlySoldItems = $StockOut->mostSoldStockOutDataGroupByDtRng($lst30, $strtDt, $adminId);
} else {
    $stoldItemsFromStart = $StockOut->leastSoldStockOutDataFromStart($adminId);

    $dailySoldItems = $StockOut->leastSoldStockOutDataGroupByDay($adminId);

    $weeklSoldItems = $StockOut->leastSoldStockOutDataGroupByWeek($adminId);

    $monthlySoldItems = $StockOut->leastSoldStockOutDataGroupByMonth($adminId);
}

?>

<script>
    const soldItemOrder = (t) => {
        if (t.value == 'asc') {
            var currentURL = window.location.href;
            var currentURLWithoutQuery = window.location.origin + window.location.pathname;
            var filter = 'asc';
            var newURL = `${currentURLWithoutQuery}?searchSold=${filter}`;
            window.location.replace(newURL);
        } else {
            var currentURL = window.location.href;
            var currentURLWithoutQuery = window.location.origin + window.location.pathname;
            var filter = 'dsc';
            var newURL = `${currentURLWithoutQuery}?searchSold=${filter}`;
            window.location.replace(newURL);
        }
    }
    // console.log("current value : "+document.getElementById('asc-dsc-val').innerHTML);

    // ====== most sold chart data override function =========
    function updateMostSoldData(mostSold) {

        if (mostSold != null) {

            mostSoldChart.data.datasets[0].data = mostSold.map(item => item.total_sold);

            var productIds = mostSold.map(item => item.product_id);
            productIds = JSON.stringify(productIds);

            // var xmlhttp = new XMLHttpRequest();
            mostSoldProdNameUrl = `<?php echo URL ?>ajax/components-most-sold-items.ajax.php?mostSoldProdId=${productIds}   `;
            request.open("GET", mostSoldProdNameUrl, false);
            request.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
            request.send(null);
            var prodNameArray = request.responseText;
            prodNameArray = JSON.parse(prodNameArray);

            mostSoldChart.data.labels = prodNameArray;

            document.getElementById('mostsolditemchartDiv').style.display = 'block'
            document.getElementById('mostsolditemNDFDiv').style.display = 'none'

            mostSoldChart.update();

        } else {
            document.getElementById('mostsolditemchartDiv').style.display = 'none'
            document.getElementById('mostsolditemNDFDiv').style.display = 'block'
        }
    }


    
    function mostSoldItemsChkDate() {
        var mostSolddatePicker = document.getElementById('mostSoldDateInput').value;

        // var xmlhttp = new XMLHttpRequest();
        mostSoldDtPkrUrl = `<?php echo URL ?>ajax/components-most-sold-items.ajax.php?mostSoldByDt=${mostSolddatePicker}`;
        request.open("GET", mostSoldDtPkrUrl, false);
        request.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        request.send(null);
        var mostSoldDataByDate = request.responseText;

        updateMostSoldData(JSON.parse(mostSoldDataByDate));

        document.getElementById('mostSoldDtPickerDiv').style.display = 'none';
        document.getElementById('mostSoldDtRngPickerDiv').style.display = 'none';
    }


    function mostSoldItemsChkDateRng() {
        var mostSoldStarDate = document.getElementById('mostSoldStarDate').value;
        var mostSoldEndDate = document.getElementById('mostSoldEndDate').value;

        // var xmlhttp = new XMLHttpRequest();
        mostSoldDtPkrUrl = `<?php echo URL ?>ajax/components-most-sold-items.ajax.php?mostSoldStarDate=${mostSoldStarDate}&mostSoldEndDate=${mostSoldEndDate}`;
        request.open("GET", mostSoldDtPkrUrl, false);
        request.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        request.send(null);

        var mostSoldDataByDate = request.responseText;

        updateMostSoldData(JSON.parse(mostSoldDataByDate));

        document.getElementById('mostSoldDtPickerDiv').style.display = 'none';
        document.getElementById('mostSoldDtRngPickerDiv').style.display = 'none';
    }



    function mostStoldItemCheck(id) {
        if (id == 'mostSoldLst24hrs') {
            document.getElementById('mostSoldDtPickerDiv').style.display = 'none';
            document.getElementById('mostSoldDtRngPickerDiv').style.display = 'none';
            updateMostSoldData(<?php echo json_encode($dailySoldItems); ?>);
        }


        if (id == 'mostSoldLst7') {
            document.getElementById('mostSoldDtPickerDiv').style.display = 'none';
            document.getElementById('mostSoldDtRngPickerDiv').style.display = 'none';
            updateMostSoldData(<?php echo json_encode($weeklSoldItems); ?>);
        }

        if (id == 'mostSoldLst30') {
            document.getElementById('mostSoldDtPickerDiv').style.display = 'none';
            document.getElementById('mostSoldDtRngPickerDiv').style.display = 'none';
            updateMostSoldData(<?php echo json_encode($monthlySoldItems); ?>);
        }

        if (id == 'mostSoldOnDt') {
            document.getElementById('mostSoldDtPickerDiv').style.display = 'block';
            // document.getElementById('mostSoldDtRngPickerDiv').style.display = 'none';
        }

        if (id == 'mostSoldOnDtRng') {
            // document.getElementById('mostSoldDtPickerDiv').style.display = 'none';
            document.getElementById('mostSoldDtRngPickerDiv').style.display = 'block';
        }
    }


    // ========= most sold item primary data area ============= \\
    var mostSoldDataFromStart = <?php echo json_encode($stoldItemsFromStart); ?>;

    if (mostSoldDataFromStart != null) {

        var productIds = mostSoldDataFromStart.map(item => item.product_id);
        productIds = JSON.stringify(productIds);
        var dataToSend = `mostSoldProdId=${productIds}`;

        // var xmlhttp = new XMLHttpRequest();
        mostSoldProdNameUrl = `<?php echo URL ?>ajax/components-most-sold-items.ajax.php?mostSoldProdId=${productIds}`;
        request.open("GET", mostSoldProdNameUrl, false);
        request.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        request.send(null);
        var prodNameArray = request.responseText;
        prodNameArray = JSON.parse(prodNameArray);

        var totalSold = mostSoldDataFromStart.map(item => item.total_sold);

        document.getElementById('mostsolditemchartDiv').style.display = 'block'
        document.getElementById('mostsolditemNDFDiv').style.display = 'none'
    } else {
        document.getElementById('mostsolditemchartDiv').style.display = 'none'
        document.getElementById('mostsolditemNDFDiv').style.display = 'block'
    }


    // =============  most sold item bar chart area =============
    var mostSoldChartCtx = document.getElementById('mostsolditemchart').getContext('2d');
    var mostSoldChart = new Chart(mostSoldChartCtx, {
        type: 'bar',
        data: {
            labels: prodNameArray,
            datasets: [{
                label: 'Total Sold',
                data: totalSold,
                backgroundColor: 'rgba(64, 156, 71, 0.8)',
                // borderColor: 'rgba(75, 192, 192, 1)',
                borderWidth: 1
            }]
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