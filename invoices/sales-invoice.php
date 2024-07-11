<?php
require_once dirname(__DIR__) . '/config/constant.php';
require_once ROOT_DIR . '_config/sessionCheck.php'; //check admin loggedin or not

require_once CLASS_DIR . 'dbconnect.php';
require_once ROOT_DIR . '_config/user-details.inc.php';
require_once ROOT_DIR . '_config/healthcare.inc.php';
require_once CLASS_DIR . 'hospital.class.php';
require_once CLASS_DIR . 'stockOut.class.php';
require_once CLASS_DIR . 'products.class.php';
require_once CLASS_DIR . 'itemUnit.class.php';
require_once CLASS_DIR . 'packagingUnit.class.php';
require_once CLASS_DIR . 'manufacturer.class.php';
require_once CLASS_DIR . 'patients.class.php';

require_once CLASS_DIR . 'encrypt.inc.php';


$invoiceId = $_GET['id'];

//  INSTANTIATING CLASS
$StockOut        = new StockOut();
$Products        = new Products();
$ItemUnit        = new ItemUnit();
$PackagingUnits  = new PackagingUnits();
$Manufacturer    = new Manufacturer();
$Patients        = new Patients;
$ClinicInfo  = new HealthCare;
// echo $healthCareLogo;

if (isset($_GET['id']) && !empty($_GET['id'])) {

    $invoiceId = url_dec($_GET['id']);
    // echo $invoiceId;
    $stockOut  = $StockOut->stockOutDisplayById($invoiceId);
    // print_r($stockOut);
    foreach ($stockOut as $stockOut) {
        $invoiceId      = $stockOut['invoice_id'];
        $customerId     = $stockOut['customer_id'];
        $reffby         = $stockOut['reff_by'];
        $totalMrp       = $stockOut['mrp'];
        $totalGSt       = $stockOut['gst'];
        $billAmout      = $stockOut['amount'];
        $pMode          = $stockOut['payment_mode'];
        $billdate       = $stockOut['bill_date'];

        $details = $StockOut->stockOutDetailsBY1invoiveID($invoiceId);
        $details = json_decode($details, true);
        // print_r($details);
    }
} else {
    echo 'Invalid Request!';
    die("404");
}

if ($customerId != 'Cash Sales') {
    $patient = json_decode($Patients->patientsDisplayByPId($customerId));

    $patientName = $patient->name;
    $patientPhno = $patient->phno;
    $patientAge  = $patient->age;

    // $patientElement = "<p style='margin-top: -3px; margin-bottom: 0px;'><small><b>Patient: </b>  $patientName, <b>Age:</b> $patientAge </small></p><p style='margin-top: -5px; margin-bottom: 0px;'><small><b>M:</b> $patientPhno </small></p>"";
} else {
    // $patientElement = "<p style='margin-top: -3px; margin-bottom: 0px;'><small><b>Patient: </b>  $customerId</small></p>"";
    $patientName = 'Cash Sales';
    $patientPhno = '';
    $patientAge = '';
}



$selectClinicInfo = json_decode($ClinicInfo->showHealthCare($adminId));
// print_r($selectClinicInfo->data);
$pharmacyLogo = $selectClinicInfo->data->logo;
$pharmacyName = $selectClinicInfo->data->hospital_name;


// Include FPDF library
require('../assets/plugins/pdfprint/fpdf/fpdf.php');

// Extend the FPDF class
class PDF extends FPDF
{
    private $invoiceId;
    private $pMode;
    private $billDate;
    private $patientName;
    private $patientAge;
    private $patientPhno;
    private $totalGSt;
    private $totalMrp;
    private $billAmout;
    private $healthCareLogo;
    private $healthCareName;
    private $healthCareAddress1;
    private $healthCareAddress2;
    private $healthCareCity;
    private $healthCarePin;
    private $healthCarePhno;
    private $healthCareApntbkNo;
    private $gstinData;
    private $reffby;
    private $slno;
    private $isLastPage;

