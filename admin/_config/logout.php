<?php
session_start();
session_unset();
session_destroy();
header("Location: ../../employee/config/login.php");
                     

exit();

?>