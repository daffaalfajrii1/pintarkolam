<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\CycleCostEntry;
use App\Models\CultivationCycle;
use App\Models\FeedingLog;
use App\Models\FeedingSchedule;
use App\Models\FishSpecies;
use App\Models\GrowthRecord;
use App\Models\MortalityLog;
use App\Models\Pond;
use App\Models\WaterQualityLog;
use App\Services\HarvestEstimateService;
use App\Services\WaterQualityService;
use Illuminate\Http\Request;

class CycleController extends Controller
{
    private function authorizeCycle(Request $request, CultivationCycle $cycle): void
    {
        abort_unless($cycle->user_id === $request->user()->id || $request->user()->hasRole('admin'), 403);
    }

    public function index(Request $request)
    {
        return redirect()->route('user.ponds.index');
    }

    public function create(Request $request)
    {
        $selectedPondId = $request->integer('pond_id') ?: null;
        if (! $selectedPondId) {
            return redirect()
                ->route('user.ponds.index')
                ->with('status', 'Pilih kolam dulu, lalu klik + Siklus pada kolam tersebut.');
        }

        $pond = Pond::where('user_id', $request->user()->id)->findOrFail($selectedPondId);

        return view('user.cycles.create', [
            'pond' => $pond,
            'species' => FishSpecies::where('is_active', true)->orderBy('name')->get(),
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'pond_id' => ['required', 'exists:ponds,id'],
            'fish_species_id' => ['required', 'exists:fish_species,id'],
            'name' => ['required', 'string', 'max:255'],
            'stocking_date' => ['required', 'date'],
            'seed_count' => ['required', 'integer', 'min:1'],
            'initial_size_gram' => ['nullable', 'numeric', 'min:0'],
            'seed_source' => ['nullable', 'string', 'max:255'],
            'target_size_gram' => ['nullable', 'numeric', 'min:0'],
            'target_harvest_date' => ['nullable', 'date'],
            'notes' => ['nullable', 'string'],
        ]);

        $pond = Pond::findOrFail($data['pond_id']);
        abort_unless($pond->user_id === $request->user()->id, 403);

        $cycle = CultivationCycle::create([
            ...$data,
            'user_id' => $request->user()->id,
            'status' => 'active',
        ]);

        foreach (['07:00', '17:00'] as $time) {
            FeedingSchedule::create([
                'cultivation_cycle_id' => $cycle->id,
                'feed_time' => $time,
                'feed_type' => 'Pelet',
                'amount_kg' => max(0.1, round($cycle->seed_count * 0.02 / 1000, 3)),
                'is_active' => true,
            ]);
        }

        return redirect()->route('user.cycles.show', $cycle)->with('status', 'Siklus dibuat.');
    }

    public function show(Request $request, CultivationCycle $cycle, HarvestEstimateService $estimateService)
    {
        $this->authorizeCycle($request, $cycle);

        $cycle->load(['pond.latestHealthScore', 'fishSpecies']);
        $estimate = $cycle->harvestEstimate ?: $estimateService->calculate($cycle);

        $waterLogsAsc = $cycle->waterQualityLogs()->latest('measured_at')->limit(30)->get()->sortBy('measured_at')->values();
        $waterLogs = $waterLogsAsc->sortByDesc(fn ($l) => $l->measured_at)->values();
        $feedingLogs = $cycle->feedingLogs()->latest('fed_at')->limit(15)->get();
        $mortalityLogs = $cycle->mortalityLogs()->latest('recorded_at')->limit(15)->get();
        $growthRecords = $cycle->growthRecords()->latest('sampled_at')->limit(15)->get();

        [$recommendations, $latestWaterAt] = $this->latestRecommendations($cycle);

        return view('user.cycles.show', [
            'cycle' => $cycle,
            'estimate' => $estimate,
            'waterLogs' => $waterLogs,
            'recommendations' => $recommendations,
            'latestWaterAt' => $latestWaterAt,
            'alive' => $cycle->aliveCount(),
            'deaths' => $cycle->totalDeaths(),
            'totalFeed' => $cycle->totalFeedKg(),
            'fcr' => $cycle->fcr(),
            'totalCost' => $cycle->totalCost(),
            'netProfit' => $cycle->netProfit(),
            'chart' => [
                'labels' => $waterLogsAsc->map(fn ($l) => optional($l->measured_at)->format('d/m H:i'))->values(),
                'ph' => $waterLogsAsc->pluck('ph')->values(),
                'temp' => $waterLogsAsc->pluck('temperature_c')->values(),
                'do' => $waterLogsAsc->pluck('dissolved_oxygen')->values(),
                'mortality_labels' => $mortalityLogs->sortBy('recorded_at')->values()->map(fn ($m) => optional($m->recorded_at)->format('d/m'))->values(),
                'mortality' => $mortalityLogs->sortBy('recorded_at')->values()->pluck('death_count')->values(),
                'weight_labels' => $growthRecords->sortBy('sampled_at')->values()->map(fn ($g) => optional($g->sampled_at)->format('d/m'))->values(),
                'weight' => $growthRecords->sortBy('sampled_at')->values()->pluck('avg_weight_gram')->values(),
                'feed_labels' => $feedingLogs->sortBy('fed_at')->values()->map(fn ($f) => optional($f->fed_at)->format('d/m'))->values(),
                'feed' => $feedingLogs->sortBy('fed_at')->values()->pluck('amount_kg')->values(),
            ],
        ]);
    }

