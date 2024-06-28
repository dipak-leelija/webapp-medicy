<!-- <?php
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


$reportId = $_GET['id'];

// $patientId = $_GET['patient_id'];
$SubTests           = new SubTests;
$LabReport     = new LabReport();
$Patients      = new Patients();
$LabBilling    = new LabBilling();
$LabBillDetails     = new LabBillDetails();


$labReportDetailbyId = $LabReport->labReportDetailbyId($reportId);
// echo $labReportDetailbyId;
$testIds = [];
$labReportDetailbyId = json_decode($labReportDetailbyId);
if (is_array($labReportDetailbyId) && !empty($labReportDetailbyId)) {
    foreach ($labReportDetailbyId as $report) {
        $testIds[] = $report->test_id;
        $testData[] = $report->test_value;
        $reportId = $report->report_id;
    }
}


/// find patient id ///
$findPatienttId = $LabReport->labReportbyReportId($reportId);
// echo $findPatienttId;
$findPatienttId = json_decode($findPatienttId, true);
if ($findPatienttId !== null) {
    $patient_id = $findPatienttId['patient_id'];
    $billId    = $findPatienttId['bill_id'];
    // echo "<div style='width:40%; margin-left:20px;' >  $billId</div>";
} //end..//

/// geting for test_date ///
$labBillingDetails   = json_decode($LabBilling->labBillDisplayById($billId)); /// geting for test_date
$labBillingDetails = $labBillingDetails->data;

$testDate = $labBillingDetails->test_date;


//===find patient details ===//
$patientDatafetch = $LabReport->patientDatafetch($patient_id);
$patientDatafetch = json_decode($patientDatafetch, true);
if ($patientDatafetch !== null) {
    $name       = isset($patientDatafetch['name']) ? $patientDatafetch['name'] : 'N/A';
    $patient_id = isset($patientDatafetch['patient_id']) ? $patientDatafetch['patient_id'] : 'N/A';
    $age        = isset($patientDatafetch['age']) ? $patientDatafetch['age'] : 'N/A';
    $gender     = isset($patientDatafetch['gender']) ? $patientDatafetch['gender'] : 'N/A';
}


?> -->

<!-- <!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="<?php echo CSS_PATH ?>lab-report.css">
    <title>Prescription</title>
</head>

