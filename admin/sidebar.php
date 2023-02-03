     <!-- Sidebar -->

    <ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">



        <!-- Sidebar - Brand -->

        <a class="sidebar-brand d-flex align-items-center justify-content-center" href="index.php">

            <div class="sidebar-brand-icon rotate-n-15">

                <!-- <i class="fas fa-laugh-wink"></i> -->

            </div>

            <div class="text-center"><img class="img-fluid img-thumbnail"src="img/logo.png" alt="" ></div>

        </a>



        <!-- Divider -->

        <hr class="sidebar-divider my-0">



        <!-- Nav Item - Dashboard -->

        <li class="nav-item <?php if($page ==  "dashboard"){ echo "active";} ?>">
            <a class="nav-link" href="index.php">
                <i class="fas fa-fw fa-tachometer-alt"></i>
                <span>Dashboard</span></a>
        </li>

        <!-- Divider -->
        <hr class="sidebar-divider">

        <!-- Heading -->

        <div class="sidebar-heading">
            Interface
        </div>

        <!-- Nav Item - Pages Collapse Menu -->
        <li class="nav-item <?php if($page ==  "helth-care"){ echo "active";} ?>">
            <a class="nav-link collapsed" href="helth-care.php">
                <i class="fas fa-fw fa-cog"></i>
                <span>Health Care Details</span>
            </a>
        </li>

        <li class="nav-item <?php if($page ==  "appointments"){ echo "active";} ?>">
            <a class="nav-link collapsed" href="appointments.php">
                <i class="fas fa-fw fa-cog"></i>
                <span>Appointments</span>
            </a>
        </li>


        <!-- Lab section  -->

        <li class="nav-item <?php if($page ==  "lab-tests" || $page ==  "test-appointments"){ echo "active";} ?>">
            <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseTest"
                aria-expanded="true" aria-controls="collapsePages">
                <i class="fas fa-vial"></i>
                <span>Lab Tests</span>
            </a>

            <div id="collapseTest" class="collapse" aria-labelledby="headingPages" data-parent="#accordionSidebar">
                <div class="bg-white py-2 collapse-inner rounded">
                    <!-- <h6 class="collapse-header">Login Screens:</h6> -->
                    <a class="collapse-item" href="lab-tests.php">Avilable Tests</a>
                    <a class="collapse-item" href="test-appointments.php">Test Bill Details</a>
                </div>
            </div>

        </li>

        <!-- Doctors in Employees -->

        <li class="nav-item <?php if($page ==  "employees"){ echo "active";} ?>">
            <a class="nav-link" href="employees.php">
                <i class="fas fa-users"></i>
                <span>Employees</span></a>
        </li>

        <!-- Nav Item - Doctors -->
        <li class="nav-item <?php if($page ==  "doctors"){ echo "active";} ?>">
            <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseDoctor"
                aria-expanded="true" aria-controls="collapsePages">
                <i class="fas fa fa-users"></i>
                <span>Doctors</span>
            </a>

            <div id="collapseDoctor" class="collapse" aria-labelledby="headingPages" data-parent="#accordionSidebar">
                <div class="bg-white py-2 collapse-inner rounded">
                    <a class="collapse-item" href="doctors.php">Doctors</a>
                    <a class="collapse-item" href="doctor-specialization.php">Specializations</a>
                </div>
            </div>

        </li>










        <!-- Nav Item - Utilities Collapse Menu -->

        <!-- Divider -->
        <hr class="sidebar-divider">

        <!-- Heading -->
        <div class="sidebar-heading">
            Addons
        </div>



        <!-- Nav Item - Pages Collapse Menu -->

        <li class="nav-item">
            <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapsePages"
                aria-expanded="true" aria-controls="collapsePages">
                <i class="fas fa-fw fa-folder"></i>
                <span>Pages</span>
            </a>

            <div id="collapsePages" class="collapse" aria-labelledby="headingPages" data-parent="#accordionSidebar">
                <div class="bg-white py-2 collapse-inner rounded">
                    <h6 class="collapse-header">Login Screens:</h6>
                    <a class="collapse-item" href="login.php">Login</a>
                    <a class="collapse-item" href="register.php">Register</a>
                    <a class="collapse-item" href="forgot-password.php">Forgot Password</a>
                    <div class="collapse-divider"></div>
                    <h6 class="collapse-header">Other Pages:</h6>
                    <a class="collapse-item" href="404.php">404 Page</a>
                    <a class="collapse-item" href="blank.php">Blank Page</a>
                </div>
            </div>

        </li>



        <!-- Divider -->

        <hr class="sidebar-divider d-none d-md-block">



        <!-- Sidebar Toggler (Sidebar) -->

        <div class="text-center d-none d-md-inline">

            <button class="rounded-circle border-0" id="sidebarToggle"></button>

        </div>



    </ul>

    <!-- End of Sidebar -->