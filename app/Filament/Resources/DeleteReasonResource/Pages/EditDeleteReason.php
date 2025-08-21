<?php

namespace App\Filament\Resources\DeleteReasonResource\Pages;

use App\Filament\Resources\DeleteReasonResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditDeleteReason extends EditRecord
{
    protected static string $resource = DeleteReasonResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
