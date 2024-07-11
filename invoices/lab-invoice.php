<?php

require_once dirname(__DIR__) . '/config/constant.php';
require_once ROOT_DIR . '_config/sessionCheck.php';

require_once CLASS_DIR . 'dbconnect.php';
require_once ROOT_DIR . '_config/healthcare.inc.php';

require_once CLASS_DIR . 'sub-test.class.php';
require_once CLASS_DIR . 'doctors.class.php';
require_once CLASS_DIR . 'labBilling.class.php';
require_once CLASS_DIR . 'labBillDetails.class.php';
require_once CLASS_DIR . 'hospital.class.php';
require_once CLASS_DIR . 'patients.class.php';
require_once CLASS_DIR . 'utility.class.php';
require_once CLASS_DIR . 'encrypt.inc.php';
// require_once CLASS_DIR.'hospital.class.php';





//  INSTANTIATING CLASS
$LabBilling      = new LabBilling();
$LabBillDetails  = new LabBillDetails();
$SubTests        = new SubTests();
$Doctors         = new Doctors();
$Patients        = new Patients();
$Utility         = new Utility;
// $HealthCare     = new HealthCare;
// $LabAppointments = new LabAppointments();

if (isset($_GET['bill_id'])) {

    $billId = $_GET['bill_id'];
    $billId = url_dec($billId);

    $labBil      = json_decode($LabBilling->labBillDisplayById($billId));

    $billId         = $labBil->data->bill_id;
    $billingDate    = $labBil->data->bill_date;
    $patientId      = $labBil->data->patient_id;
    $docId          = $labBil->data->refered_doctor;
    $testDate       = $labBil->data->test_date;
    $totalAmount    = $labBil->data->total_amount;
    $totalDiscount  = $labBil->data->discount;
    $afterDiscount  = $labBil->data->total_after_discount;
    $cgst           = $labBil->data->cgst;
    $sgst           = $labBil->data->sgst;
    $paidAmount     = $labBil->data->paid_amount;
    $dueAmount      = $labBil->data->due_amount;
    $status         = $labBil->data->status;
    $addedBy        = $labBil->data->added_by;
    $BillOn         = $labBil->data->added_on;

    $patient = json_decode($Patients->patientsDisplayByPId($patientId));
    $patientName    = isset($patient->name) ? $patient->name : 'N/A';
    $patientPhno    = isset($patient->phno) ? $patient->phno : 'N/A';
    $patientAge     = isset($patient->age)  ? $patient->age  : 'N/A';
    $patientGender  = isset($patient->gender) ? $patient->gender : 'N/A';


    if (is_numeric($docId)) {
        $showDoctor = $Doctors->showDoctorNameById($docId);
        $showDoctor = json_decode($showDoctor);
        // print_r($showDoctor);
        if ($showDoctor->status == 1) {
            foreach ($showDoctor->data as $rowDoctor) {
                $doctorName = $rowDoctor->doctor_name;
                $doctorReg = $rowDoctor->doctor_reg_no;
                // print_r($doctorName);
            }
        }
    } else {
        $doctorName = $docId;
        $doctorReg  = NULL;
    }
} elseif (isset($_GET['billId'])) {
    $billId = $_GET['billId'];
    $billId = url_dec($billId);

    $labBillData = json_decode($LabBilling->labBillDisplayById($billId)); // lab bill data
    if ($labBillData->status) {
        $patientId      = $labBillData->data->patient_id;
        $payable        = $labBillData->data->total_after_discount;
        $dicountAmount  = $labBillData->data->discount;
        $dueAmount      = $labBillData->data->due_amount;
        $paidAmount     = $labBillData->data->paid_amount;
        $billDate       = $labBillData->data->bill_date;
        $testDate       = $labBillData->data->test_date;


        $patientData = json_decode($Patients->chekPatientsDataOnColumn('patient_id', $patientId, $adminId));
        $patientName = $patientData->data->name;
        $patientPhno = $patientData->data->phno;
        $patientAge  = $patientData->data->age;


        if ($labBillData->data->refered_doctor != 'Self') {
            $docColumn = 'doctor_id';
            $docData = json_decode($Doctors->chekDataOnColumn($docColumn, $labBillData->data->refered_doctor, $adminId));

            $doctorName  = $docData->data->doctor_name;
            $doctorReg  = $docData->data->doctor_reg_no;
        } else {
            $doctorName = 'SELF';
            $doctorReg = '';
        }


        $labBillDetailsData = json_decode($LabBillDetails->billDetailsById($billId));

        if ($labBillDetailsData->status) {
            $labBillDetailsData = $labBillDetailsData->data;

            $discArray = [];
            $amountArray = [];
            $amountAfterDisc = [];

            foreach ($labBillDetailsData as $detailsData) {
                array_push($discArray, $detailsData->percentage_of_discount_on_test);
                array_push($amountArray, $detailsData->test_price);
                array_push($amountAfterDisc, $detailsData->price_after_discount);
            }
        }
    }
}

