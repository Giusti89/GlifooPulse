<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class AggregateSocialClicks extends Command
{
    protected $signature = 'analytics:aggregate-social-clicks {--days=1}';
    protected $description = 'Agrupa los clicks en redes en social_click_dailies';

    public function handle(): int
    {
        $days = (int) $this->option('days');
        $from = now()->subDays($days)->startOfDay();
        $to   = now()->endOfDay();

        $this->info("Agregando social clicks desde {$from} hasta {$to}...");

        $rows = DB::table('social_clicks')
            ->select([
                'social_id',
                DB::raw('DATE(clicked_at) as date'),
                'utm_source',
                'utm_campaign',
                'device_type',
                DB::raw('COUNT(*) as clicks'),
            ])
            ->whereBetween('clicked_at', [$from, $to])
            ->groupBy(['social_id', 'date', 'utm_source', 'utm_campaign', 'device_type'])
            ->get();

        foreach ($rows as $row) {
            DB::table('social_click_dailies')->updateOrInsert(
                [
                    'social_id'    => $row->social_id,
                    'date'         => $row->date,
                    'utm_source'   => $row->utm_source,
                    'utm_campaign' => $row->utm_campaign,
                    'device_type'  => $row->device_type,
                ],
                [
                    'clicks'     => $row->clicks,
                    'updated_at' => now(),
                    'created_at' => now(),
                ]
            );
        }

        $this->info("Listo. {$rows->count()} combinaciones agregadas.");
        return self::SUCCESS;
    }
}
