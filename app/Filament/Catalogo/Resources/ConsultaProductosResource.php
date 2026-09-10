<?php

namespace App\Filament\Catalogo\Resources;

use App\Filament\Catalogo\Resources\ConsultaProductosResource\Pages;
use App\Filament\Catalogo\Resources\ConsultaProductosResource\RelationManagers;
use App\Models\Categoria;
use App\Models\ConsultaProducto;
use App\Models\Producto;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Auth;


class ConsultaProductosResource extends Resource
{
    protected static ?string $model = ConsultaProducto::class;

    protected static ?string $navigationIcon = 'heroicon-m-chat-bubble-left';
    protected static ?string $navigationLabel = 'Productos consultados';
    protected static ?string $navigationGroup = 'Consulta de Clientes';
    protected static ?string $pluralModelLabel = 'Productos consultados';

    protected static ?int $navigationSort = 9;

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
            ->whereHas('producto.categoria.spot.suscripcion', function ($query) {
                $query->where('user_id', auth()->id());
            });
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('nombre')
                    ->label('Nombre Solicitante'),
                Forms\Components\TextInput::make('telefono')
                    ->label('Telefono'),
                Forms\Components\Textarea::make('mensaje')
                    ->label('Descripción'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('nombre')
                    ->label('Nombre Solicitante')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('telefono')
                    ->label('Teléfono')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('mensaje')
                    ->label('Descripción')
                    ->limit(50)
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('producto_id')
                    ->label('Filtrar por Producto')
                    ->options(function () {
                        $userId = auth()->id();

                        return Producto::whereHas('categoria.spot.suscripcion', function ($q) use ($userId) {
                            $q->where('user_id', $userId);
                        })
                            ->pluck('nombre', 'id');
                    })
                    ->placeholder('Selecciona un producto'),
            ])
            ->persistFiltersInSession()

            ->actions([
                Tables\Actions\ViewAction::make(),
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
            'index' => Pages\ListConsultaProductos::route('/'),
            'view' => Pages\ViewConsulta::route('/{record}'),
        ];
    }
}
