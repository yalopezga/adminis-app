<?php

namespace App\Filament\Widgets;

use App\Models\Product;
use App\Models\Purchase;
use App\Models\Sale;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverviewWidget extends BaseWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make('Total de Productos', Product::count())
                ->description('Productos registrados en el sistema')
                ->descriptionIcon('heroicon-m-cube')
                ->color('success'),

            Stat::make('Entradas Totales', Purchase::sum('quantity'))
                ->description('Unidades ingresadas al inventario')
                ->descriptionIcon('heroicon-m-arrow-down-tray')
                ->color('info'),

            Stat::make('Salidas Totales', Sale::sum('quantity'))
                ->description('Unidades despachadas o vendidas')
                ->descriptionIcon('heroicon-m-arrow-up-tray')
                ->color('warning'),
        ];
    }
}