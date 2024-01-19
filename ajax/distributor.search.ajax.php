<?php
require_once dirname(__DIR__) . '/config/constant.php';
require_once ROOT_DIR . '_config/sessionCheck.php'; //check admin loggedin or not

require_once CLASS_DIR . 'dbconnect.php';
require_once CLASS_DIR . 'distributor.class.php';


// $match = $_POST['search'];
// echo $match;
$Distributor        = new Distributor();

$showDistributor = json_decode($Distributor->showDistributor());
$showDistributor = $showDistributor->data;


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
                $distributorStatus  = $rowDistributor->dis_status;

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
                            <a class="mx-1" data-toggle="modal" data-target="#distributorModal" onclick="distViewAndEdit(' . $distributorId . ')"><i class="fas fa-edit"
                            <a class="mx-1" id="delete-btn" data-id="' . $distributorId . '"><i class="far fa-trash-alt"></i></a>
                        </td>
                       </tr>';
                $rowCount++;
                if ($rowCount == 5) {
                    break;
                }
            }
        }
        ?>
    </tbody>
</table>
