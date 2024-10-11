<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ApiTokenResource\Pages;
use App\Filament\Resources\ApiTokenResource\RelationManagers;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Actions\Action;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Auth;

class ApiTokenResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('name')
                ->label('Token Name')
                ->required()
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')->label('User'),
                TextColumn::make('api_key')->label('Api Key')
                ->badge()
                ->copyable(true)
                ->tooltip('Click to copy'),
            ])
            ->filters([
                //
            ])
            ->actions([
                Action::make('ReGenerate Token')
                ->label('Generate Key')
                ->action(function (User $user) {
                    // dd($data);
                    $userId = Auth::user()->id;
                    $token = $user->generateKey($userId);
                    // $user->tokens()->delete(); // Revokes all tokens
                    // $user = Auth::user();
                    // $token = $user->createToken('front-end-api');
                    Notification::make()
                    ->color('success')
                    ->icon('feathericon-settings')
                    ->title('New Api Key')
                    ->body($token)
                    ->send();
                })
                ->requiresConfirmation()
                ->icon('feathericon-refresh-ccw')
                ->color('danger'),
                // Tables\Actions\EditAction::make()->label('regenerate'),
            ])
            ->bulkActions([
                // Tables\Actions\BulkActionGroup::make([
                //     Tables\Actions\DeleteBulkAction::make(),
                // ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListApiTokens::route('/'),
            'create' => Pages\TokenCreation::route('/create'),
            // 'edit' => Pages\EditApiToken::route('/{record}/edit'),
        ];
    }
}
