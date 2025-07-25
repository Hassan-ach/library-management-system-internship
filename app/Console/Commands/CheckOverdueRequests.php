<?php

namespace App\Console\Commands;

use App\Enums\RequestStatus;
use App\Models\BookRequest;
use Illuminate\Console\Command;

class CheckOverdueRequests extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:check-overdue-requests';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check for borrowed requests that are overdue and update their status.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('🔍 Starting check for overdue book requests...');

        $borrowedRequests = BookRequest::with('latestRequestInfo')->get();
        $totalChecked = 0;
        $overdueMarked = 0;

        foreach ($borrowedRequests as $request) {
            $totalChecked++;
            $latest = $request->latestRequestInfo;
            $due = $request->return_date();

            if ($latest && $latest->status === RequestStatus::BORROWED && $due && $due->isPast()) {
                $request->requestInfo()->create([
                    'status' => RequestStatus::OVERDUE,
                    'user_id' => 1,
                ]);
                $overdueMarked++;
                $this->line("✅ Marked request ID {$request->id} as OVERDUE (Due date: {$due->toDateString()})");
            } else {
                $this->line("➖ Request ID {$request->id} is not overdue.");
            }
        }

        $this->info('📋 Summary:');
        $this->info("🔎 Total checked: {$totalChecked}");
        $this->info("⚠️ Overdue marked: {$overdueMarked}");
        $this->info('✅ Done.');
    }
}