<body>
    <section class="print-page">


        <header>
            <div>
                <img src="./assets/images/top-wave.svg" alt="">
            </div>
            <h1><?= $healthCareName ?></h1>
            <div>
                <span>DIAGNOSTIC & POLYCLINIC</span>
            </div>
            <div>
                <p>Daulatabad, Murshidabad,(W.B.),Pin -742302, Mobile:8695494415/9064390598,Website:www.medicy.in</p>
            </div>
            <hr>
            <div>
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
            <hr>
        </header>

        <div class="content">
            <h5><U><b>REPORT OF LIVER FUNCTION TEST</b></U></h5>

            <table>

                <?php
                $labReportDetailbyId = $LabReport->labReportDetailbyId($reportId);
                $labReportDetailbyId = json_decode($labReportDetailbyId);

                if (is_array($labReportDetailbyId) && !empty($labReportDetailbyId)) {
                    for ($i = 0; $i < count($testIds); $i++) {
                        $patientTest = $SubTests->subTestById($testIds[$i]);
                        $decodedData = json_decode($patientTest, true);
                        if ($decodedData !== null) {
                            $sub_test_name = $decodedData['sub_test_name'];
                ?>
                <tr>
                    <td>
                        <?= $sub_test_name ?>
                        <br>
                        <small>Lorem ipsum dolor sit amet consectetur, adipisicing elit.</small>
                        <br>
                        <small>Lorem ipsum dolor 12h - 76gh </small>
                    </td>
                    <td><?= $testData[$i] ?></td>
                </tr>
                <tr>
                    <td>
                        <?= $sub_test_name ?>
                        <br>
                        <small>Lorem ipsum dolor sit amet consectetur, adipisicing elit.</small>
                        <br>
                        <small>Lorem ipsum dolor 12h - 76gh </small>
                    </td>
                    <td><?= $testData[$i] ?></td>
                </tr>
                <tr>
                    <td>
                        <?= $sub_test_name ?>
                        <br>
                        <small>Lorem ipsum dolor sit amet consectetur, adipisicing elit.</small>
                        <br>
                        <small>Lorem ipsum dolor 12h - 76gh </small>
                    </td>
                    <td><?= $testData[$i] ?></td>
                </tr>
                <tr>
                    <td>
                        <?= $sub_test_name ?>
                        <br>
                        <small>Lorem ipsum dolor sit amet consectetur, adipisicing elit.</small>
                        <br>
                        <small>Lorem ipsum dolor 12h - 76gh </small>
                    </td>
                    <td><?= $testData[$i] ?></td>
                </tr>
                <tr>
                    <td>
                        <?= $sub_test_name ?>
                        <br>
                        <small>Lorem ipsum dolor sit amet consectetur, adipisicing elit.</small>
                        <br>
                        <small>Lorem ipsum dolor 12h - 76gh </small>
                    </td>
                    <td><?= $testData[$i] ?></td>
                </tr>
                <tr>
                    <td>
                        <?= $sub_test_name ?>
                        <br>
                        <small>Lorem ipsum dolor sit amet consectetur, adipisicing elit.</small>
                        <br>
                        <small>Lorem ipsum dolor 12h - 76gh </small>
                    </td>
                    <td><?= $testData[$i] ?></td>
                </tr>
                <tr>
                    <td>
                        <?= $sub_test_name ?>
                        <br>
                        <small>Lorem ipsum dolor sit amet consectetur, adipisicing elit.</small>
                        <br>
                        <small>Lorem ipsum dolor 12h - 76gh </small>
                    </td>
                    <td><?= $testData[$i] ?></td>
                </tr>
                <tr>
                    <td>
                        <?= $sub_test_name ?>
                        <br>
                        <small>Lorem ipsum dolor sit amet consectetur, adipisicing elit.</small>
                        <br>
                        <small>Lorem ipsum dolor 12h - 76gh </small>
                    </td>
                    <td><?= $testData[$i] ?></td>
                </tr>
                <tr>
                    <td>
                        <?= $sub_test_name ?>
                        <br>
                        <small>Lorem ipsum dolor sit amet consectetur, adipisicing elit.</small>
                        <br>
                        <small>Lorem ipsum dolor 12h - 76gh </small>
                    </td>
                    <td><?= $testData[$i] ?></td>
                </tr>
                <tr>
                    <td>
                        <?= $sub_test_name ?>
                        <br>
                        <small>Lorem ipsum dolor sit amet consectetur, adipisicing elit.</small>
                        <br>
                        <small>Lorem ipsum dolor 12h - 76gh </small>
                    </td>
                    <td><?= $testData[$i] ?></td>
                </tr>
                <tr>
                    <td>
                        <?= $sub_test_name ?>
                        <br>
                        <small>Lorem ipsum dolor sit amet consectetur, adipisicing elit.</small>
                        <br>
                        <small>Lorem ipsum dolor 12h - 76gh </small>
                    </td>
                    <td><?= $testData[$i] ?></td>
                </tr>
                <?php
                        }
                    }
                }

                ?>

            </table>
        </div>

        <footer>
        <div>
            <p>Reference values are obtained from the literature provided with reagent kit.</p>
            <hr>
        </div>

        <p><b>***END OF REPORT***</b></p>
            <div>
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
            </div>
        <div>
            <img src="./assets/images/bottom-wave.svg" alt="">
        </div>
    </footer>
    </section> -->
<!-- <div class="footer"> -->

<!-- </div> -->

<!-- <div class="printButton mb-5">
        <button onclick="history.back()">Go Back</button>
        <button onclick="window.print()">Print Prescription</button>
    </div>
</body>

</html> -->


<?php
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

$reportId = $_GET['id'];

$SubTests = new SubTests();
$LabReport = new LabReport();
$Patients = new Patients();
$LabBilling = new LabBilling();
$LabBillDetails = new LabBillDetails();

$labReportDetailbyId = $LabReport->labReportDetailbyId($reportId);
$testIds = [];
$testData = [];
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

$labBillingDetails = json_decode($LabBilling->labBillDisplayById($billId))->data;
$testDate = $labBillingDetails->test_date;

