<?php

namespace App\Filament\Resources;

use App\Filament\Resources\EnlaceLandingResource\Pages;
use App\Filament\Resources\EnlaceLandingResource\RelationManagers;
use App\Models\Enlace;
use App\Models\EnlaceLanding;
use App\Models\Landing;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class EnlaceLandingResource extends Resource
{
    protected static ?string $model = EnlaceLanding::class;

    protected static ?string $navigationLabel = 'Asignar redes sociales';
    protected static ?string $pluralModelLabel = 'Asignar Redes Sociales';
    protected static ?string $navigationGroup = 'Recursos Glifoo';
    protected static ?string $navigationIcon = 'heroicon-s-folder-arrow-down';
    protected static ?int $navigationSort = 9;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('landing_id')
                    ->label('Landing')
                    ->options(Landing::pluck('nombre', 'id'))
                    ->searchable()
                    ->required(),

                Forms\Components\Select::make('enlace_id')
                    ->label('Botón')
                    ->options(Enlace::pluck('nombre', 'id'))
                    ->searchable()
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('landing.paquete.nombre')
                    ->label('Landing')
                    ->sortable()
                    ->searchable(),

                Tables\Columns\TextColumn::make('landing.nombre')
                    ->label('Landing')
                    ->sortable()
                    ->searchable(),

                Tables\Columns\TextColumn::make('enlace.nombre')
                    ->label('Botón')
                    ->sortable()
                    ->searchable(),
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
            'index' => Pages\ListEnlaceLandings::route('/'),
            'create' => Pages\CreateEnlaceLanding::route('/create'),
            'edit' => Pages\EditEnlaceLanding::route('/{record}/edit'),
        ];
    }
}
