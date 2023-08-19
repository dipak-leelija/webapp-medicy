<?php
require_once '../../php_control/salesReturn.class.php';
require_once '../../php_control/patients.class.php';
require_once '../../php_control/products.class.php';
require_once '../../php_control/stockOut.class.php';


// classes initiating 
$SalesReturn    = new SalesReturn();
$Patients       = new Patients();
$products       = new Products();
$StockOut       = new StockOut();

if($_SERVER['REQUEST_METHOD'] == 'GET'){
    if (isset($_GET['invoice'])) {

        $SalesReturnid = $_GET['id'];
        $invoiceID = $_GET['invoice'];
        // echo "Sales return id : ",$SalesReturnid,"<br>Invoice id : ",$invoiceID;
        $returnBill = $SalesReturn->salesReturnByID($SalesReturnid , $invoiceID);
        // echo "<br>";
        // print_r($returnBill); echo "<br><br>";

        $patientId = $returnBill[0]['patient_id'];

        if($patientId == "Cash Sales"){
            $patientName = "Cash Sales";
        }else{
            $patientName = $returnBill[0]['patient_id'];
        }
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>

<style>
    .summary{
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

<body>
    <div class="container">
        <div class="row">
            <div class="col-12 col-sm-6 col-md-3">
                <p><b>Invoice No:</b> <span>#<?php echo $returnBill[0]['invoice_id']; ?></span></p>
            </div>
            <div class="col-12 col-sm-6 col-md-3">
                <?php 
                    if($patientId == "Cash Sales"){
                        $patientName = "Cash Sales";
                    }else{
                        $patientId = $returnBill[0]['patient_id'];
                        $patient = $Patients->patientsDisplayByPId($patientId);
                        $patientName = $patient[0]['name'];
                    }
                
                ?>
                <p><b>Patient Name:</b> <?php echo $patientName; ?></p>
            </div>
            <div class="col-12 col-sm-6 col-md-3">
                <p><b>Return Date:</b> <span><?php echo $returnBill[0]['return_date']; ?></span></p>
            </div>
            <div class="col-12 col-sm-6 col-md-3">
                <p><b>Refund Mode:</b> <span><?php echo $returnBill[0]['refund_mode']; ?></span></p>
            </div>
        </div>
        <hr>
        <div class="table-responsive" style="min-height: 30vh;">
            <table class="table table-sm text-dark">
                <thead class="bg-primary text-light">
                    <tr>
                        <th>Invoice</th>
                        <th>Item</th>
                        <th>Batch</th>
                        <th>Exp Date</th>
                        <th>Weatage</th>
                        <th>Qty</th>
                        <th>Disc</th>
                        <th>GST</th>
                        <th>Amount</th>
                        <th>Return</th>
                        <th>Refund</th>
                    </tr>
                </thead>
                <tbody>

                    <?php
                    $attribute = 'sales_return_id';
                    $billList = $SalesReturn->selectSalesReturnList($attribute, $SalesReturnid);
                    print_r($billList);
                    echo "<br><br>";
                    foreach ($billList as $bill) {

                        $invoice    = $invoiceID;
                        $attribute = "invoice_id";
                        $invoicDetials = $StockOut->invoiceDetialsByTableData($attribute, $invoice);
                        print_r($invoicDetials); echo "<br><br>";

                        foreach($invoicDetials as $invoiceData){
                            $productId = $invoiceData['product_id'];
                        }

                        $productName = $products->showProductsById($productId);
                        $ItemName = $productName[0]['name']; 
                        
                        

                    echo '<tr>
                            <td>'.$bill['invoice_id'].'</td>
                            <td>'.$ItemName.'</td>
                            <td>'.$bill['batch_no'].'</td>
                            <td>'.$bill['weatage'].'</td>
                            <td>'.$bill['exp'].'</td>
                            <td>'.$pQty.'</td>
                            <td>'.$bill['disc'].'</td>
                            <td>'.$bill['gst'].'</td>
                            <td>'.$bill['amount'].'</td>
                            <td>'.$return.'</td>
                            <td>'.$bill['refund'].'</td>

                        </tr>';
                }
                ?>
                </tbody>
            </table>
        </div>

        <div class="row p-2 p-md-0 summary rounded align-middle">
            <div class="col-6 col-sm-3">Items: <?php echo $returnBill[0]['items']; ?></div>
            <div class="col-6 col-sm-3">Quantity: <?php echo $returnBill[0]['items']; ?></div>
            <div class="col-6 col-sm-3">GST: <?php echo $returnBill[0]['gst_amount']; ?></div>
            <div class="col-6 col-sm-3">Amount: <?php echo $returnBill[0]['refund_amount']; ?></div>

        </div>

    </div>
</body>

</html>