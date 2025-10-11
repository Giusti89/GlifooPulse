<?php

namespace App\Filament\Catalogo\Resources;

use App\Filament\Catalogo\Resources\ImagenProductosResource\Pages;
use App\Filament\Catalogo\Resources\ImagenProductosResource\RelationManagers;
use App\Models\ImagenProducto;
use App\Models\ImagenProductos;
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
    protected static ?string $navigationLabel = 'Imagenes';
    protected static ?string $pluralModelLabel = 'Galeria de imagenes';
    protected static ?string $navigationGroup = 'Galeria de imagenes';

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
                    ->required(),


                Forms\Components\FileUpload::make('url')
                    ->label('Imagen')
                    ->image()
                    ->directory(fn($record) => 'imagenes-productos/' . Str::slug(auth()->user()->name . '-' . auth()->user()->lastname))
                    ->required()
                    ->maxSize(2048)
                    ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/webp'])
                    ->helperText('Formatos permitidos: JPG, PNG, WEBP. Máximo 2MB. Proporción recomendada: 1:1 (cuadrada)')
                    ->rules([
                        'dimensions:min_width=600,min_height=600,max_width=1200,max_height=1200,ratio=1/1', // Fuerza relación cuadrada
                    ])
                    ->validationMessages([
                        'dimensions' => 'La imagen debe ser cuadrada (mismo ancho y alto) y tener entre 600x600 y 1200x1200 píxeles.',
                    ])
                    ->imageEditor()
                    ->imageResizeTargetWidth(800) // Tamaño consistente
                    ->imageResizeTargetHeight(800) // Tamaño consistente
                    ->imageResizeMode('cover'), // Recorta para mantener proporción

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
                    ->sortable()
                    ->alignCenter(),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Creado')
                    ->dateTime('d/m/Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('producto')
                    ->relationship('producto', 'nombre')
                    ->label('Filtrar por Producto'),
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
