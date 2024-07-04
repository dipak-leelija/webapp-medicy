const xmlhttp = new XMLHttpRequest();

const selectedDateSpan = document.getElementById('selected-date');
const selectedDateRange = document.getElementById('selected-date-range');
const selectedReportOn = document.getElementById('report-on-filter');
const searchedItem = document.getElementById('search-by-item');
const itemSearchVal = document.getElementById('item-search-val');

// data holding labels
const downloadType = document.getElementById('download-file-type');
const healthCareName = document.getElementById('healthcare-name');
const healthCareGstin = document.getElementById('healthcare-gstin');
const healthCareAddress = document.getElementById('healthcare-address');
const reportGenerationTime = document.getElementById('report-generation-date-time-holder');
const selectedStartDate = document.getElementById('selected-start-date');
const selectedEndDate = document.getElementById('selected-end-date');

// output labels
const totalSalesAmountLabel = document.getElementById('total-sales-amount');
const totalPurchaseAmountLable = document.getElementById('total-purchase-amount');
const netGstAmountLable = document.getElementById('net-gst-amount');
const totalProfitAmountLable = document.getElementById('total-profit-amount');

// buttons
const reset1 = document.getElementById('search-reset-1');

// divs
const grandTotalShow = document.getElementById('grand-total-div');

// dynamic table 
const itemMarginTable = document.getElementById('item-wise-margin-table');

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
            'Today': [moment(), moment()],
            'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
            'Last 7 Days': [moment().subtract(6, 'days'), moment()],
            'Last 30 Days': [moment().subtract(29, 'days'), moment()],
            'This Month': [moment().startOf('month'), moment().endOf('month')],
            'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
        }
    }, cb);

    $('#date-range-select-div').on('apply.daterangepicker', function(ev, picker) {
        cb(picker.startDate, picker.endDate);
    });

    $('#date-range-select-div').on('cancel.daterangepicker', function(ev, picker) {
        $('#date-range-select-div span').html('Select Date'); // reset place holder
    });

    $('#date-range-select-div span').html('Select Date'); // initial place holder
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





// control functions
function reportOnFilter(t){
    if(t.value == 'S'){
        selectedReportOn.innerHTML = 'sales-table';
    }
    if(t.value == 'SR'){
        selectedReportOn.innerHTML = 'sales-return-table';
    }
}

// close button contorl
function resteUrl(){
    searchedItem.value = '';
    reset1.style.display = 'none';
    itemMerginSearch();
}


function itemMerginSearch(){
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

    if(selectedReportOn.innerHTML == ''){
        alert('select report type');
        return;
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
        filterBy: selectedReportOn.innerHTML,
        searchOn: searchItem,
        searchFlag: 1,
    };

    itemMerginDataSearch(dataArray);
}



// item mergin data search function (ajax call)
function itemMerginDataSearch(array){

    let arryString = JSON.stringify(array);
    let salesDataReport = `ajax/itemMerginReport.ajax.php?dataArray=${arryString}`;
    xmlhttp.open("GET", salesDataReport, false);
    xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xmlhttp.send(null);
    let report = xmlhttp.responseText;

    console.log(report);
    report = JSON.parse(report);
    if(report.status == '1'){
        reportShow(report.data);
    }else{
        grandTotalShow.classList.add('d-none');
        itemMarginTable.innerHTML = '';
        alert('no data found');
    }
}


