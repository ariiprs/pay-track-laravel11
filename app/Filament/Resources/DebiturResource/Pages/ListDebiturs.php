<?php

namespace App\Filament\Resources\DebiturResource\Pages;

use App\Filament\Resources\DebiturResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListDebiturs extends ListRecords
{
    protected static string $resource = DebiturResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
