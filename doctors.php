<?php

require_once 'php_control/doctors.class.php';

require_once 'php_control/doctor.category.class.php';



$docCatId = $_GET['doccatid'];



?>



<!DOCTYPE html>

<html lang="en">

<!-- Basic -->
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">

<!-- Mobile Metas -->
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="viewport" content="initial-scale=1, maximum-scale=1">

<!-- Site Metas -->

<title>Medicy Healthcare - we care for you</title>
<meta name="keywords" content="">
<meta name="description" content="">
<meta name="author" content="">

<!-- Site Icons -->
<link rel="shortcut icon" href="images/fevicon.ico.png" type="image/x-icon" />
<link rel="apple-touch-icon" href="images/apple-touch-icon.png">

<!-- Bootstrap CSS -->
<link rel="stylesheet" href="css/bootstrap-3/bootstrap.css">
<link rel="stylesheet" href="css/bootstrap-3/bootstrap.min.css">
<link rel="stylesheet" href="css/font-awesome.min.css">

<!-- Site CSS -->
<link rel="stylesheet" href="css/root-index-style.css">

<!-- Colors CSS -->
<link rel="stylesheet" href="css/colors.css">

<!-- ALL VERSION CSS -->
<link rel="stylesheet" href="css/versions.css">

<!-- Responsive CSS -->
<link rel="stylesheet" href="css/responsive.css">

<!-- Custom CSS -->
<link rel="stylesheet" href="css/root-index-custom.css">

<!-- Modernizer for Portfolio -->
<script src="js/modernizer.js"></script>

<link rel="stylesheet" href="css/doctors-css/style.css">

</head>



<body id="top">

    <?php include 'require/nav.php' ?>

    <section class="main_section">

        <?php

        $doctors =  new Doctors(); // doctor class

        //Fetching Doctor By categoryId

        $showDoctors = $doctors->showDoctorByCatId($docCatId);

        if ($showDoctors > 0) {

           

            foreach ($showDoctors as $showDoctorsDetails) {

                $docName = $showDoctorsDetails['doctor_name'];

                $docSpecialization = $showDoctorsDetails['doctor_specialization'];

                $docDegree = $showDoctorsDetails['doctor_degree'];

                $docId = $showDoctorsDetails['doctor_id'];





                    //Fetching Doctor Category By Id

                    $doctorCategory = new DoctorCategory();

                    $showCategoryById = $doctorCategory->showDoctorCategoryById($docSpecialization);

                    if ($showCategoryById > 0) {

                        foreach ($showCategoryById as $showCategoryByIdDetails) {

                            $docCategoryName = $showCategoryByIdDetails['category_name'];

                            $doctorCategoryDescp = $showCategoryByIdDetails['category_descreption'];

                        }

                    }

                

                echo '<div class="docrow">

                <div class="title">

                    <p>Doctor of the Hour!</p>

                </div>

                <div class="doctor_details">

                    <img class="doctor_details_logo" src="https://newassets.apollo247.com/images/ic_apollo.svg" alt="">

                    <p>available in 6 mins</p>



                    <div class="image"><img src="images/doctors-images/photo.webp" alt=""></div>

                    <div class="description">

                        <h2><a href="specific-doc.php?doctorid='.$docId.'">'.$docName.'</a></h2>

                        <h3>'.$docCategoryName.' | 9years</h3>

                        <h4>'.$docDegree.'</h4>

                        <h4>Medicy Health Care Murshidabad</h4>

                    </div>

                    <div class="fees_and_book">

                        <h4>Starts at: ₹ 1000</h4>

                        <button type="button" class="btn DocbtnColor">Book an Apointment</button>

                    </div>

                </div>

                </div>';

            }

        }else {
            echo '<div class="jumbotron text-center">
            <h1 class="display-4">No Doctors in This Category!</h1>
            <p class="lead mx-4">This is a simple hero unit, a simple jumbotron-style component for calling extra attention to featured content or information.</p>
            <hr class="my-4 mx-4">
            <p>It uses utility classes for typography and spacing to space content out within the larger container.</p>
            <p class="lead">
              <a class="btn btn-primary btn-lg" href="#" role="button">Learn more</a>
            </p>
          </div>'; 
        }

        ?>



    </section>

    <?php include 'require/footer.php' ?>



    <!-- Javascript Links -->

    <?php include 'footerlink.php' ?>



</body>



</html>