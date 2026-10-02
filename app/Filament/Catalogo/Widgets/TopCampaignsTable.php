<?php

namespace App\Filament\Catalogo\Widgets;

use App\Models\Categoria;
use App\Models\Spot;
use App\Models\VisitDaily;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\Auth;
use Filament\Widgets\TableWidget as BaseWidget;


class TopCampaignsTable extends BaseWidget
{
    protected static ?int $sort = 4;
    
    protected static ?string $heading = 'Rendimiento por campaña (últimos 30 días)';

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
                    ->where('date', '>=', now()->subDays(30)->toDateString())
                    ->whereNotNull('utm_campaign')
                    ->selectRaw('MIN(id) as id, utm_campaign, SUM(visits) as total_visits, SUM(unique_visitors) as total_unicos')
                    ->groupBy('utm_campaign')
                    ->orderByDesc('total_visits')
            )
            ->columns([
                TextColumn::make('utm_campaign')
                    ->label('Campaña')
                    ->searchable()
                    ->weight('bold'),
                TextColumn::make('total_visits')
                    ->label('Visitas')
                    ->numeric()
                    ->sortable()
                    ->badge()
                    ->color('warning'),
                TextColumn::make('total_unicos')
                    ->label('Únicos')
                    ->numeric()
                    ->sortable(),
            ])
            ->recordUrl(null)
            ->recordAction(null)
            ->emptyStateHeading('Sin campañas activas')
            ->emptyStateDescription('Cuando uses links con UTM aparecerán aquí.')
            ->paginated([5, 10]);
    }
}
