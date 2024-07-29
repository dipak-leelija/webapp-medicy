<?php
require_once dirname(dirname(__DIR__)) . '/config/constant.php';



$response = $Component->salesOfEmployee($adminId);
$salesEmp = [];
foreach ($response as $eachResponse) {
    $salesEmp[] = [$Utility->getNameById($eachResponse['added_by']), $eachResponse['amount']];
}

$salesEmp = json_encode($salesEmp);
?>
<div class="card shadow-sm h-100 py-2 animated--grow-in">
    <div class="px-3 mt-2">
        <p class="text-xs font-weight-bold text-success text-uppercase mb-1">Sales of Employees</p>
        <canvas id="salesbyemp-doughnut"></canvas>

    </div>
</div>

<script>
    document.addEventListener("DOMContentLoaded", function(event) {
        // Parse JSON data from PHP
        var mydata = <?php echo $salesEmp; ?>;

        // Process data for the chart
        var labels = [];
        var data = [];
        var bgColor = [];


        mydata.forEach(function(item) {
            var name = item[0];
            var amount = item[1];

            // push name and amout one by ine in the array
            labels.push(name);
            data.push(amount);
            bgColor.push(getRandomColor());

        });

        // Chart.js code
        var salesbyemp = document.getElementById('salesbyemp-doughnut').getContext('2d');

        var salesDoughnut = new Chart(salesbyemp, {
            type: 'doughnut',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Amount',
                    data: data,
                    backgroundColor: bgColor,
                    hoverOffset: 4
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'top',
                    },
                }
            },
        });
    });
</script>