<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\FarmerProfileResource;
use App\Http\Resources\PondResource;
use App\Http\Responses\ApiResponse;
use App\Models\Pond;
use App\Models\PondPhoto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class MyPondController extends Controller
{
    public function dashboard(Request $request)
    {
        $user = $request->user();
        $pondId = $request->integer('pond_id') ?: null;

        $ponds = Pond::with(['photos', 'latestHealthScore'])
            ->where('user_id', $user->id)
            ->latest()
            ->get();

        if ($pondId && ! $ponds->contains('id', $pondId)) {
            $pondId = null;
        }

        $cyclesQuery = \App\Models\CultivationCycle::with(['pond', 'fishSpecies', 'harvestEstimate'])
            ->where('user_id', $user->id)
            ->whereIn('status', ['active', 'near_harvest', 'preparation']);

        if ($pondId) {
            $cyclesQuery->where('pond_id', $pondId);
        }

        $cycles = $cyclesQuery->latest()->get();

        $primaryPond = $pondId
            ? $ponds->firstWhere('id', $pondId)
            : ($ponds->first(fn ($p) => $p->latestHealthScore) ?? $ponds->first());
        $health = $primaryPond?->latestHealthScore;

        $waterQuery = \App\Models\WaterQualityLog::query()->where('user_id', $user->id);
        if ($pondId) {
            $waterQuery->where('pond_id', $pondId);
        }

        $latestWater = (clone $waterQuery)->latest('measured_at')->first();

        $waterLogs = (clone $waterQuery)
            ->latest('measured_at')
            ->limit(20)
            ->get()
            ->sortBy('measured_at')
            ->values();

        $cycleIds = $cycles->pluck('id');
        if ($cycleIds->isEmpty() && ! $pondId) {
            $cycleIds = \App\Models\CultivationCycle::where('user_id', $user->id)->pluck('id');
        }

        $recommendations = \App\Models\Recommendation::whereIn('cultivation_cycle_id', $cycleIds)
            ->with('cycle')
            ->latest()
            ->limit(8)
            ->get()
            ->map(fn ($rec) => [
                'id' => $rec->id,
                'title' => $rec->title,
                'advice' => $rec->advice,
                'severity' => $rec->severity,
                'cycle_name' => $rec->cycle?->name,
                'created_at' => $rec->created_at?->toISOString(),
            ]);

        $alertCounts = [
            'baik' => $ponds->filter(fn ($p) => in_array($p->latestHealthScore?->category, ['baik', 'normal'], true))->count(),
            'waspada' => $ponds->filter(fn ($p) => ($p->latestHealthScore?->category ?? '') === 'waspada')->count(),
            'kritis' => $ponds->filter(fn ($p) => ($p->latestHealthScore?->category ?? '') === 'kritis')->count(),
        ];

        $categoryLabel = match ($health?->category) {
            'baik', 'normal' => 'Normal',
            'waspada' => 'Waspada',
            'kritis' => 'Kritis',
            default => 'Belum ada data',
        };

        return ApiResponse::success([
            'user_name' => $user->name,
            'selected_pond_id' => $primaryPond?->id,
            'ponds_count' => $ponds->count(),
            'cycles_count' => $cycles->count(),
            'alert_counts' => $alertCounts,
            'health' => [
                'score' => $health?->score,
                'category' => $health?->category,
                'category_label' => $categoryLabel,
                'message' => match ($health?->category) {
                    'baik', 'normal' => 'Kondisi kolam dalam keadaan baik',
                    'waspada' => 'Perlu perhatian, pantau kualitas air',
                    'kritis' => 'Kondisi kritis, segera tindak lanjuti',
                    default => 'Belum ada skor kesehatan. Input kualitas air dulu.',
                },
                'pond_name' => $primaryPond?->name,
                'pond_id' => $primaryPond?->id,
            ],
            'latest_water' => $latestWater ? [
                'ph' => (float) $latestWater->ph,
                'temperature_c' => (float) $latestWater->temperature_c,
                'dissolved_oxygen' => (float) $latestWater->dissolved_oxygen,
                'measured_at' => $latestWater->measured_at?->toISOString(),
                'cycle_id' => $latestWater->cultivation_cycle_id,
                'pond_id' => $latestWater->pond_id,
            ] : null,
            'chart' => [
                'labels' => $waterLogs->map(fn ($l) => optional($l->measured_at)->format('d/m H:i'))->values(),
                'ph' => $waterLogs->pluck('ph')->map(fn ($v) => (float) $v)->values(),
                'temp' => $waterLogs->pluck('temperature_c')->map(fn ($v) => (float) $v)->values(),
                'do' => $waterLogs->pluck('dissolved_oxygen')->map(fn ($v) => (float) $v)->values(),
            ],
            'recommendations' => $recommendations,
            'ponds' => PondResource::collection($ponds),
            'cycles' => \App\Http\Resources\CultivationCycleResource::collection($cycles),
        ], 'Dashboard pembudidaya');
    }

    public function profile(Request $request)
    {
        $profile = $request->user()->farmerProfile;
        if (! $profile) {
            return ApiResponse::error('Profil pembudidaya belum tersedia.', 404);
        }

        return ApiResponse::success(new FarmerProfileResource($profile), 'Profil pembudidaya');
    }

    public function updateProfile(Request $request)
    {
        $user = $request->user();
        $profile = $user->farmerProfile;
        if (! $profile) {
            return ApiResponse::error('Profil pembudidaya belum tersedia.', 404);
        }

        $data = $request->validate([
            'business_name' => ['sometimes', 'string', 'max:255'],
            'owner_name' => ['sometimes', 'string', 'max:255'],
            'address' => ['nullable', 'string'],
            'village' => ['nullable', 'string', 'max:100'],
            'district' => ['nullable', 'string', 'max:100'],
            'whatsapp' => ['nullable', 'string', 'max:30'],
            'bio' => ['nullable', 'string'],
            'latitude' => ['nullable', 'numeric'],
            'longitude' => ['nullable', 'numeric'],
            'hide_exact_location' => ['sometimes', 'boolean'],
            'request_storefront' => ['sometimes', 'boolean'],
        ]);

        if (! empty($data['request_storefront'])) {
            $data['storefront_status'] = 'pending';
            unset($data['request_storefront']);
        }

        $profile->update($data);

        return ApiResponse::success(new FarmerProfileResource($profile->fresh()), 'Profil diperbarui');
    }

    public function index(Request $request)
    {
        $this->authorize('viewAny', Pond::class);

        $ponds = Pond::with(['photos', 'latestHealthScore'])
            ->where('user_id', $request->user()->id)
            ->latest()
            ->paginate(15);

        return ApiResponse::success(PondResource::collection($ponds), 'Daftar kolam');
    }

    public function store(Request $request)
    {
        $this->authorize('create', Pond::class);

        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'type' => ['required', 'in:beton,terpal,tanah,bioflok,lainnya'],
            'area_m2' => ['nullable', 'numeric', 'min:0'],
            'depth_m' => ['nullable', 'numeric', 'min:0'],
            'volume_m3' => ['nullable', 'numeric', 'min:0'],
            'latitude' => ['nullable', 'numeric'],
            'longitude' => ['nullable', 'numeric'],
            'hide_exact_location' => ['sometimes', 'boolean'],
            'notes' => ['nullable', 'string'],
            'status' => ['sometimes', 'in:active,inactive,maintenance'],
            'photo' => ['nullable', 'image', 'max:5120'],
        ]);

        if (empty($data['volume_m3']) && ! empty($data['area_m2']) && ! empty($data['depth_m'])) {
            $data['volume_m3'] = round($data['area_m2'] * $data['depth_m'], 2);
        }

        $data['user_id'] = $request->user()->id;
        $data['farmer_profile_id'] = $request->user()->farmerProfile?->id;

        $pond = Pond::create($data);

        if ($request->hasFile('photo')) {
            $path = $request->file('photo')->store('ponds', 'public');
            PondPhoto::create([
                'pond_id' => $pond->id,
                'path' => $path,
                'is_primary' => true,
            ]);
        }

        return ApiResponse::success(
            new PondResource($pond->load(['photos', 'latestHealthScore'])),
            'Kolam berhasil ditambahkan',
            201
        );
    }

    public function show(Request $request, Pond $pond)
    {
        $this->authorize('view', $pond);

        return ApiResponse::success(
            new PondResource($pond->load(['photos', 'latestHealthScore', 'cycles.fishSpecies'])),
            'Detail kolam'
        );
    }

    public function update(Request $request, Pond $pond)
    {
        $this->authorize('update', $pond);

        $data = $request->validate([
            'name' => ['sometimes', 'string', 'max:255'],
            'type' => ['sometimes', 'in:beton,terpal,tanah,bioflok,lainnya'],
            'area_m2' => ['nullable', 'numeric', 'min:0'],
            'depth_m' => ['nullable', 'numeric', 'min:0'],
            'volume_m3' => ['nullable', 'numeric', 'min:0'],
            'latitude' => ['nullable', 'numeric'],
            'longitude' => ['nullable', 'numeric'],
            'hide_exact_location' => ['sometimes', 'boolean'],
            'notes' => ['nullable', 'string'],
            'status' => ['sometimes', 'in:active,inactive,maintenance'],
            'photo' => ['nullable', 'image', 'max:5120'],
        ]);

        $pond->update($data);

        if ($request->hasFile('photo')) {
            $path = $request->file('photo')->store('ponds', 'public');
            PondPhoto::create([
                'pond_id' => $pond->id,
                'path' => $path,
                'is_primary' => true,
            ]);
        }

        return ApiResponse::success(new PondResource($pond->fresh()->load(['photos', 'latestHealthScore'])), 'Kolam diperbarui');
    }

    public function destroy(Pond $pond)
    {
        $this->authorize('delete', $pond);
        $pond->delete();

        return ApiResponse::success(null, 'Kolam dihapus');
    }
}
