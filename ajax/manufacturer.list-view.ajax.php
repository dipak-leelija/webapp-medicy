<?php
require_once dirname(__DIR__) . '/config/constant.php';
require_once ROOT_DIR . '_config/sessionCheck.php'; //check admin loggedin or not

require_once CLASS_DIR . 'dbconnect.php';
require_once CLASS_DIR . 'manufacturer.class.php';


// $match = $_POST['search'];
$match = isset($_POST['search']) ? $_POST['search'] : $adminId;

$Manufacturer        = new Manufacturer();

if ($match == 'all') {
    $showmanufacturer   = json_decode($Manufacturer->manufCardSearch($match, $adminId));
} else {
    $showmanufacturer   = json_decode($Manufacturer->manufCardSearch($match, $adminId));
}


if ($showmanufacturer->status) {
    $showmanufacturer = $showmanufacturer->data;
    // print_r($showmanufacturer);
    // foreach ($showmanufacturer as $eachManufacturer) {
    //     echo "<div class='p-1 border-bottom list' id='$eachManufacturer->id' onclick='setManufacturer(this)'>
    //     $eachManufacturer->name
    //     </div>";
    // }
} else {
    // echo "<p class='text-center font-weight-bold'>manufacturerNot Found!</p>";
    echo "<div class='p-1 border-bottom list'> $match </div>";
}
?>

<table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
    <thead>
        <tr>
            <th>SL.</th>
            <th>Name</th>
            <th>Description</th>
            <th>Status</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody>
        <?php
        if (is_array($showmanufacturer)) {
            foreach ($showmanufacturer as $rowmanufacturer) {
                $manufacturerId      = $rowmanufacturer->id;
                $manufacturerName    = $rowmanufacturer->name;
                $manufacturerDsc     = $rowmanufacturer->dsc;
                $manufacturerStatus  = $rowmanufacturer->status;

                $statusLabel = '';
                $statusColor = '';
                switch ($manufacturerStatus) {
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
                        <td>' . $manufacturerId  . '</td>
                        <td>' . $manufacturerName . '</td>
                        <td>' . $manufacturerDsc . '</td>
                        <td style="color: ' . $statusColor . ';">' . $statusLabel . '</td>
                        <td>
                            <button class="btn btn-sm btn-transparent text-primary" data-bs-target="#manufacturerModal" data-bs-toggle="modal" data-bs-dismiss="modal" onclick="manufacturerRequest(' . $manufacturerId . ')"><i class="fas fa-edit"></i></button>
                        </td>
                       </tr>';
            }
        }
        ?>
    </tbody>
</table>


<script>
    const manufacturerRequest = (manufacturerId) => {
        let ViewAndEdit = manufacturerId;
        let url = "ajax/manufacturer.request.ajax.php?Id=" + ViewAndEdit;
        $(".manufacturerModal").html(
            '<iframe width="99%" height="330px" frameborder="0" allowtransparency="true" src="' +
            url + '"></iframe>');
    }

    // function closeModal() {
    //     $('#manufacturerModal').modal('hide');
    // }
</script>