<?php
require_once dirname(__DIR__) . '/config/constant.php';
require_once ROOT_DIR . '_config/sessionCheck.php'; //check admin loggedin or not

require_once CLASS_DIR . 'dbconnect.php';
require_once CLASS_DIR . 'encrypt.inc.php';
require_once ROOT_DIR . '_config/healthcare.inc.php';
require_once CLASS_DIR . 'hospital.class.php';
require_once CLASS_DIR . 'distributor.class.php';
require_once CLASS_DIR . 'stockInDetails.class.php';
require_once CLASS_DIR . 'products.class.php';
require_once CLASS_DIR . 'packagingUnit.class.php';
require_once CLASS_DIR . 'manufacturer.class.php';
require_once CLASS_DIR . 'stockIn.class.php';
require_once CLASS_DIR . 'itemUnit.class.php';


$ClinicInfo     = new HealthCare;
$Distributor    = new Distributor();
$StockInDetails = new StockInDetails();
$Products       = new Products();
$PackagingUnits = new PackagingUnits();
$Manufacturer   = new Manufacturer();
$StockIn        = new StockIn();
$ItemUnit       = new ItemUnit;


if (isset($_GET['data'])) {
    $response =  json_decode(url_dec($_GET['data']));

    // $distributorId      = $response->distributorId;
    $stockIn_Id         = $response->stockIn_Id;
    // $distributorBill    = $response->distributorBill;
    // $stockIn_Id         = $response->stockIn_Id;

    $stockInData   = $StockIn->selectStockInById($stockIn_Id);
    $distributorId      = $stockInData[0]['distributor_id'];
    $distributorBill    = $stockInData[0]['distributor_bill'];
    $pMode              = $stockInData[0]['payment_mode'];
    $dueDate            = $stockInData[0]['due_date'];
    $billDate           = $stockInData[0]['bill_date'];
    $crrntDt            = $newDateFormat = date("d-m-Y", strtotime(NOW));


    $distributorResponse = json_decode($Distributor->showDistributorById($distributorId));

    if ($distributorResponse->status == 1) {

        $distributorDetails = $distributorResponse->data;

        $distributorName    = $distributorDetails->name;
        $distAddress        = $distributorDetails->address;
        $distGST            = $distributorDetails->gst_id;
        $distPIN            = $distributorDetails->area_pin_code;
        $distContact        = $distributorDetails->phno;
        $distEmail          = $distributorDetails->email;
    }
}




// $selectClinicInfo = json_decode($ClinicInfo->showHealthCare($adminId));
// // print_r($selectClinicInfo->data);
// $pharmacyLogo = $selectClinicInfo->data->logo;
// $pharmacyName = $selectClinicInfo->data->hospital_name;
// $pharmacyContact = $selectClinicInfo->data->hospital_phno;

$selectClinicInfo = json_decode($ClinicInfo->showHealthCare($adminId));
// // print_r($selectClinicInfo->data);
$pharmacyLogo       = $selectClinicInfo->data->logo;
$pharmacyName       = $selectClinicInfo->data->hospital_name;
$pharmacyContact    = $selectClinicInfo->data->hospital_phno;


// Include FPDF library
require('../assets/plugins/pdfprint/fpdf/fpdf.php');

