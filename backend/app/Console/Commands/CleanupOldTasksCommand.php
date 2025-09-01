<?php

namespace App\Console\Commands;

use App\Jobs\CleanupOldTasks;
use Illuminate\Console\Command;

class CleanupOldTasksCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'tasks:cleanup {--dry-run : Show what would be deleted without actually deleting}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Delete tasks older than 30 days';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $isDryRun = $this->option('dry-run');
        
        if ($isDryRun) {
            $this->info('🔍 DRY RUN MODE - No tasks will be deleted');
        }
        
        $this->info('Starting task cleanup process...');
        
        if ($isDryRun) {
            // For dry run, we'll just dispatch the job but it will run in dry-run mode
            CleanupOldTasks::dispatch(true);
            $this->info('✅ Dry run completed. Check logs for details.');
        } else {
            // Dispatch the job to the queue
            CleanupOldTasks::dispatch(false);
            $this->info('✅ Task cleanup job dispatched to queue.');
            $this->info('💡 Run "php artisan queue:work" to process the job.');
        }
        
        return Command::SUCCESS;
    }
}
