
<div class="card border-left-primary shadow h-100 py-2 pending_border animated--grow-in">
    <div class="row mt-3">
        <div class="col-md-5 ml-3">
            <div class="text-xs font-weight-bold text-info text-uppercase mb-1" id="sold-items-header" style="display: block;">
                Most sold 10 items
            </div>
        </div>
        <div class="col-md-3">
            <div class="dropdown-menu dropdown-menu-right p-3 mt-n5" id="soldItemDtPickerDiv" style="display: none; margin-right:1rem;">
                <input type="date" id="soldDateInput">
                <button class="btn btn-sm btn-primary" id="sold-item-date-fetch" onclick="soldItemDataFilter(this.id)" style="height: 2rem;">Find</button>
            </div>
            <div class="dropdown-menu dropdown-menu-right p-3 mt-n5" id="soldItemDtRngPickerDiv" style="display: none; margin-right:1rem; ">
                <label>Start Date</label>
                <input type="date" id="soldStarDate">
                <label>End Date</label>
                <input type="date" id="soldEndDate">
                <button class="btn btn-sm btn-primary" id="sold-item-date-range-fetch" onclick="soldItemDataFilter(this.id)" style="height: 2rem;">Find</button>
            </div>

        </div>
        <div class="col-md-3 d-flex justify-content-end">
            <div class="mr-2">
                <label id='sold-item-data-sort' class="d-none">asc</label>
                <button type="button" class="btn btn-sm btn-outline-primary card-btn dropdown font-weight-bold" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                    <i class="fas fa-sort"></i> Sort
                </button>

                <div class="dropdown-menu dropdown-menu-right" style="background-color: rgba(200, 200, 200, 0.3);">
                    <button class="dropdown-item  dropdown" type="button" id="asc" value="asc" onclick="soldItemDataFilter(this.id)">Ascending</button>
                    <button class="dropdown-item  dropdown" type="button" id="dsc" value="dsc" onclick="soldItemDataFilter(this.id)">Descending</button>
                </div>
            </div>

            <div class="btn-group">
                    <lebel class="d-none" id="dateFilterStartDate">allData</lebel>
                    <lebel class="d-none" id="dateFilterEndDate">allData</lebel>
                <button type="button" class="btn btn-sm btn-outline-primary card-btn dropdown font-weight-bold" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                    <i class="fas fa-filter"></i>
                </button>

                <div class="dropdown-menu dropdown-menu-right" style="background-color: rgba(255, 255, 255, 0.8);">
                    <button class="dropdown-item" type="button" id="soldLst24hrs" onclick="soldItemDataFilter(this.id)">Last 24 hrs</button>
                    <button class="dropdown-item" type="button" id="soldLst7" onclick="soldItemDataFilter(this.id)">Last 7 Days</button>
                    <button class="dropdown-item" type="button" id="soldLst30" onclick="soldItemDataFilter(this.id)">Last 30 DAYS</button>
                    <button class="dropdown-item  dropdown" type="button" id="soldOnDt" onclick="soldItemDataFilter(this.id)">By Date</button>
                    <button class="dropdown-item  dropdown" type="button" id="soldOnDtRng" onclick="soldItemDataFilter(this.id)">By Range</button>
                </div>
            </div>
        </div>
    </div>

    <div class="card-body mt-n2 pb-0">
        <div class="row no-gutters align-items-center">
            <div style="width: 100%; margin: 0 auto;" id='soldItemsChartDiv'>
                <canvas id="soldItemsChart"></canvas>
            </div>
            <!-- <div style="width: 100%; margin: 0 auto;" id='solditemNDFDiv'>
                <p class="text-warning">Oops!, the requested data isn't in our records.</p>
            </div> -->
        </div>
    </div>
</div>

<script src="<?php echo PLUGIN_PATH; ?>chartjs-4.4.0/updatedChart.js"></script>

