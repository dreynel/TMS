<?php

use App\Services\BorrowingService;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Artisan::command('borrowings:check-overdue', function (BorrowingService $service) {
    $this->info('Scanning active borrowings for overdue return dates...');
    $count = $service->checkAndUpdateOverdue();
    $this->info("Scan completed. Updated {$count} overdue borrowing(s).");
})->purpose('Scan and update status of overdue released borrowings');

Schedule::command('borrowings:check-overdue')->hourly();
