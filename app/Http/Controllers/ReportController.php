<?php

namespace App\Http\Controllers;

use App\Models\Cash;
use App\Models\CashFlow;
use App\Models\SaleDebtPayment;
use App\Models\Sales;
use App\Models\Store;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function reportSales()
    {
        $sales = Sales::with('customer', 'user', 'store')->get();
        return view('pages.laporan.report-sale', compact('sales'));
    }

    public function reportDebt()
    {
        $salesDebt = SaleDebtPayment::with('sale')->where('status', 1)->get();
        $salesDebtLunas = SaleDebtPayment::with('sale')->where('status', 2)->get();
        return view('pages.laporan.report-debt-sales', compact('salesDebt', 'salesDebtLunas'));
    }

    public function reportCashFlow(Request $request)
    {
        $roleUser = userRoleName();
        $start_date = $request->start_date ?? date('Y-m-d');
        $end_date = $request->end_date ?? date('Y-m-d');
        if ($roleUser === 'Super Admin') {
            $cashOut = CashFlow::where('type_cash', 2)->where('created_at', '>=', $start_date)->where('created_at', '<=', $end_date)->get();
            $cashIn = CashFlow::where('type_cash', 1)->where('created_at', '>=', $start_date)->where('created_at', '<=', $end_date)->get();
            $totalCashIn = CashFlow::where('type_cash', 1)->where('created_at', '>=', $start_date)->where('created_at', '<=', $end_date)->sum('amount');
            $totalCashOut = CashFlow::where('type_cash', 2)->where('created_at', '>=', $start_date)->where('created_at', '<=', $end_date)->sum('amount');
        } else {
            $storeUser = userStore();
            $store = Store::where('name', $storeUser)->first();
            $cashOut = CashFlow::where(['type_cash' => 2, 'store_id' => $store->id])->where('created_at', '>=', $start_date)->where('created_at', '<=', $end_date)->get();
            $cashIn = CashFlow::where(['type_cash' =>  1, 'store_id' => $store->id])->where('created_at', '>=', $start_date)->where('created_at', '<=', $end_date)->get();
            $totalCashIn = CashFlow::where(['type_cash' => 1, 'store_id' => $store->id])->where('created_at', '>=', $start_date)->where('created_at', '<=', $end_date)->sum('amount');
            $totalCashOut = CashFlow::where(['type_cash' => 2, 'store_id' => $store->id])->where('created_at', '>=', $start_date)->where('created_at', '<=', $end_date)->sum('amount');
        }

        return view('pages.laporan.report-cash-flow', compact('cashOut', 'cashIn', 'totalCashIn', 'totalCashOut'));
    }

    public function reportCashOut(Request $request)
    {
        $modal = Cash::where('type_cash', 1)->where('type_cash_out', 'Modal')->get();
        $operasional = Cash::where('type_cash', 1)->where('type_cash_out', 'Operasional')->get();

        return view('pages.laporan.report-cash-out', compact('modal', 'operasional'));
    }
}
