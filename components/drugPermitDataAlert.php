<?php if (empty($form20Data) || empty($form21Data) || (empty($gstinData) && empty($panData))) : ?>
    <!-- <div class="alert alert-warning d-flex justify-content-center" role="alert">Update drug permit data !</div> -->
    <div class="col-12 d-flex justify-content-end alert alert-warning alert-dismissible fade show" role="alert">
        <div class="col-md-11">
            <strong>Alert!</strong> Please upload drug permit data.
        </div>
        <div class="col-md-1 d-flex justify-content-end">
            <button type="button" class="btn btn-light" style="border: none; color: black; background-color: #faf8c3;" onclick="redirectToTargetPage('clinic-setting.php?tab-control')">
                <i class="fas fa-upload"></i>
            </button>
        </div>
    </div>
<?php endif; ?>

<script>
    const redirectToTargetPage = (url) =>{
        window.location.href = url;
    }
</script>