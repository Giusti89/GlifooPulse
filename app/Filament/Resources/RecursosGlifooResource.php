<?php

namespace App\Filament\Resources;

use App\Filament\Resources\RecursosGlifooResource\Pages;
use App\Filament\Resources\RecursosGlifooResource\RelationManagers;
use App\Models\Enlace;
use App\Models\RecursosGlifoo;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Str;


class RecursosGlifooResource extends Resource
{
    protected static ?string $model = Enlace::class;
    protected static ?string $navigationLabel = 'Redes Sociales';
    protected static ?string $pluralModelLabel = 'Redes Sociales';
    protected static ?string $navigationGroup = 'Recursos Glifoo';
    protected static ?string $navigationIcon = 'heroicon-s-chat-bubble-left-right';
    protected static ?int $navigationSort = 7;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('nombre')
                    ->label(' Nombre red social')
                    ->helperText('Nombre de la red social.')
                    ->required()
                    ->maxLength(255),

                Forms\Components\FileUpload::make('logo_path')
                    ->image()
                    ->imageEditor()
                    ->directory('RedesSociales')
                    ->required(),

                Forms\Components\Select::make('tipored_id')
                    ->label('Tipo red')
                    ->relationship('tipored', 'nombre')
                    ->required()
                    ->default(fn() => request()->query('tipored_id'))
                    ->live(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                tables\Columns\TextColumn::make('nombre')
                    ->label('Nombre'),

                tables\Columns\TextColumn::make('tipored.nombre')
                    ->searchable()
                    ->label('Tipo de enlace'),

                Tables\Columns\ImageColumn::make('logo_path')
                    ->label('Logo')
                    ->disk('public'),
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
            'index' => Pages\ListRecursosGlifoos::route('/'),
            'create' => Pages\CreateRecursosGlifoo::route('/create'),
            'edit' => Pages\EditRecursosGlifoo::route('/{record}/edit'),
        ];
    }
}
