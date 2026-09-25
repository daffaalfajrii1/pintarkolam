<?php

namespace App\Services;

use App\Models\FeedingSchedule;
use App\Models\User;
use App\Notifications\UserAlertNotification;
use Illuminate\Support\Facades\Cache;

class FeedReminderService
{
    public function dispatchDueReminders(?User $user = null): int
    {
        $now = now();
        $windowStart = $now->copy()->subMinutes(5)->format('H:i');
        $windowEnd = $now->copy()->addMinute()->format('H:i');
        $today = $now->toDateString();
        $sent = 0;

        $query = FeedingSchedule::query()
            ->with(['cycle.user'])
            ->where('is_active', true)
            ->whereHas('cycle', function ($q) use ($user) {
                $q->whereIn('status', ['active', 'near_harvest']);
                if ($user) {
                    $q->where('user_id', $user->id);
                }
            });

        foreach ($query->get() as $schedule) {
            $time = substr((string) $schedule->feed_time, 0, 5);
            if ($time < $windowStart || $time > $windowEnd) {
                continue;
            }

            $owner = $schedule->cycle?->user;
            if (! $owner) {
                continue;
            }

            $cacheKey = "feed_reminded:{$owner->id}:{$schedule->id}:{$today}";
            if (Cache::has($cacheKey)) {
                continue;
            }

            $owner->notify(new UserAlertNotification(
                'Pengingat pakan',
                "Saatnya memberi pakan ({$time}) untuk siklus {$schedule->cycle->name}: {$schedule->feed_type} {$schedule->amount_kg} kg.",
                'feeding',
                route('user.cycles.feeding', $schedule->cycle),
                ['schedule_id' => $schedule->id, 'feed_time' => $time],
            ));

            Cache::put($cacheKey, true, now()->endOfDay());
            $sent++;
        }

        return $sent;
    }
}
