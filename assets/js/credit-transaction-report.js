const xmlhttp = new XMLHttpRequest();
// filter data holer
const downloadCheckingVal = document.getElementById('download-checking');
const startDate = document.getElementById('selected-start-date');
const endDate = document.getElementById('selected-end-date');
const reportGenerationDateTime = document.getElementById('report-generation-date-time-holder');
const reportOn = document.getElementById('reprt-on');

// labels
const needToCollectLabel = document.getElementById('need-to-pay-label');
const needToPayLabel = document.getElementById('need-to-collect-label');

// divs
const needToPayDiv = document.getElementById('need-to-pay-div');
const needToCollectDiv = document.getElementById('need-to-collect-div');

// uses data holder
const downloadType = document.getElementById('download-file-type');
const healthCareName = document.getElementById('healthcare-name');
const healthCareGstin = document.getElementById('healthcare-gstin');
const healthCareAddress = document.getElementById('healthcare-address');

// data table 
const creditTransactionDetaisTable = document.getElementById('credit-transaction-report-table');

$(function() {
    function cb(start, end) {
        $('#date-range-select-div span').html(start.format('DD-MM-YYYY') + ' to ' + end.format('DD-MM-YYYY'));
    }

    let dateRanges = {
        'Last 30 Days': [moment().subtract(29, 'days'), moment()],
        'Last 45 Days': [moment().subtract(44, 'days'), moment()],
        'Last 60 Days': [moment().subtract(59, 'days'), moment()],
        'Last 90 Days': [moment().subtract(89, 'days'), moment()],
        'Last 120 Days': [moment().subtract(119, 'days'), moment()],
        'Last 180 Days': [moment().subtract(179, 'days'), moment()]
    };

    let startDate = moment().subtract(29, 'days');
    let endDate = moment();

    // Define common settings for the date range picker
    let commonSettings = {
        locale: {
            format: 'DD-MM-YYYY'
        },
        ranges: dateRanges,
        startDate: startDate,
        endDate: endDate,
        maxDate: moment() // Set the maximum date to today
    };

    // Initialize the date range picker
    $('#date-range-select-div').daterangepicker({
        ...commonSettings,
        minDate: $('#dateRangeType').text() === 'EG' ? moment() : undefined
    }, cb);

    // Event handler for applying the date range
    $('#date-range-select-div').on('apply.daterangepicker', function(ev, picker) {
        cb(picker.startDate, picker.endDate);
    });

    // Event handler for canceling the date range selection
    $('#date-range-select-div').on('cancel.daterangepicker', function(ev, picker) {
        $('#date-range-select-div span').html('Select Date Range');
    });

    // Set initial display text to "Select Date Range"
    $('#date-range-select-div span').html('Select Date Range');
});



