<?php

use App\Models\Permission;
use App\Models\RequestDiskonNotification;
use App\Models\RoleUser;
use App\Models\Store;
use App\Models\User;
use App\Models\UserPermission;
use App\Models\UserStore;
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

if (!function_exists('statusPurchaseOrder')) {
    function statusPurchaseOrder($data): string
    {
        if ($data === 1) {
            return 'Belum lunas';
        } else {
            return 'Lunas';
        }
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


if (!function_exists('permissionByGroup')) {
    function permissionByGroup($group)
    {
        return Permission::where('group', $group)->get();
    }
}

if (!function_exists('checkPermission')) {
    function checkPermission($permissionId)
    {
        $data = UserPermission::where('permission_id', $permissionId)->first();
        return $data;
    }
}

if (!function_exists('validationAkses')) {
    function validationAkses($aksesname)
    {
        $data = UserPermission::query();
        $data->join('permissions', 'permissions.id', '=', 'user_permissions.permission_id');
        $data->where('permissions.name', $aksesname);
        $valid = $data->first();
        return $valid;
    }
}


if (!function_exists('userRoleName')) {
    function userRoleName(): string
    {
        $user = User::where('id', auth()->user()->id)->first();
        $data = RoleUser::query();
        $data->join('roles', 'roles.id', '=', 'role_users.role_id');
        $data->where('role_users.user_id', $user->id);
        $roleUser = $data->first();
        return $roleUser->name;
    }
}

if (!function_exists('userStore')) {
    function userStore(): string
    {
        $user = User::where('id', auth()->user()->id)->first();
        $data = UserStore::query();
        $data->join('stores', 'stores.id', '=', 'user_stores.store_id');
        $data->where('user_stores.user_id', $user->id);
        $stores = $data->first();
        return $stores->name ?? 'Semua Cabang';
    }
}

if (!function_exists('notifDiskon')) {
    function notifDiskon(): string
    {
        $userRoleName = userRoleName();
        if ($userRoleName === 'Super Admin') {
            $countDiskon = RequestDiskonNotification::where('readed', 0)->count();
        } else {
            $store = Store::where('name', userStore())->first();
            $countDiskon = RequestDiskonNotification::where(['store_id' => $store->id, 'readed' => 0])->count();
        }


        return $countDiskon;
    }
}

if (!function_exists('dataDiskon')) {
    function dataDiskon()
    {
        $userRoleName = userRoleName();
        if ($userRoleName === 'Super Admin') {
            $diskon = RequestDiskonNotification::where('readed', 0)->get();
        } else {
            $store = Store::where('name', userStore())->first();
            $diskon = RequestDiskonNotification::where(['store_id' => $store->id, 'readed' => 0])->get();
        }

        return $diskon;
    }
}

if (!function_exists('statusDiskon')) {
    function statusDiskon($status): string
    {
        if ($status === 1) {
            $nameStatus = 'Menunggu Konfirmasi';
        } else if ($status === 2) {
            $nameStatus = 'Disetujui';
        } else if ($status === 3) {
            $nameStatus = 'Ditolak';
        } else {
            $nameStatus = '';
        }
        return $nameStatus;
    }
}

if (!function_exists('jenisCash')) {
    function jenisCash($cash): string
    {
        if ($cash === 1) {
            $nameCash = 'Pemasukan';
        } else if ($cash === 2) {
            $nameCash = 'Pengeluaran';
        } else {
            $nameCash = '';
        }
        return $nameCash;
    }
}

if (!function_exists('jenisCashOut')) {
    function jenisCashOut($cash): string
    {
        if ($cash === 1) {
            $nameCash = 'Modal';
        } else if ($cash === 2) {
            $nameCash = 'Operasional';
        } else {
            $nameCash = '-';
        }
        return $nameCash;
    }
}
