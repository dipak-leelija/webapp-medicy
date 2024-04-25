<?php

$mostVistedCustomerFromStart = $StockOut->mostVistedCustomerFrmStart($adminId);

$dailyMostVistiCustomerData = $StockOut->mostVisitCustomersByDay($adminId);

$weeklyMostVistiCustomerData = $StockOut->mostVisitCustomersByWeek($adminId);

$monthlyMostVistiCustomerData = $StockOut->mostVisitCustomersByMonth($adminId);

//========================================================================================

$highestPurchaseCustomerAllTime = $StockOut->overallMostPurchaseCustomer($adminId);

$highestPurchaseCustomerByDay = $StockOut->mostPurchaseCustomerByDay($adminId);

$highestPurchaseCustomerByWeek = $StockOut->mostPurchaseCustomerByWeek($adminId);

$highestPurchaseCustomerByMonth = $StockOut->mostPurchaseCustomerByMonth($adminId);
// print_r($mostVistedCustomerFromStart);exit;
?>

<div class="card border-left-primary shadow h-100 py-2 pending_border animated--grow-in">
    <div class="d-flex justify-content-between align-items-center">
        <div class="col ml-2 mt-3">
            <ul class="nav nav-tabs">
                <li class="nav-item" style="font-size: medium;">
                    <button id="mostVisitedLink" class="nav-link active" onclick="changeTab('mostVisited')" style="color: rebeccapurple; font-size: small; background-color: white;">Most Visited Customer</button>
                </li>
                <li class="nav-item">
                    <button id="highestPurchasedLink" class="nav-link" onclick="changeTab('highestPurchased')" style="font-size: small; background-color: white; border-bottom: 1px;">Highest Purchased Customer</button>
                </li>
            </ul>

            <label id='customer-sort' class="d-none" value='mostVisited'>mostVisited</label>
            <lebel class="d-none" id="chart-label">Visit Count</lebel>

        </div>
        <div class="d-flex justify-content-end px-2">
            <div class="dropdown-menu dropdown-menu-right p-3 mt-n5" id="mostVistedCustomerDtPkr" style="display: none; ">
                <input type="date" id="mostVisiteCustomerDt">
                <button class="btn btn-sm btn-primary" onclick="mostVistedCustomerByDt()" style="height: 2rem;">Find</button>
            </div>
            <div class="dropdown-menu dropdown-menu-right p-3 mt-n5" id="mostVistedCustomerDtPkrRng" style="display: none;">
                <label>Start Date</label>
                <input type="date" id="mostVisiteCustomerStartDate">
                <label>End Date</label>
                <input type="date" id="mostVisiteCustomerEndDate">
                <button class="btn btn-sm btn-primary" onclick="mostVistedCustomerDateRange()" style="height: 2rem;">Find</button>
            </div>
            <div class="btn-group">
                <button type="button" class="btn btn-sm btn-outline-primary card-btn dropdown font-weight-bold" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                    <i class="fas fa-filter"></i> Filter
                </button>
                <div class="dropdown-menu dropdown-menu-right" style="background-color: rgba(255, 255, 255, 0.8);">
                    <button class="dropdown-item" type="button" id="mostVisitCustomerLst24hrs" onclick="mostvisitCustomer(this.id)">Last 24 hrs</button>
                    <button class="dropdown-item" type="button" id="mostVisitCustomerLst7" onclick="mostvisitCustomer(this.id)">Last 7 Days</button>
                    <button class="dropdown-item" type="button" id="mostVisitCustomerLst30" onclick="mostvisitCustomer(this.id)">Last 30 DAYS</button>
                    <button class="dropdown-item dropdown" type="button" id="mostVisitCustomerOnDt" onclick="mostvisitCustomer(this.id)">By Date</button>
                    <button class="dropdown-item dropdown" type="button" id="mostVisitCustomerDtRng" onclick="mostvisitCustomer(this.id)">By Range</button>
                </div>

                <lebel class="d-none" id="customer-purchse-filter-val">allData</lebel>
            </div>
        </div>
    </div>
    <div class="card-body mt-n2 pb-0">
        <div class="row no-gutters align-items-center">
            <div class="col mr-2">
                <div style="width: 100%; margin: 0 auto;" id="mostVisitCustomerCharDiv">
                    <canvas id="mostVisitCustomerChart"></canvas>
                </div>
                <div style="width: 100%; margin: 0 auto; display:none" id="most-visited-no-data-found-div">
                    <p class="text-warning">Oops!, the requested data isn't in our records.</p>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- <script src="../../../medicy.in/admin/vendor/chartjs-4.4.0/updatedChart.js"></script> -->


