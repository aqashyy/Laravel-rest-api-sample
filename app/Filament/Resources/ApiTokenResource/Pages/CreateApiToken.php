<?php

namespace App\Filament\Resources\ApiTokenResource\Pages;

use App\Filament\Resources\ApiTokenResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Auth;

class CreateApiToken extends CreateRecord
{
    protected static string $resource = ApiTokenResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {

        // Revoke the user's existing token (if any)
        Auth::user()->tokens()->delete();

        // Create the new personal access token
        $token = Auth::user()->createToken($data['name']);

        // Return the token's plain text representation
        return array_merge($data, ['token' => $token->plainTextToken]);
    }
}
