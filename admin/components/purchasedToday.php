<?php
require_once dirname(dirname(__DIR__)) . '/config/constant.php';

$includePath = get_include_path();

$podStrtDt = date('Y-m-d');
$podLst24hrs = date('Y-m-d', strtotime($strtDt . ' - 1 days'));
$podLst7 = date('Y-m-d', strtotime($strtDt . ' - 7 days'));
$podLst30 = date('Y-m-d', strtotime($strtDt . ' - 30 days'));

$purchaeTodayCurrentData = $StockIn->purchaseTodayByDateRange($podStrtDt, $podStrtDt, $adminId);

$purchaeTodayDataLst24hrs = $StockIn->purchaseTodayByDateRange($podLst24hrs, $podStrtDt, $adminId);

$purchaeTodayDataLst7dys = $StockIn->purchaseTodayByDateRange($podLst7, $podStrtDt, $adminId);

$purchaeTodayDataLst30dys = $StockIn->purchaseTodayByDateRange($podLst30, $podStrtDt, $adminId);

// print_r($purchaeTodayDataLst30dys);
?>

<div class="card border-left-info h-100 py-2 pending_border animated--grow-in">
    <div class="d-flex justify-content-end px-2">
        <div id="podDatePikDiv" style="display: none; margin-right:1rem;">
            <input type="date" id="salesOfTheDayDate">
            <button class="btn btn-sm btn-primary" onclick="sodOnDate()" style="height: 2rem;">Find</button>
        </div>
        <div id="podDtPikRngDiv" style="display: none; margin-right:1rem;">
            <div class="d-flex d-flex justify-content-start">
                <div>
                    <label>Start Date</label>&nbsp<input type="date" id="sodStartDt"><br>
                    <label>End Date</label>&nbsp&nbsp&nbsp<input type="date" id="sodEndDt">
                </div>&nbsp
                <div>
                    <br>
                    <button class="btn btn-sm btn-primary" onclick="sodDtRange()" style="height: 2rem;">Find</button>
                </div>
            </div>
        </div>
        <div class="btn-group">
            <button type="button" class="btn btn-sm btn-outline-light text-dark card-btn dropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                ...
            </button>
            <div class="dropdown-menu dropdown-menu-right">
                <button class="dropdown-item" type="button" id="podCurrentDt" onclick="chkPod(this.id)">Today</button>
                <button class="dropdown-item" type="button" id="podLst24hrs" onclick="chkPod(this.id)">Last 24 hrs</button>
                <button class="dropdown-item" type="button" id="podLst7" onclick="chkPod(this.id)">Last 7 Days</button>
                <button class="dropdown-item" type="button" id="podLst30" onclick="chkPod(this.id)">Last 30 Days</button>
                <button class="dropdown-item" type="button" id="podGvnDt" onclick="chkPod(this.id)">By Date</button>
                <button class="dropdown-item" type="button" id="podDtRng" onclick="chkPod(this.id)">By Date Range</button>
            </div>
        </div>
    </div>
    <div class="card-body pb-0">
        <div class="row no-gutters align-items-center">
            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                Purchased today
            </div>
        </div>
        <div class="row no-gutters align-items-center">
            <div class="col mr-2" id="pod-data-div" style="display: none;">
                <div class="h5 mb-0 font-weight-bold text-gray-800">
                    <div>
                        <label type="symble" id="rupeeSymble" name="rupeeSymble">₹</label>
                        <label type="text" id="podAmount"></label>
                    </div>
                    <div>
                        <label type="text" id="podItemsCount" name="itemsCount"><small></small></label>
                        <label type="text"><small>Items</small></label>
                    </div>
                </div>
            </div>
            <div class="col-auto" id="pod-no-data-found-div" style="display: none;">
                <label>NO DATA FOUND</label>
            </div>
        </div>
    </div>
</div>



