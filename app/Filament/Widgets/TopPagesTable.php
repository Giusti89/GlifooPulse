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
    protected static ?int $sort = 7;

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
            Tables\Columns\TextColumn::make('path')->label('Página'),
            Tables\Columns\TextColumn::make('total')->label('Visitas'),
        ];
    }
}
