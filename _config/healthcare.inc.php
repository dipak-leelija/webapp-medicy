<?php
require_once CLASS_DIR.'hospital.class.php';
require_once CLASS_DIR.'subscription.class.php';

$HealthCare     = new HealthCare;
$Subscription   = new Subscription;

$healthCare   = json_decode($HealthCare->showHealthCare($adminId));
// $SubsDetails  = json_decode($Subscription->getSubscription($adminId));
$checkSubscription = $Subscription->checkSubscription($adminId, NOW);

// print_r($checkSubscription);
// exit;
// ========================== CHECK SUBSCRIPTION ========================== 

if (!$checkSubscription){
    header("Location:".URL."premium/plans.php");
    exit;
}


// ========================== CLINIC SETUP ========================== 
if ($healthCare->status === 1 ) {
    $healthCare = $healthCare->data;

    if (!empty($healthCare->hospital_name)){
        $healthCareName = $healthCare->hospital_name;
    }else {
        if (!str_contains(PAGE, 'clinic-setup')) {
            header('Location: '.URL.'clinic-setup.php?setup=Please complete your Pharmacy/Healthcare setup!');
        }
    }

    $healthCareLogo      = $healthCare->logo;
    $healthCareLogo      = empty($healthCareLogo) ? SITE_IMG_PATH.'logo-p.png' : URL.$healthCareLogo;

    $healthCareId        = $healthCare->hospital_id;
    $healthCareName      = $healthCare->hospital_name;
    $healthCareAddress1  = $healthCare->address_1;
    $healthCareAddress2  = $healthCare->address_2;
    $healthCareCity      = $healthCare->city;
    $healthCareDist      = $healthCare->dist;
    $healthCarePin       = $healthCare->pin;
    $healthCareState     = $healthCare->health_care_state;
    $healthCareEmail     = $healthCare->hospital_email;
    $healthCarePhno      = $healthCare->hospital_phno;
    $healthCareApntbkNo  = $healthCare->appointment_help_line;
    
}else {
    if (!str_contains(PAGE, 'clinic-setup')) {
        header('Location: '.URL.'clinic-setup.php?setup=Please complete your Pharmacy/Healthcare setup!');
    }
}