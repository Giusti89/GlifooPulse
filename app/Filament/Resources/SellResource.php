<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SellResource\Pages;
use App\Filament\Resources\SellResource\RelationManagers;
use App\Models\Sell;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Tables\Actions\Action;
use Filament\Notifications\Notification;

class SellResource extends Resource
{
    protected static ?string $model = Sell::class;

    protected static ?string $navigationIcon = 'heroicon-m-currency-dollar';
    protected static ?string $navigationGroup = 'Datos de Usuarios';
    protected static ?string $navigationLabel = 'Ventas y pagos';
    protected static ?string $pluralModelLabel = 'Ventas y pagos';


    protected static ?int $navigationSort = 3;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                //
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                tables\Columns\TextColumn::make('suscripcion.user.name')
                    ->numeric()
                    ->searchable(),

                Tables\Columns\TextColumn::make('total')
                    ->label('Pago total')
                    ->numeric(),

                Tables\Columns\TextColumn::make('pago')
                    ->label('Pago')
                    ->numeric(),

                Tables\Columns\TextColumn::make('fecha')
                    ->label('Fecha')
                    ->sortable()
                    ->searchable(),

                Tables\Columns\TextColumn::make('estadov.nombre')
                    ->label('estado')
                    ->sortable()
                    ->searchable(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Action::make('realizarPago')
                    ->label('Realizar Pago')
                    ->icon('heroicon-m-currency-dollar')
                    ->color('success')
                    ->visible(fn($record) => $record->estadov->nombre === 'por pagar')
                    ->requiresConfirmation()
                    ->action(function ($record) {
                        // Actualiza la venta
                        $record->update([
                            'pago' => $record->total,
                            'estadov_id' => 2, // ID del estado "pagado"
                        ]);

                        // Activa la suscripción
                        $suscripcion = $record->suscripcion;
                        $suscripcion->update([
                            'estado' => '1',
                        ]);

                        // Buscar renovación pendiente asociada a esta suscripción
                        $renewal = $suscripcion->renewals()
                            ->where('estado', 'pendiente') // o el estado que uses
                            ->latest()
                            ->first();

                        if ($renewal) {
                            $suscripcion->renovar($renewal->meses); // 👈 usa tu método ya creado
                            $renewal->update(['estado' => 'verificada']); // Marcar como aprobada
                        }

                        Notification::make()
                            ->title('Pago registrado y suscripción renovada')
                            ->success()
                            ->send();
                    })
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
            'index' => Pages\ListSells::route('/'),


        ];
    }
}