    public function print(Request $request, CultivationCycle $cycle, HarvestEstimateService $estimateService)
    {
        $this->authorizeCycle($request, $cycle);
        $cycle->load(['pond.latestHealthScore', 'fishSpecies', 'user']);
        $estimate = $cycle->harvestEstimate ?: $estimateService->calculate($cycle);
        $waterLogs = $cycle->waterQualityLogs()->latest('measured_at')->limit(20)->get();

        return view('user.cycles.print', [
            'cycle' => $cycle,
            'estimate' => $estimate,
            'waterLogs' => $waterLogs,
            'alive' => $cycle->aliveCount(),
            'deaths' => $cycle->totalDeaths(),
            'totalFeed' => $cycle->totalFeedKg(),
            'fcr' => $cycle->fcr(),
            'totalCost' => $cycle->totalCost(),
            'netProfit' => $cycle->netProfit(),
        ]);
    }

    public function water(Request $request, CultivationCycle $cycle)
    {
        $this->authorizeCycle($request, $cycle);
        $cycle->load(['pond', 'fishSpecies']);
        $logs = $cycle->waterQualityLogs()->latest('measured_at')->paginate(20);
        [$recommendations, $latestWaterAt] = $this->latestRecommendations($cycle);

        return view('user.cycles.water', compact('cycle', 'logs', 'recommendations', 'latestWaterAt'));
    }

    /**
     * @return array{0: \Illuminate\Support\Collection, 1: \Carbon\Carbon|null}
     */
    private function latestRecommendations(CultivationCycle $cycle): array
    {
        $latestLog = $cycle->waterQualityLogs()->latest('measured_at')->first();
        if (! $latestLog) {
            return [collect(), null];
        }

        $severityOrder = "CASE severity WHEN 'critical' THEN 1 WHEN 'warning' THEN 2 ELSE 3 END";

        $recommendations = $cycle->recommendations()
            ->where('water_quality_log_id', $latestLog->id)
            ->orderByRaw($severityOrder)
            ->latest('id')
            ->get();

        if ($recommendations->isEmpty()) {
            $recommendations = $cycle->recommendations()
                ->orderByRaw($severityOrder)
                ->latest()
                ->limit(5)
                ->get();
        }

        return [$recommendations, $latestLog->measured_at];
    }

    public function feeding(Request $request, CultivationCycle $cycle)
    {
        $this->authorizeCycle($request, $cycle);
        $cycle->load(['pond', 'fishSpecies', 'feedingSchedules']);
        $logs = $cycle->feedingLogs()->latest('fed_at')->paginate(20);

        return view('user.cycles.feeding', compact('cycle', 'logs'));
    }

    public function mortality(Request $request, CultivationCycle $cycle)
    {
        $this->authorizeCycle($request, $cycle);
        $cycle->load(['pond', 'fishSpecies']);
        $logs = $cycle->mortalityLogs()->latest('recorded_at')->paginate(20);

        return view('user.cycles.mortality', [
            'cycle' => $cycle,
            'logs' => $logs,
            'alive' => $cycle->aliveCount(),
            'deaths' => $cycle->totalDeaths(),
        ]);
    }

    public function growth(Request $request, CultivationCycle $cycle)
    {
        $this->authorizeCycle($request, $cycle);
        $cycle->load(['pond', 'fishSpecies']);
        $logs = $cycle->growthRecords()->latest('sampled_at')->paginate(20);

        return view('user.cycles.growth', compact('cycle', 'logs'));
    }

