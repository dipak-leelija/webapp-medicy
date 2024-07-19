<?php
// echo dirname(dirname(__DIR__)) . '/config/constant.php';
require_once __DIR__ . '/config/constant.php';
require_once ROOT_DIR . '_config/sessionCheck.php'; //check admin loggedin or not
require_once CLASS_DIR . 'dbconnect.php';
require_once CLASS_DIR . 'request.class.php';
require_once CLASS_DIR . 'idsgeneration.class.php';
require_once CLASS_DIR . 'utility.class.php';
require_once CLASS_DIR . 'encrypt.inc.php';


$responseString = url_dec($_GET['response']);

$response = json_decode($responseString);

?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Medicy Health Care Medicine Purchase Bill</title>
    <link rel="stylesheet" href="<?= CSS_PATH ?>bootstrap/5.3.3/dist/css/bootstrap.css">

    <!-- Include SweetAlert2 CSS -->
    <link href="<?= CSS_PATH ?>sweetalert2/sweetalert2.min.css" rel="stylesheet">


</head>

<body>
    <script src="<?= JS_PATH ?>sweetalert2/sweetalert2.all.min.js"></script>

    <?php if ($response->status == true) : ?>
        <script>
            Swal.fire({
                text: "Query submitted successfully.",
                icon: "success",
                showCancelButton: false,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = "<?php echo URL ?>ticket-query-generator.php";
                }
            });
        </script>
    <?php elseif ($alert === false) : ?>
        <script>
            Swal.fire({
                text: "Error occurred",
                icon: "error",
                showCancelButton: false,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = "<?php echo URL ?>ticket-query-generator.php";
                }
            });
        </script>
    <?php endif; ?>

    <!-- Include SweetAlert2 JavaScript -->
</body>

</html>