function filterReportType(t){
    if(t.value == 'P'){
        reportOn.innerHTML = 'P';
    }

    if(t.value == 'S'){
        reportOn.innerHTML = 'S';
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
    const monthDate = new Date(Date.parse(month + " 1, " + year));
    console.log(monthDate);
    const monthNumber = monthDate.getMonth() + 1; // getMonth() returns 0-11, so add 1 to get 1-12
    const monthStr = monthNumber.toString().padStart(2, '0'); // Convert to 2-digit format
    
    const startDate = `01-${monthStr}-${year}`;
    
    // Get the last day of the month
    const endDate = new Date(year, monthNumber, 0);
    const endDateStr = `${endDate.getDate().toString().padStart(2, '0')}-${monthStr}-${year}`;
    
    return {
        startDate: startDate,
        endDate: endDateStr
    };
}



function convertDateFormat1(dateStr) {
    // Trim any extra spaces from the input string
    const trimmedDateStr = dateStr.trim();
    const [day, month, year] = trimmedDateStr.split('-');
    const newDateStr = `${year}-${month.padStart(2, '0')}-${day.padStart(2, '0')}`;

    return newDateStr;
}


function convertDateFormat2(dateStr) {
    const [day, month, year] = dateStr.split('-');
    const newDateStr = `${day}/${month}/${year}`;

    return newDateStr;
}



function searchReport(){
    let selectedDateRange = $('#selected-month').text();
    const arr = selectedDateRange.split("to");   
    // console.log(arr);
    let convertedStartDate = convertDateFormat1(arr[0]);
    let convertedEndDate = convertDateFormat1(arr[1]);
    startDate.innerHTML = convertDateFormat2(arr[0]);
    endDate.innerHTML = convertDateFormat2(arr[1]);

    if(reportOn.innerHTML == ''){
        alert('select report type');
        return;
    }

    let dataArray = {
        startDate: convertedStartDate,
        endDate: convertedEndDate,
        reportOn: reportOn.innerHTML,
    }   
    // console.log(dataArray);
    getCreditTransactionReport(dataArray);
}




// item mergin data search function (ajax call)
let fullReportData = [];
function getCreditTransactionReport(array){
    let arryString = JSON.stringify(array);
    let salesDataReport = `ajax/credit-transaction-report.ajax.php?dataArray=${arryString}`;
    xmlhttp.open("GET", salesDataReport, false);
    xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xmlhttp.send(null);
    let report = xmlhttp.responseText;

    report = JSON.parse(report);
    // console.log(report);
    if(report.status == '1'){
        creditTransactionReportShow(report.data);
        fullReportData = report.data;
    }else{
        creditTransactionDetaisTable.innerHTML = '';
        alert('no data found');
    }
}



function creditTransactionReportShow(reportDataArray){
    
    // pagination page Constants
    const rowsPerPage = 1;
    let currentPage = 1;

    // Clear the table and other elements
    creditTransactionDetaisTable.innerHTML = '';
    document.getElementById('download-checking').innerHTML = '1';

    // Set the current date and time
    let currentDateTime = getCurrentDateTime();
    reportGenerationDateTime.innerHTML = currentDateTime;

    // Define headers based on filter values
    let header = [];
    if (reportOn.innerHTML == 'P') {
        needToPayDiv.classList.remove('d-none');
        needToCollectDiv.classList.add('d-none');
        header = ['Sl No', 'Distributor Name', 'Bill Id', 'Bill Date', 'Due Date', 'Added On', 'Items Count', 'Amount'];
    } else if (reportOn.innerHTML == 'S') {
        needToCollectDiv.classList.remove('d-none');
        needToPayDiv.classList.add('d-none');
        header = ['Sl No', 'Customer Name', 'Contact Number', 'Bill Id', 'Bill Date', 'Added On', 'Items Count', 'Amount'];
    }

    // Function to render table
    function renderTable(data, page) {
        const start = (page - 1) * rowsPerPage;
        const end = start + rowsPerPage;
        const paginatedData = data.slice(start, end);

        // Clear previous table content
        creditTransactionDetaisTable.innerHTML = '';

        // Create table headers
        const thead = document.createElement('thead');
        const tr = document.createElement('tr');
        header.forEach((headerText, index) => {
            const th = document.createElement('th');
            th.textContent = headerText;
            th.style.fontWeight = 'bold'; // Make the header bold
        
            // Right align the last two headers
            if (index >= header.length - 2) {
                th.style.textAlign = 'right';
            }

            tr.appendChild(th);
        });
        thead.appendChild(tr);
        
        // Append the header row to the table head
        creditTransactionDetaisTable.appendChild(thead);

        // Create table body
        const tbody = document.createElement('tbody');
        let slNo = start; // Adjust slNo based on pagination
        let totalAmount = '';

        paginatedData.forEach(data => {
            slNo++;
            const row = document.createElement('tr');

            const slNoCell = document.createElement('td');
            slNoCell.textContent = slNo || ''; // Add serial number data
            row.appendChild(slNoCell);       

            if(reportOn.innerHTML == 'P'){
                const distributorCell = document.createElement('td');
                distributorCell.textContent = data.dist_name || ''; // Add distributor name data
                row.appendChild(distributorCell);
            }else if(reportOn.innerHTML == 'S'){
                const patientCell = document.createElement('td');
                patientCell.textContent = data.patient_name || ''; // Add distributor name data
                row.appendChild(patientCell);
            }

            if(reportOn.innerHTML == 'S'){
                const patientContactCell = document.createElement('td');
                patientContactCell.textContent = data.patient_contact || ''; // Add bill date data
                // patientContactCell.style.textAlign = 'right';
                row.appendChild(patientContactCell);   
            }

            const billNoCell = document.createElement('td');
            billNoCell.textContent = '#'+data.bill_no || ''; // Add bill number data
            row.appendChild(billNoCell);     

            const billDateCell = document.createElement('td');
            billDateCell.textContent = data.bill_date || ''; // Add bill date data
            row.appendChild(billDateCell);   

            if(reportOn.innerHTML == 'P'){
                const dueDateCell = document.createElement('td');
                dueDateCell.textContent = data.due_date || ''; // Add bill date data
                row.appendChild(dueDateCell);   
            }

            const entryDateCell = document.createElement('td');
            entryDateCell.textContent = data.added_on || ''; // Add entry date data
            row.appendChild(entryDateCell);

            const itemsCountCell = document.createElement('td');
            itemsCountCell.textContent = data.total_items;
            itemsCountCell.style.textAlign = 'right';
            row.appendChild(itemsCountCell);

            const amountCell = document.createElement('td');
            amountCell.textContent = data.amount;
            amountCell.style.textAlign = 'right';
            row.appendChild(amountCell);

            totalAmount += data.amount;

            tbody.appendChild(row);
        });

        if(reportOn.innerHTML == 'P'){
            needToCollectLabel.innerHTML = totalAmount;
        }else if(reportOn.innerHTML == 'S'){
            needToPayLabel.innerHTML = totalAmount;
        }

        // Append the table body to the table
        creditTransactionDetaisTable.appendChild(tbody);

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
                renderTable(reportDataArray, currentPage);
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
                        renderTable(reportDataArray, currentPage);
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
                renderTable(reportDataArray, currentPage);
            });
            paginationWrapper.appendChild(nextButton);

            paginationControls.appendChild(paginationWrapper);
        }
    }

    // Initial render
    renderTable(reportDataArray, currentPage);
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
    // Ensure that fullReportData contains the complete data
    if (!fullReportData || fullReportData.length === 0) {
        alert('No data available to export');
        return;
    }

    const headerData1 = [
        [healthCareName.innerHTML],
    ];
    const headerData2 = [
        [healthCareAddress.innerHTML],
        ["GSTIN : " + healthCareGstin.innerHTML],
        [],
        ["Purchase Report : " + startDate.innerHTML + " To " + endDate.innerHTML],
        ["Report generated at : " + reportGenerationDateTime.innerHTML],
        []
    ];

    // Define headers based on filter values
    let header = [];
    if (reportOn.innerHTML == 'P') {
        header = ['Sl No', 'Distributor Name', 'Bill Id', 'Bill Date', 'Due Date', 'Added On', 'Items Count', 'Amount'];
    } else if (reportOn.innerHTML == 'S') {
        header = ['Sl No', 'Customer Name', 'Contact Number', 'Bill Id', 'Bill Date', 'Added On', 'Items Count', 'Amount'];
    }

    // Extract rows from fullReportData
    const rows = fullReportData.map((data, index) => {
        let row = [];
        row.push(index + 1); // Serial number

        if (reportOn.innerHTML == 'P') {
            row.push(data.dist_name || ''); // Distributor Name
        } else if (reportOn.innerHTML == 'S') {
            row.push(data.patient_name || ''); // Customer Name
            row.push(data.patient_contact || ''); // Contact Number
        }

        row.push('#' + (data.bill_no || '')); // Bill Id
        row.push(data.bill_date || ''); // Bill Date

        if (reportOn.innerHTML == 'P') {
            row.push(data.due_date || ''); // Due Date
        }

        row.push(data.added_on || ''); // Added On
        row.push(data.total_items || 0); // Items Count
        row.push(data.amount || 0); // Amount

        return row;
    });

    // Create a new Excel workbook
    const workbook = new ExcelJS.Workbook();
    const worksheet = workbook.addWorksheet('Report');

    // Add header data1 to the worksheet with merged cells, center alignment, and specified font
    let currentRow = 1; // Start at row 1

    headerData1.forEach(rowData => {
        const mergeToColumn = header.length > 0 ? header.length : 1; // Merge across all columns if headers exist
        worksheet.mergeCells(`A${currentRow}:${String.fromCharCode(65 + mergeToColumn - 1)}${currentRow}`);
        const mergedCell = worksheet.getCell(`A${currentRow}`);
        mergedCell.value = rowData[0];
        mergedCell.alignment = { horizontal: 'center' }; // Center align the content
        mergedCell.font = { size: 14, bold: true }; // Set font size to 14 and bold
        currentRow++;
    });

    // Add header data2 to the worksheet with merged cells and center alignment
    headerData2.forEach(rowData => {
        const mergeToColumn = header.length > 0 ? header.length : 1; // Merge across all columns if headers exist
        worksheet.mergeCells(`A${currentRow}:${String.fromCharCode(65 + mergeToColumn - 1)}${currentRow}`);
        const mergedCell = worksheet.getCell(`A${currentRow}`);
        mergedCell.value = rowData[0];
        mergedCell.alignment = { horizontal: 'center' }; // Center align the content
        currentRow++;
    });

    // Add an empty row for spacing
    worksheet.addRow([]);

    // Add headers row to the worksheet and apply bold font
    const headersRow = worksheet.addRow(header);
    headersRow.eachCell((cell, colNumber) => {
        cell.font = { bold: true };
        // Style the header cells with a yellow background
        cell.fill = {
            type: 'pattern',
            pattern: 'solid',
            fgColor: { argb: 'FFFF00' } // Yellow background color
        };
    });

    // Initialize total variable for the last column
    let totalLastColumn = 0;

    // Add rows to the worksheet
    rows.forEach(row => {
        const excelRow = worksheet.addRow(row);
        excelRow.eachCell((cell, colNumber) => {
            // Check if cell value is numeric
            const isNumeric = !isNaN(parseFloat(cell.value)) && isFinite(cell.value);
            if (isNumeric && colNumber === header.length) {
                // Add to total for the last column
                totalLastColumn += parseFloat(cell.value);
            }
        });
    });

    // Add an empty row before the totals
    worksheet.addRow([]);

    // Add grand totals row
    const totalsRow = worksheet.addRow([]);
    totalsRow.getCell(1).value = 'Total';
    totalsRow.getCell(header.length).value = totalLastColumn.toFixed(2); // Total for the last column

    // Style the grand totals row
    totalsRow.eachCell((cell, colNumber) => {
        cell.font = { bold: true };
        cell.alignment = { horizontal: colNumber > 1 ? 'left' : 'center' };
    });

    // Generate Excel file
    workbook.xlsx.writeBuffer().then(buffer => {
        const blob = new Blob([buffer], { type: 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet' });
        saveAs(blob, 'credit-report.xlsx');
    });
}





// function exportToCSV() {
//     // Ensure that fullReportData contains the complete data
//     if (!fullReportData || fullReportData.length === 0) {
//         alert('No data available to export');
//         return;
//     }

//     // Define headers based on filter values
//     let header = [];
//     if (reportOn.innerHTML == 'P') {
//         header = ['Sl No', 'Distributor Name', 'Bill Id', 'Bill Date', 'Due Date', 'Added On', 'Items Count', 'Amount'];
//     } else if (reportOn.innerHTML == 'S') {
//         header = ['Sl No', 'Customer Name', 'Contact Number', 'Bill Id', 'Bill Date', 'Added On', 'Items Count', 'Amount'];
//     }

//     // Extract rows from fullReportData
//     const rows = fullReportData.map((data, index) => {
//         let row = [];
//         row.push(index + 1); // Serial number

//         if (reportOn.innerHTML == 'P') {
//             row.push(data.dist_name || ''); // Distributor Name
//         } else if (reportOn.innerHTML == 'S') {
//             row.push(data.patient_name || ''); // Customer Name
//             row.push(data.patient_contact || ''); // Contact Number
//         }

//         row.push('#' + (data.bill_no || '')); // Bill Id
//         row.push(data.bill_date || ''); // Bill Date

//         if (reportOn.innerHTML == 'P') {
//             row.push(data.due_date || ''); // Due Date
//         }

//         row.push(data.added_on || ''); // Added On
//         row.push(data.total_items || 0); // Items Count
//         row.push(data.amount || 0); // Amount

//         return row;
//     });

//     // Add header data1 and header data2
//     const headerData1 = [healthCareName.innerHTML];
//     const headerData2 = [
//         healthCareAddress.innerHTML,
//         "GSTIN : " + healthCareGstin.innerHTML,
//         "",
//         "Purchase Report : " + startDate.innerHTML + " To " + endDate.innerHTML,
//         "Report generated at : " + reportGenerationDateTime.innerHTML,
//         ""
//     ];

//     // Convert data to CSV format
//     let csvContent = "data:text/csv;charset=utf-8,";

//     // Add header data1
//     csvContent += headerData1.join(",") + "\n";

//     // Add header data2
//     headerData2.forEach(row => {
//         csvContent += row + "\n";
//     });

//     // Add an empty row for spacing
//     csvContent += "\n";

//     // Add headers row
//     csvContent += header.join(",") + "\n";

//     // Add rows
//     rows.forEach(row => {
//         csvContent += row.join(",") + "\n";
//     });

//     // Calculate total for the last column
//     const totalLastColumn = rows.reduce((sum, row) => {
//         const amount = parseFloat(row[header.length - 1]);
//         return sum + (isNaN(amount) ? 0 : amount);
//     }, 0);

//     // Add an empty row before the totals
//     csvContent += "\n";

//     // Add grand totals row
//     csvContent += "Total,,".repeat(header.length - 1) + totalLastColumn.toFixed(2) + "\n";

//     // Encode the CSV content and trigger download
//     const encodedUri = encodeURI(csvContent);
//     const link = document.createElement("a");
//     link.setAttribute("href", encodedUri);
//     link.setAttribute("download", "credit-report.csv");
//     document.body.appendChild(link);
//     link.click();
//     document.body.removeChild(link);
// }






