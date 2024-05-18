const xmlhttp = new XMLHttpRequest();

        const viewReturnItem = (invoice, id) => {
            let url = `ajax/viewSalesReturn.ajax.php?invoice=${invoice}&id=${id}`;
            xmlhttp.open("GET", url, false);
            xmlhttp.send(null);
            document.getElementById('viewReturnModalBody').innerHTML = xmlhttp.responseText
        }


        const editSalesReturn = (invoiceId, salesReturnId) => {
            let editUrl = `sales-return-edit.php?invoice=${invoiceId}&salesReturnId=${salesReturnId}`;
            window.location.href = editUrl;
        };


        const cancelSalesReturn = (t) => {

            cancelId = t.id;

            swal({
                    title: "Are you sure?",
                    text: "Do you really cancel theis transaction?",
                    icon: "warning",
                    buttons: true,
                    dangerMode: true,
                })
                .then((willDelete) => {
                    if (willDelete) {

                        $.ajax({
                            url: "ajax/salesReturnCancle.ajax.php?",
                            type: "POST",
                            data: {
                                id: cancelId
                            },
                            success: function(response) {
                                console.log(response);
                                if (response.includes('1')) {
                                    swal(
                                        "Canceled",
                                        "Transaction Has Been Canceled",
                                        "success"
                                    ).then(function() {
                                        $(t).closest("tr").css({
                                            "background-color": "red",
                                            "color": "white"
                                        });
                                        window.location.reload();
                                    });

                                } else {
                                    swal("Failed", "Transaction Deletion Failed!",
                                        "error");
                                    $("#error-message").html("Deletion Field !!!")
                                        .slideDown();
                                    $("success-message").slideUp();
                                }
                            }
                        });
                    }
                    return false;
                });
        }

//=======================================================================

// ============= date format controler ===============
function formatDate(dateString) {
    let dateParts = dateString.split('-');
    let year = dateParts[0];
    let month = dateParts[1];
    let day = dateParts[2];

    // Format day and month with leading zeros if necessary
    day = day.padStart(2, '0');
    month = month.padStart(2, '0');

    return `${day}-${month}-${year}`;
}

// minus date calculation
function calculateDate(days) {
    var currentDate = new Date();
    
    // Subtract days from current date
    var newDate = new Date(currentDate);
    newDate.setDate(currentDate.getDate() - parseInt(days));
    
    // Format the date as dd-mm-yyyy
    var formattedDate = ("0" + newDate.getDate()).slice(-2) + "-" + ("0" + (newDate.getMonth() + 1)).slice(-2) + "-" + newDate.getFullYear();
    
    return formattedDate;
}



