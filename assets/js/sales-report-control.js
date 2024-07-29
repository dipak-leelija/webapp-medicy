
const xmlhttp = new XMLHttpRequest();

// TABLE CONSTANT
const dataTable = document.getElementById('report-table');

// DIV CONSTANT
const reportTypeFilter = document.getElementById('day-filter');  // primary filter select class
const dateRangeSelect = document.getElementById('date-range'); // date range select class
const categoryFilter = document.getElementById('category-filter'); // primary filter category select
const datePickerDiv = document.getElementById('dtPickerDiv');
const additionalFilterDiv = document.getElementById('extraFilterDiv');
const productCategorySelectDiv = document.getElementById('prod-category-select-div'); // item category select div secondary filter
const paymentModeDiv = document.getElementById('payment-mode-div'); // payment mode select div secondary filter
const staffFilterDiv = document.getElementById('staff-filter-div'); // staff select div secondary filter
const reportFilterDiv = document.getElementById('report-filter-div'); // report generation on primary filter div
const dateRangeSelectDiv = document.getElementById('date-range-select-div');
const inputedDateRangeDiv = document.getElementById('inputed-date-range-div');


// BUTTONS
const productCategoryBtn = document.getElementById('prod-category'); // item category select button
const staffFilter = document.getElementById('staff-filter'); // staff select dropdown button


// FILTERS
const paymentMode = document.getElementById('payment-mode'); // payment mode select class
const salesReportOn = document.getElementById('sales-report-on'); // report generation on primary filter select
const checkSelectAdditionalFilter = document.getElementById('extra-filter-check');
const selectedAdditionalFilter = document.getElementById('selected-additional-fiter');


// FILTER CHECK BOX
const filterCheckbox1 = document.getElementById('bill-date-checked-check-box');


/// constand default data holders .........
const downloadType = document.getElementById('download-file-type');
const dayFilterVal = document.getElementById('day-filter-val');
const dateRangeVal = document.getElementById('dt-rng-val');
const filterByVal = document.getElementById('filter-by-val');
const filterByProdCategoryIdVal = document.getElementById('filter-by-prod-categoty-id-val');
const filterByProdCategoryNameVal = document.getElementById('filter-by-prod-categoty-name');
const filterByPaymentModeVal = document.getElementById('filter-by-payment-mode-val');
const filterByStaffName = document.getElementById('filter-by-staff-name');
const filterByStaffId = document.getElementById('filter-by-staff-id');
const reportFilterVal = document.getElementById('report-filter-val');
const selectedStartDate = document.getElementById('selected-start-date');
const selectedEndDate = document.getElementById('selected-end-date');
const inputedDateRange = document.getElementById('inputed-date-range');
const healthCareName = document.getElementById('healthcare-name');
const healthCareGstin = document.getElementById('healthcare-gstin');
const healthCareAddress = document.getElementById('healthcare-address');
const reportGenerationTime = document.getElementById('report-generation-date-time-holder');


/// dropdown inner html constant
const paymentModeConst = document.getElementById('payment-mode-select-span');


/// all staff data on admin 
const allCurrentStaffNameOnAdmin = document.getElementById('all-stuff-name-data');
const allCurrentStaffIdOnAdmin = document.getElementById('all-stuff-id-data');


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


//// page all function dafination area


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


// item category selection function 
function toggleCheckboxes1(source) {
    if(source.id == 'ac-chkBx'){
        const checkboxes = document.querySelectorAll('.item-category-select-checkbox-menu input[type="checkbox"]');
        checkboxes.forEach(checkbox => {
        if (!checkbox.disabled) {
                checkbox.checked = source.checked;
            }
        });

        if(source.checked == true){
            productCategoryBtn.innerHTML = 'All Category';
            filterByProdCategoryIdVal.innerHTML = '1,2,3,4,5,6,7,8';
            filterByProdCategoryNameVal.innerHTML = 'Allopathy,Ayurvedic,Cosmetic,Drug,Generic,Nutraceuticals,OTC,Surgical';
        }else{
            productCategoryBtn.innerHTML = 'Select Item Category';
            filterByProdCategoryIdVal.innerHTML = '';
            filterByProdCategoryNameVal.innerHTML = '';
        }
    }

    
    if(source.id != 'ac-chkBx'){
        if(source.checked == true){
            if(filterByProdCategoryIdVal.innerHTML == '' && filterByProdCategoryNameVal.innerHTML == ''){
                productCategoryBtn.innerHTML = source.id;
                filterByProdCategoryIdVal.innerHTML = source.value;
                filterByProdCategoryNameVal.innerHTML = source.id;
            }else{
                let selectedCategoryIdString = filterByProdCategoryIdVal.innerHTML;
                let selectedCategoryNameString = filterByProdCategoryNameVal.innerHTML;
                let updatedCategoryId = selectedCategoryIdString + ',' + source.value;
                let updatedCategoryName = selectedCategoryNameString + ',' + source.id;
                filterByProdCategoryIdVal.innerHTML = updatedCategoryId;
                filterByProdCategoryNameVal.innerHTML = updatedCategoryName;
            }
        }else{
            if(filterByProdCategoryIdVal.innerHTML != '' && filterByProdCategoryNameVal.innerHTML != ''){
                let selectedCategoryIdString = filterByProdCategoryIdVal.innerHTML;
                let selectedCategoryNameString = filterByProdCategoryNameVal.innerHTML;
                let replacedId = source.value + ',';
                let updatedCategoryId = selectedCategoryIdString.replace(replacedId, "").replace(source.value, "");
                updatedCategoryId = updatedCategoryId.replace(/,(\s*)$/, '$1');
                let replacedName = source.id + ',';
                let updatedCategoryName = selectedCategoryNameString.replace(replacedName,"").replace(source.id, "");
                updatedCategoryName = updatedCategoryName.replace(/,(\s*)$/, '$1');
                filterByProdCategoryIdVal.innerHTML = updatedCategoryId;
                filterByProdCategoryNameVal.innerHTML = updatedCategoryName;
                document.getElementById(source.id).checked = false;
            }
        }
        document.getElementById('ac-chkBx').checked = false;
    }

    let crntItemCategory = filterByProdCategoryNameVal.innerHTML;
    if(crntItemCategory.includes('Allopathy') &&  crntItemCategory.includes('Ayurvedic') && crntItemCategory.includes('Cosmetic') && crntItemCategory.includes('Drug') && crntItemCategory.includes('Generic') &&  crntItemCategory.includes('Nutraceuticals') && crntItemCategory.includes('OTC') && crntItemCategory.includes('Surgical')){
        productCategoryBtn.innerHTML = 'All Category'
    }else if(filterByProdCategoryNameVal.innerHTML == ''){
        productCategoryBtn.innerHTML = 'Select Category';
    }else{
         productCategoryBtn.innerHTML = filterByProdCategoryNameVal.innerHTML;
    }

    // console.log(filterByProdCategoryIdVal.innerHTML);
    // console.log(filterByProdCategoryNameVal.innerHTML);
}



