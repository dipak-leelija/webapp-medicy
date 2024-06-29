
const xmlhttp = new XMLHttpRequest();

// TABLE CONSTANT
const dataTable = document.getElementById('report-table');

// DIV CONSTANT
const reportTypeFilter = document.getElementById('day-filter');  // primary filter select class
const dateRangeSelect = document.getElementById('date-range'); // date range select class
const categoryFilter = document.getElementById('category-filter'); // primary filter category select

const productCategorySelectDiv = document.getElementById('prod-category-select-div'); // item category select div secondary filter

const productCategoryBtn = document.getElementById('prod-category'); // item category select button

const paymentModeDiv = document.getElementById('payment-mode-div'); // payment mode select div secondary filter
const paymentMode = document.getElementById('payment-mode'); // payment mode select class

const staffFilterDiv = document.getElementById('staff-filter-div'); // staff select div secondary filter
const staffFilter = document.getElementById('staff-filter'); // staff select dropdown button

const reportFilterDiv = document.getElementById('report-filter-div'); // report generation on primary filter div
const salesReportOn = document.getElementById('sales-report-on'); // report generation on primary filter select

const dateRangeSelectDiv = document.getElementById('date-range-select-div');
const datePickerDiv = document.getElementById('dtPickerDiv');

const inputedDateRangeDiv = document.getElementById('inputed-date-range-div');

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
// download file format selection function
function selectDownloadType(ts){
    if(document.getElementById('download-checking').innerHTML == '1'){
        if(ts.value == 'exl'){
            exportToExcel();
            downloadType.selectedIndex = 0;
        }
        if(ts.value == 'csv'){
            exportToCSV();
            downloadType.selectedIndex = 0;
        }
        if(ts.value == 'pdf'){
            exportToPDF();
        }
    }else{
        alert('generate report first!');
        downloadType.selectedIndex = 0;
    }
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


// sales data search call (funning ajax query)
function salesSummerySearch() {
    let searchString = '';

    let dtFilter = dayFilterVal.innerHTML;

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
function salesDataSearchFunction(array){
    let arryString = JSON.stringify(array);
    let salesDataReport = `ajax/salesSummeryReport.ajax.php?dataArray=${arryString}`;
    xmlhttp.open("GET", salesDataReport, false);
    xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xmlhttp.send(null);
    let report = xmlhttp.responseText;

    // console.log(report);
    report = JSON.parse(report);
    if(report.status){
        reportShow(report.data);
    }else{
        alert('no data found');
    }
}



// dynamic table generation on data
let currentPage = 1;
const rowsPerPage = 30; // Define the number of rows per page


function reportShow(parsedData) {
    console.log(parsedData);
    document.getElementById('download-checking').innerHTML = '1';
    // Reset table data
    dataTable.innerHTML = '';

    let currentDateTime = getCurrentDateTime();
    reportGenerationTime.innerHTML = currentDateTime;

    // Define headers based on filter values
    const headerStart1 = ['Date'];
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

    // Determine middle headers based on filterByVal
    if (filterByVal.innerHTML === 'ICAT') {
        headerMid = slicedString(filterByProdCategoryNameVal.innerHTML);
    } else if (filterByVal.innerHTML === 'PM') {
        headerMid = slicedString(filterByPaymentModeVal.innerHTML);
    } else if (filterByVal.innerHTML === 'STF') {
        headerMid = slicedString(filterByStaffName.innerHTML);
    }

    // Header end on filter val
    if (reportFilterVal.innerHTML === 'Total Sell') {
        headerEnd = headerEnd1;
    } else if (reportFilterVal.innerHTML === 'Total Margin') {
        headerEnd = headerEnd2;
    } else if (reportFilterVal.innerHTML === 'Total Discount') {
        headerEnd = headerEnd3;
    }

    // Remove duplicate headers and sort middle headers
    headerMid = [...new Set(headerMid)].sort();
    const headers = headerStart.concat(headerMid).concat(headerEnd);

    // Create table headers
    const thead = document.createElement('thead');
    const tr = document.createElement('tr');
    headers.forEach(headerText => {
        const th = document.createElement('th');
        th.textContent = headerText;
        th.style.fontWeight = 'bold'; // Make the header bold
        tr.appendChild(th);
    });
    thead.appendChild(tr);

    // Append the header row to the table head
    dataTable.appendChild(thead);

    // Calculate and append the grand total row
    const grandTotalRow = document.createElement('tr');
    grandTotalRow.classList.add('grand-total');
    grandTotalRow.style.fontWeight = 'bold'; // Make the row bold

    // Initialize grand total for each header
    const grandTotals = {};
    headers.forEach(header => {
        grandTotals[header] = 0;
    });

    // Iterate through headers to calculate column totals and build the row
    headers.forEach(header => {
        // console.log(header);
        if (header !== 'Date' && header !== 'Start Date' && header !== 'End Date') {
            parsedData.forEach(data => {
                if (headerMid.includes(header)) {
                    if (filterByVal.innerHTML === 'ICAT' && data.category_name === header) {
                        grandTotals[header] += parseFloat(data.total_stock_out_amount || 0);
                    } else if (filterByVal.innerHTML === 'PM' && data.payment_mode === header) {
                        grandTotals[header] += parseFloat(data.total_amount || 0);
                    } else if (filterByVal.innerHTML === 'STF' && data.added_by_name === header) {
                        grandTotals[header] += parseFloat(data.total_stock_out_amount || 0);
                    }
                } else if(header === 'Total Sell'){
                    grandTotals[header] += parseFloat(data.total_stock_out_amount || 0);
                } else if(header === 'Total Margin'){
                    grandTotals[header] += parseFloat(data.total_sales_margin || 0);
                } else if(header === 'Total Discount'){
                    grandTotals[header] += parseFloat(data.total_discount || 0);
                }
            });
        }
    });

    // Create grand total row cells
    headers.forEach(header => {
        const cell = document.createElement('td');
        if (header !== 'Date' && header !== 'Start Date' && header !== 'End Date') {
            cell.textContent = `₹${grandTotals[header].toFixed(2)}`;
        } else {
            cell.textContent = ''; // Leave Date, Start Date, End Date cells empty
        }
        cell.className = 'total-cell';
        grandTotalRow.appendChild(cell);
    });

    // Append the grand total row to the table head
    thead.appendChild(grandTotalRow);

    // Create tbody and populate with data
    const tbody = document.createElement('tbody');

    // Function to group data by key
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

    if (dayFilterVal.innerHTML == 0) { // Day-wise
        groupedData = groupDataByKey(parsedData, item => item.added_on);
    } else if (dayFilterVal.innerHTML == 1) { // Week-wise
        groupedData = groupDataByKey(parsedData, item => item.start_date + ' to ' + item.end_date);
    } else if (dayFilterVal.innerHTML == 2) { // Month-wise
        groupedData = groupDataByKey(parsedData, item => `${item.year}-${item.month}`);
    }

    // Function to create rows for each group
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

        // Initialize middle headers to 0
        headerMid.forEach(header => {
            rowData[header] = 0.00;
        });

        // Process data for each group
        groupData.forEach(data => {
            // On Product category
            if (filterByVal.innerHTML === 'ICAT' && headerMid.includes(data.category_name)) {
                const stockOutAmount = parseFloat(data.total_stock_out_amount || 0);
                rowData[data.category_name] += stockOutAmount;
                totalSellAmount += stockOutAmount;
                totalMargin += parseFloat(data.total_sales_margin || 0);
                totalDiscount += parseFloat(data.total_discount || 0);
            }

            // On Payment mode
            if (filterByVal.innerHTML === 'PM' && headerMid.includes(data.payment_mode)) {
                const totalAmount = parseFloat(data.total_amount || 0);
                rowData[data.payment_mode] += totalAmount;
                totalSellAmount += totalAmount;
                totalMargin += parseFloat(data.total_sales_margin || 0);
                totalDiscount += parseFloat(data.total_discount || 0);
            }

            // On Staff name
            if (filterByVal.innerHTML === 'STF' && headerMid.includes(data.added_by_name)) {
                const stockOutAmount = parseFloat(data.total_stock_out_amount || 0);
                rowData[data.added_by_name] += stockOutAmount;
                totalSellAmount += stockOutAmount;
                totalMargin += parseFloat(data.total_sales_margin || 0);
                totalDiscount += parseFloat(data.total_discount || 0);
            }
        });

        // Set values based on reportFilterVal
        if (reportFilterVal.innerHTML === 'Total Sell') {
            rowData['Total Sell'] = `₹${totalSellAmount.toFixed(2)}`; // Add rupees sign
        } else if (reportFilterVal.innerHTML === 'Total Margin') {
            rowData['Total Margin'] = `₹${totalMargin.toFixed(2)}`; // Add rupees sign
        } else if (reportFilterVal.innerHTML === 'Total Discount') {
            rowData['Total Discount'] = `₹${totalDiscount.toFixed(2)}`; // Add rupees sign
        }

        // Create table cells for each header
        headers.forEach(header => {
            const td = document.createElement('td');
            td.className = header;
            td.textContent = rowData[header] !== undefined ? rowData[header] : '';
            tr.appendChild(td);
        });

        return tr;
    };

    // Display all data without pagination
    Object.keys(groupedData).forEach(groupKey => {
        const groupData = groupedData[groupKey];
        const tr = createRows(groupData, groupKey, headers);
        tbody.appendChild(tr);
    });

    // Append tbody to the table
    dataTable.appendChild(tbody);
}




/// exporting function gose down there
// Function for export the table data to Excel
function exportToExcel() {
    
    const headerData1 = [
        [healthCareName.innerHTML],
    ];
    const headerData2 = [
        [healthCareAddress.innerHTML],
        ["GSTIN : " + healthCareGstin.innerHTML],
        [],
        ["Sales Summary Report : " + selectedStartDate.innerHTML + " To " + selectedEndDate.innerHTML],
        ["Report generated at : " + reportGenerationTime.innerHTML],
    ];

    const headers = Array.from(dataTable.querySelectorAll('th')).map(th => th.textContent);
    const rows = Array.from(dataTable.querySelectorAll('tbody tr')).map(tr => {
        return Array.from(tr.querySelectorAll('td')).map(td => td.textContent);
    });

    // Calculate grand totals for each column (excluding the first column which is the date column)
    const grandTotals = headers.map((_, colIndex) => {
        if (colIndex === 0) return "Grand Total"; // Label for the first column
        return rows.reduce((sum, row) => {
            const value = row[colIndex].replace(/[^0-9.-]+/g, ""); // Remove non-numeric characters like currency symbols
            return sum + (parseFloat(value) || 0);
        }, 0).toFixed(2); // Sum and format as needed
    });

    // Create a new Excel workbook
    const workbook = new ExcelJS.Workbook();
    const worksheet = workbook.addWorksheet('Report');

    // Add header data1 to the worksheet with merged cells, center alignment, and specified font
    let currentRow = 1; // Start at row 1

    headerData1.forEach(rowData => {
        const mergeToColumn = headers.length > 0 ? headers.length : 1; // Merge across all columns if headers exist
        worksheet.mergeCells(`A${currentRow}:${String.fromCharCode(65 + mergeToColumn - 1)}${currentRow}`);
        const mergedCell = worksheet.getCell(`A${currentRow}`);
        mergedCell.value = rowData[0];
        mergedCell.alignment = { horizontal: 'center' }; // Center align the content
        mergedCell.font = { size: 14, bold: true }; // Set font size to 14 and bold
        currentRow++;
    });

    // Add header data2 to the worksheet with merged cells and center alignment
    headerData2.forEach(rowData => {
        const mergeToColumn = headers.length > 0 ? headers.length : 1; // Merge across all columns if headers exist
        worksheet.mergeCells(`A${currentRow}:${String.fromCharCode(65 + mergeToColumn - 1)}${currentRow}`);
        const mergedCell = worksheet.getCell(`A${currentRow}`);
        mergedCell.value = rowData[0];
        mergedCell.alignment = { horizontal: 'center' }; // Center align the content
        currentRow++;
    });

    // Add an empty row for spacing
    worksheet.addRow([]);

    // Add headers row to the worksheet and apply bold font
    const headersRow = worksheet.addRow(headers);
    headersRow.eachCell((cell, colNumber) => {
        cell.font = { bold: true };
        // Style the header cells with a yellow background
        cell.fill = {
            type: 'pattern',
            pattern: 'solid',
            fgColor: { argb: 'FFFF00' } // Yellow background color
        };
    });

    // Add rows to the worksheet
    rows.forEach(row => {
        const excelRow = worksheet.addRow(row);
        excelRow.eachCell((cell, colNumber) => {
            // Skip styling for the first cell (date column)
            if (colNumber > 1) {
                // Check if cell value is numeric
                const isNumeric = !isNaN(parseFloat(cell.value)) && isFinite(cell.value);
                if (isNumeric) {
                    // Left align numeric cells
                    cell.alignment = { horizontal: 'left' };
                }
            }
        });
    });

    // Add the grand totals row and style its cells with green background and bold font
    const grandTotalRow = worksheet.addRow(grandTotals);
    grandTotalRow.eachCell((cell, colNumber) => {
        // Apply bold font to all cells in the grand totals row
        cell.font = { bold: true };
        // Skip styling for the first cell (Grand Total label)
        if (colNumber > 0) {
            cell.fill = {
                type: 'pattern',
                pattern: 'solid',
                fgColor: { argb: '00FF00' } // Green background color
            };
        }
    });

    // Generate Excel file
    workbook.xlsx.writeBuffer().then(buffer => {
        const blob = new Blob([buffer], { type: 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet' });
        saveAs(blob, 'report.xlsx');
    });
}







// Function for export the table data to CSV
function exportToCSV() {
    const headerData = [
        [healthCareName.innerHTML],
        [healthCareAddress.innerHTML],
        ["GSTIN : " + healthCareGstin.innerHTML],
        [],
        ["Sales Summary Report : " + selectedStartDate.innerHTML + " To " + selectedEndDate.innerHTML],
        ["Report generated at : " + reportGenerationTime.innerHTML],
        []
    ];

    const headers = Array.from(dataTable.querySelectorAll('th')).map(th => th.textContent); // get headers

    // get rows from the table
    const rows = Array.from(dataTable.querySelectorAll('tbody tr')).map(tr => {
        return Array.from(tr.querySelectorAll('td')).map(td => td.textContent);
    });

    // Calculate grand totals for each column (excluding the first column which is typically non-numeric)
    const grandTotals = rows[0].map((_, colIndex) => {
        if (colIndex === 0) return "Grand Total"; // Label for the first column
        return rows.reduce((sum, row) => {
            return sum + (parseFloat(row[colIndex]) || 0);
        }, 0).toFixed(2); // Sum and format as needed
    });

    // Add grand totals row to the end of the rows array
    const csvData = [...headerData, headers, ...rows];

    // Convert array to CSV string
    const csvString = csvData.map(row => row.join(',')).join('\n');

    // Create blob for CSV string
    const blob = new Blob([csvString], { type: 'text/csv' });

    // Create download link
    const link = document.createElement('a');
    link.href = URL.createObjectURL(blob);
    link.download = 'report.csv';

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