// Include FPDF library
require('../assets/plugins/pdfprint/fpdf/fpdf.php');

class PDF extends FPDF {

    var $isLastPage = false;

    private $billId;
    private $billingDate;
    private $billDate;
    private $subTotal;
    private $discountAmount;
    private $dueAmount;
    private $paidAmount;
    private $LabBillDetails;
    private $SubTests;
    private $healthCareLogo;

    // Constructor with parameters
    function __construct($billId, $billingDate, $billDate, $subTotal, $discountAmount, $dueAmount, $paidAmount, $LabBillDetails, $SubTests, $healthCareLogo) {
        parent::__construct();
        $this->billId = $billId;
        $this->billingDate = $billingDate;
        $this->billDate = $billDate;
        // $this->subTotal = $subTotal;
        $this->discountAmount = $discountAmount;
        $this->dueAmount = $dueAmount;
        $this->paidAmount = $paidAmount;
        $this->LabBillDetails = $LabBillDetails;
        $this->SubTests = $SubTests;
        $this->healthCareLogo = $healthCareLogo;
    }

    function Header() {
        global $healthCareLogo, $healthCareName, $healthCareAddress1, $healthCareAddress2, $healthCareCity, $healthCarePin, $healthCarePhno, $healthCareApntbkNo, $billId, $billingDate, $billDate, $patientName, $patientAge, $patientPhno, $testDate, $doctorName, $doctorReg;

        if ($this->PageNo() == 1) {  ///this line only show the header first page

            //.. healthCareLogo...///
            $logoX = 10;
            $logoY = 14;
            $logoWidth = 20;
            $logoHeight = 20;
            if (!empty($this->healthCareLogo)) {
                $this->Image($this->healthCareLogo, $logoX, $logoY, $logoWidth, $logoHeight);
            }

            ///....Title (Healthcare Name)...///
            $this->SetFont('Arial', 'B', 16);
            $this->SetXY($logoX + $logoWidth + 5, $logoY); // Position next to the logo
            $this->Cell(90, 8, $healthCareName, 0, 1, 'L'); // Centered text

            // Address
            $this->SetFont('Arial', '', 10);
            $address = "$healthCareAddress1, $healthCareAddress2\n$healthCareCity, $healthCarePin\nM: $healthCarePhno, $healthCareApntbkNo";
            $this->SetXY($logoX + $logoWidth + 5, $logoY + 8); // Position below the title
            $this->MultiCell(90, 5, $address, 0, 'L');

            ///...Invoice Info
            $this->SetY(22); // Reset Y position
            $this->SetX(-40); // Align to the right
            // Draw vertical line
            $this->SetDrawColor(108, 117, 125);
            $this->Line($this->GetX(), $this->GetY(), $this->GetX(), $this->GetY() + 17);
            // $this->Ln(0);
            $this->MultiCell(80, 5, " Invoice: \n #$billId\n" . (isset($billingDate) && !empty($billingDate) ? formatDateTime( $billingDate) : formatDateTime( $billDate)), 0, 'L');
            // Patient Info
            $this->Ln(2);
            $this->SetDrawColor(108, 117, 125);
            $this->Line(10, $this->GetY(), 200, $this->GetY());
            $this->SetY($this->GetY() + 2);
            $this->MultiCell(0, 5, "Patient: $patientName, Age: $patientAge\nM: $patientPhno, Test date: " .     formatDateTime($testDate), 0, 'L');
            // Doctor Info
            $this->SetY($this->GetY() - 10); // Move Y position up to align with patient info
            $this->SetX(-80); // Align to the right
            $this->MultiCell(70, 5, "Referred Doctor: $doctorName\n" . ($doctorReg != NULL ? "Reg: $doctorReg"     : "Reg: N/A"), 0, 'R');
            $this->Ln(1);
            $this->SetDrawColor(108, 117, 125);
            $this->Line(10, $this->GetY(), 200, $this->GetY());
            $this->Ln(10);
        }
    }


