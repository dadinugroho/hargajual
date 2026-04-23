<?php

namespace App\Providers;

use App\Models\Organization;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void {}

    public function boot(): void
    {
        Paginator::useBootstrapFive();

        if (request()->isSecure()) {
            URL::forceScheme('https');
        }

        View::composer('layouts.app', function ($view) {
            if (auth()->check()) {
                $orgs = auth()->user()->isSuperAdmin()
                    ? Organization::orderBy('name')->get()
                    : auth()->user()->organizations()->orderBy('name')->get();
                $selectedOrgId = session('selected_org_id');

                if (!$selectedOrgId && $orgs->isNotEmpty()) {
                    $selectedOrgId = $orgs->first()->id;
                    session(['selected_org_id' => $selectedOrgId]);
                }

                $view->with('navOrganizations', $orgs);
                $view->with('navSelectedOrgId', $selectedOrgId);
            }
        });
    }
}