// payment mode selection function 
function toggleCheckboxes2(source) {

    if(source.id == 'apm-chkBx'){
        const checkboxes = document.querySelectorAll('.payment-mode-checkbox-menu input[type="checkbox"]');
        checkboxes.forEach(checkbox => {
        if (!checkbox.disabled) {
                checkbox.checked = source.checked;
            }
        });

        if(source.checked == true){
            paymentModeConst.innerHTML = 'All Payment Mode';
            filterByPaymentModeVal.innerHTML = 'Card,Cash,Credit,UPI';
        }else{
            paymentModeConst.innerHTML = 'Select Payment Mode';
            filterByPaymentModeVal.innerHTML = '';
        }
    }

    if(source.id == 'csh-chkBx'){
        if(source.checked == true){
            if(filterByPaymentModeVal.innerHTML == ''){
                filterByPaymentModeVal.innerHTML = 'Cash';
            }else{
                let mainStirng = filterByPaymentModeVal.innerHTML;
                let updatedString = mainStirng + ',Cash';
                filterByPaymentModeVal.innerHTML = updatedString;
            }
        }else{
            if(filterByPaymentModeVal.innerHTML != ''){
                let mainStirng = filterByPaymentModeVal.innerHTML;
                let modifiedString = mainStirng.replace('Cash,', "").replace('Cash', '');
                modifiedString = modifiedString.replace(/,(\s*)$/, '$1');
                filterByPaymentModeVal.innerHTML = modifiedString;
                document.getElementById('apm-chkBx').checked = false;
            }
        }
    }

    if(source.id == 'crdt-chkBx'){
        if(source.checked == true){
            if(filterByPaymentModeVal.innerHTML == ''){
                filterByPaymentModeVal.innerHTML = 'Credit';
            }else{
                let mainStirng = filterByPaymentModeVal.innerHTML;
                let updatedString = mainStirng + ',Credit';
                filterByPaymentModeVal.innerHTML = updatedString;
            }
        }else{
            if(filterByPaymentModeVal.innerHTML != ''){
                let mainStirng = filterByPaymentModeVal.innerHTML;
                let modifiedString = mainStirng.replace('Credit,', "").replace('Credit', '');
                modifiedString = modifiedString.replace(/,(\s*)$/, '$1');
                filterByPaymentModeVal.innerHTML = modifiedString;
                document.getElementById('apm-chkBx').checked = false;
            }
        }
    }

    if(source.id == 'crd-chkBx'){
        if(source.checked == true){
            if(filterByPaymentModeVal.innerHTML == ''){
                filterByPaymentModeVal.innerHTML = 'Card';
            }else{
                let mainStirng = filterByPaymentModeVal.innerHTML;
                let updatedString = mainStirng + ',Card';
                filterByPaymentModeVal.innerHTML = updatedString;
            }
        }else{
            if(filterByPaymentModeVal.innerHTML != ''){
                let mainStirng = filterByPaymentModeVal.innerHTML;
                let modifiedString = mainStirng.replace('Card,', "").replace('Card', '');
                modifiedString = modifiedString.replace(/,(\s*)$/, '$1');
                filterByPaymentModeVal.innerHTML = modifiedString;
                document.getElementById('apm-chkBx').checked = false;
            }
        }
    }

    if(source.id == 'upi-chkBx'){
        if(source.checked == true){
            if(filterByPaymentModeVal.innerHTML == ''){
                filterByPaymentModeVal.innerHTML = 'UPI';
            }else{
                let mainStirng = filterByPaymentModeVal.innerHTML;
                let updatedString = mainStirng + ',UPI';
                filterByPaymentModeVal.innerHTML = updatedString;
            }
        }else if(source.checked == false){
            if(filterByPaymentModeVal.innerHTML != ''){
                let mainStirng = filterByPaymentModeVal.innerHTML;
                let modifiedString = mainStirng.replace('UPI,', '').replace('UPI', '');
                modifiedString = modifiedString.replace(/,(\s*)$/, '$1');
                filterByPaymentModeVal.innerHTML = modifiedString;
                document.getElementById('apm-chkBx').checked = false;
            }
        }
    }
    
    let crntPaymentMod = filterByPaymentModeVal.innerHTML;
    if(crntPaymentMod.includes('Cash') &&  crntPaymentMod.includes('Card') &&crntPaymentMod.includes('UPI') &&crntPaymentMod.includes('Credit')){
        paymentModeConst.innerHTML = 'All Payment Mode'
    }else if(filterByPaymentModeVal.innerHTML == ''){
        paymentModeConst.innerHTML = 'Select Payment Mode';
    }else{
        paymentModeConst.innerHTML = filterByPaymentModeVal.innerHTML;
    }

    // console.log(filterByPaymentModeVal.innerHTML);
}


