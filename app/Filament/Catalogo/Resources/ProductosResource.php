<?php

namespace App\Filament\Catalogo\Resources;

use App\Filament\Catalogo\Resources\ProductosResource\Pages;
use App\Filament\Catalogo\Resources\ProductosResource\RelationManagers;
use App\Models\Categoria;
use App\Models\Producto;
use App\Models\Productos;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Actions\ActionGroup;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Filament\Tables\Filters\SelectFilter;



class ProductosResource extends Resource
{
    protected static ?string $model = Producto::class;

    protected static ?string $navigationIcon = 'heroicon-c-tv';
    protected static ?string $navigationLabel = 'Productos';
    protected static ?string $pluralModelLabel = 'productos';
    protected static ?string $navigationGroup = 'Configuracion Catalogo';

    protected static ?int $navigationSort = 3;

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->whereHas('categoria.spot.suscripcion', function ($query) {
                $query->where('user_id', auth()->id());
            });
    }
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('categoria_id')
                    ->label('Seleccione la categoría')
                    ->options(fn() => Categoria::getCachedForSpot())
                    ->searchable()
                    ->required()
                    ->default(function () {

                        if (
                            request()->has('tableFilters') &&
                            isset(request()->tableFilters['categoria_id']['value'])
                        ) {
                            return request()->tableFilters['categoria_id']['value'];
                        }


                        if (request()->has('categoria_id')) {
                            return request()->categoria_id;
                        }

                        return null;
                    }),

                Forms\Components\TextInput::make('nombre')
                    ->label('Nombre Producto')
                    ->required()
                    ->maxLength(255),

                Forms\Components\Textarea::make('descripcion')
                    ->label('Descripción')
                    ->nullable()
                    ->maxLength(500),

                Forms\Components\TextInput::make('precio')
                    ->label('Precio')
                    ->numeric()
                    ->required(),

                Forms\Components\Select::make('estado')
                    ->label('Estado')
                    ->options([
                        'Disponible' => 'Disponible',
                        'Agotado' => 'Agotado',
                        'No disponible' => 'No disponible',
                        'Proximamente' => 'Proximamente',
                    ])
                    ->default('Disponible')
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                tables\Columns\TextColumn::make('nombre')
                    ->searchable()
                    ->label('Nombre Producto'),

                tables\Columns\TextColumn::make('orden')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->label('Orden del   Producto'),

                tables\Columns\TextColumn::make('descripcion')
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->label('Descripcion Producto'),

                tables\Columns\TextColumn::make('categoria.nombre')
                    ->label('categoria del producto'),

                tables\Columns\TextColumn::make('estado')
                    ->label('Estado')
                    ->badge()
                    ->color(fn(string $state): string => match ($state) {
                        'No disponible' => 'warning',
                        'Agotado' => 'danger',
                        'Disponible' => 'success',
                        'Proximamente' => 'primary'
                    }),

                tables\Columns\TextColumn::make('precio')
                    ->label('Precio'),
            ])
            ->filters([
                SelectFilter::make('categoria_id')
                    ->label('Filtrar por categoría')
                    ->options(fn() => \App\Models\Categoria::getCachedForSpot())
                    ->searchable()
                    ->preload(),

                // FILTRO POR ESTADO (opcional)
                SelectFilter::make('estado')
                    ->label('Estado')
                    ->options([
                        'disponible' => 'Disponible',
                        'agotado' => 'Agotado',
                        'próximamente' => 'Próximamente',
                    ]),
            ])
            ->persistFiltersInSession()

            ->actions([
                ActionGroup::make([
                    Tables\Actions\EditAction::make()
                        ->color('primary'),
                    Tables\Actions\DeleteAction::make(),

                    Tables\Actions\Action::make('crearimagen')
                        ->label('Crear Imagen')
                        ->icon('heroicon-m-plus-circle')
                        ->color('success')
                        ->url(fn(Producto $record): string => ImagenProductosResource::getUrl('create', [
                            'producto_id' => $record->id
                        ])),
                ])
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
            'index' => Pages\ListProductos::route('/'),
            'create' => Pages\CreateProductos::route('/create'),
            'edit' => Pages\EditProductos::route('/{record}/edit'),
        ];
    }
}
