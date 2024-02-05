<?php
require_once dirname(__DIR__) . '/config/constant.php';
require_once ROOT_DIR . '_config/sessionCheck.php'; //check admin loggedin or not

require_once CLASS_DIR . 'dbconnect.php';
// require_once CLASS_DIR.'manufacturer.class.php';
require_once CLASS_DIR . 'packagingUnit.class.php';


// $match = $_POST['search'];
$match = isset($_POST['search']) ? $_POST['search'] : $adminId;

$packUnit       = new PackagingUnits();

if ($match == 'all') {
    $showPackUnit = json_decode($packUnit->packUnitCardSearch($match, $adminId));
} else {
    $showPackUnit    = json_decode($packUnit->packUnitCardSearch($match, $adminId));
}


if ($showPackUnit->status) {
    $showPackUnit = $showPackUnit->data;
} else {
    echo "<p class='text-center font-weight-bold'>Packaging Unit Not Found!</p>";
    // echo "<div class='p-1 border-bottom list'> $match </div>";
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
                $packUnitStatus  = $rowPackUnit->status;

                $statusLabel = '';
                $statusColor = '';
                switch ($packUnitStatus) {
                    case 2:
                        $statusLabel = 'Disabled';
                        $statusColor = 'red';
                        break;
                    case 0:
                        $statusLabel = 'Pending';
                        $statusColor = '#4e73df';
                        break;
                    case 1:
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
                            <button class="btn btn-sm btn-transparent text-primary" data-bs-target="#unitModal" data-bs-toggle="modal" data-bs-dismiss="modal" onclick="packUnitRequest(' . $packUnitId . ')"><i class="fas fa-edit"></i></button>
                        </td>
                       </tr>';
            }
        }
        ?>
    </tbody>
</table>


<script>
    // packaging unit rerquest function //
    packUnitRequest = (unitId) => {
        let ViewAndEdit = unitId;
        let url = "ajax/packagingUnit.request.ajax.php?Id=" + ViewAndEdit;
        $(".unitModal").html(
            '<iframe width="99%" height="120rem" frameborder="0" allowtransparency="true" src="' +
            url + '"></iframe>');
    }

    function closeModal() {
        $('#unitModal').modal('hide');
    }
</script>