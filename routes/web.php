<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\BalanceCustomerController;
use App\Http\Controllers\BalanceStoreController;
use App\Http\Controllers\BrandController;
use App\Http\Controllers\CashController;
use App\Http\Controllers\CashierController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DebtPaymentController;
use App\Http\Controllers\FlowDebtController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\PurchaseOrderController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\RequestDiskonController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\SaleCancelController;
use App\Http\Controllers\SaleInfoController;
use App\Http\Controllers\SaleReturnController;
use App\Http\Controllers\StockOpnameController;
use App\Http\Controllers\StoreController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\UserController;
use App\Models\BalanceCustomer;
use Illuminate\Support\Facades\Route;

Route::get('/', static function () {
    if (auth()->check()) {
        return redirect()->route('dashboard');
    }

    return view('login');
})->name('login');

Route::post('actionlogin', [AuthController::class, 'actionlogin'])->name('actionlogin');
Route::get('actionlogut', [AuthController::class, 'actionLogout'])->name('logout');

Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard')->middleware('auth');
Route::get('sales-year', [DashboardController::class, 'reportSalesYear'])->name('sales_year')->middleware('auth');

Route::group(['middleware' => ['auth']], static function () {

    //router cabang
    Route::prefix('cabang')->name('cabang.')->group(function () {
        Route::get('/', [StoreController::class, 'index'])->name('index');
        Route::get('input-store', [StoreController::class, 'create'])->name('input_store');
        Route::post('store-cabang', [StoreController::class, 'store'])->name('store_cabang');
        Route::get('edit/{idStore}', [StoreController::class, 'edit'])->name('edit_store');
        Route::put('update-store/{idStore}', [StoreController::class, 'update'])->name('update_store');
        Route::get('delete-store', [StoreController::class, 'delete'])->name('delete_store');
    });

    // route brand
    Route::prefix('brand')->name('brand.')->group(function () {
        Route::get('/', [BrandController::class, 'index'])->name('index');
        Route::get('input-brand', [BrandController::class, 'create'])->name('input_brand');
        Route::post('store-brand', [BrandController::class, 'store'])->name('store_brand');
        Route::get('edit/{idBrand}', [BrandController::class, 'show'])->name('edit_brand');
        Route::put('update-brand/{idBrand}', [BrandController::class, 'update'])->name('update_brand');
        Route::get('delete-brand', [BrandController::class, 'delete'])->name('delete_brand');
    });

    // route Category
    Route::prefix('categories')->name('categories.')->group(function () {
        Route::get('/', [CategoryController::class, 'index'])->name('index');
        Route::get('input-category', [CategoryController::class, 'create'])->name('input_category');
        Route::post('store-category', [CategoryController::class, 'store'])->name('store_category');
        Route::get('edit/{idCategory}', [CategoryController::class, 'show'])->name('edit_category');
        Route::put('update-category/{idCategory}', [CategoryController::class, 'update'])->name('update_category');
        Route::get('delete-category', [CategoryController::class, 'delete'])->name('delete_category');
    });

    // route supplier
    Route::prefix('supplier')->name('supplier.')->group(function () {
        Route::get('/', [SupplierController::class, 'index'])->name('index');
        Route::get('input-supplier', [SupplierController::class, 'create'])->name('input_supplier');
        Route::post('store-supplier', [SupplierController::class, 'store'])->name('store_supplier');
        Route::get('edit/{idSupplier}', [SupplierController::class, 'show'])->name('edit_supplier');
        Route::put('update-supplier/{idSupplier}', [SupplierController::class, 'update'])->name('update_supplier');
        Route::get('delete-supplier', [SupplierController::class, 'delete'])->name('delete_supplier');
    });

    // route customer
    Route::prefix('customer')->name('customer.')->group(function () {
        Route::get('/', [CustomerController::class, 'index'])->name('index');
        Route::get('input-customer', [CustomerController::class, 'create'])->name('input_customer');
        Route::post('store-customer', [CustomerController::class, 'store'])->name('store_customer');
        Route::get('edit/{idCustomer}', [CustomerController::class, 'show'])->name('edit_customer');
        Route::put('update-customer/{idCustomer}', [CustomerController::class, 'update'])->name('update_customer');
        Route::get('delete-customer', [CustomerController::class, 'delete'])->name('delete_customer');

        //route balance customer
        Route::get('balance-customer/{idCustomer}', [BalanceCustomerController::class, 'index'])->name('balance_customer');
        Route::post('balance-deposit/{idBalance}', [BalanceCustomerController::class, 'deposit'])->name('balance_deposit');
        Route::post('balance-keluar/{idBalance}', [BalanceCustomerController::class, 'pengeluaran'])->name('balance_keluar');
    });

    //route product
    Route::prefix('products')->name('product.')->group(function () {
        Route::get('/', [ProductController::class, 'index'])->name('index');
        Route::get('input-product', [ProductController::class, 'create'])->name('input_product');
        Route::post('create-product', [ProductController::class, 'store'])->name('create_product');
        Route::get('edit-product/{idProduct}', [ProductController::class, 'show'])->name('edit_product');
        Route::put('update-product/{idProduct}', [ProductController::class, 'update'])->name('update_product');
        Route::get('delete-product', [ProductController::class, 'delete'])->name('delete_product');
        Route::post('import-product', [ProductController::class, 'importProduct'])->name('import_product');
        Route::post('search-product', [ProductController::class, 'searchProduct'])->name('search_product');
        Route::post('search-category', [ProductController::class, 'searchCategory'])->name('search_category');

        Route::prefix('stock-opname')->name('stock_opname.')->group(function () {
            Route::get('/', [StockOpnameController::class, 'index'])->name('index');
            Route::get('stock_opname', [StockOpnameController::class, 'create'])->name('create_stock_opname');
            Route::post('store_stock_opname', [StockOpnameController::class, 'store'])->name('store_stock_opname');
        });
    });

    Route::prefix('cashier')->name('cashier.')->group(function () {
        Route::get('/', [CashierController::class, 'index'])->name('index');
        Route::post('store-cart', [CashierController::class, 'storeCart'])->name('store_cart');
        Route::post('request-discount', [RequestDiskonController::class, 'requestDiskon'])->name('request_discount');
        Route::put('edit-qtycart', [CashierController::class, 'editQty'])->name('edit_qty_cart');
        Route::get('delete-cart', [CashierController::class, 'deleteCart'])->name('delete_cart');
        Route::post('transaction-cashier', [CashierController::class, 'storeSale'])->name('transaction_cashier');

        //route sale info
        Route::get('info/{idSale}', [SaleInfoController::class, 'index'])->name('sale_info');
        Route::get('info/receip-f/{idSale}', [SaleInfoController::class, 'receipF'])->name('sale_info_receipf');
        Route::get('info/selling-note/{idSale}', [SaleInfoController::class, 'sellingNote'])->name('sale_info_selling_note');
    });

    Route::prefix('sale-cancel')->name('sale_cancel.')->group(function () {
        Route::get('/', [SaleCancelController::class, 'index'])->name('index');
        Route::post('store-sale-cancel/{idSale}', [SaleCancelController::class, 'cancelTransaction'])->name('cancel_transaction');
    });

    Route::prefix('sale-returns')->name('sale_returns.')->group(function () {
        Route::get('/', [SaleReturnController::class, 'index'])->name('index');
        Route::get('create-sale-return', [SaleReturnController::class, 'create'])->name('create_sale_return');
        Route::post('process-sale-return', [SaleReturnController::class, 'process'])->name('proses_sale_return');
        Route::get('detail-sale-return/{idReturn}', [SaleReturnController::class, 'show'])->name('detail_sale_return');
        Route::post('verify-sale-return', [SaleReturnController::class, 'verifyReturn'])->name('verify_sale_return');
    });

    Route::prefix('report')->name('report.')->group(function () {
        Route::get('sales', [ReportController::class, 'reportSales'])->name('report_sales');
        Route::get('debt', [ReportController::class, 'reportDebt'])->name('report_debt');
        Route::get('cash-flow', [ReportController::class, 'reportCashFlow'])->name('report_cash_flow');
        Route::get('assets', [ReportController::class, 'reportAssets'])->name('report_assets');
        Route::get('cash-out', [ReportController::class, 'reportCashOut'])->name('report_cash_out');
    });

    Route::prefix('roles')->name('roles.')->group(function () {
        Route::get('/', [RoleController::class, 'index'])->name('index');
        Route::get('create', [RoleController::class, 'create'])->name('create_roles');
        Route::post('store', [RoleController::class, 'store'])->name('store_roles');
    });

    Route::prefix('debt-payment')->name('debt_payment.')->group(function () {
        Route::get('payment/{paymentId}', [DebtPaymentController::class, 'index'])->name('index');
        Route::post('process-payment/{saledebtId}', [DebtPaymentController::class, 'paymentDebt'])->name('process_payment');
        Route::get('auto-balance-customer', [DebtPaymentController::class, 'autoBalanceCustomer'])->name('auto_balance_customer');
        Route::get('proses-auto-balance-customer', [DebtPaymentController::class, 'prosesAutoBalanceCustomer'])->name('proses_auto_balance_customer');
    });

    Route::prefix('cash')->name('cash.')->group(function () {
        Route::get('/', [CashController::class, 'index'])->name('index');
        Route::get('create-cash', [CashController::class, 'create'])->name('create_cash');
        Route::post('store-cash', [CashController::class, 'store'])->name('store_cash');
        Route::get('edit-cash/{cashId}', [CashController::class, 'show'])->name('edit_cash');
        Route::put('update-cash/{cashId}', [CashController::class, 'update'])->name('update_cash');
        Route::get('delete-cash', [CashController::class, 'delete'])->name('delete_cash');

        Route::get('balance-store', [BalanceStoreController::class, 'index'])->name('balance_store');
        Route::get('balance-transaction', [BalanceStoreController::class, 'transaction'])->name('balance_transaction');
        Route::post('proses-balance-transaction', [BalanceStoreController::class, 'prosesTransaction'])->name('proses_balance_transaction');
    });


    Route::prefix('diskon')->name('diskon.')->group(function () {
        Route::get('confirm-diskon/{diskonId}', [RequestDiskonController::class, 'confirmDiskon'])->name('confirm_diskon');
        Route::post('store-diskon/{diskonId}', [RequestDiskonController::class, 'storeDiskon'])->name('store_diskon');
    });

    Route::prefix('debt')->name('debt.')->group(function () {
        Route::get('/', [FlowDebtController::class, 'index'])->name('index');
        Route::get('create', [FlowDebtController::class, 'create'])->name('create');
        Route::post('searchSupplier', [FlowDebtController::class, 'searchSupplier'])->name('search_supplier');
        Route::post('proses-hutang', [FlowDebtController::class, 'prosesHutang'])->name('proses_debt');
        Route::get('detail-data-hutang/{debtId}', [FlowDebtController::class, 'show'])->name('detail_debt');
        Route::post('proses-hutang/{flowdebtId}', [FlowDebtController::class, 'prosesPaymentDebt'])->name('proses_hutang');
    });

    Route::prefix('users')->name('users.')->group(function () {
        Route::get('/', [UserController::class, 'index'])->name('index');
        Route::get('create', [UserController::class, 'create'])->name('create_users');
        Route::post('store', [UserController::class, 'store'])->name('store_users');
        Route::get('akses-user/{idUser}', [UserController::class, 'aksesUser'])->name('akses_user');
        Route::post('save-permission', [UserController::class, 'savePermission'])->name('save_permission');
    });

    Route::prefix('purchase')->name('purchase.')->group(function () {
        Route::get('/', [PurchaseOrderController::class, 'index'])->name('index');
        Route::post('proses-add-cart-order', [PurchaseOrderController::class, 'prosesAddCart'])->name('proses_add_cart_order');
        Route::get('delete-list-cart-order/{cartId}', [PurchaseOrderController::class, 'deleteListCartOrder'])->name('delete_list_cart_order');
        Route::post('proses-order', [PurchaseOrderController::class, 'prosesOrder'])->name('proses_order');

        Route::get('list-order-pembelian', [PurchaseOrderController::class, 'listPurchaseOrder'])->name('list_order_pembelian');
        Route::get('detail-order-pembelian/{purchaseId}', [PurchaseOrderController::class, 'detailPurchaseOrder'])->name('detail_order_pembelian');
        Route::get('confirm-payment-order-pembelian/{purchaseId}', [PurchaseOrderController::class, 'confirmPaymentPurchaseOrder'])->name('konfirmasi_pembayaran_order_pembelian');
        Route::post('proses-payment-order-pembelian', [PurchaseOrderController::class, 'prosesConfirmPayment'])->name('proses_confirm_payment');
        Route::post('validation-supplier-purchase-order', [PurchaseOrderController::class, 'validationSupplierPurchaseOrder'])->name('validation_supplier');
    });
});
