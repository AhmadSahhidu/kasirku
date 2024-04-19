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
// if (! function_exists('getProduct')) {
//     function getProduct($productId, $withTrashed = false)
//     {
//         return Product::withTrashed($withTrashed)->find($productId);
//     }
// }
if (!function_exists('getUser')) {
    function getUser($productId)
    {
        return User::find($productId);
    }
}
if (!function_exists('getUser')) {
    function getUser($userId)
    {
        return User::find($userId);
    }
}

if (!function_exists('set_active')) {
    function set_active($uri, $output = 'active')
    {
        if (is_array($uri)) {
            foreach ($uri as $u) {
                if (Route::is($u)) {
                    return $output;
                }
            }
        } else {
            if (Route::is($uri)) {
                return $output;
            }
        }

        return '';
    }
}

// if (! function_exists('generateProductCode')) {
//     function generateProductCode($supplierId, $series): string
//     {
//         $supplier = Supplier::find($supplierId)->code ?? Supplier::first()->code;
//         $code = $supplier.getCurrentProductCode().date('my').$series;
//         incrementProductCode();

//         return $code;
//     }
// }

// if (! function_exists('incrementProductCode')) {
//     function incrementProductCode(): bool
//     {
//         $data = ProductCode::first();
//         if ($data) {
//             $iteration = (int) substr($data->amount, -5, 5);
//             $iteration++;
//             $iteration = sprintf('%05s', $iteration);
//             $data->amount = $iteration;
//             $data->save();
//         } else {
//             ProductCode::create([]);
//         }

//         return true;
//     }
// }
// if (! function_exists('getCurrentProductCOde')) {
//     function getCurrentProductCode()
//     {
//         $code = ProductCode::first();
//         if (! $code) {
//             $code = ProductCode::create();
//         }

//         return $code->amount ? $code->amount : '00001';
//     }
// }




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
