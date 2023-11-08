<?php
require_once dirname(dirname(__DIR__)) . '/config/constant.php';

$includePath = get_include_path();

require_once CLASS_DIR.'hospital.class.php';

$HealthCare        = new HelthCare();
$healthCareDetailsPrimary = $HealthCare->showhelthCarePrimary();
$healthCareDetailsByAdminId = $HealthCare->showhelthCare($adminId);

if($healthCareDetailsByAdminId != null){
    $healthCareDetails = $healthCareDetailsByAdminId;
}else{
    $healthCareDetails = $healthCareDetailsPrimary;
}

?>
<footer class="sticky-footer ">
    <div class="container my-auto">
        <div class="copyright text-center my-auto">
            <span>All Rights Resarved &copy; <?Php echo $healthCareDetails[0][2].' 2021'; ?></span>
        </div>
    </div>
</footer>