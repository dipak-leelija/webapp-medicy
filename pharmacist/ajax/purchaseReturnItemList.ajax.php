<?php
require_once "../../php_control/stockReturn.class.php";
require_once "../../php_control/distributor.class.php";
require_once "../../php_control/products.class.php";

$PurchaseReturn = new StockReturn();
$DistributorDetils = new Distributor();
$Product = new Products();

// getBatchList function for geting bill date
if (isset($_GET['return-id'])) {
    $returnId = $_GET['return-id'];
    // echo $returnId;
    $bill =  $PurchaseReturn->showStockReturnById($returnId);
    // print_r($bill);
    $dist = $DistributorDetils->showDistributorById($bill[0]["distributor_id"]);

?>

    <!DOCTYPE html>
    <html lang="en">

    <head>
        <!-- Custom fonts for this template-->
        <link href="../vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
        <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">

        <link rel="stylesheet" href="../../css/bootstrap 5/bootstrap.css">
        <style>
            .container-fluid {
                display: flex;
                flex-direction: column;
                min-height: 50vh;
            }

            .summary {
                margin-top: auto;
                min-height: 3rem;
                background: #af3636;
                align-items: center;
                color: #fff;
                font-size: 0.9rem;
                font-weight: 600;
            }
        </style>
    </head>

    <body class="mx-0">

        <!-- start container-fluid -->
        <div class="container-fluid">

            <div class="row">
                <div class="col-3 col-sm-3">
                    <p><b> Distribubtor: </b><?php echo $dist[0]['name']; ?></p>
                </div>
                <div class="col-3 col-sm-3">
                    <p><b> Return Bill No: </b>#<?php echo $bill[0]['id']; ?></p>
                </div>
                <div class="col-3 col-sm-3">
                    <p><b> Return Date: </b><?php echo date("d-m-Y", strtotime($bill[0]["return_date"])); ?></p>
                </div>
                <div class="col-3 col-sm-3">
                    <p><b> Payment Mode: </b><?php echo $bill[0]['refund_mode']; ?></p>
                </div>
            </div>
            <div class="table-responsive mb-3">

                <table class="table table-sm table-hover" style="font-size:0.9rem;">
                    <thead class="bg-primary text-light">
                        <tr>
                            <th>SL.</th>
                            <th>Item Name</th>
                            <th>Batch</th>
                            <th>Exp.</th>
                            <th>Weatage</th>
                            <th>P.Qty</th>
                            <th>Free Qty</th>
                            <th>PTR</th>
                            <th>MRP</th>
                            <th>GST</th>
                            <th>PTR.Amount</th>
                            <th>Return Qty</th>
                            <th>Refund</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $sl = 0;
                        $qty = 0;
                        $gst = 0;
                        $amount = 0;

                        $items = $PurchaseReturn->showStockReturnDetails($returnId);
                        // print_r($items);
                        // echo "<br><br>";
                        foreach ($items as $item) {
                            $sl     += 1;
                            $qty    += $item['return_qty'];
                            $gst    += $item['gst'];
                            $amount += $item['ptr'];

                            $productData = $Product->showProductsById($item['product_id']);
                            // print_r($productData);
                            foreach($productData as $pData){
                                $name = $pData['name'];
                            }

                            echo "<tr>
                            <th scope='row'>" . $sl . "</th>
                            <td>" . $name . "</td>
                            <td>" . $item['batch_no'] . "</td>
                            <td>" . $item['exp_date'] . "</td>
                            <td>" . $item['unit'] . "</td>
                            <td>" . $item['purchase_qty'] . "</td>
                            <td>" . $item['free_qty'] . "</td>
                            <td>" . $item['ptr'] . "</td>
                            <td>" . $item['mrp'] . "</td>
                            <td>" . $item['gst'] . "%</td>
                            <td>" . $item['purchase_amount'] . "</td>
                            <td>" . $item['return_qty'] . "</td>
                            <td>" . $item['refund_amount'] . "</td>
                          </tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>

            <div class="row summary rounded align-middle">
                <div class="col-6 col-sm-3">Items: <?php echo count($items); ?></div>
                <div class="col-6 col-sm-3">Quantity: <?php echo count($items) ?></div>
                <div class="col-6 col-sm-3">GST: <?php echo $bill[0]['gst_amount']; ?></div>
                <div class="col-6 col-sm-3">Amount: <?php echo $bill[0]['refund_amount']; ?></div>

            </div>

        </div>
        <!-- end container-fluid -->


        <!-- Bootstrap Js -->
        <script src="../../js/bootstrap-js-5/bootstrap.js"></script>
    </body>

    </html>

<?php
}
?>