$patientDatafetch = $LabReport->patientDatafetch($patient_id);
$patientDatafetch = json_decode($patientDatafetch, true);
if ($patientDatafetch !== null) {
    $name = isset($patientDatafetch['name']) ? $patientDatafetch['name'] : 'N/A';
    $patient_id = isset($patientDatafetch['patient_id']) ? $patientDatafetch['patient_id'] : 'N/A';
    $age = isset($patientDatafetch['age']) ? $patientDatafetch['age'] : 'N/A';
    $gender = isset($patientDatafetch['gender']) ? $patientDatafetch['gender'] : 'N/A';
}

// Include FPDF library
require('assets/plugins/pdfprint/fpdf/fpdf.php');

class PDF extends FPDF {
    var $isLastPage = false;
    // var $testData =[];

    // function setTestData($data) {
    //     $this->testData = $data;
    // }

    function Gradient($x, $y, $w, $h, $startColor, $endColor, $startPercentage=1, $direction = 'horizontal')
{
    list($r1, $g1, $b1) = $startColor;
    list($r2, $g2, $b2) = $endColor;

    for ($i = 0; $i <= 100; $i++) {
        if ($i / 100 >= (1 - $startPercentage)) {
            $r = $r1 + ($r2 - $r1) * (($i / 100 - (1 - $startPercentage)) / $startPercentage);
            $g = $g1 + ($g2 - $g1) * (($i / 100 - (1 - $startPercentage)) / $startPercentage);
            $b = $b1 + ($b2 - $b1) * (($i / 100 - (1 - $startPercentage)) / $startPercentage);
        } else {
            $r = $r1;
            $g = $g1;
            $b = $b1;
        }
        $this->SetFillColor($r, $g, $b);

        if ($direction == 'horizontal') {
            $this->Rect($x + $i * ($w / 100), $y, $w / 100, $h, 'F');
        } else {
            $this->Rect($x, $y + $i * ($h / 100), $w, $h / 100, 'F');
        }
    }
}

    function Header() {
        global $healthCareName, $name, $patient_id, $age, $gender, $testDate;

        if ($this->PageNo() == 1) {

            $leftSpace = 3; // Left side space in mm
            $rightSpace = -6; // Right side space in mm
            // Calculate positions
            $imageX = $leftSpace; // X position with left space
            $imageY = 3; // Y position
            $imageWidth = 200 - ($leftSpace + $rightSpace); // Adjusted width with spaces
            $imageHeight = 18; // Height of the image
            $this->Image('./assets/images/top-wave.jpg', $imageX, $imageY, $imageWidth, $imageHeight);
            // $this->Image('./assets/images/top-wave.jpg', 2, 0, 220, 20);
            $this->Ln(2);
            $this->SetFont('Arial','B',25);
            $this->SetTextColor(24, 54, 151);
            $this->Cell(0,15, $healthCareName, 0, 1, 'R');
            $this->SetFont('Arial','',12);
             $this->Gradient($this->GetPageWidth() / 2, 24, $this->GetPageWidth() / 2 - 10, 8, [255, 255, 255], [24, 54, 151], 1, 'horizontal');
            $this->SetY(20);
            $this->SetTextColor(255, 255, 255);
            $this->Cell(0,16, 'DIAGNOSTIC & POLYCLINIC', 0, 1, 'R');
            $this->SetFont('Arial','',10);
            $this->SetTextColor(24, 54, 151);
            $this->Cell(0,2, 'Daulatabad, Murshidabad, (W.B.), Pin -742302, Mobile:8695494415/9064390598, Website:www.medicy.in', 0, 1, 'R');
            $this->Ln(5);
            $this->SetFont('Arial','B',10);
            $this->SetTextColor(0, 0, 0);
            $this->Cell(0,5, "Patient's Name: $name", 0, 0,'L');
            $this->Cell(0,5, "Age: $age   Sex: $gender", 0, 1, 'R');
            $this->Cell(0,5, "Patient ID: $patient_id", 0, 0, 'L');
            $this->Cell(0,5, 'Collection Date: ' . $this->formatDateTime($testDate, '/'), 0, 1, 'R');
            $this->Cell(0,5, 'Place of collection: LAB', 0, 0, 'L');
            $this->Cell(0,5, 'Reporting Date: ' . $this->formatDateTime($testDate, '/'), 0, 1, 'R');
            $this->Cell(0,5, 'Ref. by: DR. SELF', 0, 0, 'L');
            $this->Ln(5);
            $this->Line(10, $this->GetY(), 200, $this->GetY());
            $this->Ln(2);
        }
    }

