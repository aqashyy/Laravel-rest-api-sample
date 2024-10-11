<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PageItemResource\Pages;
use App\Filament\Resources\PageItemResource\RelationManagers;
use App\Models\PageCategory;
use App\Models\PageItem;
use Filament\Forms;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Form;
use Filament\Forms\Set;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Str;
class PageItemResource extends Resource
{
    protected static ?string $model = PageItem::class;

    protected static ?string $navigationIcon = 'feathericon-list';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('title')
                ->required()
                ->live(onBlur:true)
                ->afterStateUpdated(fn (string $operation, $state, Set $set) =>
                ($operation === 'create' || $operation === 'edit') ? $set('alias' ,Str::slug($state)) : ''),

                TextInput::make('alias')
                ->required()
                ->dehydrated(),

                Select::make(name: 'type')
                ->label('Select Category')
                ->options(PageCategory::all()->pluck('title','id'))
                ->required(),

                RichEditor::make('description')->columnSpan(2),

                Toggle::make('state')
                ->default('1'),
                // TextInput::make('title'),
                // TextInput::make('title'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('title'),
                TextColumn::make('description')->limit(10),
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
            'index' => Pages\ListPageItems::route('/'),
            'create' => Pages\CreatePageItem::route('/create'),
            'edit' => Pages\EditPageItem::route('/{record}/edit'),
        ];
    }
}
