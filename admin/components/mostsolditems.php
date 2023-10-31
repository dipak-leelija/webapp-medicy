<?php
require_once dirname(dirname(__DIR__)) . '/config/constant.php';
$includePath = get_include_path();

$today = NOW;

$dailyMostStoldItems = $StockOut->stockOutDataGroupByDay($adminId);
// print_r($dailyMostStoldItems);

$weeklyMostStoldItems = $StockOut->stockOutDataGroupByWeek($adminId);
// print_r($weeklyMostStoldItems);

$monthlyMostStoldItems = $StockOut->stockOutDataGroupByMonth($adminId);
// print_r($monthlyMostStoldItems);
?>

<div class="card border-left-primary h-100 py-2 pending_border animated--grow-in">
    <div class="d-flex justify-content-end px-2">
        <div id="dtPickerDiv" style="display: none;">
            <input type="date" id="dateInput">
            <button class="btn btn-sm btn-primary" id="added_on" value="CR" onclick="mostSoldItems(this.value)" style="height: 2rem;">Find</button>
        </div>
        <div class="btn-group">
            <button type="button" class="btn btn-sm btn-outline-light text-dark card-btn dropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                <!-- <img src=" IMG_PATH./arrow-down-sign-to-navigate.jpg" alt=""> -->

                <b>...</b>
            </button>
            <div class="dropdown-menu dropdown-menu-right">
                <button class="dropdown-item" type="button" id="lst7">Last 7 Days</button>
                <button class="dropdown-item" type="button" id="lst30">Last 30 DAYS</button>
                <button class="dropdown-item" type="button" id="lstdt">By Date</button>
            </div>
        </div>
    </div>
    <div class="card-body pb-0">
        <div class="row no-gutters align-items-center">
            <div class="col mr-2">
                <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                    most sold 10 items</div>
            </div>
            <div style="width: 100%; margin: 0 auto;">
                <canvas id="mostsolditemchart"></canvas>
            </div>
        </div>
    </div>
</div>

<script src="../../../medicy.in/admin/vendor/chartjs-4.4.0/updatedChart.js"></script>
<script>

</script>

<script>
    let isClicked1 = false;
    const button1 = document.getElementById("lst7");
    button1.addEventListener("click", function() {
        isClicked1 = true;
        updateData();
    });

    
    function updateData() {

        // var salesData24hrs = <?php echo json_encode($weeklyMostStoldItems); ?>;
        // var salesData7 = <?php echo json_encode($weeklyMostStoldItems); ?>;
        // var salesData30 = <?php echo json_encode($weeklyMostStoldItems); ?>;
        // var data = salesData;

        if (isClicked1) {
            data = <?php echo json_encode($weeklyMostStoldItems); ?>;
            console.log("Button is clicked after page load");
            chart.update();
        }
    }

    // ========= chart control area ============= \\
    let data = <?php echo json_encode($weeklyMostStoldItems); ?>;
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

    var ctx = document.getElementById('mostsolditemchart').getContext('2d');

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