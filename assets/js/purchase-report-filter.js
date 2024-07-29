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

const selectedStartDate = document.getElementById('selected-start-date');
const selectedEndDate = document.getElementById('selected-end-date');
const reportGenerationTime = document.getElementById('report-generation-date-time-holder');

// total amount show div
const totalPurchaseAmountShow = document.getElementById('total-purchase-amount-show-div');
const grossTotalLebel = document.getElementById('total-purchase-amount');
totalPurchaseAmountShow.classList.add('d-none');

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

function convertDateFormat1(dateStr) {
    const [day, month, year] = dateStr.split('-');
    const newDateStr = `${year}-${month}-${day}`;

    return newDateStr;
}

function convertDateFormat2(dateStr) {
    const [day, month, year] = dateStr.split('-');
    const newDateStr = `${day}/${month}/${year}`;

    return newDateStr;
}

function filterSearch(){
    let selectedMonth = $('#selected-month').text();
    const arr = selectedMonth.split(" ");
    let dateRange =  getMonthRange(arr[0], arr[1]);
    console.log(dateRange);
    let convertedStartDate = convertDateFormat1(dateRange.startDate);
    let convertedEndDate = convertDateFormat1(dateRange.endDate);
    selectedStartDate.innerHTML = convertDateFormat2(dateRange.startDate);
    selectedEndDate.innerHTML = convertDateFormat2(dateRange.endDate);

    if(reportFilterVal.innerHTML == ''){
        alert('select report type');
        return;
    }

    let reportOnFilter = '';
    if(selectedPurchaseType.innerHTML == ''){
        alert('select purchase type type');
        return;
    }else{
        if(selectedPurchaseType.innerHTML == 'WG'){
            reportOnFilter = 1;
        }else if(selectedPurchaseType.innerHTML == 'NG'){
            reportOnFilter = 0;
        }
    }

    let dataArray = {
        searchOn: reportFilterVal.innerHTML,
        startDate: convertedStartDate,
        endDate: convertedEndDate,
        reportOn: reportOnFilter,
    }   
    // console.log(dataArray);
    gstPurchaseReportSearch(dataArray);
}

let fullReportData = [];
// item mergin data search function (ajax call)
function gstPurchaseReportSearch(array){
    let arryString = JSON.stringify(array);
    let salesDataReport = `ajax/gstPurchaseReport.ajax.php?dataArray=${arryString}`;
    xmlhttp.open("GET", salesDataReport, false);
    xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xmlhttp.send(null);
    let report = xmlhttp.responseText;

    report = JSON.parse(report);
    // console.log(report);
    if(report.status == '1'){
        fullReportData = report.data
        purchaseReportShow(report.data);
    }else{
        stockInStockReturnGstReportTable.innerHTML = '';
        alert('no data found');
    }
}


