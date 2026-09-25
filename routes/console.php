<?php

use App\Models\Pond;
use App\Services\FeedReminderService;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Artisan::command('ponds:tokens', function () {
    Pond::query()->get()->each(function (Pond $pond) {
        $pond->ensurePublicToken();
    });
    $this->info('Pond tokens ready: '.Pond::whereNotNull('public_token')->count());
})->purpose('Generate public QR tokens for ponds');

Artisan::command('feed:remind', function (FeedReminderService $service) {
    $sent = $service->dispatchDueReminders();
    $this->info("Feed reminders sent: {$sent}");
})->purpose('Send due feeding schedule reminders');

Schedule::command('feed:remind')->everyMinute();
