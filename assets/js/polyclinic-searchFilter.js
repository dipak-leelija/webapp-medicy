// ==========================================================
// ================= DATA FILTER ACTION AREA ================
// ==========================================================

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

// data fetch area
const filterAppointmentByValue = () => {
    var currentUrl = newUrl = window.location.origin + window.location.pathname;

    // custom range control
    document.getElementById('dtPickerDiv').style.display = 'none'; // date picker div control
    var customDateRangeFlag = document.getElementById('date-range-control-flag');
    let parameters = ''
    
    // filter data fetch area
    let searchKey = document.getElementById('appointment_search');  // search by input value
    let fIlter1 = document.getElementById('added_on');    // date filter
    let filter2 = document.getElementById('doctor_id');   // doctor filter
    let filter3 = document.getElementById('added_by');    // employee filter

    let dtValStart = document.getElementById('select-start-date');
    let dtValEnd = document.getElementById('select-end-date');
    let docIdVal = document.getElementById('select-docId');
    let empIdVal = document.getElementById('select-empId');

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
        parameters +=  `&search=${searchKey.value}`;
    }
    

    // date filter check and data control area 
    if(fIlter1.value != ''){

        if(fIlter1.value == 'T'){            // 'T' -> (date filter today)   
            startDate = currentDate;
            endDate = currentDate;
            
            customDateRangeFlag.innerHTML = '0';
            document.getElementById('dtPickerDiv').style.display = 'none';
        }

        if(fIlter1.value == 'Y'){            // 'Y' -> (date filter yeesterday)  
            startDate = calculateDate('1');
            endDate = currentDate;
            
            customDateRangeFlag.innerHTML = '0';
            document.getElementById('dtPickerDiv').style.display = 'none';
        }

        if(fIlter1.value == 'LW'){            // 'LW' -> (date filter last 7 days)  
            startDate = calculateDate('7');
            endDate = currentDate;
            
            customDateRangeFlag.innerHTML = '0';
            document.getElementById('dtPickerDiv').style.display = 'none';
        }

        if(fIlter1.value == 'LM'){            // 'LM' -> (date filter last 30 days)  
            startDate = calculateDate('30');
            endDate = currentDate;
            
            customDateRangeFlag.innerHTML = '0';
            document.getElementById('dtPickerDiv').style.display = 'none';
        }

        if(fIlter1.value == 'LQ'){            // 'LQ' -> (date filter last 90 days)  
            startDate = calculateDate('90');
            endDate = currentDate;
            
            customDateRangeFlag.innerHTML = '0';
            document.getElementById('dtPickerDiv').style.display = 'none';
        }

        if(fIlter1.value == 'CFY'){           // 'T' -> (date filter today)  
            let currentYr =  year;
            let fiscalYr = parseInt(year) + 1;
                     
            startDate = '01-04-'+currentYr;
            endDate = '31-03-'+fiscalYr;
            
            customDateRangeFlag.innerHTML = '0';
            document.getElementById('dtPickerDiv').style.display = 'none';
        }

        if(fIlter1.value == 'PFY'){            // 'T' -> (date filter today)  
            let fiscalYr = parseInt(year) - 1;

            startDate = '01-04-'+fiscalYr;
            endDate = '31-03-'+year;
            
            customDateRangeFlag.innerHTML = '0';
            document.getElementById('dtPickerDiv').style.display = 'none';
        }

        if(fIlter1.value == 'CR'){            // 'CR' -> (custom range)

            document.getElementById('dtPickerDiv').style.display = 'block';
            if(customDateRangeFlag.innerHTML == '1'){
                startDate = formatDate(document.getElementById('from-date').value);
                endDate = formatDate(document.getElementById('to-date').value);
            }
            customDateRangeFlag.innerHTML = '1';
        }

        dtValStart.innerHTML = startDate;
        dtValEnd.innerHTML = endDate;
    }
    if(dtValStart.innerHTML != ''){
        parameters +=  `&dateFilterStart=${dtValStart.innerHTML}&dateFilterEnd=${dtValEnd.innerHTML}`;
    }

    // doctor filter set
    if(filter2.value != ''){
        docIdVal.innerHTML = filter2.value;
        
    }
    if(docIdVal.innerHTML != ''){
        parameters +=  `&docIdFilter=${docIdVal.innerHTML}`;
    }

    // employee filter set
    if(filter3.value != ''){
        empIdVal.innerHTML = filter3.value;
    }
    if(empIdVal.innerHTML != ''){
        parameters +=  `&staffIdFilter=${empIdVal.innerHTML}`;
    }


    // update url
    var newUrl = `${currentUrl}?${parameters}`;
    window.location.replace(newUrl);
}


const checkResetFilter = ()=>{
    if(document.getElementById('appointment_search').value != ''){
        document.getElementById('appointment-search-reset-1').classList.remove('d-none');
    }else{
        document.getElementById('appointment-search-reset-1').classList.add('d-none');
    }
    if(document.getElementById('select-start-date').innerHTML != ''){
        document.getElementById('appointment-search-reset-2').classList.remove('d-none');
    }else{
        document.getElementById('appointment-search-reset-2').classList.add('d-none');
    }
    if(document.getElementById('select-docId').innerHTML != ''){
        document.getElementById('appointment-search-reset-3').classList.remove('d-none');
    }else{
        document.getElementById('appointment-search-reset-3').classList.add('d-none');
    }
    if(document.getElementById('select-empId').innerHTML != ''){
        document.getElementById('appointment-search-reset-4').classList.remove('d-none');
    }else{
        document.getElementById('appointment-search-reset-4').classList.add('d-none');
    }
}

// reset url function
const resteUrl = (thisId)=>{
    console.log(thisId);
    if(thisId == 'appointment-search-reset-1'){
        document.getElementById('appointment_search').value = ''; 
    }

    if(thisId == 'appointment-search-reset-2'){
        document.getElementById('select-start-date').innerHTML = '';
        document.getElementById('select-end-date').innerHTML = '';
    }

    if(thisId == 'appointment-search-reset-3'){
        document.getElementById('select-docId').innerHTML = '';
    }

    if(thisId == 'appointment-search-reset-4'){
        document.getElementById('select-empId').innerHTML = ''; 
    }

    filterAppointmentByValue();
    checkResetFilter();
}


checkResetFilter();