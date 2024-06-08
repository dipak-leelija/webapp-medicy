<li class="nav-item <?= $page == "lab-tests" || $page ==  "test-appointments" || $page ==  "test-reports" || $page == "test-report-generate" ? "active" : ''; ?>">
    <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseTest" aria-expanded="<?= $page ==  "lab-tests" || $page ==  "test-appointments" || $page ==  "test-reports" ? "true" : ''; ?>" aria-controls="collapsePages">
        <i class="fas fa-vial"></i>
        <span>Lab Tests</span>
    </a>

    <div id="collapseTest" class="collapse <?= $page ==  "lab-tests" || $page ==  "test-appointments" || $page ==  "test-reports" || $page == "single-lab-page" || $page == "add-patient" || $page == "lab-patient-selection" || $page == "lab-billing" || $page == "tests-bill-invoice" || $page == "test-report-generate" || $page == 'edit-lab-billing' ? "show" : ''; ?>" aria-labelledby="headingPages" data-parent="#accordionSidebar">
        <div class="bg-white py-2 collapse-inner rounded">
            <a class="collapse-item <?= $page ==  "lab-tests" || $page == "single-lab-page" ? "active" : ''; ?>" href="lab-tests.php">Avilable
                Tests</a>
            <a class="collapse-item <?= $page ==  "test-appointments" || $page == "add-patient" || $page == "lab-patient-selection" || $page == "lab-billing" || $page == "tests-bill-invoice" || $page == "test-report-generate" || $page == 'edit-lab-billing' ? "active" : ''; ?>" href="test-appointments.php">Test Bill Details</a>
            <a class="collapse-item <?= $page ==  "test-reports" ? "active" : ''; ?>" href="test-reports.php">Test Reports</a>
        </div>
    </div>

</li>