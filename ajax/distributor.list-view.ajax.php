<?php
require_once dirname(__DIR__) . '/config/constant.php';
require_once ROOT_DIR . '_config/sessionCheck.php'; //check admin loggedin or not

require_once CLASS_DIR . 'dbconnect.php';
require_once CLASS_DIR . 'distributor.class.php';

$Distributor        = new Distributor();

if (isset($_GET['match'])) {

    $match = $_GET['match'];
    // echo $match;
    if ($match == 'all') {
        $showDistributor    = json_decode($Distributor->distributorSearch($match));
    } else {
        $showDistributor    = json_decode($Distributor->distributorSearch($match));
    }

    if ($showDistributor->status == 1) {
        $showDistributor = $showDistributor->data;
        // print_r($showmanufacturer);
        foreach ($showDistributor as $eachDistributor) {
            echo "<div class='p-1 border-bottom list' id='$eachDistributor->id' onclick='setDistributor(this)'>
            $eachDistributor->name
            </div>";
        }
    } else {
        echo "<p class='text-center font-weight-bold'>Distributor Not Found!</p>";
        // echo "<div class='p-1 border-bottom list'> $match </div>";
    }
} elseif (isset($_POST['search'])) {
    $match = isset($_POST['search']) ? $_POST['search'] : $adminId;

    if ($match == 'all') {
        $showDistributor    = json_decode($Distributor->distCardSearch($match, $adminId));
    } else {
        $showDistributor    = json_decode($Distributor->distCardSearch($match, $adminId));
    }


    if ($showDistributor->status == 1) {
        $showDistributor = $showDistributor->data;
        // print_r($showmanufacturer);
        // foreach (showDistributor as $eachDistributor) {
        //     echo "<div class='p-1 border-bottom list' id='$eachDistributor->id' onclick='setManufacturer(this)'>
        //     $eachManufacturer->name
        //     </div>";
        // }
    } else {
        echo "<p class='text-center font-weight-bold'>Distributor Not Found!</p>";
        // echo "<div class='p-1 border-bottom list'> $match </div>";
    }

?>


    <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
        <thead>
            <tr>
                <th>SL.</th>
                <th>Name</th>
                <th>Contact</th>
                <th>Area PIN</th>
                <th>Status</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php
            if (is_array($showDistributor)) {
                $rowCount = 0;
                foreach ($showDistributor as $rowDistributor) {
                    $distributorId      = $rowDistributor->id;
                    $distributorName    = $rowDistributor->name;
                    $distributorPhno    = $rowDistributor->phno;
                    $distributorPin     = $rowDistributor->area_pin_code;
                    $distributorStatus  = $rowDistributor->status;

                    $statusLabel = '';
                    $statusColor = '';
                    switch ($distributorStatus) {
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
                    <td>' . $distributorId . '</td>
                    <td>' . $distributorName . '</td>
                    <td>' . $distributorPhno . '</td>
                    <td>' . $distributorPin . '</td>
                    <td style="color: ' . $statusColor . ';">' . $statusLabel . '</td>
                    <td>
                        <a class="mx-1" data-toggle="modal" data-target="#distRequestModal" onclick="distViewAndEdit(' . $distributorId . ')"><i class="fas fa-edit"
                    </td>
                   </tr>';
                    $rowCount++;
                    // if ($rowCount == 5) {
                    //     break;
                    // }
                }
                // echo $rowCount;
            }
            ?>
        </tbody>
    </table>

    <!-- Manufacturer View and Edit Modal -->
    <div class="modal fade" id="distRequestModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" data-backdrop="static">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">View and Edit Distributor Details</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body distRequestModal">
                    <!-- Details Appeare Here by Ajax  -->
                </div>
            </div>
        </div>
    </div>

    <script>
        //View and Edit Manufacturer function
        distViewAndEdit = (distributorId) => {
            let ViewAndEdit = distributorId;
            let url = "ajax/distributor.request.ajax.php?Id=" + ViewAndEdit;
            $(".distRequestModal").html(
                '<iframe width="99%" height="530px" frameborder="0" allowtransparency="true" src="' +
                url + '"></iframe>');
        } // end of viewAndEdit function
    </script>

<?php

}

?>