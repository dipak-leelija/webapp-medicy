<?php
require_once dirname(__DIR__) . '/config/constant.php';
require_once ROOT_DIR . '_config/sessionCheck.php'; // Check if admin is logged in

require_once CLASS_DIR . 'dbconnect.php';
require_once CLASS_DIR . 'doctor.category.class.php';

$DoctorCategory = new DoctorCategory();

print_r($_GET);

if (isset($_GET['match'])) {

    echo $match = htmlspecialchars($_GET['match']);

    $searchResult = json_decode($DoctorCategory->showDoctorCategoryByLikeWise($match));

    if ($searchResult->status) {
        $data = $searchResult->data;

        foreach ($data as $data) {
            $id = htmlspecialchars($data->doctor_category_id);
            $name = htmlspecialchars($data->category_name);

            echo "<div class='p-1 border-bottom list' id='$id' onclick='setDocSpecialization(this)'>$name</div>";
        }
        echo '<div class="d-flex flex-column justify-content-center" onclick="addDocSpecialization()">
        <button type="button" id="add-specialization" class="text-primary border-0">
            <i class="fas fa-plus-circle"></i>Add Now</button>
    </div>';
    } else {
        echo "<p class='text-center font-weight-bold'>Data Not Found!</p>";
        echo '<div class="d-flex flex-column justify-content-center" onclick="addDocSpecialization()">
        <button type="button" id="add-specialization" class="text-primary border-0">
            <i class="fas fa-plus-circle"></i>Add Now</button>
    </div>';
    }
}
