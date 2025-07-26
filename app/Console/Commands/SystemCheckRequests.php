<?php

namespace App\Console\Commands;

use App\Enums\RequestStatus;
use App\Models\BookRequest;
use App\Models\Setting;
use Illuminate\Console\Command;

class SystemCheckRequests extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:system-check-requests';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check requests and update their status.';

    /**
     * Execute the console command.
     */
    public function handleOverdueRequests()
    {
        //
        $this->info('🔍 Starting check for overdue book requests...');

        $oneWeekAgo = now()->subWeek();
        $borrowedRequests = BookRequest::where('created_at', '>=', $oneWeekAgo)
            ->with('latestRequestInfo')
            ->get();
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

        $this->logSummary('⚠️ Overdue marked:', $totalChecked, $overdueMarked);

    }

    public function handleExpiredApprovedRequests()
    {
        //
        $this->info('🔍 Starting check for expired approved book requests...');
        $setting = Setting::find(1);
        $expireDays = $setting?->DUREE_RESERVATION ?? 3;

        $expireDate = now()->subDays($expireDays);
        $oneWeekAgo = now()->subWeek();
        $expiredApprovedRequests = BookRequest::where('created_at', '<=', $expireDate)
            ->where('created_at', '>=', $oneWeekAgo)
            ->with('latestRequestInfo')
            ->get();
        $totalChecked = 0;
        $autoRejected = 0;

        foreach ($expiredApprovedRequests as $request) {
            $totalChecked++;
            $latest = $request->latestRequestInfo;

            if ($latest && $latest->status === RequestStatus::APPROVED) {
                $request->requestInfo()->create([
                    'status' => RequestStatus::REJECTED,
                    'user_id' => 1,
                ]);
                $autoRejected++;
                $this->line("✅ Auto-rejected request ID {$request->id} (Approved on: {$latest->created_at->toDateString()})");
            } else {
                $this->line("➖ Request ID {$request->id} is not eligible for auto-rejection.");
            }
        }
        $this->logSummary('❌ Auto-rejected:', $totalChecked, $autoRejected, "⏰ Expire threshold: {$expireDays} days");

    }

    public function handle()
    {
        $this->handleOverdueRequests();

        $this->handleExpiredApprovedRequests();
    }

    protected function logSummary(string $type, int $checked, int $affected, string $extra = '')
    {
        $this->info('📋 Summary:');
        $this->info("🔎 Total checked: {$checked}");
        $this->info("{$type} {$affected}");
        if ($extra) {
            $this->info($extra);
        }
        $this->info('✅ Done.');
    }
}
