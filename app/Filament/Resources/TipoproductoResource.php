<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TipoproductoResource\Pages;
use App\Filament\Resources\TipoproductoResource\RelationManagers;
use App\Models\Tipoproducto;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class TipoproductoResource extends Resource
{
    protected static ?string $model = Tipoproducto::class;

    protected static ?string $navigationIcon = 'heroicon-s-briefcase';
    protected static ?string $navigationGroup = 'Productos Glifoo';
    protected static ?string $navigationLabel = 'Tipo de productos';
    protected static ?string $pluralModelLabel = 'Tipo de producto';

    protected static ?int $navigationSort = 8;

    

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                 Forms\Components\TextInput::make('nombre')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('detalle')
                    ->required()
                    ->maxLength(255),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('nombre')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('detalle')
                    ->searchable()
                    ->sortable(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                   
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
            'index' => Pages\ListTipoproductos::route('/'),
            'create' => Pages\CreateTipoproducto::route('/create'),
            'edit' => Pages\EditTipoproducto::route('/{record}/edit'),
        ];
    }
}
