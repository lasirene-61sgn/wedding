<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Host;
use App\Models\Ceramonies;
use App\Models\Pictures;
use App\Models\Videos;
use App\Models\Albums;
use App\Models\Invitation;
use App\Models\SaveDate;
use Carbon\Carbon;

class CleanupDeletedHostData extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cleanup:deleted-hosts-data';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Delete related gallery, ceremonies, and invitations for hosts soft-deleted 5 days ago';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $date = Carbon::now()->subDays(5);
        $hosts = Host::onlyTrashed()->where('deleted_at', '<=', $date)->get();

        foreach ($hosts as $host) {
            Ceramonies::where('host_id', $host->id)->delete();
            Pictures::where('host_id', $host->id)->delete();
            Videos::where('host_id', $host->id)->delete();
            Albums::where('host_id', $host->id)->delete();
            Invitation::where('host_id', $host->id)->delete();
            SaveDate::where('host_id', $host->id)->delete();
            
            $this->info("Cleaned up data for host ID: {$host->id}");
        }

        $this->info('Cleanup completed.');
    }
}
