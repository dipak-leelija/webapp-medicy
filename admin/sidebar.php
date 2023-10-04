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


        <!-- Heading -->
        <div class="sidebar-heading">
            Pharmacy
        </div>

        
        <!-- Products -->
        <li class="nav-item <?php if($page ==  "products" || $page ==  "add-products"){ echo "active";} ?>">
                <a active class="nav-link <?php if($page !=  "sales"){ echo "collapsed";} ?>" href="#" data-toggle="collapse" data-target="#productsManagement"
                    aria-expanded="<?php if($page ==  "products" || $page ==  "add-products"){ echo "true";} ?>" aria-controls="productsManagement">
                    <i class="fas fa-pills"></i>
                    <span>Products</span>
                </a>
                <div id="productsManagement" class="collapse <?php if($page ==  "products" ||  $page ==  "add-products"){ echo "show";} ?>" aria-labelledby="headingUtilities"
                    data-parent="#accordionSidebar">
                    <div class="bg-white py-2 collapse-inner rounded">
                        <a class="collapse-item <?php if($page ==  "products" ){ echo "active";} ?>" href="products.php">All Products </a>
                        <a class="collapse-item <?php if($page ==  "add-products"){ echo "active";} ?>" href="add-products.php ">Add Product</a>
                    </div>
                </div>
            </li>
        <!--/end Products Menu  -->


        <!-- Product Management collapsed Menu  -->
        <li class="nav-item <?php if($page ==  "sales" || $page ==  "sales-returns"){ echo "active";} ?>">
                <a active class="nav-link <?php if($page !=  "sales"){ echo "collapsed";} ?>" href="#" data-toggle="collapse" data-target="#collapseSalesManagement"
                    aria-expanded="<?php if($page ==  "sales" || $page ==  "sales-returns"){ echo "true";} ?>" aria-controls="collapseSalesManagement">
                    <i class="fas fa-clinic-medical"></i>
                    <span>Sales Management</span>
                </a>
                <div id="collapseSalesManagement" class="collapse <?php if($page ==  "sales" ||  $page ==  "sales-returns"){ echo "show";} ?>" aria-labelledby="headingUtilities"
                    data-parent="#accordionSidebar">
                    <div class="bg-white py-2 collapse-inner rounded">
                        <a class="collapse-item <?php if($page ==  "sales" ){ echo "active";} ?>" href="sales.php">Sales </a>
                        <a class="collapse-item <?php if($page ==  "sales-returns"){ echo "active";} ?>" href="sales-returns.php ">Returns </a>
                    </div>
                </div>
            </li>
        <!--/end Purchase Master collapsed Menu  -->


        <!-- Purchase Management  -->
        <li class="nav-item <?php if($page ==  "stock-in" || $page == "stock-return"){ echo "active";} ?>">
                <a active class="nav-link <?php if($page !=  "stock-in"){ echo "collapsed";} ?>" href="#" data-toggle="collapse" data-target="#collapsePurchaseManagement"
                    aria-expanded="<?php if($page ==  "stock-in" || $page == "stock-return"){ echo "true";} ?>" aria-controls="collapsePurchaseManagement">
                    <i class="fas fa-store-alt"></i>
                    <span>Purchase Management</span>
                </a>
                <div id="collapsePurchaseManagement" class="collapse <?php if($page ==  "stock-in" || $page == "stock-return"){ echo "show";} ?>" aria-labelledby="headingUtilities"
                    data-parent="#accordionSidebar">
                    <div class="bg-white py-2 collapse-inner rounded">
                        <h6 class="collapse-header">Purchase Management:</h6>
                        <a class="collapse-item <?php if($page ==  "stock-in"){ echo "active";} ?>" href="stock-in.php ">Purchase </a>
                        <a class="collapse-item <?php if($page ==  "stock-return"){ echo "active";} ?>"" href="stock-return.php">Purchase Return</a>
                    </div>
                </div>
            </li>
        <!--/end Purchase Master collapsed Menu  -->


        <!-- Product Management collapsed Menu  -->
        <li class="nav-item <?php if($page ==  "current-stock" || $page ==  "stock-expiring" || $page ==  "stock-in-details"){ echo "active";} ?>">
                <a class="nav-link <?php if($page !=  "current-stock"){ echo "collapsed";} ?>" href="#" data-toggle="collapse" data-target="#collapseStock"
                    aria-expanded="<?php if($page ==  "current-stock" || $page ==  "stock-expiring" || $page ==  "stock-in-details"){ echo "true";} ?>" aria-controls="collapseStock">
                    <i class="fas fa-store-alt"></i>
                    <span>Stock Details</span>
                </a>
                <div id="collapseStock" class="collapse <?php if($page ==  "current-stock" || $page ==  "stock-expiring" || $page ==  "stock-in-details"){ echo "show";} ?>" aria-labelledby="headingUtilities"
                    data-parent="#accordionSidebar">
                    <div class="bg-white py-2 collapse-inner rounded">
                        <h6 class="collapse-header">Purchase Master:</h6>
                        <a class="collapse-item <?php if($page ==  "current-stock"){ echo "active";} ?>" href="current-stock.php">Current Stock </a>
                        <a class="collapse-item <?php if($page ==  "stock-in-details"){ echo "active";} ?>" href="stock-in-details.php">StockIn Details </a>
                        <a class="collapse-item <?php if($page ==  "stock-expiring"){ echo "active";} ?>" href="stock-expiring.php">Stock Expiring </a>
                    </div>
                </div>
            </li>


        <!--/end Purchase Master collapsed Menu  -->


        <!-- Purchase Master collapsed Menu  -->
        <li class="nav-item <?php if($page ==  "distributor" || $page ==  "manufacturer" || $page ==  "pack-unit" || $page ==  "product-unit"){ echo "active";} ?>">
                <a class="nav-link <?php if($page !=  "distributor"){ echo "collapsed";} ?>" href="#" data-toggle="collapse" data-target="#collapseUtilities"
                    aria-expanded="<?php if($page ==  "distributor" || $page ==  "manufacturer" || $page ==  "pack-unit" || $page ==  "product-unit"){ echo "true";} ?>" aria-controls="collapseUtilities">
                    <i class="fas fa-shopping-basket"></i>
                    <span>Purchase Master</span>
                </a>
                <div id="collapseUtilities" class="collapse <?php if($page ==  "distributor" || $page ==  "manufacturer" || $page ==  "pack-unit" || $page ==  "product-unit"){ echo "show";} ?>" aria-labelledby="headingUtilities"
                    data-parent="#accordionSidebar">
                    <div class="bg-white py-2 collapse-inner rounded">
                        <h6 class="collapse-header">Purchase Master:</h6>
                        <a class="collapse-item <?php if($page ==  "distributor"){ echo "active";} ?>" href="distributor.php">Distributor </a>
                        <a class="collapse-item <?php if($page ==  "manufacturer"){ echo "active";} ?>" href="manufacturers.php">Manufacturer </a>
                        <a class="collapse-item <?php if($page ==  "pack-unit"){ echo "active";} ?>" href="packaging-unit.php">Packageing Unit </a>
                        <a class="collapse-item <?php if($page ==  "product-unit"){ echo "active";} ?>" href="product-unit.php">Product Unit </a>
                    </div>
                </div>
            </li>
        <!--/end Purchase Master collapsed Menu  -->

        <!-- Sidebar Toggler (Sidebar) -->

        <div class="text-center d-none d-md-inline">

            <button class="rounded-circle border-0" id="sidebarToggle"></button>

        </div>



    </ul>

    <!-- End of Sidebar -->