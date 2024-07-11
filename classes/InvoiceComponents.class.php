<?php

trait PrintComponents
{

    function billHeader()
    {
        if ($this->PageNo() == 1) {  ///this line only show the header first page

            //.. healthCareLogo...//
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
            $this->Cell(90, 8, $this->healthCareName, 0, 1, 'L'); // Centered text

            // Address
            $this->SetFont('Arial', '', 10);
            $address = $this->healthCareAddress1 . "," . $this->healthCareAddress2 . "\n" . $this->healthCareCity . "," . $this->healthCarePin ."\nGST ID :" . $this->gstinData;

            $this->SetXY($logoX + $logoWidth + 5, $logoY + 8); // Position below the title
            $this->MultiCell(90, 5, $address, 0, 'L');

            //...Invoice Info
            $this->SetY(15); // Reset Y position
            $this->SetX(-50); // Align to the right
            // Draw vertical line
            $this->SetDrawColor(108, 117, 125);
            $this->Line($this->GetX(), $this->GetY(), $this->GetX(), $this->GetY() + 20);

            $this->SetFont('Arial', 'B', 10);
            $this->cell(80, 0, ' Invoice', 0, 'L');
            $this->SetFont('Arial', '', 10);
            $this->MultiCell(80, 5, "\n #" . $this->invoiceId . "\n Payment:" . $this->pMode . "\n Date:" . $this->billDate, 0, 'L');
            $this->Ln(5);

            $this->SetDrawColor(108, 117, 125);
            $this->Line(10, $this->GetY(), 200, $this->GetY());
            $this->Ln(10);
        }
    }
    // Page footer
    function billFooter()
    {
        $FooterFontSize = 8;

        if ($this->isLastPage) { /// this line only show the footer last page 

            $pageHeight = $this->GetPageHeight();
            $middleY = $pageHeight / 2;
            $this->SetY($middleY);
            $this->SetLineWidth(0.4);
            $this->SetDrawColor(108, 117, 125);
            $this->Line(10, $this->GetY(), 200, $this->GetY());
            // $this->Ln(2);

            // Set the font for the footer content
            $this->SetFont('Arial', '', $FooterFontSize);

            // Patient Info
            $this->SetY($this->GetY() + 2); // Add some padding
            $startX = 10;
            $currentY = $this->GetY();

            $this->SetX($startX);
            if ($this->REFFBY !== 'Cash Sales') {
                $this->SetFont('Arial', 'B', $FooterFontSize);
                $this->Cell(30, 5, 'Referred By: ', 0, 0, 'L');
                $this->SetFont('Arial', '', $FooterFontSize);
                $this->Cell(30, 5, $this->REFFBY, 0, 'L');
            } else {
                $this->Cell(30, 5, '');
            }
            $this->SetX($startX);
            $this->SetFont('Arial', 'B', $FooterFontSize);
            $this->Cell(30, 5, 'Patient: ', 0, 0, 'L');
            $this->SetFont('Arial', '', $FooterFontSize);
            $this->Cell(30, 5, $this->PATIENTNAME, 0, 'L');
            $this->SetX($startX);
            $this->SetFont('Arial', 'B', $FooterFontSize);
            $this->Cell(30, 5, 'Age: ', 0, 0, 'L');
            $this->SetFont('Arial', '', $FooterFontSize);
            $this->Cell(30, 5, $this->PATIENTAGE, 0, 1, 'L');
            $this->SetX($startX);
            $this->SetFont('Arial', 'B', $FooterFontSize);
            $this->Cell(30, 5, 'Contact: ', 0, 0, 'L');
            $this->SetFont('Arial', '', $FooterFontSize);
            $this->Cell(30, 5, $this->PATIENTPHNO, 0, 1, 'L');

            // GST Calculation
            $this->SetY(149); // Reset Y position
            $this->SetX(98); // Align to the right
            // Draw vertical line
            $this->SetDrawColor(108, 117, 125);
            $this->Line($this->GetX(), $this->GetY(), $this->GetX(), $this->GetY() + 19);

            $startX = 70;
            $this->SetY($currentY); // Reset Y position to top of the section
            $this->SetX($startX);
            $this->Cell(70, 5, 'CGST :', 0, 0, 'C');
            $this->Cell(-10, 5, ' ' . ($this->TOTALGST / 2), 0, 1, 'C');
            $this->SetX($startX);
            $this->Cell(70, 5, 'SGST :', 0, 0, 'C');
            $this->Cell(-10, 5, ' ' . ($this->TOTALGST / 2), 0, 1, 'C');
            $this->SetX($startX);
            $this->Cell(75, 5, 'Total GST :', 0, 0, 'C');
            $this->Cell(-21, 5, ' ' . $this->TOTALGST, 0, 1, 'C');

            // Amount Calculation
            $startX = 140;
            $this->SetY($currentY); // Reset Y position to top of the section
            $this->SetX($startX);
            $this->Cell(20, 5, 'MRP :', 0, 0, 'R');
            $this->Cell(40, 5, ' ' . $this->TOTALMRP, 0, 1, 'R');

            $savedName = $this->TOTALMRP - $this->BILLAMOUT > 0 ? 'You Saved  :' : '';
            $savedAmount = $this->TOTALMRP - $this->BILLAMOUT > 0 ? $this->TOTALMRP - $this->BILLAMOUT : '';
            
            $this->SetX($startX);
            $this->SetFont('Arial', '', $FooterFontSize);
            $this->Cell(28, 5, $savedName, 0, 0, 'R');
            $this->Cell(28, 5, $savedAmount, 0, 1, 'R');
            
            $this->SetX($startX);
            $this->SetFont('Arial', 'B', $FooterFontSize);
            $this->Cell(25, 5, 'Payable :', 0, 0, 'R');
            $this->Cell(34, 5, ' ' . $this->BILLAMOUT, 0, 1, 'R');

            $this->Ln(3);
            $this->SetDrawColor(108, 117, 125);
            $this->Line(10, $this->GetY(), 200, $this->GetY());
            $this->Ln(2.5);

            // Address
            $this->SetFont('Arial', '', $FooterFontSize);
            $address = "Contact: " . $this->healthCarePhno . ", " . $this->healthCareApntbkNo ."  Print Time: ".TODAY;
            $this->cell(0, 0, $address, 0, 'L');
        }
    }
}