function dateFilterCheck(filter, value){

    let filterId = filter.id;

    if(filter == 'from-date'){
        var date1 = document.getElementById('from-date').value;
        var date2 = document.getElementById('to-date').value;
        value = 'CR';
    }

    let date = new Date();
    let day = date.getDate();
    let month = '0'+(date.getMonth() + 1); // Adding 1 because January is 0
    let year = date.getFullYear();
    let currentDate = `${day}-${month}-${year}`;

    let startDate = '';
    let endDate = '';

    let customDateRangeFlag = document.getElementById('date-range-control-flag');
    let urlControlFlag = document.getElementById('url-control-flag');

    let itemFilterDtStart = document.getElementById('select-sales-start-date');
    let itemFilterDtEnd = document.getElementById('select-sales-end-date');

    let itemReturnStartDt = document.getElementById('select-sales-return-start-date');
    let itemReturnEndDt = document.getElementById('select-sales-return-end-date');
    
    if(value == 'T'){            // 'T' -> (date filter today)   
        startDate = currentDate;
        endDate = currentDate;
        
        customDateRangeFlag.innerHTML = '0';
        document.getElementById('dtPickerDiv').style.display = 'none';
    }
    
    if(value == 'Y'){            // 'Y' -> (date filter yeesterday)  
        startDate = calculateDate('1');
        endDate = currentDate;
        
        customDateRangeFlag.innerHTML = '0';
        document.getElementById('dtPickerDiv').style.display = 'none';
    }
    
    if(value == 'LW'){            // 'LW' -> (date filter last 7 days)  
        startDate = calculateDate('7');
        endDate = currentDate;
        
        customDateRangeFlag.innerHTML = '0';
        document.getElementById('dtPickerDiv').style.display = 'none';
    }
    
    if(value == 'LM'){            // 'LM' -> (date filter last 30 days)  
        startDate = calculateDate('30');
        endDate = currentDate;
        
        customDateRangeFlag.innerHTML = '0';
        document.getElementById('dtPickerDiv').style.display = 'none';
    }
    
    if(value == 'LQ'){            // 'LQ' -> (date filter last 90 days)  
        startDate = calculateDate('90');
        endDate = currentDate;
        
        customDateRangeFlag.innerHTML = '0';
        document.getElementById('dtPickerDiv').style.display = 'none';
    }
    
    if(value == 'CFY'){           // 'CFY' -> (date filter curremt fiscal year)  
        let currentYr =  year;
        let fiscalYr = parseInt(year) + 1;
                 
        startDate = '01-04-'+currentYr;
        endDate = '31-03-'+fiscalYr;
        
        customDateRangeFlag.innerHTML = '0';
        document.getElementById('dtPickerDiv').style.display = 'none';
    }
    
    if(value == 'PFY'){            // 'PFY' -> (date filter previous fiscal year)  
        let fiscalYr = parseInt(year) - 1;

        startDate = '01-04-'+fiscalYr;
        endDate = '31-03-'+year;
        
        customDateRangeFlag.innerHTML = '0';
        document.getElementById('dtPickerDiv').style.display = 'none';
    }

    if(value == 'CR'){ 
        document.getElementById('dtPickerDiv').style.display = 'block';
        if(customDateRangeFlag.innerHTML == '1'){
            startDate = formatDate(date1);
            endDate = formatDate(date2);
            urlControlFlag.innerHTML = '1';
        }
        customDateRangeFlag.innerHTML = '1';
    }


    if(filterId == 'added_on'){
        console.log('hello');
        // pharmacySearchFilter3();
    }

    if(filterId == 'sales-return-on'){
        console.log('hi');
        // pharmacySearchFilter3();
    }
        // itemFilterDtStart.innerHTML = startDate1;
        // itemFilterDtEnd.innerHTML = endDate1;

        // console.log();
       
        // return startDate;
        // return endDate;
}



