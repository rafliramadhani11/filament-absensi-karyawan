<?php

namespace App\Providers\Filament;

use App\Filament\Pages\Dashboard;
use App\Filament\Resources\AttendanceResource\Widgets\AttendanceChart;
use App\Filament\Resources\DivisionResource\Widgets\DivisionChart;
use App\Filament\Resources\UserResource;
use App\Filament\Widgets\Absensi;
use App\Filament\Widgets\StatsOverview;
use Filament\Facades\Filament;
use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Navigation\UserMenuItem;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Filament\Widgets;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
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
            ->darkMode(false)
            ->brandLogo(asset('img/logo-pdf.jpeg'))
            ->brandLogoHeight(fn () => auth()->check() ? '2.8rem' : '4rem')
            ->path('dashboard')
            ->profile()
            ->login()
            ->colors([
                'primary' => Color::Slate,
            ])
            ->topNavigation()
            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\\Filament\\Resources')
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\\Filament\\Pages')
            ->pages([
                Dashboard::class,
            ])
            ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\\Filament\\Widgets')
            ->widgets([
                Widgets\AccountWidget::class,
                StatsOverview::class,
                AttendanceChart::class,
                DivisionChart::class,
                Absensi::class,
            ])
            ->middleware([
                EncryptCookies::class,
                AddQueuedCookiesToResponse::class,
                StartSession::class,
                AuthenticateSession::class,
                ShareErrorsFromSession::class,
                VerifyCsrfToken::class,
                SubstituteBindings::class,
                DisableBladeIconComponents::class,
                DispatchServingFilamentEvent::class,
            ])
            ->authMiddleware([
                Authenticate::class,
            ])
            ->viteTheme('resources/css/filament/admin/theme.css');
    }

    // public function boot(): void
    // {
    //     Filament::serving(function () {
    //         Filament::registerUserMenuItems([
    //             UserMenuItem::make()
    //                 ->label('Setting')
    //                 ->url(UserResource::getUrl()),
    //         ]);
    //     });
    // }
}
