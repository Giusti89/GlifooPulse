<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SpotResource\Pages;
use App\Filament\Resources\SpotResource\RelationManagers;
use App\Models\Spot;
use Filament\Forms;
use Filament\Forms\Components\Section;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Forms\Set;
use Illuminate\Support\Str;

class SpotResource extends Resource
{
    protected static ?string $model = Spot::class;

    protected static ?string $navigationIcon = 'heroicon-s-sparkles';
    protected static ?string $navigationLabel = 'Landings Usuarios';
    protected static ?string $pluralModelLabel = 'Landingpages Usuarios';


    protected static ?int $navigationSort = 4;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Publicidad')
                    ->columns(3)
                    ->schema([
                        Forms\Components\TextInput::make('titulo')
                            ->required()
                            ->maxLength(255),

                        Forms\Components\TextInput::make('slug')
                            ->required()
                            ->live(onBlur: true)
                            ->afterStateUpdated(fn(Set $set, ?string $state) => $set('slug', Str::slug($state)))
                            ->maxLength(255),
                            
                        Forms\Components\Toggle::make('estado')
                            ->label('Publicar web')
                            ->hiddenOn(['create'])
                            ->default(false),

                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([

                tables\Columns\TextColumn::make('id')
                    ->searchable(),

                tables\Columns\TextColumn::make('titulo')
                    ->searchable(),

                tables\Columns\TextColumn::make('slug')
                    ->searchable(),

                tables\Columns\TextColumn::make('suscripcion.user.name')
                    ->searchable(),

                tables\Columns\TextColumn::make('estado')
                    ->searchable(),

                Tables\Columns\TextColumn::make('suscripcion.paquete.landing.nombre')
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
