<?php

namespace App\Http\Controllers;

use App\Helpers\FlashData;
use App\Models\Role;
use Illuminate\Http\Request;

class RoleController extends Controller
{
    public function index()
    {
        $roles = Role::all();

        return view('pages.roles.index', compact('roles'));
    }

    public function create()
    {
        return view('pages.roles.create');
    }

    public function store(Request $request)
    {
        try {
            Role::create($request->all());

            FlashData::success_alert('Berhasil menambah data peran');
            return redirect()->route('roles.index');
        } catch (\Throwable $th) {
            FlashData::danger_alert($th->getMessage());
            return redirect()->back();
        }
    }
}
