<?php

namespace App\Http\Controllers;

use App\Helpers\FlashData;
use App\Models\Customer;
use App\Models\Store;
use Illuminate\Http\Request;
use Throwable;

class CustomerController extends Controller
{
    public function index()
    {
        $customer = Customer::with('store')->get();

        return view('pages.customer.index', compact('customer'));
    }

    public function create()
    {
        $store = Store::all();

        return view('pages.customer.create', compact('store'));
    }

    public function store(Request $request)
    {
        try {

            $countCustomer = Customer::count();
            $newCustomerCode = 'CD' . ($countCustomer + 1);

            Customer::create([
                'name' => $request->name,
                'phone' => $request->phone,
                'code' => $newCustomerCode,
                'address' => $request->address,
                'store_id' => $request->store_id
            ]);

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
        $store = Store::all();

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
