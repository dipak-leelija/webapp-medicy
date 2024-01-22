<?php 
require_once dirname(__DIR__).'/config/constant.php';
require_once ROOT_DIR.'_config/sessionCheck.php'; //check admin loggedin or not

require_once CLASS_DIR.'dbconnect.php';
// require_once CLASS_DIR.'manufacturer.class.php';
require_once CLASS_DIR.'packagingUnit.class.php';


$match = $_POST['search'];

$packUnit       = new PackagingUnits();

if ($match == 'all') {
    $showPackUnit = json_decode($packUnit ->packUnitSearch($match));
}else {
    $showPackUnit    = json_decode($packUnit ->packUnitSearch($match));
}


if ($showPackUnit->status) {
    $showPackUnit= $showPackUnit->data;
}else {
    // echo "<p class='text-center font-weight-bold'>manufacturerNot Found!</p>";
    echo "<div class='p-1 border-bottom list'> $match </div>";
}
?>

<table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
    <thead>
        <tr>
            <th>SL.</th>
            <th>Unit Name</th>
            <th>Status</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody>
        <?php
        if (is_array($showPackUnit)) {
            foreach ($showPackUnit as $rowPackUnit) {
                $packUnitId      = $rowPackUnit->id;
                $packUnitName    = $rowPackUnit->unit_name;
                $packUnitStatus  = $rowPackUnit->pack_status;

                $statusLabel = '';
                $statusColor = '';
                switch ($packUnitStatus) {
                    case 0:
                        $statusLabel = 'Disabled';
                        $statusColor = 'red';
                        break;
                    case 1:
                        $statusLabel = 'Pending';
                        $statusColor = '#4e73df';
                        break;
                    case 2:
                        $statusLabel = 'Active';
                        $statusColor = 'green';
                        break;
                    default:
                        $statusLabel = 'Disabled';
                        break;
                }
                echo '<tr>
                        <td>' . $packUnitId  . '</td>
                        <td>' . $packUnitName . '</td>
                        <td style="color: ' . $statusColor . ';">' . $statusLabel . '</td>
                        <td>
                            <a class="mx-1" data-toggle="modal" data-target="#distributorModal" onclick="distViewAndEdit(' . $packUnitId . ')"><i class="fas fa-edit"
                            <a class="mx-1" id="delete-btn" data-id="' . $packUnitId . '"><i class="far fa-trash-alt"></i></a>
                        </td>
                       </tr>';
            }
        }
        ?>
    </tbody>
</table>