<?php

    require_once dirname(dirname(__DIR__)).'/config/constant.php';

    $includePath = get_include_path();

    $StockOut = new StockOut();

    $today = date("Y-m-d");
    // echo $today;

    $amount = 0;
    $itemsCount = 0;

    $data = $StockOut->stockOutDisplay($adminId);
    foreach($data as $data){
        $SoldDate = $data['added_on'];
        if($SoldDate == $today){
            $amount += $data['amount'];
            $itemsCount += $data['items'];
        }  
    }
    // echo "<br>Amount : $amount";
    // echo "<br>Items Count : $itemsCount";
?>

<div class="card border-left-info border-right-info h-100 py-2 pending_border animated--grow-in">
    <div class="d-flex justify-content-end px-2">
        <div id="datePickerDiv" style="display: none;">
            <input type="date" id="dateInput">
            <button class="btn btn-sm btn-primary" id="added_on" value="CR" onclick="getDates(this.value)" style="height: 2rem;">Find</button>
        </div>
        <div class="btn-group">
            <button type="button" class="btn btn-sm btn-outline-light text-dark card-btn dropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                <!-- <img src=" IMG_PATH./arrow-down-sign-to-navigate.jpg" alt=""> -->
                
                <b>...</b>
            </button>
            <div class="dropdown-menu dropdown-menu-right">
                <button class="dropdown-item" type="button" id="lst7">Last 7 Days</button>
                <button class="dropdown-item" type="button" id="lst30">Last 1 Month</button>
                <button class="dropdown-item" type="button" id="lstdt">By Date</button>
                <button class="dropdown-item" type="button" id="reset">Reset</button>
            </div>
        </div>
    </div>
    <div class="card-body pb-0">
        <div class="row no-gutters align-items-center">
            <div class="col mr-2">
                <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                    sales of the day</div>
                <div class="h5 mb-0 font-weight-bold text-gray-800">
                    <label type="symble" id="rupeeSymble" name="rupeeSymble">₹</label>
                    <label type="text" id="salesAmount" name="salesAmount"><?php echo $amount ?></label>
                </div>
                <label type="text" id="itemsCount" name="itemsCount"><small><?php echo $itemsCount ?></small></label>
                <label type="text"><small>Items</small></label>
            </div>
            <div class="col-auto">
                <i class="fas fa-rupee-sign"></i>
            </div>
        </div>
    </div>
</div>

<!-- <script>

    const chkDays = (id) => {
        var xmlhttp = new XMLHttpRequest();
        // alert(id);

        if (id == 'lst7') {
            // alert(id);
            lastThirtyDaysUrl = 'components/partials_ajax/salesoftheDay.ajax.php?lstWeek=' + id;
            // alert(unitUrl);
            xmlhttp.open("GET", lastThirtyDaysUrl, false);
            xmlhttp.send(null);
            // console.log(xmlhttp.responseText);
            document.getElementById("salesAmount").innerHTML = xmlhttp.responseText;
            document.getElementById("itemsCount").innerHTML = xmlhttp.responseText;
        }

        if (id == 'lst30') {
            // alert(id);
            lastThirtyDaysUrl = 'components/partials_ajax/salesoftheDay.ajax.php?lstMnth=' + id;
            // alert(unitUrl);
            xmlhttp.open("GET", lastThirtyDaysUrl, false);
            xmlhttp.send(null);
            // console.log(xmlhttp.responseText);
            document.getElementById("salesAmount").innerHTML = xmlhttp.responseText;
            document.getElementById("itemsCount").innerHTML = xmlhttp.responseText;
        }

        if (id == 'lstdt') {
            // alert(id);
            const dateInput = document.getElementById('datePickerDiv');
            dateInput.style.display = 'block';
            dateInput.focus();
        }
    }
</script> -->