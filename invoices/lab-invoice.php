<?php

require_once dirname(__DIR__) . '/config/constant.php';
require_once ROOT_DIR . '_config/sessionCheck.php';

require_once CLASS_DIR . 'dbconnect.php';
require_once ROOT_DIR . '_config/healthcare.inc.php';

require_once CLASS_DIR . 'Pathology.class.php';
require_once CLASS_DIR . 'doctors.class.php';
require_once CLASS_DIR . 'labBilling.class.php';
require_once CLASS_DIR . 'labBillDetails.class.php';
require_once CLASS_DIR . 'patients.class.php';
require_once CLASS_DIR . 'utility.class.php';
require_once CLASS_DIR . 'encrypt.inc.php';


//  INSTANTIATING CLASS
$LabBilling         = new LabBilling();
$LabBillDetails     = new LabBillDetails();
$Pathology          = new Pathology();
$Doctors            = new Doctors();
$Patients           = new Patients();
$Utility            = new Utility;

if (isset($_GET['bill_id']) || isset($_GET['billId'])):

    if (isset($_GET['bill_id'])) {
        $billId = url_dec($_GET['bill_id']);
    }

    if (isset($_GET['billId'])) {
        $billId = url_dec($_GET['billId']);
    }

    $labBil      = json_decode($LabBilling->labBillDisplayById($billId));

    $billId         = $labBil->data->bill_id;
    $billDate       = $labBil->data->bill_date;
    $patientId      = $labBil->data->patient_id;
    $docId          = $labBil->data->refered_doctor;
    $testDate       = $labBil->data->test_date;
    $totalAmount    = $labBil->data->total_amount;
    $dicountAmount  = $labBil->data->discount;
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
        if ($showDoctor->status == 1) {
                $doctorName = $showDoctor->data->doctor_name;
                $doctorReg = $showDoctor->data->doctor_reg_no;
        }
    } else {
        $doctorName = $docId;
        $doctorReg  = NULL;
    }

    /*
        $labBillDetailsData = json_decode($LabBillDetails->billDetailsById($billId));

        if ($labBillDetailsData->status) {
            $labBillDetailsData = $labBillDetailsData->data;

            // $discArray = [];
            // $amountArray = [];
            // $amountAfterDisc = [];

            // foreach ($labBillDetailsData as $detailsData) {
            //     array_push($discArray, $detailsData->percentage_of_discount_on_test);
            //     array_push($amountArray, $detailsData->test_price);
            //     array_push($amountAfterDisc, $detailsData->price_after_discount);
            // }
        }
    */

endif;

// Include FPDF library
require('../assets/plugins/pdfprint/fpdf/fpdf.php');

class PDF extends FPDF
{

    var $isLastPage = false;

    private $billId;
    // private $billingDate;
    private $billDate;
    private $subTotal;
    private $discountAmount;
    private $dueAmount;
    private $paidAmount;
    private $LabBillDetails;
    private $SubTests;
    private $healthCareLogo;
    private $healthCarePhno, $healthCareApntbkNo, $healthCareEmail;

    // Constructor with parameters
    function __construct($billId, $billDate, $subTotal, $discountAmount, $dueAmount, $paidAmount, $LabBillDetails, $SubTests, $healthCareLogo,$healthCarePhno, $healthCareApntbkNo,  $healthCareEmail)
    {
        parent::__construct();
        $this->billId = $billId;
        // $this->billingDate = $billingDate;
        $this->billDate = $billDate;
        // $this->subTotal = $subTotal;
        $this->discountAmount = $discountAmount;
        $this->dueAmount = $dueAmount;
        $this->paidAmount = $paidAmount;
        $this->LabBillDetails = $LabBillDetails;
        $this->SubTests = $SubTests;
        $this->healthCareLogo = $healthCareLogo;
        $this->healthCarePhno = $healthCarePhno;
        $this->healthCareApntbkNo = $healthCareApntbkNo;
        $this->healthCareEmail = $healthCareEmail;
    }

