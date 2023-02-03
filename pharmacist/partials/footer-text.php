<?php
require_once '../php_control/hospital.class.php';

$HealthCare        = new HelthCare();
$healthCareDetails = $HealthCare->showhelthCare();
// print_r();

?>
<footer class="sticky-footer ">
    <div class="container my-auto">
        <div class="copyright text-center my-auto">
            <span>All Rights Resarved &copy; <?Php echo $healthCareDetails[0][2].' 2021'; ?></span>
        </div>
    </div>
</footer>