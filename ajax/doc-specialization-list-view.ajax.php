<?php
require_once dirname(__DIR__) . '/config/constant.php';
require_once ROOT_DIR . '_config/sessionCheck.php'; // Check if admin is logged in

require_once CLASS_DIR . 'dbconnect.php';
require_once CLASS_DIR . 'doctor.category.class.php';

$DoctorCategory = new DoctorCategory();

if (isset($_GET['match'])) {
    
    $match = htmlspecialchars($_GET['match']);

    $searchResult = json_decode($DoctorCategory->showDoctorCategoryByLikeWise($match, $adminId));
    print_r($searchResult);

    if ($searchResult->status) {
        $data = $searchResult->data;

        foreach ($data as $data) {
            $id = htmlspecialchars($data->doctor_category_id );
            $name = htmlspecialchars($data->category_name);

            echo "<div class='p-1 border-bottom list' id='$id' onclick='setDocSpecialization(this)'>$name</div>";
        }
    } else {
        echo "<p class='text-center font-weight-bold'>Distributor Not Found!</p>";
    }
}
?>
