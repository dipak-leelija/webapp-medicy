<?php
require_once dirname(__DIR__) . '/config/constant.php';
require_once ROOT_DIR . '_config/sessionCheck.php'; //check admin loggedin or not

require_once CLASS_DIR . 'dbconnect.php';
require_once CLASS_DIR . 'searchForAll.class.php';

$SearchForAll = new SearchForAll;

// === sod fixd date data fetch =======
if (isset($_GET['searchKey'])) {

    $searchFor = $_GET['searchKey'];

    // echo $searchFor;
    // if (!is_numeric($searchFor)) {

    // if ($searchFor != null){

        // $substring = substr($searchFor, 0, 2);

        // if ($substring == 'me' || $substring == 'ME' || $substring == 'mE' || $substring == 'Me') {

        $appointmentsResult = json_decode($SearchForAll->searchAllFilterForAppointment($searchFor, $adminId));

        if ($appointmentsResult->status) {
            $appointmentsTable = 'appointments';
            $appointmentsStatus = $appointmentsResult->status;
            $appointmentsData = $appointmentsResult->data;
        } else {
            $appointmentsTable = 'appointments';
            $appointmentsStatus = $appointmentsResult->status;
            $appointmentsData = $appointmentsResult->message;
        }

        // } 
        
        // if ($substring == 'pe' || $substring == 'PE' || $substring == 'pE' || $substring == 'Pe') {
           
        $patientsResult = json_decode($SearchForAll->searchAllFilterForPatient($searchFor, $adminId));
        if ($patientsResult->status) {
            $patientsTable = 'patient_details';
            $patientsStatus = $patientsResult->status;
            $patientsData = $patientsResult->data;
        } else {
            $patientsTable = 'patient_details';
            $patientsStatus = $patientsResult->status;
            $patientsData = $patientsResult->message;
        }

        // }

    // } else {
        // echo $searchFor;
        $stockIn = json_decode($SearchForAll->searchAllFilterForStockIn($searchFor, $adminId));
        // print_r($stockIn);
        if ($stockIn->status) {
            $stockInTable = 'stock_in';
            $stockInStatus = $stockIn->status;
            $stockInData = $stockIn->data;
        } else {
            $stockInTable = '';
            $stockInStatus = $stockIn->status;
            $stockInData = $stockIn->message;
        }




        $salesData = json_decode($SearchForAll->searchAllFilterForStockOut($searchFor, $adminId));
        if ($salesData->status) {
            $stockOutTable = 'stock_out';
            $stockOutStatus = $salesData->status;
            $stockOutData = $salesData->data;
        } else {
            $stockOutTable = '';
            $stockOutStatus = $salesData->status;
            $stockOutData = $salesData->message;
        }



        $labBillData = json_decode($SearchForAll->searchAllFilterForLabdata($searchFor, $adminId));
        // print_r($labBillData);
        if ($labBillData->status) {
            $labBillTable = 'lab_billing';
            $labBillStatus = $labBillData->status;
            $labBillData = $labBillData->data;
        } else {
            $labBillTable = '';
            $labBillStatus = $labBillData->status;
            $labBillData = $labBillData->message;
        }

    // }
    if($appointmentsResult->status == 0 && $patientsResult->status && $stockIn->status && $labBillData->status){
        $status = '0';
    }else{
        $status = '1';
    }


    $margedResultData = [
        'appointments'=>['token' => $appointmentsTable, 'status' => $appointmentsStatus, 'data' => $appointmentsData], 
        
        'patients'=>['token' => $patientsTable, 'status' => $patientsStatus, 'data' => $patientsData], 
        
        'stock_in'=>['token' => $stockInTable, 'status' => $stockInStatus, 'data'=> $stockInData], 

        'stock_out'=>['token' => $stockOutTable, 'status' => $stockOutStatus, 'data'=> $stockOutData], 
        
        'lab_billing'=>['token' => $labBillTable, 'status' => $labBillStatus, 'data'=> $labBillData]
    ];


    // print_r($margedResultData['appointments']['data']);


    if ($status) {

        if ($margedResultData['appointments']['status']) {

            echo '
            <div class="row border-bottom border-primary small mx-0 mb-2">
                <div class="col-md-6">Appointment Id</div>
                <div class="col-md-3">Patient Id</div>
                <div class="col-md-3">Contact</div>
            </div>';

            foreach ($margedResultData['appointments']['data'] as $result) {
                
        ?>
                <div class="row mx-0 py-2 border-bottom p-row item-list" id="search-all-listed-items" tabindex="0" onclick="getDtls('<?php echo $margedResultData['appointments']['token'] ?>','<?php echo $result->appointment_id ?>');">
           
        <?php
            echo'
                <div class="col-md-6">' . $result->appointment_id . '</div>
                <div class="col-md-3"><small>' . $result->patient_id . '</small></div>
                <div class="col-md-3"><small>' . $result->patient_phno . '</small></div>
            </div>';
            }

        } else {
            echo '
            <div class="row border-bottom border-primary small mx-0 mb-2">
                <div class="col-md-12">No Data Found</div>
            </div>';
        }

        
        if ($margedResultData['patients']['status']) {
            echo '
            <div class="row border-bottom border-primary small mx-0 mb-2">
                <div class="col-md-5">Patient Id</div>
                <div class="col-md-4">Name</div>
                <div class="col-md-3">Contact</div>
            </div>';

            foreach ($margedResultData['patients']['data'] as $result) {
        ?>
                <div class="row mx-0 py-2 border-bottom p-row item-list" id="search-all-listed-items" tabindex="0" onclick="getDtls('<?php echo $margedResultData['patients']['token'] ?>', '<?php echo $result->patient_id ?>');">

        <?php
            echo '
                <div class="col-md-5">' . $result->patient_id . '</div>
                <div class="col-md-4"><small>' . $result->name . '</small></div>
                <div class="col-md-3"><small>' . $result->phno . '</small></div>
            </div>';
            }

        } else {
            echo '
            <div class="row border-bottom border-primary small mx-0 mb-2">
                <div class="col-md-12">No Patient Data Found</div>
            </div>';
        }

        
        if ($margedResultData['stock_in']['status']) {
            echo '
            <div class="row border-bottom border-primary small mx-0 mb-2">
                <div class="col-md-4">Invoice Id</div>
                <div class="col-md-5">Distributor name</div>
                <div class="col-md-3">Bill No</div>
            </div>';

            foreach ($margedResultData['stock_in']['data'] as $result) {
            
            ?>
                <div class="row mx-0 py-2 border-bottom p-row item-list" id="search-all-listed-items" tabindex="0" onclick="getDtls('<?php echo $margedResultData['stock_in']['token'] ?>', '<?php echo $result->id ?> ');">

            <?php

            echo'
                <div class="col-md-5">' . $result->id . '</div>
                <div class="col-md-4"><small>' . $result->distributor_id . '</small></div>
                <div class="col-md-3"><small>' . $result->distributor_bill . '</small></div>
            </div>';
            }
        } else {
            echo '
            <div class="row border-bottom border-primary small mx-0 mb-2">
                <div class="col-md-12">No Stock In Data Found</div>
            </div>';
        }


        if ($margedResultData['stock_out']['status']) {
            echo '
            <div class="row border-bottom border-primary small mx-0 mb-2">
                <div class="col-md-2">Invoice Id</div>
                <div class="col-md-4">Customer Id</div>
                <div class="col-md-3">Amount</div>
                <div class="col-md-3">Payment Mode</div>
            </div>';

            foreach ($margedResultData['stock_out']['data'] as $result) {
            
            ?>
                <div class="row mx-0 py-2 border-bottom p-row item-list" id="search-all-listed-items" tabindex="0" onclick="getDtls('<?php echo $margedResultData['stock_out']['token'] ?>', 
                '<?php echo $result->invoice_id  ?> ');">

            <?php

            echo'
                <div class="col-md-2">' . $result->invoice_id . '</div>
                <div class="col-md-4"><small>' . $result->customer_id . '</small></div>
                <div class="col-md-3"><small>' . $result->amount . '</small></div>
                <div class="col-md-3"><small>' . $result->payment_mode . '</small></div>
            </div>';
            }

        } else {
            echo '
            <div class="row border-bottom border-primary small mx-0 mb-2">
                <div class="col-md-12">No Stock Out Data Found</div>
            </div>';
        }


        if ($margedResultData['lab_billing']['status']) {
            echo '
            <div class="row border-bottom border-primary small mx-0 mb-2">
                <div class="col-md-3">Bill Id</div>
                <div class="col-md-6">Patient id</div>
                <div class="col-md-3">Bill Date</div>
            </div>';

            foreach ($margedResultData['lab_billing']['data'] as $result) {
            
            ?>
                <div class="row mx-0 py-2 border-bottom p-row item-list" id="search-all-listed-items" tabindex="0" onclick="getDtls('<?php echo $margedResultData['lab_billing']['token'] ?>', '<?php echo $result->bill_id ?> ');">

            <?php

            echo'
                <div class="col-md-3">' . $result->bill_id . '</div>
                <div class="col-md-6"><small>' . $result->patient_id . '</small></div>
                <div class="col-md-3"><small>' . $result->bill_date . '</small></div>
            </div>';
            }
        } else {
            echo '
            <div class="row border-bottom border-primary small mx-0 mb-2">
                <div class="col-md-12">No Lab Data Found</div>
            </div>';
        }
    } else {
        echo '
        <div class="row border-bottom border-primary small mx-0 mb-2">
            <div class="col-md-12">No Data Found</div>
        </div>';
    }
}