function purchaseReportShow(reportData) {
    // pagination page Constants
    const rowsPerPage = 25;
    let currentPage = 1;

    // Clear the table and other elements
    stockInStockReturnGstReportTable.innerHTML = '';
    document.getElementById('download-checking').innerHTML = '1';

    // Set the current date and time
    let currentDateTime = getCurrentDateTime();
    reportGenerationTime.innerHTML = currentDateTime;

    // Define headers based on filter values
    const header = ['Sl No', 'Bill No', 'Entry Date', 'Bill Date', 'Distributor', 'Taxable', 'CESS', 'SGST ', 'CGST', 'IGST', 'Total Amount'];

    // Function to render table
    function renderTable(data, page) {
        const start = (page - 1) * rowsPerPage;
        const end = start + rowsPerPage;
        const paginatedData = data.slice(start, end);

        // Clear previous table content
        stockInStockReturnGstReportTable.innerHTML = '';

        // Create table headers
        const thead = document.createElement('thead');
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
        let slNo = start; // Adjust slNo based on pagination
        let grossTotal = 0;
        paginatedData.forEach(data => {
            slNo++;
            const row = document.createElement('tr');

            const slNoCell = document.createElement('td');
            slNoCell.textContent = slNo || ''; // Add serial number data
            row.appendChild(slNoCell);       

            const billNoCell = document.createElement('td');
            billNoCell.textContent = data.bill_no || ''; // Add bill number data
            row.appendChild(billNoCell);     

            const entryDateCell = document.createElement('td');
            entryDateCell.textContent = data.added_on || ''; // Add entry date data
            row.appendChild(entryDateCell);  

            const billDateCell = document.createElement('td');
            billDateCell.textContent = data.bill_date || ''; // Add bill date data
            row.appendChild(billDateCell);   

            const distributorCell = document.createElement('td');
            distributorCell.textContent = data.dist_name || ''; // Add distributor name data
            row.appendChild(distributorCell);

            let taxable = (parseFloat(data.total_paid_on_item) - parseFloat(data.total_gst_amount));
            const taxableCell = document.createElement('td');
            taxableCell.textContent = parseFloat(taxable).toFixed(2); // Format to 2 decimal places
            taxableCell.style.textAlign = 'right'; // Right align the text
            row.appendChild(taxableCell);    

            let cess = 0;
            const cessCell = document.createElement('td');
            cessCell.textContent = parseFloat(cess);
            cessCell.style.textAlign = 'right';
            row.appendChild(cessCell);       

            let sgst = (parseFloat(data.total_gst_amount) / 2).toFixed(2);
            let sgstPercent = (parseFloat(data.total_gst_percent) / 2).toFixed(2);
            const sgstCell = document.createElement('td');
            sgstCell.textContent = parseFloat(sgst) + ' (' + sgstPercent + '%)';
            sgstCell.style.textAlign = 'right';
            row.appendChild(sgstCell);       

            let cgst = (parseFloat(data.total_gst_amount) / 2).toFixed(2);
            let cgstPercent = (parseFloat(data.total_gst_percent) / 2).toFixed(2);
            const cgstCell = document.createElement('td');
            cgstCell.textContent = parseFloat(cgst) + ' (' + cgstPercent + '%)';
            cgstCell.style.textAlign = 'right';
            row.appendChild(cgstCell);       

            let igst = 0;
            const igstCell = document.createElement('td');
            igstCell.textContent = parseFloat(igst);
            igstCell.style.textAlign = 'right';
            row.appendChild(igstCell);       

            const totalAmountCell = document.createElement('td');
            totalAmountCell.textContent = data.total_paid_on_item;
            totalAmountCell.style.textAlign = 'right';
            row.appendChild(totalAmountCell);

            tbody.appendChild(row);

            grossTotal = parseFloat(grossTotal) +  parseFloat(data.total_paid_on_item);
        });

        // Append the table body to the table
        stockInStockReturnGstReportTable.appendChild(tbody);
        totalPurchaseAmountShow.classList.remove('d-none');
        grossTotalLebel.innerHTML = grossTotal.toFixed(2);

        // Create pagination controls
        createPaginationControls(data.length, page);
    }

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

            if (totalPages > 3) {
                // Show "1 of n" format after the third page
                const pageNumber = document.createElement('span');
                pageNumber.textContent = `${page} of ${totalPages}`;
                pageNumber.className = `mx-1 ${'fw-bold text-primary'}`;
                paginationWrapper.appendChild(pageNumber);
            } else {
                // Page numbers
                for (let i = 1; i <= totalPages; i++) {
                    const pageNumber = document.createElement('span');
                    pageNumber.textContent = i;
                    pageNumber.className = `mx-1 ${i === page ? 'fw-bold text-primary' : 'text-skyblue'}`;
                    pageNumber.style.cursor = 'pointer';
                    pageNumber.addEventListener('click', () => {
                        currentPage = i;
                        renderTable(reportData, currentPage);
                    });
                    paginationWrapper.appendChild(pageNumber);
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

            paginationControls.appendChild(paginationWrapper);
        }
    }

    // Initial render
    renderTable(reportData, currentPage);
}


/// report download function call
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
        // if(ts.value == 'pdf'){
        //     exportToPDF();
        // }
    }else{
        alert('generate report first!');
        downloadType.selectedIndex = 0;
    }
}