    public function costs(Request $request, CultivationCycle $cycle)
    {
        $this->authorizeCycle($request, $cycle);
        $cycle->load(['pond', 'fishSpecies']);

        $entries = $cycle->costEntries()->latest('recorded_at')->latest('id')->paginate(20);
        $byCategory = $cycle->costEntries()
            ->selectRaw('category, SUM(amount) as total')
            ->groupBy('category')
            ->pluck('total', 'category');

        return view('user.cycles.costs', [
            'cycle' => $cycle,
            'entries' => $entries,
            'byCategory' => $byCategory,
            'categories' => CycleCostEntry::CATEGORIES,
            'totalCost' => $cycle->totalCost(),
            'totalRevenue' => $cycle->totalRevenue(),
            'netProfit' => $cycle->netProfit(),
        ]);
    }

    public function storeCostEntry(Request $request, CultivationCycle $cycle)
    {
        $this->authorizeCycle($request, $cycle);

        $data = $request->validate([
            'category' => ['required', 'in:'.implode(',', array_keys(CycleCostEntry::CATEGORIES))],
            'amount' => ['required', 'numeric', 'min:1'],
            'recorded_at' => ['nullable', 'date'],
            'notes' => ['nullable', 'string'],
            'label' => ['nullable', 'string', 'max:150'],
        ]);

        CycleCostEntry::create([
            ...$data,
            'cultivation_cycle_id' => $cycle->id,
            'user_id' => $request->user()->id,
            'label' => $data['label'] ?? CycleCostEntry::CATEGORIES[$data['category']],
            'recorded_at' => $data['recorded_at'] ?? now()->toDateString(),
        ]);

        $this->syncLegacyCostColumns($cycle);

        return back()->with('status', 'Biaya ditambahkan.');
    }

    public function destroyCostEntry(Request $request, CultivationCycle $cycle, CycleCostEntry $entry)
    {
        $this->authorizeCycle($request, $cycle);
        abort_unless($entry->cultivation_cycle_id === $cycle->id, 404);
        $entry->delete();
        $this->syncLegacyCostColumns($cycle);

        return back()->with('status', 'Catatan biaya dihapus.');
    }

    private function syncLegacyCostColumns(CultivationCycle $cycle): void
    {
        $sums = $cycle->costEntries()
            ->selectRaw('category, SUM(amount) as total')
            ->groupBy('category')
            ->pluck('total', 'category');

        $cycle->update([
            'seed_cost' => (float) ($sums['seed'] ?? 0),
            'feed_cost' => (float) ($sums['feed'] ?? 0),
            'electricity_cost' => (float) ($sums['electricity'] ?? 0),
            'medicine_cost' => (float) ($sums['medicine'] ?? 0),
            'other_cost' => (float) ($sums['other'] ?? 0),
            'estimated_revenue' => (float) ($sums['revenue'] ?? 0),
        ]);
    }

    public function storeWaterQuality(Request $request, CultivationCycle $cycle, WaterQualityService $service)
    {
        $this->authorizeCycle($request, $cycle);
        $cycle->loadMissing('fishSpecies');

        $data = $request->validate([
            'ph' => ['required', 'numeric', 'between:0,14'],
            'temperature_c' => ['required', 'numeric', 'between:0,50'],
            'dissolved_oxygen' => ['required', 'numeric', 'min:0'],
            'visual_condition' => ['nullable', 'string', 'max:100'],
            'odor' => ['nullable', 'string', 'max:100'],
            'notes' => ['nullable', 'string'],
            'measured_at' => ['nullable', 'date'],
        ]);

        $log = WaterQualityLog::create([
            ...$data,
            'cultivation_cycle_id' => $cycle->id,
            'pond_id' => $cycle->pond_id,
            'user_id' => $request->user()->id,
            'measured_at' => $data['measured_at'] ?? now(),
        ]);

        $health = $service->processLog($log->load('cycle.fishSpecies'));

        $speciesLabel = $cycle->fishSpecies?->name ? " untuk {$cycle->fishSpecies->name}" : '';

        return back()->with('status', "Kualitas air tersimpan. Skor{$speciesLabel}: {$health->score} ({$health->category}).");
    }

    public function storeFeeding(Request $request, CultivationCycle $cycle)
    {
        $this->authorizeCycle($request, $cycle);

        $data = $request->validate([
            'fed_at' => ['nullable', 'date'],
            'feed_type' => ['nullable', 'string', 'max:100'],
            'amount_kg' => ['required', 'numeric', 'min:0'],
            'leftover_kg' => ['nullable', 'numeric', 'min:0'],
            'status' => ['required', 'in:done,skipped,partial'],
            'notes' => ['nullable', 'string'],
            'feeding_schedule_id' => ['nullable', 'exists:feeding_schedules,id'],
        ]);

        FeedingLog::create([
            ...$data,
            'cultivation_cycle_id' => $cycle->id,
            'user_id' => $request->user()->id,
            'fed_at' => $data['fed_at'] ?? now(),
        ]);

        return back()->with('status', 'Riwayat pakan dicatat.');
    }

