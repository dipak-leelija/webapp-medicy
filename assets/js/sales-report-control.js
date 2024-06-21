
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

const filterByProdCategoryIdVal = document.getElementById('filter-by-prod-categoty-id-val');
const filterByProdCategoryNameVal = document.getElementById('filter-by-prod-categoty-name');

const filterByPaymentModeVal = document.getElementById('filter-by-payment-mode-val');
const filterByStaffVal = document.getElementById('filter-by-staff-val');
const reportFilterVal = document.getElementById('report-filter-val');

const selectedStartDate = document.getElementById('selected-start-date');
const selectedEndDate = document.getElementById('selected-end-date');

const inputedDateRange = document.getElementById('inputed-date-range');


/// dropdown inner html constant
const paymentModeConst = document.getElementById('payment-mode-select-span');


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
            console.log("check 1");
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
                console.log("check 2");
                productCategoryBtn.innerHTML = source.id;
                filterByProdCategoryIdVal.innerHTML = source.value;
                filterByProdCategoryNameVal.innerHTML = source.id;
            }else{
                console.log("check 3");
                let selectedCategoryIdString = filterByProdCategoryIdVal.innerHTML;
                let selectedCategoryNameString = filterByProdCategoryNameVal.innerHTML;
                let updatedCategoryId = selectedCategoryIdString + ',' + source.value;
                let updatedCategoryName = selectedCategoryNameString + ',' + source.id;
                filterByProdCategoryIdVal.innerHTML = updatedCategoryId;
                filterByProdCategoryNameVal.innerHTML = updatedCategoryName;
            }
        }else{
            if(filterByProdCategoryIdVal.innerHTML != '' && filterByProdCategoryNameVal.innerHTML != ''){
                console.log("check 4");
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

    console.log(filterByProdCategoryIdVal.innerHTML);
    console.log(filterByProdCategoryNameVal.innerHTML);

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

    console.log(filterByPaymentModeVal.innerHTML);
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
    let searchString = '';

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
    }

    // primary report generator filter based on select
    if(reportFilterVal.innerHTML == ''){
        alert('select filter');
        return;
    }

    let startDate = convertDateFormatToBig(selectedStartDate.innerHTML);
    let endDate = convertDateFormatToBig(selectedEndDate.innerHTML);

    let dataArray = {
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
    }
}


