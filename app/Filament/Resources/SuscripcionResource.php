<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SuscripcionResource\Pages;
use App\Filament\Resources\SuscripcionResource\RelationManagers;
use App\Models\Paquete;
use App\Models\Suscripcion;
use Carbon\Carbon;
use DragonCode\PrettyArray\Services\Formatters\Php;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Tables\Actions\Action;
use Illuminate\Support\Facades\Notification;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Enums\FiltersLayout;

class SuscripcionResource extends Resource
{
    protected static ?string $model = Suscripcion::class;


    protected static ?string $navigationLabel = 'Suscripciones';
    protected static ?string $navigationIcon = 'heroicon-m-pencil';
    protected static ?string $navigationGroup = 'Datos de Usuarios';
    protected static ?string $pluralModelLabel = 'Suscripciones';


    protected static ?int $navigationSort = 2;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('user_id')
                    ->label('Usuario')
                    ->relationship('user', 'name')
                    ->searchable()
                    ->preload()
                    ->required(),

                Forms\Components\Select::make('paquete_id')
                    ->label('Paquete')
                    ->relationship('paquete', 'nombre')
                    ->searchable()
                    ->preload()
                    ->required(),

                Forms\Components\DatePicker::make('fecha_inicio')
                    ->default(now())
                    ->required()
                    ->readOnly()
                    ->live()
                    ->afterStateUpdated(function (Get $get, Set $set) {
                        if ($get('meses_suscripcion') && $get('fecha_inicio')) {
                            $fechaFin = Carbon::parse($get('fecha_inicio'))
                                ->addMonths($get('meses_suscripcion'));
                            $set('fecha_fin', $fechaFin);
                        }
                    }),

                Forms\Components\TextInput::make('meses_suscripcion')
                    ->label('Meses de suscripción')
                    ->numeric()
                    ->minValue(1)
                    ->default(1)

                    ->live()
                    ->afterStateUpdated(function (Get $get, Set $set) {
                        if ($get('fecha_inicio')) {
                            $fechaFin = Carbon::parse($get('fecha_inicio'))
                                ->addMonths($get('meses_suscripcion'));
                            $set('fecha_fin', $fechaFin);
                        }
                    }),

                Forms\Components\DatePicker::make('fecha_fin')
                    ->label('Fecha de finalización')
                    ->required()
                    ->readOnly(),

                Forms\Components\Toggle::make('estado')
                    ->label('Estado Activo')
                    ->hiddenOn(['create'])
                    ->default(false),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('user.name')
                    ->label('Nombre de usuario')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('user.lastname')
                    ->label('Apellido de usuario')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('user.email')
                    ->label('Email de usuario')
                    ->numeric()
                    ->sortable(),

                Tables\Columns\TextColumn::make('paquete.nombre')
                    ->numeric()
                    ->sortable(),

                Tables\Columns\IconColumn::make('estado')
                    ->label('Estado suscripcion')
                    ->boolean()
                    ->sortable(),
                Tables\Columns\TextColumn::make('fecha_inicio')
                    ->date()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\TextColumn::make('fecha_fin')
                    ->date()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\TextColumn::make('dias_restantes_texto')
                    ->label('Días restantes')
                    ->color(function ($record) {
                        return $record->dias_restantes_color;
                    }),

            ])
            ->filters([
                SelectFilter::make('paquete_id')
                    ->label('Filtrar por paquete')
                    ->options(Paquete::all()->pluck('nombre', 'id'))
                    ->searchable()
                    ->preload(),
            ], layout: FiltersLayout::AboveContent)

            ->persistFiltersInSession()

            ->actions([
                // Tables\Actions\EditAction::make(),

            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([]),
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
            'index' => Pages\ListSuscripcions::route('/'),
            'create' => Pages\CreateSuscripcion::route('/create'),
            'edit' => Pages\EditSuscripcion::route('/{record}/edit'),
        ];
    }
}
