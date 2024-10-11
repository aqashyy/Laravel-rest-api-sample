<?php

namespace App\Filament\Resources\ApiTokenResource\Pages;

use App\Filament\Resources\ApiTokenResource;
use Filament\Resources\Pages\Page;

class TokenCreation extends Page
{
    protected static string $resource = ApiTokenResource::class;

    protected static string $view = 'filament.resources.api-token-resource.pages.token-creation';
}
