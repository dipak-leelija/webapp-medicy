<?php if (empty($form20Data) || empty($form21Data) && (empty($gstinData) || empty($panData))) : ?>
    <div class="row" style="z-index: 999;">
        <div class="col-12">
            <div class="col-12 d-flex justify-content-end alert alert-warning fade show" role="alert" id="drugPermitAlertDiv">
                <div class="col-md-11" id="drugPermitAlertMsgDiv">
                    <strong>Please Upload Drug Permit Documents!</strong>
                </div>
                <div class="col-md-1 d-flex justify-content-end">
                    <a href="<?= URL ?>clinic-setting.php?tab-control" class="btn btn-sm btn-primary"><i class="fas fa-upload"></i></a>
                </div>
            </div>
        </div>
    </div>
<?php endif; ?>