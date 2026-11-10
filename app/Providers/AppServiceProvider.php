<?php

namespace App\Providers;

use App\Models\Peminjaman;
use App\Services\NotificationService;
use Illuminate\Support\ServiceProvider;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\View;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Paginator::useBootstrap();

        View::composer('*', function ($view) {
            $user = auth()->user();
            $unread = app(NotificationService::class)->getUnreadCount($user);
            $pendingCount = 0;

            if ($user && $user->role === 'admin') {
                $pendingCount = Peminjaman::where('status_pinjam', 'menunggu')->count();
            }

            $view->with([
                'notificationUnreadCount' => $unread,
                'pendingApprovalsCount'   => $pendingCount,
            ]);
        });
    }
}
