<?php 
    if($_SESSION['ADMIN']){
        // echo 'true';
        $userEmail = $_SESSION['USER_EMAIL'];
        $userRole = 'ADMIN';
        $userFname = $_SESSION['USER_FNAME'];
        $username = $_SESSION['USERNAME'];
        $userId = $_SESSION['USERID'];
    }else{
        // echo 'false';
        $userEmail = $_SESSION['USER_EMAIL'] ;
        $userRole = $_SESSION['USER_ROLE'];
        $userFname = $_SESSION['USER_FNAME'];
        $username = $_SESSION['USERNAME'];
        $userId = $_SESSION['USERID'];
    }

?>