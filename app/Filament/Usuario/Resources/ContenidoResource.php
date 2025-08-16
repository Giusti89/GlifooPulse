<?php

namespace App\Filament\Usuario\Resources;

use App\Filament\Usuario\Resources\ContenidoResource\Pages;
use App\Filament\Usuario\Resources\ContenidoResource\RelationManagers;
use App\Models\Contenido;
use App\Models\Suscripcion;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Forms\Components\Section;
use Illuminate\Support\Str;
use Filament\Forms\Components\ColorPicker;
use Filament\Forms\Components\Livewire;
use Dotswan\MapPicker\Fields\Map;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Set;

class ContenidoResource extends Resource
{
    protected static ?string $model = Contenido::class;

    protected static ?string $navigationIcon = 'heroicon-c-folder';
    protected static ?string $navigationLabel = 'Configuracion Contenidos';
    protected static ?string $pluralModelLabel = 'Configuracion Contenido';
    protected static ?string $navigationGroup = 'Configuracion pagina web';

    protected static ?int $navigationSort = 2;

    protected static function getPlanConditions(): array
    {
        static $conditions;

        if ($conditions === null) {
            $user = auth()->user();
            $suscripcion = $user->suscripcion;

            $conditions = [
                'isFreePlan' => optional($suscripcion->paquete)->precio == 0,
                'isEnterprisePlan' => $suscripcion->estado &&
                    str_contains(optional($suscripcion->paquete)->nombre, 'Glifoo Enterprise'),
            ];
        }

        return $conditions;
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->whereHas('spot.suscripcion', function ($query) {
                $query->where('user_id', auth()->id());
            });
    }


    public static function form(Form $form): Form
    {

        $conditions = self::getPlanConditions();

        $isFreePlan = fn() => optional(auth()->user()->suscripcion->paquete)->precio == 0;
        return $form
            ->schema([
                Section::make('Descripción')
                    ->columns(1)
                    ->schema([
                        Forms\Components\Textarea::make('texto')
                            ->label('Descripción')
                            ->required()
                            ->maxLength(400),

                        Forms\Components\Textarea::make('pie')
                            ->label('Dirección')
                            ->maxLength(255)
                            ->required(!$conditions['isFreePlan'])
                            ->hidden($conditions['isFreePlan'])
                            ->disabled($conditions['isFreePlan']),


                        ColorPicker::make('background')
                            ->label('Color de fondo')
                            ->helperText('Se admiten valores hexadecimales')
                            ->default('#ffffff')
                            ->rgb(),

                        ColorPicker::make('ctexto')
                            ->label('Color del los textos')
                            ->default('#ffffff')
                            ->helperText('Se admiten valores hexadecimales')
                            ->rgb(),
                    ]),
                Section::make('Contenido digital')
                    ->columns(2)
                    ->schema([

                        Forms\Components\FileUpload::make('banner_url')
                            ->image()
                            ->imageEditor()
                            ->directory(function () {
                                $user = auth()->user();
                                return 'paquetes/' . Str::slug($user->name);
                            })
                            ->required(!$isFreePlan)
                            ->hidden($isFreePlan)
                            ->disabled($isFreePlan),

                        Forms\Components\FileUpload::make('logo_url')
                            ->image()
                            ->imageEditor()
                            ->directory(function () {
                                $user = auth()->user();

                                return 'paquetes/' . Str::slug($user->name);
                            })
                            ->required(),
                    ]),
                Section::make('Ubicación en el mapa')
                    ->columns(1)
                    ->visible(function ($get, $record) {
                        // Obtener el usuario actual
                        $user = auth()->user();

                        // Verificar si tiene suscripción activa al plan Medium
                        $tienePlanMedium = Suscripcion::where('user_id', $user->id)
                            ->where('estado', 1) // Asumiendo que 1 es activo
                            ->whereHas('paquete', function ($query) {
                                $query->where('nombre', 'like', '%Glifoo Enterprise%');
                            })
                            ->where('fecha_fin', '>=', now())
                            ->exists();

                        return $tienePlanMedium;
                    })
                    ->schema([
                        Hidden::make('latitude')
                            ->default(-17.7833)
                            ->reactive(),

                        Hidden::make('longitude')
                            ->default(-63.1821)
                            ->reactive(),

                        Map::make('location')
                            ->label('Ubicación')
                            ->columnSpanFull()
                            ->defaultLocation(latitude: -17.7833, longitude: -63.1821)
                            ->draggable(true)
                            ->clickable(true)
                            ->zoom(15)
                            ->tilesUrl("https://tile.openstreetmap.de/{z}/{x}/{y}.png")
                            ->afterStateUpdated(function (Set $set, ?array $state): void {
                                if ($state) {
                                    $set('latitude', $state['lat']);
                                    $set('longitude', $state['lng']);
                                }
                            })
                            ->afterStateHydrated(function (Set $set, $state, $record): void {
                                $set('location', [
                                    'lat' => $record->latitude ?? -17.7833,
                                    'lng' => $record->longitude ?? -63.1821
                                ]);
                            })
                    ])


            ]);
    }

    public static function table(Table $table): Table
    {
        $conditions = self::getPlanConditions();
        return $table
            ->columns([
                tables\Columns\TextColumn::make('spot.titulo')
                    ->label('Titulo')
                    ->searchable(),

                Tables\Columns\ImageColumn::make('banner_url')
                    ->disk('public')
                    ->label('Banner')
                    ->hidden($conditions['isFreePlan']),

                Tables\Columns\ImageColumn::make('logo_url')
                    ->disk('public')
                    ->label('Logo'),


                tables\Columns\TextColumn::make('texto')
                    ->limit(20),


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

            'edit' => Pages\EditContenido::route('/{record}/edit'),
        ];
    }
}