    function Header()
    {
        global $healthCareLogo, $healthCareName, $healthCareAddress1, $healthCareAddress2, $healthCareCity, $healthCarePin, $healthCarePhno, $healthCareApntbkNo, $billId, $billingDate, $billDate, $patientName, $patientAge, $patientPhno, $testDate, $doctorName, $doctorReg;

        if ($this->PageNo() == 1) {  ///this line only show the header first page

            //.. healthCareLogo...///
            $logoX = 10;
            $logoY = 8;
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
            $this->SetFont('Arial', '', 9);
            $address = "$healthCareAddress1, $healthCareAddress2\n$healthCareCity, $healthCarePin\nM: $healthCarePhno, $healthCareApntbkNo";
            

            $this->SetXY($logoX + $logoWidth + 5, $logoY + 8); // Position below the title
            $this->MultiCell(90, 4.5, $address, 0, 'L');

            ///...Invoice Info
            $this->SetY(12); // Reset Y position
            $this->SetX(-49.9); // Align to the right
            // Draw vertical line
            // $this->SetDrawColor(108, 117, 125);
            $this->Line($this->GetX(), $this->GetY()-2, $this->GetX(), $this->GetY() + 16);

            $this->SetFont('Arial', 'B', 10);
            $this->cell(80, 1, ' Invoice:', 0, 'L');
            $this->SetFont('Arial', '', 9);
            $this->MultiCell(80, 4.2, " \n Bill Id: #$billId\n Bill Date : ". (isset($billingDate) && !empty($billingDate) ? formatDateTime( $billingDate) : formatDateTime( $billDate)), 0, 'L');

            // Patient Info
            $this->Ln(4.8);
            // $this->SetDrawColor(108, 117, 125);
            $this->SetFillColor(236, 236, 236);
            $this->Rect(10, $this->GetY(), 190.1, 9, 'F');
            $this->Line(10, $this->GetY(), 200, $this->GetY());
            // Set font for "Patient Info:"
            $this->SetFont('Arial', 'B', 9);
            $this->Cell(20, 5, 'Patient Info:', 0, 0, 'L');
            $this->SetFont('Arial', '', 9);
            $this->Cell(30, 5, $patientName, 0, 1, 'L');
            $this->SetY($this->GetY()-5);
            $this->SetX(60);
            $this->SetFont('Arial', 'B', 9);
            $this->Cell(8, 5, 'Age : ', 0, 0, 'L');
            $this->SetFont('Arial', '', 9);
            $this->Cell(0, 5, $patientAge, 0, 1, 'L');
            $this->SetY($this->GetY());
            $this->SetFont('Arial', 'B', 9);
            $this->Cell(5, 4, 'M:', 0, 0, 'L');
            $this->SetFont('Arial', '', 9);
            $this->Cell(0, 4, $patientPhno, 0, 1, 'L');
            $this->SetY($this->GetY()-4);
            $this->SetX(35);
            $this->SetFont('Arial', 'B', 9);
            $this->Cell(18, 4, 'Test Date : ', 0, 0, 'L');
            $this->SetFont('Arial', '', 9);
            $this->Cell(0, 4, formatDateTime($testDate), 0, 1, 'L');
            // Doctor Info
            $this->SetY($this->GetY()-9); // Move Y position up to align with patient info
            $this->SetX(-84); // Align to the right
            $this->SetFont('Arial', 'B', 9);
            $this->Cell(38, 5, "Referred Doctor : ", 0, 0, 'R');
            $this->SetFont('Arial', '', 9);
            $this->Cell(30, 5, $doctorName, 0, 1, 'L');
            $this->SetY($this->GetY()); // Move Y position up to align with patient info
            $this->SetX(-80); 
            if($doctorReg != NULL){
            $this->SetFont('Arial', 'B', 9);
            $this->Cell(38, 4, "Reg : ", 0, 0, 'R');
            $this->SetFont('Arial', '', 9);
            $this->Cell(0, 4, $doctorReg, 0, 1, 'L');
            }
            $this->Line(10, $this->GetY(), 200, $this->GetY());
            $this->Ln(8);
        }
    }


