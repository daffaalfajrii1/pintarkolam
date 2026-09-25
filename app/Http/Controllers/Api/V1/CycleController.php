<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\CultivationCycleResource;
use App\Http\Resources\FeedingLogResource;
use App\Http\Resources\FeedingScheduleResource;
use App\Http\Resources\GrowthRecordResource;
use App\Http\Resources\HarvestEstimateResource;
use App\Http\Resources\MortalityLogResource;
use App\Http\Resources\RecommendationResource;
use App\Http\Resources\WaterQualityLogResource;
use App\Http\Responses\ApiResponse;
use App\Models\CultivationCycle;
use App\Models\FeedingLog;
use App\Models\FeedingSchedule;
use App\Models\GrowthRecord;
use App\Models\MortalityLog;
use App\Models\Pond;
use App\Models\WaterQualityLog;
use App\Services\HarvestEstimateService;
use App\Services\WaterQualityService;
use Illuminate\Http\Request;

class CycleController extends Controller
{
    public function index(Request $request)
    {
        $this->authorize('viewAny', CultivationCycle::class);

        $cycles = CultivationCycle::with(['pond', 'fishSpecies', 'harvestEstimate'])
            ->where('user_id', $request->user()->id)
            ->latest()
            ->paginate(15);

        return ApiResponse::success(CultivationCycleResource::collection($cycles), 'Daftar siklus');
    }

    public function store(Request $request)
    {
        $this->authorize('create', CultivationCycle::class);

        $data = $request->validate([
            'pond_id' => ['required', 'exists:ponds,id'],
            'fish_species_id' => ['required', 'exists:fish_species,id'],
            'name' => ['required', 'string', 'max:255'],
            'stocking_date' => ['required', 'date'],
            'seed_count' => ['required', 'integer', 'min:1'],
            'initial_size_gram' => ['nullable', 'numeric', 'min:0'],
            'initial_size_cm' => ['nullable', 'numeric', 'min:0'], // alias lama
            'seed_source' => ['nullable', 'string', 'max:255'],
            'target_size_gram' => ['nullable', 'numeric', 'min:0'],
            'target_harvest_date' => ['nullable', 'date'],
            'status' => ['sometimes', 'in:preparation,active,near_harvest,completed,failed'],
            'notes' => ['nullable', 'string'],
            'feed_times' => ['nullable', 'array'],
            'feed_times.*' => ['date_format:H:i'],
        ]);

        $pond = Pond::findOrFail($data['pond_id']);
        $this->authorize('update', $pond);

        if (! isset($data['initial_size_gram']) && isset($data['initial_size_cm'])) {
            $data['initial_size_gram'] = $data['initial_size_cm'];
        }
        unset($data['initial_size_cm'], $data['feed_times']);

        $feedTimes = $request->input('feed_times', ['07:00', '17:00']);

        $data['user_id'] = $request->user()->id;
        $data['status'] = $data['status'] ?? 'active';

        $cycle = CultivationCycle::create($data);

        foreach ($feedTimes as $time) {
            FeedingSchedule::create([
                'cultivation_cycle_id' => $cycle->id,
                'feed_time' => $time,
                'feed_type' => 'Pelet',
                'amount_kg' => max(0.1, round($cycle->seed_count * 0.02 / 1000, 3)),
                'is_active' => true,
            ]);
        }

        return ApiResponse::success(
            new CultivationCycleResource($cycle->load(['pond', 'fishSpecies', 'harvestEstimate'])),
            'Siklus budidaya dibuat',
            201
        );
    }

    public function show(CultivationCycle $cycle)
    {
        $this->authorize('view', $cycle);

        return ApiResponse::success(
            new CultivationCycleResource($cycle->load(['pond', 'fishSpecies', 'harvestEstimate'])),
            'Detail siklus'
        );
    }

    public function update(Request $request, CultivationCycle $cycle)
    {
        $this->authorize('update', $cycle);

        $data = $request->validate([
            'name' => ['sometimes', 'string', 'max:255'],
            'fish_species_id' => ['sometimes', 'exists:fish_species,id'],
            'stocking_date' => ['sometimes', 'date'],
            'seed_count' => ['sometimes', 'integer', 'min:1'],
            'initial_size_gram' => ['nullable', 'numeric', 'min:0'],
            'initial_size_cm' => ['nullable', 'numeric', 'min:0'], // alias lama
            'seed_source' => ['nullable', 'string', 'max:255'],
            'target_size_gram' => ['nullable', 'numeric', 'min:0'],
            'target_harvest_date' => ['nullable', 'date'],
            'status' => ['sometimes', 'in:preparation,active,near_harvest,completed,failed'],
            'notes' => ['nullable', 'string'],
        ]);

        if (! isset($data['initial_size_gram']) && isset($data['initial_size_cm'])) {
            $data['initial_size_gram'] = $data['initial_size_cm'];
        }
        unset($data['initial_size_cm']);

        $cycle->update($data);

        return ApiResponse::success(
            new CultivationCycleResource($cycle->fresh()->load(['pond', 'fishSpecies', 'harvestEstimate'])),
            'Siklus diperbarui'
        );
    }

