<?php
require_once dirname(__DIR__).'/config/constant.php';
require_once ROOT_DIR.'_config/sessionCheck.php'; //check admin loggedin or not

require_once CLASS_DIR.'dbconnect.php';
require_once CLASS_DIR.'distributor.class.php';


//Class initilization
$Distributor = new Distributor();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="<?= JS_PATH ?>sweetAlert.min.js"></script>
</head>

<body>

    <?php
    
    if (isset($_POST['distributor-data-add'])) {
        $distributorName        = $_POST['distributor-name'];
        $distributorPhno        = $_POST['distributor-phno'];
        $distributorGSTID       = $_POST['distributor-gstid'];
        $distributorEmail       = $_POST['distributor-email'];
        $distributorAddress     = $_POST['distributor-address'];
        $distributorAreaPIN     = $_POST['distributor-area-pin'];
        $distributorDsc         = $_POST['distributor-dsc'];
        $parentUrl              = $_POST['parent-window-location'];
        $distributorStatus      = 0;
        $newData                = 1;


        //Insert Into Distributor DB
        
        $addDistributor     = $Distributor->addDistributor(
            $distributorName, $distributorGSTID, $distributorAddress, $distributorAreaPIN, $distributorPhno, $distributorEmail, $distributorDsc, $employeeId, NOW,  $distributorStatus,$newData, $adminId
        );
        print_r($addDistributor);

//*************************************************************************************** */
        // // Function to count how many times another function is called
        // function countFunctionCalls($addDistributor)
        // {
        //     static $callCount = 0;

        //     if (function_exists($addDistributor)) {
        //         $callCount++;
        //         call_user_func($addDistributor);
        //     } else {
        //         echo "Function '$addDistributor' does not exist.";
        //     }

        //     return $callCount;
        // }

        // // Function to be monitored
        // function myFunction()
        // {
        //     echo "This is myFunction().<br>";
        // }

        // // Call countFunctionCalls() to invoke myFunction()
        // $count = countFunctionCalls('myFunction');
        // echo "myFunction() has been called $count times.<br>";

//******************************************************************************************** */
       

        if ($addDistributor == true) {
    ?>
            <script>
                swal("Success", "Distributor added successfully!", "success")
                    .then((value) => {
                        window.location = '<?= $parentUrl ?>';
                    });
            </script>
        <?php
        } else {
        ?>
            <script>
                swal("Error", "Data Not Added!", "error")
                    .then((value) => {
                        window.location = '<?= $parentUrl ?>';
                    });
            </script>
    <?php
        }
    }

    ?>
</body>

</html>