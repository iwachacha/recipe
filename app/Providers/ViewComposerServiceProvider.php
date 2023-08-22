<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\View\Composers\IconComposer;
use Illuminate\Support\Facades\View;

class ViewComposerServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        View::composers([
            IconComposer::class => '*',  // 全てのbladeファイルに適用
        ]);
    }
}
