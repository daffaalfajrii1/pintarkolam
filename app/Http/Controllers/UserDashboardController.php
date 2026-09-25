<?php

namespace App\Http\Controllers;

use App\Models\CultivationCycle;
use App\Models\Pond;
use App\Models\Product;
use App\Models\Recommendation;
use App\Models\WaterQualityLog;
use App\Services\FeedReminderService;
use Illuminate\Http\Request;

class UserDashboardController extends Controller
{
    public function index(Request $request, FeedReminderService $feedReminder)
    {
        $user = $request->user();

        if ($user->hasRole('admin')) {
            return redirect()->route('admin.dashboard');
        }

        $feedReminder->dispatchDueReminders($user);

        $cycleIds = CultivationCycle::where('user_id', $user->id)->pluck('id');

        $waterLogs = WaterQualityLog::whereIn('cultivation_cycle_id', $cycleIds)
            ->latest('measured_at')
            ->limit(20)
            ->get()
            ->sortBy('measured_at')
            ->values();

        $recommendations = Recommendation::whereIn('cultivation_cycle_id', $cycleIds)
            ->with('cycle')
            ->latest()
            ->limit(8)
            ->get();

        $ponds = Pond::where('user_id', $user->id)
            ->with('latestHealthScore')
            ->latest()
            ->limit(6)
            ->get();

        $alertCounts = [
            'baik' => $ponds->filter(fn ($p) => in_array($p->latestHealthScore?->category, ['baik', 'normal'], true))->count(),
            'waspada' => $ponds->filter(fn ($p) => ($p->latestHealthScore?->category ?? '') === 'waspada')->count(),
            'kritis' => $ponds->filter(fn ($p) => ($p->latestHealthScore?->category ?? '') === 'kritis')->count(),
        ];

        return view('user.dashboard', [
            'pondsCount' => Pond::where('user_id', $user->id)->count(),
            'cyclesCount' => CultivationCycle::where('user_id', $user->id)->whereIn('status', ['active', 'near_harvest'])->count(),
            'productsCount' => Product::where('user_id', $user->id)->count(),
            'ponds' => $ponds,
            'cycles' => CultivationCycle::where('user_id', $user->id)->with(['fishSpecies', 'pond'])->latest()->limit(5)->get(),
            'products' => Product::where('user_id', $user->id)->latest()->limit(5)->get(),
            'recommendations' => $recommendations,
            'alertCounts' => $alertCounts,
            'chart' => [
                'labels' => $waterLogs->map(fn ($l) => optional($l->measured_at)->format('d/m H:i'))->values(),
                'ph' => $waterLogs->pluck('ph')->values(),
                'temp' => $waterLogs->pluck('temperature_c')->values(),
                'do' => $waterLogs->pluck('dissolved_oxygen')->values(),
            ],
        ]);
    }
}