const pharmacySearchFilter3 = () => {

    // current url path
    let currentUrl = newUrl = window.location.origin + window.location.pathname; // holding current location

    // custom range control
    document.getElementById('dtPickerDiv').style.display = 'none'; // date picker div control
    let customDateRangeFlag1 = document.getElementById('date-range-control-flag1');
    let urlControlFlag1 = document.getElementById('url-control-flag1');

    let customDateRangeFlag2 = document.getElementById('date-range-control-flag2');
    let urlControlFlag2 = document.getElementById('url-control-flag2');

    // let customDateRangeFlag2 = document.getElementById('date-range-control-flag2');
    // let urlControlFlag2 = document.getElementById('url-control-flag2');

    let parameters = ''
    
    // filter data fetch area
    let searchKey = document.getElementById('data-search');  // search by input value

    let filter1 = document.getElementById('added_on');  // sales or purchase date filter1
    let filter2 = document.getElementById('sales-return-on'); // sales or purchase date filter2
    
    let filter3 = document.getElementById('sales-return-processed-by');  // payment mode filter
    
    let itemFilterDtStart = document.getElementById('select-sales-start-date');
    let itemFilterDtEnd = document.getElementById('select-sales-end-date');

    let itemReturnStartDt = document.getElementById('select-sales-return-start-date');
    let itemReturnEndDt = document.getElementById('select-sales-return-end-date');

    let salesReturnBy = document.getElementById('return-processed-by');

    // date contorl area
    let date = new Date();
    let day = date.getDate();
    let month = '0'+(date.getMonth() + 1); // Adding 1 because January is 0
    let year = date.getFullYear();
    let currentDate = `${day}-${month}-${year}`;

    // search key check
    if(searchKey.value != ''){
        parameters +=  `&search=${searchKey.value}`;
    }


    // date filter 1
    if(filter1.value != ''){

        if(filter1.value == 'T'){            // 'T' -> (date filter today)   
            startDate1 = currentDate;
            endDate1 = currentDate;
            
            customDateRangeFlag1.innerHTML = '0';
            document.getElementById('dtPickerDiv').style.display = 'none';
        }

        if(filter1.value == 'Y'){            // 'Y' -> (date filter yeesterday)  
            startDate1 = calculateDate('1');
            endDate1 = currentDate;
            
            customDateRangeFlag1.innerHTML = '0';
            document.getElementById('dtPickerDiv').style.display = 'none';
        }

        if(filter1.value == 'LW'){            // 'LW' -> (date filter last 7 days)  
            startDate1 = calculateDate('7');
            endDate1 = currentDate;
            
            customDateRangeFlag1.innerHTML = '0';
            document.getElementById('dtPickerDiv').style.display = 'none';
        }

        if(filter1.value == 'LM'){            // 'LM' -> (date filter last 30 days)  
            startDate1 = calculateDate('30');
            endDate1 = currentDate;
            
            customDateRangeFlag1.innerHTML = '0';
            document.getElementById('dtPickerDiv').style.display = 'none';
        }

        if(filter1.value == 'LQ'){            // 'LQ' -> (date filter last 90 days)  
            startDate1 = calculateDate('90');
            endDate1 = currentDate;
            
            customDateRangeFlag1.innerHTML = '0';
            document.getElementById('dtPickerDiv').style.display = 'none';
        }

        if(filter1.value == 'CFY'){           // 'CFY' -> (date filter curremt fiscal year)  
            let currentYr =  year;
            let fiscalYr = parseInt(year) + 1;
                     
            startDate1 = '01-04-'+currentYr;
            endDate1 = '31-03-'+fiscalYr;
            
            customDateRangeFlag1.innerHTML = '0';
            document.getElementById('dtPickerDiv').style.display = 'none';
        }

        if(filter1.value == 'PFY'){            // 'PFY' -> (date filter previous fiscal year)  
            let fiscalYr = parseInt(year) - 1;

            startDate1 = '01-04-'+fiscalYr;
            endDate1 = '31-03-'+year;
            
            customDateRangeFlag1.innerHTML = '0';
            document.getElementById('dtPickerDiv').style.display = 'none';
        }

        if(filter1.value == 'CR'){                  // 'CR' -> (custom range)
            
            let fiscalYr = parseInt(year) - 1;

            startDate1 = '01-04-'+fiscalYr;
            endDate1 = '31-03-'+year;
        }

        itemFilterDtStart.innerHTML = startDate1;
        itemFilterDtEnd.innerHTML = endDate1;
    }
    if(itemFilterDtStart.innerHTML != ''){
        parameters +=  `&itemReturnStartDt=${itemFilterDtStart.innerHTML}&itemReturnEndDt=${itemFilterDtEnd.innerHTML}`;
    }
    

    // date filter 2
    if(filter2.value != ''){

        if(filter2.value == 'T'){            // 'T' -> (date filter today)   
            startDate2 = currentDate;
            endDate2 = currentDate;
            
            customDateRangeFlag.innerHTML = '0';
            document.getElementById('dtPickerDiv').style.display = 'none';
        }

        if(filter2.value == 'Y'){            // 'Y' -> (date filter yeesterday)  
            startDate2 = calculateDate('1');
            endDate2 = currentDate;
            
            customDateRangeFlag.innerHTML = '0';
            document.getElementById('dtPickerDiv').style.display = 'none';
        }

        if(filter2.value == 'LW'){            // 'LW' -> (date filter last 7 days)  
            startDate2 = calculateDate('7');
            endDate2 = currentDate;
            
            customDateRangeFlag.innerHTML = '0';
            document.getElementById('dtPickerDiv').style.display = 'none';
        }

        if(filter2.value == 'LM'){            // 'LM' -> (date filter last 30 days)  
            startDate2 = calculateDate('30');
            endDate2 = currentDate;
            
            customDateRangeFlag.innerHTML = '0';
            document.getElementById('dtPickerDiv').style.display = 'none';
        }

        if(filter2.value == 'LQ'){            // 'LQ' -> (date filter last 90 days)  
            startDate2 = calculateDate('90');
            endDate2 = currentDate;
            
            customDateRangeFlag.innerHTML = '0';
            document.getElementById('dtPickerDiv').style.display = 'none';
        }

        if(filter2.value == 'CFY'){           // 'CFY' -> (date filter curremt fiscal year)  
            let currentYr =  year;
            let fiscalYr = parseInt(year) + 1;
                     
            startDate2 = '01-04-'+currentYr;
            endDate2 = '31-03-'+fiscalYr;
            
            customDateRangeFlag.innerHTML = '0';
            document.getElementById('dtPickerDiv').style.display = 'none';
        }

        if(filter2.value == 'PFY'){            // 'PFY' -> (date filter previous fiscal year)  
            let fiscalYr = parseInt(year) - 1;

            startDate2 = '01-04-'+fiscalYr;
            endDate2 = '31-03-'+year;
            
            customDateRangeFlag.innerHTML = '0';
            document.getElementById('dtPickerDiv').style.display = 'none';
        }

        if(filter2.value == 'CR'){                  // 'CR' -> (custom range)
            
        }

        itemReturnStartDt.innerHTML = startDate2;
        itemReturnEndDt.innerHTML = endDate2;
    }
    if(itemReturnStartDt.innerHTML != ''){
        parameters +=  `&itemReturnStartDt=${itemReturnStartDt.innerHTML}&itemReturnEndDt=${itemReturnEndDt.innerHTML}`;
    }


    
    
    // returned by filter set
    if(filter3.value != ''){
        salesReturnBy.innerHTML = filter3.value;
    }
    if(salesReturnBy.innerHTML != ''){
        parameters +=  `&addedBy=${salesReturnBy.innerHTML}`;
    }
  
    console.log(parameters);

    if(urlControlFlag1.innerHTML == '1'){
        customDateRangeFlag.innerHTML = '0';
        urlControlFlag.innerHTML = '0';
        document.getElementById('dtPickerDiv').style.display = 'none';
    }

    // update url
    // if(customDateRangeFlag.innerHTML == '0'){
    //     var newUrl = `${currentUrl}?${parameters}`;
    //     window.location.replace(newUrl);
    // }
}



