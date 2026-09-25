<?php

namespace App\Providers;

use App\Models\CultivationCycle;
use App\Models\Pond;
use App\Models\Product;
use App\Policies\CultivationCyclePolicy;
use App\Policies\PondPolicy;
use App\Policies\ProductPolicy;
use App\Services\WebsiteSettingService;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        Paginator::useBootstrapFive();

        Gate::policy(Pond::class, PondPolicy::class);
        Gate::policy(CultivationCycle::class, CultivationCyclePolicy::class);
        Gate::policy(Product::class, ProductPolicy::class);

        View::composer(['layouts.viscous', 'landing.*'], function ($view) {
            $settings = app(WebsiteSettingService::class);
            $data = $view->getData();
            $view->with([
                'branding' => $data['branding'] ?? $settings->get('site_branding'),
                'header' => $data['header'] ?? $settings->get('site_header'),
                'footer' => $data['footer'] ?? $settings->get('site_footer'),
            ]);
        });
    }
}
