<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ContenidoResource\Pages;
use App\Filament\Resources\ContenidoResource\RelationManagers;
use App\Models\Contenido;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Str;
use Filament\Forms\Components\Section;

class ContenidoResource extends Resource
{
    protected static ?string $model = Contenido::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?int $navigationSort = 3;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Basico')
                    ->columns(2)
                    ->schema([
                        Forms\Components\TextInput::make('texto')
                            ->required()
                            ->maxLength(255),

                        Forms\Components\TextInput::make('pie')
                            ->required()
                            ->maxLength(255),

                        Forms\Components\FileUpload::make('logo_url')
                            ->image()
                            ->imageEditor()
                            ->directory(function () {
                                $user = auth()->user();

                                return 'paquetes/' . Str::slug($user->name);
                            })
                            ->required(),
                        Forms\Components\FileUpload::make('banner_url')
                            ->image()
                            ->imageEditor()
                            ->directory(function () {
                                $user = auth()->user();

                                return 'paquetes/' . Str::slug($user->name);
                            })
                            ->required(),
                    ]),

            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                tables\Columns\TextColumn::make('spot_id')
                    ->searchable(),

                tables\Columns\TextColumn::make('texto'),

                tables\Columns\TextColumn::make('pie')
                    ->searchable(),

                Tables\Columns\ImageColumn::make('banner_url')
                    ->disk('public')
                    ->label('Banner'),

                Tables\Columns\ImageColumn::make('logo_url')
                    ->disk('public')
                    ->label('Logo'),
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
            'index' => Pages\ListContenidos::route('/'),
            'create' => Pages\CreateContenido::route('/create'),
            'edit' => Pages\EditContenido::route('/{record}/edit'),
        ];
    }
}
