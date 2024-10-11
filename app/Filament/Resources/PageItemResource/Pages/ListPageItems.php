<?php

namespace App\Filament\Resources\PageItemResource\Pages;

use App\Filament\Resources\PageItemResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListPageItems extends ListRecords
{
    protected static string $resource = PageItemResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
