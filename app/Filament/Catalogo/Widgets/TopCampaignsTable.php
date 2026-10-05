<?php

namespace App\Filament\Catalogo\Widgets;

use App\Models\Categoria;
use App\Models\Spot;
use App\Models\VisitDaily;
use Carbon\Carbon;
use Filament\Tables;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

class TopCampaignsTable extends BaseWidget
{
    protected static ?int $sort = 4;
    protected int|string|array $columnSpan = 1;
    protected static ?string $heading = 'Rendimiento por campaña';

    public static function canView(): bool
    {
        $user = Auth::user();
        if (! $user) return false;

        return Categoria::whereHas('spot.suscripcion', fn($q) => $q->where('user_id', $user->id))->exists();
    }

    public function table(Table $table): Table
    {
        $user = Auth::user();
        $spot = Spot::whereHas('suscripcion', fn($q) => $q->where('user_id', $user->id))->first();

        return $table
            ->query(
                VisitDaily::query()
                    ->where('spot_id', $spot?->id ?? 0)
                    ->whereNotNull('utm_campaign')
                    ->selectRaw('
                        MIN(id) as id,
                        utm_campaign,
                        SUM(visits) as total_visits,
                        SUM(unique_visitors) as total_unicos
                    ')
                    ->groupBy('utm_campaign')
                    ->orderByDesc('total_visits')
            )
            ->columns([
                Tables\Columns\TextColumn::make('utm_campaign')
                    ->label('Campaña')
                    ->searchable()
                    ->weight('bold')
                    ->wrap(),

                Tables\Columns\TextColumn::make('total_visits')
                    ->label('Visitas')
                    ->numeric()
                    ->sortable()
                    ->badge()
                    ->color('warning'),

                Tables\Columns\TextColumn::make('total_unicos')
                    ->label('Únicos')
                    ->numeric()
                    ->sortable(),
            ])
            ->filters([
                SelectFilter::make('periodo')
                    ->label('Período')
                    ->options([
                        '7'   => 'Últimos 7 días',
                        '30'  => 'Últimos 30 días',
                        '90'  => 'Últimos 90 días',
                        'all' => 'Todo el historial',
                    ])
                    ->default('30')
                    ->query(function (Builder $query, array $data): Builder {
                        $periodo = $data['value'] ?? '30';

                        if ($periodo === 'all') {
                            return $query;
                        }

                        return $query->where('date', '>=', now()->subDays((int) $periodo)->toDateString());
                    }),
            ])
            ->recordUrl(null)
            ->recordAction(null)
            ->emptyStateHeading('Sin campañas en este período')
            ->emptyStateDescription('Prueba cambiando el filtro de período o espera a que llegue más tráfico.')
            ->paginated([5, 10]);
    }
}