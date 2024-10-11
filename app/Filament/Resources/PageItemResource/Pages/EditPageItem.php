<?php

namespace App\Filament\Resources\PageItemResource\Pages;

use App\Filament\Resources\PageItemResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditPageItem extends EditRecord
{
    protected static string $resource = PageItemResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
