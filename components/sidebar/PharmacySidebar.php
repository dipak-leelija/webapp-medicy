<!-- Products -->
<li class="nav-item <?= $page ==  "products" || $page ==  "add-new-product" ? "active" : ''; ?>">
    <a  id="sidebarExp1" class="nav-link <?= $page !=  "sales" ? "collapsed" : ''; ?>" href="#" data-toggle="collapse" data-target="#productsManagement" aria-expanded="<?= $page ==  "products" || $page ==  "add-new-product" ? "true" : ''; ?>" aria-controls="productsManagement">
        <i class="fas fa-pills"></i>
        <span>Products</span>
    </a>
    <div id="productsManagement" class="collapse <?= $page ==  "products" ||  $page ==  "add-new-product" ? "show" : ''; ?>" aria-labelledby="headingUtilities" data-parent="#accordionSidebar">
        <div class="bg-white py-2 collapse-inner rounded">
            <a class="collapse-item <?= $page ==  "products" ? "active" : ''; ?>" href="products.php">All Products </a>
            <a class="collapse-item <?= $page ==  "add-new-product" ? "active" : ''; ?>" href="add-new-product.php ">Add Product</a>
        </div>
    </div>
</li>
<!--/end Products Menu  -->


<!-- Product Management collapsed Menu  -->
<li class="nav-item <?= $page ==  "sales" || $page ==  "sales-returns" || $page == "new-sales" || $page == "sales-returns-items" || $page == 'update-sales' || $page == 'sales-return-edit' ? "active" : ''; ?>">
    <a active id="sidebarExp2" class="nav-link <?= $page !=  "sales" ? "collapsed" : ''; ?>" href="#" data-toggle="collapse" data-target="#collapseSalesManagement" aria-expanded="<?= $page ==  "sales" || $page ==  "sales-returns" || $page == "new-sales" || $page == "sales-returns-items" || $page == 'update-sales' || $page == 'sales-return-edit' ? "true" : ''; ?>" aria-controls="collapseSalesManagement">
        <i class="fas fa-clinic-medical"></i>
        <span>Sales Management</span>
    </a>
    <div id="collapseSalesManagement" class="collapse <?= $page ==  "sales" ||  $page ==  "sales-returns" || $page == "new-sales" || $page == "sales-returns-items" || $page == 'update-sales' || $page == 'sales-return-edit' ? "show" : ''; ?>" aria-labelledby="headingUtilities" data-parent="#accordionSidebar">
        <div class="bg-white py-2 collapse-inner rounded">
            <a class="collapse-item <?= $page ==  "sales" || $page == "new-sales" || $page == 'update-sales'  ? "active" : ''; ?>" href="sales.php">Sales</a>
            <a class="collapse-item <?= $page ==  "sales-returns" || $page == "sales-returns-items" || $page == 'sales-return-edit' ? "active" : ''; ?>" href="sales-returns.php">Sales Returns</a>
        </div>
    </div>
</li>
<!--/end Purchase Master collapsed Menu  -->


<!-- Purchase Management  -->
<li class="nav-item <?= $page ==  "stock-in" || $page ==  "purchase-details" || $page == "stock-in-edit" || $page == "stock-return" || $page == "stock-return-item" ? "active" : ''; ?>">
    <a active id="sidebarExp3" class="nav-link <?= $page !=  "stock-in" || $page !=  "purchase-details" ? "collapsed" : ''; ?>" href="#" data-toggle="collapse" data-target="#collapsePurchaseManagement" aria-expanded="<?= $page ==  "stock-in" || $page ==  "purchase-details" || $page == "stock-in-edit" || $page == "stock-return" || $page == "stock-return-item" ?  "true" : ''; ?>" aria-controls="collapsePurchaseManagement">
        <i class="fas fa-store-alt"></i>
        <span>Purchase Management</span>
    </a>
    <div id="collapsePurchaseManagement" class="collapse <?= $page ==  "stock-in" || $page ==  "purchase-details" || $page == "stock-in-edit" || $page == "stock-return" || $page == "stock-return-item" ? "show" : ''; ?>" aria-labelledby="headingUtilities" data-parent="#accordionSidebar">
        <div class="bg-white py-2 collapse-inner rounded">
            <h6 class="collapse-header">Purchase Management:</h6>
            <a class="collapse-item <?= $page ==  "stock-in" ? "active" : ''; ?>" href="stock-in.php ">New </a>
            <a class="collapse-item <?= $page ==  "purchase-details" || $page == "stock-in-edit" ? "active" : ''; ?>" href="purchase-details.php ">Purchase </a>
            <a class="collapse-item <?= $page ==  "stock-return" || $page == "stock-return-item" ? "active" : ''; ?>" href="stock-return.php">Purchase Return</a>
        </div>
    </div>
</li>
<!--/end Purchase Master collapsed Menu  -->


<!-- Product Management collapsed Menu  -->
<li class="nav-item <?= $page ==  "current-stock" || $page ==  "stock-expiring" || $page ==  "stock-in-details" ?  "active" : ''; ?>">
    <a id="sidebarExp4" class="nav-link <?= $page !=  "current-stock" ? "collapsed" : ''; ?>" href="#" data-toggle="collapse" data-target="#collapseStock" aria-expanded="<?= $page ==  "current-stock" || $page ==  "stock-expiring" || $page ==  "stock-in-details" ? "true" : ''; ?>" aria-controls="collapseStock">
        <i class="fas fa-store-alt"></i>
        <span>Stock Details</span>
    </a>
    <div id="collapseStock" class="collapse <?= $page ==  "current-stock" || $page ==  "stock-expiring" || $page ==  "stock-in-details" ? "show" : ''; ?>" aria-labelledby="headingUtilities" data-parent="#accordionSidebar">
        <div class="bg-white py-2 collapse-inner rounded">
            <h6 class="collapse-header">Purchase Master:</h6>
            <a class="collapse-item <?= $page ==  "current-stock" ? "active" : ''; ?>" href="current-stock.php">
                Current Stock </a>
            <a class="collapse-item <?= $page ==  "stock-expiring" ? "active" : ""; ?>" href="stock-expiring.php">
                Stock Expiring </a>
        </div>
    </div>
</li>
<!--/end product management collapsed Menu  -->


<!-- Nav Item - Distributo -->
<li class="nav-item <?= $page ==  "distributor" ? "active" : ""; ?>">
    <a class="nav-link" href="distributor.php">
        <i class="fas fa-clinic-medical"></i>
        <span>Distributor</span></a>
</li>


<!-- Purchase Master collapsed Menu  -->
<li class="nav-item <?= $page ==  "purchesmaster" ? "active" : '' ?>">
    <a class="nav-link collapsed" href="purchesmaster.php">
        <i class="fas fa-fw fa-cog"></i>
        <span>Purchase Master</span>
    </a>
</li>


<!-- <script>
document.addEventListener('DOMContentLoaded', function() {
    var sidebar = document.getElementById('accordionSidebar');
    var buttons = ['sidebarExp1', 'sidebarExp2', 'sidebarExp3', 'sidebarExp4'];

    function toggleSidebar() {
        sidebar.classList.toggle('sidebar');
        sidebarToggle.classList.toggle('expanded');
    }

    buttons.forEach(function(buttonId) {
        var button = document.getElementById(buttonId);
        button.addEventListener('click', toggleSidebar);
    });
});
</script> -->