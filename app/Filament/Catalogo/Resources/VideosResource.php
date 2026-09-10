<?php

namespace App\Filament\Catalogo\Resources;

use App\Filament\Catalogo\Resources\VideosResource\Pages;
use App\Filament\Catalogo\Resources\VideosResource\RelationManagers;
use App\Models\Categoria;
use App\Models\Video;
use App\Models\Videos;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Forms\Components\Section;
use Illuminate\Support\Facades\Auth;


class VideosResource extends Resource
{
    protected static ?string $model = Video::class;


    protected static ?string $navigationIcon = 'heroicon-o-film';
    protected static ?string $navigationLabel = 'Video Publicitarios';
    protected static ?string $navigationGroup = 'Catalogo de productos';
    protected static ?string $pluralModelLabel = 'Galeria de imagenes';
    protected static ?int $navigationSort = 5;

    public static function shouldRegisterNavigation(): bool
    {
        $user = auth()->user();

        if (!$user) {
            return false;
        }

        // Usamos una única llave 'user_onboarding_check_' compartida por TODOS los recursos.
        // El primer recurso que se cargue hará la consulta; los demás leerán de la RAM directamente.
        return cache()->driver('array')->remember('user_onboarding_check_' . $user->id, 60, function () use ($user) {

            // 1. Obtenemos la suscripción activa
            $suscripcion = $user->getSuscripcionActiva();

            // Si la suscripción viene como colección por algún motivo, extraemos el primero
            if ($suscripcion instanceof \Illuminate\Support\Collection) {
                $suscripcion = $suscripcion->first();
            }

            if (!$suscripcion) {
                return false;
            }

            // 2. Buscamos el spot de forma segura
            $spot = $suscripcion->spot;
            if ($spot instanceof \Illuminate\Support\Collection) {
                $spot = $spot->first();
            }

            if (!$spot) {
                return false;
            }

            // 3. Verificamos si ya completó el onboarding (si tiene al menos una categoría)
            // Consulta indexada ultra veloz que tarda microsegundos
            return \App\Models\Categoria::where('spot_id', $spot->id)->exists();
        });
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
        return $form
            ->schema([
                Section::make('Social')
                    ->columns(2)
                    ->schema([
                        Forms\Components\TextInput::make('titulo')
                            ->label('titulo del video')
                            ->required()
                            ->helperText('Ingrese titulo del video')
                            ->maxLength(255),

                        Forms\Components\TextInput::make('url')
                            ->label('url del video')
                            ->required()
                            ->helperText('Ingrese url del video')
                            ->maxLength(255),

                        Forms\Components\TextInput::make('orden')
                            ->label('Orden')
                            ->numeric()
                            ->default(0)
                            ->required()
                            ->minValue(0)
                            ->maxValue(100)
                            ->helperText('Define el orden de visualización (0 = primero)'),

                        Forms\Components\Toggle::make('estado')
                            ->label('estado del video')
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
                    ->label('Titulo'),
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
            'index' => Pages\ListVideos::route('/'),
            'create' => Pages\CreateVideos::route('/create'),
            'edit' => Pages\EditVideos::route('/{record}/edit'),
        ];
    }
}
