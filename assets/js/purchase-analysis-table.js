const xmlhttp = new XMLHttpRequest();

const selectedDateSpan = document.getElementById('selected-date');
const searchedData = document.getElementById('search-by-data');
const itemSearchVal = document.getElementById('search-val');

// data holding labels
const downloadType = document.getElementById('download-file-type');
const healthCareName = document.getElementById('healthcare-name');
const healthCareGstin = document.getElementById('healthcare-gstin');
const healthCareAddress = document.getElementById('healthcare-address');
const reportGenerationTime = document.getElementById('report-generation-date-time-holder');
const selectedStartDate = document.getElementById('selected-date');

// buttons
const reset1 = document.getElementById('search-reset-1');

// dynamic table 
const itemMarginTable = document.getElementById('purchase-analysis-table');

// calender control 
$(function() {
    // Set the current date
    var currentDate = moment().format('DD-MM-YYYY');
    $('#selected-date').html(currentDate);

    // Initialize the date picker
    $('#date-range-select-div').daterangepicker({
        singleDatePicker: true, // Single date picker
        showDropdowns: true, // Year and month controls
        autoUpdateInput: false, // Don't auto update the input
        autoApply: true, // Automatically apply the date selection
        maxDate: moment(), // Disable future dates
        locale: {
            format: 'DD-MM-YYYY'
        }
    });

    // Event handler for applying the date
    $('#date-range-select-div').on('apply.daterangepicker', function(ev, picker) {
        $('#selected-date').html(picker.startDate.format('DD-MM-YYYY'));
    });
});


