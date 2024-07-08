<?php

require_once dirname(__DIR__) . '/config/constant.php';
require_once ROOT_DIR . '_config/sessionCheck.php'; //check admin loggedin or not

require_once CLASS_DIR . 'dbconnect.php';
require_once ROOT_DIR . '_config/healthcare.inc.php';
require_once CLASS_DIR . 'encrypt.inc.php';
require_once CLASS_DIR . 'hospital.class.php';
require_once CLASS_DIR . 'stockReturn.class.php';
require_once CLASS_DIR . 'distributor.class.php';
require_once CLASS_DIR . 'products.class.php';


//  INSTANTIATING CLASS
$HelthCare       = new HealthCare();
$StockReturn     = new StockReturn();
$Distributor     = new Distributor;
$StockReturn     = new StockReturn;
$Products        = new Products;

if (isset($_GET['data'])) {

    $reponse = json_decode(url_dec($_GET['data']));

    $stockReturnId   = $reponse->stock_return_id;
    if ($stockReturnId) {
        $returnResponse = json_decode($StockReturn->showStockReturnById($stockReturnId));
        // print_r($returnResponse);
        if ($returnResponse->status == 1) {

            $returnData     = $returnResponse->data[0];
            $returnDate     = $returnData->return_date;
            $totalReturnQty = $returnData->total_qty;
            $returnGst      = $returnData->gst_amount;
            $refundMode     = $returnData->refund_mode;
            $refund         = $returnData->refund_amount;
            $itemQty        = $returnData->items;
            $distributorId  = $returnData->distributor_id;
            $retuenAdmin    = $returnData->admin_id;

            $distributorResponse = json_decode($Distributor->showDistributorById($distributorId));

            if ($distributorResponse->status) {
                $distributorData = $distributorResponse->data;

                $distributorName = $distributorData->name;
                $distContact = $distributorData->phno;
                $distGST        = $distributorData->gst_id;
                $distAddress = $distributorData->address;
                $distPIN = $distributorData->area_pin_code;
            }

            $returnDetails = $StockReturn->showStockReturnDetails($stockReturnId);
        }
    }
}


$selectClinicInfo = json_decode($HelthCare->showHealthCare($ADMINID));
// print_r($selectClinicInfo->data);
$pharmacyLogo = $selectClinicInfo->data->logo;
$pharmacyName = $selectClinicInfo->data->hospital_name;
$pharmacyContact = $selectClinicInfo->data->hospital_phno;


// Include FPDF library
require('../assets/plugins/pdfprint/fpdf/fpdf.php');

class PDF extends FPDF
{
    private $pharmacyName, $pharmacyContact, $returnGst, $itemQty, $totalReturnQty, $refund;
    function __construct($pharmacyName, $pharmacyContact, $returnGst, $itemQty, $totalReturnQty, $refund) {
        parent::__construct();
        $this->pharmacyName = $pharmacyName;
        $this->pharmacyContact =$pharmacyContact;
        $this->returnGst = $returnGst;
        $this->itemQty = $itemQty;
        $this->totalReturnQty = $totalReturnQty;
        $this->refund = $refund;
    }