    // Page footer
    function Footer()
    {
        if ($this->isLastPage) { /// this line only show the footer last page 

            $pageHeight = $this->GetPageHeight();
            $middleY = ($pageHeight / 2)-30.1;
            $this->SetY($middleY);
            // $this->SetLineWidth(0.4);
            $this->SetDrawColor(0, 0, 0);
            $this->Line(10, $this->GetY(), 200, $this->GetY());
            $this->Ln(1);

            $this->SetFont('Arial', 'B', 9);
            $this->Cell(111, 5, 'Total Amount :', 0, 0, 'R');
            $this->SetFont('Arial', '', 9);
            $this->Cell(80, 5, ' ' . number_format(floatval($this->subTotal), 2), 0, 1, 'R');

            if (isset($_GET['billId'])) {
                $this->SetFont('Arial', 'B', 9);
                $this->Cell(111, 5, 'Less Amount :', 0, 0, 'R');
                $this->SetFont('Arial', '', 9);
                $this->Cell(80, 5, ' ' . number_format(floatval($this->discountAmount), 2), 0, 1, 'R');
            }

            if ($this->dueAmount != NULL && $this->dueAmount > 0) {
                $this->SetFont('Arial', 'B', 9);
                $this->Cell(110, 5, 'Due Amount :', 0, 0, 'R');
                $this->SetFont('Arial', '', 9);
                $this->Cell(81, 5, ' ' . number_format(floatval($this->dueAmount), 2), 0, 1, 'R');
            }

            $this->SetFont('Arial', 'B', 9);
            $this->Cell(111, 5, 'Paid Amount :', 0, 0, 'R');
            // $this->SetFont('Arial', '', 9);
            $this->Cell(80, 5, ' ' . number_format(floatval($this->paidAmount), 2), 0, 1, 'R');

            $this->Ln(1);
            // $this->SetDrawColor(0, 117, 125);
            $this->Line(10, $this->GetY(), 200, $this->GetY());
            $this->Ln(2.5);

            $phoneIcon = '../assets/plugins/pdfprint/icon/phone.png';
            $emailIcon = '../assets/plugins/pdfprint/icon/email.png';
            $this->SetFont('Arial', '', 8);
            $startX = $this->GetX();
            $startY = $this->GetY();
            $this->Image($phoneIcon, $startX, $startY - 2, 4); // Adjust position and size as needed
            if(!empty($this->healthCareEmail)){
            $this->Image($emailIcon, $startX + 38, $startY -2, 3.5);
            }
            $address = " " . $this->healthCarePhno . "," . $this->healthCareApntbkNo . ",          ".$this->healthCareEmail.",  Print Time: " . date('Y-m-d H:i:s');
            $textX = $startX + 3;
            if (empty($this->healthCareEmail)) {
                $address = " " . $this->healthCarePhno . "," . $this->healthCareApntbkNo . ",  Print Time: " . date('Y-m-d H:i:s');
            }
            $this->SetXY($textX, $startY);
            // Output the address text
            $this->SetFont('Arial', 'B', 8);
            $this->MultiCell(0, 0, $address, 0, 'L');
        }
    }

