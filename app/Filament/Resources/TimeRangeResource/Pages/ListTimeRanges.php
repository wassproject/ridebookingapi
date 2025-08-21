<?php

namespace App\Filament\Resources\TimeRangeResource\Pages;

use App\Filament\Resources\TimeRangeResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListTimeRanges extends ListRecords
{
    protected static string $resource = TimeRangeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
