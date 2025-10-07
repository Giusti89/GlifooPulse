<?php

namespace App\Filament\Catalogo\Resources;

use App\Filament\Catalogo\Resources\CategoriaResource\Pages;
use App\Filament\Catalogo\Resources\CategoriaResource\RelationManagers;
use App\Models\Categoria;
use App\Models\Producto;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Actions\ActionGroup;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class CategoriaResource extends Resource
{
    protected static ?string $model = Categoria::class;

    protected static ?string $navigationIcon = 'heroicon-m-folder-open';
    protected static ?string $navigationLabel = 'Categoria de Productos';
    protected static ?string $pluralModelLabel = 'Categoria de productos';
    protected static ?string $navigationGroup = 'Configuracion Catalogo';

    protected static ?int $navigationSort = 2;

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
                Forms\Components\TextInput::make('nombre')
                    ->label('Nombre categoria')
                    ->required()
                    ->maxLength(255),

                Forms\Components\TextInput::make('descripcion')
                    ->label('Descripcion de la categoria')
                    ->maxLength(255),

                Forms\Components\TextInput::make('orden')
                    ->label('Orden de visualización')
                    ->numeric()
                    ->default(0)
                    ->helperText('Define el orden en que aparecerá la categoría en el catálogo'),

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
