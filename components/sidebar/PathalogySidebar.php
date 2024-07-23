<li class="nav-item <?= $page ==  "lab-tests" || $page == "single-lab-page" ? "active" : ''; ?>">
    <a class="nav-link collapsed" href="lab-tests.php">
        <i class="fas fa-vial"></i>
        <span>Avilable Tests</span>
    </a>
</li>

<li class="nav-item <?= $page ==  "test-appointments" || $page == "add-patient" || $page == "lab-patient-selection" || $page == "lab-billing" || $page == "tests-bill-invoice" || $page == "test-report-generate" || $page == 'edit-lab-billing' ? "active" : ''; ?>">
    <a class="nav-link collapsed" href="test-appointments.php">
        <i class="fas fa-vial"></i>
        <span>Test Invoices</span>
    </a>
</li>

<li class="nav-item <?= $page ==  "test-reports" ? "active" : ''; ?>">
    <a class="nav-link collapsed" href="test-reports.php">
        <i class="fas fa-vial"></i>
        <span>Pathalogy Report</span>
    </a>
</li>