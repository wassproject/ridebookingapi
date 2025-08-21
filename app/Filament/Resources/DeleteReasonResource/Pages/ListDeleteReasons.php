<?php

namespace App\Filament\Resources\DeleteReasonResource\Pages;

use App\Filament\Resources\DeleteReasonResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListDeleteReasons extends ListRecords
{
    protected static string $resource = DeleteReasonResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