     // Page footer
    function Footer() {
        if ($this->isLastPage) { /// this line only show the footer last page 

            $pageHeight = $this->GetPageHeight();
            $middleY = $pageHeight / 2;
            $this->SetY($middleY);
            $this->SetLineWidth(0.4);
            $this->SetDrawColor(108, 117, 125);
            $this->Line(10, $this->GetY(), 200, $this->GetY());
            $this->Ln(4);

            $this->SetFont('Arial', 'B', 10);
            $this->Cell(110, 5, 'Total Amount:', 0, 0, 'R');
            $this->SetFont('Arial', '', 10);
            $this->Cell(80, 5, ' ' . number_format(floatval($this->subTotal), 2), 0, 1, 'R');

            if (isset($_GET['billId'])) {
                $this->SetFont('Arial', 'B', 10);
                $this->Cell(110, 5, 'Less Amount:', 0, 0, 'R');
                $this->SetFont('Arial', '', 10);
                $this->Cell(80, 5, ' ' . number_format(floatval($this->discountAmount), 2), 0, 1, 'R');
            }

            if ($this->dueAmount != NULL && $this->dueAmount > 0) {
                $this->SetFont('Arial', 'B', 10);
                $this->Cell(110, 5, 'Due Amount:', 0, 0, 'R');
                $this->SetFont('Arial', '', 10);
                $this->Cell(80, 5, ' ' . number_format(floatval($this->dueAmount), 2), 0, 1, 'R');
            }

            $this->SetFont('Arial', 'B', 10);
            $this->Cell(110, 5, 'Paid Amount:', 0, 0, 'R');
            $this->SetFont('Arial', '', 10);
            $this->Cell(80, 5, ' ' . number_format(floatval($this->paidAmount), 2), 0, 1, 'R');

            $this->Ln(4);
            $this->SetDrawColor(108, 117, 125);
            $this->Line(10, $this->GetY(), 200, $this->GetY());
        }
    }

