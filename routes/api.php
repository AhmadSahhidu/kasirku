<?php

use App\Http\Controllers\AuthController;
use App\Models\Permission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::group([
    'prefix' => 'auth'
], function ($router) {
    Route::post('/register', [AuthController::class, 'register'])->name('register');
    Route::post('/login', [AuthController::class, 'login'])->name('login');
});
Route::get('create-permission', function () {
    $data = [
        [
            'name' => 'Dashboard',
            'group' => 'dashboard',
        ],
        [
            'name' => 'konfirmasi diskon',
            'group' => 'diskon',
        ],
        [
            'name' => 'request diskon',
            'group' => 'diskon',
        ],
        [
            'name' => 'view data brand',
            'group' => 'brand',
        ],
        [
            'name' => 'create data brand',
            'group' => 'brand',
        ],
        [
            'name' => 'edit data brand',
            'group' => 'brand',
        ],

        [
            'name' => 'delete data brand',
            'group' => 'brand',
        ],
        [
            'name' => 'view data category',
            'group' => 'cateogry',
        ],
        [
            'name' => 'create data category',
            'group' => 'cateogry',
        ],
        [
            'name' => 'edit data category',
            'group' => 'cateogry',
        ],
        [
            'name' => 'delete data category',
            'group' => 'cateogry',
        ],
        [
            'name' => 'view data supplier',
            'group' => 'supplier',
        ],
        [
            'name' => 'create data supplier',
            'group' => 'supplier',
        ],
        [
            'name' => 'edit data supplier',
            'group' => 'supplier',
        ],
        [
            'name' => 'delete data supplier',
            'group' => 'supplier',
        ],
        [
            'name' => 'view data customer',
            'group' => 'customer',
        ],
        [
            'name' => 'create data customer',
            'group' => 'customer',
        ],
        [
            'name' => 'edit data customer',
            'group' => 'customer',
        ],
        [
            'name' => 'delete data customer',
            'group' => 'customer',
        ],
        [
            'name' => 'view balance customer',
            'group' => 'customer',
        ],
        [
            'name' => 'input deposit balance customer',
            'group' => 'customer',
        ],
        [
            'name' => 'input pengeluaran balance customer',
            'group' => 'customer',
        ],
        [
            'name' => 'view data product',
            'group' => 'product',
        ],
        [
            'name' => 'import data product',
            'group' => 'product',
        ],
        [
            'name' => 'create data product',
            'group' => 'product',
        ],
        [
            'name' => 'edit data product',
            'group' => 'product',
        ],
        [
            'name' => 'delete data product',
            'group' => 'product',
        ],
        [
            'name' => 'stock opname product',
            'group' => 'product',
        ],
        [
            'name' => 'kasir',
            'group' => 'kasir',
        ],
        [
            'name' => 'view data arus kas',
            'group' => 'keuangan',
        ],
        [
            'name' => 'create data arus kas',
            'group' => 'keuangan',
        ],
        [
            'name' => 'edit data arus kas',
            'group' => 'keuangan',
        ],
        [
            'name' => 'delete data arus kas',
            'group' => 'keuangan',
        ],
        [
            'name' => 'view data transaksi pembatalan',
            'group' => 'penjualan',
        ],
        [
            'name' => 'proses transaksi pembatalan',
            'group' => 'penjualan',
        ],
        [
            'name' => 'view data transaksi pengembalian',
            'group' => 'penjualan',
        ],
        [
            'name' => 'konfirmasi transaksi pengembalian',
            'group' => 'penjualan',
        ],
        [
            'name' => 'Laporan transaksi penjualan',
            'group' => 'laporan',
        ],
        [
            'name' => 'Laporan transaksi jatuh tempo',
            'group' => 'laporan',
        ],
        [
            'name' => 'Laporan transaksi laba rugi',
            'group' => 'laporan',
        ],
        [
            'name' => 'view data peran',
            'group' => 'hak akses',
        ],
        [
            'name' => 'create data peran',
            'group' => 'hak akses',
        ],
        [
            'name' => 'edit data peran',
            'group' => 'hak akses',
        ],
        [
            'name' => 'delete data peran',
            'group' => 'hak akses',
        ],
        [
            'name' => 'view data pengguna',
            'group' => 'hak akses',
        ],
        [
            'name' => 'create data pengguna',
            'group' => 'hak akses',
        ],
        [
            'name' => 'edit data pengguna',
            'group' => 'hak akses',
        ],
        [
            'name' => 'delete data pengguna',
            'group' => 'hak akses',
        ],
        [
            'name' => 'view akses pengguna',
            'group' => 'hak akses',
        ]
    ];

    foreach ($data as $item) {
        Permission::create(['name' => $item['name'], 'group' => $item['group']]);
    }


    return 'Sukses';
});