// staff selection function
function toggleCheckboxes3(source){
    // console.log(source);
    if(source.id == 'stf-chkBx'){
        const checkboxes = document.querySelectorAll('.staff-list-checkbox-menu input[type="checkbox"]');
        checkboxes.forEach(checkbox => {
        if (!checkbox.disabled) {
                checkbox.checked = source.checked;
            }
        });
    }

    if(source.value == 'AS'){
        if(source.checked == true){
            filterByStaffName.innerHTML = allCurrentStaffNameOnAdmin.innerHTML;
            filterByStaffId.innerHTML = allCurrentStaffIdOnAdmin.innerHTML;
            staffFilter.innerHTML = 'All Staff';
        }else{
            staffFilter.innerHTML = 'Select Staff';
            filterByStaffId.innerHTML = '';
            filterByStaffName.innerHTML = '';
        }
    }

    if(source.id == 'adm-chkBx'){
        if(source.checked == true){
            if(filterByStaffName.innerHTML == '' && filterByStaffId.innerHTML == ''){
                filterByStaffName.innerHTML = source.name;
                filterByStaffId.innerHTML = source.value;
                staffFilter.innerHTML = 'ADMIN';
            }else{
                filterByStaffName.innerHTML = filterByStaffName.innerHTML +','+ source.name;
                filterByStaffId.innerHTML = filterByStaffId.innerHTML +','+ source.value;
                staffFilter.innerHTML = staffFilter.innerHTML +','+ 'ADMIN';
            }
        }else{
            let mainNameStirng = filterByStaffName.innerHTML;
            let modifiedNameString = mainNameStirng.replace(source.name+',',"").replace(source.name, '');
            modifiedNameString = modifiedNameString.replace(/,(\s*)$/, '$1');
            filterByStaffName.innerHTML = modifiedNameString;

            let mainIdStirng = filterByStaffId.innerHTML;
            let modifiedIdString = mainIdStirng.replace(source.value+',',"").replace(source.value, '');
            modifiedIdString = modifiedIdString.replace(/,(\s*)$/, '$1');
            filterByStaffId.innerHTML = modifiedIdString;
            if(modifiedNameString != ''){
                staffFilter.innerHTML = modifiedNameString;
            }else{
                staffFilter.innerHTML = 'Select Staff';
            }
        }
    }

    if(source.id != 'adm-chkBx' && source.id != 'stf-chkBx'){
        if(source.checked == true){
            if(filterByStaffName.innerHTML == '' && filterByStaffId.innerHTML == ''){
                filterByStaffName.innerHTML = source.id;
                filterByStaffId.innerHTML = source.value;
                staffFilter.innerHTML = filterByStaffName.innerHTML;
            }else{
                filterByStaffName.innerHTML = filterByStaffName.innerHTML +','+ source.id;
                filterByStaffId.innerHTML = filterByStaffId.innerHTML +','+ source.value;
                staffFilter.innerHTML = staffFilter.innerHTML +','+ filterByStaffName.innerHTML;
            }
        }else{
            let mainNameStirng = filterByStaffName.innerHTML;
            let modifiedNameString = mainNameStirng.replace(source.id+',',"").replace(source.id, '');
            modifiedNameString = modifiedNameString.replace(/,(\s*)$/, '$1');
            filterByStaffName.innerHTML = modifiedNameString;

            let mainIdStirng = filterByStaffId.innerHTML;
            let modifiedIdString = mainIdStirng.replace(source.value+',',"").replace(source.value, '');
            modifiedIdString = modifiedIdString.replace(/,(\s*)$/, '$1');
            filterByStaffId.innerHTML = modifiedIdString;

            if(modifiedNameString != ''){
                staffFilter.innerHTML = modifiedNameString;
            }else{
                staffFilter.innerHTML = 'Select Staff';
            }
        }
    }

    // console.log(filterByStaffId.innerHTML.length);
    // console.log('selected staff id list : '+filterByStaffId.innerHTML);
    // console.log('selected staff name list : '+filterByStaffName.innerHTML);
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


// current date time generation function
function getCurrentDateTime() {
    const currentDate = new Date();

    const day = String(currentDate.getDate()).padStart(2, '0');
    const month = String(currentDate.getMonth() + 1).padStart(2, '0'); // January is 0!
    const year = currentDate.getFullYear();

    const hours = String(currentDate.getHours()).padStart(2, '0');
    const minutes = String(currentDate.getMinutes()).padStart(2, '0');
    const seconds = String(currentDate.getSeconds()).padStart(2, '0');

    return `${day}-${month}-${year} ${hours}:${minutes}:${seconds}`;
}



// function extraFilterDiv(){
//     if(dayFilterVal.innerHTML == '0'){
//         if(checkSelectAdditionalFilter.innerHTML == '0'){
//             additionalFilterDiv.style.display = 'block';
//             checkSelectAdditionalFilter.innerHTML = '1';
//         }else{
//             additionalFilterDiv.style.display = 'none';
//             checkSelectAdditionalFilter.innerHTML = '0';
//         } 
//     }else{
//         alert('bill date only can show in day wise report!');
//     }
// }



function additionalCheckBoxFunction(){
    if(filterCheckbox1.checked == true){
        return 1;
    }else{
        return 0;
    }
}




// sales data search call (funning ajax query)
function salesSummerySearch() {
    
    let searchString = '';
    let dtFilter = dayFilterVal.innerHTML;
    let additionalFilter1 = additionalCheckBoxFunction();

    if(dtFilter != '0'){
        additionalFilter1 = 0;
    }
    
    // date range input check
    if(dateRangeVal.innerHTML == ''){
        alert('select date range');
        return;
    }
    
    // primary filter check
    if(filterByVal.innerHTML == ''){
        alert('select filter val');
        return;
    }else{
        // secondory filter chek based on primary filter
        if(filterByVal.innerHTML == 'ICAT'){
            if(filterByProdCategoryIdVal.innerHTML == '' && filterByProdCategoryNameVal.innerHTML == ''){
                alert('select item category val');
                return;
            }else{
                searchString = filterByProdCategoryNameVal.innerHTML;
            }
        }
        

        if(filterByVal.innerHTML == 'PM'){
            if(filterByPaymentModeVal.innerHTML == ''){
                alert('select payment mode');
                return;
            }else{
                searchString = filterByPaymentModeVal.innerHTML;
            }
        }

        if(filterByVal.innerHTML == 'STF'){
            if(filterByStaffId.innerHTML == ''){
                alert('select staff');
                return;
            }else{
                searchString = filterByStaffId.innerHTML;
            }
        }
    }

    // primary report generator filter based on select
    if(reportFilterVal.innerHTML == ''){
        alert('select filter');
        return;
    }

    let startDate = convertDateFormatToBig(selectedStartDate.innerHTML);
    let endDate = convertDateFormatToBig(selectedEndDate.innerHTML);

    let dataArray = {
        datefilter: dtFilter,
        searchOn: searchString,
        startDt: startDate,
        endDt: endDate,
        filterBy: filterByVal.innerHTML,
        additionalFilter1 : additionalFilter1
    };
    salesDataSearchFunction(dataArray);
}


// string slice function based on ','.....
function slicedString(string){
    let strToArr = [];
    strToArr = string.split(',');
    return strToArr;
}




// salse data search function (ajax call)
let reportData = '';
function salesDataSearchFunction(array){
    let arryString = JSON.stringify(array);
    let salesDataReport = `ajax/salesSummeryReport.ajax.php?dataArray=${arryString}`;
    xmlhttp.open("GET", salesDataReport, false);
    xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xmlhttp.send(null);
    let report = xmlhttp.responseText;

    // console.log(report);
    report = JSON.parse(report);
    
    if(report.status == '1'){
        salesReportShow(report.data);
        reportData = report.data;
    }else{
        alert('no data found');
    }
}




function salesReportShow(parsedData) {
    // Pagination Constants and Variables
    const rowsPerPage = 25;
    let currentPage = 1;

    document.getElementById('download-checking').innerHTML = '1';
    dataTable.innerHTML = '';

    let currentDateTime = getCurrentDateTime();
    reportGenerationTime.innerHTML = currentDateTime;

    // Define Headers
    const headerStart1a = ['Date'];
    const headerStart1b = ['Date', 'Bill Date'];
    let headerStart1;
    parsedData.forEach(item => {
        if (item.hasOwnProperty('bil_dt')) {
            headerStart1 = headerStart1b;
        } else {
            headerStart1 = headerStart1a;
        }
    });
    const headerStart2 = ['Start Date', 'End Date'];
    const headerEnd1 = ['Total Sell'];
    const headerEnd2 = ['Total Margin'];
    const headerEnd3 = ['Total Discount'];

    let headerStart = [];
    let headerMid = [];
    let headerEnd = [];

    if (dayFilterVal.innerHTML == 0) {
        headerStart = headerStart1;
    } else {
        headerStart = headerStart2;
    }

    if (filterByVal.innerHTML === 'ICAT') {
        headerMid = slicedString(filterByProdCategoryNameVal.innerHTML);
    } else if (filterByVal.innerHTML === 'PM') {
        headerMid = slicedString(filterByPaymentModeVal.innerHTML);
    } else if (filterByVal.innerHTML === 'STF') {
        headerMid = slicedString(filterByStaffName.innerHTML);
    }

    if (reportFilterVal.innerHTML === 'Total Sell') {
        headerEnd = headerEnd1;
    } else if (reportFilterVal.innerHTML === 'Total Margin') {
        headerEnd = headerEnd2;
    } else if (reportFilterVal.innerHTML === 'Total Discount') {
        headerEnd = headerEnd3;
    }

    headerMid = [...new Set(headerMid)].sort();
    const headers = headerStart.concat(headerMid).concat(headerEnd);

    function renderTable(data, page) {
        const start = (page - 1) * rowsPerPage;
        const end = start + rowsPerPage;
        const paginatedData = data.slice(start, end);

        dataTable.innerHTML = '';

        // Create table headers
        const thead = document.createElement('thead');
        const tr = document.createElement('tr');
        headers.forEach(headerText => {
            const th = document.createElement('th');
            th.textContent = headerText;
            th.style.fontWeight = 'bold';
            tr.appendChild(th);
        });
        thead.appendChild(tr);

        // Calculate and append the grand total row
        const grandTotalRow = document.createElement('tr');
        grandTotalRow.classList.add('grand-total');
        grandTotalRow.style.fontWeight = 'bold';

        const grandTotals = {};
        headers.forEach(header => {
            grandTotals[header] = 0;
        });

        headers.forEach(header => {
            if (header !== 'Date' && header !== 'Bill Date' && header !== 'Start Date' && header !== 'End Date') {
                parsedData.forEach(data => {
                    if (headerMid.includes(header)) {
                        if (filterByVal.innerHTML === 'ICAT' && data.category_name === header) {
                            if (reportFilterVal.innerHTML === 'Total Sell') {
                                grandTotals[header] += parseFloat(data.total_stock_out_amount || 0);
                            } else if (reportFilterVal.innerHTML === 'Total Margin') {
                                grandTotals[header] += parseFloat(data.total_sales_margin || 0);
                            } else if (reportFilterVal.innerHTML === 'Total Discount') {
                                grandTotals[header] += parseFloat(data.total_discount || 0);
                            }
                        } else if (filterByVal.innerHTML === 'PM' && data.payment_mode === header) {
                            if (reportFilterVal.innerHTML === 'Total Sell') {
                                grandTotals[header] += parseFloat(data.total_stock_out_amount || 0);
                            } else if (reportFilterVal.innerHTML === 'Total Margin') {
                                grandTotals[header] += parseFloat(data.total_sales_margin || 0);
                            } else if (reportFilterVal.innerHTML === 'Total Discount') {
                                grandTotals[header] += parseFloat(data.total_discount || 0);
                            }
                        } else if (filterByVal.innerHTML === 'STF' && data.added_by_name === header) {
                            if (reportFilterVal.innerHTML === 'Total Sell') {
                                grandTotals[header] += parseFloat(data.total_stock_out_amount || 0);
                            } else if (reportFilterVal.innerHTML === 'Total Margin') {
                                grandTotals[header] += parseFloat(data.total_sales_margin || 0);
                            } else if (reportFilterVal.innerHTML === 'Total Discount') {
                                grandTotals[header] += parseFloat(data.total_discount || 0);
                            }
                        }
                    } else if (header === 'Total Sell') {
                        grandTotals[header] += parseFloat(data.total_stock_out_amount || 0);
                    } else if (header === 'Total Margin') {
                        grandTotals[header] += parseFloat(data.total_sales_margin || 0);
                    } else if (header === 'Total Discount') {
                        grandTotals[header] += parseFloat(data.total_discount || 0);
                    }
                });
            }
        });

        headers.forEach(header => {
            const cell = document.createElement('td');
            if (header !== 'Date' && header !== 'Bill Date' && header !== 'Start Date' && header !== 'End Date') {
                cell.textContent = `₹${grandTotals[header].toFixed(2)}`; // Show 0 if no data
            } else {
                cell.textContent = '';
            }
            cell.className = 'total-cell';
            grandTotalRow.appendChild(cell);
        });

        thead.appendChild(grandTotalRow);
        dataTable.appendChild(thead);

        const tbody = document.createElement('tbody');

        const groupDataByKey = (data, keyFn) => {
            return data.reduce((acc, item) => {
                const key = keyFn(item);
                if (!acc[key]) {
                    acc[key] = [];
                }
                acc[key].push(item);
                return acc;
            }, {});
        };

        let groupedData;

        if (dayFilterVal.innerHTML == 0) {
            groupedData = groupDataByKey(paginatedData, item => item.added_on);
        } else if (dayFilterVal.innerHTML == 1) {
            groupedData = groupDataByKey(paginatedData, item => item.start_date + ' to ' + item.end_date);
        } else if (dayFilterVal.innerHTML == 2) {
            groupedData = groupDataByKey(paginatedData, item => `${item.year}-${item.month}`);
        }

        const createRows = (groupData, groupKey, headers) => {
            const tr = document.createElement('tr');
            let rowData = {};

            if (dayFilterVal.innerHTML == 0) {
                rowData['Date'] = formatDate(groupKey);
            } else if (dayFilterVal.innerHTML == 1) {
                const [startDate, endDate] = groupKey.split(' to ');
                rowData['Start Date'] = formatDate(startDate);
                rowData['End Date'] = formatDate(endDate);
            } else if (dayFilterVal.innerHTML == 2) {
                const [year, month] = groupKey.split('-');
                rowData['Start Date'] = formatDate(groupData[0].start_date);
                rowData['End Date'] = formatDate(groupData[0].end_date);
            }

            let totalSellAmount = 0, totalMargin = 0, totalDiscount = 0;

            headerMid.forEach(header => {
                rowData[header] = 0.00;
            });

            groupData.forEach(data => {
                if (filterByVal.innerHTML === 'ICAT' && headerMid.includes(data.category_name)) {
                    let amount = 0;
                    if (reportFilterVal.innerHTML === 'Total Sell') {
                        amount = parseFloat(data.total_stock_out_amount || 0);
                    } else if (reportFilterVal.innerHTML === 'Total Margin') {
                        amount = parseFloat(data.total_sales_margin || 0);
                    } else if (reportFilterVal.innerHTML === 'Total Discount') {
                        amount = parseFloat(data.total_discount || 0);
                    }

                    rowData[data.category_name] += amount;
                    totalSellAmount += amount;
                    totalMargin += parseFloat(data.total_sales_margin || 0);
                    totalDiscount += parseFloat(data.total_discount || 0);
                } else if (filterByVal.innerHTML === 'PM' && headerMid.includes(data.payment_mode)) {
                    let amount = 0;
                    if (reportFilterVal.innerHTML === 'Total Sell') {
                        amount = parseFloat(data.total_stock_out_amount || 0);
                    } else if (reportFilterVal.innerHTML === 'Total Margin') {
                        amount = parseFloat(data.total_sales_margin || 0);
                    } else if (reportFilterVal.innerHTML === 'Total Discount') {
                        amount = parseFloat(data.total_discount || 0);
                    }

                    rowData[data.payment_mode] += amount;
                    totalSellAmount += amount;
                    totalMargin += parseFloat(data.total_sales_margin || 0);
                    totalDiscount += parseFloat(data.total_discount || 0);
                } else if (filterByVal.innerHTML === 'STF' && headerMid.includes(data.added_by_name)) {
                    let amount = 0;
                    if (reportFilterVal.innerHTML === 'Total Sell') {
                        amount = parseFloat(data.total_stock_out_amount || 0);
                    } else if (reportFilterVal.innerHTML === 'Total Margin') {
                        amount = parseFloat(data.total_sales_margin || 0);
                    } else if (reportFilterVal.innerHTML === 'Total Discount') {
                        amount = parseFloat(data.total_discount || 0);
                    }

                    rowData[data.added_by_name] += amount;
                    totalSellAmount += amount;
                    totalMargin += parseFloat(data.total_sales_margin || 0);
                    totalDiscount += parseFloat(data.total_discount || 0);
                }
            });

            headerEnd.forEach(header => {
                if (header === 'Total Sell') {
                    rowData[header] = `₹${totalSellAmount.toFixed(2)}`;
                } else if (header === 'Total Margin') {
                    rowData[header] = `₹${totalMargin.toFixed(2)}`;
                } else if (header === 'Total Discount') {
                    rowData[header] = `₹${totalDiscount.toFixed(2)}`;
                }
            });

            headers.forEach(header => {
                const cell = document.createElement('td');
                cell.textContent = rowData[header] || '0.00'; // Show 0 if no data
                tr.appendChild(cell);
            });

            tbody.appendChild(tr);
        };

        Object.keys(groupedData).forEach(groupKey => {
            createRows(groupedData[groupKey], groupKey, headers);
        });

        dataTable.appendChild(tbody);

        // Call the pagination controls function
        createPaginationControls(parsedData);
    }

    function createPaginationControls(data) {
        const totalPages = Math.ceil(data.length / rowsPerPage);
        const paginationControls = document.getElementById('pagination-controls');
        paginationControls.innerHTML = '';

        const prevButton = document.createElement('button');
        prevButton.className = 'btn btn-link btn-sky-blue'; // Borderless and sky blue color
        prevButton.innerHTML = '<i class="fas fa-arrow-left"></i>';
        prevButton.disabled = currentPage === 1;
        prevButton.addEventListener('click', () => {
            if (currentPage > 1) {
                currentPage--;
                renderTable(parsedData, currentPage);
                createPaginationControls(parsedData);
            }
        });
        paginationControls.appendChild(prevButton);

        for (let i = 1; i <= totalPages; i++) {
            const pageButton = document.createElement('button');
            pageButton.textContent = i;
            pageButton.className = `btn btn-link btn-sky-blue ${i === currentPage ? 'text-primary' : ''}`; // Borderless and sky blue color
            pageButton.addEventListener('click', () => {
                currentPage = i;
                renderTable(parsedData, currentPage);
                createPaginationControls(parsedData);
            });
            paginationControls.appendChild(pageButton);
        }

        const nextButton = document.createElement('button');
        nextButton.className = 'btn btn-link btn-sky-blue'; // Borderless and sky blue color
        nextButton.innerHTML = '<i class="fas fa-arrow-right"></i>';
        nextButton.disabled = currentPage === totalPages;
        nextButton.addEventListener('click', () => {
            if (currentPage < totalPages) {
                currentPage++;
                renderTable(parsedData, currentPage);
                createPaginationControls(parsedData);
            }
        });
        paginationControls.appendChild(nextButton);
    }

    // Initialize table with the first page of data
    renderTable(parsedData, currentPage);
}




// Custom CSS for Sky Blue Pagination Buttons
const style = document.createElement('style');
style.textContent = `
    .btn-sky-blue {
        color: #87CEEB; /* Sky Blue color */
        border: none;
    }

    .btn-sky-blue:hover {
        color: #1E90FF; /* A darker shade for hover */
    }

    .total-cell {
        font-weight: bold;
    }

    .grand-total {
        background-color: #f0f0f0;
    }`;
document.head.appendChild(style);





// download file format selection function
function selectDownloadType(ts){
    if(document.getElementById('download-checking').innerHTML == '1'){
        if(ts.value == 'exl'){
            exportToExcel(reportData);
            downloadType.selectedIndex = 0;
        }
        if(ts.value == 'csv'){
            exportToCSV(reportData);
            downloadType.selectedIndex = 0;
        }
        // if(ts.value == 'pdf'){
        //     exportToPDF();
        // }
    }else{
        alert('generate report first!');
        downloadType.selectedIndex = 0;
    }
}





// Function for export the table data to Excel
function exportToExcel(parsedData) {
    const masterHeaderData1 = [
        [healthCareName.innerHTML],];
    
    const masterHeaderData2 = [
        [healthCareAddress.innerHTML],
        ["GSTIN : " + healthCareGstin.innerHTML],
        [],
        ["Sales Summary Report : " + selectedStartDate.innerHTML + " To " + selectedEndDate.innerHTML],
        ["Report generated at : " + reportGenerationTime.innerHTML],
        []
    ];

   
    // Create a new workbook and a worksheet
    const workbook = new ExcelJS.Workbook();
    const worksheet = workbook.addWorksheet('Sales Report');

    let currentDateTime = getCurrentDateTime();

    // Define Headers
    const headerStart1a = ['Date'];
    const headerStart1b = ['Date', 'Bill Date'];
    let headerStart1;
    parsedData.forEach(item => {
        if (item.hasOwnProperty('bil_dt')) {
            headerStart1 = headerStart1b;
        } else {
            headerStart1 = headerStart1a;
        }
    });
    const headerStart2 = ['Start Date', 'End Date'];
    const headerEnd1 = ['Total Sell'];
    const headerEnd2 = ['Total Margin'];
    const headerEnd3 = ['Total Discount'];

    let headerStart = [];
    let headerMid = [];
    let headerEnd = [];

    if (dayFilterVal.innerHTML == 0) {
        headerStart = headerStart1;
    } else {
        headerStart = headerStart2;
    }

    if (filterByVal.innerHTML === 'ICAT') {
        headerMid = slicedString(filterByProdCategoryNameVal.innerHTML);
    } else if (filterByVal.innerHTML === 'PM') {
        headerMid = slicedString(filterByPaymentModeVal.innerHTML);
    } else if (filterByVal.innerHTML === 'STF') {
        headerMid = slicedString(filterByStaffName.innerHTML);
    }

    if (reportFilterVal.innerHTML === 'Total Sell') {
        headerEnd = headerEnd1;
    } else if (reportFilterVal.innerHTML === 'Total Margin') {
        headerEnd = headerEnd2;
    } else if (reportFilterVal.innerHTML === 'Total Discount') {
        headerEnd = headerEnd3;
    }

    headerMid = [...new Set(headerMid)].sort();
    const headers = headerStart.concat(headerMid).concat(headerEnd);

    // Add header data1 to the worksheet with merged cells, center alignment, and specified font
    let currentRow = 1; 

    masterHeaderData1.forEach(rowData => {
        const mergeToColumn = headers.length;
        worksheet.mergeCells(`A${currentRow}:${String.fromCharCode(65 + mergeToColumn - 1)}${currentRow}`);
        const mergedCell = worksheet.getCell(`A${currentRow}`);
        mergedCell.value = rowData[0];
        mergedCell.alignment = { horizontal: 'center' }; // Center align the content
        mergedCell.font = { size: 14, bold: true }; // Set font size to 14 and bold
        currentRow++;
    });

    // Add header data2 to the worksheet with merged cells and center alignment
    masterHeaderData2.forEach(rowData => {
        const mergeToColumn = headers.length;
        worksheet.mergeCells(`A${currentRow}:${String.fromCharCode(65 + mergeToColumn - 1)}${currentRow}`);
        const mergedCell = worksheet.getCell(`A${currentRow}`);
        mergedCell.value = rowData[0];
        mergedCell.alignment = { horizontal: 'center' }; // Center align the content
        currentRow++;
    });


    // Add headers to the worksheet with color
    const headerRow = worksheet.addRow(headers);
    headerRow.eachCell(cell => {
        cell.font = { bold: true };
        cell.fill = {
            type: 'pattern',
            pattern: 'solid',
            fgColor: { argb: 'FFFF00' } // Yellow
        };
    });

    // Group data and add to worksheet
    const groupDataByKey = (data, keyFn) => {
        return data.reduce((acc, item) => {
            const key = keyFn(item);
            if (!acc[key]) {
                acc[key] = [];
            }
            acc[key].push(item);
            return acc;
        }, {});
    };

    let groupedData;
    if (dayFilterVal.innerHTML == 0) {
        groupedData = groupDataByKey(parsedData, item => item.added_on);
    } else if (dayFilterVal.innerHTML == 1) {
        groupedData = groupDataByKey(parsedData, item => item.start_date + ' to ' + item.end_date);
    } else if (dayFilterVal.innerHTML == 2) {
        groupedData = groupDataByKey(parsedData, item => `${item.year}-${item.month}`);
    }

    const createRows = (groupData, groupKey, headers) => {
        let rowData = {};

        if (dayFilterVal.innerHTML == 0) {
            rowData['Date'] = formatDate(groupKey);
        } else if (dayFilterVal.innerHTML == 1) {
            const [startDate, endDate] = groupKey.split(' to ');
            rowData['Start Date'] = formatDate(startDate);
            rowData['End Date'] = formatDate(endDate);
        } else if (dayFilterVal.innerHTML == 2) {
            const [year, month] = groupKey.split('-');
            rowData['Start Date'] = formatDate(groupData[0].start_date);
            rowData['End Date'] = formatDate(groupData[0].end_date);
        }

        let totalSellAmount = 0, totalMargin = 0, totalDiscount = 0;

        headerMid.forEach(header => {
            rowData[header] = 0.00;
        });

        groupData.forEach(data => {
            if (filterByVal.innerHTML === 'ICAT' && headerMid.includes(data.category_name)) {
                let amount = 0;
                if (reportFilterVal.innerHTML === 'Total Sell') {
                    amount = parseFloat(data.total_stock_out_amount || 0);
                } else if (reportFilterVal.innerHTML === 'Total Margin') {
                    amount = parseFloat(data.total_sales_margin || 0);
                } else if (reportFilterVal.innerHTML === 'Total Discount') {
                    amount = parseFloat(data.total_discount || 0);
                }

                rowData[data.category_name] += amount;
                totalSellAmount += amount;
                totalMargin += parseFloat(data.total_sales_margin || 0);
                totalDiscount += parseFloat(data.total_discount || 0);
            } else if (filterByVal.innerHTML === 'PM' && headerMid.includes(data.payment_mode)) {
                let amount = 0;
                if (reportFilterVal.innerHTML === 'Total Sell') {
                    amount = parseFloat(data.total_stock_out_amount || 0);
                } else if (reportFilterVal.innerHTML === 'Total Margin') {
                    amount = parseFloat(data.total_sales_margin || 0);
                } else if (reportFilterVal.innerHTML === 'Total Discount') {
                    amount = parseFloat(data.total_discount || 0);
                }

                rowData[data.payment_mode] += amount;
                totalSellAmount += amount;
                totalMargin += parseFloat(data.total_sales_margin || 0);
                totalDiscount += parseFloat(data.total_discount || 0);
            } else if (filterByVal.innerHTML === 'STF' && headerMid.includes(data.added_by_name)) {
                let amount = 0;
                if (reportFilterVal.innerHTML === 'Total Sell') {
                    amount = parseFloat(data.total_stock_out_amount || 0);
                } else if (reportFilterVal.innerHTML === 'Total Margin') {
                    amount = parseFloat(data.total_sales_margin || 0);
                } else if (reportFilterVal.innerHTML === 'Total Discount') {
                    amount = parseFloat(data.total_discount || 0);
                }

                rowData[data.added_by_name] += amount;
                totalSellAmount += amount;
                totalMargin += parseFloat(data.total_sales_margin || 0);
                totalDiscount += parseFloat(data.total_discount || 0);
            }
        });

        if (reportFilterVal.innerHTML === 'Total Sell') {
            rowData['Total Sell'] = totalSellAmount;
        } else if (reportFilterVal.innerHTML === 'Total Margin') {
            rowData['Total Margin'] = totalMargin;
        } else if (reportFilterVal.innerHTML === 'Total Discount') {
            rowData['Total Discount'] = totalDiscount;
        }

        return rowData;
    };

    Object.entries(groupedData).forEach(([groupKey, groupData]) => {
        const rowData = createRows(groupData, groupKey, headers);
        const dataRow = [];
        headers.forEach(header => {
            if (typeof rowData[header] === 'number') {
                dataRow.push(`₹${rowData[header].toFixed(2)}`);
            } else {
                dataRow.push(rowData[header] || '');
            }
        });
        worksheet.addRow(dataRow);
    });

    // Calculate and append the grand total row
    const grandTotals = {};
    headers.forEach(header => {
        grandTotals[header] = 0;
    });

    headers.forEach(header => {
        if (header !== 'Date' && header !== 'Bill Date' && header !== 'Start Date' && header !== 'End Date') {
            parsedData.forEach(data => {
                if (headerMid.includes(header)) {
                    if (filterByVal.innerHTML === 'ICAT' && data.category_name === header) {
                        if (reportFilterVal.innerHTML === 'Total Sell') {
                            grandTotals[header] += parseFloat(data.total_stock_out_amount || 0);
                        } else if (reportFilterVal.innerHTML === 'Total Margin') {
                            grandTotals[header] += parseFloat(data.total_sales_margin || 0);
                        } else if (reportFilterVal.innerHTML === 'Total Discount') {
                            grandTotals[header] += parseFloat(data.total_discount || 0);
                        }
                    } else if (filterByVal.innerHTML === 'PM' && data.payment_mode === header) {
                        if (reportFilterVal.innerHTML === 'Total Sell') {
                            grandTotals[header] += parseFloat(data.total_stock_out_amount || 0);
                        } else if (reportFilterVal.innerHTML === 'Total Margin') {
                            grandTotals[header] += parseFloat(data.total_sales_margin || 0);
                        } else if (reportFilterVal.innerHTML === 'Total Discount') {
                            grandTotals[header] += parseFloat(data.total_discount || 0);
                        }
                    } else if (filterByVal.innerHTML === 'STF' && data.added_by_name === header) {
                        if (reportFilterVal.innerHTML === 'Total Sell') {
                            grandTotals[header] += parseFloat(data.total_stock_out_amount || 0);
                        } else if (reportFilterVal.innerHTML === 'Total Margin') {
                            grandTotals[header] += parseFloat(data.total_sales_margin || 0);
                        } else if (reportFilterVal.innerHTML === 'Total Discount') {
                            grandTotals[header] += parseFloat(data.total_discount || 0);
                        }
                    }
                } else if (header === 'Total Sell') {
                    grandTotals[header] += parseFloat(data.total_stock_out_amount || 0);
                } else if (header === 'Total Margin') {
                    grandTotals[header] += parseFloat(data.total_sales_margin || 0);
                } else if (header === 'Total Discount') {
                    grandTotals[header] += parseFloat(data.total_discount || 0);
                }
            });
        }
    });

    const grandTotalRow = [];
    headers.forEach(header => {
        if (header !== 'Date' && header !== 'Bill Date' && header !== 'Start Date' && header !== 'End Date') {
            grandTotalRow.push(`₹${grandTotals[header].toFixed(2)}`);
        }
    });
    const grandTotalRowExcel = worksheet.addRow(['Grand Total', ...grandTotalRow]);

    // Style the grand total row
    grandTotalRowExcel.font = { bold: true };
    grandTotalRowExcel.eachCell({ includeEmpty: true }, (cell, colNumber) => {
        const header = headers[colNumber - 1];
        if (header.includes(header)) {
            cell.fill = {
                type: 'pattern',
                pattern: 'solid',
                fgColor: { argb: '90EE90' } // Light Green
            };
        }
    });

    // Save the Excel file
    workbook.xlsx.writeBuffer().then(buffer => {
        saveAs(new Blob([buffer]), `SalesReport_${currentDateTime}.xlsx`);
    });
}







// Function for export the table data to CSV
function exportToCSV(parsedData) {
    // Define header data
    const headerData1 = [healthCareName.innerHTML];
    const headerData2 = [
        healthCareAddress.innerHTML,
        "GSTIN : " + healthCareGstin.innerHTML,
        "",
        "Sales Summary Report : " + selectedStartDate.innerHTML + " To " + selectedEndDate.innerHTML,
        "Report generated at : " + reportGenerationTime.innerHTML,
        ""
    ];

    // Define headers from the data table
    const headerStart1a = ['Date'];
    const headerStart1b = ['Date', 'Bill Date'];
    let headerStart1;
    parsedData.forEach(item => {
        if (item.hasOwnProperty('bil_dt')) {
            headerStart1 = headerStart1b;
        } else {
            headerStart1 = headerStart1a;
        }
    });
    const headerStart2 = ['Start Date', 'End Date'];
    const headerEnd1 = ['Total Sell'];
    const headerEnd2 = ['Total Margin'];
    const headerEnd3 = ['Total Discount'];

    let headerStart = [];
    let headerMid = [];
    let headerEnd = [];

    if (dayFilterVal.innerHTML == 0) {
        headerStart = headerStart1;
    } else {
        headerStart = headerStart2;
    }

    if (filterByVal.innerHTML === 'ICAT') {
        headerMid = slicedString(filterByProdCategoryNameVal.innerHTML);
    } else if (filterByVal.innerHTML === 'PM') {
        headerMid = slicedString(filterByPaymentModeVal.innerHTML);
    } else if (filterByVal.innerHTML === 'STF') {
        headerMid = slicedString(filterByStaffName.innerHTML);
    }

    if (reportFilterVal.innerHTML === 'Total Sell') {
        headerEnd = headerEnd1;
    } else if (reportFilterVal.innerHTML === 'Total Margin') {
        headerEnd = headerEnd2;
    } else if (reportFilterVal.innerHTML === 'Total Discount') {
        headerEnd = headerEnd3;
    }

    headerMid = [...new Set(headerMid)].sort();
    const headers = headerStart.concat(headerMid).concat(headerEnd);

    // Initialize grand totals
    const grandTotals = {};
    headers.forEach(header => {
        grandTotals[header] = 0;
    });

    // Group data based on the filter values
    const groupDataByKey = (data, keyFn) => {
        return data.reduce((acc, item) => {
            const key = keyFn(item);
            if (!acc[key]) {
                acc[key] = [];
            }
            acc[key].push(item);
            return acc;
        }, {});
    };

    let groupedData;
    if (dayFilterVal.innerHTML == 0) {
        groupedData = groupDataByKey(parsedData, item => item.added_on);
    } else if (dayFilterVal.innerHTML == 1) {
        groupedData = groupDataByKey(parsedData, item => item.start_date + ' to ' + item.end_date);
    } else if (dayFilterVal.innerHTML == 2) {
        groupedData = groupDataByKey(parsedData, item => `${item.year}-${item.month}`);
    }

    const createRows = (groupData, groupKey) => {
        let rowData = {};

        if (dayFilterVal.innerHTML == 0) {
            rowData['Date'] = formatDate(groupKey);
        } else if (dayFilterVal.innerHTML == 1) {
            const [startDate, endDate] = groupKey.split(' to ');
            rowData['Start Date'] = formatDate(startDate);
            rowData['End Date'] = formatDate(endDate);
        } else if (dayFilterVal.innerHTML == 2) {
            const [year, month] = groupKey.split('-');
            rowData['Start Date'] = formatDate(groupData[0].start_date);
            rowData['End Date'] = formatDate(groupData[0].end_date);
        }

        let totalSellAmount = 0, totalMargin = 0, totalDiscount = 0;

        headerMid.forEach(header => {
            rowData[header] = 0.00;
        });

        groupData.forEach(data => {
            let amount = 0;
            if (filterByVal.innerHTML === 'ICAT' && headerMid.includes(data.category_name)) {
                amount = reportFilterVal.innerHTML === 'Total Sell' ? parseFloat(data.total_stock_out_amount || 0) :
                        reportFilterVal.innerHTML === 'Total Margin' ? parseFloat(data.total_sales_margin || 0) :
                        reportFilterVal.innerHTML === 'Total Discount' ? parseFloat(data.total_discount || 0) : 0;

                rowData[data.category_name] += amount;
                grandTotals[data.category_name] += amount; // Update grand totals
                totalSellAmount += amount;
                totalMargin += parseFloat(data.total_sales_margin || 0);
                totalDiscount += parseFloat(data.total_discount || 0);
            } else if (filterByVal.innerHTML === 'PM' && headerMid.includes(data.payment_mode)) {
                amount = reportFilterVal.innerHTML === 'Total Sell' ? parseFloat(data.total_stock_out_amount || 0) :
                        reportFilterVal.innerHTML === 'Total Margin' ? parseFloat(data.total_sales_margin || 0) :
                        reportFilterVal.innerHTML === 'Total Discount' ? parseFloat(data.total_discount || 0) : 0;

                rowData[data.payment_mode] += amount;
                grandTotals[data.payment_mode] += amount; // Update grand totals
                totalSellAmount += amount;
                totalMargin += parseFloat(data.total_sales_margin || 0);
                totalDiscount += parseFloat(data.total_discount || 0);
            } else if (filterByVal.innerHTML === 'STF' && headerMid.includes(data.added_by_name)) {
                amount = reportFilterVal.innerHTML === 'Total Sell' ? parseFloat(data.total_stock_out_amount || 0) :
                        reportFilterVal.innerHTML === 'Total Margin' ? parseFloat(data.total_sales_margin || 0) :
                        reportFilterVal.innerHTML === 'Total Discount' ? parseFloat(data.total_discount || 0) : 0;

                rowData[data.added_by_name] += amount;
                grandTotals[data.added_by_name] += amount; // Update grand totals
                totalSellAmount += amount;
                totalMargin += parseFloat(data.total_sales_margin || 0);
                totalDiscount += parseFloat(data.total_discount || 0);
            }
        });

        if (reportFilterVal.innerHTML === 'Total Sell') {
            rowData['Total Sell'] = totalSellAmount;
            grandTotals['Total Sell'] += totalSellAmount; // Update grand totals
        } else if (reportFilterVal.innerHTML === 'Total Margin') {
            rowData['Total Margin'] = totalMargin;
            grandTotals['Total Margin'] += totalMargin; // Update grand totals
        } else if (reportFilterVal.innerHTML === 'Total Discount') {
            rowData['Total Discount'] = totalDiscount;
            grandTotals['Total Discount'] += totalDiscount; // Update grand totals
        }

        return rowData;
    };

    const csvRows = [];

    // Add header data to CSV rows
    csvRows.push(headerData1[0]);
    headerData2.forEach(row => csvRows.push(row));
    csvRows.push(headers.join(','));

    // Process grouped data and add to CSV rows
    Object.entries(groupedData).forEach(([groupKey, groupData]) => {
        const rowData = createRows(groupData, groupKey);
        const dataRow = headers.map(header => {
            if (typeof rowData[header] === 'number') {
                return `${rowData[header].toFixed(2)}`;
            } else {
                return rowData[header] || '';
            }
        });
        csvRows.push(dataRow.join(','));
    });

    // Calculate grand totals for the CSV
    const grandTotalRow = ['Grand Total'];
    headers.forEach(header => {
        if (header !== 'Date' && header !== 'Bill Date' && header !== 'Start Date' && header !== 'End Date') {
            grandTotalRow.push(`${grandTotals[header] || 0.00}`);
        }
    });

    // Add grand total row to the CSV rows
    csvRows.push(grandTotalRow.join(','));

    // Convert to CSV string
    const csvData = csvRows.join('\n');

    // Create blob for CSV string
    const blob = new Blob([csvData], { type: 'text/csv' });

    // Create download link
    const link = document.createElement('a');
    link.href = URL.createObjectURL(blob);
    link.download = `SalesReport_${getCurrentDateTime()}.csv`;

    // Append link to DOM, simulate click, and remove link
    document.body.appendChild(link);
    link.click();
    document.body.removeChild(link);
}







/*
// function for exporting table data to pdf
async function exportToPDF() {
    const { jsPDF } = window.jspdf;
    const doc = new jsPDF();

    // Header data
    const headerData = [
        "Medicy Health Care",
        "Daulatabad Thanar More, Daulatabad",
        "GSTIN: ",
        "Sales Summary Report 01/06/2024 To 30/06/2024",
        "Report generated at: 26-06-24 01:10 PM"
    ];

    // Adding header data to PDF
    headerData.forEach((text, index) => {
        doc.text(text, 10, 10 + (index * 10)); // Adjust the Y coordinate as needed
    });

    // Retrieve headers and rows from the table
    const headers = Array.from(dataTable.querySelectorAll('th')).map(th => th.textContent);
    const rows = Array.from(dataTable.querySelectorAll('tbody tr')).map(tr => {
        return Array.from(tr.querySelectorAll('td')).map(td => td.textContent);
    });

    // Moving the table down to avoid overlapping with the header
    doc.autoTable({
        startY: 60, // Adjust the start position for the table
        head: [headers],
        body: rows,
    });

    doc.save('report.pdf');
}
*/



