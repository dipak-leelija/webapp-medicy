
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



function toggleCheckboxes1(checked) {
    console.log(checked);
}

// filter report on function
function filterReportOn(t){
    if(t.value == 'TS'){
        reportFilterVal.innerHTML = 'Total Sell';
    }

    if(t.value == 'TM'){
        reportFilterVal.innerHTML = 'Total Margin';
    }

    if(t.value == 'TD'){
        reportFilterVal.innerHTML = 'Total Discount';
    }
}



// sales data search call (funning ajax query)
function salesSummerySearch() {

    if(dateRangeVal.innerHTML == ''){
        alert('select date range');
        return;
    }
    
    if(filterByVal.innerHTML == ''){
        alert('select filter val');
        return;
    }
    
    if(reportFilterVal.innerHTML == ''){
        alert('select filter');
        return;
    }

    let startDate = convertDateFormatToBig(selectedStartDate.innerHTML);
    let endDate = convertDateFormatToBig(selectedEndDate.innerHTML);

    let dataArray = {
        startDt: startDate,
        endDt: endDate,
        filterBy: filterByVal.innerHTML,
    };

    salesDataSearchFunction(dataArray);
}


// salse data search function
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
    console.log(parsedData);
    dataTable.innerHTML = ''; // reset table data
    var dateArray = [];
    var headerStart = ['Date'];
    var headerMid = [];
    var heaerEnd1 = ['Total Discount'];
    var heaerEnd2 = ['Total Margin'];
    var heaerEnd3 = ['Total Sell'];

    if(reportFilterVal.innerHTML == 'Total Sell'){
        var heaerEnd = heaerEnd1;
    }else if(reportFilterVal.innerHTML == 'Total Margin'){
        var heaerEnd = heaerEnd2;
    }else if(reportFilterVal.innerHTML == 'Total Discount'){
        var heaerEnd = heaerEnd3;
    }

    parsedData.forEach(item=>{
        if(item.payment_mode){
            headerMid.push(item.payment_mode); 
        }else if(item.category_name){
            headerMid.push(item.category_name); 
        }
    });

    headerMid = [...new Set(headerMid)];
    headerMid = headerMid.sort();
    
    // Create the <thead> element
    const thead = document.createElement('thead');

    // Create a <tr> element
    const tr = document.createElement('tr');

    // filter the headers
    const headers = headerStart.concat(headerMid).concat(heaerEnd);

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

    // create unique date array for data filtering
    const tbody = document.createElement('tbody');
    parsedData.forEach(item=>{
        dateArray.push(item.added_on); 
    });

    var  uniqueDateArray = [...new Set(dateArray)];    // unique date array create
        
    var parsedDataArray = Object.values(parsedData);    // converting parsedata to object
        
    // date wise data filtering
    for(let i=0; i<uniqueDateArray.length; i++){
        
        const tr = document.createElement('tr');
        const tdDate = document.createElement('td');

        if(filterByVal.innerHTML == 'PM'){
            const td1 = document.createElement('td');
            const td2 = document.createElement('td');
            const td3 = document.createElement('td');
            const td4 = document.createElement('td');
        }else if(filterByVal.innerHTML == 'ICAT'){
            const td1 = document.createElement('td');
            const td2 = document.createElement('td');
            const td3 = document.createElement('td');
            const td4 = document.createElement('td');
            const td5 = document.createElement('td');
            const td6 = document.createElement('td');
            const td7 = document.createElement('td');
            const td8 = document.createElement('td');
        }
        
        const resultFilterDiv = document.createElement('td');
        
        let uniqueDate = '';
        
        // for payment mode wise calculation 
        let cashAmount = 0;
        let creditAmount = 0;
        let upiAmount = 0;
        let cardAmount = 0;

        // for item category wise calculation
        let allopathyAmount = 0;
        let ayurvedicAmount = 0;
        let cosmeticAmount = 0;
        let drugAmount = 0;
        let genericAmount = 0;
        let nutraceuticalsAmount = 0;
        let otcAmount = 0;
        let surgicalAmount = 0;
        
        // total amount
        let totalSellAmount = 0;
        let totalMargin = 0;

        for(let j=0; j<parsedDataArray.length; j++){

            if(uniqueDateArray[i] == parsedDataArray[j].added_on){
                
                uniqueDate = uniqueDateArray[i];

                if(filterByVal.innerHTML == 'PM'){
                    if (parsedDataArray[j].payment_mode == 'Cash') {
                        cashAmount = parseFloat(cashAmount) + parseFloat(parsedDataArray[j].total_amount);
                    }

                    if (parsedDataArray[j].payment_mode == 'Credit') {
                        creditAmount = parseFloat(creditAmount) + parseFloat(parsedDataArray[j].total_amount);
                    }

                    if (parsedDataArray[j].payment_mode == 'UPI') {
                        upiAmount = parseFloat(upiAmount) + parseFloat(parsedDataArray[j].total_amount);
                    }

                    if (parsedDataArray[j].payment_mode == 'Card') {
                        cardAmount = parseFloat(cardAmount) + parseFloat(parsedDataArray[j].total_amount);
                    }

                    totalMargin = parseFloat(totalMargin) + parseFloat(parsedDataArray[j].total_sales_margin);
                }

                if(filterByVal.innerHTML == 'ICAT'){
                    if (parsedDataArray[j].category_name == 'Allopathy') {
                        allopathyAmount = parseFloat(allopathyAmount) + parseFloat(parsedDataArray[j].total_stock_out_amount);
                    }

                    if (parsedDataArray[j].category_name == 'Ayurvedic') {
                        ayurvedicAmount = parseFloat(ayurvedicAmount) + parseFloat(parsedDataArray[j].total_stock_out_amount);
                    }

                    if (parsedDataArray[j].category_name == 'Cosmetic') {
                        cosmeticAmount = parseFloat(cosmeticAmount) + parseFloat(parsedDataArray[j].total_stock_out_amount);
                    }

                    if (parsedDataArray[j].category_name == 'Drug') {
                        drugAmount = parseFloat(drugAmount) + parseFloat(parsedDataArray[j].total_stock_out_amount);
                    }

                    if (parsedDataArray[j].category_name == 'Generic') {
                        genericAmount = parseFloat(genericAmount) + parseFloat(parsedDataArray[j].total_stock_out_amount);
                    }

                    if (parsedDataArray[j].category_name == 'Nutraceuticals') {
                        nutraceuticalsAmount = parseFloat(nutraceuticalsAmount) + parseFloat(parsedDataArray[j].total_stock_out_amount);
                    }

                    if (parsedDataArray[j].category_name == 'OTC') {
                        otcAmount = parseFloat(otcAmount) + parseFloat(parsedDataArray[j].total_stock_out_amount);
                    }

                    if (parsedDataArray[j].category_name == 'Surgical') {
                        surgicalAmount = parseFloat(surgicalAmount) + parseFloat(parsedDataArray[j].total_stock_out_amount);
                    }
                }
                
                totalSellAmount = parseFloat(allopathyAmount)+ parseFloat(ayurvedicAmount)+ parseFloat(cosmeticAmount)+ parseFloat(drugAmount)+parseFloat(genericAmount)+ parseFloat(nutraceuticalsAmount)+ parseFloat(otcAmount)+ parseFloat(surgicalAmount);
                }
            }

            tdDate.textContent = formatDate(uniqueDate);

            if(filterByVal.innerHTML == 'PM'){
                td1.textContent = cashAmount.toFixed(2);
                td2.textContent = creditAmount.toFixed(2);
                td3.textContent = upiAmount.toFixed(2);
                td4.textContent = cardAmount.toFixed(2);
            }

            if(filterByVal.innerHTML == 'ICAT'){
                td1.textContent = allopathyAmount.toFixed(2);
                td2.textContent = ayurvedicAmount.toFixed(2);
                td3.textContent = cosmeticAmount.toFixed(2);
                td4.textContent = drugAmount.toFixed(2);
                td5.textContent = genericAmount.toFixed(2);
                td6.textContent = nutraceuticalsAmount.toFixed(2);
                td7.textContent = otcAmount.toFixed(2);
                td8.textContent = surgicalAmount.toFixed(2);
            }
            

            if(reportFilterVal.innerHTML == 'Total Sell'){
                resultFilterDiv.textContent = totalSellAmount.toFixed(2);
            }else if(reportFilterVal.innerHTML == 'Total Margin'){
                resultFilterDiv.textContent = totalMargin.toFixed(2);
            }else if(reportFilterVal.innerHTML == 'Total Discount'){
                resultFilterDiv.textContent = totalMargin.toFixed(2);
            }
              
            tr.appendChild(tdDate);
            tr.appendChild(tdCardAmount);
            tr.appendChild(tdCashAmount);
            tr.appendChild(tdCreditAmount);
            tr.appendChild(tdUPIAmount);
            tr.appendChild(resultFilterDiv);
            
            tbody.appendChild(tr);
        }

        dataTable.appendChild(tbody);
}

