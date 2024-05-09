<?php
require_once 'config/constant.php';
require_once ROOT_DIR . '_config/sessionCheck.php'; //check admin loggedin or not

require_once CLASS_DIR . 'dbconnect.php';
require_once ROOT_DIR . '_config/healthcare.inc.php';
require_once CLASS_DIR . 'subscription.class.php';
require_once CLASS_DIR . 'utility.class.php';

$Subscription   = new Subscription;
$Utility        = new Utility;

$currentUrl = $Utility->currentUrl();
// Healthcare Addesss and details

$baseURL = URL;
$filePath = "assets/images/orgs/drug-permit/";
// $storeDataFilePath = ROOT_DIR . "assets/images/orgs/drug-permit/";
$storeDataFilePath = $baseURL . ltrim($filePath, '/');

// echo $storeDataFilePath;

$form20Data;
$form21Data;

?>

<!-- Page Wrapper -->
<div id="wrapper">

    <!-- New Section -->
    <div class="col">
        <div class="mt-4 mb-4">

            <div class="card-body">

                <div class="row">
                    <div class="col-sm-6">
                        <div class="card">
                            <div class="card-body">
                                <div id="imagePreviewForm20" class="image-preview" onclick="getFile('form-20')" style="z-index: 9999;">
                                    <i class="fas fa-upload">Upload Form 20</i>
                                </div>
                                <div style='height: 0px;width: 0px; overflow:hidden;'>
                                    <input id="form-20" type="file" name="form-20" value="" onchange="sub(this, 'imagePreviewForm20')" required />
                                </div>
                            </div>
                        </div>
                    </div>


                    <div class="col-sm-6">
                        <div class="card">
                            <div class="card-body">
                                <div id="imagePreviewForm21" class="image-preview" onclick="getFile('form-21')">
                                    <i class="fas fa-upload">Upload Form 21</i>
                                </div>
                                <div style='height: 0px;width: 0px; overflow:hidden;'>
                                    <input id="form-21" type="file" name="form-21" value="" onchange="sub(this, 'imagePreviewForm21')" required />
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row mt-3">
                    <div class="col-md-6">
                        <label>Enter Organizatin GST number</label><span class="ml-2" id="gstin-span" style="color: red;">*</span>
                        <input type="text" class="form-control mb-3" id="gstin" name="gstin" maxlength="15" placeholder="GSTIN" value="<?= $gstinData = $gstinData ?? ''; ?>" autocomplete="off" required onfocusout="checkField()">
                    </div>
                    <div class="col-md-6">
                        <label>Enter PAN number</label><span class="ml-2" id="pan-span" style="color: red;">*</span>
                        <input type="text" class="form-control mb-3" id="pan" name="pan" maxlength="10" placeholder="PAN" value="<?= $panData = $panData ?? ''; ?>" autocomplete="off" required onfocusout="checkField()">
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12 d-grid gap-2 d-md-flex justify-content-md-end mt-2 me-md-2">
                        <button class="btn btn-success me-md-2" name="drug-permit-data-update" type="submit" onclick="drugFormDataUpload ()">Update</button>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>

<!-- Scroll to Top Button-->
<a class="scroll-to-top rounded" href="#page-top">
    <i class="fas fa-angle-up"></i>
</a>


<script>
    // =====================================================
    // Function to fetch file from database and display it in the given div
    function displayFileFromDatabase(filePath, previewId) {
        console.log(filePath);
        const xhr = new XMLHttpRequest();
        xhr.onreadystatechange = function() {
            if (xhr.readyState === 4 && xhr.status === 200) {
                const preview = document.getElementById(previewId);
                const fileType = filePath.split('.').pop().toLowerCase();
                const contentType = xhr.getResponseHeader("Content-Type");
                const blob = new Blob([xhr.response], {
                    type: contentType
                });
                const reader = new FileReader();
                reader.onload = function() {
                    const base64data = reader.result;
                    if (fileType === 'pdf') {
                        preview.innerHTML = `<embed src="${base64data}" type="application/pdf" width="100%" height="100%"><i class="" style="position: absolute; align-item: center; width: 60%; height: 12rem;"></i>`;
                    } else if (fileType === 'jpg' || fileType === 'jpeg' || fileType === 'png') {
                        preview.innerHTML = `<img src="${base64data}" style="max-width: 100%; max-height: 12rem;"><i class="" style="position: absolute; align-item: center; width: 60%; height: 12rem;"></i>`;
                    } else {
                        preview.innerHTML = `<p>Unsupported file format</p>`;
                    }
                };
                reader.readAsDataURL(blob);
            }
        };
        xhr.open('GET', filePath, true);
        xhr.responseType = 'arraybuffer';
        xhr.send();
    }


    <?php if (!empty($form20Data)) : ?>
        const form20path = <?php echo json_encode($storeDataFilePath . $form20Data); ?>;
        displayFileFromDatabase(form20path, 'imagePreviewForm20');
    <?php endif; ?>

    <?php if (!empty($form21Data)) : ?>
        const form21path = <?php echo json_encode($storeDataFilePath . $form21Data); ?>;
        displayFileFromDatabase(form21path, 'imagePreviewForm21');
    <?php endif; ?>


    // =====================================================
    function getFile(inputId) {
        document.getElementById(inputId).click();
    }


    function sub(obj, previewId) {

        var file = obj.files[0];
        var reader = new FileReader();

        reader.onloadend = function() {
            var filePreview = document.getElementById(previewId);

            if (file.type.includes('image')) {
                filePreview.innerHTML = '<img src="' + reader.result + '" style="max-width: 100%; max-height: 12rem;" ><i class="" style="position: absolute; align-item: center; width: 60%; height: 12rem;"></i>'
            } else if (file.type === 'application/pdf') {
                filePreview.innerHTML = '<embed src="' + reader.result + '" style="max-width: 100%; max-height: 12rem;" class="fas fa-upload"><i class="" style="position: absolute; align-item: center; width: 60%; height: 12rem;"></i>';
            } else {
                filePreview.innerHTML = '<p>File type not supported for preview</p>';
            }
        }

        if (file) {
            reader.readAsDataURL(file);
        }
    }



    const checkField = () => {
        const gstinData = document.getElementById('gstin').value;
        const panData = document.getElementById('pan').value;

        if (gstinData !== '') {
            document.getElementById('pan').removeAttribute("required");
            document.getElementById('pan-span').style.display = 'none';
        }

        if (panData !== '') {
            document.getElementById('gstin').removeAttribute("required");
            document.getElementById('gstin-span').style.display = 'none';
        }
    }


    const drugFormDataUpload = () => {
        var formData = new FormData();
        var form20File = document.getElementById('form-20').files[0];
        var form21File = document.getElementById('form-21').files[0];
        var gstin = document.getElementById('gstin').value;
        var pan = document.getElementById('pan').value;

        if (!form20File && !form21File) {
            console.error("No files selected.");
            return; // No need to proceed if no files are selected
        }

        if (form20File) {
            formData.append('form_20', form20File);
        }
        if (form21File) {
            formData.append('form_21', form21File);
        }
        formData.append('gstin', gstin);
        formData.append('pan', pan);

        $.ajax({
            url: '<?= URL ?>_config/form-submission/navTab-drug-permit-data-submit.php',
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
                console.log("Response from server:", response);
                if(response == '1'){
                    document.getElementById("alert-div-control").innerHTML = '1';
                    document.getElementById('drugPermitMsg').innerHTML = 'Success! Data updated successfully.';
                }
            },
            error: function(xhr, status, error) {
                console.error("Error occurred:", error);
            }
        });
    }



    // ====================================== 
</script>