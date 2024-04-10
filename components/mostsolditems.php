<?php

$strtDt = date('Y-m-d');
$lst7 = date('Y-m-d', strtotime($strtDt . ' - 7 days'));
$lst30 = date('Y-m-d', strtotime($strtDt . ' - 30 days'));




$dailyMostSoldItems = $StockOut->mostSoldStockOutDataGroupByDay($adminId);

$weeklyMostSoldItems = $StockOut->mostSoldStockOutDataGroupByDtRng($lst7, $strtDt, $adminId);

$monthlyMostSoldItems = $StockOut->mostSoldStockOutDataGroupByDtRng($lst30, $strtDt, $adminId);

$mostSoldItemsFromStart = $StockOut->mostSoldStockOutDataFromStart($adminId);


// print_r($mostSoldItemsFromStart);
//================================================================================


$dailyLessSoldItems = $StockOut->leastSoldStockOutDataGroupByDay($adminId);

$weeklyLessSoldItems = $StockOut->leastSoldStockOutDataGroupByWeek($adminId);

$monthlyLessSoldItems = $StockOut->leastSoldStockOutDataGroupByMonth($adminId);

$lessSoldItemsFromStart = $StockOut->leastSoldStockOutDataFromStart($adminId);


// print_r($stoldItemsFromStart);
?>


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
            <div class="mr-2">
                <label id='primary-filter' class="d-none">asc</label>
                <button type="button" class="btn btn-sm btn-outline-primary card-btn dropdown font-weight-bold" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                    <i class="fa-solid fa-sort-up"></i> Sort
                </button>

                <div class="dropdown-menu dropdown-menu-right" style="background-color: rgba(200, 200, 200, 0.3);">
                    <button class="dropdown-item  dropdown" type="button" id="asc" value="asc" onclick="dataSort(this)">Ascending</button>
                    <button class="dropdown-item  dropdown" type="button" id="dsc" value="dsc" onclick="dataSort(this)">Descending</button>
                </div>
            </div>
            <div class="btn-group">
                <button type="button" class="btn btn-sm btn-outline-primary card-btn dropdown font-weight-bold" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                    <i class="fas fa-filter"></i> Filter
                </button>

                <label id='secondary-filter' class="d-none"></label>
                <div class="dropdown-menu dropdown-menu-right" style="background-color: rgba(255, 255, 255, 0.8);">
                    <button class="dropdown-item" type="button" id="mostSoldLst24hrs" onclick="dataFilter(this.id)">Last 24 hrs</button>
                    <button class="dropdown-item" type="button" id="mostSoldLst7" onclick="dataFilter(this.id)">Last 7 Days</button>
                    <button class="dropdown-item" type="button" id="mostSoldLst30" onclick="dataFilter(this.id)">Last 30 DAYS</button>
                    <button class="dropdown-item  dropdown" type="button" id="mostSoldOnDt" onclick="dataFilter(this.id)">By Date</button>
                    <button class="dropdown-item  dropdown" type="button" id="mostSoldOnDtRng" onclick="dataFilter(this.id)">By Range</button>
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

<script>
    let flag = 0;

    // ====== most sold chart data override function =========
    function updateMostSoldData(mostSold) {
        let soldData = mostSold;
        console.log(soldData);
        console.log(mostSold);
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


    /// ================== filter area =====================

    let filterVal = document.getElementById("primary-filter").innerHTML;

    const dataSort = (t) => {
        if (t.value == 'asc') {
            var secondaryFilterVal = document.getElementById("secondary-filter").innerHTML;
            document.getElementById("primary-filter").innerHTML = t.value;
            dataFilter(secondaryFilterVal);
        } else if (t.value == 'dsc') {
            var secondaryFilterVal = document.getElementById("secondary-filter").innerHTML;
            document.getElementById("primary-filter").innerHTML = t.value;
            dataFilter(secondaryFilterVal);
        }
    }


    function dataFilter(id) {
        if (document.getElementById("primary-filter").innerHTML == 'asc') {
            if (id == 'mostSoldLst24hrs') {
                document.getElementById('secondary-filter').innerHTML = id;
                document.getElementById('mostSoldDtPickerDiv').style.display = 'none';
                document.getElementById('mostSoldDtRngPickerDiv').style.display = 'none';
                updateMostSoldData(<?php echo json_encode($dailyMostSoldItems); ?>);
            }

            if (id == 'mostSoldLst7') {
                document.getElementById('mostSoldDtPickerDiv').style.display = 'none';
                document.getElementById('mostSoldDtRngPickerDiv').style.display = 'none';
                updateMostSoldData(<?php echo json_encode($weeklyMostSoldItems); ?>);
            }

            if (id == 'mostSoldLst30') {
                document.getElementById('mostSoldDtPickerDiv').style.display = 'none';
                document.getElementById('mostSoldDtRngPickerDiv').style.display = 'none';
                updateMostSoldData(<?php echo json_encode($monthlyMostSoldItems); ?>);
            }

            if (id == 'mostSoldOnDt') {
                document.getElementById('mostSoldDtPickerDiv').style.display = 'block';
                // document.getElementById('mostSoldDtRngPickerDiv').style.display = 'none';
            }

            if (id == 'mostSoldOnDtRng') {
                // document.getElementById('mostSoldDtPickerDiv').style.display = 'none';
                document.getElementById('mostSoldDtRngPickerDiv').style.display = 'block';
            }

        } else if (document.getElementById("primary-filter").innerHTML == 'dsc') {

            if (id == 'mostSoldLst24hrs') {
                document.getElementById('mostSoldDtPickerDiv').style.display = 'none';
                document.getElementById('mostSoldDtRngPickerDiv').style.display = 'none';
                updateMostSoldData(<?php echo json_encode($dailyLessSoldItems); ?>);
            }


            if (id == 'mostSoldLst7') {
                document.getElementById('mostSoldDtPickerDiv').style.display = 'none';
                document.getElementById('mostSoldDtRngPickerDiv').style.display = 'none';
                updateMostSoldData(<?php echo json_encode($weeklyLessSoldItems); ?>);
            }

            if (id == 'mostSoldLst30') {
                document.getElementById('mostSoldDtPickerDiv').style.display = 'none';
                document.getElementById('mostSoldDtRngPickerDiv').style.display = 'none';
                updateMostSoldData(<?php echo json_encode($monthlyLessSoldItems); ?>);
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
    }



    

    // ========= most sold item primary data area ============= \\

    var mostSoldDataFromStart = <?php echo json_encode($mostSoldItemsFromStart); ?>;

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