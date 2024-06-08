// const itemSearchFilter = document.getElementById('search-by-item-name');
const dateFilterVal = document.getElementById('dt-fltr-val');
const distributorSelect = document.getElementById('selected-dist-id');
const purchseTypeSelect = document.getElementById('selected-purchse-type');

const selectedStartDate = document.getElementById('select-start-date');
const selectedEndDate = document.getElementById('select-end-date');


/////////////////////// date contorl function \\\\\\\\\\\\\\\\\\\\\\\\
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


////// date select function 
function filterDate(t){

    let date = new Date();
    let day = date.getDate();
    let month = '0'+(date.getMonth() + 1); // Adding 1 because January is 0
    let year = date.getFullYear();
    let currentDate = `${day}-${month}-${year}`;

    if(t.value == 'CR'){
        document.getElementById('dtPickerDiv').style.display = 'block';
        dateFilterVal.innerHTML = t.value;
    }else{
        document.getElementById('dtPickerDiv').style.display = 'none';

        if(t.value == 'T'){
            selectedStartDate.innerHTML = currentDate;
            selectedEndDate.innerHTML = currentDate;
        }

        if(t.value == 'Y'){
            selectedStartDate.innerHTML = calculateDate('1');;
            selectedEndDate.innerHTML = currentDate;
        }

        if(t.value == 'LW'){
            selectedStartDate.innerHTML = calculateDate('7');;
            selectedEndDate.innerHTML = currentDate;
        }

        if(t.value == 'LM'){
            selectedStartDate.innerHTML = calculateDate('30');;
            selectedEndDate.innerHTML = currentDate;
        }

        if(t.value == 'LQ'){
            selectedStartDate.innerHTML = calculateDate('90');;
            selectedEndDate.innerHTML = currentDate;
        }

        if(t.value == 'CFY'){
            let currentYr =  year;
            let fiscalYr = parseInt(year) + 1;

            selectedStartDate.innerHTML = '01-04-'+currentYr;
            selectedEndDate.innerHTML = '31-03-'+fiscalYr;
        }

        if(t.value == 'PFY'){
            let fiscalYr = parseInt(year) - 1;

            selectedStartDate.innerHTML = '01-04-'+fiscalYr;
            selectedEndDate.innerHTML = '31-03-'+year;
        }

        dateFilterVal.innerHTML = t.value;
    }
}


function selectStartDate(t){
    selectedStartDate.innerHTML = t.value;
}

function selectEndDate(t){
    selectedEndDate.innerHTML = t.value;
}



////// distributor select function
function filterDistributor(t){
    distributorSelect.innerHTML = t.value;
}



////// purchse type select function
function filterPurchaseType(t){
    purchseTypeSelect.innerHTML = t.value;
}



////// filter search go
function filterSearch(){
    // let itemSearchValue = itemSearchFilter.value;
    let filterStartDate = '';
    let filterEndDate = '';
    let distributorId = '';
    let purchaseType = '';

    if(dateFilterVal.innerHTML != 'A'){
        filterStartDate = selectedStartDate.innerHTML;
        filterEndDate = selectedEndDate.innerHTML;        
    }

    if(distributorSelect.innerHTML != 'AD'){
        distributorId = distributorSelect.innerHTML;
    }

    if(purchseTypeSelect.innerHTML === 'C'){
        purchaseType - 'Credit';
    }

    if(purchseTypeSelect.innerHTML === 'P'){
        purchaseType - 'Paid';
    }

    
    // console.log(itemSearchValue);
    // console.log(filterStartDate);
    // console.log(filterEndDate);
    // console.log(distributorId);
    // console.log(purchaseType);

    let filterParameter = `startDate=${filterStartDate}&endDate=${filterEndDate}&distId=${distributorId}&purchaeType=${purchaseType}`;

    let currentUrl = window.location.origin + window.location.pathname;

    let newUrl = `${currentUrl}?reportGenerat=true&${filterParameter}`;
    window.location.replace(newUrl);
}