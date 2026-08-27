<?php

namespace App\Filament\Pages;

use Filament\Pages\Dashboard as BaseDashboard;

class Dashboard extends BaseDashboard
{
    // Cambia 'Inicio' por el nombre que quieras ver en el menú (ej: 'Panel Principal', 'Inicio', etc.)
    protected static ?string $navigationLabel = 'Inicio';

    // Opcional: Cambia el título grande que aparece arriba al entrar a la página
    protected static ?string $title = 'Inicio';
}