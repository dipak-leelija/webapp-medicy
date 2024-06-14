
const xmlhttp = new XMLHttpRequest();

// TABLE CONSTANT
const dataTable = document.getElementById('report-table');

// DIV CONSTANT
const reportTypeFilter = document.getElementById('day-filter');  // primary filter select class
const dateRangeSelect = document.getElementById('date-range'); // date range select class
const categoryFilter = document.getElementById('category-filter'); // primary filter category select

const productCategorySelectDiv = document.getElementById('prod-category-select-div'); // item category select div secondary filter
const productCategoryList = document.getElementById('prod-category'); // item category select class

const paymentModeDiv = document.getElementById('payment-mode-div'); // payment mode select div secondary filter
const paymentMode = document.getElementById('payment-mode'); // payment mode select class

const staffFilterDiv = document.getElementById('staff-filter-div'); // staff select div secondary filter
const staffFilter = document.getElementById('staff-filter'); // staff select class

const reportFilterDiv = document.getElementById('report-filter-div'); // report generation on primary filter div
const salesReportOn = document.getElementById('sales-report-on'); // report generation on primary filter select

const dateRangeSelectDiv = document.getElementById('date-range-select-div');
const datePickerDiv = document.getElementById('dtPickerDiv');

const inputedDateRangeDiv = document.getElementById('inputed-date-range-div');

/// constand default data holders .........
const dayFilterVal = document.getElementById('day-filter-val');
const dateRangeVal = document.getElementById('dt-rng-val');
const filterByVal = document.getElementById('filter-by-val');

const filterByProdCategoryVal = document.getElementById('filter-by-prod-categoty-val');
const filterByPaymentModeVal = document.getElementById('filter-by-payment-mode-val');
const filterByStaffVal = document.getElementById('filter-by-staff-val');
const reportFilterVal = document.getElementById('report-filter-val');

const selectedStartDate = document.getElementById('selected-start-date');
const selectedEndDate = document.getElementById('selected-end-date');

const inputedDateRange = document.getElementById('inputed-date-range');


//// temp data holder
let tempStartDate = '';
let tempEndDate = '';

// date range modified function 
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

function convertDateFormatToBig(dateStr) {
    let parts = dateStr.split('-');
    let day = parts[0];
    let month = parts[1];
    let year = parts[2];
    return `${year}-${month}-${day}`;
}

// minus date calculation
function calculateDate(days) {
    var currentDate = new Date();
    
    // Subtract days from current date
    var newDate = new Date(currentDate);
    newDate.setDate(currentDate.getDate() - parseInt(days));
    
    // Format the date as dd-mm-yyyy
    var formattedDate = ("0" + newDate.getDate()).slice(-2) + "/" + ("0" + (newDate.getMonth() + 1)).slice(-2) + "/" + newDate.getFullYear();
    
    return formattedDate;
}

// date format convertion
function convertDateFormat(dateStr) {
    const dateParts = dateStr.split('/');
    const newDateStr = dateParts.join('-');
    return newDateStr;
}

function convertDatePickerDateFormat(dateStr) {
    const dateParts = dateStr.split('-');
    const newDateFormat = `${dateParts[2]}/${dateParts[1]}/${dateParts[0]}`;
    return newDateFormat;
}

// day filter function
function dayFilter(t){
    dayFilterVal.innerHTML = t.value;
}

