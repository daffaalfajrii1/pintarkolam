<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CultivationCycle;
use App\Models\FarmerProfile;
use App\Models\FishSpecies;
use App\Models\FishSpeciesParameter;
use App\Models\Pond;
use App\Models\PondHealthScore;
use App\Models\Product;
use App\Models\RecommendationRule;
use App\Models\User;
use App\Models\WaterQualityLog;
use App\Models\WaterQualityParameter;
use App\Notifications\UserAlertNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class DashboardController extends Controller
{
    public function index()
    {
        $activeCycleStatuses = ['active', 'near_harvest'];

        $stats = [
            'users' => User::count(),
            'farmers' => FarmerProfile::where('verification_status', 'approved')->count(),
            'farmers_pending' => FarmerProfile::where(function ($q) {
                $q->where('verification_status', 'pending')->orWhere('storefront_status', 'pending');
            })->count(),
            'ponds' => Pond::where('status', 'active')->count(),
            'cycles_active' => CultivationCycle::where('status', 'active')->count(),
            'cycles_near' => CultivationCycle::where('status', 'near_harvest')->count(),
            'cycles_running' => CultivationCycle::whereIn('status', $activeCycleStatuses)->count(),
            'cycles_completed' => CultivationCycle::where('status', 'completed')->count(),
            'critical' => PondHealthScore::where('category', 'kritis')->where('calculated_at', '>=', now()->subDays(7))->count(),
            'waspada' => PondHealthScore::where('category', 'waspada')->where('calculated_at', '>=', now()->subDays(7))->count(),
            'products' => Product::where('availability', 'available')->where('moderation_status', 'approved')->count(),
            'products_pending' => Product::where('moderation_status', 'pending')->count(),
            'shops_suspended' => FarmerProfile::where('storefront_status', 'suspended')->count(),
        ];

        $runningCycles = CultivationCycle::with(['user.farmerProfile', 'pond', 'fishSpecies'])
            ->whereIn('status', $activeCycleStatuses)
            ->latest()
            ->limit(10)
            ->get();

        return view('admin.dashboard', [
            'stats' => $stats,
            'runningCycles' => $runningCycles,
            'pendingFarmers' => FarmerProfile::with('user')->where(function ($q) {
                $q->where('verification_status', 'pending')->orWhere('storefront_status', 'pending');
            })->latest()->limit(8)->get(),
            'pendingProducts' => Product::with('user')->where('moderation_status', 'pending')->latest()->limit(8)->get(),
        ]);
    }

    public function users()
    {
        return view('admin.users', [
            'users' => User::with('roles', 'farmerProfile')->latest()->paginate(20),
        ]);
    }

    public function farmers(Request $request)
    {
        $q = trim((string) $request->query('q', ''));

        $farmers = FarmerProfile::query()
            ->with(['user' => fn ($query) => $query->withCount(['ponds', 'cycles'])])
            ->withCount('ponds')
            ->when($q !== '', function ($query) use ($q) {
                $query->where(function ($inner) use ($q) {
                    $inner->where('shop_name', 'like', "%{$q}%")
                        ->orWhere('business_name', 'like', "%{$q}%")
                        ->orWhere('owner_name', 'like', "%{$q}%")
                        ->orWhere('district', 'like', "%{$q}%")
                        ->orWhereHas('user', function ($user) use ($q) {
                            $user->where('name', 'like', "%{$q}%")
                                ->orWhere('email', 'like', "%{$q}%");
                        });
                });
            })
            ->latest()
            ->paginate(15)
            ->withQueryString();

        return view('admin.farmers', [
            'farmers' => $farmers,
            'q' => $q,
        ]);
    }

    public function showFarmer(FarmerProfile $farmer)
    {
        $farmer->load([
            'user',
            'products.photos',
            'ponds.latestHealthScore',
        ]);

        $ponds = $farmer->user
            ? $farmer->user->ponds()->with('latestHealthScore')->latest()->get()
            : $farmer->ponds;
        $cycles = $farmer->user
            ? $farmer->user->cycles()->with(['pond', 'fishSpecies'])->latest()->get()
            : collect();

        return view('admin.farmers-show', compact('farmer', 'ponds', 'cycles'));
    }

    public function verifyFarmer(Request $request, FarmerProfile $farmer)
    {
        $data = $request->validate([
            'verification_status' => ['nullable', 'in:approved,rejected,pending'],
            'storefront_status' => ['nullable', 'in:approved,rejected,suspended,inactive,pending'],
            'rejection_reason' => ['nullable', 'string'],
            'storefront_rejection_reason' => ['nullable', 'string'],
        ]);

        $payload = array_filter($data, fn ($v) => $v !== null);

        if (($payload['verification_status'] ?? null) === 'approved') {
            $payload['verified_at'] = now();
            $payload['verified_by'] = $request->user()->id;
            $payload['rejection_reason'] = null;
        }

        if (($payload['storefront_status'] ?? null) === 'approved') {
            $payload['storefront_rejection_reason'] = null;
            if (($farmer->verification_status !== 'approved') && (($payload['verification_status'] ?? null) !== 'approved')) {
                $payload['verification_status'] = 'approved';
                $payload['verified_at'] = now();
                $payload['verified_by'] = $request->user()->id;
            }
        }

        if (($payload['storefront_status'] ?? null) === 'pending') {
            $payload['storefront_rejection_reason'] = null;
        }

        $farmer->update($payload);
        $farmer->refresh();

        if ($farmer->user) {
            if (($payload['storefront_status'] ?? null) === 'approved') {
                $farmer->user->notify(new UserAlertNotification(
                    'Profil toko disetujui',
                    'Profil toko Anda telah disetujui. Anda sekarang dapat menjual di katalog.',
                    'shop',
                    route('user.products.create'),
                ));
            }
            if (($payload['storefront_status'] ?? null) === 'rejected') {
                $farmer->user->notify(new UserAlertNotification(
                    'Profil toko ditolak',
                    $payload['storefront_rejection_reason'] ?? 'Profil toko ditolak admin. Perbaiki lalu kirim ulang.',
                    'shop',
                    route('user.shop.edit'),
                    ['reason' => $payload['storefront_rejection_reason'] ?? null],
                ));
            }
            if (($payload['storefront_status'] ?? null) === 'suspended') {
                $farmer->user->notify(new UserAlertNotification(
                    'Toko ditangguhkan',
                    $payload['storefront_rejection_reason'] ?? 'Etalase toko Anda ditangguhkan sementara oleh admin.',
                    'shop',
                    route('user.shop.edit'),
                ));
            }
            if (($payload['storefront_status'] ?? null) === 'pending') {
                $farmer->user->notify(new UserAlertNotification(
                    'Status toko direset',
                    'Status etalase toko direset ke pending. Lengkapi profil lalu menunggu persetujuan ulang.',
                    'shop',
                    route('user.shop.edit'),
                ));
            }
            if (($payload['verification_status'] ?? null) === 'rejected') {
                $farmer->user->notify(new UserAlertNotification(
                    'Verifikasi pembudidaya ditolak',
                    $payload['rejection_reason'] ?? 'Verifikasi ditolak admin.',
                    'shop',
                    route('user.shop.edit'),
                ));
            }
        }

        return back()->with('status', 'Status pembudidaya / toko diperbarui.');
    }

    public function products()
    {
        return view('admin.products', [
            'products' => Product::with(['user', 'fishSpecies', 'farmerProfile', 'photos'])->latest()->paginate(20),
        ]);
    }

    public function showProduct(Product $product)
    {
        $product->load(['user', 'fishSpecies', 'farmerProfile', 'photos']);

        return view('admin.products-show', compact('product'));
    }

    public function moderateProduct(Request $request, Product $product)
    {
        $data = $request->validate([
            'moderation_status' => ['required', 'in:approved,rejected'],
            'rejection_reason' => ['nullable', 'string', 'required_if:moderation_status,rejected'],
        ]);

        $product->update([
            'moderation_status' => $data['moderation_status'],
            'rejection_reason' => $data['moderation_status'] === 'rejected' ? $data['rejection_reason'] : null,
            'is_published' => $data['moderation_status'] === 'approved',
            'moderated_by' => $request->user()->id,
            'moderated_at' => now(),
        ]);

        if ($product->user) {
            if ($data['moderation_status'] === 'approved') {
                $product->user->notify(new UserAlertNotification(
                    'Produk katalog disetujui',
                    "Produk \"{$product->title}\" telah disetujui dan tayang di katalog.",
                    'catalog',
                    route('user.products.index'),
                ));
            } else {
                $product->user->notify(new UserAlertNotification(
                    'Produk katalog ditolak',
                    $data['rejection_reason'] ?? "Produk \"{$product->title}\" ditolak admin.",
                    'catalog',
                    route('user.products.index'),
                    ['product_id' => $product->id, 'reason' => $data['rejection_reason'] ?? null],
                ));
            }
        }

        return back()->with('status', 'Moderasi produk selesai.');
    }

    public function ponds()
    {
        return redirect()->route('admin.farmers');
    }

    public function cycles()
    {
        return redirect()->route('admin.farmers');
    }

    public function water(Request $request)
    {
        $users = User::query()
            ->whereHas('roles', fn ($q) => $q->whereIn('name', ['pembudidaya', 'admin']))
            ->whereHas('cycles')
            ->with(['farmerProfile'])
            ->orderBy('name')
            ->get();

        $selectedUserId = $request->integer('user_id') ?: $users->first()?->id;

        $cycles = collect();
        $logs = null;
        $selectedUser = null;

        if ($selectedUserId) {
            $selectedUser = User::with('farmerProfile')->find($selectedUserId);
            $cycles = CultivationCycle::with(['pond', 'fishSpecies'])
                ->where('user_id', $selectedUserId)
                ->whereIn('status', ['active', 'near_harvest', 'completed'])
                ->latest()
                ->get();

            $cycleId = $request->integer('cycle_id');
            $logsQuery = WaterQualityLog::with(['cycle', 'pond'])
                ->where('user_id', $selectedUserId)
                ->latest('measured_at');

            if ($cycleId) {
                $logsQuery->where('cultivation_cycle_id', $cycleId);
            }

            $logs = $logsQuery->paginate(25)->withQueryString();
        }

        return view('admin.water', compact('users', 'selectedUser', 'cycles', 'logs'));
    }

    public function species()
    {
        return view('admin.species', [
            'species' => FishSpecies::orderBy('name')->paginate(20),
        ]);
    }

    public function storeSpecies(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:100'],
            'scientific_name' => ['nullable', 'string', 'max:150'],
            'typical_harvest_days' => ['nullable', 'integer', 'min:1'],
            'typical_harvest_weight_gram' => ['nullable', 'numeric', 'min:0'],
            'description' => ['nullable', 'string'],
        ]);

        FishSpecies::create([
            ...$data,
            'slug' => Str::slug($data['name']),
            'is_active' => true,
        ]);

        $species = FishSpecies::where('slug', Str::slug($data['name']))->first();
        if ($species) {
            $this->seedDefaultSpeciesParameters($species);
        }

        return back()->with('status', 'Jenis ikan ditambahkan.');
    }

    public function parameters(Request $request)
    {
        $species = FishSpecies::orderBy('name')->get();
        $selectedSpeciesId = $request->integer('species_id') ?: $species->first()?->id;

        $speciesParameters = $selectedSpeciesId
            ? FishSpeciesParameter::with('fishSpecies')
                ->where('fish_species_id', $selectedSpeciesId)
                ->orderBy('code')
                ->get()
            : collect();

        return view('admin.parameters', [
            'species' => $species,
            'selectedSpeciesId' => $selectedSpeciesId,
            'speciesParameters' => $speciesParameters,
            'globalParameters' => WaterQualityParameter::orderBy('code')->get(),
        ]);
    }

    public function updateParameter(Request $request, WaterQualityParameter $parameter)
    {
        $data = $request->validate([
            'ideal_min' => ['nullable', 'numeric'],
            'ideal_max' => ['nullable', 'numeric'],
            'min_value' => ['nullable', 'numeric'],
            'max_value' => ['nullable', 'numeric'],
            'weight' => ['required', 'integer', 'min:1', 'max:100'],
            'is_active' => ['sometimes', 'boolean'],
        ]);

        $parameter->update([
            ...$data,
            'is_active' => $request->boolean('is_active'),
        ]);

        return back()->with('status', 'Parameter global diperbarui.');
    }

    public function updateSpeciesParameter(Request $request, FishSpeciesParameter $parameter)
    {
        $data = $request->validate([
            'ideal_min' => ['nullable', 'numeric'],
            'ideal_max' => ['nullable', 'numeric'],
            'min_value' => ['nullable', 'numeric'],
            'max_value' => ['nullable', 'numeric'],
            'weight' => ['required', 'integer', 'min:1', 'max:100'],
            'is_active' => ['sometimes', 'boolean'],
        ]);

        $parameter->update([
            ...$data,
            'is_active' => $request->boolean('is_active'),
        ]);

        return back()->with('status', 'Parameter jenis ikan diperbarui.');
    }

    public function rules(Request $request)
    {
        $speciesId = $request->integer('species_id') ?: null;
        $allSpecies = FishSpecies::orderBy('name')->get();

        $groups = collect();
        $paginator = null;

        if ($speciesId) {
            $sp = $allSpecies->firstWhere('id', $speciesId);
            $groups->push([
                'label' => $sp?->name ?? 'Jenis ikan',
                'species_id' => $speciesId,
                'rules' => RecommendationRule::query()
                    ->where('fish_species_id', $speciesId)
                    ->orderBy('parameter_code')
                    ->orderBy('priority')
                    ->get(),
            ]);
        } else {
            // Paginate per jenis ikan (bukan per baris aturan)
            $paginator = FishSpecies::query()
                ->orderBy('name')
                ->paginate(3)
                ->withQueryString();

            if ($paginator->onFirstPage()) {
                $globalRules = RecommendationRule::query()
                    ->whereNull('fish_species_id')
                    ->orderBy('parameter_code')
                    ->orderBy('priority')
                    ->get();

                if ($globalRules->isNotEmpty()) {
                    $groups->push([
                        'label' => 'Global (semua ikan)',
                        'species_id' => null,
                        'rules' => $globalRules,
                    ]);
                }
            }

            foreach ($paginator as $sp) {
                $groups->push([
                    'label' => $sp->name,
                    'species_id' => $sp->id,
                    'rules' => RecommendationRule::query()
                        ->where('fish_species_id', $sp->id)
                        ->orderBy('parameter_code')
                        ->orderBy('priority')
                        ->get(),
                ]);
            }
        }

        return view('admin.rules', [
            'groups' => $groups,
            'paginator' => $paginator,
            'species' => $allSpecies,
            'selectedSpeciesId' => $speciesId,
        ]);
    }

    public function storeRule(Request $request)
    {
        $data = $request->validate([
            'fish_species_id' => ['nullable', 'exists:fish_species,id'],
            'parameter_code' => ['required', 'string', 'max:50'],
            'title' => ['required', 'string', 'max:255'],
            'advice' => ['required', 'string'],
            'severity' => ['required', 'in:info,warning,critical'],
            'min_value' => ['nullable', 'numeric'],
            'max_value' => ['nullable', 'numeric'],
            'priority' => ['nullable', 'integer', 'min:1'],
        ]);

        RecommendationRule::create([
            ...$data,
            'priority' => $data['priority'] ?? 100,
            'is_active' => true,
        ]);

        return back()->with('status', 'Aturan rekomendasi ditambahkan.');
    }

    private function seedDefaultSpeciesParameters(FishSpecies $species): void
    {
        $defaults = [
            ['code' => 'ph', 'name' => 'pH', 'unit' => '', 'min_value' => 6.0, 'max_value' => 9.0, 'ideal_min' => 6.5, 'ideal_max' => 8.5, 'weight' => 30],
            ['code' => 'temperature', 'name' => 'Suhu', 'unit' => '°C', 'min_value' => 20, 'max_value' => 35, 'ideal_min' => 25, 'ideal_max' => 32, 'weight' => 30],
            ['code' => 'do', 'name' => 'Oksigen Terlarut (DO)', 'unit' => 'mg/L', 'min_value' => 2, 'max_value' => 12, 'ideal_min' => 4, 'ideal_max' => 8, 'weight' => 40],
        ];

        foreach ($defaults as $param) {
            FishSpeciesParameter::firstOrCreate(
                ['fish_species_id' => $species->id, 'code' => $param['code']],
                $param + ['is_active' => true]
            );
        }
    }
}
