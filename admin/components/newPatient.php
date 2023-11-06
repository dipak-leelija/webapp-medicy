<?php

$newPatients             = $Patients->newPatientCount($adminId);
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
            <label style="margin-bottom: 0px;"><small>Start Date</small></label>&nbsp; &nbsp;  <label style="margin-bottom: 0px;"><small>End Date</small></label><br>
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
                    <span id="newPatients"><?= ($newPatients) ? $newPatients : 'No Data Found' ?> </span>
                </div>
            </div>
        </div>
    </div>
    <div class="d-flex justify-content-end "> 
        <button class="btn btn-outline-light card-btn ">..</button>
    </div>
</div>

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
                    // Update the 'newPatients' element with the response from the server
                    document.getElementById('newPatients').textContent = xhr.responseText;
                    document.getElementById('newPatientDtPkr').style.display = 'none';
                }
            }
        };
        xhr.send('newPatientDt=' + encodeURIComponent(newPatientDt));
    }

    /// find new patient by selected range ///
    function newPatientDateRange() {
        var newPatientStartDate = document.getElementById('newPatientStartDate').value;
        var newPatientEndDate = document.getElementById('newPatientEndDate').value;

        // Create a FormData object to send the data in a POST request
        var formData = new FormData();
        formData.append('newPatientStartDate', newPatientStartDate);
        formData.append('newPatientEndDate', newPatientEndDate);

        var xhr = new XMLHttpRequest();
        xhr.open('POST', '../admin/ajax/new-patient-count.ajax.php', true);
        xhr.onreadystatechange = function() {
            if (xhr.readyState === XMLHttpRequest.DONE) {
                if (xhr.status === 200) {
                    document.getElementById('newPatients').textContent = xhr.responseText;
                    document.getElementById('newPatientDtPkrRng').style.display = 'none';
                }
            }
        };
        xhr.send(formData);
    }



    function newPatientCount(buttonId) {
        document.getElementById('newPatientDtPkr').style.display = 'none';
        document.getElementById('newPatientDtPkrRng').style.display = 'none';
        switch (buttonId) {
            case 'newPatientLst24hrs':
                document.getElementById('newPatients').textContent = <?= $newPatientLast24Hours ?>;
                break;
            case 'newPatientLst7':
                document.getElementById('newPatients').textContent = <?= $newPatientLast7Days ?>;
                break;
            case 'newPatientLst30':
                document.getElementById('newPatients').textContent = <?= $newPatientLast30Days ?>;
                break;
            case 'newPatientOnDt':
                document.getElementById('newPatientDtPkr').style.display = 'block';
                break;
            case 'newPatientDtRng':
                document.getElementById('newPatientDtPkrRng').style.display = 'block';
                break;
            default:
                document.getElementById('newPatients').textContent = <?= $newPatients   ?>;
                break;
        }
    }





    // /// for line chart ///
    // const ctx = document.getElementById('myChart');
    // const labels = ['Red', 'Blue', 'Yellow', 'Green', 'Purple', 'Orange'];
    // new Chart(ctx, {
    //     type: 'line',
    //     data: {
    //         labels: labels,
    //         datasets: [{
    //             label: '# of Votes',
    //             data: [65, 59, 80, 81, 56, 55, 40],
    //             borderColor: 'rgb(75, 192, 192)',
    //             tension: 0.1
    //         }]
    //     }
    // });
</script>