    public function waterQualityIndex(CultivationCycle $cycle)
    {
        $this->authorize('view', $cycle);

        $logs = $cycle->waterQualityLogs()->latest('measured_at')->paginate(20);

        return ApiResponse::success(WaterQualityLogResource::collection($logs), 'Riwayat kualitas air');
    }

    public function waterQualityStore(Request $request, CultivationCycle $cycle, WaterQualityService $service)
    {
        $this->authorize('update', $cycle);

        $data = $request->validate([
            'ph' => ['required', 'numeric', 'between:0,14'],
            'temperature_c' => ['required', 'numeric', 'between:0,50'],
            'dissolved_oxygen' => ['required', 'numeric', 'min:0'],
            'visual_condition' => ['nullable', 'string', 'max:100'],
            'odor' => ['nullable', 'string', 'max:100'],
            'notes' => ['nullable', 'string'],
            'measured_at' => ['nullable', 'date'],
            'photo' => ['nullable', 'image', 'max:5120'],
        ]);

        if ($request->hasFile('photo')) {
            $data['photo_path'] = $request->file('photo')->store('water-quality', 'public');
        }

        $log = WaterQualityLog::create([
            ...$data,
            'cultivation_cycle_id' => $cycle->id,
            'pond_id' => $cycle->pond_id,
            'user_id' => $request->user()->id,
            'measured_at' => $data['measured_at'] ?? now(),
        ]);

        $health = $service->processLog($log->load('cycle.fishSpecies'));

        return ApiResponse::success([
            'log' => new WaterQualityLogResource($log),
            'health_score' => [
                'score' => $health->score,
                'category' => $health->category,
                'factors' => $health->factors,
            ],
        ], 'Kualitas air tercatat', 201);
    }

    public function recommendations(CultivationCycle $cycle)
    {
        $this->authorize('view', $cycle);

        return ApiResponse::success(
            RecommendationResource::collection($cycle->recommendations()->latest()->paginate(20)),
            'Rekomendasi kualitas air'
        );
    }

    public function feedingSchedules(CultivationCycle $cycle)
    {
        $this->authorize('view', $cycle);

        return ApiResponse::success(
            FeedingScheduleResource::collection($cycle->feedingSchedules()->where('is_active', true)->get()),
            'Jadwal pakan'
        );
    }

    public function feedingSchedulesStore(Request $request, CultivationCycle $cycle)
    {
        $this->authorize('update', $cycle);

        $data = $request->validate([
            'feed_time' => ['required', 'date_format:H:i'],
            'feed_type' => ['nullable', 'string', 'max:100'],
            'amount_kg' => ['nullable', 'numeric', 'min:0'],
            'notes' => ['nullable', 'string'],
        ]);

        $schedule = FeedingSchedule::create([
            'cultivation_cycle_id' => $cycle->id,
            'feed_time' => $data['feed_time'],
            'feed_type' => $data['feed_type'] ?? 'Pelet',
            'amount_kg' => $data['amount_kg'] ?? 0.5,
            'notes' => $data['notes'] ?? null,
            'is_active' => true,
        ]);

        return ApiResponse::success(new FeedingScheduleResource($schedule), 'Jadwal pakan ditambahkan', 201);
    }

    public function feedingLogsStore(Request $request, CultivationCycle $cycle)
    {
        $this->authorize('update', $cycle);

        $data = $request->validate([
            'feeding_schedule_id' => ['nullable', 'exists:feeding_schedules,id'],
            'fed_at' => ['nullable', 'date'],
            'feed_type' => ['nullable', 'string', 'max:100'],
            'amount_kg' => ['required', 'numeric', 'min:0'],
            'leftover_kg' => ['nullable', 'numeric', 'min:0'],
            'status' => ['sometimes', 'in:done,skipped,partial'],
            'notes' => ['nullable', 'string'],
        ]);

        $log = FeedingLog::create([
            ...$data,
            'cultivation_cycle_id' => $cycle->id,
            'user_id' => $request->user()->id,
            'fed_at' => $data['fed_at'] ?? now(),
            'status' => $data['status'] ?? 'done',
        ]);

        return ApiResponse::success(new FeedingLogResource($log), 'Pemberian pakan tercatat', 201);
    }

