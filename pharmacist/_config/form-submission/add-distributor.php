<?php
require_once '../../../php_control/distributor.class.php';


//Class initilization
$Distributor = new Distributor();


if( isset($_POST['add-distributor'])){
    $distributorName        = $_POST['distributor-name'];
    $distributorPhno        = $_POST['distributor-phno'];
    $distributorEmail       = $_POST['distributor-email'];
    $distributorAddress     = $_POST['distributor-address'];
    $distributorAreaPIN     = $_POST['distributor-area-pin'];
    $distributorDsc         = $_POST['distributor-dsc'];
    

    //Insert Into Distributor DB
    $addDistributor     = $Distributor->addDistributor($distributorName, $distributorAddress, $distributorAreaPIN,
                                                        $distributorPhno, $distributorEmail, $distributorDsc);
    if ($addDistributor) {
        //   echo "<script>alert(' Added!')</script>";
          echo "<script>alert('Distributor Added!'); window.location='../../distributor.php';</script>";

     }else{
        //  echo "<script>alert('Distributor Insertion Failed!')</script>";
        echo "<script>alert('Distributor Insertion Failed!'); window.location='../../distributor.php';</script>";

     }
     
 }


?>