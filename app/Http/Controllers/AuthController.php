<?php

namespace App\Http\Controllers;

use App\Helpers\FlashData;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\View\View;

class AuthController extends Controller
{
    // public function __construct()
    // {
    //     $this->middleware('auth:api', ['except' => ['login', 'register']]);
    // }

    /**
     * Register a User.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function register()
    {
        $validator = Validator::make(request()->all(), [
            'name' => 'required',
            'username' => 'required|unique:users',
            'password' => 'required|confirmed|min:6',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors()->toJson(), 400);
        }

        $input['name'] = request()->name;
        $input['username'] = request()->username;
        $input['password'] = bcrypt(request()->password);
        $user = User::create($input);

        return response()->json($user, 201);
    }

    public function login(Request $request): View
    {
        return view('login');
    }

    public function actionlogin(Request $request)
    {
        $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
        ]);
        $credentials = $request->only('username', 'password');

        $token = Auth::attempt($credentials);
        if (!$token) {
            FlashData::danger_alert('User tidak ditemukan');
            return redirect()->back();
            // return response()->json([
            //     'status' => 'error',
            //     'message' => 'Unauthorized',
            // ], 401);
        }

        Auth::user();
        return redirect()->route('dashboard');
    }

    public function actionLogout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}
