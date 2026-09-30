<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class AggregateVisits extends Command
{
    protected $signature = 'analytics:aggregate-visits {--days=1}';
    protected $description = 'Agrupa las visitas crudas en visit_dailies';

    public function handle(): int
    {
        $days = (int) $this->option('days');
        $from = now()->subDays($days)->startOfDay();
        $to   = now()->endOfDay();

        $this->info("Agregando visitas desde {$from} hasta {$to}...");

        $rows = DB::table('visits')
            ->select([
                'spot_id',
                DB::raw('DATE(visited_at) as date'),
                'utm_source',
                'utm_campaign',
                'referrer_domain',
                'device_type',
                DB::raw('COUNT(*) as visits'),
                DB::raw('COUNT(DISTINCT ip) as unique_visitors'),
            ])
            ->whereBetween('visited_at', [$from, $to])
            ->groupBy([
                'spot_id',
                'date',
                'utm_source',
                'utm_campaign',
                'referrer_domain',
                'device_type',
            ])
            ->get();

        foreach ($rows as $row) {
            DB::table('visit_dailies')->updateOrInsert(
                [
                    'spot_id'         => $row->spot_id,
                    'date'            => $row->date,
                    'utm_source'      => $row->utm_source,
                    'utm_campaign'    => $row->utm_campaign,
                    'referrer_domain' => $row->referrer_domain,
                    'device_type'     => $row->device_type,
                ],
                [
                    'visits'          => $row->visits,
                    'unique_visitors' => $row->unique_visitors,
                    'updated_at'      => now(),
                    'created_at'      => now(),
                ]
            );
        }

        $this->info("Listo. {$rows->count()} combinaciones agregadas.");
        return self::SUCCESS;
    }
}
