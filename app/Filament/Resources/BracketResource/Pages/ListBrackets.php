<?php

namespace App\Filament\Resources\BracketResource\Pages;

use App\Filament\Resources\BracketResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListBrackets extends ListRecords
{
    protected static string $resource = BracketResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
