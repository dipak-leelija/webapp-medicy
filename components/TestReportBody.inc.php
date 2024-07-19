<?php
require_once dirname(__DIR__) . '/config/constant.php';
require_once CLASS_DIR . 'dbconnect.php';
require_once CLASS_DIR . 'Pathology.class.php';

$Pathology      = new Pathology;

if (isset($_POST['testId'])) {
    $responseTestId = $_POST['testId'];
} else {
    echo "Request not found!";
    exit;
}

// Check if a comma exists in the string
if (strpos($responseTestId, ',') !== false) {
    // Comma exists, explode the string into an array
    $testIdArray = explode(',', $responseTestId);

    foreach ($testIdArray as $testId) {

        $showTestName   = $Pathology->showTestById($testId);
        $testId         = $showTestName['id'];
        $subTestName    = $showTestName['name'];

        $parameters = json_decode($Pathology->showParametersByTest($testId));
        if ($parameters->status) {
            $parameters = $parameters->data;
        }
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
    }
} else {
    if (!empty($responseTestId)) {

        $showTestName   = $Pathology->showTestById($responseTestId);
        $testId         = $showTestName['id'];
        $subTestName    = $showTestName['name'];

        $parameters = json_decode($Pathology->showParametersByTest($testId));
        if ($parameters->status) {
            $parameters = $parameters->data;
        }
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
    }
}

