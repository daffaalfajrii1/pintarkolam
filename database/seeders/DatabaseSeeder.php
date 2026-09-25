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

        $nila = FishSpecies::where('slug', 'nila')->first();
        $pond = Pond::updateOrCreate(
            ['user_id' => $farmerUser->id, 'name' => 'Kolam Nila 1'],
            [
                'farmer_profile_id' => $farmerUser->farmerProfile?->id,
                'type' => 'terpal',
                'area_m2' => 80,
                'depth_m' => 1.2,
                'volume_m3' => 96,
                'latitude' => -3.4705,
                'longitude' => 102.5207,
                'hide_exact_location' => true,
                'status' => 'active',
                'notes' => 'Kolam contoh untuk demo MVP',
            ]
        );

        $cycle = CultivationCycle::updateOrCreate(
            ['user_id' => $farmerUser->id, 'name' => 'Siklus Nila Maret 2026'],
            [
                'pond_id' => $pond->id,
                'fish_species_id' => $nila->id,
                'stocking_date' => now()->subDays(40)->toDateString(),
                'seed_count' => 2000,
                'initial_size_gram' => 5,
                'seed_source' => 'Balai Benih Lokal',
                'target_size_gram' => 250,
                'target_harvest_date' => now()->addDays(80)->toDateString(),
                'status' => 'active',
            ]
        );

        Product::updateOrCreate(
            ['user_id' => $farmerUser->id, 'title' => 'Nila Segar Curup'],
            [
                'farmer_profile_id' => $farmerUser->farmerProfile?->id,
                'fish_species_id' => $nila->id,
                'cultivation_cycle_id' => $cycle->id,
                'size_label' => '2-3 ekor/kg',
                'stock_kg' => 50,
                'price_unit' => 'kg',
                'price' => 28000,
                'min_order' => 2,
                'location_label' => 'Curup, Rejang Lebong',
                'description' => 'Nila budidaya lokal, siap panen.',
                'whatsapp' => '6281298765432',
                'availability' => 'available',
                'moderation_status' => 'approved',
                'is_published' => true,
            ]
        );

        \App\Models\FeedingSchedule::updateOrCreate(
            ['cultivation_cycle_id' => $cycle->id, 'feed_time' => '07:00:00'],
            ['feed_type' => 'Pelet', 'amount_kg' => 0.4, 'is_active' => true]
        );
        \App\Models\FeedingSchedule::updateOrCreate(
            ['cultivation_cycle_id' => $cycle->id, 'feed_time' => '17:00:00'],
            ['feed_type' => 'Pelet', 'amount_kg' => 0.4, 'is_active' => true]
        );
    }
}
