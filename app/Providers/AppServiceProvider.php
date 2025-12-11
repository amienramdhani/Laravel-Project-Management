<?php

namespace App\Providers;

use App\Models\Customer;
use App\Models\Finance;
use App\Models\Order;
use App\Models\Project;
use App\Models\Task;
use App\Models\User;
use App\Policies\CustomerPolicy;
use App\Policies\FinancePolicy;
use App\Policies\OrderPolicy;
use App\Policies\ProjectPolicy;
use App\Policies\TaskPolicy;
use App\Policies\UserPolicy;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    protected $policies = [
        User::class => UserPolicy::class,
        Customer::class => CustomerPolicy::class,
        Project::class => ProjectPolicy::class,
        Task::class => TaskPolicy::class,
        Order::class => OrderPolicy::class,
        Finance::class => FinancePolicy::class,
    ];

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