function reportShow(reportData) {
    // Clear the table and other elements
    itemMarginTable.innerHTML = '';
    document.getElementById('download-checking').innerHTML = '1';
    grandTotalShow.classList.remove('d-none');

    // Set the current date and time
    let currentDateTime = getCurrentDateTime();
    reportGenerationTime.innerHTML = currentDateTime;

    // Define headers based on filter values
    const header = ['Added by', 'Bill No', 'Bill Date', 'Patient Name', 'Item Name', 'Category', 'Unit', 'MANUF.', 'Sale', 'Stock', 'MRP', 'Sales Amt.', 'Purchase', 'Net GST', 'Profit (%)'];

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

    let totalSalesAmount = 0;
    let totalPurchaseAmount = 0;
    let totalNetGst = 0;
    let totalProfit = 0;

    // Populate the table with report data
    reportData.forEach(data => {
        const row = document.createElement('tr');

        const addedByCell = document.createElement('td');
        addedByCell.textContent = data.added_by_name || ''; // Add added by data
        row.appendChild(addedByCell);

        const billNoCell = document.createElement('td');
        billNoCell.textContent = data.bill_no || ''; // Add bill number data
        row.appendChild(billNoCell);

        const billDateCell = document.createElement('td');
        billDateCell.textContent = data.bill_date || ''; // Add bill date data
        row.appendChild(billDateCell);

        const patientNameCell = document.createElement('td');
        patientNameCell.textContent = data.patient_name || ''; // Add patient name data
        row.appendChild(patientNameCell);

        const itemNameCell = document.createElement('td');
        itemNameCell.textContent = data.item || ''; // Add item name data
        row.appendChild(itemNameCell);

        const itemCategoryCell = document.createElement('td');
        itemCategoryCell.textContent = data.category || ''; // Add category data
        row.appendChild(itemCategoryCell);

        const itemUnitCell = document.createElement('td');
        itemUnitCell.textContent = data.unit || ''; // Add unit data
        row.appendChild(itemUnitCell);

        const itemManufCell = document.createElement('td');
        itemManufCell.textContent = data.manuf_short_name || ''; // Add manufacturer short name
        row.appendChild(itemManufCell);

        const itemSalesQtyCell = document.createElement('td');
        itemSalesQtyCell.textContent = data.stock_out_qty || ''; // Add stock out quantity data
        row.appendChild(itemSalesQtyCell);

        const itemCurrentQtyCell = document.createElement('td');
        itemCurrentQtyCell.textContent = data.current_qty || ''; // Add current quantity data
        row.appendChild(itemCurrentQtyCell);

        const itemMrpCell = document.createElement('td');
        itemMrpCell.textContent = parseFloat(data.mrp).toFixed(2); // Format to 2 decimal places
        row.appendChild(itemMrpCell);

        const itemSalesAmountCell = document.createElement('td');
        itemSalesAmountCell.textContent = parseFloat(data.sales_amount).toFixed(2); // Format to 2 decimal places
        totalSalesAmount += parseFloat(data.sales_amount);
        row.appendChild(itemSalesAmountCell);

        const itemPurchaseAmountCell = document.createElement('td');
        itemPurchaseAmountCell.textContent = parseFloat(data.p_amount).toFixed(2); // Format to 2 decimal places
        totalPurchaseAmount += parseFloat(data.p_amount);
        row.appendChild(itemPurchaseAmountCell);

        const itemNetGstCell = document.createElement('td');
        itemNetGstCell.textContent = parseFloat(data.gst_amount).toFixed(2); // Format to 2 decimal places
        totalNetGst += parseFloat(data.gst_amount);
        row.appendChild(itemNetGstCell);

        const itemProfitAmountPercentageCell = document.createElement('td');
        let profit = data.profit;
        profit = profit.toFixed(2);
        itemProfitAmountPercentageCell.textContent = profit+'( '+parseFloat(data.p).toFixed(2) + '%)'; // Format to 2 decimal places with % sign
        totalProfit += parseFloat(profit).toFixed(2);
        row.appendChild(itemProfitAmountPercentageCell);

        // Append the row to the table body
        tbody.appendChild(row);
    });

    totalSalesAmountLabel.innerHTML = totalSalesAmount.toFixed(2);
    totalPurchaseAmountLable.innerHTML = totalPurchaseAmount.toFixed(2);
    netGstAmountLable.innerHTML = totalNetGst.toFixed(2);
    let profitAmount = parseFloat(totalSalesAmount) - parseFloat(totalPurchaseAmount);
    totalProfitAmountLable.innerHTML = profitAmount.toFixed(2);

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
        ["Sales Summary Report : " + selectedStartDate.innerHTML + " To " + selectedEndDate.innerHTML],
        ["Report generated at : " + reportGenerationTime.innerHTML],
    ];

    const headers = Array.from(itemMarginTable.querySelectorAll('th')).map(th => th.textContent);
    const rows = Array.from(itemMarginTable.querySelectorAll('tbody tr')).map(tr => {
        return Array.from(tr.querySelectorAll('td')).map(td => td.textContent);
    });

    // Calculate grand totals for each column (excluding the first column which is the date column)
    const numColumns = headers.length;
    const grandTotals = new Array(numColumns).fill('');
    const startColumn = numColumns - 4; // Start from the last four columns

    for (let colIndex = startColumn; colIndex < numColumns; colIndex++) {
        grandTotals[colIndex] = rows.reduce((sum, row) => {
            // Extract numeric value outside parentheses
            const match = row[colIndex].match(/([0-9.-]+)(?:\s*\([^)]*\))?/);
            const value = match ? match[1].replace(/[^0-9.-]+/g, "") : "0";
            return sum + (parseFloat(value) || 0);
        }, 0).toFixed(2); // Sum and format as needed
    }

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

    const headers = Array.from(itemMarginTable.querySelectorAll('th')).map(th => th.textContent);
    const rows = Array.from(itemMarginTable.querySelectorAll('tbody tr')).map(tr => {
        return Array.from(tr.querySelectorAll('td')).map(td => td.textContent);
    });

    // Calculate grand totals for each column (excluding the first column which is the date column)
    const numColumns = headers.length;
    const grandTotals = new Array(numColumns).fill('');
    const startColumn = numColumns - 4; // Start from the last four columns

    for (let colIndex = startColumn; colIndex < numColumns; colIndex++) {
        grandTotals[colIndex] = rows.reduce((sum, row) => {
            // Extract numeric value outside parentheses
            const match = row[colIndex].match(/([0-9.-]+)(?:\s*\([^)]*\))?/);
            const value = match ? match[1].replace(/[^0-9.-]+/g, "") : "0";
            return sum + (parseFloat(value) || 0);
        }, 0).toFixed(2); // Sum and format as needed
    }

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
    link.setAttribute('download', 'report.csv');
    link.style.visibility = 'hidden';
    document.body.appendChild(link);
    link.click();
    document.body.removeChild(link);
}
