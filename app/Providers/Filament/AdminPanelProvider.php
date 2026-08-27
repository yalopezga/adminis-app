<?php

namespace App\Providers\Filament;

use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Pages;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Filament\Widgets;
use Filament\View\PanelsRenderHook;
use Illuminate\Support\Facades\Blade;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;
use App\Filament\Pages\Dashboard;

class AdminPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->default()
            ->id('admin')
            ->path('admin')
            ->login()

            // --- LOGOS PRINCIPALES DEL PANEL ---
            ->brandLogo(asset('images/logo_adminis_negro.svg'))
            ->darkModeBrandLogo(asset('images/logo_adminis_blanco.svg'))
            ->brandLogoHeight('3rem')
            ->favicon(asset('images/favicon.png'))

            // --- COLOR NEGRO / ZINC ---
            ->colors([
                'primary' => Color::Gray,
            ])

            // --- LOGO DE UT LLANO ALIANZA (COMPATIBILIDAD CSS DIRECTA CON MODO OSCURO) ---
            ->renderHook(
                PanelsRenderHook::SIDEBAR_NAV_START,
                fn (): string => Blade::render('
                    <style>
                        .sidebar-logo-dark { display: none !important; }
                        .sidebar-logo-light { display: block !important; }
                        
                        .dark .sidebar-logo-dark { display: block !important; }
                        .dark .sidebar-logo-light { display: none !important; }
                    </style>
                    <div class="px-4 py-3 mb-2 flex justify-center border-b border-gray-200 dark:border-gray-800">
                        <img src="{{ asset("images/logo_ut_llano_alianza_negro.svg") }}" 
                             alt="UT Llano Alianza" 
                             class="h-12 w-auto object-contain sidebar-logo-light" />

                        <img src="{{ asset("images/logo_ut_llano_alianza_blanco.svg") }}" 
                             alt="UT Llano Alianza" 
                             class="h-12 w-auto object-contain sidebar-logo-dark" />
                    </div>
                ')
            )
            // ----------------------------------------------------

            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\\Filament\\Resources')
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\\Filament\\Pages')
            ->pages([
                Dashboard::class,
            ])
            ->widgets([
                \App\Filament\Widgets\StatsOverviewWidget::class,
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
            ]);
    }
}