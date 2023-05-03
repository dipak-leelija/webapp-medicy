<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Filter</title>
    <link href="../../assets/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link href="../css/sb-admin-2.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../vendor/product-table/dataTables.bootstrap4.css">
</head>

<body>

    <?php
require_once "../../php_control/stockReturn.class.php";


$StockReturn    = new StockReturn();

$today = date("Y-m-d");
$value1 = date("Y-m-d");
$value2 = date("Y-m-d");

if ($_GET['table'] !== null && $_GET['value'] !== null) {

    // print_r($_GET['table']); echo "<br>";
    // print_r($_GET['value']); echo "<br>";

    $table = ($_GET['table']);
    $value = ($_GET['value']); 

    // echo $table;
    // echo $value;

    // if($table == 'added_by' || $table == 'distributor_id' || $table == 'refund_mode' ){
        
    //     $data1 = $StockReturn->stockReturnFilter($table, $value);
    //     $data = $data1;
    // }elseif($table == 'added_on'){
    //     if($value == 'T'){
    //         $fromDate = date("Y-m-d");
    //         $toDate = date("Y-m-d");
    //     }elseif($value == 'Y'){
    //         $fromDate = date("Y-m-d", strtotime("yesterday"));
    //         $toDate = date("Y-m-d", strtotime("yesterday"));
    //     }elseif($value == 'LW'){
    //         $fromDate = date("Y-m-d", strtotime("-7 days"));
    //         $toDate = date("Y-m-d");
    //     }elseif($value == 'LM'){
    //         $fromDate = date("Y-m-d", strtotime("-30 days"));
    //         $toDate = date("Y-m-d");
    //     }elseif($value == 'LQ'){
    //         $fromDate = date("Y-m-d", strtotime("-90 days"));
    //         $toDate = date("Y-m-d");
    //     }
    //     elseif($value == 'CFY'){
    //         $fromDate = date("Y-01-01", strtotime("-90 days"));
    //         $toDate = date("Y-m-d");
    //     }elseif($value == 'PFY'){
    //         $year = date("Y")-1;
    //         $fromDate = date("$year-01-01");
    //         $toDate = date("$year-12-31");
    //     }
    //     // elseif($value == 'CR'){
    //     //     if ($_GET['fromDate'] != null && $_GET['toDate'] != null){
    //     //         $fromDate = $_GET['fromDate'];
    //     //         $toDate = $_GET['toDate'];
    //     //     } 
    //     // }
    
    //     // echo $fromDate;
    //     // echo $toDate;
    //     $data2 = $StockReturn->stockReturnFilterbyDate($table, $fromDate, $toDate);
    //     $data = $data2;

        $data = $StockReturn->stockReturnFilter($_GET['table'], $_GET['value']);
        print_r($data);
    }

    ?>
    <table class="table table-sm table-hover" id="dataTable" width="100%"
        cellspacing="0">
        <thead class="bg-primary text-light">
            <tr>
                <th>Return Id</th>
                <th>Distributor</th>
                <th>Return Date</th>
                <th>Entry Date</th>
                <th>Entry By</th>
                <th>Payment Mode</th>
                <th>Amount</th>
            </tr>
        </thead>
        <tbody>
            <?php
        
if (count($data) >0) {
    foreach ($data as $row) {
    echo '<tr>
    <td>'.$row['id'].'</td>
    <td>'.$row['distributor_id'].'</td>
    <td>'.$row['return_date'].'</td>
    <td>'.$row['added_on'].'</td>
    <td>'.$row['added_by'].'</td>
    <td>'.$row['refund_mode'].'</td>
    <td>'.$row['refund_amount'].'</td>
</tr>';    
}
}else{
    echo '<tr>
            <td>No Data</td>
         </tr>';
}

        ?>
        </tbody>
    </table>
    <?php
// }


?>


    <!-- Bootstrap core JavaScript-->
    <script src="../../assets/jquery/jquery.min.js"></script>
    <script src="../../js/bootstrap-js-4/bootstrap.bundle.min.js"></script>

    <!-- Custom scripts for all pages-->
    <script src="../js/sb-admin-2.min.js"></script>

    <script src="../vendor/product-table/jquery.dataTables.js"></script>
    <script src="../vendor/product-table/dataTables.bootstrap4.js"></script>

    <!-- Core plugin JavaScript-->
    <script src="../../assets/jquery-easing/jquery.easing.min.js"></script>

    <!-- Page level custom scripts -->
    <script src="../js/demo/datatables-demo.js"></script>

</body>

</html>