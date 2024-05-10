<?php

namespace App\Http\Controllers;

use App\Helpers\FlashData;
use App\Models\BalanceCustomer;
use App\Models\Customer;
use App\Models\Store;
use Illuminate\Http\Request;
use Throwable;

class CustomerController extends Controller
{
    public function index()
    {
        $roleuser = userRoleName();
        if ($roleuser === 'Super Admin') {
            $customer = Customer::with('store')->get();
        } else {
            $userStore = userStore();
            $stores = Store::where('name', $userStore)->first();
            $customer = Customer::with('store')->where('store_id', $stores->id)->get();
        }

        return view('pages.customer.index', compact('customer'));
    }

    public function create()
    {
        $roleuser = userRoleName();
        if ($roleuser === 'Super Admin') {
            $store = Store::all();
        } else {
            $userStore = userStore();
            $store = Store::where('name', $userStore)->get();
        }

        return view('pages.customer.create', compact('store'));
    }

    public function store(Request $request)
    {
        try {

            $countCustomer = Customer::count();
            $newCustomerCode = 'CD' . ($countCustomer + 1);

            $customer = Customer::create([
                'id' => generateUuid(),
                'name' => $request->name,
                'phone' => $request->phone,
                'code' => $newCustomerCode,
                'address' => $request->address,
                'store_id' => $request->store_id
            ]);
            BalanceCustomer::create(['nominal' => 0, 'customer_id' => $customer->id]);
            FlashData::success_alert('Berhasil menambahkan customer baru');
            return redirect()->route('customer.index');
        } catch (\Throwable $th) {
            FlashData::danger_alert($th->getMessage());
            return redirect()->back();
        }
    }

    public function show($idCustomer)
    {
        $customer = Customer::with('store')->where('id', $idCustomer)->first();
        $roleuser = userRoleName();
        if ($roleuser === 'Super Admin') {
            $store = Store::all();
        } else {
            $userStore = userStore();
            $store = Store::where('name', $userStore)->get();
        }

        return view('pages.customer.edit', compact('customer', 'store'));
    }

    public function update(Request $request, $idCustomer)
    {
        try {
            Customer::where('id', $idCustomer)->update(['name' => $request->name, 'phone' => $request->phone, 'address' => $request->address, 'store_id' => $request->store_id]);

            FlashData::success_alert('Berhasil merubah data customer');
            return redirect()->route('customer.index');
        } catch (\Throwable $th) {
            FlashData::danger_alert($th->getMessage());
            return redirect()->back();
        }
    }

    public function delete(Request $request)
    {
        try {
            Customer::where('id', $request->idCustomer)->delete();
            FlashData::success_alert('Berhasil menghapus data');
            return redirect()->route('customer.index');
        } catch (Throwable $th) {
            FlashData::danger_alert($th->getMessage());
            return redirect()->route('customer.index');
        }
    }
}
