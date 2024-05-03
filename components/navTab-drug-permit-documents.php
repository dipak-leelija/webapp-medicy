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

$storeDataFilePath = ROOT_DIR . "assets/images/orgs/drug-permit/";
$form20Data;
$form21Data;

?>
<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title><?= $healthCareName . " - " . SITE_NAME ?></title>

    <link href="<?= CSS_PATH ?>upload-design.css" rel="stylesheet">

    <style>
        .image-preview {
            width: 100%;
            height: 200px;
            /* Set the fixed height as needed */
            border: 1px solid #ccc;
            display: flex;
            justify-content: center;
            align-items: center;
            overflow: hidden;
        }

        .image-preview img,
        .image-preview embed {
            width: auto;
            height: 100%;
            object-fit: contain;
        }
    </style>

</head>

<body id="page-top">

    <!-- Page Wrapper -->
    <div id="wrapper">

        <!-- New Section -->
        <div class="col">
            <div class="mt-4 mb-4">

                <div class="card-body">

                    <!-- <?php if (isset($_GET['setup'])) : ?>
                        <div class="alert alert-warning" role="alert">
                            <?= $_GET['setup'] ?>
                        </div>
                    <?php endif; ?> -->
                    <!-- <form action="<?= PAGE ?>" method="post" enctype="multipart/form-data"> -->
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="card">
                                <div class="card-body">
                                    <div class="text-center" id="yourBtn1" onclick="getFile('form-20')">Click to upload a file for Form 20</div>
                                    <div id="imagePreviewForm20" class="image-preview"></div>
                                    <div style='height: 0px;width: 0px; overflow:hidden;'>
                                        <input id="form-20" type="file" name="form-20" value="" onchange="sub(this, 'imagePreviewForm20')" required />
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-sm-6">
                            <div class="card">
                                <div class="card-body">
                                    <div class="text-center" id="yourBtn2" onclick="getFile('form-21')">Click to upload a file for Form 21</div>
                                    <div id="imagePreviewForm21" class="image-preview"></div>
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
                            <button class="btn btn-success me-md-2" name="drug-permit-data-update" type="submit" onclick="drugFormDataUpload()">Update</button>
                        </div>
                    </div>
                    <!-- </form> -->
                </div>
            </div>
        </div>
    </div>

    <!-- Scroll to Top Button-->
    <a class="scroll-to-top rounded" href="#page-top">
        <i class="fas fa-angle-up"></i>
    </a>


    <script>
        
        document.getElementById("imagePreviewForm20").getElementsByTagName("img")[0].src = <?php echo json_encode($storeDataFilePath . $form20Data); ?>;
        document.getElementById("imagePreviewForm21").getElementsByTagName("img")[0].src = <?php echo json_encode($storeDataFilePath . $form21Data); ?>;

        function validateFileType() {
            var fileName = document.getElementById("form-20").value;
            var idxDot = fileName.lastIndexOf(".") + 1;
            var extFile = fileName.substr(idxDot, fileName.length).toLowerCase();
            if (extFile == "jpg" || extFile == "jpeg" || extFile == "png" || extFile == "pdf") {
                document.getElementById("err-show").classList.add("d-none");
            } else {
                document.getElementById("err-show").classList.remove("d-none");
                // Show current image when error occurs
                document.querySelector('.img-uv-view').src = "<?= $healthCareLogo; ?>";
            }
        }


        function getFile(inputId) {
            document.getElementById(inputId).click();
        }

        function sub(obj, previewId) {
            var file = obj.files[0];
            var reader = new FileReader();

            reader.onloadend = function() {
                var filePreview = document.getElementById(previewId);

                if (file.type.includes('image')) {
                    filePreview.innerHTML = '<img src="' + reader.result + '" style="max-width: 100%; max-height: 100%;">';
                } else if (file.type === 'application/pdf') {
                    filePreview.innerHTML = '<embed src="' + reader.result + '" width="100%" height="100%">';
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


        const drugFormDataUpload = async () => {
            var form20File = document.getElementById('form-20').files[0];
            var form21File = document.getElementById('form-21').files[0];
            var gstin = document.getElementById('gstin').value;
            var pan = document.getElementById('pan').value;

            // Convert file data to base64 strings
            var form20Base64 = null;
            var form21Base64 = null;
            if (form20File) {
                form20Base64 = await getBase64(form20File);
            }
            if (form21File) {
                form21Base64 = await getBase64(form21File);
            }

            var formData = new FormData();
            if (form20Base64) {
                formData.append('form_20', form20Base64);
            }
            if (form21Base64) {
                formData.append('form_21', form21Base64);
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
                },
                error: function(xhr, status, error) {
                    console.error("Error occurred:", error);
                }
            });
        }

        // Function to convert file to base64 string
        function getBase64(file) {
            return new Promise((resolve, reject) => {
                const reader = new FileReader();
                reader.readAsDataURL(file);
                reader.onload = () => resolve(reader.result);
                reader.onerror = error => reject(error);
            });
        }
    </script>
</body>

</html>