<?php

namespace App\Filament\Resources;

use Dom\Text;
use Filament\Forms;
use Filament\Tables;
use App\Models\Purchase;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Filament\Forms\Components\Select;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\DatePicker;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\PurchaseResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\PurchaseResource\RelationManagers;

class PurchaseResource extends Resource
{
    protected static ?string $model = Purchase::class;

    // --- TRADUCCIÓN AUTOMÁTICA Y AGRUPACIÓN ---
    protected static ?string $modelLabel = 'entrada';
    protected static ?string $pluralModelLabel = 'entradas';
    protected static ?string $navigationLabel = 'Entradas';
    protected static ?string $navigationIcon = 'heroicon-o-arrow-down-tray';

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

                Select::make('supplier_id')
                    ->label('Proveedor')
                    ->relationship('supplier', 'name')
                    ->searchable()
                    ->preload()
                    ->createOptionForm([
                        TextInput::make('name')
                            ->label('Nombre del Proveedor')
                            ->required()
                            ->maxLength(255),
                        TextInput::make('contact')
                            ->label('Contacto / Teléfono')
                            ->tel()
                            ->required()
                            ->maxLength(11),
                    ])
                    ->required(),

                TextInput::make('quantity')
                    ->label('Cantidad')
                    ->numeric()
                    ->minValue(1)
                    ->required(),

                DatePicker::make('purchased_at')
                    ->label('Fecha de Entrada')
                    ->default(now())
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('product.name')->label('Producto')->searchable()->sortable(),
                TextColumn::make('supplier.name')->label('Proveedor')->searchable()->sortable(),
                TextColumn::make('quantity')->label('Cantidad')->sortable()->badge(),
                TextColumn::make('purchased_at')->label('Fecha')->date()->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('product')->label('Producto')->relationship('product', 'name'),
                Tables\Filters\SelectFilter::make('supplier')->label('Proveedor')->relationship('supplier', 'name'),
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
            'index' => Pages\ListPurchases::route('/'),
            'create' => Pages\CreatePurchase::route('/create'),
            'edit' => Pages\EditPurchase::route('/{record}/edit'),
        ];
    }
}