    function AddContentPage() {
        $this->AddPage();
        ///....add paid badge...///
        if( $this->paidAmount){
            $imageX = 50; // X position with left space
            $imageY = 70;
            $imageWidth = 100; // Adjusted width with spaces
            $imageHeight = 60; // Height of the image
           $this->Image('../assets/images/paid-seal.png', $imageX, $imageY, $imageWidth, $imageHeight);
       }///....end page badge...///

        $this->SetFont('Arial', 'B', 10);
        $this->Cell(20, -10, 'SL. NO.', 0, 0, 'L');
        $this->Cell(80, -10, 'Description', 0, 0, 'L');
        $this->Cell(30, -10, 'Price', 0, 0, 'L');
        $this->Cell(30, -10, 'Disc (%)', 0, 0, 'L');
        $this->Cell(30, -10, 'Amount', 0, 1, 'R');
        $this->Ln(10);
        $this->SetDrawColor(108, 117, 125);
        $this->Line(10, $this->GetY(), 200, $this->GetY()); // Draw line

        ///...Bill Details...///
        $this->SetFont('Arial', '', 9);
        $slno = 1;
        $rowsPerPage = 8; // Maximum rows per page
        $rowCounter = 0;
        $billDetails = json_decode($this->LabBillDetails->billDetailsById($this->billId))->data;

        foreach ($billDetails as $rowDetails) {
            if ($rowCounter >= $rowsPerPage) {

                ///....show first page total amount...////
                $this->SetFont('Arial', 'B', 10);
                $this->Cell(170, 10, 'Total Amount:', 0, 0, 'R');
                $this->SetFont('Arial', '', 10);
                $this->Cell(20, 10, '' .$amount, 0, 1, 'R');

                // Add new page if rowCounter reaches rowsPerPage
                $this->AddPage();
                $this->Ln(10);
                $this->SetFont('Arial', '', 10);

                $rowCounter = 0; // Reset row counter for new page

                 ///....add paid badge...///
               if($this->paidAmount){
                   $imageX = 50; // X position with left space
                   $imageY = 70;
                   $imageWidth = 100; // Adjusted width with spaces
                   $imageHeight = 60; // Height of the image
                  $this->Image('../assets/images/paid-seal.png', $imageX, $imageY, $imageWidth, $imageHeight);
                }///....end page badge...///
            }

            $subTestId = $rowDetails->test_id;
            $testAmount = $rowDetails->price_after_discount;
            $testDisc = $rowDetails->percentage_of_discount_on_test;

            if ($subTestId != '') {
                $showSubTest = json_decode($this->SubTests->subTestById($subTestId));
                $testName = $showSubTest->sub_test_name;
                $testPrice = $showSubTest->price;

                //...start dotted row line...//
                if ($slno > 1) {
                    $this->SetDrawColor(183, 182, 182); // Set color for the dotted line
                    $dotWidth = 0.5; // Width of each dot
                    $spaceWidth = 0.2; // Space between each dot
                    $lineLength = 200; // Length of the line
                    $x = 10; // Starting X position
                    $y = $this->GetY(); // Current Y position
                
                    // Draw the dotted line
                    $drawDot = true; // Initialize to draw dot
                    while ($x <= $lineLength) {
                        if ($drawDot) {
                            $this->Line($x, $y, $x + $dotWidth, $y); // Draw dot
                        }
                        $x += $dotWidth + $spaceWidth; // Move X position to next dot
                        $drawDot = !$drawDot; // Switch drawing state for next dot
                    }
                }//...end dotted row...///
                
                $this->Cell(20, 10, $slno, 0, 0, 'L');
                $this->Cell(80, 10, $testName, 0, 0, 'L');
                $this->Cell(30, 10, $testPrice, 0, 0, 'L');
                $this->Cell(30, 10, $testDisc, 0, 0, 'L');
                $this->Cell(30, 10, $testAmount, 0, 1, 'R');
                $amount  = $amount + $testAmount;
                $slno++;
                $this->subTotal += $testAmount;
                $rowCounter++;
            } 
        }
    }

    //....footer set last page...//
    function AddLastPage() {
        $this->isLastPage = true;
    }//footer end..///

}

// if (isset($_POST['printPDF'])) {

    $healthCare   = json_decode($HealthCare->showHealthCare($ADMINID));
    if ($healthCare->status === 1 ) {
        $healthCare = $healthCare->data;
        $healthCareLogo      = $healthCare->logo;
        $healthCareLogo      = empty($healthCareLogo) ? SITE_IMG_PATH.'logo-p.png' : URL.$healthCareLogo;
        // print($healthCareLogo);
        $logoFilename = basename($healthCareLogo);
        // print($logoFilename);
        // $healthCareLogo = empty($healthCareLogo) ? SITE_IMG_PATH.'logo-p.png' : URL .  rawurlencode($healthCareLogo);
        $healthCareLogo = empty($healthCareLogo) ? SITE_IMG_PATH.'logo-p.png' : realpath('../assets/images/orgs/'.$logoFilename.'');
    }
    // exit;

    $pdf = new PDF($billId, $billingDate, $billDate, $subTotal, $discountAmount, $dueAmount, $paidAmount, $LabBillDetails, $SubTests,$healthCareLogo);
    $pdf->AliasNbPages();
    $pdf->AddContentPage();
    $pdf->AddLastPage();
    ob_clean();
    $pdf->Output();
    exit;
