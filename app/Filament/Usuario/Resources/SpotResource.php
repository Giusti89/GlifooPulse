<?php

namespace App\Filament\Usuario\Resources;

use App\Filament\Usuario\Resources\SpotResource\Pages;
use App\Filament\Usuario\Resources\SpotResource\RelationManagers;
use App\Models\Spot;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class SpotResource extends Resource
{
    protected static ?string $model = Spot::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->where('user_id', auth()->user()->id);
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                //
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                tables\Columns\TextColumn::make('tiutlo')
                    ->searchable(),

                tables\Columns\TextColumn::make('slug')
                    ->searchable(),

                tables\Columns\TextColumn::make('user.name')
                    ->searchable(),

                tables\Columns\TextColumn::make('estado')
                    ->searchable(),

                Tables\Columns\TextColumn::make('user.suscripciones.paquete.landing.nombre')
                    ->label('Tipo depublicidad')
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
            'index' => Pages\ListSpots::route('/'),
            
            'edit' => Pages\EditSpot::route('/{record}/edit'),
        ];
    }
}