const checkResetFilter = ()=>{
    if(document.getElementById('data-search').value != ''){
        document.getElementById('filter-reset-1').classList.remove('d-none');
    }else{
        document.getElementById('filter-reset-1').classList.add('d-none');
    }
    if(document.getElementById('select-sales-start-date').innerHTML != ''){
        document.getElementById('filter-reset-2').classList.remove('d-none');
    }else{
        document.getElementById('filter-reset-2').classList.add('d-none');
    }
    if(document.getElementById('select-sales-return-start-date').innerHTML != ''){
        document.getElementById('filter-reset-3').classList.remove('d-none');
    }else{
        document.getElementById('filter-reset-3').classList.add('d-none');
    }
    if(document.getElementById('return-processed-by').innerHTML != ''){
        document.getElementById('filter-reset-4').classList.remove('d-none');
    }else{
        document.getElementById('filter-reset-4').classList.add('d-none');
    }
}


// reset url function
const resteUrl = (thisId)=>{
    if(thisId == 'filter-reset-1'){
        document.getElementById('data-search').value = ''; 
    }

    if(thisId == 'filter-reset-2'){
        document.getElementById('select-sales-start-date').innerHTML = '';
        document.getElementById('select-sales-end-date').innerHTML = '';
    }

    if(thisId == 'filter-reset-3'){
        document.getElementById('select-sales-return-start-date').innerHTML = '';
        document.getElementById('select-sales-return-end-date').innerHTML = '';
    }

    if(thisId == 'filter-reset-4'){
        document.getElementById('return-processed-by').innerHTML = '';
    }

    pharmacySearchFilter3();
    checkResetFilter();
}


checkResetFilter();