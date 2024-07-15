const xmlhttp = new XMLHttpRequest();

const selectedDateSpan = document.getElementById('selected-date');
const selectedDateRange = document.getElementById('selected-date-range');
const searchedItem = document.getElementById('search-by');

// data holding labels
const healthCareName = document.getElementById('healthcare-name');
const healthCareGstin = document.getElementById('healthcare-gstin');
const healthCareAddress = document.getElementById('healthcare-address');
const reportGenerationTime = document.getElementById('report-generation-date-time-holder');
const selectedStartDate = document.getElementById('selected-start-date');
const selectedEndDate = document.getElementById('selected-end-date');

// buttons
const reset1 = document.getElementById('search-reset-1');

// dynamic table 
const itemExpiaryReportTable = document.getElementById('expiry-report-table');

// date picker div range control script
$(function() {
    function cb(start, end) {
        $('#date-range-select-div span').html(start.format('DD-MM-YYYY') + ' to ' + end.format('DD-MM-YYYY'));
    }

    $('#date-range-select-div').daterangepicker({
        autoUpdateInput: false, // initial value
        showDropdowns: true, // year and month controls
        locale: {
            format: 'DD-MM-YYYY',
            cancelLabel: 'Clear'
        },
        ranges: {
            'Last 30 Days': [moment().subtract(29, 'days'), moment()],
            'Last 45 Days': [moment().subtract(44, 'days'), moment()],
            'Last 60 Days': [moment().subtract(59, 'days'), moment()],
            'Last 90 Days': [moment().subtract(89, 'days'), moment()],
            'Last 120 Days': [moment().subtract(119, 'days'), moment()],
            'Last 180 Days': [moment().subtract(179, 'days'), moment()]
        },
        alwaysShowCalendars: false, // Show calendars for custom range
        opens: 'right', // Position the calendar
        startDate: moment().subtract(29, 'days'), // Default start date
        endDate: moment() // Default end date
    }, cb);

    $('#date-range-select-div').on('apply.daterangepicker', function(ev, picker) {
        cb(picker.startDate, picker.endDate);
    });

    $('#date-range-select-div').on('cancel.daterangepicker', function(ev, picker) {
        $('#date-range-select-div span').html('Select Date'); // reset placeholder
    });

    $('#date-range-select-div span').html('Select Date'); // initial placeholder
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
    const newDateStr = `${day}/${month}/${year}`;

    return newDateStr;
}

// close button contorl
function resteUrl(){
    searchedItem.value = '';
    reset1.style.display = 'none';
    itemExpiryReportSearch();
}

function itemExpiryReportSearch(){
    let searchItem = '';
    let startDate = '';
    let endDate = '';
    
    if($(selectedDateSpan).text() == 'Select Date'){
        alert('Select Date');
        return;
    }else{
        let separatedDates = separateDates($(selectedDateSpan).text());
        startDate = convertDateFormat(separatedDates.startDate);
        endDate = convertDateFormat(separatedDates.endDate);
        selectedStartDate.innerHTML = startDate;
        selectedEndDate.innerHTML = endDate;   
    }
    
    if(searchedItem.value == ''){
        searchItem = '';
    }else{
        searchItem = searchedItem.value;
        reset1.style.display = 'block';
    }
    
    // console.log(startDate, endDate);
    let dataArray = {
        startDt: startDate,
        endDt: endDate,
        searchOn: searchItem
    };

    itemExpiaryDataSearch(dataArray);
}



// item mergin data search function (ajax call)
function itemExpiaryDataSearch(array){

    let arryString = JSON.stringify(array);
    let salesDataReport = `ajax/itemExpiryReport.ajax.php?dataArray=${arryString}`;
    xmlhttp.open("GET", salesDataReport, false);
    xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xmlhttp.send(null);
    let report = xmlhttp.responseText;

    report = JSON.parse(report);
    console.log(report);
    if(report.status == '1'){
        itemExpiryReportShow(report.data);
    }else{
        // grandTotalShow.classList.add('d-none');
        itemExpiaryReportTable.innerHTML = '';
        alert('no data found');
    }
}





function itemExpiryReportShow(reportData) {
    console.log(reportData);
    itemExpiaryReportTable.innerHTML = '';
    
    document.getElementById('download-checking').innerHTML = '1';
    // grandTotalShow.classList.remove('d-none');

    let currentDateTime = getCurrentDateTime();
    reportGenerationTime.innerHTML = currentDateTime;

    // Define headers
    const header = ['Distributor', 'GSTIN', 'Total Items', 'Total Quantity', 'Stock by PTR'];

    // Create table headers
    const thead = document.createElement('thead');
    const tr = document.createElement('tr');

    header.forEach((headerText, index) => {
        const th = document.createElement('th');
        th.textContent = headerText;
        th.style.fontWeight = 'bold'; 
    
        if (index >= 2) {
            th.style.textAlign = 'right'; 
        }
    
        tr.appendChild(th);
    });
    thead.appendChild(tr);
    // Append the header row to the table head
    itemExpiaryReportTable.appendChild(thead);

    // Create table body
    const tbody = document.createElement('tbody');

    let distNamesList = [];
    reportData.forEach(data => {
        distNamesList.push(data.dist_name);
    });

    const distNames = [...new Set(distNamesList)].sort();
    console.log(distNames);
    
    let totalQantity = 0;
    let stockByPtr = 0;

    distNames.forEach(distributor => {
        let totalItems = 0;
        let distGstin = '';
        let distributorTotalQuantity = 0;
        let distributorStockByPtr = 0;

        reportData.forEach(data => {
            if (distributor == data.dist_name) {
                totalItems++;
                totalQantity += data.current_qty;
                stockByPtr += data.current_qty * data.item_ptr;

                if(data.dist_gstin != ''){
                    distGstin = data.dist_gstin;
                }else{
                    distGstin = 'No GST data found!';
                }
                
                distributorTotalQuantity += data.current_qty;
                distributorStockByPtr += data.current_qty * data.item_ptr;
            }
        });

        const row = document.createElement('tr');

        const distNameCell = document.createElement('td');
        distNameCell.textContent = distributor;
        row.appendChild(distNameCell);

        const distGstinCell = document.createElement('td');
        distGstinCell.textContent = distGstin;
        row.appendChild(distGstinCell);

        const totalItemCell = document.createElement('td');
        totalItemCell.textContent = totalItems;
        totalItemCell.style.textAlign = 'right';
        row.appendChild(totalItemCell);

        const totalQantityCell = document.createElement('td');
        totalQantityCell.textContent = distributorTotalQuantity;
        totalQantityCell.style.textAlign = 'right';
        row.appendChild(totalQantityCell);

        const stockByPtrCell = document.createElement('td');
        stockByPtrCell.textContent = distributorStockByPtr.toFixed(2);
        stockByPtrCell.style.textAlign = 'right';
        row.appendChild(stockByPtrCell);

        tbody.appendChild(row);
    });

    // Append the table body to the table
    itemExpiaryReportTable.appendChild(tbody);
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
        ["Sales Summary Report : " + selectedStartDate.innerHTML + " To " + selectedEndDate.innerHTML],
        ["Report generated at : " + reportGenerationTime.innerHTML],
    ];

    const headers = Array.from(itemExpiaryReportTable.querySelectorAll('th')).map(th => th.textContent);
    headers.push('Total'); // Add 'Total' to the headers

    const rows = Array.from(itemExpiaryReportTable.querySelectorAll('tbody tr')).map(tr => {
        const cells = Array.from(tr.querySelectorAll('td')).map(td => td.textContent);
        const total = parseFloat(cells[cells.length - 1].replace(/[^0-9.-]+/g, "")) || 0; // Get the last column's value
        cells.push(total.toFixed(2)); // Add total to each row
        return cells;
    });

    // Calculate grand total for the last column
    const numColumns = headers.length;
    const grandTotals = new Array(numColumns).fill('');
    grandTotals[0] = 'Total'; // Set the label for the first cell in the grand total row
    grandTotals[numColumns - 1] = rows.reduce((sum, row) => {
        const value = parseFloat(row[row.length - 1]) || 0;
        return sum + value;
    }, 0).toFixed(2);

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
                fgColor: { argb: 'FF0000' } // Green background color
            };
        }
    });

    // Generate Excel file
    workbook.xlsx.writeBuffer().then(buffer => {
        const blob = new Blob([buffer], { type: 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet' });
        saveAs(blob, 'expired-items-report.xlsx');
    });
}







// export to csv
function exportToCSV() {
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

    const headers = Array.from(itemExpiaryReportTable.querySelectorAll('th')).map(th => th.textContent);
    headers.push('Total'); // Add 'Total' to the headers

    const rows = Array.from(itemExpiaryReportTable.querySelectorAll('tbody tr')).map(tr => {
        const cells = Array.from(tr.querySelectorAll('td')).map(td => td.textContent);
        const total = parseFloat(cells[cells.length - 1].replace(/[^0-9.-]+/g, "")) || 0; // Get the last column's value
        cells.push(total.toFixed(2)); // Add total to each row
        return cells;
    });

    // Calculate grand total for the last column
    const numColumns = headers.length;
    const grandTotals = new Array(numColumns).fill('');
    grandTotals[0] = 'Total'; // Set the label for the first cell in the grand total row
    grandTotals[numColumns - 1] = rows.reduce((sum, row) => {
        const value = parseFloat(row[row.length - 1]) || 0;
        return sum + value;
    }, 0).toFixed(2);

    // Function to escape CSV values
    function escapeCSVValue(value) {
        if (typeof value === 'string' && (value.includes(',') || value.includes('"') || value.includes('\n'))) {
            value = '"' + value.replace(/"/g, '""') + '"';
        }
        return value;
    }

    // Create CSV content
    let csvContent = '';

    // Add header data1 to the CSV content
    headerData1.forEach(rowData => {
        csvContent += rowData.map(escapeCSVValue).join(',') + '\n';
    });

    // Add header data2 to the CSV content
    headerData2.forEach(rowData => {
        csvContent += rowData.map(escapeCSVValue).join(',') + '\n';
    });

    // Add an empty row for spacing
    csvContent += '\n';

    // Add headers row to the CSV content
    csvContent += headers.map(escapeCSVValue).join(',') + '\n';

    // Add rows to the CSV content
    rows.forEach(row => {
        csvContent += row.map(escapeCSVValue).join(',') + '\n';
    });

    // Add the grand totals row to the CSV content
    csvContent += grandTotals.map(escapeCSVValue).join(',') + '\n';

    // Create a blob from the CSV content
    const blob = new Blob([csvContent], { type: 'text/csv;charset=utf-8;' });
    const url = URL.createObjectURL(blob);

    // Create a link to download the CSV file
    const link = document.createElement('a');
    link.setAttribute('href', url);
    link.setAttribute('download', 'expired-items-report.csv');
    link.style.visibility = 'hidden';
    document.body.appendChild(link);
    link.click();
    document.body.removeChild(link);
}