class PDF extends FPDF
{
    private $pharmacyName, $pharmacyContact, $cGst, $totalGst, $totalMrp, $billAmnt;
    function __construct($pharmacyName, $pharmacyContact, $cGst, $totalGst, $totalMrp, $billAmnt) {
        parent::__construct();
        $this->pharmacyName = $pharmacyName;
        $this->pharmacyContact =$pharmacyContact;
        $this->cGst = $cGst;
        $this->totalGst = $totalGst;
        $this->totalMrp = $totalMrp;
        $this->billAmnt = $billAmnt;
    }
    function Header() {
        global $distributorName, $distAddress, $distPIN, $distContact, $healthCareApntbkNo,$distributorBill, $pMode,$billDate, $dueDate, $patientName, $patientAge, $patientPhno, $gstinData;

        if ($this->PageNo() == 1) {  ///this line only show the header first page

            ///....Title (distributorName Name)...///
            $this->SetFont('Arial', 'B', 16);
            $this->Cell(90, 8, $distributorName, 0, 1, 'L'); // Centered text

            // Address
            $this->SetFont('Arial', '', 10);
            $address = "$distAddress, $distPIN\nM: $distContact,\nGST ID : $gstinData";
            $this->MultiCell(100, 5, $address, 0, 'L');

            ///...Invoice Info
            $this->SetY(15); // Reset Y position
            $this->SetX(-50); // Align to the right
            // Draw vertical line
            $this->SetDrawColor(108, 117, 125);
            $this->Line($this->GetX(), $this->GetY(), $this->GetX(), $this->GetY() + 24);
            $this->SetFont('Arial', 'B', 10);
            $this->cell(80, 0, ' Purchase:', 0, 'L');
            $this->SetFont('Arial', '', 10);
            $this->MultiCell(80, 5, "\n #$distributorBill\n Payment:$pMode\n Bill Date:$billDate\n Due Date: $dueDate", 0, 'L');

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
            $this->Ln(2);

           // Set the font for the footer content
           $this->SetFont('Arial', '', 10);

            // Patient Info
            $this->SetY($this->GetY() + 2); // Add some padding
            $startX = 10;
            $currentY = $this->GetY();

            $this->SetX($startX);
            $this->SetFont('Arial', 'B', 10);
            $this->Cell(30, 5, 'Bill To : ', 0, 0, 'L');
            $this->SetFont('Arial', '', 10);
            $this->MultiCell(80, 5,  " $this->pharmacyName\n $this->pharmacyContact", 0, 'L');

            // GST Calculation
            $this->SetY(149); // Reset Y position
            $this->SetX(92); // Align to the right
            // Draw vertical line
            $this->SetDrawColor(108, 117, 125);
            $this->Line($this->GetX(), $this->GetY(), $this->GetX(), $this->GetY() + 20);

            $startX = 70;
            $this->SetY($currentY); // Reset Y position to top of the section
            $this->SetX($startX);
            $this->Cell(72, 5, 'CGST :', 0, 0, 'C');
            $this->Cell(-10, 5, '' . $this->cGst, 0, 1, 'C');
            $this->SetX($startX);
            $this->Cell(72, 5, 'SGST :', 0, 0, 'C');
            $this->Cell(-10, 5, '' . $this->cGst, 0, 1, 'C');
            $this->SetX($startX);
            $this->Cell(78, 5, 'Total GST :', 0, 0, 'C');
            $this->Cell(-24, 5, '' . $this->totalGst, 0, 1, 'C');

            // Amount Calculation
            $startX = 140;
            $this->SetY($currentY); // Reset Y position to top of the section
            $this->SetX($startX);
            $this->Cell(20, 5, 'MRP :', 0, 0, 'R');
            $this->Cell(40, 5, '' . $this->totalMrp, 0, 1, 'R');
            $this->SetX($startX);
            $this->SetFont('Arial', 'B', 10);
            $this->Cell(30, 5, 'You Saved :', 0, 0, 'R');
            $this->Cell(30, 5, '' . ($this->totalMrp - $this->billAmnt), 0, 1, 'R');
            $this->SetX($startX);
            $this->SetFont('Arial', '', 10);
            $this->Cell(30, 5, 'Net Amoun :', 0, 0, 'R');
            $this->Cell(30, 5, '' . $this->billAmnt, 0, 1, 'R');
            
            $this->Ln(2);
            $this->SetDrawColor(108, 117, 125);
            $this->Line(10, $this->GetY(), 200, $this->GetY());
        }
    }

