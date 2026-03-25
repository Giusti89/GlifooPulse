<?php

namespace App\Filament\Widgets;

use Filament\Widgets\TableWidget;
use App\Models\Plataforma_visit;
use Filament\Widgets\Widget;
use Illuminate\Database\Eloquent\Builder;
use Filament\Tables;
use Illuminate\Support\Facades\DB;

class TopPagesTable extends TableWidget
{
    protected static ?int $sort = 6;

    protected function getTableQuery(): Builder
    {
        return Plataforma_visit::query()
            ->select(
                DB::raw('MIN(id) as id'),
                'path',
                DB::raw('count(*) as total')
            )
            ->groupBy('path')
            ->orderByDesc('total')
            ->limit(10);
    }

    protected function getTableColumns(): array
    {
        return [
            Tables\Columns\TextColumn::make('path')
                ->label('Página')
                ->formatStateUsing(fn($state) => $this->formatPath($state)),
            Tables\Columns\TextColumn::make('total')->label('Visitas'),
        ];
    }
    private function formatPath($path)
    {
        if (!$path) return '/';

        // 👉 caso especial: enlaces encriptados
        if (str_starts_with($path, 'enlace/')) {
            return 'Enlaces (tracking)';
        }

        // 👉 portfolio
        if (str_starts_with($path, 'portfolio/vista')) {
            return 'Portfolio';
        }

        // 👉 socios dinámicos
        if (str_starts_with($path, 'socios/')) {
            return 'Socios';
        }

        // 👉 home
        if ($path === '/') {
            return 'Inicio';
        }

        return $path;
    }
}
