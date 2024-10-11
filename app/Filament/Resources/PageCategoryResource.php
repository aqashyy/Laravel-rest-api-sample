<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PageCategoryResource\Pages;
use App\Filament\Resources\PageCategoryResource\RelationManagers;
use App\Models\PageCategory;
use Filament\Forms;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class PageCategoryResource extends Resource
{
    protected static ?string $model = PageCategory::class;

    protected static ?string $navigationIcon = 'feathericon-layers';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('title'),
                Select::make('parent_id')
                ->options(PageCategory::all()->pluck('title','id'))
                ->default('0')
                ->label('Select Parent'),
                Toggle::make('state')
                ->default('1'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('title'),
                ToggleColumn::make('state')
                ->onColor('success')
                ->offColor('danger'),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
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
            'index' => Pages\ListPageCategories::route('/'),
            'create' => Pages\CreatePageCategory::route('/create'),
            'edit' => Pages\EditPageCategory::route('/{record}/edit'),
        ];
    }
}
