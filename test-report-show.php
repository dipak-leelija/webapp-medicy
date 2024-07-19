<?php
require_once __DIR__ . '/config/constant.php';
require_once ROOT_DIR . '_config/sessionCheck.php';
require_once CLASS_DIR . 'dbconnect.php';
require_once ROOT_DIR . '_config/healthcare.inc.php';
require_once CLASS_DIR . 'appoinments.class.php';
require_once CLASS_DIR . 'doctors.class.php';
require_once CLASS_DIR . 'doctor.category.class.php';
require_once CLASS_DIR . 'Pathology.class.php';
require_once CLASS_DIR . 'PathologyReport.class.php';
require_once CLASS_DIR . 'labBilling.class.php';
require_once CLASS_DIR . 'labBillDetails.class.php';
require_once CLASS_DIR . 'patients.class.php';
require_once CLASS_DIR . 'sub-test.class.php';
require_once CLASS_DIR . 'utility.class.php';

$reportId = $_GET['id'];

$SubTests        = new SubTests();
$Pathology       = new Pathology;
$PathologyReport = new PathologyReport;
$Patients        = new Patients();
$LabBilling      = new LabBilling();
$LabBillDetails  = new LabBillDetails();

$testReportResponse = json_decode($PathologyReport->testReportById($reportId));

if (is_object($testReportResponse) && !empty($testReportResponse)) {
    $billId         = $testReportResponse->bill_id;
    $reportDetails  = $testReportResponse->details;

    foreach ($reportDetails as $eachParam) {
        $paramDetails = json_decode($Pathology->showTestByParameter($eachParam->param_id));
        if ($paramDetails->status) {
            $testIds[] = $paramDetails->data->test_id;
        }
    }
    $testIds = array_unique($testIds);
    // foreach ($testIds as $eachTestId) {
    //     $testResponse = $Pathology->showTestById($eachTestId);
    //     $testnames[] = $testResponse['name'];
    // }

    // $testnames = array_unique($testnames);

    $billDetails = json_decode($LabBilling->labBillDisplayById($billId));
    if ($billDetails->status) {
        $billDetails = $billDetails->data;

        $testDate       = $billDetails->test_date;
        $patient_id     = $billDetails->patient_id;
        $refered_doctor = $billDetails->refered_doctor;
    }

    $patientDatafetch = $Patients->patientsDisplayByPId($patient_id);
    $patientDatafetch = json_decode($patientDatafetch, true);
    if ($patientDatafetch !== null) {
        $name       = isset($patientDatafetch['name']) ? $patientDatafetch['name'] : 'N/A';
        $patient_id = isset($patientDatafetch['patient_id']) ? $patientDatafetch['patient_id'] : 'N/A';
        $age        = isset($patientDatafetch['age']) ? $patientDatafetch['age'] : 'N/A';
        $gender     = isset($patientDatafetch['gender']) ? $patientDatafetch['gender'] : 'N/A';
    }
}






// Include FPDF library
require('assets/plugins/pdfprint/fpdf/fpdf.php');

// class PDF extends FPDF
// {
//     var $isLastPage = false;
//     var $paramsValues = [];

//     function setTestData($data)
//     {
//         $this->testData = $data;
//     }
//     ///.....for gradient color....///
//     function Gradient($x, $y, $w, $h, $startColor, $endColor, $startPercentage = 1, $direction = 'horizontal')
//     {
//         list($r1, $g1, $b1) = $startColor;
//         list($r2, $g2, $b2) = $endColor;

//         for ($i = 0; $i <= 100; $i++) {
//             if ($i / 100 >= (1 - $startPercentage)) {
//                 $r = $r1 + ($r2 - $r1) * (($i / 100 - (1 - $startPercentage)) / $startPercentage);
//                 $g = $g1 + ($g2 - $g1) * (($i / 100 - (1 - $startPercentage)) / $startPercentage);
//                 $b = $b1 + ($b2 - $b1) * (($i / 100 - (1 - $startPercentage)) / $startPercentage);
//             } else {
//                 $r = $r1;
//                 $g = $g1;
//                 $b = $b1;
//             }
//             $this->SetFillColor($r, $g, $b);

//             if ($direction == 'horizontal') {
//                 $this->Rect($x + $i * ($w / 100), $y, $w / 100, $h, 'F');
//             } else {
//                 $this->Rect($x, $y + $i * ($h / 100), $w, $h / 100, 'F');
//             }
//         }
//     } ///...end gradient color....///

