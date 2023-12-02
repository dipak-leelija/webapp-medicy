<?php
require_once dirname(__DIR__) . '/config/constant.php';
?>

<div class="card-body pt-0">
    <form method="post" action="<?= URL?>ajax/manufacturer.add.ajax.php">

        <div class="col-md-12">
            <input class="form-control" id="manuf-name" name="manuf-name" placeholder="Manufacturer Name"
                maxlength="155" required>
        </div>

        <div class="col-md-12 mt-2">
        <input class="form-control" id="manuf-mark" name="manuf-mark" placeholder="Manufacturer mark"
                required>
        </div>

        <div class="col-md-12 mt-2">
            <textarea name="manuf-dsc" id="manuf-dsc" class="form-control" cols="30" rows="3"
                maxlength="355" placeholder="Description"></textarea>
        </div>

        <div class="d-grid gap-2 d-md-flex justify-content-md-end mt-3 me-md-2">
            <button class="btn btn-primary me-md-2" name="add-distributor" type="submit">Add
                Manufacturer</button>
        </div>
    </form>
</div>
<!-- /end Add Distributor  -->