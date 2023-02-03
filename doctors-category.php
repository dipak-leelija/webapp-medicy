<?php  
require_once 'php_control/doctor.category.class.php';

$doctorCategory = new DoctorCategory();//Doctor Caterogy Class Initilizes
$showDoctorCategory = $doctorCategory->showDoctorCategory();

 
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

<!-- Doctor Category Page CSS -->
<link rel="stylesheet" href="css/doctors-category-css/style.css">
</head>

<body>
    <?php include 'require/nav.php' ?>

    <section id="doc-top" class="doccategory">

        <?php 
        $length20 = FALSE;
        foreach($showDoctorCategory as $showDoctorCategoryDetails){
         $doctorCatId = $showDoctorCategoryDetails['doctor_category_id'];
         $catname = $showDoctorCategoryDetails['category_name'];
         $description = $showDoctorCategoryDetails['category_descreption'];
         if (strlen($catname) > 20 ) {
            // echo 'style=min-height:20px;';
            $length20 = TRUE;
         }

        }
      //   echo var_dump($length20);
   foreach($showDoctorCategory as $showDoctorCategoryDetails){
      $doctorCatId = $showDoctorCategoryDetails['doctor_category_id'];
      $catname = $showDoctorCategoryDetails['category_name'];
      $description = $showDoctorCategoryDetails['category_descreption'];
      
      //   echo var_dump($length20);
            echo '<div class="doccard">
            <div class="image_box"><img src="images/doctor-category-images/dermatology.jpg" alt=""></div>
            <h2 '; 
            if ($length20) {
               echo 'style=min-height:51px;';
            }
            echo '>'.$catname.'</h2>
            <div class="text_box">
               <span class="para1">
                  <p>'.substr($description, 0, 50).'</p>
               </span>
               <button class="doccard_button"><a href="doctors.php?doccatid='.$doctorCatId.'">View Doctors</a></button>
            </div>
         </div>';
        } ?>
    </section>
    

    <!-- Footer With Copyright Texts -->
    <?php include 'require/footer.php' ?>
    <!-- end copyrights -->

    <!-- Javascript Links -->
    <?php include 'footerlink.php' ?>

</body>

</html>