<?php
require_once 'php_control/labtypes.class.php';
$labTypes = new LabTypes();
$showLabTypes = $labTypes->showLabtypes();
?>

<head>
<?php include 'headerlink.php';?>
<!-- Site Meta  -->
<title>Medicy Healthcare - we care for you</title>
<meta name="keywords" content="">
<meta name="description" content="">
<meta name="author" content="">

<!-- Lab category Page CSS -->
<link rel="stylesheet" href="css/lab-category/lab-cat.css">

</head>

<body>
    <!-- Navbar -->
    <?php include 'require/nav.php' ?>
    <!-- Navbar End -->
    <section class="category">
    <?php
      foreach ($showLabTypes as $showLabTypesDetails) {
         $labTypeid    = $showLabTypesDetails['id'];
         $labTypeName  = $showLabTypesDetails['test_type_name'];
         $labTypeImage = $showLabTypesDetails['image'];
         $labTypeDsc   = $showLabTypesDetails['dsc'];

         echo'<div class="card">
                  <div class="image_box"><img src="admin/'.$labTypeImage.'" alt=""></div>
                  <h2>'.$labTypeName.'</h2>
                  <div class="text_box">
                     <span class="para1"><p>For individual age 10-80 years <br><br><br> 5 test included</p></span>
                     </div>
                     <button class="card_button" onclick="labPage('.$labTypeid.');"><a href="single-lab-page.php?id='.$labTypeid.'"><span>₹ 999</span> Available Tests</a></button>
                     </div>';
                     
                  }
                  // <a class="card_button" href="single-lab-page.php"><span>₹ 999</span> Available Tests</a>
      ?>
    </section>
    <!-- Footer With Copyright Texts -->
    <?php include 'require/footer.php' ?>
    <!-- end copyrights -->

    <!-- Javascript Links -->
    <?php include 'footerlink.php' ?>
    <script>
       labPage = (labTypeid) =>{
         //  alert(labTypeid);
          window.location = 'single-lab-page.php?id='+ escape(labTypeid);;
       }

    </script>
</body>
</html>