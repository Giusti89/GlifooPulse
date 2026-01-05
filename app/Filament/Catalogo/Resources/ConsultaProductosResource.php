<?php

namespace App\Filament\Catalogo\Resources;

use App\Filament\Catalogo\Resources\ConsultaProductosResource\Pages;
use App\Filament\Catalogo\Resources\ConsultaProductosResource\RelationManagers;
use App\Models\ConsultaProducto;
use App\Models\Producto;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ConsultaProductosResource extends Resource
{
    protected static ?string $model = ConsultaProducto::class;

    protected static ?string $navigationIcon = 'heroicon-m-chat-bubble-left';
    protected static ?string $navigationLabel = 'Productos consultados';
    protected static ?string $navigationGroup = 'Productos consultados';
    protected static ?string $pluralModelLabel = 'Productos consultados';

    protected static ?int $navigationSort = 6;

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
                Tables\Columns\TextColumn::make('producto.nombre')
                    ->label('Producto')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('nombre')
                    ->label('Nombre Cliente')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('telefono')
                    ->label('Telefono')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('mensaje')
                    ->label('Mensaje')
                    ->sortable(),
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