// date splitter
function separateDates(dateRange) {
    let dates = dateRange.split(' to ');
    let startDate = dates[0];
    let endDate = dates[1];
    return {
        startDate: startDate,
        endDate: endDate
    };
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

// convert date format
function convertDateFormat(dateStr) {
    const [day, month, year] = dateStr.split('-');
    const newDateStr = `${year}-${month}-${day}`;

    return newDateStr;
}




// close button contorl
function resteUrl(){
    searchedData.value = '';
    reset1.style.display = 'none';
    purchaseAnalysisSearch();
}

function purchaseAnalysisSearch(){

    let searchValue = searchedData.value;
    let searchDate = selectedDateSpan.innerHTML;

    if(searchValue != ''){
        reset1.style.display = 'block';
    }

    let convertedDate = convertDateFormat(searchDate);
    
    let dataArray = {
        searchDate: convertedDate,
        searchOn: searchValue,
    };

    purchaseAnalysisDataSearch(dataArray);
}



// item mergin data search function (ajax call)
function purchaseAnalysisDataSearch(array){

    let arryString = JSON.stringify(array);
    let salesDataReport = `ajax/purchaseAnalysis.ajax.php?dataArray=${arryString}`;
    xmlhttp.open("GET", salesDataReport, false);
    xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xmlhttp.send(null);
    let report = xmlhttp.responseText;

    
    
    report = JSON.parse(report);
    
    if(report.status == '1'){
        reportShow(report.data);
    }else{
        itemMarginTable.innerHTML = '';
        alert('no data found');
    }
}


function reportShow(reportData) {
    // Clear the table and other elements
    itemMarginTable.innerHTML = '';
    document.getElementById('download-checking').innerHTML = '1';

    // Set the current date and time
    let currentDateTime = getCurrentDateTime();
    reportGenerationTime.innerHTML = currentDateTime;

    // Define headers based on filter values
    const header = ['Bill Date', 'Bill No', 'Distributo Name', 'Item Name', 'Qty', 'MRP', 'PTR', 'Margin %', 'Margin Difference %', 'Margin Difference ₹', 'Avg. Margin%'];

    // Create table headers
    const thead = document.createElement('thead');

    // Add main column headers row
    const tr = document.createElement('tr');
    header.forEach(headerText => {
        const th = document.createElement('th');
        th.textContent = headerText;
        th.style.fontWeight = 'bold'; // Make the header bold
        tr.appendChild(th);
    });
    thead.appendChild(tr);

    // Append the header row to the table head
    itemMarginTable.appendChild(thead);

    // Create table body
    const tbody = document.createElement('tbody');

    // Populate the table with report data
    reportData.forEach(data => {
        const row = document.createElement('tr');

        const addedOnCell = document.createElement('td');
        addedOnCell.textContent = data.bill_date || ''; // Add bill date data
        row.appendChild(addedOnCell);

        const billNoCell = document.createElement('td');
        billNoCell.textContent = data.bill_no || ''; // Add bill number data
        row.appendChild(billNoCell);

        const distNameCell = document.createElement('td');
        distNameCell.textContent = data.dist_name || ''; // Add distributor name data
        row.appendChild(distNameCell);

        const itemNameCell = document.createElement('td');
        itemNameCell.textContent = data.item_name || ''; // Add item name data
        row.appendChild(itemNameCell);

        const itemQtyCell = document.createElement('td');
        itemQtyCell.textContent = data.qty || ''; // Add quantity data
        itemQtyCell.style.textAlign = 'right'; // Right align the text
        row.appendChild(itemQtyCell);

        const itemMrpCell = document.createElement('td');
        itemMrpCell.textContent = parseFloat(data.mrp).toFixed(2); // Format to 2 decimal places
        itemMrpCell.style.textAlign = 'right'; // Right align the text
        row.appendChild(itemMrpCell);

        const itemPtrCell = document.createElement('td');
        itemPtrCell.textContent = parseFloat(data.ptr).toFixed(2); // Format to 2 decimal places
        itemPtrCell.style.textAlign = 'right'; // Right align the text
        row.appendChild(itemPtrCell);

        const itemMerginCell = document.createElement('td');
        itemMerginCell.textContent = parseFloat(data.margin).toFixed(2); // Format to 2 decimal places
        itemMerginCell.style.textAlign = 'right'; // Right align the text
        row.appendChild(itemMerginCell);

        // calculate margin difference %

        // calculate margin difference amount

        // calculate average margin percent

        // Append the row to the table body
        tbody.appendChild(row);
    });

    // Append the table body to the table
    itemMarginTable.appendChild(tbody);
}





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


// exporting function gose down there
// Function for export the table data to Excel
function exportToExcel() {
    const headerData1 = [
        [healthCareName.innerHTML],
    ];
    const headerData2 = [
        [healthCareAddress.innerHTML],
        ["GSTIN : " + healthCareGstin.innerHTML],
        [],
        ["Sales Summary Report : " + selectedStartDate.innerHTML],
        ["Report generated at : " + reportGenerationTime.innerHTML],
    ];

    const headers = Array.from(itemMarginTable.querySelectorAll('th')).map(th => th.textContent);
    const rows = Array.from(itemMarginTable.querySelectorAll('tbody tr')).map(tr => {
        return Array.from(tr.querySelectorAll('td')).map(td => td.textContent);
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
    currentRow++; // Increment row index for spacing

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
        worksheet.addRow(row);
    });

    // Generate Excel file
    workbook.xlsx.writeBuffer().then(buffer => {
        const blob = new Blob([buffer], { type: 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet' });
        saveAs(blob, 'purchase-analysis-report.xlsx');
    });
}





// export to csv
function exportToCSV() {
    const headerData1 = [
        healthCareName.innerHTML,
    ];
    const headerData2 = [
        healthCareAddress.innerHTML,
        "GSTIN : " + healthCareGstin.innerHTML,
        "",
        "Sales Summary Report : " + selectedStartDate.innerHTML,
        "Report generated at : " + reportGenerationTime.innerHTML,
    ];

    const headers = Array.from(itemMarginTable.querySelectorAll('th')).map(th => th.textContent);
    const rows = Array.from(itemMarginTable.querySelectorAll('tbody tr')).map(tr => {
        return Array.from(tr.querySelectorAll('td')).map(td => td.textContent);
    });

    // Initialize CSV content
    let csvContent = "";

    // Add header data1 to the CSV content with merged cells (simulated by joining columns)
    headerData1.forEach(rowData => {
        csvContent += rowData + "\n";
    });

    // Add header data2 to the CSV content with merged cells (simulated by joining columns)
    headerData2.forEach(rowData => {
        csvContent += rowData + "\n";
    });

    // Add an empty row for spacing
    csvContent += "\n";

    // Add headers row to the CSV content
    csvContent += headers.join(",") + "\n";

    // Add rows to the CSV content
    rows.forEach(row => {
        csvContent += row.join(",") + "\n";
    });

    // Create a Blob from the CSV content
    const blob = new Blob([csvContent], { type: 'text/csv;charset=utf-8;' });
    const url = URL.createObjectURL(blob);

    // Create a link to download the CSV file
    const link = document.createElement("a");
    link.setAttribute("href", url);
    link.setAttribute("download", "purchase-analysis-report.csv");
    document.body.appendChild(link);

    // Simulate a click to trigger the download
    link.click();

    // Clean up
    document.body.removeChild(link);
    URL.revokeObjectURL(url);
}
