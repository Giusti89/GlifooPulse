<?php

namespace App\Filament\Catalogo\Resources;

use App\Filament\Catalogo\Resources\CategoriaResource\Pages;
use App\Filament\Catalogo\Resources\CategoriaResource\RelationManagers;
use App\Models\Categoria;
use App\Models\Producto;
use Filament\Forms;
use Filament\Forms\Components\Actions\Action;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Actions\ActionGroup;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Section;
use Illuminate\Support\Str;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Auth;

class CategoriaResource extends Resource
{
    protected static ?string $model = Categoria::class;

    protected static ?string $navigationIcon = 'heroicon-m-folder-open';
    protected static ?string $navigationLabel = 'Categoria de Productos';
    protected static ?string $pluralModelLabel = 'Categoria de productos';
    protected static ?string $navigationGroup = 'Catalogo de productos';

    protected static ?int $navigationSort = 2;

    public static function shouldRegisterNavigation(): bool
    {
        $user = auth()->user();

        if (!$user) {
            return false;
        }

        // Verifica si el usuario tiene al menos una categoría a través de la relación
        return Categoria::whereHas('spot.suscripcion', function ($query) use ($user) {
            $query->where('user_id', $user->id);
        })->exists();
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
                Section::make('Información de la Categoría')
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                TextInput::make('nombre')
                                    ->label('Nombre de la Categoría')
                                    ->required()
                                    ->maxLength(255)
                                    ->live(onBlur: true)
                                    // Genera el slug automáticamente si lo necesitas
                                    ->afterStateUpdated(
                                        fn(string $operation, $state, Forms\Set $set) =>
                                        $operation === 'create' ? $set('slug', Str::slug($state)) : null
                                    ),

                                TextInput::make('slug')
                                    ->disabled()
                                    ->dehydrated()
                                    ->required()
                                    ->unique(Categoria::class, 'slug', ignoreRecord: true),

                                Textarea::make('descripcion')
                                    ->label('Descripción')
                                    ->columnSpanFull()
                                    ->rows(2),

                                TextInput::make('orden')
                                    ->label('Orden de Categoría')
                                    ->numeric()
                                    ->default(0),
                            ]),
                    ]),

                // === SECCIÓN: REPEATER DE PRODUCTOS ===
                Section::make('Productos de esta Categoría')
                    ->description('Administra los productos asociados y sus respectivas galerías.')
                    ->schema([
                        Repeater::make('productos')
                            ->relationship('productos') // Relación hasMany en Categoria
                            ->schema([
                                Grid::make(2)
                                    ->schema([
                                        TextInput::make('nombre')
                                            ->label('Nombre del Producto')
                                            ->required()
                                            ->maxLength(255)
                                            ->live(onBlur: true),

                                        TextInput::make('precio')
                                            ->label('Precio')
                                            ->numeric()
                                            ->prefix('Bs.') // Ajusta tu moneda
                                            ->required(),

                                        Textarea::make('descripcion')
                                            ->label('Descripción Corta')
                                            ->columnSpanFull(),

                                        Toggle::make('estado')
                                            ->label('Producto Activo')
                                            ->default(true),

                                        TextInput::make('orden')
                                            ->label('Orden')
                                            ->numeric()
                                            ->default(0),
                                    ]),

                                // === SUB-REPEATER: IMÁGENES DEL PRODUCTO ===
                                Section::make('Galería del Producto')
                                    ->compact()
                                    ->schema([
                                        Repeater::make('imagenes')
                                            ->relationship('imagenes') // Relación hasMany en Producto
                                            ->schema([
                                                FileUpload::make('url')
                                                    ->label('Subir Imagen')
                                                    ->image()
                                                    ->imageEditor()
                                                    ->directory(fn($record) => 'imagenes-productos/' . Str::slug(auth()->user()->name . '-' . auth()->user()->lastname))
                                                    ->maxSize(5120)
                                                    ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/webp','image/jfif'])
                                                    ->required()
                                                    ->live()
                                                    ->preserveFilenames()
                                                    ->rules([
                                                        fn($get) => function (string $attribute, $value, \Closure $fail) use ($get) {
                                                            $user = Auth::user();
                                                            $suscripcion = $user?->getSuscripcionActiva();

                                                            // 1. Validar suscripción en interfaz
                                                            if (! $suscripcion || $suscripcion->estado != 1) {
                                                                Notification::make()
                                                                    ->title('Suscripción requerida')
                                                                    ->body('Necesitas una suscripción activa para subir imágenes.')
                                                                    ->danger()
                                                                    ->send();

                                                                return $fail('Suscripción requerida.');
                                                            }

                                                            $maxImagenes = $suscripcion->paquete?->max_imagenes_producto;
                                                            if ($maxImagenes === null) {
                                                                return;
                                                            }

                                                            $productoId = $get('../../id');
                                                            $imagenesEnBaseDatos = $productoId ? \App\Models\ImagenProducto::where('producto_id', $productoId)->count() : 0;

                                                            $imagenesEnFormulario = count($get('../../imagenes') ?? []);
                                                            $conteoTotal = max($imagenesEnBaseDatos, $imagenesEnFormulario);

                                                            if ($conteoTotal > $maxImagenes) {
                                                                Notification::make()
                                                                    ->title('Límite excedido')
                                                                    ->body("Tu plan ({$suscripcion->paquete?->nombre}) solo permite {$maxImagenes} imágenes por producto.")
                                                                    ->danger()
                                                                    ->send();

                                                                return $fail("Límite alcanzado ({$maxImagenes} máx).");
                                                            }
                                                        },
                                                    ]),
                                                TextInput::make('orden')
                                                    ->label('Orden de foto')
                                                    ->numeric()
                                                    ->default(0),
                                            ])
                                            ->grid(3) // Muestra las fotos en 3 columnas para optimizar espacio
                                            ->defaultItems(0)
                                            ->createItemButtonLabel('Añadir Foto')
                                            ->collapsible()
                                            ->deleteAction(
                                                fn(Action $action) => $action->requiresConfirmation(),
                                            ),
                                    ]),
                            ])
                            ->itemLabel(fn(array $state): ?string => $state['nombre'] ?? 'Nuevo Producto')
                            ->createItemButtonLabel('Agregar Producto')
                            ->collapsible()
                            ->cloneable()
                            ->columnSpanFull()
                            ->deleteAction(
                                fn(Action $action) => $action->requiresConfirmation(),
                            ),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                tables\Columns\TextColumn::make('nombre')
                    ->searchable()
                    ->label('Categoria'),

                tables\Columns\TextColumn::make('descripcion')
                    ->label('Descripcion'),

                tables\Columns\TextColumn::make('orden')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->label('Orden de visualización'),
            ])
            ->filters([
                //
            ])
            ->actions([
                ActionGroup::make([
                    Tables\Actions\EditAction::make()
                        ->color('primary'),

                    Tables\Actions\Action::make('crearProducto')
                        ->label('Crear Producto')
                        ->icon('heroicon-m-plus-circle')
                        ->color('success')
                        ->url(fn(Categoria $record): string => ProductosResource::getUrl('create', [
                            'categoria_id' => $record->id
                        ])),

                    Tables\Actions\Action::make('verProductos')
                        ->label('Ver Productos')
                        ->icon('heroicon-m-eye')
                        ->color('info')
                        ->url(fn(Categoria $record): string => ProductosResource::getUrl('index', [
                            'tableFilters' => [
                                'categoria_id' => [
                                    'value' => $record->id,
                                ],
                            ],
                        ])),

                    Tables\Actions\DeleteAction::make(),


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
            'index' => Pages\ListCategorias::route('/'),
            'create' => Pages\CreateCategoria::route('/create'),
            'edit' => Pages\EditCategoria::route('/{record}/edit'),
        ];
    }
}