    // Constructor with parameters
    function __construct($invoiceId, $pMode, $billDate, $patientName, $patientAge, $patientPhno, $totalGSt, $totalMrp, $billAmout, $healthCareLogo, $healthCareName, $healthCareAddress1, $healthCareAddress2, $healthCareCity, $healthCarePin, $healthCarePhno, $healthCareApntbkNo, $gstinData, $reffby) {
        parent::__construct();

        $this->invoiceId = $invoiceId;
        $this->pMode = $pMode;
        $this->billDate = $billDate;
        $this->patientName = $patientName;
        $this->patientAge = $patientAge;
        $this->patientPhno = $patientPhno;
        $this->totalGSt = $totalGSt;
        $this->totalMrp = $totalMrp;
        $this->billAmout = $billAmout;
        $this->healthCareLogo = $healthCareLogo;
        $this->healthCareName = $healthCareName;
        $this->healthCareAddress1 = $healthCareAddress1;
        $this->healthCareAddress2 = $healthCareAddress2;
        $this->healthCareCity = $healthCareCity;
        $this->healthCarePin = $healthCarePin;
        $this->healthCarePhno = $healthCarePhno;
        $this->healthCareApntbkNo = $healthCareApntbkNo;
        $this->gstinData = $gstinData;
        $this->reffby = $reffby;
    }

    function Header() {
        global $healthCareLogo, $healthCareName, $healthCareAddress1, $healthCareAddress2, $healthCareCity, $healthCarePin, $healthCarePhno, $healthCareApntbkNo, $invoiceId, $pMode, $billdate, $patientName, $patientAge, $patientPhno, $gstinData;

        if ($this->PageNo() == 1) {  ///this line only show the header first page

            //.. healthCareLogo...///
            $logoX = 10;
            $logoY = 12;
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
            $address = "$healthCareAddress1, $healthCareAddress2\n$healthCareCity, $healthCarePin\nM: $healthCarePhno, $healthCareApntbkNo\nGST ID : $gstinData";
            
            $this->SetXY($logoX + $logoWidth + 5, $logoY + 8); // Position below the title
            $this->MultiCell(90, 5, $address, 0, 'L');

            ///...Invoice Info
            $this->SetY(20); // Reset Y position
            $this->SetX(-50); // Align to the right
            // Draw vertical line
            $this->SetDrawColor(108, 117, 125);
            $this->Line($this->GetX(), $this->GetY(), $this->GetX(), $this->GetY() + 20);
            $this->SetFont('Arial', 'B', 10);
            $this->cell(80, 0, ' Invoice:', 0, 'L');
            $this->SetFont('Arial', '', 10);
            $this->MultiCell(80, 5, "\n #$invoiceId\n Payment:$pMode\n Date: $this->billDate", 0, 'L');
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
            // $this->Ln(2);

           // Set the font for the footer content
           $this->SetFont('Arial', '', 10);

            // Patient Info
            $this->SetY($this->GetY() + 2); // Add some padding
            $startX = 10;
            $currentY = $this->GetY();

            $this->SetX($startX);
            if ($this->reffby !== 'Cash Sales') {
                $this->SetFont('Arial', 'B', 10);
                $this->Cell(30, 5, 'Referred By: ', 0, 0, 'L');
                $this->SetFont('Arial', '', 10);
                $this->Cell(30, 5, $this->reffby, 0, 'L');
            }else{
                $this->Cell(30, 5,'');
            }
            $this->SetX($startX);
            $this->SetFont('Arial', 'B', 10);
            $this->Cell(30, 5, 'Patient: ', 0, 0, 'L');
            $this->SetFont('Arial', '', 10);
            $this->Cell(30, 5, $this->patientName, 0, 'L');
            $this->SetX($startX);
            $this->SetFont('Arial', 'B', 10);
            $this->Cell(30, 5, 'Age: ', 0, 0, 'L');
            $this->SetFont('Arial', '', 10);
            $this->Cell(30, 5, $this->patientAge, 0, 1, 'L');
            $this->SetX($startX);
            $this->SetFont('Arial', 'B', 10);
            $this->Cell(30, 5, 'Contact: ', 0, 0, 'L');
            $this->SetFont('Arial', '', 10);
            $this->Cell(30, 5, $this->patientPhno, 0, 1, 'L');

            // GST Calculation
            $this->SetY(149); // Reset Y position
            $this->SetX(98); // Align to the right
            // Draw vertical line
            $this->SetDrawColor(108, 117, 125);
            $this->Line($this->GetX(), $this->GetY(), $this->GetX(), $this->GetY() + 22);

            $startX = 70;
            $this->SetY($currentY); // Reset Y position to top of the section
            $this->SetX($startX);
            $this->Cell(70, 5, 'CGST :', 0, 0, 'C');
            $this->Cell(-10, 5, ' ' . ($this->totalGSt / 2), 0, 1, 'C');
            $this->SetX($startX);
            $this->Cell(70, 5, 'SGST :', 0, 0, 'C');
            $this->Cell(-10, 5, ' ' . ($this->totalGSt / 2), 0, 1, 'C');
            $this->SetX($startX);
            $this->Cell(76, 5, 'Total GST :', 0, 0, 'C');
            $this->Cell(-24, 5, ' ' . $this->totalGSt, 0, 1, 'C');

            // Amount Calculation
            $startX = 140;
            $this->SetY($currentY); // Reset Y position to top of the section
            $this->SetX($startX);
            $this->Cell(20, 5, 'MRP :', 0, 0, 'R');
            $this->Cell(40, 5, ' ' . $this->totalMrp, 0, 1, 'R');
            $this->SetX($startX);
            $this->SetFont('Arial', 'B', 10);
            $this->Cell(26, 5, 'Payable :', 0, 0, 'R');
            $this->Cell(34, 5, ' ' . $this->billAmout, 0, 1, 'R');
            $this->SetX($startX);
            $this->SetFont('Arial', '', 10);
            $this->Cell(30, 5, 'You Saved  :', 0, 0, 'R');
            $this->Cell(28, 5, ' ' . ($this->totalMrp - $this->billAmout), 0, 1, 'R');
            
            $this->Ln(6);
            $this->SetDrawColor(108, 117, 125);
            $this->Line(10, $this->GetY(), 200, $this->GetY());
        }
    }

