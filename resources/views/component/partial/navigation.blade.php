<ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">

    <!-- Sidebar - Brand -->
    <a class="sidebar-brand d-flex align-items-center justify-content-center" href="index.html">
        <div class="sidebar-brand-icon rotate-n-15">
            <i class="fas fa-laugh-wink"></i>
        </div>
        <div class="sidebar-brand-text mx-3">KasirKu</div>
    </a>

    <!-- Divider -->
    <hr class="sidebar-divider my-0">

    <!-- Nav Item - Dashboard -->
    <li class="nav-item @if (Route::is('dashboard')) active @endif">
        <a class="nav-link" href="{{ route('dashboard') }}">
            <i class="fas fa-fw fa-tachometer-alt"></i>
            <span>Dashboard</span></a>
    </li>
    <li class="nav-item @if (Route::is(['cabang.index', 'cabang.input_store', 'cabang.edit_store'])) active @endif">
        <a class="nav-link" href="{{ route('cabang.index') }}">
            <i class="fas fa-fw fa-archive"></i>
            <span>Cabang Store</span></a>
    </li>

    <!-- Nav Item - Pages Collapse Menu -->
    <li class="nav-item @if (Route::is([
            'brand.index',
            'brand.input_brand',
            'brand.edit_brand',
            'categories.index',
            'categories.input_category',
            'categories.edit_category',
            'supplier.index',
            'supplier.input_supplier',
            'supplier.edit_supplier',
        ])) active @endif">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseTwo"
            aria-expanded="true" aria-controls="collapseTwo">
            <i class="fas fa-fw fa-wrench"></i>
            <span>Master</span>
        </a>
        <div id="collapseTwo" class="collapse @if (Route::is([
                'brand.index',
                'brand.input_brand',
                'brand.edit_brand',
                'categories.index',
                'categories.input_category',
                'categories.edit_category',
                'supplier.index',
                'supplier.input_supplier',
                'supplier.edit_supplier',
            ])) show @endif"
            aria-labelledby="headingTwo" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                <h6 class="collapse-header">Master List :</h6>
                <a class="collapse-item @if (Route::is(['brand.index', 'brand.input_brand', 'brand.edit_brand'])) active @endif"
                    href="{{ route('brand.index') }}">Merek</a>
                <a class="collapse-item @if (Route::is(['categories.index', 'categories.input_category', 'categories.edit_category'])) active @endif"
                    href="{{ route('categories.index') }}">Kategori
                    Produk</a>
                <a class="collapse-item @if (Route::is(['supplier.index', 'supplier.input_supplier', 'supplier.edit_supplier'])) active @endif"
                    href="{{ route('supplier.index') }}">Supplier</a>
            </div>
        </div>
    </li>
    <li class="nav-item @if (Route::is(['customer.index', 'customer.input_customer', 'customer.edit_customer', 'customer.balance_customer'])) active @endif">
        <a class="nav-link" href="{{ route('customer.index') }}">
            <i class="fas fa-fw fa-users"></i>
            <span>Customer</span></a>
    </li>
    <li class="nav-item @if (Route::is([
            'product.index',
            'product.create_product',
            'product.edit_product',
            'product.stock_opname.index',
            'product.stock_opname.create_stock_opname',
        ])) active @endif">
        <a class="nav-link" href="{{ route('product.index') }}">
            <i class="fas fa-fw fa-dumpster-fire"></i>
            <span>Product</span></a>
    </li>
    <li class="nav-item @if (Route::is(['cashier.index', 'cashier.sale_info'])) active @endif">
        <a class="nav-link" href="{{ route('cashier.index') }}">
            <i class="fas fa-fw fa-cash-register"></i>
            <span>Kasir</span></a>
    </li>
    <li class="nav-item @if (Route::is([
            'sale_cancel.index',
            'sale_returns.index',
            'sale_returns.create_sale_return',
            'sale_returns.detail_sale_return',
        ])) active @endif">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#saled" aria-expanded="true"
            aria-controls="collapseTwo">
            <i class="fas fa-fw fa-shopping-cart"></i>
            <span>Penjualan</span>
        </a>
        <div id="saled" class="collapse @if (Route::is([
                'sale_cancel.index',
                'sale_returns.index',
                'sale_returns.create_sale_return',
                'sale_returns.detail_sale_return',
            ])) show @endif"
            aria-labelledby="headingTwo" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                <h6 class="collapse-header">Penjualan List :</h6>
                <a class="collapse-item @if (Route::is(['sale_cancel.index'])) active @endif"
                    href="{{ route('sale_cancel.index') }}">Cancel</a>
                <a class="collapse-item @if (Route::is(['sale_returns.index', 'sale_returns.create_sale_return', 'sale_returns.detail_sale_return'])) active @endif"
                    href="{{ route('sale_returns.index') }}">Return</a>
            </div>
        </div>
    </li>
    <li class="nav-item @if (Route::is([
            'sale_cancel.index',
            'sale_returns.index',
            'sale_returns.create_sale_return',
            'sale_returns.detail_sale_return',
        ])) active @endif">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#report" aria-expanded="true"
            aria-controls="collapseTwo">
            <i class="fas fa-fw fa-file"></i>
            <span>Laporan</span>
        </a>
        <div id="report" class="collapse @if (Route::is([
                'sale_cancel.index',
                'sale_returns.index',
                'sale_returns.create_sale_return',
                'sale_returns.detail_sale_return',
            ])) show @endif"
            aria-labelledby="headingTwo" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                <h6 class="collapse-header">Laporan List :</h6>
                <a class="collapse-item @if (Route::is(['sale_cancel.index'])) active @endif"
                    href="{{ route('report.report_sales') }}">Laporan Penjualan</a>
                <a class="collapse-item @if (Route::is(['sale_returns.index', 'sale_returns.create_sale_return', 'sale_returns.detail_sale_return'])) active @endif"
                    href="{{ route('sale_returns.index') }}">Laporan Jatuh Tempo</a>
                <a class="collapse-item @if (Route::is(['sale_returns.index', 'sale_returns.create_sale_return', 'sale_returns.detail_sale_return'])) active @endif"
                    href="{{ route('sale_returns.index') }}">Laporan Laba Rugi</a>
            </div>
        </div>
    </li>
    {{-- <!-- Divider -->
    <hr class="sidebar-divider">

    <!-- Heading -->
    <div class="sidebar-heading">
        Addons
    </div>

    <!-- Nav Item - Tables -->
    <li class="nav-item">
        <a class="nav-link" href="tables.html">
            <i class="fas fa-fw fa-table"></i>
            <span>Tables</span></a>
    </li> --}}

    <hr class="sidebar-divider d-none d-md-block">
    <div class="text-center d-none d-md-inline">
        <button class="rounded-circle border-0" id="sidebarToggle"></button>
    </div>


</ul>