    function AddContentPage()
    {
        $this->AddPage();
        ///....add paid badge...///
        if( $this->paidAmount){
            $imageX = 70; // X position with left space
            $imageY = 55;
            $imageWidth = 80; // Adjusted width with spaces
            $imageHeight = 45; // Height of the image
           $this->Image('../assets/images/paid-seal.png', $imageX, $imageY, $imageWidth, $imageHeight);
       }///....end page badge...///

        $this->SetFont('Arial', 'B', 10);
        $this->Cell(20, -10, 'SL. NO.', 0, 0, 'L');
        $this->Cell(80, -10, 'Description', 0, 0, 'L');
        $this->Cell(30, -10, 'Price', 0, 0, 'L');
        $this->Cell(31, -10, 'Disc (%)', 0, 0, 'L');
        $this->Cell(30, -10, 'Amount', 0, 1, 'R');
        $this->Ln(8);
        // $this->SetDrawColor(108, 117, 125);
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
                $this->Ln(-2);
                $this->SetFont('Arial', 'B', 8);
                $this->Cell(170, 10, 'Total Amount :', 0, 0, 'R');
                $this->SetFont('Arial', '', 9);
                $this->Cell(20, 10, '' .$amount, 0, 1, 'R');

                // Add new page if rowCounter reaches rowsPerPage
                $this->AddPage();
                $this->Ln(10);
                $this->SetFont('Arial', '', 8);

                $rowCounter = 0; // Reset row counter for new page

                 ///....add paid badge...///
               if($this->paidAmount){
                   $imageX = 70; // X position with left space
                   $imageY = 55;
                   $imageWidth = 80; // Adjusted width with spaces
                   $imageHeight = 45; // Height of the image
                  $this->Image('../assets/images/paid-seal.png', $imageX, $imageY, $imageWidth, $imageHeight);
                }///....end page badge...///
            }

            $subTestId = $rowDetails->test_id;
            $testAmount = $rowDetails->price_after_discount;
            $testDisc = $rowDetails->percentage_of_discount_on_test;

            if ($subTestId != '') {
                // print_r($this->SubTests->showTestById($subTestId));
                // exit;
                $showSubTest = $this->SubTests->showTestById($subTestId);
                $testName = $showSubTest['name'];
                $testPrice = $showSubTest['price'];

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
                $this->SetFont('Arial', '', 9);
                $this->Cell(20, 8, $slno, 0, 0, 'L');
                $this->Cell(80, 8, $testName, 0, 0, 'L');
                $this->Cell(30, 8, $testPrice, 0, 0, 'L');
                $this->Cell(31, 8, $testDisc, 0, 0, 'L');
                $this->Cell(30, 8, $testAmount, 0, 1, 'R');
                $amount  = $amount + $testAmount;
                $slno++;
                $this->subTotal += $testAmount;
                $rowCounter++;
            }
        }
    }

    //....footer set last page...//
    function AddLastPage()
    {
        $this->isLastPage = true;
    } //footer end..///

}

// if (isset($_POST['printPDF'])) {

$healthCare   = json_decode($HealthCare->showHealthCare($ADMINID));
if ($healthCare->status === 1) {
    $healthCare = $healthCare->data;
    $healthCareLogo      = $healthCare->logo;
    $healthCareLogo      = empty($healthCareLogo) ? SITE_IMG_PATH . 'logo-p.png' : URL . $healthCareLogo;
    // print($healthCareLogo);
    $logoFilename = basename($healthCareLogo);
    // print($logoFilename);
    // $healthCareLogo = empty($healthCareLogo) ? SITE_IMG_PATH.'logo-p.png' : URL .  rawurlencode($healthCareLogo);
    $healthCareLogo = empty($healthCareLogo) ? SITE_IMG_PATH . 'logo-p.png' : realpath('../assets/images/orgs/' . $logoFilename . '');
}
// exit;

$pdf = new PDF($billId, $billDate, $totalAmount, $dicountAmount, $dueAmount, $paidAmount, $LabBillDetails, $Pathology, $healthCareLogo,$healthCarePhno, $healthCareApntbkNo,  $healthCareEmail);
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
                        <img class="float-end" style="height: 55px; width: 58px; position: absolute;" src="<?= $healthCareLogo ?>" alt="Medicy">
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
                        $showSubTest = $Pathology->showTestById($subTestId);
                        $testName = $showSubTest['name'];
                        $testPrice = $showSubTest['price'];

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