    public function storeSchedule(Request $request, CultivationCycle $cycle)
    {
        $this->authorizeCycle($request, $cycle);

        $data = $request->validate([
            'feed_time' => ['required'],
            'feed_type' => ['nullable', 'string', 'max:100'],
            'amount_kg' => ['nullable', 'numeric', 'min:0'],
            'notes' => ['nullable', 'string'],
        ]);

        FeedingSchedule::create([
            ...$data,
            'cultivation_cycle_id' => $cycle->id,
            'is_active' => true,
        ]);

        return back()->with('status', 'Jadwal pakan ditambahkan.');
    }

    public function updateSchedule(Request $request, CultivationCycle $cycle, FeedingSchedule $schedule)
    {
        $this->authorizeCycle($request, $cycle);
        abort_unless($schedule->cultivation_cycle_id === $cycle->id, 404);

        $data = $request->validate([
            'feed_time' => ['required'],
            'feed_type' => ['nullable', 'string', 'max:100'],
            'amount_kg' => ['nullable', 'numeric', 'min:0'],
            'notes' => ['nullable', 'string'],
            'is_active' => ['sometimes', 'boolean'],
        ]);

        $schedule->update([
            'feed_time' => $data['feed_time'],
            'feed_type' => $data['feed_type'] ?? $schedule->feed_type,
            'amount_kg' => $data['amount_kg'] ?? $schedule->amount_kg,
            'notes' => $data['notes'] ?? $schedule->notes,
            'is_active' => (bool) (int) $request->input('is_active', 1),
        ]);

        return back()->with('status', 'Jadwal pakan diperbarui.');
    }

    public function destroySchedule(Request $request, CultivationCycle $cycle, FeedingSchedule $schedule)
    {
        $this->authorizeCycle($request, $cycle);
        abort_unless($schedule->cultivation_cycle_id === $cycle->id, 404);
        $schedule->delete();

        return back()->with('status', 'Jadwal pakan dihapus.');
    }

    public function storeMortality(Request $request, CultivationCycle $cycle)
    {
        $this->authorizeCycle($request, $cycle);

        $data = $request->validate([
            'recorded_at' => ['nullable', 'date'],
            'death_count' => ['required', 'integer', 'min:1'],
            'suspected_cause' => ['nullable', 'string', 'max:255'],
            'notes' => ['nullable', 'string'],
        ]);

        MortalityLog::create([
            ...$data,
            'cultivation_cycle_id' => $cycle->id,
            'user_id' => $request->user()->id,
            'recorded_at' => $data['recorded_at'] ?? now()->toDateString(),
        ]);

        return back()->with('status', 'Kematian dicatat. Sisa ikan: '.$cycle->fresh()->aliveCount());
    }

    public function storeGrowth(Request $request, CultivationCycle $cycle)
    {
        $this->authorizeCycle($request, $cycle);

        $data = $request->validate([
            'sampled_at' => ['nullable', 'date'],
            'sample_count' => ['nullable', 'integer', 'min:1'],
            'avg_weight_gram' => ['required', 'numeric', 'min:0'],
            'avg_length_cm' => ['nullable', 'numeric', 'min:0'],
            'notes' => ['nullable', 'string'],
        ]);

        GrowthRecord::create([
            ...$data,
            'cultivation_cycle_id' => $cycle->id,
            'user_id' => $request->user()->id,
            'sampled_at' => $data['sampled_at'] ?? now()->toDateString(),
            'estimated_alive' => $cycle->aliveCount(),
        ]);

        return back()->with('status', 'Data pertumbuhan dicatat.');
    }

    public function updateCosts(Request $request, CultivationCycle $cycle)
    {
        return redirect()->route('user.cycles.costs', $cycle);
    }

    public function deactivate(Request $request, CultivationCycle $cycle)
    {
        $this->authorizeCycle($request, $cycle);

        if (in_array($cycle->status, ['failed', 'completed'], true)) {
            return back()->with('status', 'Siklus sudah tidak aktif.');
        }

        $cycle->update(['status' => 'failed']);

        return redirect()->route('user.ponds.index')->with('status', 'Siklus dinonaktifkan.');
    }

    public function destroy(Request $request, CultivationCycle $cycle)
    {
        $this->authorizeCycle($request, $cycle);

        $name = $cycle->name;
        $cycle->delete();

        return redirect()->route('user.ponds.index')->with('status', "Siklus \"{$name}\" dihapus.");
    }
}