<script>
    function changeTab(tab) {
        document.getElementById('mostVisitedLink').classList.remove('active');
        document.getElementById('highestPurchasedLink').classList.remove('active');
        let customerSort = tab;

        if (tab === 'mostVisited') {

            document.getElementById('mostVisitedLink').classList.add('active');
            document.getElementById('mostVisitedLink').style.color = 'rebeccapurple';
            document.getElementById('mostVisitedLink').style.borderBottom = 'none';
            document.getElementById('highestPurchasedLink').style.borderBottom = '1px';
            document.getElementById('highestPurchasedLink').style.color = 'black';
            document.getElementById('customer-sort').innerHTML = customerSort;
            document.getElementById('chart-label').innerHTML = 'Visit Count';

            mostvisitCustomer(document.getElementById('customer-purchse-filter-val').innerHTML);

        } else if (tab === 'highestPurchased') {

            document.getElementById('highestPurchasedLink').classList.add('active');
            document.getElementById('mostVisitedLink').style.color = 'black';
            document.getElementById('highestPurchasedLink').style.color = 'rebeccapurple';
            document.getElementById('highestPurchasedLink').style.borderBottom = 'none';
            document.getElementById('mostVisitedLink').style.borderBottom = '1px';
            document.getElementById('customer-sort').innerHTML = customerSort;
            document.getElementById('chart-label').innerHTML = 'Purchase Amount';

            mostvisitCustomer(document.getElementById('customer-purchse-filter-val').innerHTML);

        }
    }

    // =========== most visit customer chart override function body ==========
    function mostVisitCustomerDataFunction(mostVisitCustomerData, flag) {

        if (mostVisitCustomerData != null) {

            if (flag == 0) {
                mostVistedCustomerChart.data.datasets[0].data = mostVisitCustomerData.map(item => item.visit_count);
            } else {
                mostVistedCustomerChart.data.datasets[0].data = mostVisitCustomerData.map(item => item.total_purchase);
            }

            var customerId = mostVisitCustomerData.map(item => item.customer_id);
            customerId = JSON.stringify(customerId);

            mostVisitedCustomerDataUrl = `<?php echo URL ?>ajax/most-visit-and-purchase-customer.ajax.php?customerId=${customerId}`;
            xmlhttp.open("GET", mostVisitedCustomerDataUrl, false);
            xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
            xmlhttp.send(null);
            var mostVistiCustomerNameArray = xmlhttp.responseText;

            mostVistiCustomerNameArray = JSON.parse(mostVistiCustomerNameArray);

            mostVistedCustomerChart.data.labels = mostVistiCustomerNameArray;

            document.getElementById("mostVisitCustomerCharDiv").style.display = 'block';
            document.getElementById('most-visited-no-data-found-div').style.display = 'none';

            mostVistedCustomerChart.update();

        } else {
            document.getElementById("mostVisitCustomerCharDiv").style.display = 'none';
            document.getElementById('most-visited-no-data-found-div').style.display = 'block';
        }

    }



    // ============= most visit customer by specific date function body ==============
    function mostVistedCustomerByDt() {
        var mostVistedCustomerDt = document.getElementById('mostVisiteCustomerDt').value;
        
        if(document.getElementById('customer-sort').innerHTML == 'mostVisited'){
            var customerFilterByDate = 'mostVstCstmrByDt';
            var flag = 0;
        }else if(document.getElementById('customer-sort').innerHTML == 'highestPurchased'){
            var flag = 1;
            var customerFilterByDate = 'mostPrchsCstmrByDt';
        }

        mostVstCstmrDtUrl = `<?php echo URL ?>ajax/most-visit-and-purchase-customer.ajax.php?${customerFilterByDate}=${mostVistedCustomerDt}`;
        xmlhttp.open("GET", mostVstCstmrDtUrl, false);
        xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        xmlhttp.send(null);
        
        mostVisitCustomerDataFunction(JSON.parse(xmlhttp.responseText), flag);

        document.getElementById('mostVistedCustomerDtPkr').style.display = 'none';
        document.getElementById('mostVistedCustomerDtPkrRng').style.display = 'none';
    }


    // ============= most visit customer by date range function body ==============
    function mostVistedCustomerDateRange() {
        var purchaseVisitStartDt = document.getElementById('mostVisiteCustomerStartDate').value;
        var purchaseVisitEndDt = document.getElementById('mostVisiteCustomerEndDate').value;

        if(document.getElementById('customer-sort').innerHTML == 'mostVisited'){
            var flag = 0;
            var customFilterByStartDt = 'mostVisitStartDt';
            var customFilterByEndDt = 'mostVisitEndDt';
            
        }else if(document.getElementById('customer-sort').innerHTML == 'highestPurchased'){
            var flag = 1;
            var customFilterByStartDt = 'mostPurchaseStartDt';
            var customFilterByEndDt = 'mostPurchaseEndDt';
        }

        mostVstCstmrDtRngUrl = `<?php echo URL ?>ajax/most-visit-and-purchase-customer.ajax.php?${customFilterByStartDt}=${purchaseVisitStartDt}&${customFilterByEndDt}=${purchaseVisitEndDt}`;
        xmlhttp.open("GET", mostVstCstmrDtRngUrl, false);
        xmlhttp.send(null);

        mostVisitCustomerDataFunction(JSON.parse(xmlhttp.responseText), flag);

        document.getElementById('mostVistedCustomerDtPkr').style.display = 'none';
        document.getElementById('mostVistedCustomerDtPkrRng').style.display = 'none';
    }


    // ============ button onclick function call area ===============
    const mostvisitCustomer = (id) => {
        let filter1 = document.getElementById('customer-sort');
        document.getElementById('customer-purchse-filter-val').innerHTML = filter2 = id;

        if (filter1.innerHTML == 'mostVisited') {
            var flag = 0;

            if (filter2 == 'allData') {
                document.getElementById('mostVistedCustomerDtPkr').style.display = 'none';
                document.getElementById('mostVistedCustomerDtPkrRng').style.display = 'none';
                mostVisitCustomerDataFunction(<?php echo json_encode($mostVistedCustomerFromStart); ?>, flag);
            }

            if (filter2 == 'mostVisitCustomerLst24hrs') {
                document.getElementById('mostVistedCustomerDtPkr').style.display = 'none';
                document.getElementById('mostVistedCustomerDtPkrRng').style.display = 'none';
                mostVisitCustomerDataFunction(<?php echo json_encode($dailyMostVistiCustomerData); ?>, flag);
            }

            if (filter2 == 'mostVisitCustomerLst7') {
                document.getElementById('mostVistedCustomerDtPkr').style.display = 'none';
                document.getElementById('mostVistedCustomerDtPkrRng').style.display = 'none';
                mostVisitCustomerDataFunction(<?php echo json_encode($weeklyMostVistiCustomerData); ?>, flag);
            }

            if (filter2 == 'mostVisitCustomerLst30') {
                document.getElementById('mostVistedCustomerDtPkr').style.display = 'none';
                document.getElementById('mostVistedCustomerDtPkrRng').style.display = 'none';
                mostVisitCustomerDataFunction(<?php echo json_encode($monthlyMostVistiCustomerData); ?>, flag);
            }

            if (id == 'mostVisitCustomerOnDt') {
                document.getElementById('mostVistedCustomerDtPkrRng').style.display = 'none';
                document.getElementById('mostVistedCustomerDtPkr').style.display = 'block';

            }

            if (filter2 == 'mostVisitCustomerDtRng') {
                document.getElementById('mostVistedCustomerDtPkr').style.display = 'none';
                document.getElementById('mostVistedCustomerDtPkrRng').style.display = 'block';
            }
        }


        if (filter1.innerHTML == 'highestPurchased') {
            var flag = 1;

            if (filter2 == 'allData') {
                document.getElementById('mostVistedCustomerDtPkr').style.display = 'none';
                document.getElementById('mostVistedCustomerDtPkrRng').style.display = 'none';
                mostVisitCustomerDataFunction(<?php echo json_encode($highestPurchaseCustomerAllTime); ?>, flag);
            }

            if (filter2 == 'mostVisitCustomerLst24hrs') {
                document.getElementById('mostVistedCustomerDtPkr').style.display = 'none';
                document.getElementById('mostVistedCustomerDtPkrRng').style.display = 'none';
                mostVisitCustomerDataFunction(<?php echo json_encode($highestPurchaseCustomerByDay); ?>, flag);
            }

            if (filter2 == 'mostVisitCustomerLst7') {
                document.getElementById('mostVistedCustomerDtPkr').style.display = 'none';
                document.getElementById('mostVistedCustomerDtPkrRng').style.display = 'none';
                mostVisitCustomerDataFunction(<?php echo json_encode($highestPurchaseCustomerByWeek); ?>, flag);
            }

            if (filter2 == 'mostVisitCustomerLst30') {
                document.getElementById('mostVistedCustomerDtPkr').style.display = 'none';
                document.getElementById('mostVistedCustomerDtPkrRng').style.display = 'none';
                mostVisitCustomerDataFunction(<?php echo json_encode($highestPurchaseCustomerByMonth); ?>, flag);
            }

            if (id == 'mostVisitCustomerOnDt') {
                document.getElementById('mostVistedCustomerDtPkrRng').style.display = 'none';
                document.getElementById('mostVistedCustomerDtPkr').style.display = 'block';

            }

            if (filter2 == 'mostVisitCustomerDtRng') {
                document.getElementById('mostVistedCustomerDtPkr').style.display = 'none';
                document.getElementById('mostVistedCustomerDtPkrRng').style.display = 'block';
            }
        }
    }




    // ============== primary chart data area ==============
    var mostVstCutmrData = <?php echo json_encode($mostVistedCustomerFromStart); ?>;

    if (mostVstCutmrData != null) {
        var customerId = mostVstCutmrData.map(item => item.customer_id);
        customerId = JSON.stringify(customerId);

        mostVisitedCustomerDataUrl = `<?php echo URL ?>ajax/most-visit-and-purchase-customer.ajax.php?customerId=${customerId}`;
        xmlhttp.open("GET", mostVisitedCustomerDataUrl, false);
        xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        xmlhttp.send(null);
        var customerNameArray = xmlhttp.responseText;

        customerNameArray = JSON.parse(customerNameArray);

        var totalVisit = mostVstCutmrData.map(item => item.visit_count);

    } else {
        document.getElementById("mostVisitCustomerCharDiv").style.display = 'none';
        document.getElementById('most-visited-no-data-found-div').style.display = 'block';
    }


    // ========= chart control area ============= \\
    var sticker = document.getElementById('chart-label').innerHTML;
    var mstVstCstmrCtx = document.getElementById('mostVisitCustomerChart').getContext('2d');

    var mostVistedCustomerChart = new Chart(mstVstCstmrCtx, {
        type: 'bar',
        data: {
            labels: customerNameArray,
            datasets: [{
                label: sticker,
                data: totalVisit,
                backgroundColor: 'rgb(179, 179, 255)',
                borderWidth: 0,
                barThickness: 10,
                maxBarThickness: 10,
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