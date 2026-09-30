<?php

namespace App\Console\Commands;

use App\Models\SocialClicks;
use App\Models\Visit;
use Illuminate\Console\Command;

class PurgeOldVisits extends Command
{
    protected $signature = 'analytics:purge {--months=12}';
    protected $description = 'Borra eventos crudos antiguos (los agregados se conservan)';

    public function handle(): int
    {
        $months = (int) $this->option('months');
        $limit = now()->subMonths($months);

        $visitsDeleted = Visit::where('visited_at', '<', $limit)->delete();
        $clicksDeleted = SocialClicks::where('clicked_at', '<', $limit)->delete();

        $this->info("Borradas {$visitsDeleted} visitas y {$clicksDeleted} clicks anteriores a {$limit}.");
        return self::SUCCESS;
    }
}