//     //.....Header Star....//
//     function Header()
//     {
//         global $healthCareName, $name, $patient_id, $age, $gender, $testDate;

//         if ($this->PageNo() == 1) {

//             $leftSpace = 3; // Left side space in mm
//             $rightSpace = -6; // Right side space in mm
//             // Calculate positions
//             $imageX = $leftSpace; // X position with left space
//             $imageY = 3; // Y position
//             $imageWidth = 200 - ($leftSpace + $rightSpace); // Adjusted width with spaces
//             $imageHeight = 18; // Height of the image
//             $this->Image('./assets/images/top-wave.jpg', $imageX, $imageY, $imageWidth, $imageHeight);
//             $this->Ln(2);
//             $this->SetFont('Arial', 'B', 25);
//             $this->SetTextColor(24, 54, 151);
//             $this->Cell(186, 14, $healthCareName, 0, 1, 'R');
//             $this->SetFont('Arial', '', 12);
//             $this->Gradient($this->GetPageWidth() / 2, 24, $this->GetPageWidth() / 2 - 10, 8, [255, 255, 255], [24, 54, 151], 1, 'horizontal');
//             $this->SetY(20);
//             $this->SetTextColor(255, 255, 255);
//             $this->Cell(0, 16, 'DIAGNOSTIC & POLYCLINIC', 0, 1, 'R');
//             $this->SetFont('Arial', '', 10);
//             $this->SetTextColor(24, 54, 151);
//             $this->Cell(0, 2, 'Daulatabad, Murshidabad, (W.B.), Pin -742302, Mobile:8695494415/9064390598, Website:www.medicy.in', 0, 1, 'R');
//             $this->Image('./assets/images/report-heart.jpg', 2, 33.2, 26, 0);
//             $this->SetDrawColor(24, 54, 151);
//             $this->Line(28.3, 40, 200, 40);
//             $this->Ln(8);
//             $this->SetFont('Arial', 'B', 10);
//             $this->SetTextColor(0, 0, 0);
//             $this->Cell(0, 5, "Patient's Name: $name", 0, 0, 'L');
//             $this->Cell(0, 5, "Age: $age   Sex: $gender", 0, 1, 'R');
//             $this->Cell(0, 5, "Patient ID: $patient_id", 0, 0, 'L');
//             $this->Cell(0, 5, 'Collection Date: ' . $this->formatDateTime($testDate, '/'), 0, 1, 'R');
//             $this->Cell(0, 5, 'Place of collection: LAB', 0, 0, 'L');
//             $this->Cell(0, 5, 'Reporting Date: ' . $this->formatDateTime($testDate, '/'), 0, 1, 'R');
//             $this->Cell(0, 5, 'Ref. by: DR. SELF', 0, 0, 'L');
//             $this->Ln(5);
//             $this->SetDrawColor(0, 0, 0);
//             $this->Line(10, $this->GetY(), 200, $this->GetY());
//             $this->Ln(2);
//         }
//     } //.....Header end....//

//     //....footer start....//
//     function Footer()
//     {
//         if ($this->isLastPage) {
//             $this->SetY(-55);
//             $this->SetFont('Arial', '', 10);
//             $this->MultiCell(0, 5, 'Reference values are obtained from the literature provided with reagent kit.', 0, 'C');
//             $this->Line(10, $this->GetY(), 200, $this->GetY());
//             $this->Ln(2);
//             $this->SetFont('Arial', 'B', 12);
//             $this->Cell(0, 10, '***END OF REPORT***', 0, 1, 'C');
//             $this->Ln(2);
//             $this->SetFont('Arial', 'I', 10);
//             $this->SetTextColor(24, 54, 151);
//             $this->Cell(60, 5, 'A Health Care Unit for :-', 4, 0, 'L');
//             $this->SetTextColor(0, 0, 0);
//             $this->Cell(60, 5, 'Verified by :', 8, 0, 'R');
//             $this->SetFont('Arial', 'B', 10);
//             $this->Cell(70, 5, 'DR. S.BISWAS', 0, 1, 'R');
//             $this->SetFont('Arial', '', 10);
//             $this->SetTextColor(24, 54, 151);
//             $this->Cell(60, 5, 'Advance Assay, USG & ECHO, Colour Doppler,', 0, 0, 'L');
//             $this->Cell(60, 5, '', 0, 0, 'C');
//             $this->SetTextColor(0, 0, 0);
//             $this->Cell(70, 5, 'Consultant Pathologist(MD)', 0, 1, 'R');
//             $this->SetTextColor(24, 54, 151);
//             $this->Cell(60, 5, 'Digital X-Ray, Special X-Ray, OPG, ECG & Eye.', 0, 0, 'L');
//             $this->Cell(60, 5, '', 0, 0, 'C');
//             $this->SetTextColor(0, 0, 0);
//             $this->Cell(70, 5, 'Reg. No: 59304 (WBMC)', 0, 1, 'R');

