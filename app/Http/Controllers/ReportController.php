<?php

namespace App\Http\Controllers;

use App\Models\SaleDebtPayment;
use App\Models\Sales;
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
        $salesDebt = SaleDebtPayment::with('sale')->get();
        return view('pages.laporan.report-debt-sales', compact('salesDebt'));
    }
}
