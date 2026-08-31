<?php

namespace App\Providers\Filament;

use App\Filament\Auth\Pages\Register;
use App\Filament\Resources\Articles\ArticleResource;
use App\Filament\Resources\Employees\EmployeeResource;
use App\Filament\Resources\SiteSettings\SiteSettingResource;
use App\Filament\Widgets\StatsOverview;
use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Pages\Dashboard;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Filament\Support\Enums\Width;
use Filament\Widgets\AccountWidget;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\PreventRequestForgery;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;

class AdminPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->default()
            ->id('admin')
            ->path('admin')
            ->login()
            ->registration(Register::class)
            ->colors([
                'primary' => Color::Emerald,
            ])
            ->maxContentWidth(Width::Full)
            ->sidebarWidth('17rem')
            ->viteTheme('resources/css/filament/admin/theme.css')
            ->brandName('SITOMPUL')
            ->brandLogo(fn () => view('filament.admin.logo'))
            ->brandLogoHeight('auto')
            ->favicon(asset('images/logo.png'))
            ->resources([
                EmployeeResource::class,
                ArticleResource::class,
                SiteSettingResource::class,
            ])
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\Filament\Pages')
            ->pages([
                Dashboard::class,
            ])
            ->widgets([
                StatsOverview::class,
                AccountWidget::class,
            ])
            ->strictAuthorization()
            ->middleware([
                EncryptCookies::class,
                AddQueuedCookiesToResponse::class,
                StartSession::class,
                AuthenticateSession::class,
                ShareErrorsFromSession::class,
                PreventRequestForgery::class,
                SubstituteBindings::class,
                DisableBladeIconComponents::class,
                DispatchServingFilamentEvent::class,
            ])
            ->authMiddleware([
                Authenticate::class,
            ]);
    }
}