    function Header() {
        global $distributorName, $distAddress, $distPIN, $distContact, $stockReturnId,$refundMode,  $patientPhno,$returnDate, $gstinData;

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
            $this->Line($this->GetX(), $this->GetY(), $this->GetX(), $this->GetY() + 22);
            $this->SetFont('Arial', 'B', 10);
            $this->cell(45, 0, ' Return Bill', 0, 'L');
            $this->SetFont('Arial', '', 10);
            $this->MultiCell(45, 5, "\n Return ID:#$stockReturnId\n Refund Mode:$refundMode\n Return Date:$returnDate", 0, 'L');
            $this->Ln(3);
            $this->SetDrawColor(108, 117, 125);
            $this->Line(10, $this->GetY(), 200, $this->GetY());
            $this->Ln(10);
        }
    }

    function AddContentPage($returnDetails, $Products)
    {
        $this->AddPage();

        ///....add paid badge...///
        if( $this->$pMode != 'Credit'){
            if ($dueDate == $crrntDt){
            $imageX = 80; // X position with left space
            $imageY = 70;
            $imageWidth = 50; // Adjusted width with spaces
            $imageHeight = 50; // Height of the image
           $this->Image('../assets/images/refund-seal.png', $imageX, $imageY, $imageWidth, $imageHeight);
            }
       }///....end page badge...///

       $this->SetFont('Arial', 'B', 10);
       $this->Cell(30, -10, 'Name', 0, 0, 'L');
       $this->Cell(16, -10, 'Batch', 0, 0, 'L');
       $this->Cell(16, -10, 'Exp.', 0, 0, 'L');
       $this->Cell(16, -10, 'P.Qty', 0, 0, 'L');
       $this->Cell(16, -10, 'Free', 0, 0, 'L');
       $this->Cell(18, -10, 'MRP', 0, 0, 'L');
       $this->Cell(16, -10, 'PTR', 0, 0, 'L');
       $this->Cell(16, -10, 'GST%', 0, 0, 'L');
       $this->Cell(16, -10, 'DIS%', 0, 0, 'L');
       $this->Cell(16, -10, 'Return', 0, 0, 'L');
       $this->Cell(15, -10, 'Refund', 0, 1, 'R');
       $this->Ln(10);
       $this->SetDrawColor(108, 117, 125);
       $this->Line(10, $this->GetY(), 200, $this->GetY()); // Draw line

       $slno = 1;

       foreach ($returnDetails as $index => $eachDetail) {
        $productNameResponse = json_decode($Products->showProductNameById($eachDetail['product_id']));
        if ($productNameResponse->status) {
            $productName = $productNameResponse->data->name;
        }

        $batchNo        = $eachDetail['batch_no'];
        $expDate        = $eachDetail['exp_date'];
        $setof          = $eachDetail['unit'];
        $purchasedQty   = $eachDetail['purchase_qty'];
        $freeQty        = $eachDetail['free_qty'];
        $mrp            = $eachDetail['mrp'];
        $ptr            = $eachDetail['ptr'];
        $gstPercent     = $eachDetail['gst'];
        $discParcent    = $eachDetail['disc'];
        $returnQty      = $eachDetail['return_qty'];
        $refundAmount   = $eachDetail['refund_amount'];

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
            $yBefore = $this->GetY();
            $this->SetY($yBefore); // Reset Y to avoid new line issues
            $this->SetX($this->GetX());
            $this->MultiCell(35, 4, "$productName\n$setof", 0, 'L');
            $this->SetY($yBefore); // Reset Y to avoid new line issues
            $this->SetX($this->GetX()+30); // Move X to the next positio
            $this->Cell(16, 5, $batchNo, 0, 0, 'L');
            $this->Cell(16, 5, $expDate, 0, 0, 'L');
            // $this->Cell(16, 5, $setof, 0, 0, 'L');
            $this->Cell(16, 5, $purchasedQty, 0, 0, 'L');
            $this->Cell(18, 5, $freeQty, 0, 0, 'L');
            $this->Cell(16, 5, $mrp, 0, 0, 'L');
            $this->Cell(16, 5, $ptr, 0, 0, 'L');
            $this->Cell(16, 5, $gstPercent.'%', 0, 0, 'L');
            $this->Cell(16, 5, $discParcent.'%', 0, 0, 'L');
            $this->Cell(16, 5, $returnQty, 0, 0, 'L');
            $this->Cell(15, 5, $refundAmount, 0, 1, 'R');

            $this->Ln(9);

        $slno++;
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
            $this->Cell(30, 5, 'Customer : ', 0, 0, 'L');
            $this->SetFont('Arial', '', 10);
            $this->MultiCell(80, 5,  " $this->pharmacyName\n $this->pharmacyContact", 0, 'L');

            $this->SetY(149); // Reset Y position
            $this->SetX(92); // Align to the right
            // Draw vertical line
            $this->SetDrawColor(108, 117, 125);
            $this->Line($this->GetX(), $this->GetY(), $this->GetX(), $this->GetY() + 20);

            $startX = 70;
            $this->SetY($currentY); // Reset Y position to top of the section
            $this->SetX($startX);
            $this->Cell(72, 5, 'CGST :', 0, 0, 'C');
            $this->Cell(-10, 5, '' . $this->returnGst/2, 0, 1, 'C');
            $this->SetX($startX);
            $this->Cell(72, 5, 'SGST :', 0, 0, 'C');
            $this->Cell(-10, 5, '' . $this->returnGst/2, 0, 1, 'C');
            $this->SetX($startX);
            $this->Cell(78, 5, 'Total GST :', 0, 0, 'C');
            $this->Cell(-24, 5, '' . floatval($this->returnGst), 0, 1, 'C');

            $this->SetY(149); // Reset Y position
            $this->SetX(150); // Align to the right
            // Draw vertical line
            $this->SetDrawColor(108, 117, 125);
            $this->Line($this->GetX(), $this->GetY(), $this->GetX(), $this->GetY() + 20);

            // Amount Calculation
            $startX = 140;
            $this->SetY($currentY); // Reset Y position to top of the section
            $this->SetX($startX);
            $this->Cell(32, 5, 'Total Items :', 0, 0, 'R');
            $this->Cell(28, 5, '' . $this->itemQty, 0, 1, 'R');
            $this->SetX($startX);
            $this->SetFont('Arial', '', 10);
            $this->Cell(32, 5, 'Total Units :', 0, 0, 'R');
            $this->Cell(28, 5, '' . $this->totalReturnQty, 0, 1, 'R');
            $this->SetX($startX);
            $this->SetFont('Arial', '', 10);
            $this->Cell(35, 5, 'Total Refund :', 0, 0, 'R');
            $this->Cell(25, 5, '' . floatval($this->refund), 0, 1, 'R');
            
            $this->Ln(2);
            $this->SetDrawColor(108, 117, 125);
            $this->Line(10, $this->GetY(), 200, $this->GetY());
        }
    }

    //....footer set last page...//
    function AddLastPage() {
        $this->isLastPage = true;
    }//footer
}

