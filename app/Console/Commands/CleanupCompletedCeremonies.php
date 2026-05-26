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
    protected $description = 'soft deletes ceremonies after 5 days';
    public function handle()
    {
        $cutoffDate = now()->subDays(5)->toDateString();

        $expiredCeremonies = Ceramonies::whereDate('ceramony_date', '<', $cutoffDate)->get();

        if($expiredCeremonies->isEmpty()){
            $this->info('No Ceremonies to clean up.');
            return 0;
        }

        foreach($expiredCeremonies as $ceremony){
            GuestList::where('host_id', $ceremony->host_id)->
            where('ceramony_id', $ceremony->id)->delete();
            $ceremony->delete();
        }
        $this->info('Successfully archived old ceremonies and guest lists');
        return 0;
    }
}
