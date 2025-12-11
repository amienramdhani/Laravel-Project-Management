<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Finance;
use App\Models\Order;
use App\Models\Project;
use App\Models\User;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        $data = [
            'totalUsers' => User::count(),
            'totalCustomers' => Customer::count(),
            'totalProjects' => Project::count(),
            'totalOrders' => Order::count(),
            'totalIncome' => Finance::where('type', 'income')->sum('amount'),
            'totalExpense' => Finance::where('type', 'expense')->sum('amount'),
        ];

        if ($user->hasRole('Staff')) {
            $data['myProjects'] = $user->assignedProjects()->count();
            $data['myTasks'] = $user->tasks()->count();
        }

        return view('dashboard', $data);
    }
}
