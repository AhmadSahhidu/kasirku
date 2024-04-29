<?php

use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Webpatser\Uuid\Uuid;

if (!function_exists('generateUuid')) {
    /**
     * @throws Exception
     */
    function generateUuid(): string
    {
        return Uuid::generate(4)->string;
    }
}
if (!function_exists('rupiahFormat')) {
    function rupiahFormat($amount): string
    {
        return 'Rp. ' . number_format($amount, 0, ',', '.');
    }
}
if (!function_exists('amountFormat')) {
    function amountFormat($amount): string
    {
        return number_format($amount, 2, ',', '.');
    }
}


if (!function_exists('paymentMethod')) {
    function paymentMethod($payment): string
    {
        $name_payment = null;
        $typePayment = (int) $payment;
        if ($typePayment === 1) {
            $name_payment = 'Cash';
        } else if ($typePayment === 2) {
            $name_payment = 'Transfer';
        } else if ($typePayment === 3) {
            $name_payment = 'Tempo';
        } else {
            $name_payment = 'Balance Customer';
        }
        return $name_payment;
    }
}


if (!function_exists('getDataUser')) {
    function getDataUser($username): string
    {
        $user = User::where('username', $username)->first();
        return $user;
    }
}

if (!function_exists('statusReturn')) {
    function statusReturn($status): string
    {
        $nameStatus = null;
        $typeStatus = (int) $status;
        if ($typeStatus === 1) {
            $nameStatus = 'Menunggu Konfirmasi';
        } else if ($typeStatus === 2) {
            $nameStatus = 'Disetujui';
        } else {
            $nameStatus = 'Ditolak';
        }
        return $nameStatus;
    }
}

if (!function_exists('statusDebt')) {
    function statusDebt($status): string
    {
        $nameStatus = null;
        $typeStatus = (int) $status;
        if ($typeStatus === 1) {
            $nameStatus = 'Belum Lunas';
        } else {
            $nameStatus = 'Lunas';
        }
        return $nameStatus;
    }
}


if (!function_exists('roleTranslate')) {
    function roleTranslate($userId)
    {
        $roleName = User::find($userId)?->roles()->pluck('name')->toArray()[0];
        if ($roleName === 'admin') {
            return 'Admin';
        }
        if ($roleName === 'superadmin') {
            return 'Super Admin';
        }
        if ($roleName === 'finance') {
            return 'Keuangan';
        }

        if ($roleName === 'marketing') {
            return 'Marketing';
        }
        if ($roleName === 'cashier') {
            return 'Kasir';
        }
        if ($roleName === 'warehouse') {
            return 'Gudang';
        }
        if ($roleName === 'purchase') {
            return 'Pembelian';
        }

        if ($roleName === 'driver') {
            return 'Driver';
        }

        if ($roleName === 'foreman') {
            return 'Mandor';
        }

        return '';
    }
}


if (!function_exists('roleTranslateByName')) {
    function roleTranslateByName($role)
    {
        if ($role === 'admin') {
            return 'Admin';
        }
        if ($role === 'superadmin') {
            return 'Super Admin';
        }
        if ($role === 'finance') {
            return 'Keuangan';
        }

        if ($role === 'marketing') {
            return 'Marketing';
        }
        if ($role === 'cashier') {
            return 'Kasir';
        }
        if ($role === 'warehouse') {
            return 'Gudang';
        }
        if ($role === 'purchase') {
            return 'Pembelian';
        }

        if ($role === 'driver') {
            return 'Driver';
        }

        if ($role === 'foreman') {
            return 'Mandor';
        }

        return '';
    }

    return '';
}

if (!function_exists('indonesiaMonths')) {
    function indonesiaMonths($month): string
    {
        if ($month == 1) {
            return 'Januari';
        }
        if ($month == 2) {
            return 'Februari';
        }
        if ($month == 3) {
            return 'Maret';
        }
        if ($month == 4) {
            return 'April';
        }
        if ($month == 5) {
            return 'Mei';
        }
        if ($month == 6) {
            return 'Juni';
        }
        if ($month == 7) {
            return 'Juli';
        }
        if ($month == 8) {
            return 'Agustus';
        }
        if ($month == 9) {
            return 'September';
        }
        if ($month == 10) {
            return 'Oktober';
        }
        if ($month == 11) {
            return 'November';
        }
        if ($month == 12) {
            return 'Desember';
        }

        return '';
    }
}
