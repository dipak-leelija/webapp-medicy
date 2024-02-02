<?php
require_once dirname(__DIR__) . '/config/constant.php';
require_once ROOT_DIR . '_config/sessionCheck.php'; //check admin loggedin or not

require_once CLASS_DIR . 'dbconnect.php';
// require_once CLASS_DIR.'manufacturer.class.php';
require_once CLASS_DIR . 'measureOfUnit.class.php';


// $match = $_POST['search'];
$match = isset($_POST['search']) ? $_POST['search'] : $adminId;
// echo $match;
$prodUnit       = new MeasureOfUnits();

if ($match == 'all') {
    $showProdUnit = json_decode($prodUnit->prodUnitCardSearch($match, $adminId));
    // print_r($showProdUnit);
} else {
    $showProdUnit    = json_decode($prodUnit->prodUnitCardSearch($match, $adminId));
}


if ($showProdUnit->status) {
    $showProdUnit = $showProdUnit->data;
} else {
    echo "<p class='text-center font-weight-bold'>Manufacturer Not Found!</p>";
    // echo "<div class='p-1 border-bottom list'> $match </div>";
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
                            <button class="btn btn-sm btn-transparent text-primary" data-bs-target="#prodUnitReqModal" data-bs-toggle="modal" data-bs-dismiss="modal" onclick="unitViewAndEdit(' . $prodUnitId . ')"><i class="fas fa-edit"></i></button>
                        </td>
                       </tr>';
            }
        }
        ?>
    </tbody>
</table>


<script>
    //View and Edit Manufacturer function
    unitViewAndEdit = (unitId) => {
        let ViewAndEdit = unitId;
        let url = "ajax/unit.View.ajax.php?Id=" + ViewAndEdit;
        $(".prodUnitReqModal").html(
            '<iframe width="99%" height="250px" frameborder="0" allowtransparency="true" src="' +
            url + '"></iframe>');
    }

    // function closeModal() {
    //     $('#unitModal').modal('hide');
    // }
</script>