// date range select function
function dateRangeFilter(t){
    dateRangeVal.innerHTML = t.value;

    let date = new Date();
    let year = date.getFullYear();

    let fiscalYr = ''; 

    let startDt = '';
    let endDt = '';

    if(t.value == 'SDR'){
        datePickerDiv.style.display = 'block';
    }else{
        datePickerDiv.style.display = 'none';

        if(t.value == 'T'){
            selectedStartDate.innerHTML = convertDateFormat(calculateDate('0'));
            selectedEndDate.innerHTML = convertDateFormat(calculateDate('0'));

            inputedDateRange.value = '  '+calculateDate('0')+' - '+calculateDate('0');
            inputedDateRangeDiv.classList.remove('d-none');
            dateRangeSelectDiv.classList.add('d-none');
        }

        if(t.value == 'Y'){
            selectedStartDate.innerHTML = convertDateFormat(calculateDate('1'));
            selectedEndDate.innerHTML = convertDateFormat(calculateDate('0'));


            inputedDateRange.value = '  '+calculateDate('1')+' - '+calculateDate('0');
            inputedDateRangeDiv.classList.remove('d-none');
            dateRangeSelectDiv.classList.add('d-none');
        }

        if(t.value == 'LW'){
            selectedStartDate.innerHTML = convertDateFormat(calculateDate('7'));
            selectedEndDate.innerHTML = convertDateFormat(calculateDate('0'));

            inputedDateRange.value = '  '+calculateDate('7')+' - '+calculateDate('0');
            inputedDateRangeDiv.classList.remove('d-none');
            dateRangeSelectDiv.classList.add('d-none');
        }

        if(t.value == 'LM'){
            selectedStartDate.innerHTML = convertDateFormat(calculateDate('30'));
            selectedEndDate.innerHTML = convertDateFormat(calculateDate('0'));

            inputedDateRange.value = '  '+calculateDate('30')+' - '+calculateDate('0');
            inputedDateRangeDiv.classList.remove('d-none');
            dateRangeSelectDiv.classList.add('d-none');
        }

        if(t.value == 'LQ'){
            selectedStartDate.innerHTML = convertDateFormat(calculateDate('90'));
            selectedEndDate.innerHTML = convertDateFormat(calculateDate('0'));

            inputedDateRange.value = '  '+calculateDate('90')+' - '+calculateDate('0');
            inputedDateRangeDiv.classList.remove('d-none');
            dateRangeSelectDiv.classList.add('d-none');
        }

        if(t.value == 'CFY'){
            fiscalYr = parseInt(year) + 1;

            startDt = '01/04/'+year;
            endDt = '31/03/'+fiscalYr;

            selectedStartDate.innerHTML = convertDateFormat(startDt);
            selectedEndDate.innerHTML = convertDateFormat(endDt);

            inputedDateRange.value = '  '+startDt+' - '+endDt;
            inputedDateRangeDiv.classList.remove('d-none');
            dateRangeSelectDiv.classList.add('d-none');
        }

        if(t.value == 'PFY'){
            fiscalYr = parseInt(year) - 1;

            startDt = '01-04-'+fiscalYr;
            endDt = '31-03-'+year;

            selectedStartDate.innerHTML = convertDateFormat(startDt);
            selectedEndDate.innerHTML = convertDateFormat(endDt);

            inputedDateRange.value = '  '+startDt+' - '+endDt;
            inputedDateRangeDiv.classList.remove('d-none');
            dateRangeSelectDiv.classList.add('d-none');
        }
    }
}

// date picker functions
function selectStartDate(t){
    selectedStartDate.innerHTML = convertDateFormat(convertDatePickerDateFormat(t.value));
    tempStartDate = convertDatePickerDateFormat(t.value);
}

function selectEndDate(t){
    selectedEndDate.innerHTML = convertDateFormat(convertDatePickerDateFormat(t.value));
    tempEndDate = convertDatePickerDateFormat(t.value);

    inputedDateRange.value = '  '+tempStartDate+' - '+tempEndDate;
    inputedDateRangeDiv.classList.remove('d-none');
    dateRangeSelectDiv.classList.add('d-none');
    datePickerDiv.style.display = 'none';
}

// date range select div reset fucntion
function dateRangeReset(){
    inputedDateRangeDiv.classList.add('d-none');
    dateRangeSelectDiv.classList.remove('d-none');
}


// category select filter
function categoryFilterSelect(t){
    filterByVal.innerHTML = t.value;
    if(t.value == 'ICAT'){
        productCategorySelectDiv.classList.remove('d-none');
        reportFilterDiv.classList.remove('d-none');

        paymentModeDiv.classList.add('d-none');
        staffFilterDiv.classList.add('d-none');
    }

    if(t.value == 'PM'){
        paymentModeDiv.classList.remove('d-none');
        reportFilterDiv.classList.remove('d-none');

        productCategorySelectDiv.classList.add('d-none');
        staffFilterDiv.classList.add('d-none');
    }

    if(t.value == 'STF'){
        staffFilterDiv.classList.remove('d-none');
        reportFilterDiv.classList.remove('d-none');

        productCategorySelectDiv.classList.add('d-none');
        paymentModeDiv.classList.add('d-none');
    }
}



