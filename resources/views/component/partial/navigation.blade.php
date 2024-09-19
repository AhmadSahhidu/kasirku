@php
    $roleuser = userRoleName(auth()->user()->id);
@endphp
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
    @if (validationAkses('Dashboard') || $roleuser === 'Super Admin')
        <li class="nav-item @if (Route::is('dashboard')) active @endif">
            <a class="nav-link" href="{{ route('dashboard') }}">
                <i class="fas fa-fw fa-tachometer-alt"></i>
                <span>Dashboard</span></a>
        </li>
    @endif

    @if ($roleuser === 'Super Admin')
        <li class="nav-item @if (Route::is(['cabang.index', 'cabang.input_store', 'cabang.edit_store'])) active @endif">
            <a class="nav-link" href="{{ route('cabang.index') }}">
                <i class="fas fa-fw fa-archive"></i>
                <span>Cabang Store</span></a>
        </li>
    @endif


    <!-- Nav Item - Pages Collapse Menu -->
    @php
        $aksesviewMerek = validationAkses('view data brand');
        $aksesviewCategory = validationAkses('view data category');
        $aksesviewSupplier = validationAkses('view data supplier');
    @endphp
    @if ($aksesviewMerek || $aksesviewCategory || $aksesviewSupplier || $roleuser === 'Super Admin')
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
                    @if ($aksesviewMerek || $roleuser === 'Super Admin')
                        <a class="collapse-item @if (Route::is(['brand.index', 'brand.input_brand', 'brand.edit_brand'])) active @endif"
                            href="{{ route('brand.index') }}">Merek</a>
                    @endif
                    @if ($aksesviewCategory || $roleuser === 'Super Admin')
                        <a class="collapse-item @if (Route::is(['categories.index', 'categories.input_category', 'categories.edit_category'])) active @endif"
                            href="{{ route('categories.index') }}">Kategori
                            Produk</a>
                    @endif
                    @if ($aksesviewSupplier || $roleuser === 'Super Admin')
                        <a class="collapse-item @if (Route::is(['supplier.index', 'supplier.input_supplier', 'supplier.edit_supplier'])) active @endif"
                            href="{{ route('supplier.index') }}">Supplier</a>
                    @endif
                </div>
            </div>
        </li>
    @endif
    @if (validationAkses('view data customer') || $roleuser === 'Super Admin')
        <li class="nav-item @if (Route::is(['customer.index', 'customer.input_customer', 'customer.edit_customer', 'customer.balance_customer'])) active @endif">
            <a class="nav-link" href="{{ route('customer.index') }}">
                <i class="fas fa-fw fa-users"></i>
                <span>Customer</span></a>
        </li>
    @endif
    @if (validationAkses('view data product') || $roleuser === 'Super Admin')
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
    @endif
    @if (validationAkses('kasir') || $roleuser === 'Super Admin')
        <li class="nav-item @if (Route::is(['cashier.index', 'cashier.sale_info'])) active @endif">
            <a class="nav-link" href="{{ route('cashier.index') }}">
                <i class="fas fa-fw fa-cash-register"></i>
                <span>Kasir</span></a>
        </li>
    @endif

    @php
        $aksesviewCancel = validationAkses('view data transaksi pembatalan');
        $aksesviewReturn = validationAkses('view data transaksi pengembalian');
    @endphp
    @if ($aksesviewCancel || $aksesviewReturn || $roleuser === 'Super Admin')
        <li class="nav-item @if (Route::is([
                'sale_cancel.index',
                'sale_returns.index',
                'sale_returns.create_sale_return',
                'sale_returns.detail_sale_return',
            ])) active @endif">
            <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#saled"
                aria-expanded="true" aria-controls="collapseTwo">
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
                    @if ($aksesviewCancel || $roleuser === 'Super Admin')
                        <a class="collapse-item @if (Route::is(['sale_cancel.index'])) active @endif"
                            href="{{ route('sale_cancel.index') }}">Cancel</a>
                    @endif
                    @if ($aksesviewReturn || $roleuser === 'Super Admin')
                        <a class="collapse-item @if (Route::is(['sale_returns.index', 'sale_returns.create_sale_return', 'sale_returns.detail_sale_return'])) active @endif"
                            href="{{ route('sale_returns.index') }}">Return</a>
                    @endif
                </div>
            </div>
        </li>
    @endif
    @php
        $aksesviewArusKas = validationAkses('view data arus kas');
        $aksescreateOrderPembelian = validationAkses('create order pembelian');
        $aksesviewOrderPembelian = validationAkses('view list order pembelian');
    @endphp
    @if ($aksesviewArusKas || $roleuser === 'Super Admin')
        <li class="nav-item @if (Route::is(['cash.index'])) active @endif">
            <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#money"
                aria-expanded="true" aria-controls="collapseTwo">
                <i class="fas fa-fw fa-shopping-cart"></i>
                <span>Keuangan</span>
            </a>
            <div id="money" class="collapse @if (Route::is(['cash.index'])) show @endif"
                aria-labelledby="headingTwo" data-parent="#accordionSidebar">
                <div class="bg-white py-2 collapse-inner rounded">
                    <h6 class="collapse-header">Keuangan List :</h6>
                    @if ($aksesviewArusKas || $roleuser === 'Super Admin')
                        <a class="collapse-item @if (Route::is(['cash.index'])) active @endif"
                            href="{{ route('cash.index') }}">Arus Kas</a>
                    @endif
                    @if ($aksesviewArusKas || $roleuser === 'Super Admin')
                        <a class="collapse-item @if (Route::is(['cash.balance_store'])) active @endif"
                            href="{{ route('cash.balance_store') }}">Balance Store</a>
                    @endif
                    @if ($aksesviewArusKas || $roleuser === 'Super Admin')
                        <a class="collapse-item @if (Route::is(['debt.index'])) active @endif"
                            href="{{ route('debt.index') }}">List Hutang</a>
                    @endif
                </div>
            </div>
        </li>
    @endif
    @if ($aksescreateOrderPembelian || $aksesviewOrderPembelian || $roleuser === 'Super Admin')
        <li class="nav-item @if (Route::is(['purchase.index'])) active @endif">
            <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#purchase"
                aria-expanded="true" aria-controls="collapseTwo">
                <i class="fas fa-fw fa-shopping-cart"></i>
                <span>Pembelian</span>
            </a>
            <div id="purchase" class="collapse @if (Route::is(['purchase.index'])) show @endif"
                aria-labelledby="headingTwo" data-parent="#accordionSidebar">
                <div class="bg-white py-2 collapse-inner rounded">
                    <h6 class="collapse-header">Pembelian List :</h6>
                    @if ($aksescreateOrderPembelian || $roleuser === 'Super Admin')
                        <a class="collapse-item @if (Route::is(['purchase.index'])) active @endif"
                            href="{{ route('purchase.index') }}">Pembelian Order</a>
                    @endif
                    @if ($aksesviewOrderPembelian || $roleuser === 'Super Admin')
                        <a class="collapse-item @if (Route::is(['purchase.list_order_pembelian'])) active @endif"
                            href="{{ route('purchase.list_order_pembelian') }}">List Order Pembelian</a>
                    @endif
                </div>
            </div>
        </li>
    @endif
    @php
        $aksesviewReportSales = validationAkses('Laporan transaksi penjualan');
        $aksesviewReportDebt = validationAkses('Laporan transaksi jatuh tempo');
        $aksesviewReportLabaRugi = validationAkses('Laporan transaksi laba rugi');
    @endphp
    @if ($aksesviewReportSales || $aksesviewReportDebt || $aksesviewReportLabaRugi || $roleuser === 'Super Admin')
        <li class="nav-item @if (Route::is(['report.report_sales', 'report.report_debt', 'report.report_cash_flow'])) active @endif">
            <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#report"
                aria-expanded="true" aria-controls="collapseTwo">
                <i class="fas fa-fw fa-file"></i>
                <span>Laporan</span>
            </a>
            <div id="report" class="collapse @if (Route::is(['report.report_sales', 'report.report_debt', 'report.report_cash_flow'])) show @endif"
                aria-labelledby="headingTwo" data-parent="#accordionSidebar">
                <div class="bg-white py-2 collapse-inner rounded">
                    <h6 class="collapse-header">Laporan List :</h6>

                    @if ($aksesviewReportSales || $roleuser === 'Super Admin')
                        <a class="collapse-item @if (Route::is(['report.report_sales'])) active @endif"
                            href="{{ route('report.report_sales') }}">Laporan Penjualan</a>
                    @endif

                    @if ($aksesviewReportDebt || $roleuser === 'Super Admin')
                        <a class="collapse-item @if (Route::is(['report.report_debt'])) active @endif"
                            href="{{ route('report.report_debt') }}">Laporan Jatuh Tempo</a>
                    @endif
                    @if ($aksesviewReportLabaRugi || $roleuser === 'Super Admin')
                        <a class="collapse-item @if (Route::is(['report.report_cash_flow'])) active @endif"
                            href="{{ route('report.report_cash_flow') }}">Laporan Laba Rugi</a>
                    @endif
                    @if ($aksesviewReportLabaRugi || $roleuser === 'Super Admin')
                        <a class="collapse-item @if (Route::is(['report.report_cash_flow'])) active @endif"
                            href="{{ route('report.report_assets') }}">Laporan Assets</a>
                    @endif
                </div>
            </div>
        </li>
    @endif
    @php
        $aksesviewPeran = validationAkses('view data peran');
        $aksesviewUser = validationAkses('view data pengguna');
    @endphp
    @if ($aksesviewPeran || $aksesviewUser || $roleuser === 'Super Admin')
        <li class="nav-item @if (Route::is(['users.index', 'roles.index', 'users.create_users', 'roles.create_roles', 'users.akses_user'])) active @endif">
            <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#akses"
                aria-expanded="true" aria-controls="collapseTwo">
                <i class="fas fa-fw fa-key"></i>
                <span>Hak Akses</span>
            </a>
            <div id="akses" class="collapse @if (Route::is(['users.index', 'roles.index', 'roles.create_roles', 'users.create_users', 'users.akses_user'])) show @endif"
                aria-labelledby="headingTwo" data-parent="#accordionSidebar">
                <div class="bg-white py-2 collapse-inner rounded">
                    <h6 class="collapse-header">Hak Akses List :</h6>
                    @if ($aksesviewPeran || $roleuser === 'Super Admin')
                        <a class="collapse-item @if (Route::is(['roles.index'])) active @endif"
                            href="{{ route('roles.index') }}">Peran</a>
                    @endif
                    @if ($aksesviewUser || $roleuser === 'Super Admin')
                        <a class="collapse-item @if (Route::is(['users.index', 'users.create_users', 'users.akses_user'])) active @endif"
                            href="{{ route('users.index') }}">Pengguna</a>
                    @endif
                </div>
            </div>
        </li>
    @endif
    <hr class="sidebar-divider d-none d-md-block">
    <div class="text-center d-none d-md-inline">
        <button class="rounded-circle border-0" id="sidebarToggle"></button>
    </div>


</ul>