    function AddContentPage($stockIn_Id, $pMode, $dueDate, $crrntDt , $distributorBill,$distributorId,$Products, $StockInDetails, $adminId, $ItemUnit) {
        $this->AddPage();

        ///....add paid badge...///
        if( $this->$pMode != 'Credit'){
            if ($dueDate == $crrntDt){
            $imageX = 50; // X position with left space
            $imageY = 70;
            $imageWidth = 100; // Adjusted width with spaces
            $imageHeight = 60; // Height of the image
           $this->Image('../assets/images/paid-seal.png', $imageX, $imageY, $imageWidth, $imageHeight);
            }
       }///....end page badge...///

        $this->SetFont('Arial', 'B', 10);
        $this->Cell(12, -10, 'SL.', 0, 0, 'L');
        $this->Cell(30, -10, 'Item', 0, 0, 'L');
        $this->Cell(16, -10, 'Batch', 0, 0, 'L');
        $this->Cell(16, -10, 'Exp.', 0, 0, 'L');
        $this->Cell(16, -10, 'QTY', 0, 0, 'L');
        $this->Cell(16, -10, 'F.QTY', 0, 0, 'L');
        $this->Cell(18, -10, 'MRP', 0, 0, 'L');
        $this->Cell(16, -10, 'PTR', 0, 0, 'L');
        $this->Cell(16, -10, 'Disc', 0, 0, 'L');
        $this->Cell(16, -10, 'GST', 0, 0, 'L');
        $this->Cell(16, -10, 'Amount', 0, 1, 'R');
        $this->Ln(10);
        $this->SetDrawColor(108, 117, 125);
        $this->Line(10, $this->GetY(), 200, $this->GetY()); // Draw line

        $slno = 1;
        $itemBillNo    = $distributorBill;
        $distributorId = $distributorId;
        $totalGst   = 0;
        $totalMrp   = 0;
        $billAmnt   = 0;

        $itemDetials = $StockInDetails->showStockInDetailsByStokId($stockIn_Id);
        
        foreach ($itemDetials as $index => $itemsData) {

            $prodId = $itemsData['product_id'];
            $chkExistance = json_decode($Products->productExistanceCheck($prodId));
            if ($chkExistance->status) {
                $edtRqstFlg = 1;
            } else {
                $edtRqstFlg = '';
            }
            $productDetails = json_decode($Products->showProductsByIdOnUser($prodId, $adminId, $edtRqstFlg));
            $productDetails = $productDetails->data;

            foreach ($productDetails as $pData) {
                $pname = $pData->name;
                if (isset($pData->manufacturer_id)) {
                    $pManfId = $pData->manufacturer_id;
                } else {
                    $pManfId = '';
                }
                $pQTY = $pData->unit_quantity;
                $pUnit = $pData->unit;
                $itemUnitName = $ItemUnit->itemUnitName($pUnit);
            }

            $batchNo = $itemsData['batch_no'];
            $ExpDate        = $itemsData['exp_date'];
            $qty            = $itemsData['qty'];
            $FreeQty        = $itemsData['free_qty'];
            $Mrp            = $itemsData['mrp'];
            $Ptr            = $itemsData['ptr'];
            $discPercent    = $itemsData['discount'];
            $gstPercent     = $itemsData['gst'];
            $Amount         = $itemsData['amount'];
            $gstAmnt        = $itemsData['gst_amount'];
            $totalGst       = $totalGst + $gstAmnt;
            $totalMrp       = $totalMrp + ($Mrp * $qty);
            $billAmnt       = $billAmnt + $Amount;
            $cGst           = $sGst = number_format($totalGst / 2, 2);

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

            $this->Ln(2);
            $this->SetFont('Arial', '', 8);
           
            $this->Cell(-5, 5, $slno, 0, 0, 'L');
            $yBefore = $this->GetY();
            $this->SetY($yBefore); // Reset Y to avoid new line issues
            $this->SetX($this->GetX() + 10); // Move X to the next position
            $this->MultiCell(35, 4, "$pname\n$pQTY $itemUnitName", 0, 'L');
            $this->SetY($yBefore); // Reset Y to avoid new line issues
            $this->SetX($this->GetX() + 42); // Move X to the next position
            $this->Cell(16, 5, $batchNo, 0, 0, 'L');
            $this->Cell(16, 5, $ExpDate, 0, 0, 'L');
            $this->Cell(16, 5, $qty, 0, 0, 'L');
            $this->Cell(16, 5, $FreeQty, 0, 0, 'L');
            $this->Cell(18, 5, $Mrp, 0, 0, 'L');
            $this->Cell(16, 5, $Ptr, 0, 0, 'L');
            $this->Cell(16, 5, $discPercent.'%', 0, 0, 'L');
            $this->Cell(16, 5, $gstPercent.'%', 0, 0, 'L');
            $this->Cell(16, 5, $Amount, 0, 1, 'R');

            $this->Ln(8);

        $slno++;
        }

    }    

