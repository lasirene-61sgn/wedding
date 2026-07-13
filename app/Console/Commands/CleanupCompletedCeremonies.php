<?php

namespace App\Console\Commands;

use App\Models\Ceramonies;
use App\Models\GuestList;
use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;

#[Signature('app:cleanup-completed-ceremonies')]
#[Description('Command description')]
class CleanupCompletedCeremonies extends Command
{
    /**
     * Execute the console command.
     */
    protected $signature = 'cleanup:completed-ceremonies';
    protected $description = 'soft deletes hosts and their resources 7 days after all their ceremonies are completed';

    public function handle()
    {
        $cutoffDate = now()->subDays(1)->toDateString();

        // Find hosts that have at least one ceremony, but NO ceremonies on or after the cutoff date
        $hosts = \App\Models\Host::whereHas('ceramonies')
            ->whereDoesntHave('ceramonies', function($query) use ($cutoffDate) {
                $query->whereDate('ceramony_date', '>=', $cutoffDate);
            })->get();

        if ($hosts->isEmpty()) {
            $this->info('No completed hosts to clean up.');
            return 0;
        }

        foreach ($hosts as $host) {
            // Deleting the host will trigger the `deleting` model event 
            // to delete related resources, but will NOT delete guest lists.
            $host->delete();
        }

        $this->info('Successfully deleted completed hosts and their related resources (preserving guest lists).');
        return 0;
    }
}
