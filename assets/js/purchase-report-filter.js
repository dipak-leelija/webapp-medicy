const xmlhttp = new XMLHttpRequest();

const reportFilterVal = document.getElementById('selected-report-type');
const selectedPurchaseType = document.getElementById('selected-purchse-type');

// dynamic table
const stockInStockReturnGstReportTable = document.getElementById('gst-purchase-purchasereturn-table');

// additional data holder
const downloadType = document.getElementById('download-file-type');
const healthCareName = document.getElementById('healthcare-name');
const healthCareGstin = document.getElementById('healthcare-gstin');
const healthCareAddress = document.getElementById('healthcare-address');
const reportGenerationTime = document.getElementById('report-generation-date-time-holder');
const selectedStartDate = document.getElementById('selected-date');


// date picker control
$(function() {
    // Set the current date in format "MMMM YYYY" (e.g., "July 2024")
    var currentDate = moment().format('MMMM YYYY');
    $('#selected-month').html(currentDate);

    // Initialize the date picker
    $('#date-input').daterangepicker({
        singleDatePicker: true, // Single date picker
        showDropdowns: true, // Year and month controls
        autoUpdateInput: false, // Don't auto update the input
        autoApply: true, // Automatically apply the date selection
        maxDate: moment(), // Disable future dates
        locale: {
            format: 'MMMM YYYY',
            cancelLabel: 'Clear'
        },
        isInvalidDate: function(date) {
            return date.date() !== 1; // Disable all dates except the first of each month
        }
    });

    // Event handler for applying the date
    $('#date-input').on('apply.daterangepicker', function(ev, picker) {
        $('#selected-month').html(picker.startDate.format('MMMM YYYY'));
    });

    // Open the date picker when clicking on the div
    $('#date-range-select-div').on('click', function() {
        $('#date-input').data('daterangepicker').toggle();
    });
});



function filterReportType(t){
    if(t.value == 'P'){
        reportFilterVal.innerHTML = 'P';
    }

    if(t.value == 'PR'){
        reportFilterVal.innerHTML = 'PR';
    }
}

