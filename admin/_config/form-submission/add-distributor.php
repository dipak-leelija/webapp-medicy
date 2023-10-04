<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add New Prosuct</title>
    <script src="../../../js/sweetAlert.min.js"></script>
</head>

<body>

    <?php
    require_once '../../../php_control/distributor.class.php';


    //Class initilization
    $Distributor = new Distributor();


    if (isset($_POST['add-distributor'])) {
        $distributorName        = $_POST['distributor-name'];
        $distributorPhno        = $_POST['distributor-phno'];
        $distributorEmail       = $_POST['distributor-email'];
        $distributorAddress     = $_POST['distributor-address'];
        $distributorAreaPIN     = $_POST['distributor-area-pin'];
        $distributorDsc         = $_POST['distributor-dsc'];


        //Insert Into Distributor DB
        $addDistributor     = $Distributor->addDistributor(
            $distributorName,
            $distributorAddress,
            $distributorAreaPIN,
            $distributorPhno,
            $distributorEmail,
            $distributorDsc
        );
        if ($addDistributor == true) {
            //   echo "<script>alert(' Added!')</script>";
            // echo "<script>alert('Distributor Added!'); window.location='../../distributor.php';</script>";
    ?>
            <script>
                swal("Success", "Distributor added successfully!", "success")
                    .then((value) => {
                        window.location = '../../distributor.php';
                    });
            </script>
        <?php
        } else {
            //  echo "<script>alert('Distributor Insertion Failed!')</script>";
            //echo "<script>alert('Distributor Insertion Failed!'); window.location='../../distributor.php';</script>";
        ?>
            <script>
                swal("Error", "Data Not Added!", "error")
                    .then((value) => {
                        window.location = '../../distributor.php';
                    });
            </script>
    <?php
        }
    }

    ?>
</body>

</html>