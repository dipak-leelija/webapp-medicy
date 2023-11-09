<?php
$includePath = get_include_path();

require_once CLASS_DIR.'hospital.class.php';

$HealthCare        = new HelthCare();
$healthCareDetailsPrimary = $HealthCare->showhelthCarePrimary();

$healthCareDetailsByAdminId = $HealthCare->showhelthCare($adminId);

if($healthCareDetailsByAdminId != null){
    $healthCareDetails = $healthCareDetailsByAdminId;

    if ($healthCareDetails['hospital_name'] == null) {
        $clinicName = $healthCareDetailsPrimary['hospital_name'];
    }else {
        $clinicName = $healthCareDetails[2];
    }
}else{
    $healthCareDetails = $healthCareDetailsPrimary;
}

?>
<footer class="sticky-footer ">
    <div class="container my-auto">
        <div class="copyright text-center my-auto">
            <span>All Rights Resarved &copy; <?= $clinicName .' '. date('Y'); ?></span>
        </div>
    </div>
</footer>