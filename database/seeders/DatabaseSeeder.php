<?php

namespace Database\Seeders;

use App\Models\Article;
use App\Models\CultivationCycle;
use App\Models\FarmerProfile;
use App\Models\FeatureFlag;
use App\Models\FishSpecies;
use App\Models\FishSpeciesParameter;
use App\Models\NotificationPreference;
use App\Models\Pond;
use App\Models\Product;
use App\Models\RecommendationRule;
use App\Models\Setting;
use App\Models\User;
use App\Models\WaterQualityParameter;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        foreach (['admin', 'pembudidaya', 'buyer'] as $role) {
            Role::findOrCreate($role);
        }

        $admin = User::updateOrCreate(
            ['email' => 'admin@pintarkolam.id'],
            [
                'name' => 'Admin PintarKolam',
                'phone' => '081234567890',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
                'is_active' => true,
            ]
        );
        $admin->syncRoles(['admin']);
        NotificationPreference::firstOrCreate(['user_id' => $admin->id]);

        $farmerUser = User::updateOrCreate(
            ['email' => 'pembudidaya@pintarkolam.id'],
            [
                'name' => 'Budi Pembudidaya',
                'phone' => '081298765432',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
                'is_active' => true,
            ]
        );
        $farmerUser->syncRoles(['pembudidaya']);
        NotificationPreference::firstOrCreate(['user_id' => $farmerUser->id]);
        FarmerProfile::updateOrCreate(
            ['user_id' => $farmerUser->id],
            [
                'business_name' => 'Kolam Nila Curup',
                'owner_name' => 'Budi Pembudidaya',
                'village' => 'Air Rambai',
                'district' => 'Curup',
                'regency' => 'Rejang Lebong',
                'province' => 'Bengkulu',
                'latitude' => -3.4705,
                'longitude' => 102.5207,
                'hide_exact_location' => true,
                'whatsapp' => '6281298765432',
                'bio' => 'Pembudidaya ikan nila dan lele di Curup.',
                'verification_status' => 'approved',
                'storefront_status' => 'approved',
                'verified_at' => now(),
                'verified_by' => $admin->id,
            ]
        );

        $buyer = User::updateOrCreate(
            ['email' => 'buyer@pintarkolam.id'],
            [
                'name' => 'Siti Buyer',
                'phone' => '081211122233',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
                'is_active' => true,
            ]
        );
        $buyer->syncRoles(['buyer']);
        NotificationPreference::firstOrCreate(['user_id' => $buyer->id]);

        $speciesList = [
            [
                'slug' => 'nila',
                'name' => 'Nila',
                'scientific_name' => 'Oreochromis niloticus',
                'description' => 'Ikan nila air tawar yang umum dibudidayakan di Rejang Lebong. Tahan cuaca dan mudah dipasarkan.',
                'typical_harvest_days' => 120,
                'typical_harvest_weight_gram' => 250,
            ],
            [
                'slug' => 'lele',
                'name' => 'Lele',
                'scientific_name' => 'Clarias gariepinus',
                'description' => 'Ikan lele dengan pertumbuhan cepat dan toleransi lingkungan baik. Cocok kolam terpal.',
                'typical_harvest_days' => 90,
                'typical_harvest_weight_gram' => 150,
            ],
            [
                'slug' => 'gurame',
                'name' => 'Gurame',
                'scientific_name' => 'Osphronemus goramy',
                'description' => 'Ikan gurame bernilai jual tinggi. Pertumbuhan relatif lambat, butuh kualitas air stabil.',
                'typical_harvest_days' => 240,
                'typical_harvest_weight_gram' => 500,
            ],
            [
                'slug' => 'patin',
                'name' => 'Patin',
                'scientific_name' => 'Pangasianodon hypophthalmus',
                'description' => 'Patin tumbuh cepat di kolam tanah/terpal. Padat tebar tinggi butuh aerasi cukup.',
                'typical_harvest_days' => 150,
                'typical_harvest_weight_gram' => 800,
            ],
            [
                'slug' => 'mas',
                'name' => 'Ikan Mas',
                'scientific_name' => 'Cyprinus carpio',
                'description' => 'Ikan mas klasik untuk kolam tradisional. Sensitif terhadap penurunan oksigen mendadak.',
                'typical_harvest_days' => 180,
                'typical_harvest_weight_gram' => 600,
            ],
            [
                'slug' => 'mujair',
                'name' => 'Mujair',
                'scientific_name' => 'Oreochromis mossambicus',
                'description' => 'Kerabat nila, mudah dibudidayakan di kolam rakyat. Adaptif terhadap variasi pH.',
                'typical_harvest_days' => 120,
                'typical_harvest_weight_gram' => 200,
            ],
            [
                'slug' => 'bawal',
                'name' => 'Bawal Air Tawar',
                'scientific_name' => 'Colossoma macropomum',
                'description' => 'Bawal air tawar (tambaqui) dengan nafsu makan tinggi. Butuh pakan cukup dan DO baik.',
                'typical_harvest_days' => 210,
                'typical_harvest_weight_gram' => 1000,
            ],
            [
                'slug' => 'gabus',
                'name' => 'Gabus',
                'scientific_name' => 'Channa striata',
                'description' => 'Ikan gabus bernilai ekonomis tinggi. Dapat bernapas udara, lebih toleran DO rendah.',
                'typical_harvest_days' => 270,
                'typical_harvest_weight_gram' => 700,
            ],
            [
                'slug' => 'tawes',
                'name' => 'Tawes',
                'scientific_name' => 'Barbonymus gonionotus',
                'description' => 'Tawes (tawes/lampam) umum di kolam polikultur bersama nila atau mas.',
                'typical_harvest_days' => 150,
                'typical_harvest_weight_gram' => 300,
            ],
            [
                'slug' => 'jelawat',
                'name' => 'Jelawat',
                'scientific_name' => 'Leptobarbus hoevenii',
                'description' => 'Jelawat (sultan fish) untuk pasar premium. Butuh air relatif jernih dan pakan berkualitas.',
                'typical_harvest_days' => 300,
                'typical_harvest_weight_gram' => 900,
            ],
            [
                'slug' => 'sepat',
                'name' => 'Sepat',
                'scientific_name' => 'Trichogaster trichopterus',
                'description' => 'Sepat rawa, cocok kolam dangkal. Tahan lingkungan dengan oksigen terbatas.',
                'typical_harvest_days' => 120,
                'typical_harvest_weight_gram' => 80,
            ],
            [
                'slug' => 'betok',
                'name' => 'Betok',
                'scientific_name' => 'Anabas testudineus',
                'description' => 'Betok (climbing perch) tahan kekeringan dan kualitas air fluktuatif.',
                'typical_harvest_days' => 150,
                'typical_harvest_weight_gram' => 100,
            ],
        ];

        foreach ($speciesList as $sp) {
            FishSpecies::updateOrCreate(
                ['slug' => $sp['slug']],
                $sp + ['is_active' => true]
            );
        }

        $parameters = [
            [
                'code' => 'ph',
                'name' => 'pH',
                'unit' => '',
                'min_value' => 6.0,
                'max_value' => 9.0,
                'ideal_min' => 6.5,
                'ideal_max' => 8.5,
                'weight' => 30,
                'description' => 'Cadangan global jika spesies belum punya parameter sendiri.',
            ],
            [
                'code' => 'temperature',
                'name' => 'Suhu',
                'unit' => '°C',
                'min_value' => 20,
                'max_value' => 35,
                'ideal_min' => 25,
                'ideal_max' => 32,
                'weight' => 30,
                'description' => 'Cadangan global suhu air.',
            ],
            [
                'code' => 'do',
                'name' => 'Oksigen Terlarut (DO)',
                'unit' => 'mg/L',
                'min_value' => 2,
                'max_value' => 12,
                'ideal_min' => 4,
                'ideal_max' => 8,
                'weight' => 40,
                'description' => 'Cadangan global oksigen terlarut.',
            ],
        ];

        foreach ($parameters as $param) {
            WaterQualityParameter::updateOrCreate(['code' => $param['code']], $param + ['is_active' => true]);
        }

        // Rentang ideal/aman berbeda per jenis ikan (nilai umum budidaya air tawar).
        $speciesWaterProfiles = [
            'nila' => [
                'ph' => [6.0, 9.0, 6.5, 8.5, 30],
                'temperature' => [22, 34, 25, 30, 30],
                'do' => [3.0, 12, 4.0, 8.0, 40],
            ],
            'lele' => [
                'ph' => [5.5, 9.0, 6.5, 8.0, 25],
                'temperature' => [22, 34, 26, 30, 30],
                'do' => [1.5, 10, 3.0, 6.0, 45], // lebih toleran DO rendah
            ],
            'gurame' => [
                'ph' => [6.0, 8.5, 6.5, 8.0, 30],
                'temperature' => [22, 32, 24, 30, 30],
                'do' => [2.5, 10, 4.0, 7.0, 40],
            ],
            'patin' => [
                'ph' => [6.0, 8.5, 6.5, 8.0, 30],
                'temperature' => [24, 33, 26, 30, 30],
                'do' => [2.5, 10, 3.5, 6.5, 45],
            ],
            'mas' => [
                'ph' => [6.5, 8.5, 7.0, 8.0, 30],
                'temperature' => [18, 30, 20, 28, 35], // lebih suka lebih sejuk
                'do' => [4.0, 12, 5.0, 8.0, 40], // sensitif DO rendah
            ],
            'mujair' => [
                'ph' => [5.5, 9.0, 6.5, 8.5, 30],
                'temperature' => [22, 34, 25, 32, 30],
                'do' => [2.5, 10, 3.5, 7.0, 40],
            ],
            'bawal' => [
                'ph' => [5.5, 8.5, 6.0, 8.0, 30],
                'temperature' => [24, 34, 26, 30, 30],
                'do' => [3.0, 10, 4.0, 7.0, 40],
            ],
            'gabus' => [
                'ph' => [5.0, 8.5, 5.5, 8.0, 25],
                'temperature' => [22, 34, 25, 32, 30],
                'do' => [1.0, 8, 2.0, 5.0, 35], // bernapas udara
            ],
            'tawes' => [
                'ph' => [6.0, 8.5, 6.5, 8.0, 30],
                'temperature' => [22, 32, 24, 30, 30],
                'do' => [3.0, 10, 4.0, 7.0, 40],
            ],
            'jelawat' => [
                'ph' => [6.5, 8.5, 6.8, 8.0, 30],
                'temperature' => [22, 32, 24, 30, 30],
                'do' => [4.0, 12, 5.0, 8.0, 45], // butuh air lebih baik
            ],
            'sepat' => [
                'ph' => [5.0, 8.5, 5.5, 8.0, 25],
                'temperature' => [22, 34, 24, 32, 30],
                'do' => [1.5, 8, 2.5, 5.5, 35],
            ],
            'betok' => [
                'ph' => [5.0, 9.0, 5.5, 8.5, 25],
                'temperature' => [22, 36, 24, 34, 30],
                'do' => [1.0, 8, 2.0, 5.0, 35], // sangat toleran
            ],
        ];

        $paramMeta = [
            'ph' => ['name' => 'pH', 'unit' => ''],
            'temperature' => ['name' => 'Suhu', 'unit' => '°C'],
            'do' => ['name' => 'Oksigen Terlarut (DO)', 'unit' => 'mg/L'],
        ];

        foreach ($speciesWaterProfiles as $slug => $profile) {
            $species = FishSpecies::where('slug', $slug)->first();
            if (! $species) {
                continue;
            }

            foreach ($profile as $code => [$min, $max, $idealMin, $idealMax, $weight]) {
                FishSpeciesParameter::updateOrCreate(
                    ['fish_species_id' => $species->id, 'code' => $code],
                    [
                        'name' => $paramMeta[$code]['name'],
                        'unit' => $paramMeta[$code]['unit'],
                        'min_value' => $min,
                        'max_value' => $max,
                        'ideal_min' => $idealMin,
                        'ideal_max' => $idealMax,
                        'weight' => $weight,
                        'is_active' => true,
                        'description' => "Rentang khusus untuk {$species->name}.",
                    ]
                );

                // Aturan per spesies dari batas ideal/aman
                $rulesForSpecies = [
                    [
                        'parameter_code' => $code,
                        'min_value' => null,
                        'max_value' => round($min - 0.01, 3),
                        'severity' => 'critical',
                        'title' => "{$paramMeta[$code]['name']} kritis rendah — {$species->name}",
                        'advice' => "Nilai {$paramMeta[$code]['name']} terlalu rendah untuk {$species->name}. Segera tindak: cek aerasi/sumber air, kurangi pakan, ukur ulang.",
                        'priority' => $code === 'do' ? 1 : ($code === 'ph' ? 10 : 20),
                    ],
                    [
                        'parameter_code' => $code,
                        'min_value' => null,
                        'max_value' => round($idealMin - 0.01, 3),
                        'severity' => 'warning',
                        'title' => "{$paramMeta[$code]['name']} di bawah ideal — {$species->name}",
                        'advice' => "{$paramMeta[$code]['name']} kurang ideal untuk {$species->name}. Pantau kolam dan sesuaikan pengelolaan air/pakan.",
                        'priority' => $code === 'do' ? 2 : ($code === 'ph' ? 11 : 21),
                    ],
                    [
                        'parameter_code' => $code,
                        'min_value' => round($idealMax + 0.01, 3),
                        'max_value' => null,
                        'severity' => 'warning',
                        'title' => "{$paramMeta[$code]['name']} di atas ideal — {$species->name}",
                        'advice' => "{$paramMeta[$code]['name']} di atas rentang ideal {$species->name}. Kurangi pakan sementara dan pantau perilaku ikan.",
                        'priority' => $code === 'do' ? 3 : ($code === 'ph' ? 12 : 22),
                    ],
                    [
                        'parameter_code' => $code,
                        'min_value' => round($max + 0.01, 3),
                        'max_value' => null,
                        'severity' => 'critical',
                        'title' => "{$paramMeta[$code]['name']} kritis tinggi — {$species->name}",
                        'advice' => "Nilai {$paramMeta[$code]['name']} di luar batas aman {$species->name}. Segera lakukan tindakan korektif dan ukur ulang.",
                        'priority' => $code === 'do' ? 4 : ($code === 'ph' ? 13 : 23),
                    ],
                ];

                foreach ($rulesForSpecies as $rule) {
                    RecommendationRule::updateOrCreate(
                        [
                            'fish_species_id' => $species->id,
                            'parameter_code' => $rule['parameter_code'],
                            'title' => $rule['title'],
                        ],
                        $rule + ['is_active' => true, 'phase' => null]
                    );
                }
            }
        }

        // Aturan global cadangan (untuk siklus tanpa spesies / fallback)
        $globalRules = [
            [
                'parameter_code' => 'do',
                'min_value' => null,
                'max_value' => 2.0,
                'severity' => 'critical',
                'title' => 'Kondisi kritis oksigen (global)',
                'advice' => 'Segera nyalakan aerasi penuh, kurangi pakan, dan siapkan ganti air darurat.',
                'priority' => 1,
            ],
            [
                'parameter_code' => 'ph',
                'min_value' => null,
                'max_value' => 6.49,
                'severity' => 'warning',
                'title' => 'pH terlalu rendah (global)',
                'advice' => 'Lakukan penggantian air secara bertahap dan periksa sumber air.',
                'priority' => 10,
            ],
            [
                'parameter_code' => 'temperature',
                'min_value' => 32.01,
                'max_value' => null,
                'severity' => 'warning',
                'title' => 'Suhu terlalu tinggi (global)',
                'advice' => 'Kurangi pakan sementara dan tingkatkan aerasi.',
                'priority' => 20,
            ],
        ];

        foreach ($globalRules as $rule) {
            RecommendationRule::updateOrCreate(
                [
                    'fish_species_id' => null,
                    'parameter_code' => $rule['parameter_code'],
                    'title' => $rule['title'],
                ],
                $rule + ['is_active' => true, 'phase' => null]
            );
        }

        Setting::updateOrCreate(
            ['key' => 'landing_hero'],
            [
                'group' => 'landing',
                'value' => [
                    'title' => 'PintarKolam',
                    'tagline' => 'Pantau Air, Atur Pakan, Panen Lebih Tepat',
                    'subtitle' => 'Platform digital budidaya ikan air tawar untuk pembudidaya Rejang Lebong.',
                ],
            ]
        );

        FeatureFlag::updateOrCreate(
            ['key' => 'landing_map'],
            ['name' => 'Peta Landing', 'is_enabled' => true, 'description' => 'Tampilkan peta sebaran di landing page']
        );

        Article::updateOrCreate(
            ['slug' => 'pentingnya-ukur-ph-rutin'],
            [
                'user_id' => $admin->id,
                'title' => 'Pentingnya Mengukur pH Air Secara Rutin',
                'excerpt' => 'pH yang tidak stabil dapat memicu stres ikan dan menurunkan nafsu makan.',
                'body' => "Mengukur pH secara rutin membantu pembudidaya mendeteksi perubahan kualitas air lebih awal.\n\nLakukan pengukuran pagi dan sore, catat hasilnya di PintarKolam, lalu ikuti rekomendasi tindakan yang diberikan sistem.",
                'is_published' => true,
                'published_at' => now(),
            ]
        );

        $this->seedPembudidayaDemo($admin, $farmerUser);
    }

    private function seedPembudidayaDemo(User $admin, User $farmerUser): void
    {
        $profileId = $farmerUser->farmerProfile?->id;
        $species = FishSpecies::query()->get()->keyBy('slug');
        $wq = app(\App\Services\WaterQualityService::class);
        $estimate = app(\App\Services\HarvestEstimateService::class);

        FarmerProfile::where('user_id', $farmerUser->id)->update([
            'shop_name' => 'Toko Ikan Budi Curup',
            'business_name' => 'Budidaya Budi Multi Spesies',
            'bio' => 'Pembudidaya nila, lele, gurame, patin, dan mas di Curup, Rejang Lebong.',
        ]);

        // --- Kolam beragam ---
        $pondDefs = [
            [
                'name' => 'Kolam Nila 1',
                'type' => 'terpal',
                'area_m2' => 80,
                'depth_m' => 1.2,
                'lat' => -3.4705,
                'lng' => 102.5207,
                'notes' => 'Kolam utama nila.',
            ],
            [
                'name' => 'Kolam Nila 2',
                'type' => 'beton',
                'area_m2' => 60,
                'depth_m' => 1.0,
                'lat' => -3.4708,
                'lng' => 102.5209,
                'notes' => 'Cadangan nila / polikultur.',
            ],
            [
                'name' => 'Kolam Lele',
                'type' => 'terpal',
                'area_m2' => 100,
                'depth_m' => 2.0,
                'lat' => -3.4710,
                'lng' => 102.5210,
                'notes' => 'Kolam lele padat tebar.',
            ],
            [
                'name' => 'Kolam Gurame',
                'type' => 'tanah',
                'area_m2' => 150,
                'depth_m' => 1.5,
                'lat' => -3.4715,
                'lng' => 102.5215,
                'notes' => 'Kolam tanah untuk gurame.',
            ],
            [
                'name' => 'Kolam Patin',
                'type' => 'bioflok',
                'area_m2' => 50,
                'depth_m' => 1.8,
                'lat' => -3.4720,
                'lng' => 102.5220,
                'notes' => 'Sistem bioflok patin.',
            ],
            [
                'name' => 'Kolam Mas Tradisional',
                'type' => 'tanah',
                'area_m2' => 200,
                'depth_m' => 1.3,
                'lat' => -3.4725,
                'lng' => 102.5225,
                'notes' => 'Kolam tradisional ikan mas.',
            ],
            [
                'name' => 'Kolam Polikultur',
                'type' => 'tanah',
                'area_m2' => 180,
                'depth_m' => 1.4,
                'lat' => -3.4730,
                'lng' => 102.5230,
                'notes' => 'Tawes + mujair.',
                'status' => 'maintenance',
            ],
        ];

        $ponds = [];
        foreach ($pondDefs as $def) {
            $pond = Pond::updateOrCreate(
                ['user_id' => $farmerUser->id, 'name' => $def['name']],
                [
                    'farmer_profile_id' => $profileId,
                    'type' => $def['type'],
                    'area_m2' => $def['area_m2'],
                    'depth_m' => $def['depth_m'],
                    'volume_m3' => round($def['area_m2'] * $def['depth_m'], 2),
                    'latitude' => $def['lat'],
                    'longitude' => $def['lng'],
                    'hide_exact_location' => true,
                    'status' => $def['status'] ?? 'active',
                    'notes' => $def['notes'],
                ]
            );
            $pond->ensurePublicToken();
            $ponds[$def['name']] = $pond;
        }

        // --- Siklus beragam ---
        $cycleDefs = [
            [
                'name' => 'Siklus Nila Maret 2026',
                'pond' => 'Kolam Nila 1',
                'species' => 'nila',
                'days_ago' => 40,
                'seed' => 2000,
                'initial_g' => 5,
                'source' => 'Balai Benih Lokal',
                'status' => 'active',
                'seed_cost' => 800000,
                'feed_cost' => 1200000,
                'deaths' => [['days' => 15, 'count' => 40, 'cause' => 'Stres awal']],
                'water' => ['ph' => 7.5, 'temp' => 27.5, 'do' => 5.5],
                'growth' => ['days' => 5, 'weight' => 85, 'alive' => 1960],
                'feed_logs' => true,
            ],
            [
                'name' => 'Nila Cadangan September',
                'pond' => 'Kolam Nila 2',
                'species' => 'nila',
                'days_ago' => 12,
                'seed' => 1500,
                'initial_g' => 4,
                'source' => 'Hatchery Curup',
                'status' => 'active',
                'seed_cost' => 600000,
                'feed_cost' => 350000,
                'deaths' => [['days' => 5, 'count' => 25, 'cause' => 'Sortir']],
                'water' => ['ph' => 7.1, 'temp' => 28, 'do' => 4.8],
                'growth' => null,
                'feed_logs' => true,
            ],
            [
                'name' => 'Lele 1',
                'pond' => 'Kolam Lele',
                'species' => 'lele',
                'days_ago' => 25,
                'seed' => 1000,
                'initial_g' => 3,
                'source' => 'Pembudidaya tetangga',
                'status' => 'active',
                'seed_cost' => 450000,
                'feed_cost' => 700000,
                'deaths' => [
                    ['days' => 10, 'count' => 30, 'cause' => 'Stres setelah tebar'],
                    ['days' => 3, 'count' => 20, 'cause' => 'Tidak diketahui'],
                ],
                'water' => ['ph' => 7.2, 'temp' => 28, 'do' => 3.8],
                'growth' => ['days' => 2, 'weight' => 45, 'alive' => 950],
                'feed_logs' => true,
            ],
            [
                'name' => 'Lele Intensif Batch 2',
                'pond' => 'Kolam Lele',
                'species' => 'lele',
                'days_ago' => 70,
                'seed' => 1200,
                'initial_g' => 5,
                'source' => 'Balai Benih',
                'status' => 'near_harvest',
                'seed_cost' => 520000,
                'feed_cost' => 1500000,
                'deaths' => [['days' => 40, 'count' => 80, 'cause' => 'Kualitas air']],
                'water' => ['ph' => 7.0, 'temp' => 29, 'do' => 4.2],
                'growth' => ['days' => 1, 'weight' => 140, 'alive' => 1120],
                'feed_logs' => true,
            ],
            [
                'name' => 'Gurame Premium 2026',
                'pond' => 'Kolam Gurame',
                'species' => 'gurame',
                'days_ago' => 90,
                'seed' => 800,
                'initial_g' => 15,
                'source' => 'Supplier Bengkulu',
                'status' => 'active',
                'seed_cost' => 1600000,
                'feed_cost' => 2100000,
                'deaths' => [['days' => 20, 'count' => 35, 'cause' => 'Predator']],
                'water' => ['ph' => 7.3, 'temp' => 26.5, 'do' => 5.0],
                'growth' => ['days' => 7, 'weight' => 180, 'alive' => 765],
                'feed_logs' => true,
            ],
            [
                'name' => 'Patin Bioflok A',
                'pond' => 'Kolam Patin',
                'species' => 'patin',
                'days_ago' => 55,
                'seed' => 2500,
                'initial_g' => 8,
                'source' => 'Hatchery Jambi',
                'status' => 'active',
                'seed_cost' => 1100000,
                'feed_cost' => 2800000,
                'deaths' => [['days' => 14, 'count' => 120, 'cause' => 'Padat tebar']],
                'water' => ['ph' => 7.4, 'temp' => 28.5, 'do' => 4.0],
                'growth' => ['days' => 4, 'weight' => 220, 'alive' => 2380],
                'feed_logs' => true,
            ],
            [
                'name' => 'Ikan Mas Musim Hujan',
                'pond' => 'Kolam Mas Tradisional',
                'species' => 'mas',
                'days_ago' => 100,
                'seed' => 900,
                'initial_g' => 20,
                'source' => 'Benih lokal',
                'status' => 'near_harvest',
                'seed_cost' => 700000,
                'feed_cost' => 1900000,
                'deaths' => [['days' => 30, 'count' => 45, 'cause' => 'Suhu dingin']],
                'water' => ['ph' => 7.6, 'temp' => 24.5, 'do' => 6.2],
                'growth' => ['days' => 3, 'weight' => 520, 'alive' => 855],
                'feed_logs' => true,
            ],
            [
                'name' => 'Tawes Polikultur',
                'pond' => 'Kolam Polikultur',
                'species' => 'tawes',
                'days_ago' => 200,
                'seed' => 1100,
                'initial_g' => 10,
                'source' => 'Balai Benih',
                'status' => 'completed',
                'seed_cost' => 400000,
                'feed_cost' => 900000,
                'estimated_revenue' => 4500000,
                'deaths' => [['days' => 100, 'count' => 90, 'cause' => 'Panen bertahap']],
                'water' => null,
                'growth' => null,
                'feed_logs' => false,
            ],
            [
                'name' => 'Mujair Sampingan',
                'pond' => 'Kolam Polikultur',
                'species' => 'mujair',
                'days_ago' => 180,
                'seed' => 700,
                'initial_g' => 8,
                'source' => 'Lokal',
                'status' => 'completed',
                'seed_cost' => 250000,
                'feed_cost' => 500000,
                'estimated_revenue' => 2100000,
                'deaths' => [],
                'water' => null,
                'growth' => null,
                'feed_logs' => false,
            ],
            [
                'name' => 'Bawal Persiapan',
                'pond' => 'Kolam Nila 2',
                'species' => 'bawal',
                'days_ago' => 5,
                'seed' => 600,
                'initial_g' => 12,
                'source' => 'Supplier Padang',
                'status' => 'preparation',
                'seed_cost' => 900000,
                'feed_cost' => 100000,
                'deaths' => [],
                'water' => ['ph' => 6.8, 'temp' => 27, 'do' => 5.2],
                'growth' => null,
                'feed_logs' => false,
            ],
        ];

        $createdCycles = [];
        foreach ($cycleDefs as $def) {
            $sp = $species->get($def['species']);
            $pond = $ponds[$def['pond']] ?? null;
            if (! $sp || ! $pond) {
                continue;
            }

            $stocking = now()->subDays($def['days_ago']);
            $cycle = CultivationCycle::updateOrCreate(
                ['user_id' => $farmerUser->id, 'name' => $def['name']],
                [
                    'pond_id' => $pond->id,
                    'fish_species_id' => $sp->id,
                    'stocking_date' => $stocking->toDateString(),
                    'seed_count' => $def['seed'],
                    'initial_size_gram' => $def['initial_g'],
                    'seed_source' => $def['source'],
                    'target_size_gram' => $sp->typical_harvest_weight_gram,
                    'target_harvest_date' => $stocking->copy()->addDays((int) $sp->typical_harvest_days)->toDateString(),
                    'status' => $def['status'],
                    'seed_cost' => $def['seed_cost'] ?? 0,
                    'feed_cost' => $def['feed_cost'] ?? 0,
                    'estimated_revenue' => $def['estimated_revenue'] ?? 0,
                    'notes' => 'Data seeder demo '.$sp->name,
                ]
            );
            $createdCycles[] = $cycle;

            \App\Models\FeedingSchedule::updateOrCreate(
                ['cultivation_cycle_id' => $cycle->id, 'feed_time' => '07:00:00'],
                ['feed_type' => 'Pelet', 'amount_kg' => max(0.15, round($cycle->seed_count * 0.02 / 1000, 3)), 'is_active' => true]
            );
            \App\Models\FeedingSchedule::updateOrCreate(
                ['cultivation_cycle_id' => $cycle->id, 'feed_time' => '17:00:00'],
                ['feed_type' => 'Pelet terapung', 'amount_kg' => max(0.15, round($cycle->seed_count * 0.02 / 1000, 3)), 'is_active' => true]
            );

            foreach ($def['deaths'] as $death) {
                \App\Models\MortalityLog::updateOrCreate(
                    [
                        'cultivation_cycle_id' => $cycle->id,
                        'recorded_at' => now()->subDays($death['days'])->toDateString(),
                    ],
                    [
                        'user_id' => $farmerUser->id,
                        'death_count' => $death['count'],
                        'suspected_cause' => $death['cause'],
                    ]
                );
            }

            if (! empty($def['feed_logs'])) {
                foreach ([1, 2, 3] as $d) {
                    foreach ([7, 17] as $hour) {
                        \App\Models\FeedingLog::updateOrCreate(
                            [
                                'cultivation_cycle_id' => $cycle->id,
                                'fed_at' => now()->subDays($d)->setTime($hour, 0),
                            ],
                            [
                                'user_id' => $farmerUser->id,
                                'feed_type' => $hour === 7 ? 'Pelet' : 'Pelet terapung',
                                'amount_kg' => round(0.2 + ($cycle->seed_count / 10000), 3),
                                'leftover_kg' => 0.01,
                                'status' => 'done',
                            ]
                        );
                    }
                }
            }

            if (! empty($def['growth'])) {
                $g = $def['growth'];
                \App\Models\GrowthRecord::updateOrCreate(
                    [
                        'cultivation_cycle_id' => $cycle->id,
                        'sampled_at' => now()->subDays($g['days'])->toDateString(),
                    ],
                    [
                        'user_id' => $farmerUser->id,
                        'avg_weight_gram' => $g['weight'],
                        'sample_count' => 25,
                        'estimated_alive' => $g['alive'],
                    ]
                );
            }

            if (! empty($def['water'])) {
                $w = $def['water'];
                $log = \App\Models\WaterQualityLog::updateOrCreate(
                    [
                        'cultivation_cycle_id' => $cycle->id,
                        'measured_at' => now()->subHours(max(2, $def['days_ago'] % 20)),
                    ],
                    [
                        'pond_id' => $pond->id,
                        'user_id' => $farmerUser->id,
                        'ph' => $w['ph'],
                        'temperature_c' => $w['temp'],
                        'dissolved_oxygen' => $w['do'],
                        'visual_condition' => 'jernih',
                        'odor' => 'normal',
                        'notes' => 'Seeder pengukuran '.$cycle->name,
                    ]
                );
                $wq->processLog($log->fresh(['cycle.fishSpecies']));
            }

            if (in_array($def['status'], ['active', 'near_harvest', 'preparation'], true)) {
                if (! empty($def['growth'])) {
                    $estimate->calculate($cycle->fresh(['fishSpecies']));
                } else {
                    $estimate->bootstrapForNewCycle($cycle->fresh(['fishSpecies']));
                }
            }
        }

        // Katalog produk beragam
        $productDefs = [
            ['title' => 'Nila Segar Curup', 'species' => 'nila', 'cycle' => 'Siklus Nila Maret 2026', 'size' => '2-3 ekor/kg', 'stock' => 50, 'price' => 28000],
            ['title' => 'Lele Segar Curup', 'species' => 'lele', 'cycle' => 'Lele 1', 'size' => '6-8 ekor/kg', 'stock' => 30, 'price' => 22000],
            ['title' => 'Gurame Siap Panen', 'species' => 'gurame', 'cycle' => 'Gurame Premium 2026', 'size' => '1-2 ekor/kg', 'stock' => 20, 'price' => 45000],
            ['title' => 'Patin Fillet Lokal', 'species' => 'patin', 'cycle' => 'Patin Bioflok A', 'size' => '1 ekor/kg', 'stock' => 40, 'price' => 32000],
            ['title' => 'Ikan Mas Segar', 'species' => 'mas', 'cycle' => 'Ikan Mas Musim Hujan', 'size' => '1-2 ekor/kg', 'stock' => 25, 'price' => 35000],
            ['title' => 'Tawes Hasil Panen', 'species' => 'tawes', 'cycle' => 'Tawes Polikultur', 'size' => '3-4 ekor/kg', 'stock' => 15, 'price' => 25000],
        ];

        $cyclesByName = collect($createdCycles)->keyBy('name');
        foreach ($productDefs as $p) {
            $sp = $species->get($p['species']);
            $cycle = $cyclesByName->get($p['cycle']);
            if (! $sp) {
                continue;
            }
            Product::updateOrCreate(
                ['user_id' => $farmerUser->id, 'title' => $p['title']],
                [
                    'farmer_profile_id' => $profileId,
                    'fish_species_id' => $sp->id,
                    'cultivation_cycle_id' => $cycle?->id,
                    'size_label' => $p['size'],
                    'stock_kg' => $p['stock'],
                    'price_unit' => 'kg',
                    'price' => $p['price'],
                    'min_order' => 2,
                    'location_label' => 'Curup, Rejang Lebong',
                    'description' => $p['title'].' dari budidaya lokal Budi.',
                    'whatsapp' => '6281298765432',
                    'availability' => 'available',
                    'moderation_status' => 'approved',
                    'is_published' => true,
                ]
            );
        }

        // Pembudidaya kedua — agar daftar petani lebih beragam
        $farmer2 = User::updateOrCreate(
            ['email' => 'andi@pintarkolam.id'],
            [
                'name' => 'Andi Pembudidaya',
                'phone' => '081355566677',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
                'is_active' => true,
            ]
        );
        $farmer2->syncRoles(['pembudidaya']);
        NotificationPreference::firstOrCreate(['user_id' => $farmer2->id]);
        $profile2 = FarmerProfile::updateOrCreate(
            ['user_id' => $farmer2->id],
            [
                'business_name' => 'Kolam Andi Lubuk Linggau',
                'shop_name' => 'Ikan Segar Andi',
                'owner_name' => 'Andi Pembudidaya',
                'village' => 'Taba Pingin',
                'district' => 'Lubuk Linggau Utara',
                'regency' => 'Lubuk Linggau',
                'province' => 'Sumatera Selatan',
                'latitude' => -3.3000,
                'longitude' => 102.8600,
                'hide_exact_location' => true,
                'whatsapp' => '6281355566677',
                'bio' => 'Spesialis lele dan patin.',
                'verification_status' => 'approved',
                'storefront_status' => 'approved',
                'verified_at' => now(),
                'verified_by' => $admin->id,
            ]
        );

        $pondAndi = Pond::updateOrCreate(
            ['user_id' => $farmer2->id, 'name' => 'Kolam Lele Andi'],
            [
                'farmer_profile_id' => $profile2->id,
                'type' => 'terpal',
                'area_m2' => 70,
                'depth_m' => 1.5,
                'volume_m3' => 105,
                'latitude' => -3.3001,
                'longitude' => 102.8601,
                'hide_exact_location' => true,
                'status' => 'active',
                'notes' => 'Kolam Andi untuk demo multi-akun.',
            ]
        );
        $pondAndi->ensurePublicToken();

        $lele = $species->get('lele');
        if ($lele) {
            $cycleAndi = CultivationCycle::updateOrCreate(
                ['user_id' => $farmer2->id, 'name' => 'Lele Andi Batch 1'],
                [
                    'pond_id' => $pondAndi->id,
                    'fish_species_id' => $lele->id,
                    'stocking_date' => now()->subDays(18)->toDateString(),
                    'seed_count' => 1800,
                    'initial_size_gram' => 4,
                    'seed_source' => 'Hatchery lokal',
                    'target_size_gram' => $lele->typical_harvest_weight_gram,
                    'target_harvest_date' => now()->subDays(18)->addDays((int) $lele->typical_harvest_days)->toDateString(),
                    'status' => 'active',
                    'seed_cost' => 700000,
                    'feed_cost' => 900000,
                ]
            );
            $estimate->bootstrapForNewCycle($cycleAndi->fresh(['fishSpecies']));

            Product::updateOrCreate(
                ['user_id' => $farmer2->id, 'title' => 'Lele Andi Segar'],
                [
                    'farmer_profile_id' => $profile2->id,
                    'fish_species_id' => $lele->id,
                    'cultivation_cycle_id' => $cycleAndi->id,
                    'size_label' => '7-9 ekor/kg',
                    'stock_kg' => 35,
                    'price_unit' => 'kg',
                    'price' => 21000,
                    'min_order' => 2,
                    'location_label' => 'Lubuk Linggau',
                    'description' => 'Lele segar dari Lubuk Linggau.',
                    'whatsapp' => '6281355566677',
                    'availability' => 'available',
                    'moderation_status' => 'approved',
                    'is_published' => true,
                ]
            );
        }
    }
}