function exportToExcel() {
    const headerData1 = [
        [healthCareName.innerHTML],
    ];
    const headerData2 = [
        [healthCareAddress.innerHTML],
        ["GSTIN : " + healthCareGstin.innerHTML],
        [],
        ["Purchase Report : " + selectedStartDate.innerHTML + " To " + selectedEndDate.innerHTML],
        ["Report generated at : " + reportGenerationTime.innerHTML],
        []
    ];

    const headers = ['Sl No', 'Bill No', 'Entry Date', 'Bill Date', 'Distributor', 'Taxable', 'CESS', 'SGST ', 'CGST', 'IGST', 'Total Amount'];

    // Create a new Excel workbook
    const workbook = new ExcelJS.Workbook();
    const worksheet = workbook.addWorksheet('Report');

    // Add header data1 to the worksheet with merged cells, center alignment, and specified font
    let currentRow = 1; 

    headerData1.forEach(rowData => {
        const mergeToColumn = headers.length;
        worksheet.mergeCells(`A${currentRow}:${String.fromCharCode(65 + mergeToColumn - 1)}${currentRow}`);
        const mergedCell = worksheet.getCell(`A${currentRow}`);
        mergedCell.value = rowData[0];
        mergedCell.alignment = { horizontal: 'center' }; // Center align the content
        mergedCell.font = { size: 14, bold: true }; // Set font size to 14 and bold
        currentRow++;
    });

    // Add header data2 to the worksheet with merged cells and center alignment
    headerData2.forEach(rowData => {
        const mergeToColumn = headers.length;
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

    // Initialize total variables for the 6th and 11th columns
    let totalSixthColumn = 0;
    let totalEleventhColumn = 0;

    // Add rows to the worksheet using fullReportData
    fullReportData.forEach((data, index) => {
        const row = [
            index + 1, // Serial number
            data.bill_no || '',
            data.added_on || '',
            data.bill_date || '',
            data.dist_name || '',
            (parseFloat(data.total_paid_on_item) - parseFloat(data.total_gst_amount)).toFixed(2),
            '0',
            (parseFloat(data.total_gst_amount) / 2).toFixed(2) + ' (' + (parseFloat(data.total_gst_percent) / 2).toFixed(2) + '%)',
            (parseFloat(data.total_gst_amount) / 2).toFixed(2) + ' (' + (parseFloat(data.total_gst_percent) / 2).toFixed(2) + '%)',
            '0',
            data.total_paid_on_item
        ];
        const excelRow = worksheet.addRow(row);
        excelRow.eachCell((cell, colNumber) => {
            if (colNumber > 1) {
                const isNumeric = !isNaN(parseFloat(cell.value)) && isFinite(cell.value);
                if (isNumeric) {
                    cell.alignment = { horizontal: 'left' };

                    if (colNumber === 6) {
                        totalSixthColumn += parseFloat(cell.value);
                    }
                    if (colNumber === 11) {
                        totalEleventhColumn += parseFloat(cell.value);
                    }
                }
            }
        });
    });

    // Add an empty row before the totals
    worksheet.addRow([]);

    // Add grand totals row
    const totalsRow = worksheet.addRow([]);
    totalsRow.getCell(1).value = 'Grand Total';
    totalsRow.getCell(6).value = totalSixthColumn.toFixed(2); // 6th column total
    totalsRow.getCell(11).value = totalEleventhColumn.toFixed(2); // 11th column total

    // Style the grand totals row
    totalsRow.eachCell((cell, colNumber) => {
        cell.font = { bold: true };
        cell.alignment = { horizontal: colNumber > 1 ? 'left' : 'center' };
    });

    // Generate Excel file
    workbook.xlsx.writeBuffer().then(buffer => {
        const blob = new Blob([buffer], { type: 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet' });
        saveAs(blob, 'purchase-report.xlsx');
    });
}




function exportToCSV() {
    // Use the fullReportData stored from the AJAX call
    const data = fullReportData;

    const headerData1 = [
        [healthCareName.innerHTML],
    ];
    const headerData2 = [
        [healthCareAddress.innerHTML],
        ["GSTIN : " + healthCareGstin.innerHTML],
        [],
        ["Purchase Report : " + selectedStartDate.innerHTML + " To " + selectedEndDate.innerHTML],
        ["Report generated at : " + reportGenerationTime.innerHTML],
        []
    ];

    const headers = ['Sl No', 'Bill No', 'Entry Date', 'Bill Date', 'Distributor', 'Taxable', 'CESS', 'SGST ', 'CGST', 'IGST', 'Total Amount'];

    // Initialize total variables for the 6th and 11th columns
    let totalSixthColumn = 0;
    let totalEleventhColumn = 0;

    // Prepare CSV content
    let csvContent = '';

    // Add header data1
    headerData1.forEach(rowData => {
        csvContent += rowData.join(',') + '\n';
    });

    // Add header data2
    headerData2.forEach(rowData => {
        csvContent += rowData.join(',') + '\n';
    });

    // Add an empty row for spacing
    csvContent += '\n';

    // Add headers row
    csvContent += headers.join(',') + '\n';

    // Add data rows and calculate totals
    data.forEach((data, index) => {
        const slNo = index + 1;
        const taxable = (parseFloat(data.total_paid_on_item) - parseFloat(data.total_gst_amount)).toFixed(2);
        const cess = '0';
        const sgst = (parseFloat(data.total_gst_amount) / 2).toFixed(2) + ' (' + (parseFloat(data.total_gst_percent) / 2).toFixed(2) + '%)';
        const cgst = (parseFloat(data.total_gst_amount) / 2).toFixed(2) + ' (' + (parseFloat(data.total_gst_percent) / 2).toFixed(2) + '%)';
        const igst = '0';
        const totalAmount = data.total_paid_on_item;

        const row = [
            slNo,
            data.bill_no || '',
            data.added_on || '',
            data.bill_date || '',
            data.dist_name || '',
            taxable,
            cess,
            sgst,
            cgst,
            igst,
            totalAmount
        ];

        csvContent += row.join(',') + '\n';

        // Calculate totals
        totalSixthColumn += parseFloat(taxable);
        totalEleventhColumn += parseFloat(totalAmount);
    });

    // Add an empty row before the totals
    csvContent += '\n';

    // Add grand totals row
    const grandTotalRow = Array(headers.length).fill('');
    grandTotalRow[0] = 'Grand Total';
    grandTotalRow[5] = totalSixthColumn.toFixed(2); // 6th column total
    grandTotalRow[10] = totalEleventhColumn.toFixed(2); // 11th column total
    csvContent += grandTotalRow.join(',') + '\n';

    // Create a Blob from the CSV content
    const blob = new Blob([csvContent], { type: 'text/csv;charset=utf-8;' });
    const url = URL.createObjectURL(blob);

    // Create a link to download the CSV file
    const link = document.createElement('a');
    link.href = url;
    link.setAttribute('download', 'purchase-report.csv');
    document.body.appendChild(link);
    link.click();
    document.body.removeChild(link);
}

