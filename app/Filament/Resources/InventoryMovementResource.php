<?php

namespace App\Filament\Resources;

use App\Filament\Resources\InventoryMovementResource\Pages;
use App\Models\InventoryMovement;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class InventoryMovementResource extends Resource
{
    protected static ?string $model = InventoryMovement::class;

    protected static ?string $navigationIcon = 'heroicon-o-arrows-right-left';
    protected static ?string $navigationLabel = 'Movimientos de Inventario';
    protected static ?string $pluralModelLabel = 'Movimientos de Inventario';
    protected static ?string $modelLabel = 'Movimiento';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('product_id')
                    ->relationship('product', 'name')
                    ->label('Producto')
                    ->searchable()
                    ->preload()
                    ->required(),

                Forms\Components\Select::make('type')
                    ->label('Tipo de Movimiento')
                    ->options([
                        'in' => 'Entrada (Aumenta stock)',
                        'out' => 'Salida (Disminuye stock)',
                    ])
                    ->required(),

                Forms\Components\TextInput::make('quantity')
                    ->label('Cantidad')
                    ->numeric()
                    ->minValue(1)
                    ->required(),

                Forms\Components\Textarea::make('notes')
                    ->label('Notas / Motivo')
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('created_at')->label('Fecha')->dateTime()->sortable(),
                Tables\Columns\TextColumn::make('product.name')->label('Producto')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('type')
                    ->label('Tipo')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'in' => 'success',
                        'out' => 'danger',
                        default => 'gray',
                    })
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'in' => 'Entrada',
                        'out' => 'Salida',
                        default => $state,
                    }),
                Tables\Columns\TextColumn::make('quantity')->label('Cantidad')->sortable(),
                Tables\Columns\TextColumn::make('user.name')->label('Registrado por')->default('Sistema'),
                Tables\Columns\TextColumn::make('notes')->label('Notas')->limit(30),
            ])
            ->defaultSort('created_at', 'desc');
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListInventoryMovements::route('/'),
            'create' => Pages\CreateInventoryMovement::route('/create'),
            'edit' => Pages\EditInventoryMovement::route('/{record}/edit'),
        ];
    }
}