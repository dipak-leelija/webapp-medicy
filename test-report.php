<?php
// Include TCPDF library
require_once './assets/plugins/TCPDF/tcpdf.php';

// Other required files
require_once __DIR__ . '/config/constant.php';
require_once ROOT_DIR . '_config/sessionCheck.php';

require_once CLASS_DIR . 'dbconnect.php';
require_once ROOT_DIR . '_config/healthcare.inc.php';

require_once CLASS_DIR . 'appoinments.class.php';
require_once CLASS_DIR . 'doctors.class.php';
require_once CLASS_DIR . 'doctor.category.class.php';
require_once CLASS_DIR . 'report-generate.class.php';
require_once CLASS_DIR . 'labBilling.class.php';
require_once CLASS_DIR . 'labBillDetails.class.php';
require_once CLASS_DIR . 'patients.class.php';
require_once CLASS_DIR . 'sub-test.class.php';
require_once CLASS_DIR . 'utility.class.php';

// Fetch data
$reportId = $_GET['id'];
$SubTests = new SubTests;
$LabReport = new LabReport();
$Patients = new Patients();
$LabBilling = new LabBilling();
$LabBillDetails = new LabBillDetails();

$labReportDetailbyId = $LabReport->labReportDetailbyId($reportId);
$testIds = [];
$labReportDetailbyId = json_decode($labReportDetailbyId);
if (is_array($labReportDetailbyId) && !empty($labReportDetailbyId)) {
    foreach ($labReportDetailbyId as $report) {
        $testIds[] = $report->test_id;
        $testData[] = $report->test_value;
        $reportId = $report->report_id;
    }
}

$findPatienttId = $LabReport->labReportbyReportId($reportId);
$findPatienttId = json_decode($findPatienttId, true);
if ($findPatienttId !== null) {
    $patient_id = $findPatienttId['patient_id'];
    $billId = $findPatienttId['bill_id'];
}

$labBillingDetails = json_decode($LabBilling->labBillDisplayById($billId));
$labBillingDetails = $labBillingDetails->data;
$testDate = $labBillingDetails->test_date;

$patientDatafetch = $LabReport->patientDatafetch($patient_id);
$patientDatafetch = json_decode($patientDatafetch, true);
if ($patientDatafetch !== null) {
    $name = isset($patientDatafetch['name']) ? $patientDatafetch['name'] : 'N/A';
    $patient_id = isset($patientDatafetch['patient_id']) ? $patientDatafetch['patient_id'] : 'N/A';
    $age = isset($patientDatafetch['age']) ? $patientDatafetch['age'] : 'N/A';
    $gender = isset($patientDatafetch['gender']) ? $patientDatafetch['gender'] : 'N/A';
}

// Create new PDF document
$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

// Set document information
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('Healthcare');
$pdf->SetTitle('Lab Report');
$pdf->SetSubject('Lab Report');
$pdf->SetKeywords('TCPDF, PDF, lab report, healthcare');

// Add a page
$pdf->AddPage();

// Set content
$html = '<h1>' . $healthCareName . '</h1>
<div>DIAGNOSTIC & POLYCLINIC</div>
<div>Daulatabad, Murshidabad,(W.B.),Pin -742302, Mobile:8695494415/9064390598,Website:www.medicy.in</div>
<hr>
<div>
    <p><b>Patient\'s Name :</b> ' . $name . '</p>
    <p><b>Patient id :</b> ' . $patient_id . '</p>
    <p><b>Place of collection :</b> LAB </p>
    <p><b>Ref. by :</b> DR. SELF </p>
</div>
<div>
    <p><b>Age :</b> ' . $age . ' <b>Sex :</b> ' . $gender . '</p>
    <p><b>Collection Date :</b> ' . formatDateTime($testDate, '/') . '</p>
    <p><b>Reporting Date :</b> ' . formatDateTime($testDate, '/') . '</p>
</div>
<hr>
<h5><U><b>REPORT OF LIVER FUNCTION TEST</b></U></h5>
<table>';

$labReportDetailbyId = $LabReport->labReportDetailbyId($reportId);
$labReportDetailbyId = json_decode($labReportDetailbyId);

if (is_array($labReportDetailbyId) && !empty($labReportDetailbyId)) {
    for ($i = 0; $i < count($testIds); $i++) {
        $patientTest = $SubTests->subTestById($testIds[$i]);
        $decodedData = json_decode($patientTest, true);
        if ($decodedData !== null) {
            $sub_test_name = $decodedData['sub_test_name'];
            $html .= '<tr>
                        <td>' . $sub_test_name . '<br>
                            <small>Lorem ipsum dolor sit amet consectetur, adipisicing elit.</small><br>
                            <small>Lorem ipsum dolor 12h - 76gh </small>
                        </td>
                        <td>' . $testData[$i] . '</td>
                    </tr>';
        }
    }
}

$html .= '</table>
<div>
    <p>Reference values are obtained from the literature provided with reagent kit.</p>
    <hr>
    <p><b>***END OF REPORT***</b></p>
    <div>
        <p><small><i><b>A Health Care Unit for :-</b></i></small></p>
        <p><small><b>Advance Assay, USG & ECHO, Colour Doppler,</b></small></p>
        <p><small><b>Digital X-Ray, Special X-Ray, OPG, ECG & Eye.</b></small></p>
    </div>
    <div><small><i><b>Verified by :</b></i></small></div>
    <div>
        <p><b>DR. S.BISWAS</b></p>
        <p><b>Consultant Pathologist(MD)</b></p>
        <p><b>Reg. No: 59304 (WBMC)</b></p>
    </div>
    <img src="./assets/images/bottom-wave.svg" alt="">
</div>';

// Output the HTML content
$pdf->writeHTML($html, true, false, true, false, '');

// Close and output PDF document
$pdf->Output('lab_report.pdf', 'I');
?>