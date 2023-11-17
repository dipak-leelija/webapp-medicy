<?php
require_once CLASS_DIR.'hospital.class.php';

$HealthCare     = new HealthCare();

$healthCare     = $HealthCare->showHealthCare($adminId);

if(!empty($healthCare)){

    if (!empty($healthCare['hospital_name'])){
        $healthCareName = $healthCare['hospital_name'];
    }else {
        if (!str_contains(PAGE, 'clinic-setup')) {
            header('Location: '.URL.'clinic-setup.php?setup=Please complete your Organization/Healthcare setup!');
        }
    }

    $healthCareLogo      = $healthCare['logo'];
    $healthCareLogo      = empty($healthCareLogo) ? SITE_IMG_PATH.'logo-p.jpg' : URL.$healthCareLogo;

    $healthCareId        = $healthCare['hospital_id'];
    $healthCareName      = $healthCare['hospital_name'];
    $healthCareAddress1  = $healthCare['address_1'];
    $healthCareAddress2  = $healthCare['address_2'];
    $healthCareCity      = $healthCare['city'];
    $healthCareDist      = $healthCare['dist'];
    $healthCarePin       = $healthCare['pin'];
    $healthCareState     = $healthCare['health_care_state'];
    $healthCareEmail     = $healthCare['hospital_email'];
    $healthCarePhno      = $healthCare['hospital_phno'];
    $healthCareApntbkNo  = $healthCare['appointment_help_line'];
}