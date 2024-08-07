<?php
require_once dirname(__DIR__) . '/config/constant.php';
require_once CLASS_DIR . 'dbconnect.php';
require_once CLASS_DIR . 'Pathology.class.php';
require_once CLASS_DIR . 'PathologyReport.class.php';
require_once CLASS_DIR . 'labBillDetails.class.php';

$Pathology          = new Pathology;
$PathologyReport    = new PathologyReport;
$LabBillDetails     = new LabBillDetails;


if (isset($_POST['testId']) && isset($_POST['billId'])) {
    $responseTestId = $_POST['testId'];
    $responseBillId = $_POST['billId'];
}

if ($responseTestId != '') :

    // Check if a comma exists in the string
    if (strpos($responseTestId, ',') !== false) {
        // Comma exists, explode the string into an array
        $testIdArray = explode(',', $responseTestId);
    } else {
        $testIdArray[] = $responseTestId;
    }

    if (count($testIdArray) > 0) :

        foreach ($testIdArray as $eachTestId) {
            // echo $eachTestId;

            $reportDetails = $PathologyReport->getReportParamsByBill($responseBillId);
            if (!empty($reportDetails)) {
                foreach ($reportDetails as $eachParam) {
                    $details = json_decode($Pathology->showTestByParameter($eachParam));
                    if ($details->status) {
                        $existingTests[] = $details->data->test_id;
                    }
                }
                $existingTests = array_unique($existingTests);
            } else {
                $existingTests = [];
            }
            if (in_array($eachTestId, $existingTests)) {
                echo 'Already exists!';
            } else {

                $showTestName   = $Pathology->showTestById($eachTestId);
                $testId         = $showTestName['id'];
                $subTestName    = $showTestName['name'];

                $parameters = json_decode($Pathology->showParametersByTest($eachTestId));
                if ($parameters->status) {
                    $parameters = $parameters->data;
?>
                    <div class='shadow-sm mb-4 py-2 mt-5' data-med-paramid="<?= $testId ?>">
                        <h4 class="text-center"><u> Report of <?= $subTestName ?></u></h4>

                        <?php
                        // Generate input boxes based on the count of unit values
                        foreach ($parameters as $eachParameter) {
                        ?>
                            <div class='d-flex justify-content-between px-3' id="parameter">
                                <div class="w-50">
                                    <p><?= $eachParameter->name ?></p>
                                    <input type='hidden' name='params[]' value="<?= $eachParameter->id ?>" required>
                                </div>
                                <div class="w-50">
                                    <div class='d-flex justify-content-start align-items-baseline'>
                                        <input type='text' class='lab-val-inp col' name='values[]' required>
                                        <span class="col"><?= $eachParameter->unit ?></span>
                                        <span class="col cursor-pointer text-danger" onclick="toggleParameter(this)"><i class="far fa-eye-slash"></i></span>
                                    </div>
                                </div>
                            </div>
                        <?php
                        }
                        ?>

                    </div>
                <?php
                } else {
                ?>
                    <div class="border-1p border-primary border-dashed min-h-100 mt-5 py-5">
                        <h5 class="text-center text-danger"> Parameter Not Avilable for <?= $subTestName ?> Test</h5>
                        <div class="pt-3 text-center">
                            <a href="tel:7699753019" class="btn btn-sm btn-success">Request to Add</a>
                            <a href="<?= URL . 'ticket-query-generator.php' ?>" class="btn btn-sm btn-primary">Contact Now</a>
                        </div>
                    </div>
<?php
                }
            }
        }
    endif;

else :
    echo '
        <div class="border-1p border-muted border-dashed bg-light text-secondary min-h-100 mt-5 py-5">
            <h5 class="text-center"> Select a Test from Dropdown to Generate Report</h5>
        </div>';
endif;
