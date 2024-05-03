<?php

class UtilityFiles extends DatabaseConnection{

    function purchaseImport($fileName){

        $file = fopen($fileName, "r");

        // Get the first row as the table header
        $tableHeader = fgetcsv($file, 10000, ",");

        // Check if required columns exist
//         distributor_id
// id
// distributor_bill
// items
// total_qty
// bill_date
// due_date
// payment_mode
// gst
// amount
// added_by
// added_on
// updated_by
// updated_on
// admin_id

        if (in_array('Bill No', $tableHeader) && in_array('Entry Date', $tableHeader) && in_array('Total', $tableHeader)) {

            $billNoIndex = array_search('Bill No', $tableHeader);
            $dateIndex = array_search('Entry Date', $tableHeader);
            $bilDateIndex = array_search('Bill Date', $tableHeader);
            $totalIndex = array_search('Total', $tableHeader);


            echo "<table>";
            echo "<tr>";
                echo "<th>$tableHeader[$billNoIndex]</th>";
                echo "<th>$tableHeader[$dateIndex]</th>";
                echo "<th>$tableHeader[$bilDateIndex]</th>";
                echo "<th>$tableHeader[$totalIndex]</th>";

            echo "</tr>";

            // Read the rest of the file
            while (($getData = fgetcsv($file, 10000, ",")) !== FALSE) {
                // Check if the row has both bill_no and date
                if (count($getData) >= 2) {
                    $billNoIndex = array_search('Bill No', $tableHeader);
                    $dateIndex = array_search('Entry Date', $tableHeader);
                    $totalIndex = array_search('Total', $tableHeader);


                    echo "<tr>";
                    echo "<td>{$getData[$billNoIndex]}</td>";
                    echo "<td>{$getData[$dateIndex]}</td>";
                    echo "<td>{$getData[$bilDateIndex]}</td>";
                    echo "<td>{$getData[$totalIndex]}</td>";
                    echo "</tr>";
                }
            }

            echo "</table>";
        } else {
            echo "Required columns (bill_no and date) are missing.";
        }

        fclose($file);
    }

}

?>