<script>
    // function updatePod(uploadPodData) {

    //     if (uploadPodData.purchase_amount != null && uploadPodData.purchase_item_count != null) {
    //         document.getElementById('salesAmount').innerHTML = uploadPodData.purchase_amount;
    //         document.getElementById('itemsCount').innerHTML = uploadPodData.purchase_item_count;

    //         document.getElementById('sod-Display').style.display = 'block';
    //         document.getElementById('sod-no-data').style.display = 'none';

    //     } else {
    //         console.log('null');
    //         document.getElementById('sod-Display').style.display = 'none';
    //         document.getElementById('sod-no-data').style.display = 'block';
    //     }
    // }




    // // === fixed sod date select from calander ...
    // function sodOnDate() {
    //     let sodDateSelect = document.getElementById('salesOfTheDayDate').value;

    //     var xmlhttp = new XMLHttpRequest();
    //     var sodOnDateUrl = `ajax/sod-pod-data-search.ajax.php?sodONDate=${sodDateSelect}`;
    //     xmlhttp.open('GET', sodOnDateUrl, false);
    //     xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    //     xmlhttp.send(null);

    //     updateSod(JSON.parse(xmlhttp.responseText));
    // }





    // // === sod date range select from calander ...
    // function sodDtRange() {
    //     let sodStartDate = document.getElementById('sodStartDt').value;
    //     let sodEndDate = document.getElementById('sodEndDt').value;

    //     var xmlhttp = new XMLHttpRequest();
    //     var sodOnDateUrl = `ajax/sod-pod-data-search.ajax.php?sodStartDate=${sodStartDate}&sodEndDate=${sodEndDate}`;
    //     xmlhttp.open('GET', sodOnDateUrl, false);
    //     xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    //     xmlhttp.send(null);

    //     updateSod(JSON.parse(xmlhttp.responseText));
    // }






    /// ===== button on click function for sod date select 
    function chkPod(id) {

        if (id == 'podCurrentDt') {
            document.getElementById('podDatePikDiv').style.display = 'none';
            document.getElementById('podDtPikRngDiv').style.display = 'none';
            // updatePod(<?php echo json_encode($purchaeTodayCurrentData) ?>);

        }

        if (id == 'podLst24hrs') {
            document.getElementById('podDatePikDiv').style.display = 'none';
            document.getElementById('podDtPikRngDiv').style.display = 'none';
            // updatePod(<?php echo json_encode($purchaeTodayDataLst24hrs) ?>);
        }

        if (id == 'podLst7') {
            document.getElementById('podDatePikDiv').style.display = 'none';
            document.getElementById('podDtPikRngDiv').style.display = 'none';
            // updatePod(<?php echo json_encode($purchaeTodayDataLst7dys) ?>);
        }

        if (id == 'podLst30') {
            document.getElementById('podDatePikDiv').style.display = 'none';
            document.getElementById('podDtPikRngDiv').style.display = 'none';
            // updatePod(<?php echo json_encode($purchaeTodayDataLst30dys) ?>);
        }

        if (id == 'podGvnDt') {
            document.getElementById('podDatePikDiv').style.display = 'block';
            document.getElementById('podDtPikRngDiv').style.display = 'none';
        }

        if (id == 'podDtRng') {
            document.getElementById('podDatePikDiv').style.display = 'none';
            document.getElementById('podDtPikRngDiv').style.display = 'block';
        }
    }



    // =====initail sod data set set in sod card =====
    function podDefalultdData(podInitialData) {
        
        if (podInitialData.purchase_amount != null && podInitialData.purchase_item_count != null) {
            document.getElementById('podAmount').innerHTML = podInitialData.purchase_amount;
            document.getElementById('podItemsCount').innerHTML = podInitialData.purchase_item_count;

            document.getElementById('pod-data-div').style.display = 'block';
            document.getElementById('pod-no-data-found-div').style.display = 'none';
        } else {
            document.getElementById('pod-data-div').style.display = 'none';
            document.getElementById('pod-no-data-found-div').style.display = 'block';
        }
    }

    /// window onload function for initail sod data set....
    window.onload = podDefalultdData(<?php echo json_encode($purchaeTodayCurrentData) ?>);
</script>