    function Footer() {
        if ($this->isLastPage) {
            $this->SetY(-55);
            $this->SetFont('Arial','',10);
            $this->MultiCell(0,5, 'Reference values are obtained from the literature provided with reagent kit.', 0, 'C');
            $this->Line(10, $this->GetY(), 200, $this->GetY());
            $this->Ln(2);
            $this->SetFont('Arial','B',12);
            $this->Cell(0,10,'***END OF REPORT***', 0, 1, 'C');
            $this->Ln(2);
            $this->SetFont('Arial','I',10);
            $this->Cell(60,5,'A Health Care Unit for :-', 4, 0, 'L');
            $this->Cell(60,5,'Verified by :', 8, 0, 'R');
            $this->SetFont('Arial','B',10);
            $this->Cell(70,5,'DR. S.BISWAS', 0, 1, 'R');
            $this->SetFont('Arial','',10);
            $this->Cell(60,5,'Advance Assay, USG & ECHO, Colour Doppler,', 0, 0, 'L');
            $this->Cell(60,5,'', 0, 0, 'C');
            $this->Cell(70,5,'Consultant Pathologist(MD)', 0, 1, 'R');
            $this->Cell(60,5,'Digital X-Ray, Special X-Ray, OPG, ECG & Eye.', 0, 0, 'L');
            $this->Cell(60,5,'', 0, 0, 'C');
            $this->Cell(70,5,'Reg. No: 59304 (WBMC)', 0, 1, 'R');

            // $this->Image('./assets/images/bottom-wave.jpg', 2, 276, 220, 20);
            // $this->SetY(-11);
            // $this->SetFont('Arial','I',7);
            // $this->SetTextColor(255,255,255);
            // $this->Cell(0,3,'*The result may be correlation clinically', 0, 1, 'R');
            // $this->Cell(0,3,'*Patient identification not verified', 0, 1, 'R');
            // $this->Cell(0,3,'*This report is not valid for medico legal purpose', 0, 1, 'R');

             // Define left and right side spacing
        $leftSpace = 3; // Left side space in mm
        $rightSpace = -6; // Right side space in mm
        
        // Calculate positions
        $imageX = $leftSpace; // X position with left space
        $imageY = 276; // Y position
        $imageWidth = 200 - ($leftSpace + $rightSpace); // Adjusted width with spaces
        $imageHeight = 18; // Height of the image
        
        // Place the image with left and right side spaces
        $this->Image('./assets/images/bottom-wave.jpg', $imageX, $imageY, $imageWidth, $imageHeight);
        
        $textY = $imageY + $imageHeight + -10; // Adjust Y position as needed
        $this->SetY($textY);
        $this->SetFont('Arial', 'I', 7);
        $this->SetTextColor(255, 255, 255);
        
        $cellWidth = 180 - ($leftSpace + $rightSpace);
        $this->Cell($cellWidth, 3, '*The result may be correlation clinically', 0, 1, 'R');
        $this->Cell($cellWidth, 3, '*Patient identification not verified', 0, 1, 'R');
        $this->Cell($cellWidth, 3, '*This report is not valid for medico legal purpose', 0, 1, 'R');
        }
    }

