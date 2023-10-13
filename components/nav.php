<?php
require_once dirname(__DIR__).'/config/constant.php';
require_once CLASS_DIR.'hospital.class.php';

$helthCare = new HelthCare();//Hospital Class Initilized
$showHospital = $helthCare->showhelthCare();
foreach($showHospital as $showHospitalDetails){
   $helpLineNumber = $showHospitalDetails['hospital_phno'];
   $appBookingNumber = $showHospitalDetails['appointment_help_line'];
   $hospitalEmail = $showHospitalDetails['hospital_email'];
   // $hospitalAddress = $showHospitalDetails['hospital_address'];
   $helthCareAddress1 = $showHospitalDetails['address_1'];
    $helthCareAddress2 = $showHospitalDetails['address_2'];
    $helthCareCity = $showHospitalDetails['city'];
    $helthCareDist = $showHospitalDetails['dist'];
    $helthCarePin = $showHospitalDetails['pin'];
    
   $mainDeesc = $showHospitalDetails['main_desc'];
   $footerDesc = $showHospitalDetails['footer_desc'];
   $bookAppointmentText = $showHospitalDetails['book_appointment_text'];
}
echo '
<header>
<div class="header-top wow fadeIn">
   <div class="container">
      <a class="navbar-brand" href="index.html"><img src="images/logo.png" alt="image"></a>
      <div class="right-header">
         <div class="header-info">
            <div class="info-inner">
                <p>Call For an Appointment</p>
               <span class="icontop"><img src="images/phone-icon.png" alt="#"></span>
               <span class="iconcont"><a href="tel:'.$appBookingNumber.'">'.$appBookingNumber.'</a></span>
               
               
            </div>
            <div class="info-inner">
               <p>Send your query</p>
               <span class="icontop"><i class="fa fa-envelope" aria-hidden="true"></i></span>
               <span class="iconcont"><a data-scroll href="mailto:'.$hospitalEmail.'">'.$hospitalEmail.'</a></span>	
            </div>
            <div class="info-inner">
               <div class="serch-bar-head">
                  <div id="custom-search-input-head">
                     <div class="input-group col-md-12">
                        <input type="text" class="form-control input-lg" placeholder="Search" onkeyup="imu(this.value)" />
                        <span class="input-group-btn">
                        <button class="btn btn-info btn-lg" type="button">
                        <i class="fa fa-search" aria-hidden="true"></i>
                        </button>
                        </span>
                     </div>
                  </div>
                  <div id="searchReult">
                     
                  </div>
               </div>
            </div>
         </div>
      </div>
   </div>
</div>
<div class="header-bottom wow fadeIn">
   <div class="container">
      <nav class="main-menu">
         <div class="navbar-header">
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar"><i class="fa fa-bars" aria-hidden="true"></i></button>
         </div>
         
         <div id="navbar" class="navbar-collapse collapse">
            <ul class="nav navbar-nav">
               <li><a href="'.URL.'">Home</a></li>
               <li><a data-scroll href="doctors-category.php">Doctors</a></li>
               <li><a data-scroll href="lab-tests.php">Lab Tests</a></li>
               <li><a data-scroll href="#doctors">Pharmacy</a></li>
               <li><a data-scroll href="contact-us.php">Contact Us</a></li>

            </ul>
               <a class="apbkbtn" data-scroll href="login.php">Book Appointment</a>
         </div>
      </nav>
      <!-- Button Gose here -->
   </div>
</div>
</header>'

?>