// sales data search call (funning ajax query)

function salesSummerySearch() {

    if(dateRangeVal.innerHTML == ''){
        alert('select date range');
        return;
    }
    // else{
    //     console.log(selectedStartDate.innerHTML);
    //     console.log(selectedEndDate.innerHTML);
    // }

    if(filterByVal.innerHTML == ''){
        alert('select filter val');
        return;
    }
    // else{
    //     console.log(filterByVal.innerHTML);
    // }

    let startDate = convertDateFormatToBig(selectedStartDate.innerHTML);
    let endDate = convertDateFormatToBig(selectedEndDate.innerHTML);

    let dataArray = {
        startDt: startDate,
        endDt: endDate,
        filterBy: filterByVal.innerHTML,
    };

    salesDataSearchFunction(dataArray);
}


function salesDataSearchFunction(array){
    let arryString = JSON.stringify(array);
    let salesDataReport = `ajax/salesSummeryReport.ajax.php?dataArray=${arryString}`;
    xmlhttp.open("GET", salesDataReport, false);
    xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xmlhttp.send(null);
    let report = xmlhttp.responseText;

    report = JSON.parse(report);
    console.log(report);
    if(report.status){
        reportShow(report.data);
    }
}


function reportShow(parsedData){
    dataTable.innerHTML = ''; // reset table data
    var dateArray = [];
    if(filterByVal.innerHTML == 'PM'){
        // Create the <thead> element
        const thead = document.createElement('thead');

        // Create a <tr> element
        const tr = document.createElement('tr');

        // Define the headers
        const headers = ['Date','Cash','Credit','UPI','Card','Total Sales'];

        // Iterate over the headers array and create a <th> element for each header
        headers.forEach(headerText => {
            const th = document.createElement('th');
            th.textContent = headerText;
            tr.appendChild(th);
        });

        // Append the <tr> to the <thead>
        thead.appendChild(tr);

        // Append the <thead> to the table
        dataTable.appendChild(thead);

        const tbody = document.createElement('tbody');

        parsedData.forEach(item=>{
            dateArray.push(item.added_on); 
        });

        var  uniqueDateArray = [...new Set(dateArray)];

        var parsedDataArray = Object.values(parsedData);

        for(let i=0; i<uniqueDateArray.length; i++){
            
            const tr = document.createElement('tr');
            const tdDate = document.createElement('td');
            const tdCashAmount = document.createElement('td');
            const tdCreditAmount = document.createElement('td');
            const tdUPIAmount = document.createElement('td');
            const tdCardAmount = document.createElement('td');
            const tdTotalAmount = document.createElement('td');
            
            let uniqueDate = '';
            let cashAmount = 0;
            let creditAmount = 0;
            let upiAmount = 0;
            let cardAmount = 0;
            let totalSellAmount = 0;

            for(let j=0; j<parsedDataArray.length; j++){

                if(uniqueDateArray[i] == parsedDataArray[j].added_on){
                    
                    uniqueDate = uniqueDateArray[i];

                    if (parsedDataArray[j].payment_mode == 'Cash') {
                        cashAmount = parseFloat(cashAmount) + parseFloat(parsedDataArray[j].total_amount);
                    }

                    if (parsedDataArray[j].payment_mode == 'Credit') {
                        creditAmount = parseFloat(cashAmount) + parseFloat(parsedDataArray[j].total_amount);
                    }

                    if (parsedDataArray[j].payment_mode == 'UPI') {
                        upiAmount = parseFloat(cashAmount) + parseFloat(parsedDataArray[j].total_amount);
                    }

                    if (parsedDataArray[j].payment_mode == 'Card') {
                        cardAmount = parseFloat(cashAmount) + parseFloat(parsedDataArray[j].total_amount);
                    }

                    totalSellAmount = parseFloat(cashAmount)+ parseFloat(creditAmount)+ parseFloat(upiAmount)+ parseFloat(cardAmount);
                }
            }

            tdDate.textContent = formatDate(uniqueDate);
            tdCashAmount.textContent = cashAmount.toFixed(2);
            tdCreditAmount.textContent = creditAmount.toFixed(2);
            tdUPIAmount.textContent = upiAmount.toFixed(2);
            tdCardAmount.textContent = cardAmount.toFixed(2);
            tdTotalAmount.textContent = totalSellAmount.toFixed(2);
              
            tr.appendChild(tdDate);
            tr.appendChild(tdCashAmount);
            tr.appendChild(tdCreditAmount);
            tr.appendChild(tdUPIAmount);
            tr.appendChild(tdCardAmount);
            tr.appendChild(tdTotalAmount);

            tbody.appendChild(tr);
        }

        dataTable.appendChild(tbody);
    }


    if(filterByVal.innerHTML == 'ICAT'){
        // Create the <thead> element
        const thead = document.createElement('thead');

        // Create a <tr> element
        const tr = document.createElement('tr');

        // Define the headers
        const headers = ['Date', 'Ayurvedic', 'Cosmetic', 'Drug', 'Generic', 'Nutraceuticals', 'OTC', 'Surgical', 'Total Sales'];

        // Iterate over the headers array and create a <th> element for each header
        headers.forEach(headerText => {
            const th = document.createElement('th');
            th.textContent = headerText;
            tr.appendChild(th);
        });

        // Append the <tr> to the <thead>
        thead.appendChild(tr);

        // Append the <thead> to the table
        dataTable.appendChild(thead);

        const tbody = document.createElement('tbody');

        parsedData.forEach(item=>{
            dateArray.push(item.added_on); 
        });

        var  uniqueDateArray = [...new Set(dateArray)];

        var parsedDataArray = Object.values(parsedData);

        for(let i=0; i<uniqueDateArray.length; i++){
            
            const tr = document.createElement('tr');
            const tdDate = document.createElement('td');
            const tdCashAmount = document.createElement('td');
            const tdCreditAmount = document.createElement('td');
            const tdUPIAmount = document.createElement('td');
            const tdCardAmount = document.createElement('td');
            const tdTotalAmount = document.createElement('td');
            
            let uniqueDate = '';
            let cashAmount = 0;
            let creditAmount = 0;
            let upiAmount = 0;
            let cardAmount = 0;
            let totalSellAmount = 0;

            for(let j=0; j<parsedDataArray.length; j++){

                if(uniqueDateArray[i] == parsedDataArray[j].added_on){
                    
                    uniqueDate = uniqueDateArray[i];

                    if (parsedDataArray[j].payment_mode == 'Cash') {
                        cashAmount = parseFloat(cashAmount) + parseFloat(parsedDataArray[j].total_amount);
                    }

                    if (parsedDataArray[j].payment_mode == 'Credit') {
                        creditAmount = parseFloat(cashAmount) + parseFloat(parsedDataArray[j].total_amount);
                    }

                    if (parsedDataArray[j].payment_mode == 'UPI') {
                        upiAmount = parseFloat(cashAmount) + parseFloat(parsedDataArray[j].total_amount);
                    }

                    if (parsedDataArray[j].payment_mode == 'Card') {
                        cardAmount = parseFloat(cashAmount) + parseFloat(parsedDataArray[j].total_amount);
                    }

                    totalSellAmount = parseFloat(cashAmount)+ parseFloat(creditAmount)+ parseFloat(upiAmount)+ parseFloat(cardAmount);
                }
            }

            tdDate.textContent = formatDate(uniqueDate);
            tdCashAmount.textContent = cashAmount.toFixed(2);
            tdCreditAmount.textContent = creditAmount.toFixed(2);
            tdUPIAmount.textContent = upiAmount.toFixed(2);
            tdCardAmount.textContent = cardAmount.toFixed(2);
            tdTotalAmount.textContent = totalSellAmount.toFixed(2);
              
            tr.appendChild(tdDate);
            tr.appendChild(tdCashAmount);
            tr.appendChild(tdCreditAmount);
            tr.appendChild(tdUPIAmount);
            tr.appendChild(tdCardAmount);
            tr.appendChild(tdTotalAmount);

            tbody.appendChild(tr);
        }

        dataTable.appendChild(tbody);
    }

}

