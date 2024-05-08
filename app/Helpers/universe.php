<?php

use App\Models\Permission;
use App\Models\RoleUser;
use App\Models\User;
use App\Models\UserPermission;
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
    function userRoleName($userId): string
    {
        $user = User::where('id', $userId)->first();
        $data = RoleUser::query();
        $data->join('roles', 'roles.id', '=', 'role_users.role_id');
        $data->where('role_users.user_id', $user->id);
        $roleUser = $data->first();
        return $roleUser->name;
    }
}
