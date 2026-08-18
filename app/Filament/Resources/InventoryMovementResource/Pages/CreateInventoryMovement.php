<?php

namespace App\Filament\Resources\InventoryMovementResource\Pages;

use App\Filament\Resources\InventoryMovementResource;
use App\Models\Product;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Auth;

class CreateInventoryMovement extends CreateRecord
{
    protected static string $resource = InventoryMovementResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        // Asignar el ID del usuario autenticado
        $data['user_id'] = Auth::id();

        return $data;
    }

    protected function afterCreate(): void
    {
        $movement = $this->record;
        $product = Product::find($movement->product_id);

        if ($product) {
            if ($movement->type === 'in') {
                // Entrada: suma al stock actual
                $product->increment('stock_current', $movement->quantity);
            } elseif ($movement->type === 'out') {
                // Salida: resta al stock actual
                $product->decrement('stock_current', $movement->quantity);
            }
        }
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}