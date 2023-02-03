<?php
// function myfunction($errno, $errstr){
//     echo "<b>ERROR:</b> {$errno} $errstr<br>";
//     echo "End Script";
//     die();
// }

// set_error_handler(myfunction);
?>
<?php include 'headerlink.php';?>


<!-- Site Metas -->
<title>Medicy Healthcare - we care for you</title>
<meta name="keywords" content="">
<meta name="description" content="">
<meta name="author" content="">

</head>

<body id="top" class="clinic_version">
    <!-- LOADER -->
    <!-- <div id="preloader">
        <img class="preloader" src="images/loaders/heart-loading2.gif" alt="">
    </div> -->
    <!-- END LOADER -->
    <!-- Navbar -->
    <?php include 'require/nav.php' ?>
    
    <!-- Navbar End -->

    <div id="home" class="parallax first-section wow fadeIn" data-stellar-background-ratio="0.4"
        style="background-image:url('images/mian-doc-bg.jpg');background-size: cover;">
        <div class="container">
            <div class="row">
                <div class="col-md-12 col-sm-12">
                    <div class="text-contant">
                        <h2>
                            <!-- <span class="center"><span class="icon"><img src="images/icon-logo.png" alt="#" /></span></span> -->
                            <a href="" class="typewrite" data-period="2000"
                                data-type='[ "Welcome to Medicy Helthcare", "We Care Your Health", "We are Expert!" ]'>
                                <span class="wrap"></span>
                            </a>
                        </h2>
                    </div>
                </div>
            </div>
            <!-- end row -->
        </div>
        <!-- end container -->
    </div>
    <!-- end section -->
    <div id="time-table" class="time-table-section">
        <div class="container">
            <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
                <div class="row">
                    <div class="service-time one " style="background:#2895f1;">
                        <span class="info-icon"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></span>
                        <h3>Book Your Appointment Online</h3>
                        <p>Dignissimos ducimus qui blanditii sentium volta tum deleniti atque cori.</p>
                        <div class="btnCnt">
                            <a href="login.php" class="btn btn-primary btnCnt" onMouseOver="this.style.color='black'"
                                onMouseOut="this.style.color='white'" style="background: #ff7a19"><i
                                    class="fa fa-sign-in" aria-hidden="true"></i> Book Now</a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
                <div class="row">
                    <div class="service-time middle" style="background:#0071d1;">
                        <span class="info-icon"><i class="fa fa-user-md" aria-hidden="true"></i></span>
                        <h3>Call for an Appointment</h3>
                        <div class="time-table-section">
                            <p>We make easier appointment bookig system.</br>Call to Book Appointment</p>
                        </div>
                        <div class="btnCnt">
                            <a href="tel:8695494415" class="btn btn-primary btnCnt" onMouseOver="this.style.color='black'"
                                onMouseOut="this.style.color='white'" style="background: #ff7a19"><i class="fa fa-phone"
                                    aria-hidden="true"></i> Call Now</a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
                <div class="row">
                    <div class="service-time three" style="background:#0060b1;">
                        <span class="info-icon"><i class="fa fa-question" aria-hidden="true"></i></span>
                        <h3>Have any Query?</h3>
                        <p>Dignissimos ducimus qui blanditii sentium volta tum deleniti atque cori as quos dolores et
                            quas
                            mole.</p>
                        <div class="btnCnt">
                            <a href="contact-us.php" class="btn btn-primary btnCnt" onMouseOver="this.style.color='black'"
                                onMouseOut="this.style.color='white'" style="background: #ff7a19"> <i
                                    class="fa fa-question" aria-hidden="true"></i> Ask Now</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div id="about" class="section wow fadeIn">
        <div class="container">
            <div class="heading">
                <!-- <span class="icon-logo"><img src="images/icon-logo.png" alt="#"></span> -->
                <h2>Medicy Diagnostic</h2>
            </div>
            <!-- end title -->
            <div class="row">
                <div class="col-md-6">
                    <div class="message-box">
                        <h4>We Have The</h4>
                        <h2>Best Diagnostic Centre</h2>
                        <p><?php $mainDeesc1 = strpos($mainDeesc , '.', 150)+1; ?></p>
                        <!-- <p><?php //echo strlen(substr($mainDeesc, 0 ,150)) ?></p> -->
                        <p> <?php echo substr($mainDeesc, 0 , $mainDeesc1); ?> </p>
                        <p> <?php echo substr($mainDeesc, $mainDeesc1 ,400) ?> </p>
                        <a href="#services" data-scroll class="btn btn-light btn-radius btn-brd grd1 effect-1">Learn
                            More</a>
                    </div>
                    <!-- end messagebox -->
                </div>
                <!-- end col -->
                <div class="col-md-6">
                    <div class="post-media wow fadeIn">
                        <img src="images/about_03.jpg" alt="" class="img-responsive">
                        <!-- <a href="http://www.youtube.com/watch?v=nrJtHemSPW4" data-rel="prettyPhoto[gal]"
                            class="playbutton"><i class="flaticon-play-button"></i></a> -->
                    </div>
                    <!-- end media -->
                </div>
                <!-- end col -->
            </div>
            <!-- end row -->
            <hr class="hr1">
            
            <div class="row">
                <div class="col-md-3 col-sm-6 col-xs-12">
                    <div class="service-widget">
                        <div class="post-media wow fadeIn">
                            <a href="images/clinic_01.jpg" data-rel="prettyPhoto[gal]"
                                class="hoverbutton global-radius"><i class="flaticon-unlink"></i></a>
                            <img src="images/clinic_01.jpg" alt="" class="img-responsive">
                        </div>
                        <h3>Doctor Consultation</h3>
                    </div>
                    <!-- end service -->
                </div>
                <div class="col-md-3 col-sm-6 col-xs-12">
                    <div class="service-widget">
                        <div class="post-media wow fadeIn">
                            <a href="images/clinic_02.jpg" data-rel="prettyPhoto[gal]"
                                class="hoverbutton global-radius"><i class="flaticon-unlink"></i></a>
                            <img src="images/clinic_02.jpg" alt="" class="img-responsive">
                        </div>
                        <h3>Different Types of Testing</h3>
                    </div>
                    <!-- end service -->
                </div>
                <div class="col-md-3 col-sm-6 col-xs-12">
                    <div class="service-widget">
                        <div class="post-media wow fadeIn">
                            <a href="images/clinic_03.jpg" data-rel="prettyPhoto[gal]"
                                class="hoverbutton global-radius"><i class="flaticon-unlink"></i></a>
                            <img src="images/clinic_03.jpg" alt="" class="img-responsive">
                        </div>
                        <h3>Pharmacy</h3>
                    </div>
                    <!-- end service -->
                </div>
                <div class="col-md-3 col-sm-6 col-xs-12">
                    <div class="service-widget">
                        <div class="post-media wow fadeIn">
                            <a href="images/clinic_01.jpg" data-rel="prettyPhoto[gal]"
                                class="hoverbutton global-radius"><i class="flaticon-unlink"></i></a>
                            <img src="images/clinic_01.jpg" alt="" class="img-responsive">
                        </div>
                        <h3>Emergency Service</h3>
                    </div>
                    <!-- end service -->
                </div>
            </div>
            <!-- end row -->
        </div>
        <!-- end container -->
    </div>
    <div id="service" class="services wow fadeIn">
        <div class="container">
            <div class="row">
                <div class="col-lg-8 col-md-8 col-sm-6 col-xs-12">
                    <div class="inner-services">
                        <div class="col-lg-4 col-md-6 col-sm-6 col-xs-12">
                            <div class="serv">
                                <span class="icon-service"><img src="images/service-icon1.png" alt="#" /></span>
                                <h4>Pharmacy</h4>
                                <p>Lorem Ipsum is simply dummy text of the printing.</p>
                            </div>
                        </div>
                        <div class="col-lg-4 col-md-6 col-sm-6 col-xs-12">
                            <div class="serv">
                                <span class="icon-service"><img src="images/service-icon2.png" alt="#" /></span>
                                <h4>Medical Tests</h4>
                                <p>Lorem Ipsum is simply dummy text of the printing.</p>
                            </div>
                        </div>
                        <div class="col-lg-4 col-md-6 col-sm-6 col-xs-12">
                            <div class="serv">
                                <span class="icon-service"><img src="images/service-icon3.png" alt="#" /></span>
                                <h4>Doctor Consultation</h4>
                                <p>Lorem Ipsum is simply dummy text of the printing.</p>
                            </div>
                        </div>
                        <div class="col-lg-4 col-md-6 col-sm-6 col-xs-12">
                            <div class="serv">
                                <span class="icon-service"><img src="images/service-icon4.png" alt="#" /></span>
                                <h4>Online Appointments</h4>
                                <p>Lorem Ipsum is simply dummy text of the printing.</p>
                            </div>
                        </div>
                        <div class="col-lg-4 col-md-6 col-sm-6 col-xs-12">
                            <div class="serv">
                                <span class="icon-service"><img src="images/service-icon5.png" alt="#" /></span>
                                <h4>Online Medicine Order</h4>
                                <p>Lorem Ipsum is simply dummy text of the printing.</p>
                            </div>
                        </div>
                        <div class="col-lg-4 col-md-6 col-sm-6 col-xs-12">
                            <div class="serv">
                                <span class="icon-service"><img src="images/service-icon6.png" alt="#" /></span>
                                <h4>Covid Care</h4>
                                <p>Lorem Ipsum is simply dummy text of the printing.</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
                    <div class="appointment-form">
                        <h3><span>+</span> Book Appointment</h3>
                        <div class="form">
                            <div class="appbookbtn">
                                <p><?php echo $bookAppointmentText;  ?></p>
                                <a href="#">Book Your Appointment</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- end section -->

    <!-- doctor -->
    <div id="doctors" class="parallax expsection db" data-stellar-background-ratio="0.4" style="background:#fff;"
        data-scroll-id="doctors" tabindex="-1">
        <div class="container">
            <div class="heading">
                <!-- <span class="icon-logo"><img src="images/icon-logo.png" alt="#"></span> -->
                <h2>The Specialist Clinic</h2>
            </div>
            <div class="row dev-list text-center">
                <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12 wow fadeIn" data-wow-duration="1s"
                    data-wow-delay="0.2s"
                    style="visibility: visible; animation-duration: 1s; animation-delay: 0.2s; animation-name: fadeIn;">
                    <div class="widget clearfix">
                        <img src="images/doctor_01.jpg" alt="" class="img-responsive img-rounded">
                        <div class="widget-title">
                            <h3>Soren Bo Bostian</h3>
                            <small>Clinic Owner</small>
                        </div>
                        <!-- end title -->
                        <p>Hello guys, I am Soren from Sirbistana. I am senior art director and founder of Violetta.</p>
                    </div>
                    <!--widget -->
                </div><!-- end col -->
                <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12 wow fadeIn" data-wow-duration="1s"
                    data-wow-delay="0.4s"
                    style="visibility: visible; animation-duration: 1s; animation-delay: 0.4s; animation-name: fadeIn;">
                    <div class="widget clearfix">
                        <img src="images/doctor_02.jpg" alt="" class="img-responsive img-rounded">
                        <div class="widget-title">
                            <h3>Bryan Saftler</h3>
                            <small>Internal Diseases</small>
                        </div>
                        <!-- end title -->
                        <p>Hello guys, I am Soren from Sirbistana. I am senior art director and founder of Violetta.</p>
                    </div>
                    <!--widget -->
                </div><!-- end col -->

                <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12 wow fadeIn">
                    <div class="widget clearfix">
                        <img src="images/doctor_03.jpg" alt="" class="img-responsive img-rounded">
                        <div class="widget-title">
                            <h3>Matthew Bayliss</h3>
                            <small>Orthopedics Expert</small>
                        </div>
                        <!-- end title -->
                        <p>Hello guys, I am Soren from Sirbistana. I am senior art director and founder of Violetta.</p>
                    </div>
                    <!--widget -->
                </div><!-- end col -->

            </div><!-- end row -->
        </div><!-- end container -->
    </div>
   <!-- Footer With Copyright Texts -->
    <?php include 'require/footer.php' ?>
    <!-- end copyrights -->

    <!-- Javascript Links -->
    <?php include 'footerlink.php' ?>

</body>

</html>