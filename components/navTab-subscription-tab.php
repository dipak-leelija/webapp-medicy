<?php
require_once 'config/constant.php';
require_once ROOT_DIR . '_config/sessionCheck.php'; //check admin loggedin or not

require_once CLASS_DIR . 'dbconnect.php';
require_once ROOT_DIR . '_config/healthcare.inc.php';
require_once CLASS_DIR . 'subscription.class.php';
require_once CLASS_DIR . 'utility.class.php';

$Subscription   = new Subscription;
$Utility        = new Utility;

$currentUrl = $Utility->currentUrl();
// Healthcare Addesss and details

$bills = json_decode($Subscription->getSubscription($adminId));
if ($bills->status) {
    $allBills = $bills->data;
} else {
    $allBills = array();
}


?>

<!-- Page Wrapper -->
<div id="wrapper">

    <!-- New Section -->
    <div class="col">
        <div class="mt-4 mb-4">
            <div class="card-body">
                <div class="card">
                    <div class="card-body">
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th scope="col">Plan</th>
                                    <th scope="col">Started From</th>
                                    <th scope="col">Ended On</th>
                                    <th scope="col">Amount</th>
                                    <th scope="col">Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                foreach ($allBills as $eachBill) {

                                    // date type convertion
                                    $convertedStartDate = $Utility->convertDateFormat($eachBill->start);
                                    $convertedEndDate = $Utility->convertDateFormat($eachBill->end);
                                    
                                    echo "
                                            <tr>
                                                <th scope='row'>$eachBill->plan</th>
                                                <td>$convertedStartDate</td>
                                                <td>$convertedEndDate</td>
                                                <td>$eachBill->amount</td>
                                                <td>$eachBill->status</td>
                                            </tr>
                                            ";
                                } ?>

                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>


</div>

<!-- Scroll to Top Button-->
<a class="scroll-to-top rounded" href="#page-top">
    <i class="fas fa-angle-up"></i>
</a>


<script>
    function validateFileType() {
        var fileName = document.getElementById("img-uv-input").value;
        var idxDot = fileName.lastIndexOf(".") + 1;
        var extFile = fileName.substr(idxDot, fileName.length).toLowerCase();
        if (extFile == "jpg" || extFile == "jpeg" || extFile == "png") {
            document.getElementById("err-show").classList.add("d-none");
        } else {
            document.getElementById("err-show").classList.remove("d-none");
            // Show current image when error occurs
            document.querySelector('.img-uv-view').src = "<?= $healthCareLogo; ?>";
        }
    }



    // =====================================================

</script>