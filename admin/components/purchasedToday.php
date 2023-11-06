<div class="card border-left-warning h-100 py-2 pending_border animated--grow-in">
    <div class="d-flex justify-content-end px-2">
        <div class="btn-group">
            <button type="button" class="btn btn-sm btn-outline-light text-dark card-btn dropdown"
                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                ...
            </button>
            <div class="dropdown-menu dropdown-menu-right">
                <button class="dropdown-item" type="button" id="sodCurrentDt" onclick="chkPurchase(this.id)">Today</button>
                <button class="dropdown-item" type="button" id="sodLst24hrs" onclick="chkPurchase(this.id)">Last 24 hrs</button>
                <button class="dropdown-item" type="button" id="sodLst7" onclick="chkPurchase(this.id)">Last 7 Days</button>
                <button class="dropdown-item" type="button" id="sodLst30" onclick="chkPurchase(this.id)">Last 30 Days</button>
                <button class="dropdown-item" type="button" id="sodGvnDt" onclick="chkPurchase(this.id)">By Date</button>
                <button class="dropdown-item" type="button" id="sodDtRng" onclick="chkPurchase(this.id)">By Date Range</button>
            </div>
        </div>
    </div>
    <div class="card-body pb-0">
        <div class="row no-gutters align-items-center">
            <div class="col mr-2">
                <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                    Purchased today
                </div>
                <div class="h5 mb-0 font-weight-bold text-gray-800">
                
                </div>
                <p class="mb-0 pb-0"><small class="mb-0 pb-0"></small></p>
            </div>
            <div class="col-auto">
                <i class="fas fa-rupee-sign"></i>
            </div>
        </div>
    </div>
</div>