    function AddContentPage($details, $billDate, $pMode, $Products, $Manufacturer) {
        $this->AddPage();

        ///....add paid badge...///
        if( $this->$pMode != 'Credit'){
            $imageX = 50; // X position with left space
            $imageY = 70;
            $imageWidth = 100; // Adjusted width with spaces
            $imageHeight = 60; // Height of the image
           $this->Image('../assets/images/paid-seal.png', $imageX, $imageY, $imageWidth, $imageHeight);
       }///....end page badge...///

        $this->SetFont('Arial', 'B', 10);
        $this->Cell(16, -10, 'SL. NO.', 0, 0, 'L');
        $this->Cell(34, -10, 'Name', 0, 0, 'L');
        $this->Cell(18, -10, 'Manuf.', 0, 0, 'L');
        $this->Cell(20, -10, 'Batch', 0, 0, 'L');
        $this->Cell(16, -10, 'Exp.', 0, 0, 'L');
        $this->Cell(16, -10, 'QTY', 0, 0, 'L');
        $this->Cell(18, -10, 'MRP', 0, 0, 'L');
        $this->Cell(16, -10, 'Disc (%)', 0, 0, 'L');
        $this->Cell(16, -10, 'GST(%)', 0, 0, 'L');
        $this->Cell(18, -10, 'Amount ()', 0, 1, 'R');
        $this->Ln(10);
        $this->SetDrawColor(108, 117, 125);
        $this->Line(10, $this->GetY(), 200, $this->GetY()); // Draw line

        $slno = 1;
        $rowsPerPage = 8; // Maximum rows per page
        $rowCounter = 0;
        // Loop through details and add rows
        foreach ($details as $detail) {

        $checkTable = json_decode($Products->productExistanceCheck($detail['product_id']));
                
                        
                        if ($checkTable->status) {
                            $table = 'products';
                        } else {
                            $table = 'product_request';
                        }
                        

                        $productResponse = json_decode($Products->showProductsByIdOnTableName($detail['product_id'], $table));

                        $product = $productResponse->data;
                        // print_r($product);

                        $packQty = $product->unit_quantity;

                        if (isset($product->manufacturer_id)) {
                            $manuf = json_decode($Manufacturer->manufacturerShortName($product->manufacturer_id));

                            $manufacturerName = $manuf->status == 1 ? $manuf->data : '';
                        } else {
                            $manufacturerName = '';
                        }

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

        $this->SetFont('Arial', '', 8);
        // Draw dotted line between rows if $slno is greater than 1
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
        }

        $this->Cell(16, 10, $slno, 0, 0, 'L');
        $this->Cell(34, 10, substr($detail['item_name'], 0, 15), 0, 0, 'L');
        $this->Cell(18, 10, $manufacturerName, 0, 0, 'L');
        $this->Cell(21, 10, $detail['batch_no'], 0, 0, 'L');
        $this->Cell(16, 10, $detail['exp_date'], 0, 0, 'L');
        $this->Cell(15, 10, $detail['qty'], 0, 0, 'L');
        $this->Cell(18, 10, $detail['mrp'], 0, 0, 'L');
        $this->Cell(16, 10, $detail['discount'], 0, 0, 'L');
        $this->Cell(16, 10, $detail['gst'], 0, 0, 'L');
        $this->Cell(18, 10, $detail['amount'], 0, 1, 'R');

        $amount  = $amount + $detail['amount'];
        // $this->Ln(1); // Move to next line
        $slno++;
        $rowCounter++;
    }
       
    }

    //....footer set last page...//
    function AddLastPage() {
        $this->isLastPage = true;
    }//footer

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

    $stockOut  = $StockOut->stockOutDisplayById($invoiceId);
    foreach ($stockOut as $stockOut) {
     $reffby         = $stockOut['reff_by'];
     $billDate       = $stockOut['bill_date'];
     print_r($billdate);
    }

    // exit;
    $pdf = new PDF($invoiceId, $pMode, $billDate, $patientName, $patientAge, $patientPhno, $totalGSt, $totalMrp, $billAmout, $healthCareLogo, $healthCareName, $healthCareAddress1, $healthCareAddress2, $healthCareCity, $healthCarePin, $healthCarePhno, $healthCareApntbkNo, $gstinData, $reffby);

    $pdf->AliasNbPages();
    $pdf->AddContentPage($details,$billDate, $pMode, $Products, $Manufacturer);
    $pdf->AddLastPage();
    ob_clean();
    $pdf->Output();
    exit;
