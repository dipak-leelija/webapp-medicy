<?php
$name = $_GET['name']; 
$id   = $_GET['id'];
// print_r($name);
// print_r($id);

switch ($name) {
    case 'sales':
        $url = "sales-invoice.php?id=$id";
        break;
    case 'lab_invoice':
        $url = "lab-invoice.php?id=$id";
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
    <iframe id="pdfFrame" src="<?= $url?>" width="100%" height="800" style="border: none;padding:0px;"></iframe>
    <script>
        window.onload = function() {
            var pdfFrame = document.getElementById('pdfFrame');
            pdfFrame.focus();
            pdfFrame.contentWindow.print();
        };
    </script>
</body>
</html>