    public function growthRecords(CultivationCycle $cycle)
    {
        $this->authorize('view', $cycle);

        return ApiResponse::success(
            GrowthRecordResource::collection($cycle->growthRecords()->latest('sampled_at')->paginate(20)),
            'Riwayat pertumbuhan'
        );
    }

    public function growthRecordsStore(Request $request, CultivationCycle $cycle, HarvestEstimateService $estimateService)
    {
        $this->authorize('update', $cycle);

        $data = $request->validate([
            'sampled_at' => ['required', 'date'],
            'sample_count' => ['nullable', 'integer', 'min:1'],
            'avg_weight_gram' => ['required', 'numeric', 'min:0'],
            'avg_length_cm' => ['nullable', 'numeric', 'min:0'],
            'estimated_alive' => ['nullable', 'integer', 'min:0'],
            'notes' => ['nullable', 'string'],
        ]);

        $record = GrowthRecord::create([
            ...$data,
            'cultivation_cycle_id' => $cycle->id,
            'user_id' => $request->user()->id,
        ]);

        $estimate = $estimateService->calculate($cycle);

        return ApiResponse::success([
            'growth' => new GrowthRecordResource($record),
            'harvest_estimate' => new HarvestEstimateResource($estimate),
        ], 'Pertumbuhan tercatat', 201);
    }

    public function harvestEstimate(CultivationCycle $cycle, HarvestEstimateService $estimateService)
    {
        $this->authorize('view', $cycle);

        $estimate = $cycle->harvestEstimate ?: $estimateService->calculate($cycle);

        return ApiResponse::success(new HarvestEstimateResource($estimate), 'Estimasi panen');
    }

    public function mortalityIndex(CultivationCycle $cycle)
    {
        $this->authorize('view', $cycle);

        return ApiResponse::success(
            MortalityLogResource::collection($cycle->mortalityLogs()->latest('recorded_at')->paginate(20)),
            'Riwayat kematian'
        );
    }

    public function mortalityStore(Request $request, CultivationCycle $cycle)
    {
        $this->authorize('update', $cycle);

        $data = $request->validate([
            'death_count' => ['required', 'integer', 'min:1'],
            'suspected_cause' => ['nullable', 'string', 'max:255'],
            'notes' => ['nullable', 'string'],
            'recorded_at' => ['nullable', 'date'],
        ]);

        $log = MortalityLog::create([
            ...$data,
            'cultivation_cycle_id' => $cycle->id,
            'user_id' => $request->user()->id,
            'recorded_at' => $data['recorded_at'] ?? now()->toDateString(),
        ]);

        return ApiResponse::success(new MortalityLogResource($log), 'Kematian tercatat', 201);
    }

    public function notes(Request $request)
    {
        $userId = $request->user()->id;
        $cycleIds = CultivationCycle::where('user_id', $userId)->pluck('id');

        $water = WaterQualityLog::with('cycle')
            ->whereIn('cultivation_cycle_id', $cycleIds)
            ->latest('measured_at')
            ->limit(15)
            ->get()
            ->map(fn ($log) => [
                'type' => 'water',
                'title' => 'Kualitas air',
                'subtitle' => $log->cycle?->name,
                'detail' => 'pH '.$log->ph.' · '.$log->temperature_c.'°C · DO '.$log->dissolved_oxygen,
                'at' => $log->measured_at?->toISOString(),
                'cycle_id' => $log->cultivation_cycle_id,
            ]);

        $feeding = FeedingLog::with('cycle')
            ->whereIn('cultivation_cycle_id', $cycleIds)
            ->latest('fed_at')
            ->limit(15)
            ->get()
            ->map(fn ($log) => [
                'type' => 'feeding',
                'title' => 'Pakan',
                'subtitle' => $log->cycle?->name,
                'detail' => ($log->feed_type ?: 'Pelet').' · '.$log->amount_kg.' kg · '.$log->status,
                'at' => $log->fed_at?->toISOString(),
                'cycle_id' => $log->cultivation_cycle_id,
            ]);

        $growth = GrowthRecord::with('cycle')
            ->whereIn('cultivation_cycle_id', $cycleIds)
            ->latest('sampled_at')
            ->limit(15)
            ->get()
            ->map(fn ($log) => [
                'type' => 'growth',
                'title' => 'Pertumbuhan',
                'subtitle' => $log->cycle?->name,
                'detail' => 'Berat rata-rata '.$log->avg_weight_gram.' g',
                'at' => $log->sampled_at?->toISOString() ?? $log->sampled_at?->toDateString(),
                'cycle_id' => $log->cultivation_cycle_id,
            ]);

        $items = $water->concat($feeding)->concat($growth)
            ->sortByDesc('at')
            ->values()
            ->take(30);

        return ApiResponse::success($items, 'Catatan aktivitas');
    }
}