//             // Define left and right side spacing
//             $leftSpace = 3; // Left side space in mm
//             $rightSpace = -6; // Right side space in mm
//             $imageX = $leftSpace; // X position with left space
//             $imageY = 276; // Y position
//             $imageWidth = 200 - ($leftSpace + $rightSpace); // Adjusted width with spaces
//             $imageHeight = 18; // Height of the image

//             $this->Image('./assets/images/bottom-wave.jpg', $imageX, $imageY, $imageWidth, $imageHeight);
//             $textY = $imageY + $imageHeight + -10; // Adjust Y position as needed
//             $this->SetY($textY);
//             $this->SetFont('Arial', 'I', 7);
//             $this->SetTextColor(255, 255, 255);
//             $cellWidth = 180 - ($leftSpace + $rightSpace);
//             $this->Cell($cellWidth, 3, '*The result may be correlation clinically', 0, 1, 'R');
//             $this->Cell($cellWidth, 3, '*Patient identification not verified', 0, 1, 'R');
//             $this->Cell($cellWidth, 3, '*This report is not valid for medico legal purpose', 0, 1, 'R');
//         }
//     } //....footer end....//

//     //.....Main test content start....//
//     function AddContentPage()
//     {
//         $this->AddPage("", "A4");
//         $this->SetFont('Arial', 'B', 12);
//         $this->Cell(0, 8, 'REPORT OF LIVER FUNCTION TEST', 0, 1, 'C');
//         $lineWidth = 200 * 0.4;
//         $lineX = (208 - $lineWidth) / 2;
//         $this->Line($lineX, $this->GetY(), $lineX + $lineWidth, $this->GetY());
//         $this->Ln(10);


//         foreach ($this->testData as $test) {
//             $this->SetFont('Arial', 'B', 10);
//             $this->Cell(50, 5, $test['name'], 0, 0, 'C');
//             $this->SetFont('Arial', 'B', 10);
//             $this->Cell(115, 5, ': ' . $test['value'], 0, 1, 'R');
//             $this->SetFont('Arial', '', 8);
//             $this->Cell(62, 5, $test['ageGroup'], 0, 1, 'C');
//             $this->Cell(48, 5, $test['unit'], 0, 1, 'C');
//             // $this->Cell(0, 5, $test['description'], 0, 1);
//             // $this->Cell(90, 5, 'Lorem ipsum dolor 12h - 76gh', 0, 1);
//             $this->Ln(5);
//         }
//         $this->Ln(2);
//     }
//     //.....Main test content end....//

//     //....footer set last page...//
//     function AddLastPage()
//     {
//         $this->isLastPage = true;
//     } //footer end..///

//     ///....for date time format...///
//     function formatDateTime($date, $separator)
//     {
//         return date("d{$separator}m{$separator}Y", strtotime($date));
//     } //...end format...//
// }

// if (isset($_POST['printPDF'])) {

//     ///...for getting dynamic data...///
//     $labReportDetailbyId = $LabReport->labReportDetailbyId($reportId);
//     $labReportDetailbyId = json_decode($labReportDetailbyId, true);

//     $paramsValues = [];
//     if (is_array($labReportDetailbyId) && !empty($labReportDetailbyId)) {
//         foreach ($labReportDetailbyId as $report) {
//             $testId = $report['test_id'];
//             $testValue = $report['test_value'];
//             $patientTest = $SubTests->subTestById($testId);
//             $decodedData = json_decode($patientTest, true);
//             if ($decodedData !== null) {
//                 $paramsValues[] = [
//                     'name' => $decodedData['sub_test_name'],
//                     'value' => $testValue,
//                     'description' => $decodedData['test_dsc'],
//                     'ageGroup' => $decodedData['age_group'],
//                     'unit' => $decodedData['unit'],

//                 ];
//             }
//         }
//     } ///....dynamic data end...///

//     $pdf = new PDF();

//     $pdf->AliasNbPages();
//     $pdf->setTestData($paramsValues);
//     $pdf->AddContentPage();
//     $pdf->AddLastPage();
//     ob_clean();
//     $pdf->Output();
//     exit;
// }
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Prescription</title>
    <link rel="stylesheet" href="<?php echo CSS_PATH ?>lab-report.css">
