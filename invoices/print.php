<?php

$name = $_GET['name']; 
$id   = $_GET['id'];

switch ($name) {
    case 'sales':
        $url = "sales-invoice.php?id=$id";
        break;
    default:
        # code...
        break;
}

?>
<!DOCTYPE html>
<html>
<head>
    <title>Print Invoice</title>
</head>
<body>
    <iframe id="pdfFrame" src="<?= $url?>" width="100%" height="100%" style="border: none;"></iframe>
    <script>
        window.onload = function() {
            var pdfFrame = document.getElementById('pdfFrame');
            pdfFrame.focus();
            pdfFrame.contentWindow.print();
        };
    </script>
</body>
</html>