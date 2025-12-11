<?php

namespace App\Http\Controllers;

use App\Models\Finance;
use Illuminate\Http\Request;

class ReportController extends Controller
{
     public function index(Request $request)
    {
        if (!auth()->user()->can('report-view')) {
            abort(403, 'Unauthorized action.');
        }

        $startDate = $request->input('start_date', now()->startOfMonth()->format('Y-m-d'));
        $endDate = $request->input('end_date', now()->endOfMonth()->format('Y-m-d'));

        $income = Finance::where('type', 'income')
            ->whereBetween('date', [$startDate, $endDate])
            ->sum('amount');

        $expense = Finance::where('type', 'expense')
            ->whereBetween('date', [$startDate, $endDate])
            ->sum('amount');

        $netProfit = $income - $expense;

        $transactions = Finance::whereBetween('date', [$startDate, $endDate])
            ->orderBy('date', 'desc')
            ->get();

        $incomeByCategory = Finance::where('type', 'income')
            ->whereBetween('date', [$startDate, $endDate])
            ->selectRaw('category, SUM(amount) as total')
            ->groupBy('category')
            ->get();

        $expenseByCategory = Finance::where('type', 'expense')
            ->whereBetween('date', [$startDate, $endDate])
            ->selectRaw('category, SUM(amount) as total')
            ->groupBy('category')
            ->get();

        return view('reports.index', compact(
            'income',
            'expense',
            'netProfit',
            'transactions',
            'incomeByCategory',
            'expenseByCategory',
            'startDate',
            'endDate'
        ));
    }
}