// }
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $healthCareName ?> - #<?= $billId ?></title>
    <link rel="stylesheet" href="<?php echo CSS_PATH ?>/bootstrap/5.3.3/dist/css/bootstrap.css">
    <link rel="stylesheet" href="<?php echo CSS_PATH ?>/custom/receipts.css">
</head>


<body>
    <div class="custom-container">
        <div class="custom-body <?= $payable == $paidAmount ? "paid-bg" : ''; ?>">
            <!-- <div class="custom-body paid-bg"> -->
            <div class="card-body ">
                <div class="row mt-2">
                    <div class="col-sm-1">
                        <img class="float-end" style="height: 55px; width: 58px; position: absolute;"
                            src="<?= $healthCareLogo ?>" alt="Medicy">
                    </div>
                    <div class="col-sm-8 ps-4">
                        <h4 class="text-start mb-1"><?php echo $healthCareName; ?></h4>
                        <p class="text-start" style="margin-top: -5px; margin-bottom: 0px;">
                            <small><?php echo $healthCareAddress1 . ', ' . $healthCareAddress2 ?></small>
                        </p>
                        <p class='' style="margin-top: -5px; margin-bottom: 0px;">
                            <small><?php echo $healthCareCity . ', ' . $healthCarePin; ?></small>
                        </p>
                        <p class="text-start" style="margin-top: -5px; margin-bottom: 2px;">
                            <small><?php echo 'M: ' . $healthCarePhno . ', ' . $healthCareApntbkNo; ?></small>
                        </p>

                    </div>
                    <div class="col-sm-3 invoice-info">
                        <p class="my-0">Invoice</p>
                        <p>#<?php echo $billId; ?></p>
                        <!-- <p><?= formatDateTime($billingDate) ?></p> -->
                        <p>
                            <?php
                            if (isset($billingDate) && !empty($billingDate)) {
                                echo formatDateTime($billingDate);
                            } else {
                                echo formatDateTime($billDate);
                            }
                            ?>
                        </p>
                    </div>
                </div>
            </div>
            <hr class="mb-0 mt-1" style="opacity: .4;">
            <div class="row my-1">
                <div class="col-sm-6 my-0">
                    <p style="margin-top: -3px; margin-bottom: 0px;"><small><b>Patient: </b>
                            <?php echo $patientName . ', <b>Age:</b> ' . $patientAge; ?></small></p>
                    <p style="margin-top: -5px; margin-bottom: 0px;"><small><b>M:</b>
                            <?php echo $patientPhno;
                            echo ', <b>Test date:</b> ' . formatDateTime($testDate); ?></small></p>
                </div>
                <div class="col-sm-6 my-0">
                    <p class="text-end" style="margin-top: -3px; margin-bottom: 0px;"><small><b>Refered Doctor:</b>
                            <?php echo $doctorName; ?></small></p>
                    <p class="text-end" style="margin-top: -5px; margin-bottom: 0px;">
                        <small><?php if ($doctorReg != NULL) {
                                    echo '<b>Reg:</b> ' . $doctorReg;
                                } ?></small>
                    </p>
                </div>

            </div>
            <hr class="my-0" style="opacity: .4;">
            <div class="row py-1">
                <div class="col-sm-2 ps-4">
                    <small><b>SL. NO.</b></small>
                </div>
                <div class="col-sm-4">
                    <small><b>Description</b></small>
                </div>
                <div class="col-sm-2">
                    <small><b>Price (₹)</b></small>
                </div>
                <div class="col-sm-2">
                    <small><b>Disc (%)</b></small>
                </div>
                <div class="col-sm-2 text-end">
                    <small><b>Amount (₹)</b></small>
                </div>
            </div>
            <hr class="my-0" style="opacity:0.4">

            <div class="row">
                <?php
                $slno = 1;
                $subTotal = floatval(00.00);

                $billDetails = json_decode($LabBillDetails->billDetailsById($billId));
                $billDetails = $billDetails->data;

                foreach ($billDetails as $rowDetails) {
                    $subTestId = $rowDetails->test_id;
                    $testAmount = $rowDetails->price_after_discount;
                    $testDisc  = $rowDetails->percentage_of_discount_on_test;

                    if ($subTestId != '') {
                        $showSubTest = json_decode($SubTests->subTestById($subTestId));
                        $testName = $showSubTest->sub_test_name;
                        $testPrice = $showSubTest->price;

                        if ($slno > 1) {
                            echo '<hr style="width: 98%; border-top: 1px dashed #8c8b8b; margin: 4px 10px; align-items: center;">';
                        }

                        echo '
                                <div class="col-sm-2 ps-4 my-0">
                                            <small>' . $slno . '</small>
                                        </div>
                                        <div class="col-sm-4 my-0">
                                            <small>' . $testName . '</small>
                                        </div>
                                        <div class="col-sm-2">
                                            <small>' . $testPrice . '</small>
                                        </div>
                                        <div class="col-sm-2">
                                            <small>' . $testDisc . '</small>
                                        </div>
                                        <div class="col-sm-2 text-end my-0">
                                            <small>' . $testAmount . '</small>
                                        </div>';
                        $slno++;
                        $subTotal = floatval($subTotal + $testAmount);
                    }
                }
                ?>

            </div>

            <div class="footer">
                <hr calss="my-0" style="opacity: 0.3;">
                <div class="row">
                    <!-- table total calculation -->
                    <div class="col-sm-8 mb-1 text-end">
                        <p style="margin-top: -5px; margin-bottom: 0px;"><small><b>Total Amount:</b></small></p>
                    </div>
                    <div class="col-sm-4 mb-1 text-end">
                        <p style="margin-top: -5px; margin-bottom: 0px;">
                            <small><b>₹ <?php echo floatval($subTotal); ?></small></b>
                        </p>
                    </div>
                    <?= isset($_GET['billId']) ?
                        '<div class="col-sm-8 mb-1 text-end">
                            <p style="margin-top: -5px; margin-bottom: 0px;"><small><b>Less Amount:</b></small></p>
                        </div>
                        <div class="col-sm-4 mb-1 text-end">
                            <p style="margin-top: -5px; margin-bottom: 0px;">
                                <small><b>₹ ' . $dicountAmount . '</b></small>
                            </p>
                        </div>' : '';

                    echo ($dueAmount != NULL && $dueAmount > 0) ?
                        '<div class="col-sm-8 mb-1 text-end">
                            <p style="margin-top: -5px; margin-bottom: 0px;"><small><b>Due Amount:</b></small></p>
                        </div>
                        <div class="col-sm-4 mb-1 text-end">
                            <p style="margin-top: -5px; margin-bottom: 0px;">
                                <small><b>₹ ' . $dueAmount . '</b></small>
                            </p>
                        </div>' : '';
                    ?>
                    <div class="col-sm-8 mb-1 text-end">
                        <p style="margin-top: -5px; margin-bottom: 0px;"><small><b>Paid Amount:</b></small></p>
                    </div>
                    <div class="col-sm-4 mb-1 text-end">
                        <p style="margin-top: -5px; margin-bottom: 0px;">
                            <small><b>₹ <?php echo floatval($paidAmount); ?></small></b>
                        </p>
                    </div>
                    <!--/end table total calculation -->
                </div>
                <hr class="mt-0" style="opacity: 0.3;">
            </div>
        </div>
        <div class="justify-content-center print-sec d-flex my-5">
            <!-- <button class="btn btn-primary shadow mx-2" onclick="history.back()">Go Back</button> href="lab-tests.php"-->
            <a class="btn btn-primary shadow mx-2" href="../test-appointments.php">Go Back</a>
            <!--onclick="history.back()"-->
            <!-- <button class="btn btn-primary shadow mx-2" onclick="window.print()">Print Bill</button> -->
            <form method="post">
                <button class="btn btn-primary shadow mx-2" type="submit" name="printPDF">Print PDF</button>
            </form>
        </div>
    </div>
</body>
<script src="<?php echo JS_PATH ?>/bootstrap-js-5/bootstrap.js"></script>

</html>