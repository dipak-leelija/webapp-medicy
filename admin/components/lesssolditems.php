
<?php
require_once dirname(dirname(__DIR__)) . '/config/constant.php';
$includePath = get_include_path();

$today = NOW;

$dailyLeastStoldItems = $StockOut->leastSoldStockOutDataGroupByDay($adminId);


$weeklyLeastStoldItems = $StockOut->leastSoldStockOutDataGroupByWeek($adminId);


$monthlyLeastStoldItems = $StockOut->leastSoldStockOutDataGroupByMonth($adminId);
// print_r($monthlyLeastStoldItems);
?>

<div class="card border-left-primary h-100 py-2 pending_border animated--grow-in">
    <div class="d-flex justify-content-end px-2">
        <div id="dtPickerDiv" style="display: none;">
            <input type="date" id="dateInput">
            <button class="btn btn-sm btn-primary" id="added_on" onclick="lessSoldItemsChkDate()" style="height: 2rem;">Find</button>
        </div>
        <div class="btn-group">
            <button type="button" class="btn btn-sm btn-outline-light text-dark card-btn dropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                <!-- <img src=" IMG_PATH./arrow-down-sign-to-navigate.jpg" alt=""> -->

                <b>...</b>
            </button>
            <div class="dropdown-menu dropdown-menu-right">
                <button class="dropdown-item" type="button" id="lessLst7" onclick="lessSoldItemChk(this.id)">Last 7 Days</button>
                <button class="dropdown-item" type="button" id="lessLst30" onclick="lessSoldItemChk(this.id)">Last 30 DAYS</button>
                <button class="dropdown-item" type="button" id="lessLstDt" onclick="lessSoldItemChk(this.id)">By Date</button>
            </div>
        </div>
    </div>
    <div class="card-body pb-0">
        <div class="row no-gutters align-items-center">
            <div class="col mr-2">
                <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                    less sold 10 items</div>
                    <div style="width: 100%; margin: 0 auto;">
                <canvas id="lesssolditemchart"></canvas>
            </div>
            </div>
        </div>
    </div>
</div>

<script src="../../../medicy.in/admin/vendor/chartjs-4.4.0/updatedChart.js"></script>

<script>
    function lessSoldItemsChkDate(){
        var mostSolddatePicker = document.getElementById('mostSoldDateInput').value;
        var dataToSend = `dtRange=${mostSolddatePicker}`;

        var xmlhttp = new XMLHttpRequest();
        mostSoldDtPkrUrl = `../admin/ajax/components-most-sold-items.ajax.php`;
        xmlhttp.open("POST", mostSoldDtPkrUrl, false);
        xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        xmlhttp.send(dataToSend);
        var mostSoldDataInDtRange = xmlhttp.responseText;

        console.log(mostSoldDataInDtRange);
        updateData(JSON.parse(mostSoldDataInDtRange));
    }



    function updateLessSoldData(data) {
        console.log(data);
        chart.data.datasets[0].data = data.map(item => item.total_sold);

        var productIds = data.map(item => item.product_id);
        productIds = JSON.stringify(productIds);
        var dataToSend = `prodId=${productIds}`;

        var xmlhttp = new XMLHttpRequest();
        prodNameUrl = `../admin/ajax/components-most-sold-items.ajax.php`;
        xmlhttp.open("POST", prodNameUrl, false);
        xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        xmlhttp.send(dataToSend);
        var prodNameArray = xmlhttp.responseText;
        prodNameArray = JSON.parse(prodNameArray);

        chart.data.labels = prodNameArray;
        chart.update();
    }





    function lessSoldItemChk(id) {
        console.log(id);
        if (id == 'lessLst7') {
            document.getElementById('dtPickerDiv').style.display = 'none';
            updateMostSoldData(<?php echo json_encode($weeklyLeastStoldItems); ?>);
        }

        if (id == 'lessLst30') {
            document.getElementById('dtPickerDiv').style.display = 'none';
            updateMostSoldData(<?php echo json_encode($monthlyLeastStoldItems); ?>);
        }

        if (id == 'lessLstDt') {
            document.getElementById('dtPickerDiv').style.display = 'block';
        }
    }




    // ========= chart control area ============= \\
    let data = <?php echo json_encode($dailyLeastStoldItems); ?>;
    var productIds = data.map(item => item.product_id);
    productIds = JSON.stringify(productIds);
    var dataToSend = `prodId=${productIds}`;


    var xmlhttp = new XMLHttpRequest();
    prodName = `../admin/ajax/components-most-sold-items.ajax.php`;
    xmlhttp.open("POST", prodName, false);
    xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xmlhttp.send(dataToSend);
    var prodNameArray = xmlhttp.responseText;
    prodNameArray = JSON.parse(prodNameArray);

    var totalSold = data.map(item => item.total_sold);

    var ctx = document.getElementById('lesssolditemchart').getContext('2d');

    var chart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: prodNameArray,
            datasets: [{
                label: 'Total Sold',
                data: totalSold,
                backgroundColor: 'rgba(75, 192, 192, 0.2)',
                borderColor: 'rgba(75, 192, 192, 1)',
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
<!-- 
<script>
        // Your PHP data
        var data = [
            { product_id: 'PR928140071769', total_sold: 9 },
            { product_id: 'PR146231800947', total_sold: 5 },
            { product_id: 'PR618347790083', total_sold: 5 },
            // Add the rest of your data here
        ];

        // Extract product IDs and total sold values
        var productIds = data.map(item => item.product_id);
        var totalSold = data.map(item => item.total_sold);

        // Create a bar chart using Chart.js
        var ctx = document.getElementById('barChart').getContext('2d');
        var chart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: productIds,
                datasets: [{
                    label: 'Total Sold',
                    data: totalSold,
                    backgroundColor: 'rgba(75, 192, 192, 0.2)',
                    borderColor: 'rgba(75, 192, 192, 1)',
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
    </script> -->