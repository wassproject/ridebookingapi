<?php

namespace App\Filament\Resources\TimeRangeResource\Pages;

use App\Filament\Resources\TimeRangeResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditTimeRange extends EditRecord
{
    protected static string $resource = TimeRangeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
