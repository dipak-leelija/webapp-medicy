<?php

$newPatients             = $Patients->newPatientCount($adminId);
$totalCount = 0;
foreach ($newPatients as $row) {
    $patientCount = $row->patient_count;
    $addedOn = $row->added_on;
    $totalCount += $patientCount;
}
$newPatientLast24Hours   = $Patients->newPatientCountLast24Hours($adminId);
$newPatientLast7Days     = $Patients->newPatientCountLast7Days($adminId);
$newPatientLast30Days    = $Patients->newPatientCountLast30Days($adminId);


?>


<div class="card border-left-success shadow h-100 py-2">
    <div class="d-flex justify-content-end px-1">
        <div id="newPatientDtPkr" style="display: none;">
            <input style="height: 20px;" type="date" id="newPatientDt">
            <button class="btn btn-sm btn-primary" onclick="newPatientByDt()" style="height: 1.5rem; padding:0px;">Find</button>
        </div>
        <div id="newPatientDtPkrRng" style="display: none;">
            <label style="margin-bottom: 0px;"><small>Start Date</small></label>&nbsp; &nbsp; <label style="margin-bottom: 0px;"><small>End Date</small></label><br>
            <input style="width: 70px; height:20px;" type="date" id="newPatientStartDate">
            <input style="width: 70px; height:20px;" type="date" id="newPatientEndDate">
            <button class="btn btn-sm btn-primary" onclick="newPatientDateRange()" style="height: 1.5rem; padding:0px;">Find</button>
        </div>
        <div class="btn-group">
            <button type="button" class="btn btn-sm btn-outline-light text-dark card-btn dropdown " data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                <b>...</b>
            </button>
            <div class="dropdown-menu dropdown-menu-right" style="background-color: rgba(255, 255, 255, 0.8);">
                <button class="dropdown-item" type="button" id="newPatientLst24hrs" onclick="newPatientCount(this.id)">Last 24 hrs</button>
                <button class="dropdown-item" type="button" id="newPatientLst7" onclick="newPatientCount(this.id)">Last 7 Days</button>
                <button class="dropdown-item" type="button" id="newPatientLst30" onclick="newPatientCount(this.id)">Last 30 DAYS</button>
                <button class="dropdown-item" type="button" id="newPatientOnDt" onclick="newPatientCount(this.id)">By Date</button>
                <button class="dropdown-item" type="button" id="newPatientDtRng" onclick="newPatientCount(this.id)">By Range</button>
            </div>
        </div>
    </div>
    <div class="card-body pt-0">
        <div class="row no-gutters align-items-center">
            <div class="col mr-2">
                <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                    <i class="fas fa-user-plus"></i> New Patients
                </div>
                <div class="h5 mb-0 font-weight-bold text-gray-800">
                    <span id="newPatients"><?= ($newPatients) ? $totalCount : 'No Data Found' ?> </span>
                </div>
            </div>
        </div>
    </div>
    <?php
    if ($newPatients) {
        echo '<div class="d-flex justify-content-end ">
               <button type="button" class=" btn btn-sm btn-outline-light text-dark " id="chartButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true" disabled> <i class="fas fa-chart-line"></i></button>
                <div class="dropdown-menu " id="chartMenu" style="margin-top: -128;height: 123px;width: 226px;">
                 <canvas id="myChart"></canvas>
                </div>
              </div>';
    } else {
        echo '';
    }
    ?>
</div>

<script src="../../../medicy.in/admin/vendor/chartjs-4.4.0/updatedChart.js"></script>