    function AddContentPage() {
        $this->AddPage("","A4");
        $this->SetFont('Arial','B',12);
        $this->Cell(0,10,'REPORT OF LIVER FUNCTION TEST',0,1,'C');
        $this->Line(10, 40, 200, 40);
        $this->Ln(10);


        for ($i = 1; $i <= 20; $i++) {
            $this->SetFont('Arial','B',10);
            $this->Cell(90,5,'suger test',0,0, 'L');
            $this->SetFont('Arial','B',10);
            $this->Cell(90,5,':8.1 g/dl',0,1,'R');
            $this->SetFont('Arial','',8);
            $this->Cell(90,5,'Lorem ipsum dolor sit amet consectetur, adipisicing elit.',0,1);
            $this->Cell(90,5,'Lorem ipsum dolor 12h - 76gh',0,1);
            $this->Ln(5);
        }
        $this->Ln(2);
        // foreach ($this->testData as $test) {
        //     $this->SetFont('Arial', 'B', 10);
        //     $this->Cell(90, 5, $test['name'], 0, 0, 'L');
        //     $this->SetFont('Arial', 'B', 10);
        //     $this->Cell(90, 5, ':' . $test['value'], 0, 1, 'R');
        //     $this->SetFont('Arial', '', 8);
        //     $this->Cell(90, 5, 'Lorem ipsum dolor sit amet consectetur, adipisicing elit.', 0, 1);
        //     $this->Cell(90, 5, 'Lorem ipsum dolor 12h - 76gh', 0, 1);
        //     $this->Ln(5);
        // }
        //  $this->Ln(2);
    }

    
    function AddLastPage() {
        $this->isLastPage = true;
    }

    function formatDateTime($date, $separator) {
        return date("d{$separator}m{$separator}Y", strtotime($date));
    }
}

if (isset($_POST['printPDF'])) {

    // $labReportDetailbyId = $LabReport->labReportDetailbyId($reportId);
    // $labReportDetailbyId = json_decode($labReportDetailbyId, true);

    // $testData = [];
    // if (is_array($labReportDetailbyId) && !empty($labReportDetailbyId)) {
    //     foreach ($labReportDetailbyId as $report) {
    //         $testId = $report['test_id'];
    //         $testValue = $report['test_value'];
    //         $patientTest = $SubTests->subTestById($testId);
    //         $decodedData = json_decode($patientTest, true);
    //         if ($decodedData !== null) {
    //             $testData[] = [
    //                 'name' => $decodedData['sub_test_name'],
    //                 'value' => $testValue
    //             ];
    //         }
    //     }
    // }


    $pdf = new PDF();
    $pdf->AliasNbPages();
    // $pdf->setTestData($testData);
    $pdf->AddContentPage();
    $pdf->AddLastPage();

    ob_clean();
    $pdf->Output();
    exit;
}
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
            <!-- <div class='lab-name'> -->
            <h1 class='lab-name'><?= $healthCareName ?></h1>
            <!-- </div> -->
            <div>
                <span>DIAGNOSTIC & POLYCLINIC</span>
            </div>
            <div>
                <p>Daulatabad, Murshidabad,(W.B.),Pin -742302, Mobile:8695494415/9064390598,Website:www.medicy.in</p>
            </div>
            <hr>
            <div>
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
            <hr>
        </header>

        <div class="content">
            <h5><U><b>REPORT OF LIVER FUNCTION TEST</b></U></h5>

            <table>

                <?php
        $labReportDetailbyId = $LabReport->labReportDetailbyId($reportId);
        $labReportDetailbyId = json_decode($labReportDetailbyId);
        if (is_array($labReportDetailbyId) && !empty($labReportDetailbyId)) {
            for ($i = 0; $i < count($testIds); $i++) {
                $patientTest = $SubTests->subTestById($testIds[$i]);
                $decodedData = json_decode($patientTest, true);
                if ($decodedData !== null) {
                    $sub_test_name = $decodedData['sub_test_name'];
        ?>
                <tr>
                    <td>
                        <?= $sub_test_name ?>
                        <br>
                        <small>Lorem ipsum dolor sit amet consectetur, adipisicing elit.</small>
                        <br>
                        <small>Lorem ipsum dolor 12h - 76gh </small>
                    </td>
                    <td><?= $testData[$i] ?></td>
                </tr>
                <?php
                }
            }
        }

        ?>

            </table>
        </div>

        <footer>
            <div>
                <p>Reference values are obtained from the literature provided with reagent kit.</p>
                <hr>
            </div>

            <p><b>***END OF REPORT***</b></p>
            <div>
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
            </div>
            <div>
                <img src="./assets/images/bottom-wave.svg" alt="">
            </div>
        </footer>
    </section>
    <form method="post">
    <input type="hidden" name="printPDF" value="1">
    <button type="submit">Print PDF</button>
    </form>
</body>

</html>