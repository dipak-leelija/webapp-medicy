<?php
require_once 'php_control/doctors.class.php';


$docId = $_GET['doctorid'];
// echo $docId;
// exit;

$doctors = new Doctors();
$docDetails = $doctors->showDoctorById($docId);

?>

<?php include 'headerlink.php';?>

<head>
<title>Medicy Healthcare - we care for you</title>
<!-- Site Metas -->
<meta name="keywords" content="">
<meta name="description" content="">
<meta name="author" content="">
    <link rel="stylesheet" href="css/doc-css/style.css">
</head>

<body>
    <!-- Navbar -->
    <?php include 'require/nav.php' ?>
    <!-- Navbar End -->

    <section class="main_section">
        <?php
        foreach($docDetails as $docDetailsShow){
            $docName = $docDetailsShow['doctor_name'];


            echo'<div class="doctor_details">
            <div class="photo_button">
                <div class="photo">
                    <img src="images/doc-images/doctor-photo.png" alt="">
                </div>
                <div class="button">
                    <button class="btn">BOOK APOINTMENT</button>
                </div>
            </div>


            <div class="info">
                <div class="doctor_name">
                    <h2>'.$docName.'</h2>
                    <img src="https://newassets.apollo247.com/images/ic_apollo.svg" alt="">
                </div>
                <div class="line"></div>
                <div class="category">
                    <h3>Dermatology | 8 YRS</h3>
                    <div class="line"></div>
                </div>
                <div class="doctor_edu_loc">
                    <div class="doctor_edu_loc_reg">
                        <div class="doctor_edu_loc_reg_photo">
                            <img src="images/doc-images/edu.png" alt="">
                        </div>
                        <div class="doctor_edu_loc_reg_education">
                            <div class="doctor_edu_loc_reg_heading">Education</div>
                            <span class="description">MBBS, MD (DVL), DNB, Fellow (Dermatosurgery & Lasers)</span>
                        </div>
                    </div>
                    <div class="doctor_edu_loc_reg">
                        <div class="doctor_edu_loc_reg_photo">
                            <img src="images/doc-images/location.png" alt="">
                        </div>
                        <div class="doctor_edu_loc_reg_location">
                            <div class="doctor_edu_loc_reg_heading">Location</div>
                            <span class="description">Medicy Health Care Murshidabad<span>
                        </div>
                    </div>
                    <div class="doctor_edu_loc_reg">
                        <div class="doctor_edu_loc_reg_photo">
                            <img src="images/doc-images/reg.png" alt="">
                        </div>
                        <div class="doctor_edu_loc_reg_registration_number">
                            <div class="doctor_edu_loc_reg_heading">Registration Number</div>
                            <span class="description">79387</span>
                        </div>
                    </div>
                    <div class="doctor_edu_loc_reg"><div class="doctor_edu_loc_reg_photo">
                        <img src="images/doc-images/lan.png" alt="">
                    </div>
                    <div class="doctor_edu_loc_reg_languages">
                        <div class="doctor_edu_loc_reg_heading">Languages</div>
                        <span class="description">English, Hindi, Telugu</span>
                    </div>
                    </div>
                </div>
            </div>
        </div>


        <div class="about">
            <h3>About Dr S Madhuri</h3>
            <div class="line"></div>
            <div class="about_para">
                <p>Lorem, ipsum dolor sit amet consectetur adipisicing elit. Tempore illo molestias necessitatibus similique debitis? Fuga aliquam esse omnis beatae, exercitationem dolor cum maiores distinctio velit saepe. Voluptatem quod eveniet assumenda.</p>
            </div>
        </div>

        <div class="award">
            <h4>Awards</h4>
            <span class="award_para"><p>Best Publication Award (2016) and Best Paper Award (2015)</p></span>
        </div>

        <div class="membership">
            <h4>Membership</h4>
            <!-- <span class="membership_para"><p>1. Best Publication Award (2016) and Best Paper Award (2015)</p></span> -->
            <ul>
                <li>Best Publication Award (2016) and Best Paper Award (2015)</li>
                <li>Best Publication Award (2016) and Best Paper Award (2015)</li>
            </ul>
        </div>
        <div class="clinic_address">
            <h4>Clinic Address</h4>
            <div class="line"></div>
            <span class="address"><p>Thanar More, Daulatabad, Murshidabad,West Bengal, 742302</p> </span>
            <span class="hospital_image"><img src="images/doc-images/hospital.png" alt=""></span>
        </div>';
        }
        
        ?>
        
        
    </section>

    <!-- Footer With Copyright Texts -->
    <?php include 'require/footer.php' ?>
    <!-- end copyrights -->

    <!-- Javascript Links -->
    <?php include 'footerlink.php' ?>

</body>

</html>