<?php

namespace App\Filament\Resources;

use Filament\Forms;
use App\Models\Sale;
use Filament\Tables;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Filament\Forms\Components\Select;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\DatePicker;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\SaleResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\SaleResource\RelationManagers;

class SaleResource extends Resource
{
    protected static ?string $model = Sale::class;

    // --- TRADUCCIÓN AUTOMÁTICA Y AGRUPACIÓN ---
    protected static ?string $modelLabel = 'salida';
    protected static ?string $pluralModelLabel = 'salidas';
    protected static ?string $navigationLabel = 'Salidas';
    protected static ?string $navigationIcon = 'heroicon-o-arrow-up-tray';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('product_id')
                    ->label('Producto')
                    ->relationship('product', 'name')
                    ->preload()
                    ->searchable()
                    ->required(),

                TextInput::make('quantity')
                    ->label('Cantidad')
                    ->numeric()
                    ->minValue(1)
                    ->required(),

                DatePicker::make('sold_at')
                    ->label('Fecha de Salida')
                    ->default(now())
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('product.name')->label('Producto')->searchable()->sortable(),
                TextColumn::make('quantity')->label('Cantidad')->sortable()->badge(),
                TextColumn::make('sold_at')->label('Fecha')->date()->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('product')->label('Producto')->relationship('product', 'name'),
            ])
            ->actions([
                // Tables\Actions\EditAction::make(),
                // Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListSales::route('/'),
            'create' => Pages\CreateSale::route('/create'),
            'edit' => Pages\EditSale::route('/{record}/edit'),
        ];
    }
}