<?php
require_once CLASS_DIR.'hospital.class.php';

$HealthCare        = new HelthCare();
$healthCareDetails = $HealthCare->showhelthCarePrimary();
// print_r($healthCareDetails);

?>
<footer class="sticky-footer ">
    <div class="container my-auto">
        <div class="copyright text-center my-auto">
            <span>All Rights Resarved &copy; <?Php echo $healthCareDetails[0][2].' 2021'; ?></span>
        </div>
    </div>
</footer>