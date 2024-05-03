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
    
    if (isset($_POST['form_20'], $_POST['form_21'], $_POST['gstin'], $_POST['pan'])) {
        
        $form20Base64 = $_POST['form_20'];
        $form21Base64 = $_POST['form_21'];
        $gstin = $_POST['gstin'];
        $pan = $_POST['pan'];

        // DESTINATION FORLDER
        $uploadFolder = ROOT_DIR . "assets/images/orgs/drug-permit/";

        $validExtensions = ['pdf', 'jpeg', 'jpg', 'png'];

        function getFileExtension($data)
        {
            $imageData = explode(',', $data);
            $extension = '';

            if (preg_match('/^data:image\/(\w+);base64/', $imageData[0], $match)) {
                $extension = $match[1];
            } elseif (preg_match('/^data:(\w+\/\w+);base64/', $imageData[0], $match)) {
                $extension = explode('/', $match[1])[1];
            }

            return $extension;
        }

        // DECODE FILE DATA
        $form20Extension = getFileExtension($form20Base64);
        $form21Extension = getFileExtension($form21Base64);

        if (!in_array($form20Extension, $validExtensions) || !in_array($form21Extension, $validExtensions)) {
            echo "Invalid file format. Allowed formats: pdf, jpeg, jpg, png";
            exit;
        }

        // Decode base64 data
        $decodedForm20 = base64_decode($form20Base64);
        $decodedForm21 = base64_decode($form21Base64);

        // Generate unique filenames
        $form20FileName = uniqid() . ".$form20Extension";
        $form21FileName = uniqid() . ".$form21Extension";

        // PATH TO SAVE FILE
        $form20Path = $uploadFolder . $form20FileName;
        $form21Path = $uploadFolder . $form21FileName;

        // Save decoded files
        file_put_contents($form20Path, $decodedForm20);
        file_put_contents($form21Path, $decodedForm21);

        // Assuming $HealthCare and $adminId are defined elsewhere
        $uplodClinicData = $HealthCare->updateDrugPermissionData($form20FileName, $form21FileName, $gstin, $pan, $adminId);

        if ($uplodClinicData) {
            echo "Data updated successfully.";
        } else {
            echo "Failed to update data.";
        }
    } else {
        echo "Required fields not set.";
    }
} else {
    echo "Form not submitted.";
}
