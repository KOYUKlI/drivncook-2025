<?php

namespace App\Console\Commands;

use App\Models\ReportPdf;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

class CleanOrphanReports extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'reports:clean-orphans {--dry-run : Show what would be deleted without actually deleting}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Clean up ReportPdf records that point to non-existent files';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $dryRun = $this->option('dry-run');
        
        $reports = ReportPdf::all();
        $orphans = [];
        
        foreach ($reports as $report) {
            if (!Storage::disk('public')->exists($report->storage_path)) {
                $orphans[] = $report;
            }
        }
        
        if (empty($orphans)) {
            $this->info('No orphan reports found.');
            return 0;
        }
        
        $this->info(sprintf('Found %d orphan report(s):', count($orphans)));
        
        foreach ($orphans as $report) {
            $this->line(sprintf(
                '- ID: %s, Path: %s, Period: %d/%d',
                $report->id,
                $report->storage_path,
                $report->month,
                $report->year
            ));
        }
        
        if ($dryRun) {
            $this->warn('This was a dry run. Use without --dry-run to actually delete these records.');
            return 0;
        }
        
        if (!$this->confirm('Do you want to delete these orphan records?')) {
            $this->info('Cancelled.');
            return 0;
        }
        
        foreach ($orphans as $report) {
            $report->delete();
        }
        
        $this->info(sprintf('Deleted %d orphan report records.', count($orphans)));
        return 0;
    }
}
