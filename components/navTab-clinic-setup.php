
<!-- Page Wrapper -->
<div id="wrapper">

    <!-- New Section -->
    <div class="col">
        <div class="mt-4 mb-4">

            <div class="card-body">
                <?php if (isset($_GET['setup']) && isset($_GET['flag']) && $_GET['flag'] == '1') : ?>
                    <div class="alert alert-success" role="alert">
                        <?= $_GET['setup'] ?>
                    </div>
                <?php elseif (isset($_GET['setup']) && isset($_GET['flag']) && $_GET['flag'] == '0') :  ?>
                    <div class="alert alert-warning" role="alert">
                        <?= $_GET['setup'] ?>
                    </div>
                <?php endif; ?>
                <form action="<?= PAGE ?>" method="post" enctype="multipart/form-data">

                    <div class="row">
                        <div class="col-md-6">
                            <div class="alert alert-danger d-none" id="err-show" role="alert">
                                Only jpg/jpeg and png files are allowed!
                            </div>
                            <div class="d-flex justify-content-around align-items-center">
                                <img class="mb-0 mt-3 rounded img-uv-view" src="<?= $healthCareLogo; ?>" width="100%" height="180" alt="">
                                <div class="">
                                    <input type="file" style="display:none;" id="img-uv-input" accept=".jpg,.jpeg,.png" name="site-logo" onchange="validateFileType()">
                                    <label for="img-uv-input" class="btn btn-primary">Change
                                        Logo</label>
                                </div>
                            </div>
                            <div class="form-group col-md-12 mt-5">
                                <input class="med-input" type="text" name="helthcare-name" id="helthcare-name" value="<?= $healthCareName; ?>" required>
                                <label class="med-label" style="left:20px" for="helthcare-name">Organization/Helth Care
                                    Name <span class="text-danger font-weight-bold">*</span></label>
                            </div>

                            <div class="form-group col-md-12">
                                <input class="med-input" type="text" name="helpline-no" id="helpline-no" value="<?php echo $healthCarePhno; ?>" required>
                                <label class="med-label" style="left:20px" for="helpline-no">Help Line Number <span class="text-danger font-weight-bold">*</span></Address>
                                </label>
                            </div>
                            <div class="form-group col-md-12">
                                <input class="med-input" type="text" name="apnt-booking-no" id="apnt-booking-no" value="<?php echo $healthCareApntbkNo; ?>" required>
                                <label class="med-label" style="left:20px" for="apnt-booking-no">Appointment Help
                                    Line <span class="text-danger font-weight-bold">*</span></label>
                            </div>

                            <div class="form-group col-md-12">
                                <input class="med-input" type="text" name="email" id="email" value="<?php echo $healthCareEmail; ?>">
                                <label class="med-label" style="left:20px" for="email">Helth Care Email</label>
                            </div>

                        </div>

                        <div class="col-md-6" style="padding-top:5.5rem;">
                            <div class="form-group col-sm-12">
                                <textarea class="med-input mt-n2" maxlength="50" name="address-1" id="address-1" rows="2" required><?= $healthCareAddress1; ?></textarea>
                                <label class="med-label" style="left:20px" for="address-1">Address 1 <span class="text-danger font-weight-bold">*</span></label>
                            </div>

                            <div class="form-group col-md-12">
                                <textarea class="med-input" type="text" maxlength="50" name="address-2" id="address-2" rows="2"><?= $healthCareAddress2; ?></textarea>
                                <label class="med-label" style="left:20px" for="address-2">Address 2</label>
                            </div>
                            <div class="form-group col-md-12">
                                <input class="med-input" type="text" maxlength="50" name="city" id="city" value="<?php echo $healthCareCity; ?>" required>
                                <label class="med-label" style="left:20px" for="city">City <span class="text-danger font-weight-bold">*</span></label>
                            </div>
                            <div class="form-group col-md-12">
                                <input class="med-input" type="text" maxlength="50" name="dist" id="dist" value="<?php echo $healthCareDist; ?>" required>
                                <label class="med-label" style="left:20px" for="dist">Dist <span class="text-danger font-weight-bold">*</span></label>
                            </div>
                            <div class="form-group col-md-12">
                                <select class="med-input" name="state" id="state" required>
                                    <?php echo '<option value="' . $healthCareState . '">' . $healthCareState . '</option>'; ?>
                                    <option value="West Bengal">West Bengal</option>
                                    <option value="Others">Others</option>
                                </select>
                                <label class="med-label" style="left:20px" for="state">Select State <span class="text-danger font-weight-bold">*</span></label>
                            </div>
                            <div class="form-group col-md-12">
                                <input class="med-input" type="number" maxlength="7" minlength="7" name="pin" id="pin" value="<?php echo $healthCarePin; ?>" required>
                                <label class="med-label" style="left:20px" for="pin">PIN <span class="text-danger font-weight-bold">*</span></label>
                            </div>

                            <div class="form-group col-md-12">
                                <select class="med-input" name="country" id="country" required>
                                    <option value="India">India</option>
                                    <option value="Others">Others</option>
                                </select>
                                <label class="med-label" style="left:20px" for="country">Country <span class="text-danger font-weight-bold">*</span></label>
                            </div>
                        </div>
                    </div>

                    <div class="d-grid gap-2 d-md-flex justify-content-md-end mt-2 me-md-2">
                        <button class="btn btn-success me-md-2" name="update" type="submit">Update</button>
                    </div>

                </form>
            </div>
        </div>

    </div>


</div>

<!-- Scroll to Top Button-->
<a class="scroll-to-top rounded" href="#page-top">
    <i class="fas fa-angle-up"></i>
</a>


<script>
    function validateFileType() {
        var fileName = document.getElementById("img-uv-input").value;
        console.log(fileName);
        var idxDot = fileName.lastIndexOf(".") + 1;
        var extFile = fileName.substr(idxDot, fileName.length).toLowerCase();
        if (extFile == "jpg" || extFile == "jpeg" || extFile == "png") {
            document.getElementById("err-show").classList.add("d-none");
        } else {
            document.getElementById("err-show").classList.remove("d-none");
            // Show current image when error occurs
            document.querySelector('.img-uv-view').src = "<?= $healthCareLogo; ?>";
        }
    }
</script>