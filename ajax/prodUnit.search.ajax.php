<?php 
require_once dirname(__DIR__).'/config/constant.php';
require_once ROOT_DIR.'_config/sessionCheck.php'; //check admin loggedin or not

require_once CLASS_DIR.'dbconnect.php';
// require_once CLASS_DIR.'manufacturer.class.php';
require_once CLASS_DIR.'measureOfUnit.class.php';


// $match = $_POST['search'];
$match = isset($_POST['search']) ? $_POST['search'] : $adminId;
// echo $match;
$prodUnit       = new MeasureOfUnits();

if ($match == 'all') {
    $showProdUnit = json_decode($prodUnit ->prodUnitSearch($match, $adminId));
    // print_r($showProdUnit);
}else {
    $showProdUnit    = json_decode($prodUnit ->prodUnitSearch($match, $adminId));
}


if ($showProdUnit->status) {
    $showProdUnit= $showProdUnit->data;
}else {
    // echo "<p class='text-center font-weight-bold'>manufacturerNot Found!</p>";
    echo "<div class='p-1 border-bottom list'> $match </div>";
}
?>

<table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
    <thead>
        <tr>
            <th>SL.</th>
            <th>Short Name</th>
            <th>Full Name</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody>
        <?php
        if (is_array($showProdUnit)) {
            foreach ($showProdUnit as $rowProdUnit) {
                $prodUnitId       = $rowProdUnit->id;
                $prodUnitSName    = $rowProdUnit->short_name;
                $prodUnitFName    = $rowProdUnit->full_name;

                echo '<tr>
                        <td>' . $prodUnitId   . '</td>
                        <td>' . $prodUnitSName . '</td>
                        <td>' . $prodUnitFName . '</td>
                        <td>
                            <a class="mx-1" data-toggle="modal" data-target="#distributorModal" onclick="distViewAndEdit(' . $prodUnitId . ')"><i class="fas fa-edit"</i></a>
                        </td>
                       </tr>';
            }
        }
        ?>
    </tbody>
</table>