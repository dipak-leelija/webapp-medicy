    <?php
    $currentURL = $_SERVER['REQUEST_URI'];
    $url        = substr($currentURL, strrpos($currentURL, '/') + 1);
    $parts      = explode('.', $url);
    $updatedUrl = $parts[0];
    $page       = $updatedUrl;
    ?>

    <div>
    <!-- Sidebar -->
    <ul class="navbar-nav bg-gradient-primary sidebar sidebarExpand sidebar-dark accordion" id="accordionSidebar">

        <!-- Sidebar - Brand -->
        <a class="sidebar-brand sidebarExpand-brand d-flex align-items-center justify-content-center" href="<?= URL ?>">
            <div class="sidebar-brand-icon sidebarExpand-brand-icon">
                <div class="text-center"><img class="img-fluid" src="<?= IMAGES_PATH ?>logo.png" alt="">
                </div>
            </div>
        </a>

        <!-- Divider -->
        <hr class="sidebar-divider sidebarExpand-devider my-0">

        <!-- <div class="text-center d-none d-md-inline">
            <button class="rounded-circle border-0" id="sidebarToggle"></button>
        </div> -->

        <!-- Nav Item For Healthcare -->
        <li class="nav-item <?= $currentURL  ==  LOCAL_DIR ? "active" : ''; ?>">
            <a class="nav-link" href="<?= URL ?>">
                <i class="fas fa-fw fa-tachometer-alt"></i>
                <span>Dashboard</span></a>
        </li>

        <!-- Divider -->
        <hr class="sidebar-divider sidebarExpand-devider">


        <!-- ==================================== OPD AREA START ==================================== -->
        <?php
        if ($userRole == 2 || $userRole == 'ADMIN') : ?>

            <!-- Heading -->
            <div class="sidebar-heading sidebarExpand-heading">
                Health Care
            </div>

            <!-- Nav Item - Pages Collapse Menu -->
            <li class="nav-item <?= $page ==  "appointments" || $page == "add-patient" || $page == "patient-selection" ? "active" : ''; ?>">
                <a class="nav-link collapsed" href="appointments.php">
                    <i class="fas fa-fw fa-calendar"></i>
                    <span>Appointments</span>
                </a>
            </li>
        <?php endif; ?>
        <!-- ==================================== OPD AREA START ==================================== -->
        <?php if ($userRole == 2 || $userRole == 3 || $userRole == 'ADMIN') : ?>

            <!-- Nav Item - Doctors -->
            <li class="nav-item <?= $page ==  "doctors" ||  $page ==  "doc-specialization" ? "active" : ''; ?>">
                <a class="nav-link collapsed" id="sidebarExp" href="#" data-toggle="collapse" data-target="#collapseDoctor" aria-expanded="<?= $page ==  "doctors" ||  $page ==  "doc-specialization" ? "true" : ''; ?>" aria-controls="collapsePages">
                    <i class="fas fa fa-users"></i>
                    <span>Doctors</span>
                </a>

                <div id="collapseDoctor"  class="collapse <?= $page ==  "doctors" ||  $page ==  "doc-specialization" || $page == "doctor-specialization" ? "show" : ''; ?>" aria-labelledby="headingPages" data-parent="#accordionSidebar">
                    <div class="bg-white py-2 collapse-inner rounded">
                        <a class="collapse-item <?= $page ==  "doctors" || $page == "doctors" ? "active" : ''; ?>" href="doctors.php">Doctors</a>
                        <a class="collapse-item <?= $page ==  "doc-specialization"  || $page == "doctor-specialization" ? "active" : ''; ?>" href="doctor-specialization.php">Specializations</a>
                    </div>
                </div>
            </li>

            <!-- Nav Item - Employees -->
            <li class="nav-item <?= $page ==  "patients" || $page == 'patient-details' ? "active" : '' ?>">
                <a class="nav-link" href="patients.php">
                    <i class="fas fa-users"></i>
                    <span>Patients</span></a>
            </li>

        <?php endif; ?>

        <!-- ==================================== PATHALOGY AREA START ==================================== -->
        <hr class="sidebar-divider sidebarExpand-devider d-none d-md-block">
        <div class="sidebar-heading sidebarExpand-heading">Pathalogy Lab</div>

        <?php if ($userRole == 3 || $userRole == 'ADMIN') :
            include ROOT_COMPONENT . '/sidebar/PathalogySidebar.php';
        endif; ?>
        <!-- ==================================== PATHALOGY AREA END ==================================== -->


        <!-- ==================================== PHARMACY AREA START ==================================== -->
        <?php if ($userRole == 1 || $userRole == 'ADMIN') : ?>

            <!-- Divider -->
            <hr class="sidebar-divider sidebarExpand-devider d-none d-md-block">

            <!-- Heading -->
            <div class="sidebar-heading sidebarExpand-heading">Pharmacy</div>
            <?php include ROOT_COMPONENT . '/sidebar/PharmacySidebar.php'; ?>

        <?php endif; ?>
        <!-- ==================================== PHARMACY AREA END ==================================== -->


        <?php if ($userRole == 'ADMIN') : ?>
            <!-- TICKET MENUE -->
            <li class="nav-item <?= $page ==  "ticket-details" ? "active" : ''; ?>">
                <a class="nav-link collapsed" href="ticket-details.php">
                    <i class="fas fa-hand-paper"></i>
                    <span>All Ticket</span>
                </a>
            </li>
        <?php endif; ?>

    </ul>
    <div class="text-center d-none d-md-inline">
            <button class="rounded border-0 " id="sidebarToggle"></button>
        </div>
    </div>
    <!-- End of Sidebar -->

    <!-- ########################################################## -->
    <script src="<?php echo JS_PATH; ?>custom/sidebar.js"></script>
