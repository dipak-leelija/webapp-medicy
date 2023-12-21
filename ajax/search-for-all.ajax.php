<?php
require_once dirname(__DIR__) . '/config/constant.php';
require_once ROOT_DIR . '_config/sessionCheck.php'; //check admin loggedin or not

require_once CLASS_DIR . 'dbconnect.php';
require_once CLASS_DIR . 'searchForAll.class.php';

$SearchForAll = new SearchForAll;

// === sod fixd date data fetch =======
if (isset($_GET['searchKey'])) {

    $searchFor = $_GET['searchKey'];

    if (!is_numeric($searchFor)) {

        $substring = substr($searchFor, 0, 2);

        if ($substring == 'me' || $substring == 'ME' || $substring == 'mE' || $substring == 'Me') {

            $appointmentsResult = json_decode($SearchForAll->searchAllFilterForAppointment($searchFor, $adminId));

            if ($appointmentsResult->status) {
                $keyToken = 'appointments';
                $status = $appointmentsResult->status;
                $data = $appointmentsResult->data;
            } else {
                $keyToken = '';
                $status = $appointmentsResult->status;
                $data = $appointmentsResult->message;
            }
        } 
        
        if ($substring == 'pe' || $substring == 'PE' || $substring == 'pE' || $substring == 'Pe') {
           
            $patientsResult = json_decode($SearchForAll->searchAllFilterForPatient($searchFor, $adminId));
            if ($patientsResult->status) {
                $keyToken = 'patients';
                $status = $patientsResult->status;
                $data = $patientsResult->data;
            } else {
                $keyToken = '';
                $status = $patientsResult->status;
                $data = $patientsResult->message;
            }
        }

    } else {
        $stockIn = json_decode($SearchForAll->searchAllFilterForStockIn($searchFor, $adminId));
        // print_r($stockIn);
        if ($stockIn->status) {
            $keyToken = 'stock_in';
            $status = $stockIn->status;
            $data = $stockIn->data;
        } else {
            $keyToken = '';
            $status = $stockIn->status;
            $data = $stockIn->message;
        }
    }


    $resultData = ['token' => $keyToken, 'status' => $status, 'data' => $data];

    // print_r($resultData);

    if ($resultData['status']) {

        if ($resultData['token'] == 'appointments') {
            echo '
            <div class="row border-bottom border-primary small mx-0 mb-2">
                <div class="col-md-6">Appointment Id</div>
                <div class="col-md-3">Patient Id</div>
                <div class="col-md-3">Contact</div>
            </div>';

            foreach ($resultData['data'] as $result) {
                echo '<div class="row mx-0 py-2 border-bottom p-row item-list" id="search-all-listed-items" tabindex="0" onclick="getDtls(' . $resultData["token"] . ',' . $result->appointment_id . ');">
                <div class="col-md-6">' . $result->appointment_id . '</div>
                <div class="col-md-3"><small>' . $result->patient_id . '</small></div>
                <div class="col-md-3"><small>' . $result->patient_phno . '</small></div>
            </div>';
            }
        } elseif ($resultData['token'] == 'patients') {
            echo '
            <div class="row border-bottom border-primary small mx-0 mb-2">
                <div class="col-md-5">Patient Id</div>
                <div class="col-md-4">Patient Name</div>
                <div class="col-md-3">Contact</div>
            </div>';
            foreach ($resultData['data'] as $result) {
                echo '<div class="row mx-0 py-2 border-bottom p-row item-list" id="search-all-listed-items" tabindex="0" onclick="getDtls(' . $resultData['token'] . ',' . $result->patient_id . ');">
                <div class="col-md-5">' . $result->patient_id . '</div>
                <div class="col-md-4
                "><small>' . $result->name . '</small></div>
                <div class="col-md-3"><small>' . $result->phno . '</small></div>
            </div>';
            }
        } elseif ($resultData['token'] == 'stock_in') {
            foreach ($resultData['data'] as $resultData) {
                echo "$resultData->appointment_id ";
            }
        } else {
            echo "no data found!";
        }
    } else {
        echo "no data found";
    }
}