if (isset($_POST['printPDF'])) {

    $pdf = new PDF($pharmacyName, $pharmacyContact, $returnGst, $itemQty, $totalReturnQty, $refund);
    $pdf->AliasNbPages();
    $pdf->AddContentPage($returnDetails, $Products);
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
    <title>Stock Return</title>
    <link rel="stylesheet" href="<?= CSS_PATH ?>bootstrap 5/bootstrap.css">
    <!-- <link rel="stylesheet" href="<?= CSS_PATH ?>custom/test-bill.css"> -->
    <link rel="stylesheet" href="<?= CSS_PATH ?>custom/receipts.css">

</head>


<body>
    <div class="custom-container">
        <div class="custom-body <?= $refundMode != 'Credit' ? "paid-bg" : ''; ?>">
            <div class="card-body ">
                <div class="row">
                    <!-- <div class="col-1 pe-0">
                        <img class="float-end" style="height: 55px; width: 55px; object-fit: cover;"
                            src="<?= LOCAL_DIR . $pharmacyLogo ?>" alt="Medicy">
                        <img class="float-end" style="height: 55px; width: 58px; object-fit: cover;"
                            src="<?= $healthCareLogo ?>" alt="Medicy">
                    </div> -->

                    <div class="col-9">
                        <h4 class="text-start my-1"><?php echo $distributorName; ?></h4>
                        <p class="text-start" style="margin-top: -5px; margin-bottom: 0px;">
                            <small><?php echo $distAddress . ', ' . $distPIN; ?></small>
                        </p>
                        <p class="text-start" style="margin-top:-6px; margin-bottom: 0px;">
                            <small><?php echo 'M: ' . $distContact; ?></small>
                        </p>
                        <p class="m-0" style="font-size: 0.850em;"><small><b>GST ID :</b></small><?php echo $distGST?>
                        </p>

                    </div>
                    <div class="col-3 border-start border-dark">
                        <p class="my-0"><b>Return Bill</b></p>
                        <p style="margin-top: -5px; margin-bottom: 0px;"><small>Return ID:
                                #<?php echo $stockReturnId; ?></small></p>
                        <p style="margin-top: -5px; margin-bottom: 0px;"><small>Refund Mode:
                                <?php echo $refundMode; ?></small>
                        </p>
                        <p style="margin-top: -5px; margin-bottom: 0px;"><small>Return Date:
                                <?php echo $returnDate; ?></small>
                        </p>
                    </div>
                </div>
            </div>
            <hr class="my-0" style="height:1px;opacity:1;">

            <!-- ===================================================== -->
            <table class="table">
                <thead>
                    <tr>
                        <th class="pt-1 pb-1" scope="col"><small>Name</small></th>
                        <th class="pt-1 pb-1" scope="col"><small>Batch</small></th>
                        <th class="pt-1 pb-1" scope="col"><small>Exp.</small></th>
                        <th class="pt-1 pb-1" scope="col"><small>P.Qty</small></th>
                        <th class="pt-1 pb-1" scope="col"><small>Free</small></th>
                        <th class="pt-1 pb-1" scope="col"><small>MRP</small></th>
                        <th class="pt-1 pb-1" scope="col"><small>PTR</small></th>
                        <th class="pt-1 pb-1" scope="col"><small>GST%</small></th>
                        <th class="pt-1 pb-1" scope="col"><small>DIS%</small></th>
                        <th class="pt-1 pb-1" scope="col"><small>Return</small></th>
                        <th class="pt-1 pb-1" scope="col"><small>Refund</small></th>
                    </tr>
                </thead>
                <tbody>
                <?php
                    foreach ($returnDetails as $index => $eachDetail) {

                        $productNameResponse = json_decode($Products->showProductNameById($eachDetail['product_id']));
                        if ($productNameResponse->status) {
                            $productName = $productNameResponse->data->name;
                        }

                        $batchNo        = $eachDetail['batch_no'];
                        $expDate        = $eachDetail['exp_date'];
                        $setof          = $eachDetail['unit'];
                        $purchasedQty   = $eachDetail['purchase_qty'];
                        $freeQty        = $eachDetail['free_qty'];
                        $mrp            = $eachDetail['mrp'];
                        $ptr            = $eachDetail['ptr'];
                        $gstPercent     = $eachDetail['gst'];
                        $discParcent    = $eachDetail['disc'];
                        $returnQty      = $eachDetail['return_qty'];
                        $refundAmount   = $eachDetail['refund_amount'];

                        $isLastRow = $index === count($returnDetails) - 1;
                        // Add border style only if it's not the last row
                        $borderStyle = $isLastRow ? 'border-bottom: transparent;' : 'border-bottom: #dfdfdf;height:24px;';

                    echo '<tr style="'.$borderStyle.'">
                        <th scope="row" class="pt-1 pb-1"><small style="font-weight: normal; font-size: 0.750em;">' . substr($productName, 0, 20) . '</small>
                        <br>
                        <small style="font-size: 0.750em;">' . $setof . '</small></th>
                        <td class="pt-1 pb-1"><small style="font-size: 0.750em;">' . strtoupper($batchNo) . '</small></td>
                        <td class="pt-1 pb-1"><small style="font-size: 0.750em;">' . $expDate . '</small></td>
                        <td class="pt-1 pb-1"><small style="font-size: 0.750em;">' . $purchasedQty . '</small></td>
                        <td class="pt-1 pb-1"><small style="font-size: 0.750em;">' . $freeQty . '</small></td>
                        <td class="pt-1 pb-1"><small style="font-size: 0.750em;">' . $mrp . '</small></td>
                        <td class="pt-1 pb-1"><small style="font-size: 0.750em;">' . $ptr . '</small></td>
                        <td class="pt-1 pb-1"><small style="font-size: 0.750em;">' . $gstPercent . '</small></td>
                        <td class="pt-1 pb-1"><small style="font-size: 0.750em;">' . $discParcent . '</small></td>
                        <td class="pt-1 pb-1"><small style="font-size: 0.750em;">' . $returnQty . '</small></td>
                        <td class="pt-1 pb-1"><small style="font-size: 0.750em;">' . $refundAmount . '</small></td>
                    </tr>';
                }?>
                </tbody>
                    
            </table>

            <!-- ===================================================== -->
            <div class="footer">
                <hr calss="" style="height: 1px;margin-bottom:0;opacity:1;">

                <!-- table total calculation -->
                <div class="row">
                    <div class="col-4 border-end border-secondary text-end">
                        <div class="row my-2">
                            <div class="col-4">
                                <small><b>Customer:</b></small>
                            </div>
                            <div class="col-8 text-end">
                                <small><?= $pharmacyName; ?></small>
                                <br>
                                <small><?= $pharmacyContact; ?></small>

                            </div>
                        </div>
                    </div>
                    <div class="col-4 border-end border-secondary">
                        <div class="row mt-2">
                            <div class="col-8 text-end">
                                <p style="margin-top: -5px; margin-bottom: 0px;"><small>CGST:</small></p>
                            </div>
                            <div class="col-4 text-end">
                                <p style="margin-top: -5px; margin-bottom: 0px;">
                                    <small>₹<?php echo $returnGst / 2; ?></small>
                                </p>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-8 text-end">
                                <p style="margin-top: -5px; margin-bottom: 0px;"><small>SGST:</small></p>
                            </div>
                            <div class="col-4 text-end">
                                <p style="margin-top: -5px; margin-bottom: 0px;">
                                    <small>₹<?php echo $returnGst / 2; ?></small>
                                </p>
                            </div>
                        </div>
                        <div class="row mb-2">
                            <div class="col-8 text-end">
                                <p style="margin-top: -5px; margin-bottom: 0px;"><small>Total GST:</small></p>
                            </div>
                            <div class="col-4 text-end">
                                <p style="margin-top: -5px; margin-bottom: 0px;">
                                    <small>₹<?php echo floatval($returnGst); ?></small>
                                </p>
                            </div>
                        </div>
                    </div>
                    <div class="col-4 my-2">
                        <div class="row text-end">
                            <small class="pt-0 mt-0">Total Items <b><?php echo $itemQty; ?></b> & Total Units
                                <b><?php echo $totalReturnQty; ?></b></small>
                        </div>
                        <div class="row text-end mt-1">
                            <b><small class="mb-0 pb-0">Total Refund : ₹ <?php echo floatval($refund); ?></small></b>

                        </div>

                    </div>

                </div>
                <hr calss="my-0" style="height: 1px;margin-top:0;opacity:1;">
            </div>
        </div>
    </div>
    <!-- <hr style="height: 1px;"> -->
    </div>
    </div>
    <div class="justify-content-center print-sec d-flex my-5">
        <button class="btn btn-primary shadow mx-2" onclick="goBack()">Go Back</button>
        <!-- <button class="btn btn-primary shadow mx-2" onclick="window.print()">Print Bill</button> -->
        <form method="post">
            <button class="btn btn-primary shadow mx-2" type="submit" name="printPDF">Print PDF</button>
        </form>  
    </div>
    </div>
</body>
<script src="<?= JS_PATH ?>bootstrap-js-5/bootstrap.js"></script>

<script>
const goBack = () => {
    window.location.href = '<?= URL ?>stock-return.php';
}
</script>

</html>