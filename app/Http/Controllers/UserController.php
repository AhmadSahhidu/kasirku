<?php

namespace App\Http\Controllers;

use App\Helpers\FlashData;
use App\Models\Permission;
use App\Models\Role;
use App\Models\RoleUser;
use App\Models\Store;
use App\Models\User;
use App\Models\UserPermission;
use App\Models\UserStore;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    public function index()
    {
        $data = User::query();
        $data->select('users.*', 'roles.name as role_name', 'stores.name as store_name');
        $data->join('role_users', 'role_users.user_id', '=', 'users.id');
        $data->join('roles', 'roles.id', '=', 'role_users.role_id');
        $data->leftjoin('user_stores', 'user_stores.user_id', '=', 'users.id');
        $data->leftjoin('stores', 'stores.id', '=', 'user_stores.store_id');
        $users = $data->get();

        return view('pages.users.index', compact('users'));
    }

    public function create()
    {
        $store = Store::all();
        $roles = Role::all();

        return view('pages.users.create', compact('roles', 'store'));
    }

    public function store(Request $request)
    {
        try {
            $validator = Validator::make(request()->all(), [
                'name' => 'required',
                'username' => 'required|unique:users',
                'password' => 'required|confirmed|min:6',
            ]);

            if ($validator->fails()) {
                return response()->json($validator->errors()->toJson(), 400);
            }

            $input['id'] = generateUuid();
            $input['name'] = request()->name;
            $input['username'] = request()->username;
            $input['password'] = bcrypt(request()->password);
            User::create($input);
            $users = User::where('name', request()->name)->first();
            // error_log($users);
            // dd($users);
            RoleUser::create([
                'user_id' => $users->id,
                'role_id' => request()->role_id
            ]);
            UserStore::create([
                'user_id' => $users->id,
                'store_id' => request()->store_id
            ]);

            FlashData::success_alert('Berhasil menambahkan data users');
            return redirect()->route('users.index');
        } catch (\Throwable $th) {
            FlashData::danger_alert($th->getMessage());
            return redirect()->back();
        }
    }

    public function aksesUser($idUser)
    {
        $permissionGroup = Permission::query();
        $permissionGroup->select('group', DB::raw('GROUP_CONCAT(id) as ids'));
        $permissionGroup->orderBy('id', 'ASC');
        $permissionGroup->groupBy('group');
        $groups = $permissionGroup->get();

        return view('pages.users.hak akses.index', compact('groups'));
    }

    public function savePermission(Request $request)
    {
        try {
            $permission = $request->permission;
            if ($permission === null) {
                FlashData::danger_alert('Silahkan pilih akses user');
                return redirect()->back();
            }
            UserPermission::where('user_id', $request->user_id)->delete();
            foreach ($permission as $items) {
                UserPermission::create([
                    'user_id' => $request->user_id,
                    'permission_id' => $items
                ]);
            }

            FlashData::success_alert('Berhasil menambahkan akses pada user ini');
            return redirect()->route('users.akses_user', $request->user_id);
        } catch (\Throwable $th) {
            FlashData::danger_alert($th->getMessage());
            return redirect()->back();
        }
    }
}
