<?php

class UtilityFiles extends DatabaseConnection{
    
    function purchaseImport($fileName, $ADMINID) {
        try {
            // Open the file
            $file = fopen($fileName, "r");
            if ($file === FALSE) {
                throw new Exception("Failed to open file.");
            }
    
            // Get the table header
            $tableHeader = fgetcsv($file, 10000, ",");
            if ($tableHeader === FALSE) {
                throw new Exception("Failed to read table header.");
            }
    
            // Check if "Bill No" is present in the required columns
            if (!in_array('Bill No', $tableHeader)) {
                throw new Exception("Bill No column is missing.");
            }
    
            // Check if required columns exist
            $requiredColumns = ['Bill No', 'Bill Date', 'Payment Status', 'Distributor', 'Taxable', 'SGST', 'CGST', 'Entry Date', 'Total'];
            $columnIndices = array_map(function($col) use ($tableHeader) {
                return array_search($col, $tableHeader);
            }, $requiredColumns);
    
            if (in_array(FALSE, $columnIndices, TRUE)) {
                throw new Exception("Required columns are missing.");
            }
    
            // Start building the table
            // echo "<table><tr>";
            // foreach ($requiredColumns as $header) {
            //     echo "<th>$header</th>";
            // }
            // echo "<th>Amount</th></tr>";
    
            // Read the rest of the file
            while (($getData = fgetcsv($file, 10000, ",")) !== FALSE) {
                // Check if the "Bill No" column is not blank or only consists of white spaces
                if (trim($getData[$columnIndices[array_search('Bill No', $requiredColumns)]]) === '') {
                    continue; // Skip this line and continue to the next one
                }
    
                // Check if the row has sufficient data
                if (count($getData) >= count($requiredColumns)) {
                    // Replace distributor data with its id
                    $distributorName = $getData[$columnIndices[array_search('Distributor', $requiredColumns)]];
    
                    $select1 = "SELECT id FROM `distributor` WHERE `distributor`.`name`= '$distributorName'";
                    $selectQuery1 = $this->conn->query($select1);
    
                    // Check if the query was successful
                    if ($selectQuery1) {
                        // Fetch the result as an object
                        $result = $selectQuery1->fetch_object();
    
                        // Check if a result was found
                        if ($result) {
                            $getData[$columnIndices[array_search('Distributor', $requiredColumns)]] = $result->id;
                        } else {
                            echo "No result found.";
                        }
                    } else {
                        echo "Error executing query: " . $this->conn->error;
                    }
    
                    // Display row
                    // echo "<tr>";
                    // foreach ($columnIndices as $index) {
                    //     echo "<td>{$getData[$index]}</td>";
                    // }
                    
                    $billNo         = preg_replace('/^[^a-zA-Z0-9]*/', '', $getData[$columnIndices[array_search('Bill No', $requiredColumns)]]);
                    $billDate       = $getData[$columnIndices[array_search('Bill Date', $requiredColumns)]];
                    $paymentMode    = $getData[$columnIndices[array_search('Payment Status', $requiredColumns)]];
                    $distributor    = $getData[$columnIndices[array_search('Distributor', $requiredColumns)]];
                    $taxable        = $getData[$columnIndices[array_search('Taxable', $requiredColumns)]];
                    $SGST           = $getData[$columnIndices[array_search('SGST', $requiredColumns)]];
                    $CGST           = $getData[$columnIndices[array_search('CGST', $requiredColumns)]];
                    $entryDate      = $getData[$columnIndices[array_search('Entry Date', $requiredColumns)]];
                    $totalAmount    = $getData[$columnIndices[array_search('Total', $requiredColumns)]];

                    $GST = $SGST+$CGST;
                    
                    $items = 0;
                    $totalQty = 0;

                    $addStockIn = "INSERT INTO `stock_in` (`distributor_id`, `distributor_bill`, `items`, `total_qty`, `bill_date`, `due_date`, `payment_mode`, `gst`, `amount`, `added_by`, `added_on`, `admin_id`) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

                    $responce =  $this->conn->prepare($addStockIn);
        
                    // binding parameters --------
                    $responce->bind_param("isisssssssss", $distributor, $billNo, $items, $totalQty, $billDate, $billDate, $paymentMode, $GST, $totalAmount, $ADMINID, $entryDate, $ADMINID);
        
                    // Execute the prepared statement
                    if ($responce->execute()) {
                        // Get the ID of the newly inserted record
                        $addStockInId = $this->conn->insert_id;
                    } else {
                        // Handle the error (e.g., log or return an error message)
                        throw new Exception("Error executing SQL statement: " . $responce->error);
                    }


                    // echo "<td>{$billNo}</td>";
                    // echo "<td>{$billDate}</td>";
                    // echo "<td>{$paymentMode}</td>";
                    // echo "<td>{$distributor}</td>";
                    // echo "<td>{$taxable}</td>";
                    // echo "<td>{$SGST}</td>";
                    // echo "<td>{$SGST}</td>";
                    // echo "<td>{$entryDate}</td>";
                    // echo "<td>{$totalAmount}</td>";

                    // // Calculate and display amount
                    // $amount = $SGST +$SGST +$taxable;
                    // echo "<td>{$amount}</td>";
                    // echo "</tr>";
                }
            }
    
            // Close table
            echo "</table>";
    
            // Close file
            fclose($file);
        } catch (Exception $e) {
            echo $e->getMessage();
        }
    }

    
}

?>