    //....footer set last page...//
    function AddLastPage() {
        $this->isLastPage = true;
    }//footer
}

if (isset($_POST['printPDF'])) {

    $totalGst = 0;
    $totalMrp = 0;
    $billAmnt = 0;
    $itemDetials = $StockInDetails->showStockInDetailsByStokId($stockIn_Id);
    foreach ($itemDetials as $index => $itemsData) {
        $batchNo        = $itemsData['batch_no'];
        $ExpDate        = $itemsData['exp_date'];
        $qty            = $itemsData['qty'];
        $FreeQty        = $itemsData['free_qty'];
        $Mrp            = $itemsData['mrp'];
        $Ptr            = $itemsData['ptr'];
        $discPercent    = $itemsData['discount'];
        $gstPercent     = $itemsData['gst'];
        $Amount         = $itemsData['amount'];
        $gstAmnt        = $itemsData['gst_amount'];
        $totalGst       = $totalGst + $gstAmnt;
        $totalMrp       = $totalMrp + ($Mrp * $qty);
        $billAmnt       = $billAmnt + $Amount;
        $cGst           = $sGst = number_format($totalGst / 2, 2);
    }

    $pdf = new PDF($pharmacyName, $pharmacyContact, $cGst, $totalGst, $totalMrp, $billAmnt);
    $pdf->AliasNbPages();
    $pdf->AddContentPage($stockIn_Id, $pMode, $dueDate, $crrntDt , $distributorBill,$distributorId,$Products, $StockInDetails, $adminId,$ItemUnit);
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
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Medicy Health Care Medicine Purchase Bill</title>
    <link rel="stylesheet" href="<?= CSS_PATH ?>bootstrap/5.3.3/dist/css/bootstrap.css">
    <link rel="stylesheet" href="<?= CSS_PATH ?>custom/receipts.css">

    <style type="text/css">
        @page {
            size: landscape;
        }
    </style>

    <!-- Include SweetAlert2 CSS -->
    <link href="<?= CSS_PATH ?>sweetalert2/sweetalert2.min.css" rel="stylesheet">

    <!-- Include SweetAlert2 JavaScript -->
    <script src="<?= JS_PATH ?>sweetalert2/sweetalert2.all.min.js"></script>
</head>


<body>
    <div class="custom-container">
        <div class="custom-body <?php if ($pMode != 'Credit') {
                                    if ($dueDate == $crrntDt) {
                                        echo "paid-bg";
                                    }
                                } ?>">
            <div class="card-body ">
                <div class="row">
                    <!-- <div class="col-sm-1"> -->
                    <!-- <img class="float-end" style="height: 55px; width: 58px;" src="<?= LOCAL_DIR . $pharmacyLogo ?>" alt="Medicy"> -->
                    <!-- <img class="float-end" style="height: 55px; width: 58px;" src="<?= $healthCareLogo ?>"
                            alt="Medicy"> -->
                    <!-- </div> -->
                    <div class="col-sm-9">
                        <h4 class="text-start my-1"><?= $distributorName; ?></h4>
                        <p class="text-start" style="margin-top: -5px; margin-bottom: 0px;">
                            <small><?php echo $distAddress . ', PIN - ' . $distPIN; ?></small>
                        </p>
                        <p class="text-start" style="margin-top: -6px; margin-bottom: 0px;">
                            <small><?php echo 'M: ' . $distContact  ?></small>
                        </p>
                        <p class="m-0" style="font-size: 0.850em;"><small><b>GST ID :</b></small><?php echo $distGST ?>
                        </p>

                    </div>
                    <div class="col-sm-3 invoice-info">
                        <strong>Purchase</strong>
                        <p>#<?= $distributorBill; ?></p>
                        <p>Payment Mode:<?= $pMode; ?></p>
                        <p>Bill Date:<?= $billDate; ?></p>
                        <p>Due Date:<?= $dueDate; ?></p>
                    </div>
                </div>
            </div>
            <hr class=" mt-1 mb-0" style="opacity:0.3">

            <table class="table">
                <thead>
                    <tr>
                        <th class="pt-1 pb-1" scope="col"><small>SL.</small></th>
                        <!-- <th class="pt-1 pb-1" scope="col"><small>P Id</small></th> -->
                        <th class="pt-1 pb-1" scope="col"><small>Item</small></th>
                        <!-- <th class="pt-1 pb-1" scope="col"><small>Manuf.</small></th> -->
                        <th class="pt-1 pb-1" scope="col"><small>Batch</small></th>
                        <th class="pt-1 pb-1" scope="col"><small>Exp.</small></th>
                        <th class="pt-1 pb-1" scope="col"><small>QTY</small></th>
                        <th class="pt-1 pb-1" scope="col"><small>F.QTY</small></th>
                        <th class="pt-1 pb-1" scope="col"><small>MRP</small></th>
                        <th class="pt-1 pb-1" scope="col"><small>PTR</small></th>
                        <th class="pt-1 pb-1" scope="col"><small>Disc.</small></th>
                        <th class="pt-1 pb-1" scope="col"><small>GST</small></th>
                        <th class="pt-1 pb-1" scope="col"><small>Amount</small></th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $slno = 0;
                    $itemBillNo    = $distributorBill;
                    $distributorId = $distributorId;
                    $totalGst   = 0;
                    $totalMrp   = 0;
                    $billAmnt   = 0;
                    // $stokInId = $stokInid;

                    $itemDetials = $StockInDetails->showStockInDetailsByStokId($stockIn_Id);

                    foreach ($itemDetials as $index => $itemsData) {
                        $slno++;

                        $prodId = $itemsData['product_id'];

                        $chkExistance = json_decode($Products->productExistanceCheck($prodId));
                        if ($chkExistance->status) {
                            $edtRqstFlg = 1;
                        } else {
                            $edtRqstFlg = '';
                        }

                        $productDetails = json_decode($Products->showProductsByIdOnUser($prodId, $adminId, $edtRqstFlg));
                        // print_r($productDetails);
                        $productDetails = $productDetails->data;

                        foreach ($productDetails as $pData) {
                            // print_r($pData);
                            $pname = $pData->name;
                            if (isset($pData->manufacturer_id)) {
                                $pManfId = $pData->manufacturer_id;
                            } else {
                                $pManfId = '';
                            }
                            $pQTY = $pData->unit_quantity;
                            $pUnit = $pData->unit;

                            // echo $pUnit;
                            $itemUnitName = $ItemUnit->itemUnitName($pUnit);
                            // echo $itemUnitName;
                        }


                        if ($pManfId != '') {
                            $manufDetails = json_decode($Manufacturer->showManufacturerById($pManfId));
                            $manufDetails = $manufDetails->data;
                            // print_r($manufDetails);
                            if (isset($manufDetails->short_name)) {
                                $manufName = $manufDetails->short_name;
                            } else {
                                $manufName = '';
                            }
                        } else {
                            $manufName = '';
                        }


                        $batchNo        = $itemsData['batch_no'];
                        $ExpDate        = $itemsData['exp_date'];
                        $qty            = $itemsData['qty'];
                        $FreeQty        = $itemsData['free_qty'];
                        $Mrp            = $itemsData['mrp'];
                        $Ptr            = $itemsData['ptr'];
                        $discPercent    = $itemsData['discount'];
                        $gstPercent     = $itemsData['gst'];
                        $Amount         = $itemsData['amount'];
                        $gstAmnt        = $itemsData['gst_amount'];
                        $totalGst       = $totalGst + $gstAmnt;
                        $totalMrp       = $totalMrp + ($Mrp * $qty);
                        $billAmnt       = $billAmnt + $Amount;
                        $cGst           = $sGst = number_format($totalGst / 2, 2);


                        // if ($slno > 1) {
                        //     echo '<hr style="width: 98%; border-top: 1px dashed #8c8b8b; margin: 0 10px 0; align-items: center;">';
                        // }

                        $isLastRow = $index === count($itemDetials) - 1;
                        // Add border style only if it's not the last row
                        $borderStyle = $isLastRow ? 'border-bottom: transparent;' : 'border-bottom: #dfdfdf;height:24px;';
                    ?>
                        <tr style="<?php echo $borderStyle ?>">
                            <th scope="row" class="pt-1 pb-1"><small style="font-size: 0.750em;">
                                    <?php echo "$slno" ?></small></th>
                            <td class="pt-1 pb-1"><small style="font-size: 0.750em;"><?php echo "$pname" ?>
                                <br>
                                <?php echo $pQTY . ' ' . $itemUnitName ?></small>
                            </td>
                            <!-- <td class="pt-1 pb-1"><small style="font-size: 0.750em;"><?php // echo "$manufName" ?></small></td>
                            </td> -->
                            <td class="pt-1 pb-1"><small style="font-size: 0.750em;"><?php echo "$batchNo" ?></small></td>
                            <td class="pt-1 pb-1"><small style="font-size: 0.750em;"><?php echo "$ExpDate" ?></small></td>
                            <td class="pt-1 pb-1"><small style="font-size: 0.750em;"><?php echo "$qty" ?></small></td>
                            <td class="pt-1 pb-1"><small style="font-size: 0.750em;"><?php echo "$FreeQty" ?></small></td>
                            <td class="pt-1 pb-1"><small style="font-size: 0.750em;"><?php echo "$Mrp" ?></small></td>
                            <td class="pt-1 pb-1"><small style="font-size: 0.750em;"><?php echo "$Ptr" ?></small></td>
                            <td class="pt-1 pb-1"><small style="font-size: 0.750em;"><?php echo "$discPercent%" ?></small>
                            </td>
                            <td class="pt-1 pb-1"><small style="font-size: 0.750em;"><?php echo "$gstPercent%" ?></small>
                            </td>
                            <td class="pt-1 pb-1"><small style="font-size: 0.750em;"><?php echo "$Amount" ?></small></td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>

            <!-- <hr class="my-0" style="height:1px;"> -->

            <!-- </div> -->
            <div class="footer">
                <hr class="my-0" style="opacity:0.5;">

                <!-- table total calculation -->
                <div class="row">
                    <div class="col-5">
                        <div class="row mt-1">
                            <div class="col-6">
                                <b><small class="ms-2">Bill To : </small></b>
                            </div>
                            <div class="col-6">
                                <p class=" mb-0"><small><?= $pharmacyName ?></small></p>
                                <p class=""><small><?= $pharmacyContact ?></small></p>
                            </div>
                        </div>
                    </div>
                    <div class="col-7 border-start border-dark">
                        <div class="row my-2">
                            <div class="col-5">
                                <div class="row">
                                    <div class="col-8 text-end">
                                        <p style="margin-top: -5px; margin-bottom: 0px;"><small>CGST :</small></p>
                                    </div>
                                    <div class="col-4 text-end">
                                        <p style="margin-top: -5px; margin-bottom: 0px;">
                                            <!-- <small>₹<?php echo $totalGSt / 2; ?></small> -->
                                            <small>₹<?php echo "$cGst" ?></small>
                                        </p>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-8 text-end">
                                        <p style="margin-top: -5px; margin-bottom: 0px;"><small>SGST :</small></p>
                                    </div>
                                    <div class="col-4 text-end">
                                        <p style="margin-top: -5px; margin-bottom: 0px;">
                                            <!-- <small>₹<?php echo $totalGst / 2; ?></small> -->
                                            <small>₹<?php echo "$cGst" ?></small>
                                        </p>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-8 text-end">
                                        <p style="margin-top: -5px; margin-bottom: 0px;"><small>Total GST :</small></p>
                                    </div>
                                    <div class="col-4 text-end">
                                        <p style="margin-top: -5px; margin-bottom: 0px;">
                                            <!-- <small>₹<?php echo floatval($totalGSt); ?></small> -->
                                            <small>₹<?php echo "$totalGst" ?></small>
                                        </p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-7">
                                <div class="row">
                                    <div class="col-8 text-end">
                                        <p style="margin-top: -5px; margin-bottom: 0px;"><small>Total MRP :</small></p>
                                    </div>
                                    <div class="col-4 text-start">
                                        <p style="margin-top: -5px; margin-bottom: 0px;">
                                            <small>₹ <?php echo "$totalMrp" ?></small>
                                        </p>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-8 text-end">
                                        <p style="margin-top: -5px; margin-bottom: 0px;"><small>You Saved :</small></p>
                                    </div>
                                    <div class="col-4 text-start">
                                        <p style="margin-top: -5px; margin-bottom: 0px;">
                                            <small>₹ <?php echo $totalMrp - $billAmnt ?></small>
                                        </p>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-8 text-end">
                                        <p style="margin-top: -5px; margin-bottom: 0px;"><small>Net Amount :</small></p>
                                    </div>
                                    <div class="col-4 text-start">
                                        <p style="margin-top: -5px; margin-bottom: 0px;">
                                            <!-- <small><b>₹<?php echo floatval($billAmout); ?></b></small> -->
                                            <small><b>₹ <?php echo "$billAmnt" ?></b></small>
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <hr class="my-0" style="opacity: 0.5;">
            </div>
        </div>
    </div>
    <div class="justify-content-center print-sec d-flex my-5">
        <button class="btn btn-secondary shadow mx-2" style="background-color: #e7e7e7; color: black;" onclick="goBack('<?php echo $stockIn_Id ?>','<?php echo $itemBillNo ?>')">Go Back</button>
        <button class="btn btn-primary shadow mx-2"> <a class="text-light text-decoration-none" href="<?= URL . 'purchase-details.php'; ?>">See Details</a></button>
        <button class="btn btn-primary shadow mx-2" onclick="back()">Add New</button>
        <!-- <button class="btn btn-primary shadow mx-2" style="background-color: #4CAF50;" onclick="window.print()">Print
            Bill</button> -->
        <form method="post">
            <button class="btn btn-primary shadow mx-2" type="submit" name="printPDF">Print PDF</button>
        </form>    
    </div>
    </div>

</body>
<script src="<?= JS_PATH ?>bootstrap-js-5/bootstrap.js"></script>
<script>
    const back = () => {
        window.location.replace("<?= URL ?>stock-in.php")
    }

    const goBack = (id, value) => {
        location.href = `<?= URL ?>stock-in-edit.php?edit=${value}&editId=${id}`;
    }
</script>

</html>