// creating report and show it
function reportShow(parsedData) {
    console.log(parsedData);
    dataTable.innerHTML = ''; // reset table data
    var dateArray = [];
    var headerStart = ['Date'];
    var headerMid = [];
    var headerEnd1 = ['Total Sell'];
    var headerEnd2 = ['Total Margin'];
    var headerEnd3 = ['Total Discount'];
    var headerEnd = [];

    if (filterByVal.innerHTML == 'ICAT') {
        headerMid = slicedString(filterByProdCategoryNameVal.innerHTML);
    } else if (filterByVal.innerHTML == 'PM') {
        headerMid = slicedString(filterByPaymentModeVal.innerHTML);
    }

    if (reportFilterVal.innerHTML == 'Total Sell') {
        headerEnd = headerEnd1;
    } else if (reportFilterVal.innerHTML == 'Total Margin') {
        headerEnd = headerEnd2;
    } else if (reportFilterVal.innerHTML == 'Total Discount') {
        headerEnd = headerEnd3;
    }

    headerMid = [...new Set(headerMid)].sort();
    const headers = headerStart.concat(headerMid).concat(headerEnd);

    const thead = document.createElement('thead');
    const tr = document.createElement('tr');
    headers.forEach(headerText => {
        const th = document.createElement('th');
        th.textContent = headerText;
        tr.appendChild(th);
    });
    thead.appendChild(tr);
    dataTable.appendChild(thead);

    const tbody = document.createElement('tbody');
    parsedData.forEach(item => {
        dateArray.push(item.added_on);
    });

    var uniqueDateArray = [...new Set(dateArray)];
    var parsedDataArray = Object.values(parsedData);

    uniqueDateArray.forEach(uniqueDate => {
        const tr = document.createElement('tr');
        let rowData = {
            Date: formatDate(uniqueDate)
        };

        let cashAmount = 0, creditAmount = 0, upiAmount = 0, cardAmount = 0;
        let allopathyAmount = 0, ayurvedicAmount = 0, cosmeticAmount = 0, drugAmount = 0, genericAmount = 0, nutraceuticalsAmount = 0, otcAmount = 0, surgicalAmount = 0;
        let totalSellAmount = 0, totalMargin = 0, totalDiscount = 0;

        parsedDataArray.forEach(data => {
            if (data.added_on === uniqueDate) {
                if (filterByVal.innerHTML == 'PM') {
                    if (data.payment_mode == 'Cash') cashAmount += parseFloat(data.total_amount);
                    if (data.payment_mode == 'Credit') creditAmount += parseFloat(data.total_amount);
                    if (data.payment_mode == 'UPI') upiAmount += parseFloat(data.total_amount);
                    if (data.payment_mode == 'Card') cardAmount += parseFloat(data.total_amount);

                    totalMargin += parseFloat(data.total_sales_margin);

                    totalSellAmount = cashAmount + creditAmount + upiAmount + cardAmount;
                }

                if (filterByVal.innerHTML == 'ICAT') {
                    if (data.category_name == 'Allopathy') allopathyAmount += parseFloat(data.total_stock_out_amount);
                    if (data.category_name == 'Ayurvedic') ayurvedicAmount += parseFloat(data.total_stock_out_amount);
                    if (data.category_name == 'Cosmetic') cosmeticAmount += parseFloat(data.total_stock_out_amount);
                    if (data.category_name == 'Drug') drugAmount += parseFloat(data.total_stock_out_amount);
                    if (data.category_name == 'Generic') genericAmount += parseFloat(data.total_stock_out_amount);
                    if (data.category_name == 'Nutraceuticals') nutraceuticalsAmount += parseFloat(data.total_stock_out_amount);
                    if (data.category_name == 'OTC') otcAmount += parseFloat(data.total_stock_out_amount);
                    if (data.category_name == 'Surgical') surgicalAmount += parseFloat(data.total_stock_out_amount);

                    totalMargin += parseFloat(data.total_sales_margin);

                    totalSellAmount = allopathyAmount + ayurvedicAmount + cosmeticAmount + drugAmount + genericAmount + nutraceuticalsAmount + otcAmount + surgicalAmount;
                }
                
            }
        });

        if (filterByVal.innerHTML == 'PM') {
            rowData['Cash'] = cashAmount.toFixed(2);
            rowData['Credit'] = creditAmount.toFixed(2);
            rowData['UPI'] = upiAmount.toFixed(2);
            rowData['Card'] = cardAmount.toFixed(2);
        }

        if (filterByVal.innerHTML == 'ICAT') {
            rowData['Allopathy'] = allopathyAmount.toFixed(2);
            rowData['Ayurvedic'] = ayurvedicAmount.toFixed(2);
            rowData['Cosmetic'] = cosmeticAmount.toFixed(2);
            rowData['Drug'] = drugAmount.toFixed(2);
            rowData['Generic'] = genericAmount.toFixed(2);
            rowData['Nutraceuticals'] = nutraceuticalsAmount.toFixed(2);
            rowData['OTC'] = otcAmount.toFixed(2);
            rowData['Surgical'] = surgicalAmount.toFixed(2);
        }

        if (reportFilterVal.innerHTML == 'Total Sell') {
            rowData['Total Sell'] = totalSellAmount.toFixed(2);
        } else if (reportFilterVal.innerHTML == 'Total Margin') {
            rowData['Total Margin'] = totalMargin.toFixed(2);
        } else if (reportFilterVal.innerHTML == 'Total Discount') {
            rowData['Total Discount'] = totalDiscount.toFixed(2);
        }

        headers.forEach(header => {
            const td = document.createElement('td');
            td.className = header;
            td.textContent = rowData[header] || '';
            tr.appendChild(td);
        });

        tbody.appendChild(tr);
    });

    dataTable.appendChild(tbody);
}
