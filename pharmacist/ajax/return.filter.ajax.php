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

if ($_GET['table'] !== null && $_GET['value'] !== null) {

    $data = $StockReturn->stockReturnFilter($_GET['table'], $_GET['value']);
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
}


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