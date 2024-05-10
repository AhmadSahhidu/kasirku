<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Customer;
use App\Models\Product;
use App\Models\Sales;
use App\Models\Store;
use App\Models\Supplier;
use Carbon\Carbon;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $roleUser = userRoleName();
        if ($roleUser === 'Super Admin') {
            $countProduct = Product::count();
            $countSupplier = Supplier::count();
            $countCustomer = Customer::count();
            $countCategory = Category::count();
            $sumdaySalesTunai = Sales::whereDate('created_at', now()->format('Y-m-d'))
                ->where('payment_method', 1)
                ->sum('grand_total');
            $sumdaySalesTransfer = Sales::whereDate('created_at', now()->format('Y-m-d'))
                ->where('payment_method', 2)
                ->sum('grand_total');
            $sumdaySalesTempo = Sales::whereDate('created_at', now()->format('Y-m-d'))
                ->where('payment_method', 3)
                ->sum('grand_total');
        } else {
            $storeUser = userStore();
            $stores = Store::where('name', $storeUser)->first();
            $countProduct = Product::where('store_id', $stores->id)->count();
            $countSupplier = Supplier::where('store_id', $stores->id)->count();
            $countCustomer = Customer::where('store_id', $stores->id)->count();
            $countCategory = Category::where('store_id', $stores->id)->count();
            $sumdaySalesTunai = Sales::whereDate('created_at', now()->format('Y-m-d'))
                ->where('store_id', $stores->id)
                ->where('payment_method', 1)
                ->sum('grand_total');
            $sumdaySalesTransfer = Sales::whereDate('created_at', now()->format('Y-m-d'))
                ->where('store_id', $stores->id)
                ->where('payment_method', 2)
                ->sum('grand_total');
            $sumdaySalesTempo = Sales::whereDate('created_at', now()->format('Y-m-d'))
                ->where('store_id', $stores->id)
                ->where('payment_method', 3)
                ->sum('grand_total');
        }

        return view('pages.dashboard', compact('countProduct', 'countSupplier', 'countCustomer', 'countCategory', 'sumdaySalesTunai', 'sumdaySalesTransfer', 'sumdaySalesTempo'));
    }

    public function reportSalesYear()
    {
        $storeUser = userStore();
        $stores = Store::where('name', $storeUser)->first();
        $roleUser = userRoleName();
        $start = Carbon::now()->startOfYear()->format('Y-m-d');
        $end = Carbon::now()->endOfYear()->format('Y-m-d');
        if ($roleUser === 'Super Admin') {
            $query = Sales::selectRaw('DATE_FORMAT(created_at, "%Y-%m") as date, sum(grand_total) as total')
                ->whereBetween('created_at', [$start, $end])
                ->groupBy('date')
                ->orderBy('date', 'ASC')
                ->get()
                ->toArray();
        } else {
            $query = Sales::selectRaw('DATE_FORMAT(created_at, "%Y-%m") as date, sum(grand_total) as total')
                ->whereBetween('created_at', [$start, $end])
                ->where('store_id', $stores->id)
                ->groupBy('date')
                ->orderBy('date', 'ASC')
                ->get()
                ->toArray();
        }

        foreach ($query as &$result) {
            $date = Carbon::createFromFormat('Y-m', $result['date']);
            $result['date'] = $date->translatedFormat('F'); // F untuk nama bulan dalam bahasa Indonesia
        }
        return [
            'labels' => array_column($query, 'date'),
            'values' => array_column($query, 'total'),
        ];
    }
}