<script>
    ///find new patient by selected date ///

    function newPatientByDt() {
        var newPatientDt = document.getElementById('newPatientDt').value;
        var xhr = new XMLHttpRequest();
        xhr.open('POST', '../admin/ajax/new-patient-count.ajax.php', true);
        xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
        xhr.onreadystatechange = function() {
            if (xhr.readyState === XMLHttpRequest.DONE) {
                if (xhr.status === 200) {
                    var newPatientsDataByDt = JSON.parse(xhr.responseText);
                    console.log(newPatientsDataByDt.length);
                    const ctx1 = document.getElementById('myChart');
                    const labels = [];
                    const data = [];
                    var toatalcount1 = 0;
                    newPatientsDataByDt.forEach(row => {
                        labels.push(row.added_on);
                        data.push(row.patient_count);
                        toatalcount1 += row.patient_count;
                    });

                    new Chart(ctx1, {
                        type: 'line',
                        data: {
                            labels: labels,
                            datasets: [{
                                label: 'New Patients Count',
                                data: data,
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
                    document.getElementById('newPatients').textContent = toatalcount1;
                    document.getElementById('newPatientDtPkr').style.display = 'none';
                }
            }
        };
        xhr.send('newPatientDt=' + encodeURIComponent(newPatientDt));
    }

    /// find new patient by selected range ///
    // function newPatientDateRange() {
    //     var newPatientStartDate = document.getElementById('newPatientStartDate').value;
    //     var newPatientEndDate = document.getElementById('newPatientEndDate').value;
    //     console.log(newPatientStartDate);
    //     console.log(newPatientEndDate);

    //     newPatientRange = `../admin/ajax/new-patient-count.ajax.php?newStartDate=${newPatientStartDate}&newEndDate=${newPatientEndDate}`;
    //     xmlhttp.open("GET", newPatientRange, false);
    //     xmlhttp.send(null);

    //     var newPatientDateRange = JSON.parse(xmlhttp.responseText);
    //     console.log(newPatientDateRange);
    //     // const labels = [];
    //     // const data = [];
    //     var totalCount = 0;
    //     newPatientDateRange.forEach(row => {
    //         labels.push(row.added_on);
    //         data.push(row.count);
    //         totalCount += row.count;
    //     });

    //     document.getElementById('newPatients').textContent = totalCount;
    //     document.getElementById('newPatientDtPkrRng').style.display = 'none';

    // }
    function newPatientDateRange() {
        var newPatientStartDate = document.getElementById('newPatientStartDate').value;
        var newPatientEndDate = document.getElementById('newPatientEndDate').value;

        newPatientRange = `../admin/ajax/new-patient-count.ajax.php?newStartDate=${newPatientStartDate}&newEndDate=${newPatientEndDate}`;
        xmlhttp.open("GET", newPatientRange, true); // Use asynchronous request
        xmlhttp.onreadystatechange = function() {
            if (xmlhttp.readyState == XMLHttpRequest.DONE) {
                if (xmlhttp.status == 200) {
                    var newPatientDateRange = JSON.parse(xmlhttp.responseText);
                    console.log(newPatientDateRange);

                    // Now you have an array of objects with 'count' and 'added_on' properties.
                    for (var i = 0; i < newPatientDateRange.length; i++) {
                        var item = newPatientDateRange[i];
                        console.log(`added_on: ${item.added_on}, count: ${item.count}`);
                    }

                    // document.getElementById('newPatients').textContent = totalCount;
                    // document.getElementById('newPatientDtPkrRng').style.display = 'none';
                    // Handle the data as needed (e.g., update the UI)
                } else {
                    console.error('Error fetching data:', xmlhttp.status, xmlhttp.statusText);
                }
            }
        };
        xmlhttp.send(null);
    }




    function newPatientCount(buttonId) {
        document.getElementById('newPatientDtPkr').style.display = 'none';
        document.getElementById('newPatientDtPkrRng').style.display = 'none';

        if (buttonId === 'newPatientLst24hrs') {
            document.getElementById('newPatients').textContent = <?= $newPatientLast24Hours ?>;
        }
        if (buttonId === 'newPatientLst7') {
            document.getElementById('newPatients').textContent = <?= $newPatientLast7Days ?>;
        }
        if (buttonId === 'newPatientLst30') {
            document.getElementById('newPatients').textContent = <?= $newPatientLast30Days ?>;
        }
        if (buttonId === 'newPatientOnDt') {
            document.getElementById('newPatientDtPkr').style.display = 'block';
        }
        if (buttonId === 'newPatientDtRng') {
            document.getElementById('newPatientDtPkrRng').style.display = 'block';
        }
    }

    /// for line chart hover ////
    let myChart = document.getElementById('myChart');
    document.getElementById('chartButton').addEventListener('mouseenter', function() {
        document.getElementById('chartMenu').style.display = 'block';
    });

    document.getElementById('chartButton').addEventListener('mouseleave', function() {
        // document.getElementById('chartMenu').style.display = 'none';
        if (!myChart.matches(':hover')) {
            chartMenu.style.display = 'none';
        }
    });

    myChart.addEventListener('mouseleave', function() {
        chartMenu.style.display = 'none';
    });
    //end....///


    // /// for line chart ///
    const ctx = document.getElementById('myChart');
    const newPatients = <?php echo json_encode($newPatients); ?>;
    // console.log(newPatients.length);
    const labels = [];
    const data = [];

    if (newPatients) {
        newPatients.forEach(row => {
            labels.push(row.added_on);
            data.push(row.patient_count);
        });
    }

    new Chart(ctx, {
        type: 'line',
        data: {
            labels: labels,
            datasets: [{
                label: 'New Patients Count',
                data: data,
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