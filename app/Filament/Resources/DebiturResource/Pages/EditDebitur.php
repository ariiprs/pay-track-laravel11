<?php

namespace App\Filament\Resources\DebiturResource\Pages;

use App\Filament\Resources\DebiturResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditDebitur extends EditRecord
{
    protected static string $resource = DebiturResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
