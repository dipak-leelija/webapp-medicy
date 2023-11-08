<?php

///initial total count ///
$newPatients = $Patients->newPatientCount($adminId);
$totalCount = 0;
foreach ($newPatients as $row) {
    $patientCount = $row->patient_count;
    $totalCount += $patientCount;
}

///24 hourse total count //
$newPatientLast24Hours   = $Patients->newPatientCountLast24Hours($adminId);
if (isset($newPatientLast24Hours) && is_array($newPatientLast24Hours)) {
    $totalCount24hrs = 0;
    foreach ($newPatientLast24Hours as $row) {
        $patientCount = $row->patient_count;
        $addedOn = $row->added_on;
        $totalCount24hrs += $patientCount;
    }
}
/// 7 days total count //
$newPatientLast7Days     = $Patients->newPatientCountLast7Days($adminId);
if (isset($newPatientLast7Days) && is_array($newPatientLast7Days)) {
    $totalCount7days = 0;
    foreach ($newPatientLast7Days as $row) {
        $patientCount = $row->patient_count;
        $addedOn = $row->added_on;
        $totalCount7days += $patientCount;
    }
}
/// 30 days total count //
$newPatientLast30Days    = $Patients->newPatientCountLast30Days($adminId);
if (isset($newPatientLast30Days) && is_array($newPatientLast30Days)) {
    $totalCount30days = 0;
    foreach ($newPatientLast30Days as $row) {
        $patientCount = $row->patient_count;
        $addedOn = $row->added_on;
        $totalCount30days += $patientCount;
    }
}

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
                <button class="dropdown-item" type="button" id="newPatientLst30"onclick="newPatientCount(this.id)">Last 30 DAYS</button>
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
    function newPatientDataOverride(patientOverrideData) {
        console.log(patientOverrideData);
        if (patientOverrideData) {
            newPatientchart.data.datasets[0].data = patientOverrideData.map(item => item.patient_count);
            newPatientchart.data.labels = patientOverrideData.map(item => item.added_on);
        }
    }

    function newPatientByDt() {
        var newPatientDt = document.getElementById('newPatientDt').value;
        var xhr = new XMLHttpRequest();
        newPatientDtUrl = `../admin/ajax/new-patient-count.ajax.php?newPatientDt=${newPatientDt}`;
        xhr.open("GET", newPatientDtUrl, false);
        xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        xhr.send(null);
        var newPatientDataByDate = xhr.responseText;
        console.log(newPatientDataByDate);
        document.getElementById('newPatients').innerHTML = newPatientDataByDate.patient_count;
        newPatientDataOverride(JSON.parse(newPatientDataByDate));

        // document.getElementById('newPatients').textContent = ;
        // document.getElementById('newPatientDtPkr').style.display = 'none';
    }

    /// find new patient by selected range ///
    function newPatientDateRange() {
        var newPatientStartDate = document.getElementById('newPatientStartDate').value;
        var newPatientEndDate = document.getElementById('newPatientEndDate').value;

        newPatientDtRngUrl = `../admin/ajax/new-patient-count.ajax.php?newPatientStartDate=${newPatientStartDate}&newPatientEndDate=${newPatientEndDate}`;
        xmlhttp.open("GET", newPatientDtRngUrl, false);
        xmlhttp.send(null);

        var newPatientDataByDateRange = JSON.parse(xmlhttp.responseText);
        // console.log(newPatientDataByDateRange);

        newPatientDataOverride(newPatientDataByDateRange);
    }



    /// selection button ////
    function newPatientCount(buttonId) {
        document.getElementById('newPatientDtPkr').style.display = 'none';
        document.getElementById('newPatientDtPkrRng').style.display = 'none';

        if (buttonId === 'newPatientLst24hrs') {
            document.getElementById('newPatients').textContent = <?= $totalCount24hrs ?>;
            newPatientDataOverride(<?= json_encode($newPatientLast24Hours) ?>);
        }
        if (buttonId === 'newPatientLst7') {
            document.getElementById('newPatients').textContent = <?= $totalCount7days ?>;
            newPatientDataOverride(<?= json_encode($newPatientLast7Days) ?>);
        }
        if (buttonId === 'newPatientLst30') {
            document.getElementById('newPatients').textContent = <?= $newPatientLast30Days ?>;
            newPatientDataOverride(<?= json_encode($newPatientLast30Days) ?>);
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
        if (!myChart.matches(':hover')) {
            chartMenu.style.display = 'none';
        }
    });
    myChart.addEventListener('mouseleave', function() {
        chartMenu.style.display = 'none';
    });
    //end....///


    // primary chart data =====

    const newPatients = <?php echo json_encode($newPatients); ?>;
    console.log(newPatients);
    if (newPatients) {
        var newPatientprimaryLabel = newPatients.map(item => item.added_on);
        var newPatientparimaryData = newPatients.map(item => item.patient_count);
    }
    // /// for line chart ///
    const newPatientCtx = document.getElementById('myChart');
    var newPatientchart = new Chart(newPatientCtx, {
        type: 'line',
        data: {
            labels: newPatientprimaryLabel,
            datasets: [{
                label: 'New Patients Count',
                data: newPatientparimaryData,
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