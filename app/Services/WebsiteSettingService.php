<?php

namespace App\Services;

use App\Models\Setting;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class WebsiteSettingService
{
    public const KEYS = [
        'site_branding' => [
            'site_name' => 'PintarKolam',
            'tagline' => 'Pantau Air, Atur Pakan, Panen Lebih Tepat',
            'logo_path' => null,
            'favicon_path' => null,
        ],
        'site_header' => [
            'hours' => 'Senin - Jumat : 08:00 - 16:00',
            'location' => 'Kabupaten Rejang Lebong, Bengkulu',
            'phone' => 'admin@pintarkolam.id',
            'facebook' => 'https://www.facebook.com/',
            'instagram' => 'https://www.instagram.com/',
            'youtube' => 'https://www.youtube.com/',
        ],
        'site_footer' => [
            'about' => 'Sistem informasi budidaya ikan air tawar untuk Kabupaten Rejang Lebong.',
            'email' => 'admin@pintarkolam.id',
            'domain' => 'pintarkolam.rejanglebongkab.go.id',
            'copyright' => 'PintarKolam — Kab. Rejang Lebong',
        ],
        'landing_hero' => [
            'title' => 'PintarKolam',
            'tagline' => 'Pantau Air, Atur Pakan, Panen Lebih Tepat',
            'subtitle' => 'Sistem informasi budidaya ikan air tawar Rejang Lebong',
        ],
        'landing_carousel' => [
            'slides' => [],
        ],
        'landing_banners' => [
            'items' => [],
        ],
        'landing_sections' => [
            'why_choose_image' => null,
            'why_choose_bg' => null,
        ],
    ];

    public function get(string $key, ?array $default = null): array
    {
        $fallback = $default ?? (self::KEYS[$key] ?? []);
        $value = Setting::query()->where('key', $key)->value('value');

        return is_array($value) ? array_replace_recursive($fallback, $value) : $fallback;
    }

    public function put(string $key, array $value, string $group = 'website'): void
    {
        Setting::query()->updateOrCreate(
            ['key' => $key],
            ['value' => $value, 'group' => $group]
        );
    }

    public function storeUpload(?UploadedFile $file, string $folder): ?string
    {
        if (! $file) {
            return null;
        }

        return $file->store("uploads/{$folder}", 'public');
    }

    public function allWebsite(): array
    {
        $data = [];
        foreach (array_keys(self::KEYS) as $key) {
            $data[$key] = $this->get($key);
        }

        return $data;
    }
}
