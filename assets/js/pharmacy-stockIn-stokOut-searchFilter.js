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



const pharmacySearchFilter1 = () => {

    // current url path
    let currentUrl = newUrl = window.location.origin + window.location.pathname; // holding current location

    // custom range control
    document.getElementById('dtPickerDiv').style.display = 'none'; // date picker div control
    let customDateRangeFlag = document.getElementById('date-range-control-flag');
    let urlControlFlag = document.getElementById('url-control-flag');
    let parameters = ''
    
    // filter data fetch area
    let searchKey = document.getElementById('data-search');  // search by input value
    let filter = document.getElementById('added_on');       // sales or purchase date filter
    let filter2 = document.getElementById('payment_mode');  // payment mode filter
    console.log(filter2.value);

    let itemFilterDtStart = document.getElementById('select-start-date');
    let itemFilterDtEnd = document.getElementById('select-end-date');

    let paymentModeVal = document.getElementById('select-payment-mode');

    // date contorl area
    let date = new Date();
    let day = date.getDate();
    let month = '0'+(date.getMonth() + 1); // Adding 1 because January is 0
    let year = date.getFullYear();
    let currentDate = `${day}-${month}-${year}`;

    let startDate = '';
    let endDate = '';

    // search key check
    if(searchKey.value != ''){

        // ajax code for 

        parameters +=  `&search=${searchKey.value}`;
    }

    // date filter check and data control area 
    if(filter.value != ''){

        if(filter.value == 'T'){            // 'T' -> (date filter today)   
            startDate = currentDate;
            endDate = currentDate;
            
            customDateRangeFlag.innerHTML = '0';
            document.getElementById('dtPickerDiv').style.display = 'none';
        }

        if(filter.value == 'Y'){            // 'Y' -> (date filter yeesterday)  
            startDate = calculateDate('1');
            endDate = currentDate;
            
            customDateRangeFlag.innerHTML = '0';
            document.getElementById('dtPickerDiv').style.display = 'none';
        }

        if(filter.value == 'LW'){            // 'LW' -> (date filter last 7 days)  
            startDate = calculateDate('7');
            endDate = currentDate;
            
            customDateRangeFlag.innerHTML = '0';
            document.getElementById('dtPickerDiv').style.display = 'none';
        }

        if(filter.value == 'LM'){            // 'LM' -> (date filter last 30 days)  
            startDate = calculateDate('30');
            endDate = currentDate;
            
            customDateRangeFlag.innerHTML = '0';
            document.getElementById('dtPickerDiv').style.display = 'none';
        }

        if(filter.value == 'LQ'){            // 'LQ' -> (date filter last 90 days)  
            startDate = calculateDate('90');
            endDate = currentDate;
            
            customDateRangeFlag.innerHTML = '0';
            document.getElementById('dtPickerDiv').style.display = 'none';
        }

        if(filter.value == 'CFY'){           // 'CFY' -> (date filter curremt fiscal year)  
            let currentYr =  year;
            let fiscalYr = parseInt(year) + 1;
                     
            startDate = '01-04-'+currentYr;
            endDate = '31-03-'+fiscalYr;
            
            customDateRangeFlag.innerHTML = '0';
            document.getElementById('dtPickerDiv').style.display = 'none';
        }

        if(filter.value == 'PFY'){            // 'PFY' -> (date filter previous fiscal year)  
            let fiscalYr = parseInt(year) - 1;

            startDate = '01-04-'+fiscalYr;
            endDate = '31-03-'+year;
            
            customDateRangeFlag.innerHTML = '0';
            document.getElementById('dtPickerDiv').style.display = 'none';
        }

        if(filter.value == 'CR'){            // 'CR' -> (custom range)
            document.getElementById('dtPickerDiv').style.display = 'block';
            if(customDateRangeFlag.innerHTML == '1'){
                startDate = formatDate(document.getElementById('from-date').value);
                endDate = formatDate(document.getElementById('to-date').value);
                urlControlFlag.innerHTML = '1';
            }
            customDateRangeFlag.innerHTML = '1';
        }

        itemFilterDtStart.innerHTML = startDate;
        itemFilterDtEnd.innerHTML = endDate;
    }
    if(itemFilterDtStart.innerHTML != ''){
        parameters +=  `&dateFilterStart=${itemFilterDtStart.innerHTML}&dateFilterEnd=${itemFilterDtEnd.innerHTML}`;
    }


    // doctor filter set
    if(filter2.value != ''){
        paymentModeVal.innerHTML = filter2.value;
    }
    if(paymentModeVal.innerHTML != ''){
        parameters +=  `&paymentMode=${paymentModeVal.innerHTML}`;
    }
  
    console.log(parameters);

    if(urlControlFlag.innerHTML == '1'){
        customDateRangeFlag.innerHTML = '0';
        urlControlFlag.innerHTML = '0';
        document.getElementById('dtPickerDiv').style.display = 'none';
    }

    // update url
    if(customDateRangeFlag.innerHTML == '0'){
        var newUrl = `${currentUrl}?${parameters}`;
        window.location.replace(newUrl);
    }
}




const checkResetFilter = ()=>{
    if(document.getElementById('data-search').value != ''){
        document.getElementById('filter-reset-1').classList.remove('d-none');
    }else{
        document.getElementById('filter-reset-1').classList.add('d-none');
    }
    if(document.getElementById('select-start-date').innerHTML != ''){
        document.getElementById('filter-reset-2').classList.remove('d-none');
    }else{
        document.getElementById('filter-reset-2').classList.add('d-none');
    }
    if(document.getElementById('select-payment-mode').innerHTML != ''){
        document.getElementById('filter-reset-3').classList.remove('d-none');
    }else{
        document.getElementById('filter-reset-3').classList.add('d-none');
    }
}

// reset url function
const resteUrl = (thisId)=>{
    if(thisId == 'filter-reset-1'){
        document.getElementById('search-by-id-name-contact').value = ''; 
    }

    if(thisId == 'filter-reset-2'){
        document.getElementById('select-start-date').innerHTML = '';
        document.getElementById('select-end-date').innerHTML = '';
    }

    if(thisId == 'filter-reset-3'){
        document.getElementById('select-payment-mode').innerHTML = '';
    }

    pharmacySearchFilter1();
    checkResetFilter();
}


checkResetFilter();