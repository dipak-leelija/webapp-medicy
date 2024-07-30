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



// purchase analysis data fetch ajax call
let fullReportData = [];
function purchaseAnalysisDataSearch(array){
    let arryString = JSON.stringify(array);
    let salesDataReport = `ajax/purchaseAnalysis.ajax.php?dataArray=${arryString}`;
    xmlhttp.open("GET", salesDataReport, false);
    xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xmlhttp.send(null);
    let report = xmlhttp.responseText; 
    
    report = JSON.parse(report);
    // console.log(report);
    if(report.status == '1'){
        fullReportData = report.data;
        purchaseAnalysisReportShow(report.data);
    }else{
        itemMarginTable.innerHTML = '';
        fullReportData = [];
        alert('no data found');
    }
}





function purchaseAnalysisReportShow(reportData) {
    // Pagination page constants
    const rowsPerPage = 25;  // Assuming you want to show 1 row per page
    let currentPage = 1;

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
    header.forEach((headerText, index) => {
        const th = document.createElement('th');
        th.textContent = headerText;
        th.style.fontWeight = 'bold'; // Make the header bold
        // Right align specific headers
        if (index >= 4 && index <= 10) {
            th.style.textAlign = 'right';
        }
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

        const itemMerginDiff = document.createElement('td');
        const marginDiffValue = parseFloat(data.margin_diff).toFixed(2);
        itemMerginDiff.textContent = marginDiffValue + ' %'; // Format to 2 decimal places
        itemMerginDiff.style.textAlign = 'right'; // Right align the text
        if (marginDiffValue < 0) {
            itemMerginDiff.style.color = 'red'; // Set text color to red for negative values
        }
        row.appendChild(itemMerginDiff);

        const itemMerginDiffAmount = document.createElement('td');
        const marginDiffAmountValue = parseFloat(data.margin_diff_amount).toFixed(2);
        itemMerginDiffAmount.textContent = marginDiffAmountValue; // Format to 2 decimal places
        itemMerginDiffAmount.style.textAlign = 'right'; // Right align the text
        if (marginDiffAmountValue < 0) {
            itemMerginDiffAmount.style.color = 'red'; // Set text color to red for negative values
        }
        row.appendChild(itemMerginDiffAmount);

        const itemAvgMargin = document.createElement('td');
        itemAvgMargin.textContent = parseFloat(data.avg_margin).toFixed(2); // Format to 2 decimal places
        itemAvgMargin.style.textAlign = 'right'; // Right align the text
        row.appendChild(itemAvgMargin);

        tbody.appendChild(row);
    });

    // Append the table body to the table
    itemMarginTable.appendChild(tbody);

    // Create pagination controls
    function createPaginationControls(totalRows, page) {
        const paginationControls = document.getElementById('pagination-controls');
        paginationControls.innerHTML = ''; // Clear previous controls

        const totalPages = Math.ceil(totalRows / rowsPerPage);

        if (totalPages > 1) {
            const paginationWrapper = document.createElement('div');
            paginationWrapper.className = 'd-flex align-items-center justify-content-center';

            // Previous button
            const prevButton = document.createElement('button');
            prevButton.innerHTML = '&#8592;'; // Left arrow
            prevButton.disabled = page === 1;
            prevButton.className = 'btn btn-link text-decoration-none text-skyblue';
            prevButton.addEventListener('click', () => {
                currentPage--;
                renderTable(reportData, currentPage);
            });
            paginationWrapper.appendChild(prevButton);

            // Page numbers
            for (let i = 1; i <= totalPages; i++) {
                const pageNumber = document.createElement('span');
                if (i === 1 || i === 2 || (i >= page - 1 && i <= page + 1) || i === totalPages) {
                    pageNumber.textContent = i;
                    pageNumber.className = `mx-1 ${i === page ? 'fw-bold text-primary' : 'text-skyblue'}`;
                    pageNumber.style.cursor = 'pointer';
                    pageNumber.addEventListener('click', () => {
                        currentPage = i;
                        renderTable(reportData, currentPage);
                    });
                    paginationWrapper.appendChild(pageNumber);
                } else if (i === page - 2 || i === page + 2) {
                    const ellipsis = document.createElement('span');
                    ellipsis.textContent = '...';
                    ellipsis.className = 'mx-1 text-skyblue';
                    paginationWrapper.appendChild(ellipsis);
                }
            }

            // Next button
            const nextButton = document.createElement('button');
            nextButton.innerHTML = '&#8594;'; // Right arrow
            nextButton.disabled = page === totalPages;
            nextButton.className = 'btn btn-link text-decoration-none text-skyblue';
            nextButton.addEventListener('click', () => {
                currentPage++;
                renderTable(reportData, currentPage);
            });
            paginationWrapper.appendChild(nextButton);

            // Show page number of n format
            const pageInfo = document.createElement('div');
            pageInfo.className = 'mx-3 text-skyblue';
            pageInfo.textContent = `Page ${currentPage} of ${totalPages}`;
            paginationWrapper.appendChild(pageInfo);

            paginationControls.appendChild(paginationWrapper);
        }
    }

    // Render table and controls
    function renderTable(data, page) {
        const start = (page - 1) * rowsPerPage;
        const end = start + rowsPerPage;
        const paginatedData = data.slice(start, end);

        // Clear previous table content
        itemMarginTable.innerHTML = '';

        // Create table headers
        const thead = document.createElement('thead');

        // Add main column headers row
        const tr = document.createElement('tr');
        header.forEach((headerText, index) => {
            const th = document.createElement('th');
            th.textContent = headerText;
            th.style.fontWeight = 'bold'; // Make the header bold
            // Right align specific headers
            if (index >= 4 && index <= 10) {
                th.style.textAlign = 'right';
            }
            tr.appendChild(th);
        });
        thead.appendChild(tr);

        // Append the header row to the table head
        itemMarginTable.appendChild(thead);

        // Create table body
        const tbody = document.createElement('tbody');

        // Populate the table with report data
        paginatedData.forEach(data => {
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

            const itemMerginDiff = document.createElement('td');
            const marginDiffValue = parseFloat(data.margin_diff).toFixed(2);
            itemMerginDiff.textContent = marginDiffValue + ' %'; // Format to 2 decimal places
            itemMerginDiff.style.textAlign = 'right'; // Right align the text
            if (marginDiffValue < 0) {
                itemMerginDiff.style.color = 'red'; // Set text color to red for negative values
            }
            row.appendChild(itemMerginDiff);

            const itemMerginDiffAmount = document.createElement('td');
            const marginDiffAmountValue = parseFloat(data.margin_diff_amount).toFixed(2);
            itemMerginDiffAmount.textContent = marginDiffAmountValue; // Format to 2 decimal places
            itemMerginDiffAmount.style.textAlign = 'right'; // Right align the text
            if (marginDiffAmountValue < 0) {
                itemMerginDiffAmount.style.color = 'red'; // Set text color to red for negative values
            }
            row.appendChild(itemMerginDiffAmount);

            const itemAvgMargin = document.createElement('td');
            itemAvgMargin.textContent = parseFloat(data.avg_margin).toFixed(2); // Format to 2 decimal places
            itemAvgMargin.style.textAlign = 'right'; // Right align the text
            row.appendChild(itemAvgMargin);

            tbody.appendChild(row);
        });

        // Append the table body to the table
        itemMarginTable.appendChild(tbody);

        // Create and update pagination controls
        createPaginationControls(reportData.length, currentPage);
    }

    // Initial render
    renderTable(reportData, currentPage);
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

    const headers = ['Bill Date', 'Bill No', 'Distributo Name', 'Item Name', 'Qty', 'MRP', 'PTR', 'Margin %', 'Margin Difference %', 'Margin Difference ₹', 'Avg. Margin%'];
    
    // Use fullReportData for rows
    const rows = fullReportData.map(data => [
        data.bill_date || '',
        data.bill_no || '',
        data.dist_name || '',
        data.item_name || '',
        data.qty || '',
        parseFloat(data.mrp).toFixed(2),
        parseFloat(data.ptr).toFixed(2),
        parseFloat(data.margin).toFixed(2),
        parseFloat(data.margin_diff).toFixed(2) + ' %',
        parseFloat(data.margin_diff_amount).toFixed(2),
        parseFloat(data.avg_margin).toFixed(2)
    ]);

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
        [healthCareName.innerHTML],
    ];
    const headerData2 = [
        [healthCareAddress.innerHTML],
        ["GSTIN : " + healthCareGstin.innerHTML],
        [],
        ["Sales Summary Report : " + selectedStartDate.innerHTML],
        ["Report generated at : " + reportGenerationTime.innerHTML],
    ];

    const headers = ['Bill Date', 'Bill No', 'Distributo Name', 'Item Name', 'Qty', 'MRP', 'PTR', 'Margin %', 'Margin Difference %', 'Margin Difference ₹', 'Avg. Margin%'];
    
    // Use fullReportData for rows
    const rows = fullReportData.map(data => [
        data.bill_date || '',
        data.bill_no || '',
        data.dist_name || '',
        data.item_name || '',
        data.qty || '',
        parseFloat(data.mrp).toFixed(2),
        parseFloat(data.ptr).toFixed(2),
        parseFloat(data.margin).toFixed(2),
        parseFloat(data.margin_diff).toFixed(2) + ' %',
        parseFloat(data.margin_diff_amount).toFixed(2),
        parseFloat(data.avg_margin).toFixed(2)
    ]);

    // Convert data to CSV format
    function convertToCSV(data) {
        return data.map(row => row.map(cell => `"${cell}"`).join(',')).join('\n');
    }

    // Prepare CSV content
    let csvContent = '';

    // Add headerData1 to CSV
    headerData1.forEach(rowData => {
        csvContent += rowData[0] + '\n';
    });

    // Add headerData2 to CSV
    headerData2.forEach(rowData => {
        csvContent += rowData[0] + '\n';
    });

    // Add empty row for spacing
    csvContent += '\n';

    // Add headers row to CSV
    csvContent += headers.join(',') + '\n';

    // Add rows to CSV
    csvContent += convertToCSV(rows);

    // Create a Blob and trigger download
    const blob = new Blob([csvContent], { type: 'text/csv;charset=utf-8;' });
    const link = document.createElement('a');
    if (link.download !== undefined) { // feature detection
        const url = URL.createObjectURL(blob);
        link.setAttribute('href', url);
        link.setAttribute('download', 'purchase-analysis-report.csv');
        link.style.visibility = 'hidden';
        document.body.appendChild(link);
        link.click();
        document.body.removeChild(link);
    }
}
