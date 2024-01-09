<div class="card border-left-primary shadow h-100 py-2">
    <div class="card-body">
        <div class="row no-gutters align-items-center">
            <div class="col mr-2">
                <div class="d-flex justify-content-between align-items-start">
                    <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                        Total Appointments</div>
                    <div class="col-auto mr-n3">
                        <i class="fas fa-calendar-check"></i>
                    </div>
                </div>
                <!-- <div class="h5 mb-0 font-weight-bold text-gray-800">
                    <?php // echo $totalAppointmentsCount; ?> 
                </div> -->
            </div>

        </div>
        <div class="h5 mb-0 font-weight-bold text-gray-800"> </div>
        <p class="mb-0 pb-0">
            <small class="mb-0 pb-0"> Doctor Appointments: <?= ($labAppointment > 0) ? $labAppointment : '0'; ?></small>
            <br>
            <small class="mb-0 pb-0"> Lab Appointments: <?= ($labAppointment > 0) ? $labAppointment : '0'; ?></small>
        </p>
    </div>
</div>