<?php
require_once dirname(dirname(__DIR__)) . '/config/constant.php';
require_once ROOT_DIR . '_config/sessionCheck.php'; //check admin loggedin or not

require_once CLASS_DIR . 'dbconnect.php';
require_once ROOT_DIR . '_config/healthcare.inc.php';
require_once CLASS_DIR . 'subscription.class.php';
require_once CLASS_DIR . 'utility.class.php';

$Subscription   = new Subscription;
$Utility        = new Utility;


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_FILES['form_20'], $_FILES['form_21'], $_POST['gstin'], $_POST['pan'])) {
        
        $form20File = $_FILES['form_20'];
        $form21File = $_FILES['form_21'];
        $gstin = $_POST['gstin'];
        $pan = $_POST['pan'];

        // DESTINATION FOLDER
        $uploadFolder = ROOT_DIR . "assets/images/orgs/drug-permit/";

        $validExtensions = ['pdf', 'jpeg', 'jpg', 'png'];

        function getFileExtension($fileName)
        {
            $extension = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
            return $extension;
        }

        // FILE EXTENSIONS
        $form20Extension = getFileExtension($form20File['name']);
        $form21Extension = getFileExtension($form21File['name']);

        if (!in_array($form20Extension, $validExtensions) || !in_array($form21Extension, $validExtensions)) {
            echo "Invalid file format. Allowed formats: pdf, jpeg, jpg, png";
            exit;
        }

        // Generate unique filenames
        $form20FileName = uniqid() . ".$form20Extension";
        $form21FileName = uniqid() . ".$form21Extension";

        // PATH TO SAVE FILE
        $form20Path = $uploadFolder . $form20FileName;
        $form21Path = $uploadFolder . $form21FileName;

        // Move uploaded files to destination folder
        if (move_uploaded_file($form20File['tmp_name'], $form20Path) && move_uploaded_file($form21File['tmp_name'], $form21Path)) {

            // Assuming $HealthCare and $adminId are defined elsewhere
            $uplodClinicData = $HealthCare->updateDrugPermissionData($form20FileName, $form21FileName, $gstin, $pan, $adminId);

            if ($uplodClinicData) {
                echo "Data updated successfully. 1";
            } else {
                echo "Failed to update data. 0";
            }
        } else {
            echo "Failed to move uploaded files. 01";
        }
    } else {
        echo "Required fields not set. 10";
    }
} else {
    echo "Form not submitted. 00";
}

?>
