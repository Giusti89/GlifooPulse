<?php

namespace App\Filament\Catalogo\Resources;

use App\Filament\Catalogo\Resources\ImagenProductosResource\Pages;
use App\Filament\Catalogo\Resources\ImagenProductosResource\RelationManagers;
use App\Models\ImagenProducto;
use App\Models\ImagenProductos;
use App\Models\Producto;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Actions\ActionGroup;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Str;

class ImagenProductosResource extends Resource
{
    protected static ?string $model = ImagenProducto::class;

    protected static ?string $navigationIcon = 'heroicon-s-camera';
    protected static ?string $navigationLabel = 'Imagenes de productos';
    protected static ?string $navigationGroup = 'Configuracion Catalogo';
    protected static ?string $pluralModelLabel = 'Galeria de imagenes';

    protected static ?int $navigationSort = 4;

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
                Forms\Components\Select::make('producto_id')
                    ->label('Seleccione el producto')
                    ->relationship(
                        name: 'producto',
                        titleAttribute: 'nombre',
                        modifyQueryUsing: function ($query) {
                            $userId = auth()->id();

                            $query->whereHas('categoria.spot.suscripcion', function ($sq) use ($userId) {
                                $sq->where('user_id', $userId);
                            });
                        }
                    )
                    ->default(request()->get('producto_id')) // ← aquí se inyecta el valor desde la URL
                    ->required(),


                Forms\Components\FileUpload::make('url')
                    ->label('Imagen')
                    ->image()
                    ->directory(fn($record) => 'imagenes-productos/' . Str::slug(auth()->user()->name . '-' . auth()->user()->lastname))
                    ->required()
                    ->maxSize(5120)
                    ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/webp'])
                    ->helperText('Formatos permitidos: JPG, PNG, WEBP. Máximo 5MB. Proporción recomendada: 1:1 (cuadrada)')
                    ->rules([
                        'dimensions:min_width=400,min_height=400,max_width=2000,max_height=2000,ratio=1/1',
                    ])
                    ->validationMessages([
                        'dimensions' => 'La imagen debe ser cuadrada y tener entre 400x400 y 2000x2000 píxeles.',
                    ])
                    ->imageEditor()
                    ->imageResizeTargetWidth(800)
                    ->imageResizeTargetHeight(800)
                    ->imageResizeMode('cover'),

                Forms\Components\TextInput::make('orden')
                    ->label('Orden')
                    ->numeric()
                    ->default(0)
                    ->required()
                    ->minValue(0)
                    ->maxValue(100)
                    ->helperText('Define el orden de visualización (0 = primero)'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('url')
                    ->label('Imagen')
                    ->size(50)
                    ->circular(),

                Tables\Columns\TextColumn::make('producto.nombre')
                    ->label('Producto')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('orden')
                    ->label('Orden')
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->sortable()
                    ->alignCenter(),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Creado')
                    ->dateTime('d/m/Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
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
                ActionGroup::make([
                    Tables\Actions\EditAction::make()
                        ->color('primary'),
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
            'index' => Pages\ListImagenProductos::route('/'),
            'create' => Pages\CreateImagenProductos::route('/create'),
            'edit' => Pages\EditImagenProductos::route('/{record}/edit'),
        ];
    }
}
