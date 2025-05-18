<?php

namespace App\Filament\Resources\BracketResource\Pages;

use App\Filament\Resources\BracketResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditBracket extends EditRecord
{
    protected static string $resource = BracketResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