function filterPurchaseType(t){
    if(t.value == 'WG'){
        selectedPurchaseType.innerHTML = 'WG';
    }

    if(t.value == 'NG'){
        selectedPurchaseType.innerHTML = 'NG';
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

// date range creater from month and year
function getMonthRange(month, year) {
    
    const monthNumber = new Date(Date.parse(month +" 1, 2012")).getMonth() + 1;
    const monthStr = monthNumber.toString().padStart(2, '0');
    const startDate = `01-${monthStr}-${year}`;
    const endDate = new Date(year, monthNumber, 0); 
    const endDateStr = `${endDate.getDate().toString().padStart(2, '0')}-${monthStr}-${year}`;

    return {
        startDate : startDate, 
        endDate : endDateStr
    };
}

function convertDateFormat(dateStr) {
    const [day, month, year] = dateStr.split('-');
    const newDateStr = `${year}-${month}-${day}`;

    return newDateStr;
}

function filterSearch(){
    let selectedMonth = $('#selected-month').text();
    const arr = selectedMonth.split(" ");
    let dateRange =  getMonthRange(arr[0], arr[1]);
    let convertedStartDate = convertDateFormat(dateRange.startDate);
    let convertedEndDate = convertDateFormat(dateRange.endDate);

    if(reportFilterVal.innerHTML == ''){
        alert('select report type');
        return;
    }

    if(selectedPurchaseType.innerHTML == ''){
        alert('select purchase type type');
        return;
    }

    let dataArray = {
        searchOn: reportFilterVal.innerHTML,
        startDate: convertedStartDate,
        endDate: convertedEndDate,
    }   

    gstPurchaseReportSearch(dataArray);
}

// item mergin data search function (ajax call)
function gstPurchaseReportSearch(array){

    // console.log(array);

    let arryString = JSON.stringify(array);
    let salesDataReport = `ajax/gstPurchaseReport.ajax.php?dataArray=${arryString}`;
    xmlhttp.open("GET", salesDataReport, false);
    xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xmlhttp.send(null);
    let report = xmlhttp.responseText;

    report = JSON.parse(report);
    // console.log(report);
    if(report.status == '1'){
        reportShow(report.data);
    }else{
        stockInStockReturnGstReportTable.innerHTML = '';
        alert('no data found');
    }
}


function reportShow(reportData) {
    console.log(reportData);
    // Clear the table and other elements
    stockInStockReturnGstReportTable.innerHTML = '';
    document.getElementById('download-checking').innerHTML = '1';

    // Set the current date and time
    let currentDateTime = getCurrentDateTime();
    reportGenerationTime.innerHTML = currentDateTime;

    // Define headers based on filter values
    let header = '';
    const header1 = ['Sl No', 'Bill No', 'Entry Date', 'Bill Date', 'Distributor', 'Taxable', 'CESS', 'SGST ', 'CGST', 'IGST', 'Total'];

    const header2 = ['Sl No', 'Bill No', 'Entry Date', 'Bill Date', 'Distributor', 'Taxable', 'Total'];

    if(selectedPurchaseType.innerHTML == 'WG'){
        header = header1;
    }
    if(selectedPurchaseType.innerHTML == 'NG'){
        header = header2;
    }

    // Create table headers
    const thead = document.createElement('thead');

    // Add main column headers row
    const tr = document.createElement('tr');
    header.forEach((headerText, index) => {
        const th = document.createElement('th');
        th.textContent = headerText;
        th.style.fontWeight = 'bold'; // Make the header bold
        // Right align specific headers
        if (index > 4 && index <= 10) {
            th.style.textAlign = 'right';
        }
        tr.appendChild(th);
    });
    thead.appendChild(tr);

    // Append the header row to the table head
    stockInStockReturnGstReportTable.appendChild(thead);

    // Create table body
    const tbody = document.createElement('tbody');
    let slNo = 0;
    // Populate the table with report data
    reportData.forEach(data => {
        slNo++;
        const row = document.createElement('tr');
        
        const slNoCell = document.createElement('td');
        slNoCell.textContent = slNo || ''; // Add bill date data

        const billNoCell = document.createElement('td');
        billNoCell.textContent = data.bill_no || ''; // Add bill number data

        const entryDateCell = document.createElement('td');
        entryDateCell.textContent = data.added_on || ''; // Add distributor name data

        const billDateCell = document.createElement('td');
        billDateCell.textContent = data.bill_date || ''; // Add item name data

        const distributorCell = document.createElement('td');
        distributorCell.textContent = data.dist_name || ''; // Add quantity data
       
        let taxable = (parseFloat(data.total_Paid_on_item) - parseFloat(data.total_gst_amount));
        const taxableCell = document.createElement('td');
        taxableCell.textContent = parseFloat(taxable).toFixed(2); // Format to 2 decimal places
        taxableCell.style.textAlign = 'right'; // Right align the text

        let cess = 0;
        const cessCell = document.createElement('td');
        cessCell.textContent = parseFloat(cess);
        cessCell.style.textAlign = 'right';

        let sgst = (parseFloat(data.total_gst_amount)/2).toFixed(2);
        let sgstParcetn = (parseFloat(data.total_gst_percent)/2).toFixed(2);
        const sgstCell = document.createElement('td');
        sgstCell.textContent = parseFloat(sgst)+' ('+sgstParcetn+'%)';
        sgstCell.style.textAlign = 'right';
        
        let cgst = (parseFloat(data.total_gst_amount)/2).toFixed(2);
        let cgstParcetn = (parseFloat(data.total_gst_percent)/2).toFixed(2);
        const cgstCell = document.createElement('td');
        cgstCell.textContent = parseFloat(cgst)+' ('+cgstParcetn+'%)';
        cgstCell.style.textAlign = 'right';

        let igst = 0;
        const igstCell = document.createElement('td');
        igstCell.textContent = parseFloat(igst);
        igstCell.style.textAlign = 'right';

        const totalAmountCell = document.createElement('td');
        totalAmountCell.textContent = data.total_Paid_on_item;
        totalAmountCell.style.textAlign = 'right';


        if(selectedPurchaseType.innerHTML == 'WG'){
            row.appendChild(slNoCell);
            row.appendChild(billNoCell);
            row.appendChild(entryDateCell);
            row.appendChild(billDateCell);
            row.appendChild(distributorCell);
            row.appendChild(taxableCell);
            row.appendChild(cessCell);
            row.appendChild(sgstCell);
            row.appendChild(cgstCell);
            row.appendChild(igstCell);
            row.appendChild(totalAmountCell);
            
        }
        if(selectedPurchaseType.innerHTML == 'NG'){
            row.appendChild(slNoCell);
            row.appendChild(billNoCell);
            row.appendChild(entryDateCell);
            row.appendChild(billDateCell);
            row.appendChild(distributorCell);
            row.appendChild(taxableCell);
            row.appendChild(totalAmountCell);
        }

        tbody.appendChild(row);
    });

    // Append the table body to the table
    stockInStockReturnGstReportTable.appendChild(tbody);
}
