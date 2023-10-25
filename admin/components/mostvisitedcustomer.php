
<?php
require_once dirname(dirname(__DIR__)) . '/config/constant.php';
$includePath = get_include_path();

$today = NOW;

$dailyMostVistiCustomerData = $StockOut->mostVisitCustomersByDay($adminId);
print_r($dailyMostVistiCustomerData);

// $weeklyMostVistiCustomerData = $StockOut->mostVisitCustomersByWeek($adminId);


// $monthlyMostVistiCustomerData = $StockOut->mostVisitCustomersByMonth($adminId);

?>

<div class="card border-left-primary h-100 py-2 pending_border animated--grow-in">
    <div class="d-flex justify-content-end px-2">
        <div id="dtPickerDiv" style="display: none;">
            <input type="date" id="dateInput">
            <button class="btn btn-sm btn-primary" id="added_on" value="CR" onclick="leastSoldItems(this.value)" style="height: 2rem;">Find</button>
        </div>
        <div class="btn-group">
            <button type="button" class="btn btn-sm btn-outline-light text-dark card-btn dropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                <!-- <img src=" IMG_PATH./arrow-down-sign-to-navigate.jpg" alt=""> -->

                <b>...</b>
            </button>
            <div class="dropdown-menu dropdown-menu-right">
                <button class="dropdown-item" type="button" id="lst7" onclick="leastSoldItems(this.id)">Last 7 Days</button>
                <button class="dropdown-item" type="button" id="lst30" onclick="leastSoldItems(this.id)">Last 30 DAYS</button>
                <button class="dropdown-item" type="button" id="lstdt" onclick="leastSoldItems(this.id)">By Date</button>
            </div>
        </div>
    </div>
    <div class="card-body pb-0">
        <div class="row no-gutters align-items-center">
            <div class="col mr-2">
                <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                    most sold 10 items</div>
                <div style="width: 80%; margin: 0 auto;">
                    <canvas id="barChart"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="../../../medicy.in/admin/vendor/chartjs-4.4.0/updatedChart.js"></script>

<script>
    const leastSoldItems = (id) => {
        var xmlhttp = new XMLHttpRequest();
        if (id == 'lst7') {
            lastThirtyDaysUrl = 'components/partials_ajax/salesoftheDay.ajax.php?lstWeek=' + id;
            xmlhttp.open("GET", lastThirtyDaysUrl, false);
            xmlhttp.send(null);
            document.getElementById("salesAmount").innerHTML = xmlhttp.responseText;
            document.getElementById("itemsCount").innerHTML = xmlhttp.responseText;
        }

        if (id == 'lst30') {
            lastThirtyDaysUrl = 'components/partials_ajax/salesoftheDay.ajax.php?lstMnth=' + id;
            xmlhttp.open("GET", lastThirtyDaysUrl, false);
            xmlhttp.send(null);
            document.getElementById("salesAmount").innerHTML = xmlhttp.responseText;
            document.getElementById("itemsCount").innerHTML = xmlhttp.responseText;
        }

        if (id == 'lstdt') {
            const dateInput = document.getElementById('dtPickerDiv');
            dateInput.style.display = 'block';
            dateInput.focus();
        }
    }
</script>

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
    </script>