<script>
    let soldItemsDataChart;

    // upload chart data
    const soldItemsDataChartShow = (soldItemsDataArray)=>{
        soldItemsDataChart.data.labels = Object.keys(soldItemsDataArray);
        soldItemsDataChart.data.datasets[0].data = Object.values(soldItemsDataArray);

        soldItemsDataChart.update();
    }

    // reset chart on no data
    const soldItemsDataChartReset = ()=>{
        soldItemsDataChart.data.labels = [];
        soldItemsDataChart.data.datasets[0].data = [];
        soldItemsDataChart.options.scales.y.max = 1;
        soldItemsDataChart.options.scales.y.min = -1;
        soldItemsDataChart.update();
    }

    // data fetch on filter
    const soldItemDataFetch = (sortVal, startDt, endDt)=>{
        soldItemDataFetchUrl = `<?php echo URL ?>ajax/sold-items-data-fetch.ajax.php?sortVal=${sortVal}&startDt=${startDt}&endDt=${endDt}`;
        xmlhttp.open("GET", soldItemDataFetchUrl, false);
        xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        xmlhttp.send(null);
        var soldItemsData = JSON.parse(xmlhttp.responseText);
        
        if(soldItemsData.status == '1'){
            soldItemsDataChartShow(soldItemsData.data);
        }else{
            soldItemsDataChartReset();
        }
    }


    const soldItemDataFilter = (id)=>{
        let itemDataSort = document.getElementById('sold-item-data-sort');
        let startDateFilter = document.getElementById('dateFilterStartDate');
        let endDatefilter = document.getElementById('dateFilterEndDate');
        
        if(id == 'asc' || id == 'dsc'){
            itemDataSort.innerHTML = id;
            soldItemDataFetch(itemDataSort.innerHTML, startDateFilter.innerHTML, endDatefilter.innerHTML);
            document.getElementById('soldItemDtPickerDiv').style.display = 'none';
            document.getElementById('soldItemDtRngPickerDiv').style.display = 'none';
            
            if(itemDataSort.innerHTML == 'asc'){
                document.getElementById('sold-items-header').innerHTML = 'Most sold 10 items';
            }

            if(itemDataSort.innerHTML == 'dsc'){
                document.getElementById('sold-items-header').innerHTML = 'Less sold 10 items';
            }
        }
       
        if(id == 'allData' || id == 'soldLst24hrs'){
            startDateFilter.innerHTML = id;
            endDatefilter.innerHTML = id;
            soldItemDataFetch(itemDataSort.innerHTML, startDateFilter.innerHTML, endDatefilter.innerHTML);
            document.getElementById('soldItemDtPickerDiv').style.display = 'none';
            document.getElementById('soldItemDtRngPickerDiv').style.display = 'none';
        }
        
        if(id == 'soldLst7'){
            startDateFilter.innerHTML = '<?php echo $before7day; ?>';
            endDatefilter.innerHTML = '<?php echo $today; ?>';
            soldItemDataFetch(itemDataSort.innerHTML, startDateFilter.innerHTML, endDatefilter.innerHTML);
            document.getElementById('soldItemDtPickerDiv').style.display = 'none';
            document.getElementById('soldItemDtRngPickerDiv').style.display = 'none';
        }

        if(id == 'soldLst30'){
            startDateFilter.innerHTML = '<?php echo $before30day; ?>';
            endDatefilter.innerHTML = '<?php echo $today; ?>';
            soldItemDataFetch(itemDataSort.innerHTML, startDateFilter.innerHTML, endDatefilter.innerHTML);
            document.getElementById('soldItemDtPickerDiv').style.display = 'none';
            document.getElementById('soldItemDtRngPickerDiv').style.display = 'none';
        }

        if(id == 'soldOnDt'){
            document.getElementById('soldItemDtPickerDiv').style.display = 'block';
            document.getElementById('soldItemDtRngPickerDiv').style.display = 'none';
        }

        if(id == 'soldOnDtRng'){
            document.getElementById('soldItemDtPickerDiv').style.display = 'none';
            document.getElementById('soldItemDtRngPickerDiv').style.display = 'block';
        }

        if(id == 'sold-item-date-fetch'){
            var filterDt = document.getElementById('soldDateInput').value;
            startDateFilter.innerHTML = filterDt;
            endDatefilter.innerHTML = filterDt;
            soldItemDataFetch(itemDataSort.innerHTML, filterDt, filterDt);
            document.getElementById('soldItemDtPickerDiv').style.display = 'none';
            document.getElementById('soldItemDtRngPickerDiv').style.display = 'none';
        }

        if(id == 'sold-item-date-range-fetch'){
            var filterStartDt = document.getElementById('soldStarDate').value;
            var filterEndDt = document.getElementById('soldEndDate').value;
            startDateFilter.innerHTML = filterStartDt;
            endDatefilter.innerHTML = filterEndDt;
            soldItemDataFetch(itemDataSort.innerHTML, filterStartDt, filterEndDt);
            document.getElementById('soldItemDtPickerDiv').style.display = 'none';
            document.getElementById('soldItemDtRngPickerDiv').style.display = 'none';
        }
    }


    // ============ sold item chart ============
    soldItemsDataChart = new Chart(document.getElementById('soldItemsChart').getContext('2d'), {
        type: 'bar',
        data: {
            labels: [],
            datasets: [{
                label: "Total Sold",
                data: [],
                borderWidth: 0,
                backgroundColor: 'rgb(179, 217, 255)',
                minBarThickness: 2,
                maxBarThickness: 15,
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


    soldItemDataFilter('allData');
</script>