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
use Filament\Forms\Components\Section;
use Filament\Forms\Set;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;



class SpotResource extends Resource
{
    protected static ?string $model = Spot::class;

    protected static ?string $navigationIcon = 'heroicon-m-wrench';
    protected static ?string $navigationLabel = 'Configuracion Inicial';
    protected static ?string $pluralModelLabel = 'Configuracion Inicial';
    protected static ?int $navigationSort = 1;


    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->whereHas('suscripcion', function ($query) {
                $query->where('user_id', auth()->id());
            });
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Publicidad')
                    ->columns(3)
                    ->schema([
                        Forms\Components\TextInput::make('titulo')
                            ->label('Titulo')
                            ->required()
                            ->maxLength(255),

                        Forms\Components\TextInput::make('slug')
                            ->label('Nombre Link')
                            ->prefix('https://glifoo.org/')
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
                tables\Columns\TextColumn::make('titulo')
                    ->label('Título'),

                tables\Columns\TextColumn::make('slug')
                    ->label('Url'),

                Tables\Columns\TextColumn::make('suscripcion.paquete.landing.nombre')
                    ->label('Tipo de publicidad')
                    ->sortable(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make()
                    ->label('Llenar datos'),

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