</head>

<body>
    <section class="print-page">


        <header>
            <div>
                <img src="./assets/images/top-wave.svg" alt="">
            </div>
            <div class='lab-name'>
                <h1 class='lab-name'><?= $healthCareName ?></h1>
            </div>
            <div class="lab-tag">
                <span>DIAGNOSTIC & POLYCLINIC</span>
            </div>
            <div class="lab-address">
                <p>Daulatabad, Murshidabad,(W.B.),Pin -742302, M:8695494415/9064390598, www.medicy.in</p>
            </div>
            <div class="hear-icon">
                <img src="./assets/images/report-heart.jpg" alt="">
            </div>
            <hr class="addHr">
            <div class="patient-info">
                <div>
                    <p><b>Patient's Name :</b> <?= $name; ?></p>
                    <p><b>Patient id :</b> <?= $patient_id ?></p>
                    <p><b>Place of collection :</b> LAB </p>
                    <p><b>Ref. by :</b> DR. SELF </p>
                </div>
                <div>
                    <p><b>Age :</b> <?php echo $age; ?> <b>Sex :</b> <?php echo $gender; ?></p>
                    <p><b>Collection Date :</b> <?= formatDateTime($testDate, '/') ?></p>
                    <p><b>Reporting Date :</b> <?= formatDateTime($testDate, '/') ?></p>
                </div>
            </div>
            <hr class="customHr">
        </header>

        <?php foreach ($testIds as $eachTestId) :
            $testResponse = $Pathology->showTestById($eachTestId);
            $testname = $testResponse['name'];
        ?>

            <div class="report-area">
                <h5><U><b>REPORT OF <?= $testname ?> TEST</b></U></h5>

                <table class="align-top">

                    <?php

                    foreach ($reportDetails as $eachParam) {
                        $param_id     = $eachParam->param_id;
                        $param_value  = $eachParam->param_value;
                        $paramDetails = json_decode($Pathology->showTestByParameter($param_id));
                        if ($paramDetails->status) {
                            $paramname      = $paramDetails->data->name;
                            $matchTestId    = $paramDetails->data->test_id;
                            $unit           = $paramDetails->data->unit;
                            if ($matchTestId === $eachTestId) {
                                $rangeDetails = json_decode($Pathology->showRangeByParameter($param_id));
                                if ($rangeDetails->status){
                                    $rangeDetails = $rangeDetails->data;
                                    $childRange = $rangeDetails->child;
                                    $adultRange = $rangeDetails->adult;
                                }

                    ?>
                                <tr class="align-top" style="vertical-align: top;">
                                    <td>
                                        <div class="subtestHead">
                                            <b><?= $paramname ?></b>
                                            <p><b>For Child</b> <br><?= $childRange ?></p>
                                            <p><b>For Adult</b> <br><?= $adultRange ?></p>

                                        </div>
                                    </td>
                                    <td><b><?= $param_value .' '.$unit?></b></td>
                                </tr>
                    <?php
                            }
                        }
                    }

                    ?>
                </table>
            </div>
        <?php endforeach; ?>

        <div class="footer-info">
            <div class="footerTag">
                <p>Reference values are obtained from the literature provided with reagent kit.</p>
                <hr style="width: 90%;height: 1px;background-color:gray;">
            </div>

            <p class="footerHead"><b>***END OF REPORT***</b></p>
            <div class="footer-content">
                <div class="footer-content-Left">
                    <p><i><b>A Health Care Unit for :-</b></i></p>
                    <p><b>Advance Assay, USG & ECHO, Colour Doppler,</b></p>
                    <p><b>Digital X-Ray, Special X-Ray, OPG, ECG & Eye.</b></p>
                </div>
                <div class="verified-by"><small><i><b>Verified by :</b></i></small></div>
                <div>
                    <p><b>DR. S.BISWAS</b></p>
                    <p><b>Consultant Pathologist(MD)</b></p>
                    <p><b>Reg. No: 59304 (WBMC)</b></p>

                </div>
            </div>
            <div>
                <img src="./assets/images/bottom-wave.svg" alt="">
            </div>
        </div>
    </section>
    <form method="post">
        <div class="printDiv">
            <input type="hidden" name="printPDF" value="1">
            <button class="printButton" type="submit">Print PDF</button>
        </div>
    </form>
</body>

</html>