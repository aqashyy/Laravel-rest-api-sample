<?php

namespace App\Filament\Resources;

use App\Exports\MenuItemsExport;
use App\Filament\Resources\MenuItemResource\Pages;
use App\Filament\Resources\MenuItemResource\RelationManagers;
use App\Models\MenuCategory;
use App\Models\MenuItem;
use App\Models\PageItem;
use Filament\Forms;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Forms\Set;
use Filament\Forms\Get;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Str;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Group;
use Filament\Tables\Actions\BulkAction;
use Illuminate\Database\Eloquent\Collection;
use Maatwebsite\Excel\Facades\Excel;

class MenuItemResource extends Resource
{
    protected static ?string $model = MenuItem::class;

    protected static ?string $navigationIcon = 'feathericon-list';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('title')
                ->live(onBlur:true)
                ->afterStateUpdated(fn (string $operation, $state, Set $set) =>
                        $operation === 'edit' || $operation === 'create' ? $set('alias', Str::slug($state)): null),

                TextInput::make('alias')
                ->required()
                ->dehydrated()
                ->unique(MenuItem::class,'alias',ignoreRecord:true),

                Select::make('parent_id')
                ->label('Parent Menu')
                ->default('0')
                ->options(function (callable $get) {
                    $categoryId = $get('type');
                    if($categoryId)
                    {
                        $menuitems = MenuItem::where('type',$categoryId)->pluck('title','id')->toArray();
                        return [
                            '0' => 'No Parent'
                        ]+ $menuitems;
                    }
                    return ['0' => 'No parent'];
                })->placeholder('Select Parent Menu'),

                Select::make('target')
                ->options([
                    '_blank'    =>  'Open menu in new tab',
                    '_self'     =>  'Open menu in same tab'
                ])
                ->default('_self'),


                Select::make('type')
                ->label('Category')
                ->options(MenuCategory::all()->pluck('title','id'))
                ->live()
                ->afterStateUpdated(fn (callable $set) => $set('parent_id', null)),


                Select::make('viewtype')
                ->options([
                    'page'      => 'Page',
                    'category'  => 'Category',
                    'link'  => 'Link'
                ])
                ->default('page')
                ->live()
                ->afterStateUpdated(fn (Select $component) => $component
                    ->getContainer()
                    ->getComponent('viewtypecomponent')
                    ->getChildComponentContainer()
                    ->fill()
                ),
                Group::make()
                    ->schema(fn (Get $get): array => match ($get('viewtype')) {
                        'page' => [
                            Select::make('linkaddress')
                                ->label('Select Page')
                                ->options(PageItem::all()->pluck('title','id'))
                                ->required(),
                            ],
                        'link' => [
                            TextInput::make(name: 'linkaddress')->label('Link'),
                        ],
                        default => [],
                    })
                    ->key('viewtypecomponent'),
                TextInput::make('menuorderid')
                ->integer()
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('title'),
                TextColumn::make('alias')
                ->prefix('/'),
                TextColumn::make('category.title'),
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
                BulkAction::make('export')
                ->label('Export to excel')
                ->action(function (Collection $records){

                    return Excel::download(new MenuItemsExport($records), 'menu-items.xlsx');

                }),
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
            'index' => Pages\ListMenuItems::route('/'),
            'create' => Pages\CreateMenuItem::route('/create'),
            'edit' => Pages\EditMenuItem::route('/{record}/edit'),
        ];
    }
}