// }


?>

<!-- <!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= "Sales Invoice - $patientName - #$invoiceId"?></title>
    <link rel="stylesheet" href="<?php echo CSS_PATH ?>bootstrap 5/bootstrap.css">
    <link rel="stylesheet" href="<?php echo CSS_PATH ?>custom/receipts.css">
</head> -->


<body>
    <div class="custom-container">
        <div class="custom-body <?= $pMode != 'Credit' ? "paid-bg" : ""; ?>">
            <div class="card-body ">
                <div class="row">
                    <div class="col-1">
                        <img class="float-end" style="height: 55px; width: 58px;position: absolute;"
                            src="<?= $healthCareLogo ?>" alt="Medicy">
                    </div>
                    <div class="col-8 ps-4">
                        <h4 class="text-start my-0"><?php echo $healthCareName; ?></h4>
                        <p class="text-start" style="margin-top: -5px; margin-bottom: 0px;">
                            <small><?php echo $healthCareAddress1 . ', ' . $healthCareAddress2 . ', ' . $healthCareCity . ', ' . $healthCarePin; ?></small>
                        </p>
                        <p class="text-start" style="margin-top: -6px; margin-bottom: 0px;">
                            <small><?php echo 'M: ' . $healthCarePhno . ', ' . $healthCareApntbkNo; ?></small>
                        </p>
                        <p class="m-0" style="font-size: 0.850em;"><small><b>GST ID :</b>
                            </small><?php echo $gstinData ?></p>

                    </div>
                    <div class="col-3 invoice-info">
                        <p><b>Invoice</b></p>
                        <p><small>#<?= $invoiceId; ?></small></p>
                        <p><small>Payment: <?= $pMode; ?></small></p>
                        <p><small>Date: <?= $billdate; ?></small>
                        </p>
                    </div>
                </div>
            </div>
            <hr class="hr-divider">

            <table class="table">
                <thead class="table-header-divider">
                    <tr>
                        <th class="pt-1 pb-1"><small>SL.</small></th>
                        <th class="pt-1 pb-1"><small>Name</small></th>
                        <th class="pt-1 pb-1"><small>Manuf.</small></th>
                        <th class="pt-1 pb-1"><small>Batch</small></th>
                        <th class="pt-1 pb-1"><small>Exp.</small></th>
                        <th class="pt-1 pb-1"><small>QTY</small></th>
                        <th class="pt-1 pb-1"><small>MRP</small></th>
                        <th class="pt-1 pb-1"><small>Disc(%)</small></th>
                        <th class="pt-1 pb-1"><small>GST(%)</small></th>
                        <th class="pt-1 pb-1"><small>Amount</small></th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $slno = 0;
                    $subTotal = floatval(00.00);
                    foreach ($details as $index => $detail) {

                        //=========================
                        $checkTable = json_decode($Products->productExistanceCheck($detail['product_id']));
                
                        
                        if ($checkTable->status) {
                            $table = 'products';
                        } else {
                            $table = 'product_request';
                        }
                        //=========================

                        $productResponse = json_decode($Products->showProductsByIdOnTableName($detail['product_id'], $table));

                        $product = $productResponse->data;
                        // print_r($product);

                        $packQty = $product->unit_quantity;

                        if (isset($product->manufacturer_id)) {
                            $manuf = json_decode($Manufacturer->manufacturerShortName($product->manufacturer_id));

                            $manufacturerName = $manuf->status == 1 ? $manuf->data : '';
                        } else {
                            $manufacturerName = '';
                        }


                        $itemunit = $ItemUnit->itemUnitName($product->unit);
                        $packUnit = $PackagingUnits->packagingTypeName($product->packaging_type);
                        $weatage = "$itemunit of $packUnit";
                        $slno++;
                        $itemQty = intdiv($detail['loosely_count'], $packQty);

                        // ===================================================

                        if ($detail['loosely_count'] != 0) {
                            $itemSellQty = $detail['loosely_count'] / $detail['weightage'];

                            if (!is_int($itemSellQty)) {
                                $itemSellQty = $detail['loosely_count'] . ' ' . $detail['unit'];
                            }
                        } else {
                            $itemSellQty = $detail['qty'];
                        }

                        $isLastRow = $index === count($details) - 1;
                        // Add border style only if it's not the last row
                        $borderStyle = $isLastRow ? 'border-bottom: transparent;' : 'border-bottom: #dfdfdf;height:24px;';

                        echo '<tr style="' . $borderStyle . '">
                        <th scope="row" class="pt-1 pb-1"><small style="font-size: 0.750em;">' . $slno . '</small></th>
                        <td class="pt-1 pb-1"><small style="font-size: 0.750em;">' . substr($detail['item_name'], 0, 15) . '</small></td>
                        <td class="pt-1 pb-1"><small style="font-size: 0.750em;">' . $manufacturerName . '</small></td>
                        <td class="pt-1 pb-1"><small style="font-size: 0.750em;">' . $detail['batch_no'] . '</small></td>
                        <td class="pt-1 pb-1"><small style="font-size: 0.750em;">' . $detail['exp_date'] . '</small></td>
                        <td class="pt-1 pb-1"><small style="font-size: 0.750em;">' . $itemSellQty . '</small></td>
                        <td class="pt-1 pb-1"><small style="font-size: 0.750em;">' . $detail['mrp'] . '</small></td>
                        <td class="pt-1 pb-1"><small style="font-size: 0.750em;">' . (isset($detail['discount']) ? $detail['discount'] : '') . '</small></td>
                        <td class="pt-1 pb-1"><small style="font-size: 0.750em;">' . $detail['gst'] . '</small></td>
                        <td class="pt-1 pb-1"><small style="font-size: 0.750em;">' . $detail['amount'] . '</small></td>
                    </tr>';
                    }  ?>
                </tbody>
            </table>

            <div class="footer">
                <hr class="hr-divider">
                <div class="row my-0">
                    <div class="col-5">
                        <div class="row mt-2">
                            <div class="col-4 pe-0">
                                <?= $reffby !== 'Cash Sales' ? '<b><small>Referred By</small></b><br>' : " "; ?>
                                <b><small>Patient </small></b><br>
                                <b><small>Age</small></b><br>
                                <b><small>Contact</small></b>
                            </div>
                            <div class="col-8">
                                <!-- <?php $reffby !== 'Cash Sales' ? '<p class="text-start mb-0"><small>'.$reffby.'</small></p>' : 'Cash Sales'; ?> -->
                                <p class="text-start mb-0"><small><?= $reffby !== 'Cash Sales' ? $reffby :''?></small></p>
                                <p class="text-start mb-0"><small><?= ' :   ' . $patientName; ?></small></p>
                                <p class="text-start mb-0"><small><?= ' :   ' . $patientAge; ?></small></p>
                                <p class="text-start"><small><?= ' :   ' . $patientPhno; ?></small></p>
                            </div>
                        </div>
                    </div>
                    <div class="col-7 bill-summary">
                        <div class="col-12">
                            <div class="row mt-2">
                                <div class="col-2">
                                    <p class="m-0"><small>CGST</small></p>
                                    <p class="m-0"><small>SGST</small></p>
                                    <p style="width:4rem;"><small>Total GST</small></p>
                                </div>
                                <div class="col-4">
                                    <p class="m-0">
                                        <small>: ₹ <?php echo $totalGSt / 2; ?></small>
                                    </p>
                                    <p class="m-0">
                                        <small>: ₹ <?php echo $totalGSt / 2; ?></small>
                                    </p>
                                    <p class="m-0">
                                        <small>: ₹ <?php echo floatval($totalGSt); ?></small>
                                    </p>
                                </div>
                                <div class="col-2">
                                    <p class="m-0"><small>MRP</small></p>
                                    <b>
                                        <p class="m-0"><small>Payble</small></p>
                                    </b>
                                    <p style="width:4rem;"><small>You Saved</small></p>
                                </div>
                                <div class="col-4">
                                    <p class="m-0">
                                        <small>: ₹ <?php echo floatval($totalMrp); ?></small>
                                    </p>
                                    <p class="m-0">
                                        <b><small>: ₹ <?php echo floatval($billAmout); ?></small></b>
                                    </p>
                                    <p class="m-0">
                                        <small>: ₹ <?php echo $totalMrp - $billAmout; ?></small>
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <hr class="hr-divider mt-0">
            </div>
        </div>
    </div>
    <div class="justify-content-center print-sec d-flex my-5">
        <button class="btn btn-primary shadow mx-2" onclick="bactoNewSell()">Go Back</button>
        <!-- <button class="btn btn-primary shadow mx-2" onclick="window.print()">Print Bill</button> -->
        <form method="post">
            <button class="btn btn-primary shadow mx-2" type="submit" name="printPDF">Print PDF</button>
        </form>
    </div>
    </div>
</body>
<script src="<?php echo JS_PATH ?>bootstrap-js-5/bootstrap.js"></script>

<script>
const bactoNewSell = () => {
    window.location = "<?php echo LOCAL_DIR ?>new-sales.php";
}
</script>

</html>