<?php
require_once dirname(__DIR__) . '/config/constant.php';
require_once SUP_ADM_DIR . '_config/sessionCheck.php'; //check admin loggedin or not
require_once SUP_ADM_DIR . '_config/accessPermission.php';

require_once CLASS_DIR.'dbconnect.php';
require_once CLASS_DIR . 'encrypt.inc.php';


if (isset($_GET['response'])) {
    $response = url_dec($_GET['response']);
    $message = url_dec($_GET['message']);
}

/* ============================ End ============================ */

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Medicy Health Care Lab Test Bill</title>
    <link rel="stylesheet" href="<?= CSS_PATH ?>bootstrap 5/bootstrap.css">
    <link rel="stylesheet" href="<?= CSS_PATH ?>custom/test-bill.css">
    <script src="<?php echo JS_PATH ?>bootstrap-js-5/bootstrap.js"></script>
    <!-- Include SweetAlert2 CSS -->
    <link href="<?= CSS_PATH ?>sweetalert2/sweetalert2.min.css" rel="stylesheet">
</head>


<body>
<?php if ($response) : ?>
        <script>
            Swal.fire({
                text: $message,
                icon: "success",
                showCancelButton: false,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = "<?php echo ADM_URL ?>requests.php";
                }
            });
        </script>
    <?php elseif ($alert === false) : ?>
        <script>
            Swal.fire({
                text: $message,
                icon: "error",
                showCancelButton: false,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = "<?php echo ADM_URL ?>requests.php";
                }
            });
        </script>
    <?php endif; ?>
</body>


</html>